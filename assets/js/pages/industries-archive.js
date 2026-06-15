/**
 * Industries hub — client-side search and sort.
 *
 * @package JCP_Core
 */
(function () {
  'use strict';

  function init() {
    var root = document.querySelector('.jcp-industries-archive-section');
    if (!root) return;

    var grid = root.querySelector('#industries-archive-grid');
    var searchInput = root.querySelector('.industries-search-input');
    var sortFilter = root.querySelector('.industries-sort-filter');
    var clearBtn = root.querySelector('.industries-clear-filters');
    var clearSearchBtn = root.querySelector('.industries-search-box .clear-search-btn');
    var noResults = root.querySelector('#industries-no-results');

    if (!grid || !searchInput) return;

    var cards = Array.prototype.slice.call(grid.querySelectorAll('.jcp-niche-archive-card'));

    function updatePlaceholder(visible) {
      var singular = searchInput.getAttribute('data-placeholder-singular') || 'Search 1 trade';
      var pluralTpl = searchInput.getAttribute('data-placeholder-plural') || 'Search %d trades';
      searchInput.placeholder = visible === 1 ? singular : pluralTpl.replace('%d', String(visible));
    }

    function applySort() {
      var order = sortFilter && sortFilter.value ? sortFilter.value : 'az';
      var sorted = cards.slice().sort(function (a, b) {
        var aKey = (a.getAttribute('data-sort') || '').toLowerCase();
        var bKey = (b.getAttribute('data-sort') || '').toLowerCase();
        if (order === 'za') return bKey.localeCompare(aKey);
        return aKey.localeCompare(bKey);
      });
      sorted.forEach(function (card) {
        grid.appendChild(card);
      });
    }

    function filterCards() {
      var q = (searchInput.value || '').trim().toLowerCase();
      var visible = 0;

      cards.forEach(function (card) {
        var title = (card.getAttribute('data-title') || '').toLowerCase();
        var excerpt = (card.getAttribute('data-excerpt') || '').toLowerCase();
        var keywords = (card.getAttribute('data-keywords') || '').toLowerCase();
        var match = !q || title.indexOf(q) !== -1 || excerpt.indexOf(q) !== -1 || keywords.indexOf(q) !== -1;
        card.style.display = match ? '' : 'none';
        if (match) visible++;
      });

      updatePlaceholder(visible);
      if (noResults) noResults.classList.toggle('is-hidden', visible > 0 || cards.length === 0);
      if (clearBtn) clearBtn.classList.toggle('is-hidden', !q);
    }

    function toggleClearSearch() {
      if (clearSearchBtn) clearSearchBtn.classList.toggle('is-hidden', !searchInput.value.trim());
    }

    searchInput.addEventListener('input', function () {
      filterCards();
      toggleClearSearch();
    });

    if (sortFilter) sortFilter.addEventListener('change', applySort);

    if (clearBtn) {
      clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        filterCards();
        toggleClearSearch();
        clearBtn.classList.add('is-hidden');
      });
    }

    if (clearSearchBtn) {
      clearSearchBtn.addEventListener('click', function () {
        searchInput.value = '';
        searchInput.focus();
        filterCards();
        clearSearchBtn.classList.add('is-hidden');
      });
    }

    applySort();
    filterCards();
    toggleClearSearch();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
