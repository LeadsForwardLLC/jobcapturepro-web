<?php
/**
 * Archive Template
 *
 * Displays archive pages (category, tag, author, date, and CPT archives).
 * Help articles CPT archive (/help/) uses a custom layout: "Help Articles" headline,
 * description, and search/filter bar like the Blog archive.
 *
 * @package JCP_Core
 */

get_header();

if ( function_exists( 'jcp_core_is_help_archive' ) && jcp_core_is_help_archive() ) :
    $help_has_posts = have_posts();
    $total_posts    = $help_has_posts ? (int) $GLOBALS['wp_query']->found_posts : 0;
    $help_tax_slug  = 'help-category';
    $help_terms     = get_terms( [ 'taxonomy' => $help_tax_slug, 'hide_empty' => true ] );
    if ( is_wp_error( $help_terms ) || empty( $help_terms ) ) {
        $help_terms = [];
    }
    ?>
<main class="jcp-marketing">
  <section class="jcp-section rankings-section jcp-archive-hero-section">
    <div class="jcp-container">
      <div class="rankings-header">
        <h1><?php esc_html_e( 'Help Articles', 'jcp-core' ); ?></h1>
        <p class="rankings-subtitle"><?php esc_html_e( 'Guides, how-tos, and answers—so you can get the most out of JobCapturePro.', 'jcp-core' ); ?></p>
      </div>
    </div>
  </section>

  <section class="jcp-section rankings-section jcp-blog-archive-section">
    <div class="jcp-container">
      <?php if ( $help_has_posts ) : ?>
        <div class="blog-search-wrapper directory-search-wrapper">
          <div class="directory-search blog-search-bar">
            <div class="search-box blog-search-box">
              <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
              </svg>
              <input type="search" class="search-input blog-search-input" placeholder="<?php echo esc_attr( $total_posts === 1 ? __( 'Search 1 article', 'jcp-core' ) : sprintf( __( 'Search %d articles', 'jcp-core' ), $total_posts ) ); ?>" data-placeholder-singular="<?php esc_attr_e( 'Search 1 article', 'jcp-core' ); ?>" data-placeholder-plural="<?php echo esc_attr( sprintf( __( 'Search %d articles', 'jcp-core' ), '%d' ) ); ?>" autocomplete="off" aria-label="<?php esc_attr_e( 'Search articles', 'jcp-core' ); ?>">
              <button type="button" class="clear-search-btn is-hidden" aria-label="<?php esc_attr_e( 'Clear search', 'jcp-core' ); ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>
            <?php if ( ! empty( $help_terms ) ) : ?>
              <select class="filter-select blog-category-filter" aria-label="<?php esc_attr_e( 'Filter by category', 'jcp-core' ); ?>">
                <option value=""><?php esc_html_e( 'All categories', 'jcp-core' ); ?></option>
                <?php foreach ( $help_terms as $term ) : ?>
                  <option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option>
                <?php endforeach; ?>
              </select>
            <?php endif; ?>
            <select class="filter-select blog-sort-filter" aria-label="<?php esc_attr_e( 'Sort by date', 'jcp-core' ); ?>">
              <option value="newest"><?php esc_html_e( 'Newest to oldest', 'jcp-core' ); ?></option>
              <option value="oldest"><?php esc_html_e( 'Oldest to newest', 'jcp-core' ); ?></option>
            </select>
            <div class="view-toggle blog-view-toggle" role="group" aria-label="<?php esc_attr_e( 'View layout', 'jcp-core' ); ?>">
              <button type="button" class="view-btn blog-view-grid active" data-view="grid" aria-pressed="true" aria-label="<?php esc_attr_e( 'Grid view', 'jcp-core' ); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                  <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
              </button>
              <button type="button" class="view-btn blog-view-list" data-view="list" aria-pressed="false" aria-label="<?php esc_attr_e( 'List view', 'jcp-core' ); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                  <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                </svg>
              </button>
            </div>
            <button type="button" class="clear-filters-btn is-hidden blog-clear-filters"><?php esc_html_e( 'Clear filters', 'jcp-core' ); ?></button>
          </div>
        </div>

        <div class="jcp-blog-grid" id="blog-posts-container">
          <?php
          while ( have_posts() ) :
            the_post();
            get_template_part( 'templates/content/content', 'help-card' );
          endwhile;
          ?>
        </div>

        <?php get_template_part( 'templates/partials/pagination' ); ?>
      <?php else : ?>
        <?php get_template_part( 'templates/content/content', 'none' ); ?>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php if ( $help_has_posts ) : ?>
