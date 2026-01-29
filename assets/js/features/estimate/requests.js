/* =========================================================
   ESTIMATOR REQUESTS MANAGEMENT
   ---------------------------------------------------------
   CRUD operations for estimate requests
   Status management and history tracking
========================================================= */

const REQUESTS_KEY = 'jcp_estimator_requests';

class RequestsManager {
  constructor() {
    this.requests = [];
    this.loaded = false;
  }

  /* =========================================================
     INITIALIZATION
  ========================================================= */

  load() {
    try {
      const raw = localStorage.getItem(REQUESTS_KEY);
      if (raw) {
        this.requests = JSON.parse(raw);
      } else {
        this.requests = [];
      }
      this.loaded = true;
      console.log(`📬 Loaded ${this.requests.length} requests`);
      return this.requests;
    } catch (e) {
      console.warn('Requests load failed:', e);
      this.requests = [];
      return [];
    }
  }

  save() {
    try {
      localStorage.setItem(REQUESTS_KEY, JSON.stringify(this.requests));
      console.log('💾 Requests saved');
    } catch (e) {
      console.warn('Requests save failed:', e);
    }
  }

  /* =========================================================
     CREATE
  ========================================================= */

  createRequest(estimatorData, sessionId = null) {
    try {
      const request = {
        // Identity
        requestId: this.generateUUID(),
        sessionId: sessionId,
        
        // Status
        status: 'new',
        submittedAt: new Date().toISOString(),
        updatedAt: new Date().toISOString(),
        
        // Property Info
        address: estimatorData.address || {},
        
        // Project Details
        serviceType: estimatorData.serviceType || 'Unknown',
        scope: estimatorData.scope || 'Not specified',
        complexity: estimatorData.complexity || 'Medium',
        aiDescription: estimatorData.aiDescription || '',
        
        // Estimate Selection
        estimate: estimatorData.estimate || {},
        
        // Scheduling
        schedule: estimatorData.schedule || {},
        
        // Property Data
        property: estimatorData.property || {},
        
        // Contractor Context
        contractor: estimatorData.contractor || {},
        
        // Internal Tracking
        priority: estimatorData.priority || 'medium',
        assignee: estimatorData.assignee || { name: 'Unassigned', initials: '—' },
        tags: estimatorData.tags || [],
        slaHours: estimatorData.slaHours || 24,
        responseTimeHours: estimatorData.responseTimeHours || null,
        source: estimatorData.source || 'Website',
        statusHistory: [
          {
            status: 'new',
            timestamp: new Date().toISOString(),
            note: 'Request submitted'
          }
        ],
        internalNotes: '',
        
        // Analytics
        completionTime: estimatorData.completionTime || 0,
        stepDurations: estimatorData.stepDurations || {}
      };

      this.requests.unshift(request); // Add to beginning (newest first)
      this.save();
      
      console.log('✅ Request created:', request.requestId);
      return request;
    } catch (e) {
      console.error('Request creation failed:', e);
      return null;
    }
  }

  /* =========================================================
     READ
  ========================================================= */

  getAllRequests() {
    if (!this.loaded) this.load();
    return [...this.requests]; // Return copy
  }

  getRequestById(requestId) {
    if (!this.loaded) this.load();
    return this.requests.find(r => r.requestId === requestId);
  }

  getRequestsByStatus(status) {
    if (!this.loaded) this.load();
    return this.requests.filter(r => r.status === status);
  }

  getRequestsByContractor(contractorId) {
    if (!this.loaded) this.load();
    return this.requests.filter(r => r.contractor.id === contractorId);
  }

  /* =========================================================
     UPDATE
  ========================================================= */

  updateRequestStatus(requestId, newStatus, note = '') {
    try {
      const request = this.getRequestById(requestId);
      if (!request) {
        console.warn('Request not found:', requestId);
        return false;
      }

      request.status = newStatus;
      request.updatedAt = new Date().toISOString();
      
      // Add to history
      request.statusHistory.push({
        status: newStatus,
        timestamp: new Date().toISOString(),
        note: note || `Status changed to ${newStatus}`
      });

      this.save();
      console.log(`✅ Request ${requestId} → ${newStatus}`);
      return true;
    } catch (e) {
      console.error('Status update failed:', e);
      return false;
    }
  }

  updateInternalNotes(requestId, notes) {
    try {
      const request = this.getRequestById(requestId);
      if (!request) return false;

      request.internalNotes = notes;
      request.updatedAt = new Date().toISOString();
      
      this.save();
      return true;
    } catch (e) {
      console.error('Notes update failed:', e);
      return false;
    }
  }

  /* =========================================================
     ACTIONS
  ========================================================= */

  approveRequest(requestId, note = '') {
    return this.updateRequestStatus(requestId, 'approved', note || 'Request approved');
  }

  declineRequest(requestId, note = '') {
    return this.updateRequestStatus(requestId, 'declined', note || 'Request declined');
  }

  requestFollowup(requestId, note = '') {
    return this.updateRequestStatus(requestId, 'followup', note || 'Requested more information');
  }

  /* =========================================================
     STATISTICS
  ========================================================= */

