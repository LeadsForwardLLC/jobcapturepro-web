/**
 * Modular FAQ Component
 * Can be used on any page
 * 
 * @package JCP_Core
 */

(() => {
  /**
   * Render FAQ Section
   * @param {Object} options - FAQ configuration
   * @param {string} options.title - Section title (default: "FAQ")
   * @param {string} options.subtitle - Section subtitle
   * @param {Array} options.items - Array of {question, answer} objects
   * @param {string} options.id - Optional section ID
   * @returns {string} HTML string
   */
  window.renderFAQ = (options = {}) => {
    const {
      title = 'FAQ',
      subtitle = 'Clear answers for contractors evaluating the system.',
      items = [],
      id = 'faq'
    } = options;

    if (!items || items.length === 0) {
      return '';
    }

    const faqItems = items.map((item, index) => {
      const itemId = item.id || `faq-item-${index}`;
      return `
        <details class="faq-item" id="${itemId}">
          <summary>${item.question || ''}</summary>
          <p>${item.answer || ''}</p>
        </details>
      `;
    }).join('');

    return `
      <section class="jcp-section rankings-section faq-section" id="${id}">
        <div class="jcp-container">
          <div class="rankings-header">
            <h2>${title}</h2>
            ${subtitle ? `<p class="rankings-subtitle">${subtitle}</p>` : ''}
          </div>
          <div class="faq-grid">
            ${faqItems}
          </div>
        </div>
      </section>
    `;
  };
})();
