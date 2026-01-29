/* =========================================================
   ESTIMATOR ANALYTICS TRACKING
   ---------------------------------------------------------
   Lightweight event tracking for estimator funnel
   Stores all data in localStorage
   No external dependencies
========================================================= */

const ANALYTICS_KEY = 'jcp_estimator_analytics';
const BATCH_INTERVAL = 5000; // Save every 5 seconds

class EstimatorAnalytics {
  constructor() {
    this.currentSession = null;
    this.batchTimer = null;
    this.pendingEvents = [];
    this.initialized = false;
  }

  /* =========================================================
     INITIALIZATION
  ========================================================= */

  init(metadata = {}) {
    if (this.initialized) return;
    
    try {
      // Create or resume session
      this.currentSession = this.createSession(metadata);
      
      // Track start event
      this.track('estimator_started', {
        step: 'init',
        stepIndex: -1
      });
      
      // Setup auto-save
      this.startBatchSaving();
      
      // Save on page unload
      window.addEventListener('beforeunload', () => this.flush());
      
      this.initialized = true;
      console.log('📊 Analytics initialized:', this.currentSession.sessionId);
    } catch (e) {
      console.warn('Analytics init failed (non-blocking):', e);
    }
  }

  createSession(metadata) {
    return {
      sessionId: this.generateUUID(),
      startedAt: new Date().toISOString(),
      currentStep: 0,
      currentStepStartedAt: new Date().toISOString(),
      contractorId: metadata.contractorId || null,
      source: metadata.source || 'direct',
      events: []
    };
  }

  /* =========================================================
     EVENT TRACKING
  ========================================================= */

  track(eventType, data = {}) {
    if (!this.currentSession) return;

    try {
      const event = {
        type: eventType,
        timestamp: Date.now(),
        step: data.step || 'unknown',
        stepIndex: data.stepIndex ?? -1,
        duration: this.calculateStepDuration(),
        metadata: data.metadata || {}
      };

      this.currentSession.events.push(event);
      this.pendingEvents.push(event);

      // Update current step tracking
      if (eventType.includes('_viewed')) {
        this.currentSession.currentStep = event.stepIndex;
        this.currentSession.currentStepStartedAt = new Date().toISOString();
      }

      console.log('📊 Event tracked:', eventType, data.step);
    } catch (e) {
      console.warn('Event tracking failed (non-blocking):', e);
    }
  }

  calculateStepDuration() {
    if (!this.currentSession || !this.currentSession.currentStepStartedAt) {
      return 0;
    }
    
    const started = new Date(this.currentSession.currentStepStartedAt).getTime();
    const now = Date.now();
    return now - started;
  }

  /* =========================================================
     SESSION MANAGEMENT
  ========================================================= */

  completeSession(requestId = null) {
    if (!this.currentSession) return;

    try {
      this.currentSession.completedAt = new Date().toISOString();
      this.currentSession.submitted = !!requestId;
      this.currentSession.requestId = requestId;
      
      const started = new Date(this.currentSession.startedAt).getTime();
      const completed = new Date(this.currentSession.completedAt).getTime();
      this.currentSession.totalDuration = completed - started;

      // Move to completed sessions
      this.archiveSession();
      
      console.log('✅ Session completed:', this.currentSession.sessionId);
    } catch (e) {
      console.warn('Session completion failed (non-blocking):', e);
    }
  }

  archiveSession() {
    try {
      const data = this.loadData();
      
      // Remove from active
      data.activeSessions = data.activeSessions.filter(
        s => s.sessionId !== this.currentSession.sessionId
      );
      
      // Add to completed
      data.completedSessions.push({...this.currentSession});
      
      // Update aggregated stats
      this.updateAggregatedStats(data);
      
      this.saveData(data);
      this.currentSession = null;
    } catch (e) {
      console.warn('Session archival failed (non-blocking):', e);
    }
  }

  /* =========================================================
     DATA PERSISTENCE
  ========================================================= */

  startBatchSaving() {
    this.batchTimer = setInterval(() => {
      this.flush();
    }, BATCH_INTERVAL);
  }

  flush() {
    if (!this.currentSession || this.pendingEvents.length === 0) return;

    try {
      const data = this.loadData();
      
      // Update or add current session
      const existingIndex = data.activeSessions.findIndex(
        s => s.sessionId === this.currentSession.sessionId
      );
      
      if (existingIndex >= 0) {
        data.activeSessions[existingIndex] = {...this.currentSession};
      } else {
        data.activeSessions.push({...this.currentSession});
      }
      
      this.saveData(data);
      this.pendingEvents = [];
      
      console.log('💾 Analytics saved');
    } catch (e) {
      console.warn('Analytics save failed (non-blocking):', e);
    }
  }

  loadData() {
    try {
      const raw = localStorage.getItem(ANALYTICS_KEY);
      if (!raw) return this.getDefaultData();
      
      const data = JSON.parse(raw);
      return {
        ...this.getDefaultData(),
        ...data
      };
    } catch (e) {
      console.warn('Analytics load failed, using defaults:', e);
      return this.getDefaultData();
    }
  }

  saveData(data) {
    try {
      data.lastUpdated = new Date().toISOString();
      localStorage.setItem(ANALYTICS_KEY, JSON.stringify(data));
    } catch (e) {
      console.warn('Analytics save to localStorage failed:', e);
    }
  }

