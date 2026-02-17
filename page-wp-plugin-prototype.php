<?php
/**
 * Template Name: WP Plugin Prototype
 *
 * Prototype for the JobCapturePro WordPress plugin output: map with pins,
 * slider of check-ins (3 at a time), Powered by JobCapturePro footer.
 * Uses demo/placeholder content and existing JCP styles.
 *
 * @package JCP_Core
 */

$plugin_checkins = array(
	array(
		'images' => array(
			'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&h=400&fit=crop',
			'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=600&h=400&fit=crop',
			'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=600&h=400&fit=crop',
		),
		'description' => 'Wrapped up a window replacement project for a residential property. New energy-efficient windows were installed in multiple locations, enhancing both the exterior appearance and overall insulation. Everything is sealed up and fitted cleanly.',
		'date' => 'October 27, 2025',
		'location' => 'Near Austin, TX',
	),
	array(
		'images' => array(
			'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&h=400&fit=crop',
			'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=600&h=400&fit=crop',
		),
		'description' => 'Finished installing new energy-efficient windows on the front and back of the house. The front has a large set of sliding windows with a clean white frame. The installation went smoothly and gave the home a more modern appearance.',
		'date' => 'August 27, 2025',
		'location' => 'Near Round Rock, TX',
	),
	array(
		'images' => array(
			'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=600&h=400&fit=crop',
			'https://images.unsplash.com/photo-1600573472592-401b489a3cdc?w=600&h=400&fit=crop',
		),
		'description' => 'Wrapped up a clean install of a new sliding glass door. The frame was prepped and sealed properly, and everything slides smoothly. Fits perfectly and brings in a ton of natural light.',
		'date' => 'October 9, 2025',
		'location' => 'Near Cedar Park, TX',
	),
	array(
		'images' => array(
			'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=600&h=400&fit=crop',
		),
		'description' => 'Completed a full water heater swap-out: removed the failing tank, installed a new unit, reconnected lines, and confirmed there are no leaks. Verified ignition and heating cycle.',
		'date' => 'September 15, 2025',
		'location' => 'Near Georgetown, TX',
	),
	array(
		'images' => array(
			'https://images.unsplash.com/photo-1600566752355-35792bedcfea?w=600&h=400&fit=crop',
			'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&h=400&fit=crop',
		),
		'description' => 'Installed a new 3-panel window unit in the living room. Clean trim work and proper sealing. The room is brighter and more energy-efficient.',
		'date' => 'October 10, 2025',
		'location' => 'Near Pflugerville, TX',
	),
	array(
		'images' => array(
			'https://images.unsplash.com/photo-1600573472592-401b489a3cdc?w=600&h=400&fit=crop',
		),
		'description' => 'Replaced an aging water heater and brought the system up to code. Installed a new high-efficiency unit, verified proper venting, and tested temperature and pressure relief.',
		'date' => 'October 3, 2025',
		'location' => 'Near San Marcos, TX',
	),
);

