<?php
/**
 * Survey wrapper: overlay, card, progress, steps, deck
 * Used when /demo is loaded without ?mode=run (survey view).
 *
 * @package JCP_Core
 */
$logo_url = esc_url( 'https://jobcapturepro.com/wp-content/uploads/2025/11/JobCapturePro-Logo-Dark.png' );
?>
<div class="survey-overlay">
  <button class="survey-close" id="surveyClose" aria-label="<?php esc_attr_e( 'Close', 'jcp-core' ); ?>">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M18 6L6 18M6 6l12 12"/>
    </svg>
  </button>

  <div class="survey-card">
    <div class="survey-brand">
      <img src="<?php echo $logo_url; ?>" alt="<?php esc_attr_e( 'JobCapturePro', 'jcp-core' ); ?>" width="180" height="40" />
    </div>

    <div class="survey-progress">
      <span id="surveyProgressText">Step 1 of 3</span>
      <div class="survey-stepper" role="tablist">
        <button class="stepper-step is-active" type="button" data-step="0">1</button>
        <button class="stepper-step" type="button" data-step="1">2</button>
        <button class="stepper-step" type="button" data-step="2">3</button>
      </div>
    </div>

    <?php get_template_part( 'templates/survey/step-1' ); ?>
    <?php get_template_part( 'templates/survey/step-2' ); ?>
    <?php get_template_part( 'templates/survey/step-3' ); ?>
    <?php get_template_part( 'templates/survey/deck' ); ?>
  </div>
</div>