  getDefaultData() {
    return {
      version: "1.0",
      lastUpdated: new Date().toISOString(),
      activeSessions: [],
      completedSessions: [],
      stepStats: this.getDefaultStepStats(),
      summary: {
        totalSessions: 0,
        completedSessions: 0,
        submittedRequests: 0,
        completionRate: 0,
        avgCompletionTime: 0,
        mostCommonDropoff: null
      }
    };
  }

  getDefaultStepStats() {
    const steps = [
      'visual_intake',
      'address',
      'property_view',
      'project_details',
      'estimate',
      'schedule'
    ];

    const stats = {};
    steps.forEach(step => {
      stats[step] = {
        viewed: 0,
        completed: 0,
        skipped: 0,
        dropped: 0,
        avgDuration: 0,
        dropRate: 0
      };
    });
    
    return stats;
  }

  /* =========================================================
     AGGREGATED STATS
  ========================================================= */

  updateAggregatedStats(data) {
    try {
      // Reset step stats
      const stepStats = this.getDefaultStepStats();
      const stepDurations = {};
      const stepOrder = ['visual_intake', 'address', 'property_view', 'project_details', 'estimate', 'schedule'];
      
      // Process all completed sessions
      const allSessions = [...data.completedSessions];
      
      allSessions.forEach(session => {
        session.events.forEach(event => {
          const step = event.step;
          if (!stepStats[step]) return;
          
          // Track views
          if (event.type.includes('_viewed')) {
            stepStats[step].viewed++;
          }
          
          // Track completions
          if (event.type.includes('_completed')) {
            stepStats[step].completed++;
          }
          
          // Track skips
          if (event.type.includes('_skipped')) {
            stepStats[step].skipped++;
          }
          
          // Track durations
          if (event.duration > 0) {
            if (!stepDurations[step]) stepDurations[step] = [];
            stepDurations[step].push(event.duration);
          }
        });
        
        // Track true drops: Only mark the LAST step reached if session wasn't submitted
        if (!session.submitted && session.events.length > 0) {
          // Find the last step that was viewed
          const viewedEvents = session.events.filter(e => e.type.includes('_viewed'));
          if (viewedEvents.length > 0) {
            const lastViewed = viewedEvents[viewedEvents.length - 1];
            if (stepStats[lastViewed.step]) {
              stepStats[lastViewed.step].dropped++;
            }
          }
        }
      });
      
      // Calculate averages and drop rates
      Object.keys(stepStats).forEach(step => {
        const stats = stepStats[step];
        
        // Average duration
        if (stepDurations[step] && stepDurations[step].length > 0) {
          const sum = stepDurations[step].reduce((a, b) => a + b, 0);
          stats.avgDuration = Math.round(sum / stepDurations[step].length);
        }
        
        // Drop rate (based on actual abandonment, not incomplete steps)
        if (stats.viewed > 0) {
          stats.dropRate = stats.dropped / stats.viewed;
        }
      });
      
      data.stepStats = stepStats;
      
      // Update summary
      const totalSessions = allSessions.length;
      const completedSessions = allSessions.filter(s => s.completedAt).length;
      const submittedSessions = allSessions.filter(s => s.submitted).length;
      
      let totalDuration = 0;
      let durationCount = 0;
      allSessions.forEach(s => {
        if (s.totalDuration) {
          totalDuration += s.totalDuration;
          durationCount++;
        }
      });
      
      // Find most common dropoff
      let maxDropoff = 0;
      let maxDropoffStep = null;
      Object.entries(stepStats).forEach(([step, stats]) => {
        if (stats.dropped > maxDropoff) {
          maxDropoff = stats.dropped;
          maxDropoffStep = step;
        }
      });
      
      data.summary = {
        totalSessions,
        completedSessions,
        submittedRequests: submittedSessions,
        completionRate: totalSessions > 0 ? submittedSessions / totalSessions : 0,
        avgCompletionTime: durationCount > 0 ? Math.round(totalDuration / durationCount) : 0,
        mostCommonDropoff: maxDropoffStep
      };
      
    } catch (e) {
      console.warn('Stats aggregation failed (non-blocking):', e);
    }
  }

  /* =========================================================
     UTILITIES
  ========================================================= */

  generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
      const r = Math.random() * 16 | 0;
      const v = c === 'x' ? r : (r & 0x3 | 0x8);
      return v.toString(16);
    });
  }

  /* =========================================================
     PUBLIC API
  ========================================================= */

  static getInstance() {
    if (!window.__estimatorAnalytics) {
      window.__estimatorAnalytics = new EstimatorAnalytics();
    }
    return window.__estimatorAnalytics;
  }

  static track(eventType, data) {
    const instance = EstimatorAnalytics.getInstance();
    instance.track(eventType, data);
  }

  static init(metadata) {
    const instance = EstimatorAnalytics.getInstance();
    instance.init(metadata);
  }

  static complete(requestId) {
    const instance = EstimatorAnalytics.getInstance();
    instance.completeSession(requestId);
  }

  static reset() {
    try {
      localStorage.removeItem(ANALYTICS_KEY);
      console.log('🗑️ Analytics data cleared');
    } catch (e) {
      console.warn('Analytics reset failed:', e);
    }
  }
}

// Export for use in estimator
if (typeof module !== 'undefined' && module.exports) {
  module.exports = EstimatorAnalytics;
}