get_header();
?>
<main class="jcp-wp-plugin-prototype">
	<section class="jcp-plugin-hero jcp-container">
		<h1 class="jcp-plugin-hero__title">Our Recently Completed Projects</h1>
		<p class="jcp-plugin-hero__intro">Take a look through our recently completed projects to see real work finished across the area. Each project shows our approach to planning, installation, and respect for your home.</p>
	</section>

	<section class="jcp-plugin-map-wrap jcp-container" aria-label="Project locations">
		<div class="jcp-plugin-map" id="jcp-plugin-map">
			<div class="jcp-plugin-map__pins" id="jcp-plugin-map-pins" aria-hidden="true">
				<span class="jcp-plugin-map__pin" style="left: 28%; top: 42%;" aria-hidden="true"></span>
				<span class="jcp-plugin-map__pin" style="left: 35%; top: 38%;" aria-hidden="true"></span>
				<span class="jcp-plugin-map__pin" style="left: 42%; top: 45%;" aria-hidden="true"></span>
				<span class="jcp-plugin-map__pin" style="left: 38%; top: 52%;" aria-hidden="true"></span>
				<span class="jcp-plugin-map__pin" style="left: 45%; top: 48%;" aria-hidden="true"></span>
				<span class="jcp-plugin-map__pin" style="left: 32%; top: 58%;" aria-hidden="true"></span>
			</div>
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/map-3c5b675f-f28d-41a5-ba3a-972b4c189f10.png' ); ?>" alt="Map of service area" class="jcp-plugin-map__img" width="1200" height="500" loading="lazy">
		</div>
	</section>

	<section class="jcp-plugin-slider-section jcp-container" aria-label="Project check-ins">
		<div class="jcp-plugin-slider" id="jcp-plugin-slider">
			<button type="button" class="jcp-plugin-slider__btn jcp-plugin-slider__btn--prev" id="jcp-plugin-slider-prev" aria-label="Previous projects">
				<svg class="jcp-plugin-slider__chevron" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
			</button>
			<div class="jcp-plugin-slider__viewport">
				<div class="jcp-plugin-slider__track" id="jcp-plugin-slider-track">
					<?php foreach ( $plugin_checkins as $index => $checkin ) : ?>
						<article class="jcp-plugin-card">
							<div class="jcp-plugin-card__gallery" data-carousel>
								<div class="jcp-plugin-card__gallery-inner">
									<?php foreach ( $checkin['images'] as $img_index => $img_src ) : ?>
										<div class="jcp-plugin-card__slide<?php echo $img_index === 0 ? ' is-active' : ''; ?>">
											<img src="<?php echo esc_url( $img_src ); ?>" alt="" width="400" height="260" loading="<?php echo $index < 3 ? 'eager' : 'lazy'; ?>">
										</div>
									<?php endforeach; ?>
								</div>
								<?php if ( count( $checkin['images'] ) > 1 ) : ?>
									<button type="button" class="jcp-plugin-card__nav jcp-plugin-card__nav--prev" aria-label="Previous image"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg></button>
									<button type="button" class="jcp-plugin-card__nav jcp-plugin-card__nav--next" aria-label="Next image"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></button>
									<div class="jcp-plugin-card__dots">
										<?php foreach ( $checkin['images'] as $dot_index => $img_src ) : ?>
											<button type="button" class="jcp-plugin-card__dot<?php echo $dot_index === 0 ? ' is-active' : ''; ?>" aria-label="Image <?php echo $dot_index + 1; ?>"></button>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="jcp-plugin-card__body">
								<p class="jcp-plugin-card__description" data-desc-text><?php echo esc_html( $checkin['description'] ); ?></p>
								<button type="button" class="jcp-plugin-card__toggle" data-desc-toggle hidden aria-expanded="false">Read more</button>
								<div class="jcp-plugin-card__meta">
									<span class="jcp-plugin-card__meta-item jcp-plugin-card__date">
										<svg class="jcp-plugin-card__meta-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M3 10h18"/></svg>
										<?php echo esc_html( $checkin['date'] ); ?>
									</span>
									<span class="jcp-plugin-card__meta-item jcp-plugin-card__location">
										<svg class="jcp-plugin-card__meta-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 5-8 12-8 12s-8-7-8-12a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
										<?php echo esc_html( $checkin['location'] ); ?>
									</span>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
			<button type="button" class="jcp-plugin-slider__btn jcp-plugin-slider__btn--next" id="jcp-plugin-slider-next" aria-label="Next projects">
				<svg class="jcp-plugin-slider__chevron" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
			</button>
		</div>
	</section>

	<footer class="jcp-plugin-footer">
		<div class="jcp-container">
			<a href="https://jobcapturepro.com" target="_blank" rel="noopener noreferrer" class="jcp-plugin-footer__pill" aria-label="Powered by JobCapturePro">
				<span class="jcp-plugin-footer__text">Powered by</span>
				<img src="https://jobcapturepro.com/wp-content/uploads/2025/11/JobCapturePro-Logo-Dark.png" alt="JobCapturePro Logo" class="jcp-plugin-footer__logo" width="129" height="31" loading="lazy">
			</a>
		</div>
	</footer>
</main>
<?php get_footer(); ?>