<script>
(function() {
  var container = document.getElementById('blog-posts-container');
  var searchInput = document.querySelector('.blog-search-input');
  var categoryFilter = document.querySelector('.blog-category-filter');
  var sortFilter = document.querySelector('.blog-sort-filter');
  var clearBtn = document.querySelector('.blog-clear-filters');
  var clearSearchBtn = document.querySelector('.blog-search-box .clear-search-btn');
  var viewGridBtn = document.querySelector('.blog-view-grid');
  var viewListBtn = document.querySelector('.blog-view-list');
  if (!container || !searchInput) return;
  var cards = Array.prototype.slice.call(container.querySelectorAll('.jcp-post-card'));

  var STORAGE_KEY = 'jcp_help_view';
  var savedView = typeof localStorage !== 'undefined' ? localStorage.getItem(STORAGE_KEY) : null;
  if (savedView === 'list') {
    container.classList.add('jcp-blog-list');
    if (viewGridBtn) { viewGridBtn.classList.remove('active'); viewGridBtn.setAttribute('aria-pressed', 'false'); }
    if (viewListBtn) { viewListBtn.classList.add('active'); viewListBtn.setAttribute('aria-pressed', 'true'); }
  }

  function setView(view) {
    if (view === 'list') {
      container.classList.add('jcp-blog-list');
      if (viewGridBtn) { viewGridBtn.classList.remove('active'); viewGridBtn.setAttribute('aria-pressed', 'false'); }
      if (viewListBtn) { viewListBtn.classList.add('active'); viewListBtn.setAttribute('aria-pressed', 'true'); }
    } else {
      container.classList.remove('jcp-blog-list');
      if (viewGridBtn) { viewGridBtn.classList.add('active'); viewGridBtn.setAttribute('aria-pressed', 'true'); }
      if (viewListBtn) { viewListBtn.classList.remove('active'); viewListBtn.setAttribute('aria-pressed', 'false'); }
    }
    if (typeof localStorage !== 'undefined') localStorage.setItem(STORAGE_KEY, view);
  }
  if (viewGridBtn) viewGridBtn.addEventListener('click', function() { setView('grid'); });
  if (viewListBtn) viewListBtn.addEventListener('click', function() { setView('list'); });

  if (sortFilter) {
    function applySort() {
      var order = sortFilter.value || 'newest';
      var sorted = cards.slice().sort(function(a, b) {
        var da = a.getAttribute('data-date') || '';
        var db = b.getAttribute('data-date') || '';
        if (order === 'oldest') return da.localeCompare(db);
        return db.localeCompare(da);
      });
      sorted.forEach(function(card) { container.appendChild(card); });
    }
    sortFilter.addEventListener('change', applySort);
  }

  function updatePlaceholder(visible) {
    var singular = searchInput.getAttribute('data-placeholder-singular') || 'Search 1 article';
    var pluralTpl = searchInput.getAttribute('data-placeholder-plural') || 'Search %d articles';
    searchInput.placeholder = visible === 1 ? singular : pluralTpl.replace('%d', visible);
  }

  function filterPosts() {
    var q = (searchInput.value || '').trim().toLowerCase();
    var cat = categoryFilter ? (categoryFilter.value || '').trim() : '';
    var visible = 0;
    cards.forEach(function(card) {
      var title = (card.getAttribute('data-title') || '').toLowerCase();
      var excerpt = (card.getAttribute('data-excerpt') || '').toLowerCase();
      var categories = (card.getAttribute('data-categories') || '').split(/\s+/).filter(Boolean);
      var matchSearch = !q || title.indexOf(q) !== -1 || excerpt.indexOf(q) !== -1;
      var matchCat = !cat || categories.indexOf(cat) !== -1;
      var show = matchSearch && matchCat;
      card.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    updatePlaceholder(visible);
    if (clearBtn) clearBtn.classList.toggle('is-hidden', !q && !cat);
  }
  function toggleClearSearch() {
    if (clearSearchBtn) clearSearchBtn.classList.toggle('is-hidden', !searchInput.value.trim());
  }
  searchInput.addEventListener('input', function() { filterPosts(); toggleClearSearch(); });
  searchInput.addEventListener('keyup', function() { filterPosts(); toggleClearSearch(); });
  if (categoryFilter) categoryFilter.addEventListener('change', filterPosts);
  if (clearBtn) clearBtn.addEventListener('click', function() {
    searchInput.value = '';
    if (categoryFilter) categoryFilter.value = '';
    filterPosts();
    toggleClearSearch();
    clearBtn.classList.add('is-hidden');
  });
  if (clearSearchBtn) clearSearchBtn.addEventListener('click', function() {
    searchInput.value = '';
    searchInput.focus();
    filterPosts();
    clearSearchBtn.classList.add('is-hidden');
  });
  toggleClearSearch();
  filterPosts();
})();
</script>
<?php endif; ?>

<?php
  get_footer();
  return;
endif;
?>

<main class="jcp-marketing">
  <!-- Hero Section (same spacing as blog index / category / author / tag) -->
  <section class="jcp-section rankings-section jcp-archive-hero-section">
    <div class="jcp-container">
      <div class="rankings-header">
        <h1>
          <?php
          if ( is_category() ) {
            single_cat_title();
          } elseif ( is_tag() ) {
            single_tag_title();
          } elseif ( is_author() ) {
            the_author();
          } elseif ( is_date() ) {
            if ( is_year() ) {
              echo esc_html( get_the_date( 'Y' ) );
            } elseif ( is_month() ) {
              echo esc_html( get_the_date( 'F Y' ) );
            } elseif ( is_day() ) {
              echo esc_html( get_the_date() );
            }
          } else {
            esc_html_e( 'Archives', 'jcp-core' );
          }
          ?>
        </h1>
        <?php
        $description = get_the_archive_description();
        if ( $description ) :
          ?>
          <p class="rankings-subtitle"><?php echo wp_kses_post( $description ); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Archive Posts Section -->
  <section class="jcp-section rankings-section">
    <div class="jcp-container">
      <?php if ( have_posts() ) : ?>
        <div class="jcp-blog-grid">
          <?php
          while ( have_posts() ) :
            the_post();
            get_template_part( 'templates/content/content', 'post-card' );
          endwhile;
          ?>
        </div>

        <?php
        get_template_part( 'templates/partials/pagination' );
      else :
        get_template_part( 'templates/content/content', 'none' );
      endif;
      ?>
    </div>
  </section>
</main>

<?php
get_footer();
