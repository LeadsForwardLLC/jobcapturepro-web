<?php
/**
 * Global Header Template
 * Renders the opening HTML, head, and body tags
 *
 * @package JCP_Core
 */
$pages = jcp_core_get_page_detection();
// Marketing-only early bird banner (avoid demo/prototype/directory/company/app-like pages).
$show_earlybird_banner = empty( $pages['is_prototype'] )
  && empty( $pages['is_wp_plugin_prototype'] )
  && empty( $pages['is_demo'] )
  && empty( $pages['is_directory'] )
  && empty( $pages['is_company'] )
  && empty( $pages['is_estimate'] )
  && empty( $pages['is_ui_library'] );

$body_classes = 'jcp-global-nav-active' . ( $show_earlybird_banner ? ' has-top-banner' : '' );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class( $body_classes ); ?>>
  <?php if ( $show_earlybird_banner ) : ?>
    <?php
    $onb_args = function_exists( 'jcp_core_onboarding_utm_defaults' ) ? jcp_core_onboarding_utm_defaults( 'earlybird_banner' ) : [];
    $onb_args['coupon'] = 'earlybird';
    $onb_args['promo']  = 'earlybird';
    $earlybird_url = function_exists( 'jcp_core_onboarding_app_url' )
      ? jcp_core_onboarding_app_url( $onb_args )
      : esc_url( home_url( '/pricing' ) );
    ?>
    <div class="jcp-top-banner" id="jcpEarlybirdBanner" role="region" aria-label="<?php esc_attr_e( 'Early bird special', 'jcp-core' ); ?>">
      <div class="jcp-top-banner__inner">
        <div class="jcp-top-banner__copy">
          <strong class="jcp-top-banner__headline">Early Bird:</strong>
          <span class="jcp-top-banner__text">Get the Enterprise plan (normally $399/mo) for <strong>$125/mo</strong>.</span>
          <span class="jcp-top-banner__code">Code: <strong>EARLYBIRD</strong></span>
        </div>
        <div class="jcp-top-banner__actions">
          <a class="jcp-top-banner__cta" href="<?php echo esc_url( $earlybird_url ); ?>">Claim offer →</a>
          <button type="button" class="jcp-top-banner__close" id="jcpEarlybirdBannerClose" aria-label="<?php esc_attr_e( 'Dismiss banner', 'jcp-core' ); ?>">
            <span aria-hidden="true">×</span>
          </button>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <?php get_template_part( 'templates/partials/nav' ); ?>
  <div class="jcp-shell">
