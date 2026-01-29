/**
 * Directory → Estimate Builder Integration
 * 
 * Handles launching the estimate builder as a full-screen takeover
 * when users click "Request this contractor"
 */

(function() {
  'use strict';

  // Extract contractor context from the page
  function getContractorContext() {
    const h1 = document.querySelector('.hero-card h1');
    const sub = document.querySelector('.hero-card .sub');
    
    const name = h1 ? h1.textContent.trim() : '';
    
    // Parse "Roofing · Houston, TX" format
    let service = '';
    let city = '';
    if (sub) {
      const parts = sub.textContent.split('·').map(p => p.trim());
      service = parts[0] || '';
      city = parts[1] || '';
    }

    return { name, service, city };
  }

  // Launch the estimate builder in a takeover iframe
  function launchEstimateBuilder() {
    const context = getContractorContext();
    
    // Build URL with contractor context as query params
    const params = new URLSearchParams({
      contractor: context.name,
      service: context.service,
      city: context.city,
      source: 'directory'
    });
    
    const iframe = document.getElementById('takeoverIframe');
    const takeover = document.getElementById('estimateTakeover');
    
    if (!iframe || !takeover) {
      console.error('Estimate takeover elements not found');
      return;
    }

    // Set iframe src and show takeover
    const baseUrl = window.JCP_CONFIG && window.JCP_CONFIG.baseUrl
      ? window.JCP_CONFIG.baseUrl
      : window.location.origin;
    iframe.src = `${baseUrl}/estimate/?${params.toString()}`;
    takeover.classList.remove('is-hidden');
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
  }

  // Close the estimate builder takeover
  function closeEstimateBuilder() {
    const iframe = document.getElementById('takeoverIframe');
    const takeover = document.getElementById('estimateTakeover');
    
    if (takeover) {
      takeover.classList.add('is-hidden');
    }
    
    // Clear iframe src to stop any running scripts
    if (iframe) {
      iframe.src = 'about:blank';
    }
    
    // Restore body scroll
    document.body.style.overflow = '';
  }

  // Show demo message instead of launching estimate builder
  function showDemoMessage() {
    // Create a styled notification
    const notification = document.createElement('div');
    notification.style.cssText = `
      position: fixed;
      top: 100px;
      left: 50%;
      transform: translateX(-50%);
      background: #ffffff;
      border: 2px solid var(--jcp-color-primary, #ff503e);
      border-radius: 12px;
      padding: 24px 32px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
      z-index: 10000;
      max-width: 400px;
      text-align: center;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    `;
    notification.innerHTML = `
      <p style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Demo Mode</p>
      <p style="margin: 0; font-size: 14px; color: #6b7280; line-height: 1.5;">The contractor directory is coming soon. This is a preview of how profiles will work.</p>
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 4 seconds
    setTimeout(() => {
      notification.style.transition = 'opacity 0.3s ease';
      notification.style.opacity = '0';
      setTimeout(() => {
        document.body.removeChild(notification);
      }, 300);
    }, 4000);
  }

  // Initialize when DOM is ready
  function init() {
    // Find all "Request this contractor" buttons
    const requestBtn = document.getElementById('requestContractorBtn');
    const stickyBtn = document.querySelector('.request-contractor-sticky');
    const closeBtn = document.getElementById('takeoverClose');
    const backdrop = document.querySelector('.takeover-backdrop');

    // Attach click handlers - show demo message instead
    if (requestBtn) {
      requestBtn.addEventListener('click', (e) => {
        e.preventDefault();
        showDemoMessage();
      });
    }
    
    if (stickyBtn) {
      stickyBtn.addEventListener('click', (e) => {
        e.preventDefault();
        showDemoMessage();
      });
    }

    if (closeBtn) {
      closeBtn.addEventListener('click', closeEstimateBuilder);
    }

    if (backdrop) {
      backdrop.addEventListener('click', closeEstimateBuilder);
    }

    // Listen for escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        const takeover = document.getElementById('estimateTakeover');
        if (takeover && !takeover.classList.contains('is-hidden')) {
          closeEstimateBuilder();
        }
      }
    });

    // Listen for messages from iframe (when estimate is completed)
    window.addEventListener('message', (event) => {
      // Verify origin matches our domain (or localhost for testing)
      const allowedOrigins = [
        window.location.origin,
        'http://localhost',
        'https://jobcapturepro.com'
      ];
      
      if (!allowedOrigins.some(origin => event.origin.startsWith(origin))) {
        return;
      }

      // Handle estimate completion
      if (event.data && event.data.type === 'estimateCompleted') {
        // Could show a success message or redirect
        console.log('Estimate completed:', event.data.jobId);
        
        // Optional: Close takeover after a delay
        setTimeout(() => {
          closeEstimateBuilder();
        }, 2000);
      }
    });
  }

  // Run on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
