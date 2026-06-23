<?php
/**
 * Survey Step 1: Business name + Business type
 *
 * @package JCP_Core
 */
$demo_headline = 'See a live demo built for your business';
$demo_subhead  = 'Just a few details so the demo reflects real jobs and real outcomes.';
$demo_btn      = 'Next step';
?>
<section class="survey-step active" data-step="0">
  <div class="survey-head">
    <div class="survey-eyebrow">Online Demo</div>
    <h1 class="survey-title"><?php echo esc_html( $demo_headline ); ?></h1>
    <p class="survey-subtitle">
      <?php echo esc_html( $demo_subhead ); ?>
    </p>
    <div class="survey-note survey-note--desktop">
      <svg class="survey-note-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <rect x="2" y="4" width="20" height="14" rx="2" ry="2"></rect>
        <line x1="2" y1="20" x2="22" y2="20"></line>
        <line x1="16" y1="4" x2="16" y2="2"></line>
        <line x1="8" y1="4" x2="8" y2="2"></line>
      </svg>
      <div class="survey-note-copy">
        <strong><?php esc_html_e( 'Works on mobile or desktop.', 'jcp-core' ); ?></strong>
        <span class="survey-note-detail"><?php esc_html_e( 'On your phone, you’ll get a guided walkthrough. On a computer, you get the full side-by-side demo. Same experience, optimized for your screen.', 'jcp-core' ); ?></span>
      </div>
    </div>
  </div>

  <form class="survey-form" autocomplete="off">
    <div class="survey-field">
      <label for="businessName">Business name</label>
      <input
        id="businessName"
        type="text"
        class="survey-input"
        placeholder="Summit Plumbing"
        required
      />
    </div>

    <div class="survey-field">
      <label for="niche">Business type</label>
      <select id="niche" class="survey-input" required>
        <option value="">Select your business type</option>
        <optgroup label="Popular services">
          <option value="hvac">HVAC</option>
          <option value="plumbing">Plumbing</option>
          <option value="cleaning-services">Cleaning Services</option>
          <option value="pool-service">Pool Service</option>
          <option value="roofing">Roofing</option>
          <option value="remodeling">Remodeling</option>
          <option value="solar">Solar</option>
        </optgroup>
        <optgroup label="More services">
          <option value="carpet-cleaning">Carpet Cleaning</option>
          <option value="foundation-repair">Foundation Repair</option>
          <option value="dumpster-rental">Dumpster Rental</option>
          <option value="tree-service">Tree Service</option>
          <option value="deck-builder">Deck Builder</option>
          <option value="home-inspection">Home Inspection</option>
          <option value="home-windows">Home Windows</option>
        </optgroup>
      </select>
    </div>
  </form>

  <div class="survey-actions-row">
    <button type="button" class="survey-btn" data-action="next"><?php echo esc_html( $demo_btn ); ?></button>
  </div>
</section>