  getStats() {
    if (!this.loaded) this.load();

    const total = this.requests.length;
    const byStatus = {
      new: this.requests.filter(r => r.status === 'new').length,
      approved: this.requests.filter(r => r.status === 'approved').length,
      declined: this.requests.filter(r => r.status === 'declined').length,
      followup: this.requests.filter(r => r.status === 'followup').length
    };

    return {
      total,
      byStatus,
      approvalRate: total > 0 ? byStatus.approved / total : 0,
      declineRate: total > 0 ? byStatus.declined / total : 0
    };
  }

  /* =========================================================
     DEMO UTILITIES
  ========================================================= */

  seedDemoData(count = 5) {
    try {
      const demoRequests = [];
      
      const addresses = [
        { full: '124 Conch Street, Bikini Bottom, BB 00001', city: 'Bikini Bottom' },
        { full: '456 Elm Street, Springfield, IL 62701', city: 'Springfield' },
        { full: '789 Oak Avenue, Metropolis, NY 10001', city: 'Metropolis' },
        { full: '321 Pine Road, Gotham, NJ 07001', city: 'Gotham' },
        { full: '555 Maple Drive, Central City, MO 64101', city: 'Central City' }
      ];
      
      const services = ['Roofing', 'HVAC', 'Plumbing', 'Electrical', 'Landscaping'];
      const scopes = ['Full replacement', 'Repair', 'Installation', 'Maintenance', 'Upgrade'];
      const tiers = ['good', 'better', 'best'];
      const complexities = ['Low', 'Medium', 'High'];
      const priorities = ['high', 'medium', 'low'];
      const assignees = [
        { name: 'Trevor Eddy', initials: 'TE' },
        { name: 'Jordan Lee', initials: 'JL' },
        { name: 'Avery Chen', initials: 'AC' },
        { name: 'Unassigned', initials: '—' }
      ];
      const tags = ['New lead', 'High value', 'Follow-up', 'Urgent', 'Repeat client'];
      
      for (let i = 0; i < count; i++) {
        const service = services[i % services.length];
        const tier = tiers[Math.floor(Math.random() * tiers.length)];
        const amount = tier === 'good' ? 3500 : tier === 'better' ? 7500 : 12000;
        
        const daysAgo = Math.floor(Math.random() * 7);
        const hoursAgo = Math.floor(Math.random() * 24);
        const submittedDate = new Date();
        submittedDate.setDate(submittedDate.getDate() - daysAgo);
        submittedDate.setHours(submittedDate.getHours() - hoursAgo);
        
        const priority = priorities[Math.floor(Math.random() * priorities.length)];
        const assignee = assignees[Math.floor(Math.random() * assignees.length)];
        const tagSet = [tags[Math.floor(Math.random() * tags.length)]];
        const responseTime = Math.random() > 0.4 ? Math.floor(Math.random() * 18) + 1 : null;

        demoRequests.push({
          requestId: this.generateUUID(),
          sessionId: this.generateUUID(),
          status: 'new',
          submittedAt: submittedDate.toISOString(),
          updatedAt: submittedDate.toISOString(),
          address: addresses[i % addresses.length],
          serviceType: service,
          scope: scopes[i % scopes.length],
          complexity: complexities[Math.floor(Math.random() * complexities.length)],
          aiDescription: `${service} project requiring ${scopes[i % scopes.length].toLowerCase()}. Property assessment complete.`,
          estimate: {
            tier: tier,
            amount: amount,
            range: `${amount - 500}-${amount + 500}`,
            confidence: ['High', 'Medium', 'High'][Math.floor(Math.random() * 3)]
          },
          schedule: {
            date: new Date(Date.now() + (i + 1) * 86400000).toISOString().split('T')[0],
            time: ['9:00 AM', '10:00 AM', '2:00 PM', '3:00 PM'][Math.floor(Math.random() * 4)],
            type: 'recommended'
          },
          property: {
            measurements: {
              area: Math.floor(Math.random() * 3000) + 1000,
              perimeter: Math.floor(Math.random() * 300) + 100,
              unit: 'sq ft'
            },
            photos: [],
            aiAnalysis: null
          },
          contractor: {
            id: 'contractor-demo',
            name: "Demo Contractor"
          },
          priority: priority,
          assignee: assignee,
          tags: tagSet,
          slaHours: 24,
          responseTimeHours: responseTime,
          source: Math.random() > 0.5 ? 'Website' : 'Referral',
          statusHistory: [
            {
              status: 'new',
              timestamp: submittedDate.toISOString(),
              note: 'Demo request'
            }
          ],
          internalNotes: '',
          completionTime: Math.floor(Math.random() * 300000) + 120000,
          stepDurations: {}
        });
      }
      
      this.requests = [...demoRequests, ...this.requests];
      this.save();
      
      console.log(`🌱 Seeded ${count} demo requests`);
      return demoRequests;
    } catch (e) {
      console.error('Demo seeding failed:', e);
      return [];
    }
  }

  reset() {
    try {
      this.requests = [];
      localStorage.removeItem(REQUESTS_KEY);
      console.log('🗑️ Requests cleared');
      return true;
    } catch (e) {
      console.error('Reset failed:', e);
      return false;
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
     SINGLETON
  ========================================================= */

  static getInstance() {
    if (!window.__requestsManager) {
      window.__requestsManager = new RequestsManager();
    }
    return window.__requestsManager;
  }
}

// Export for use
if (typeof module !== 'undefined' && module.exports) {
  module.exports = RequestsManager;
}
