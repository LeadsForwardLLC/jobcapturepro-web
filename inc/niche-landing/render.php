<?php
/**
 * Server-rendered niche / industry landing sections.
 *
 * @package JCP_Core
 */

/**
 * Escape and echo plain text.
 *
 * @param string $text Text.
 */
function jcp_niche_e( string $text ): void {
	echo esc_html( $text );
}

/**
 * Render full niche landing page.
 *
 * @param int $post_id Post ID.
 */
function jcp_niche_render_page( int $post_id ): void {
	$c         = jcp_niche_get_content( $post_id );
	$niche_key = ! empty( $c['niche_key'] ) ? (string) $c['niche_key'] : get_post_field( 'post_name', $post_id );
	$niche_key = sanitize_title( $niche_key );
	$is_referral = ( $c['page_type'] ?? '' ) === 'referral';

	echo '<main class="jcp-marketing jcp-home jcp-niche' . ( $is_referral ? ' jcp-niche-referral' : '' ) . '" data-niche="' . esc_attr( $niche_key ) . '">';
	jcp_niche_render_breadcrumb( $c );
	jcp_niche_render_hero( $c, $niche_key );
	jcp_niche_render_what_it_is( $c );
	if ( $is_referral ) {
		jcp_niche_render_cta_band( $c['cta_band_1'] ?? [], $niche_key, 'cta_band_1' );
	}
	jcp_niche_render_how_it_works( $c, $niche_key );
	jcp_niche_render_check_ins( $c );
	if ( ! $is_referral ) {
		jcp_niche_render_problem( $c );
	}
	jcp_niche_render_benefits( $c );
	if ( $is_referral ) {
		jcp_niche_render_commission( $c, $niche_key );
		jcp_niche_render_partners( $c, $niche_key );
		jcp_niche_render_share( $c, $niche_key );
	} else {
		jcp_niche_render_differentiation( $c );
		jcp_niche_render_who_its_for( $c );
	}
	jcp_niche_render_faq( $c );
	jcp_niche_render_final_cta( $c, $niche_key );
	echo '</main>';
}

/**
 * @param array<string, mixed> $c Content.
 */
function jcp_niche_render_breadcrumb( array $c ): void {
	if ( ! empty( $c['hide_breadcrumb'] ) ) {
		return;
	}
	$label = ! empty( $c['niche_label'] ) ? (string) $c['niche_label'] : '';
	if ( $label === '' ) {
		return;
	}
	$hub = get_post_type_archive_link( 'jcp_niche_landing' );
	if ( ! $hub ) {
		$hub = home_url( '/industries/' );
	}
	?>
	<nav class="jcp-niche-breadcrumb jcp-container" aria-label="<?php esc_attr_e( 'Breadcrumb', 'jcp-core' ); ?>">
		<a href="<?php echo esc_url( $hub ); ?>"><?php esc_html_e( 'Industries', 'jcp-core' ); ?></a>
		<span aria-hidden="true">/</span>
		<span><?php echo esc_html( $label ); ?></span>
	</nav>
	<?php
}

/**
 * @param array<string, mixed> $c Content.
 * @param string               $niche_key Niche key.
 */
function jcp_niche_render_hero( array $c, string $niche_key ): void {
	$h = $c['hero'] ?? [];
	if ( empty( $h['h1'] ) ) {
		return;
	}
	$primary     = jcp_niche_resolve_cta( $h['cta_primary'] ?? [], $niche_key );
	$secondary   = jcp_niche_resolve_cta( $h['cta_secondary'] ?? [ 'label' => 'See how it works', 'url' => '#how-it-works' ], $niche_key );
	$show_visual = ! isset( $h['show_visual'] ) || ! empty( $h['show_visual'] );
	$demo_url    = home_url( '/demo/' );
	$photo       = 'https://jobcapturepro.com/wp-content/uploads/2025/12/jcp-user-photo.jpg';
	?>
	<section class="jcp-section jcp-hero jcp-niche-hero<?php echo $show_visual ? '' : ' jcp-niche-hero--copy-only'; ?>">
		<div class="jcp-container">
			<div class="jcp-hero-grid">
				<div class="jcp-hero-copy hero-copy">
					<h1 class="jcp-hero-title"<?php jcp_niche_editable_attr( 'hero.h1' ); ?>><?php jcp_niche_e( (string) $h['h1'] ); ?></h1>
					<?php if ( ! empty( $h['subheadline'] ) ) : ?>
						<p class="jcp-hero-subtitle"<?php jcp_niche_editable_attr( 'hero.subheadline' ); ?>><?php jcp_niche_e( (string) $h['subheadline'] ); ?></p>
					<?php endif; ?>
					<div class="jcp-actions directory-cta-row">
						<?php if ( $primary['label'] !== '' ) : ?>
							<div class="jcp-hero-primary-cta">
								<a class="btn btn-primary" href="<?php echo esc_url( $primary['url'] ); ?>"<?php jcp_niche_editable_link_attr( 'hero.cta_primary' ); ?> data-cta-location="niche_hero"><?php jcp_niche_e( $primary['label'] ); ?></a>
							</div>
						<?php endif; ?>
						<?php if ( $secondary['label'] !== '' ) : ?>
							<a class="btn btn-secondary" href="<?php echo esc_url( $secondary['url'] ); ?>"<?php jcp_niche_editable_link_attr( 'hero.cta_secondary' ); ?>><?php jcp_niche_e( $secondary['label'] ); ?></a>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $h['trust_line'] ) ) : ?>
						<p class="jcp-niche-trust-line"<?php jcp_niche_editable_attr( 'hero.trust_line' ); ?>><?php jcp_niche_e( (string) $h['trust_line'] ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( $show_visual ) : ?>
				<div class="jcp-hero-visual hero-visual">
					<a href="<?php echo esc_url( $demo_url ); ?>" class="demo-phone-mockup hero-phone-mockup" aria-label="<?php esc_attr_e( 'Try the live demo', 'jcp-core' ); ?>">
						<div class="phone-frame hero-phone-frame">
							<div class="phone-screen">
								<div class="phone-content">
									<div class="phone-header hero-phone-header">
										<div class="phone-status-bar"><span>9:41</span></div>
										<div class="hero-phone-live-row"><span class="hero-phone-live-badge"><?php esc_html_e( 'Live', 'jcp-core' ); ?></span></div>
									</div>
									<div class="phone-body hero-phone-body">
										<div class="hero-phone-image-wrap">
											<img src="<?php echo esc_url( $photo ); ?>" alt="" class="hero-phone-image" width="390" height="292" loading="eager" />
										</div>
										<div class="demo-preview-item hero-phone-card hero-phone-card-1">
											<div class="demo-item-content">
												<div class="demo-item-title"><?php esc_html_e( 'Job captured', 'jcp-core' ); ?></div>
												<div class="demo-item-subtitle"><?php esc_html_e( 'Photos from the field', 'jcp-core' ); ?></div>
											</div>
										</div>
									</div>
									<div class="phone-click-hint hero-phone-cta">
										<span><?php esc_html_e( 'Try the demo', 'jcp-core' ); ?></span>
									</div>
								</div>
							</div>
						</div>
					</a>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Centered mid-page CTA band.
 *
 * @param array<string, mixed> $band      CTA band block.
 * @param string               $niche_key Niche key.
 * @param string               $path      JSON path prefix.
 */
function jcp_niche_render_cta_band( array $band, string $niche_key, string $path = 'cta_band' ): void {
	$primary = jcp_niche_resolve_cta( $band['cta_primary'] ?? [], $niche_key );
	if ( $primary['label'] === '' ) {
		return;
	}
	?>
	<section class="jcp-section jcp-niche-cta-band">
		<div class="jcp-container">
			<div class="jcp-niche-cta-band-inner">
				<a class="btn btn-primary" href="<?php echo esc_url( $primary['url'] ); ?>"<?php jcp_niche_editable_link_attr( $path . '.cta_primary' ); ?>><?php jcp_niche_e( $primary['label'] ); ?></a>
			</div>
		</div>
	</section>
	<?php
}

/**
 * @param array<string, mixed> $c Content.
 */
function jcp_niche_render_what_it_is( array $c ): void {
	$w = $c['what_it_is'] ?? [];
	if ( empty( $w['headline'] ) ) {
		return;
	}
	$lead = ! empty( $w['lead'] ) ? (string) $w['lead'] : __( 'But once the work is done, most of it disappears. JobCapturePro changes that.', 'jcp-core' );
	?>
	<section class="jcp-section rankings-section jcp-niche-what">
		<div class="jcp-container">
			<div class="rankings-header">
				<h2<?php jcp_niche_editable_attr( 'what_it_is.headline' ); ?>><?php jcp_niche_e( (string) $w['headline'] ); ?></h2>
				<?php if ( ! empty( $w['subheadline'] ) ) : ?>
					<p class="rankings-subtitle"<?php jcp_niche_editable_attr( 'what_it_is.subheadline' ); ?>><?php jcp_niche_e( (string) $w['subheadline'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="ranking-factors-grid jcp-niche-split-grid">
				<?php
				$team_title   = ! empty( $w['team_already_title'] ) ? (string) $w['team_already_title'] : __( 'Your team is already', 'jcp-core' );
				$turns_title  = ! empty( $w['turns_into_title'] ) ? (string) $w['turns_into_title'] : __( 'Turns real jobs into', 'jcp-core' );
				jcp_niche_factor_card(
					$team_title,
					'wrench',
					'',
					'',
					function () use ( $w ) {
						echo '<ul class="jcp-niche-checklist">';
						foreach ( (array) ( $w['team_already'] ?? [] ) as $line ) {
							echo '<li>' . esc_html( (string) $line ) . '</li>';
						}
						echo '</ul>';
					}
				);
				jcp_niche_factor_card(
					$turns_title,
					'sparkles',
					'',
					'',
					function () use ( $w, $lead ) {
						echo '<p class="jcp-niche-card-lead">' . esc_html( $lead ) . '</p>';
						echo '<ul class="jcp-niche-checklist">';
						foreach ( (array) ( $w['turns_into'] ?? [] ) as $line ) {
							echo '<li>' . esc_html( (string) $line ) . '</li>';
						}
						echo '</ul>';
					}
				);
				?>
			</div>
			<?php
			if ( ! empty( $w['closing'] ) ) {
				jcp_niche_render_section_closing( (string) $w['closing'], 'what_it_is.closing' );
			}
			$mechanic = $c['core_mechanic'] ?? [];
			if ( ! empty( $mechanic ) && is_array( $mechanic ) ) {
				jcp_niche_render_meta_strip( $mechanic );
			}
			?>
		</div>
	</section>
	<?php
}

/**
 * @param array<string, mixed> $c Content.
 * @param string               $niche_key Niche key.
 */
function jcp_niche_render_how_it_works( array $c, string $niche_key ): void {
	$h = $c['how_it_works'] ?? [];
	if ( empty( $h['headline'] ) ) {
		return;
	}
	$has_cta = ! empty( $h['cta_label'] ) || ! empty( $h['cta_url'] ) || ! empty( $h['cta_primary'] );
	$cta     = $has_cta
		? jcp_niche_resolve_cta(
			[
				'label' => $h['cta_label'] ?? ( $h['cta_primary']['label'] ?? 'See it in action' ),
				'url'   => $h['cta_url'] ?? ( $h['cta_primary']['url'] ?? '/demo' ),
			],
			$niche_key
		)
		: [ 'label' => '', 'url' => '' ];
	?>
	<section class="jcp-section rankings-section" id="how-it-works">
		<div class="jcp-container">
			<div class="rankings-header">
				<h2<?php jcp_niche_editable_attr( 'how_it_works.headline' ); ?>><?php jcp_niche_e( (string) $h['headline'] ); ?></h2>
				<?php if ( ! empty( $h['subheadline'] ) ) : ?>
					<p class="rankings-subtitle"<?php jcp_niche_editable_attr( 'how_it_works.subheadline' ); ?>><?php jcp_niche_e( (string) $h['subheadline'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="timeline-steps">
				<?php
				$steps = (array) ( $h['steps'] ?? [] );
				foreach ( $steps as $i => $step ) :
					if ( ! is_array( $step ) ) {
						continue;
					}
					$num = str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT );
					?>
					<div class="timeline-step">
						<div class="step-number"><?php echo esc_html( $num ); ?></div>
						<div class="step-content">
							<h4 class="step-title"><?php jcp_niche_e( (string) ( $step['title'] ?? '' ) ); ?></h4>
							<?php foreach ( (array) ( $step['lines'] ?? [] ) as $line ) : ?>
								<p class="step-description"><?php jcp_niche_e( (string) $line ); ?></p>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if ( $cta['label'] !== '' ) : ?>
				<div class="timeline-cta">
					<a href="<?php echo esc_url( $cta['url'] ); ?>" class="timeline-cta-link"<?php jcp_niche_editable_link_paths( 'how_it_works.cta_label', 'how_it_works.cta_url' ); ?>>
						<?php jcp_niche_e( $cta['label'] ); ?>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * @param array<string, mixed> $c Content.
 */
function jcp_niche_render_check_ins( array $c ): void {
	$ch = $c['check_ins'] ?? [];
	if ( empty( $ch['headline'] ) ) {
		return;
	}
	?>
	<section class="jcp-section rankings-section jcp-niche-checkins">
		<div class="jcp-container">
			<div class="rankings-header">
				<h2<?php jcp_niche_editable_attr( 'check_ins.headline' ); ?>><?php jcp_niche_e( (string) $ch['headline'] ); ?></h2>
				<?php if ( ! empty( $ch['subheadline'] ) ) : ?>
					<p class="rankings-subtitle"<?php jcp_niche_editable_attr( 'check_ins.subheadline' ); ?>><?php jcp_niche_e( (string) $ch['subheadline'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $ch['job_types'] ) ) : ?>
				<div class="jcp-niche-tags-wrap">
					<ul class="jcp-niche-tags">
						<?php foreach ( (array) $ch['job_types'] as $tag ) : ?>
							<li><?php jcp_niche_e( (string) $tag ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
			<div class="ranking-factors-grid">
				<?php
				$feat_icons = [ 'map-pin', 'camera', 'sparkles', 'star' ];
				foreach ( (array) ( $ch['features'] ?? [] ) as $fi => $feat ) :
					if ( ! is_array( $feat ) ) {
						continue;
					}
					jcp_niche_factor_card(
						(string) ( $feat['title'] ?? '' ),
						$feat_icons[ $fi ] ?? 'badge-check',
						'',
						'',
						function () use ( $feat ) {
							echo '<p>' . esc_html( (string) ( $feat['body'] ?? '' ) ) . '</p>';
						}
					);
				endforeach;
				?>
			</div>
			<?php
			if ( ! empty( $ch['closing'] ) ) {
				jcp_niche_render_section_closing( (string) $ch['closing'], 'check_ins.closing' );
			}
			?>
		</div>
	</section>
	<?php
}

/**
 * @param array<string, mixed> $c Content.
 */
function jcp_niche_render_problem( array $c ): void {
	$p = $c['problem'] ?? [];
	if ( empty( $p['headline'] ) ) {
		return;
	}
	?>
	<section class="jcp-section rankings-section jcp-niche-problem">
		<div class="jcp-container">
			<div class="rankings-header">
				<h2<?php jcp_niche_editable_attr( 'problem.headline' ); ?>><?php jcp_niche_e( (string) $p['headline'] ); ?></h2>
				<?php if ( ! empty( $p['subheadline'] ) ) : ?>
					<p class="rankings-subtitle"<?php jcp_niche_editable_attr( 'problem.subheadline' ); ?>><?php jcp_niche_e( (string) $p['subheadline'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="ranking-factors-grid">
				<?php
				$pain_icons = [ 'image-off', 'clock', 'map-pin', 'users' ];
				foreach ( (array) ( $p['pain_points'] ?? [] ) as $pi => $pain ) :
					if ( ! is_array( $pain ) ) {
						continue;
					}
					jcp_niche_factor_card(
						(string) ( $pain['title'] ?? '' ),
						$pain_icons[ $pi ] ?? 'circle-alert',
						'',
						'',
						function () use ( $pain ) {
							echo '<p>' . esc_html( (string) ( $pain['body'] ?? '' ) ) . '</p>';
						}
					);
				endforeach;
				?>
			</div>
			<?php
			if ( ! empty( $p['closing'] ) ) {
				jcp_niche_render_section_closing( (string) $p['closing'], 'problem.closing' );
			}
			?>
		</div>
	</section>
	<?php
}

/**
 * @param array<string, mixed> $c Content.
 */
function jcp_niche_render_benefits( array $c ): void {
	$b = $c['benefits'] ?? [];
	if ( empty( $b['headline'] ) ) {
		return;
	}
	?>
	<section class="jcp-section rankings-section jcp-niche-benefits">
		<div class="jcp-container">
			<div class="rankings-header">
				<h2<?php jcp_niche_editable_attr( 'benefits.headline' ); ?>><?php jcp_niche_e( (string) $b['headline'] ); ?></h2>
			</div>
			<div class="ranking-factors-grid">
				<?php
				$benefit_icons = [ 'map-pin', 'badge-check', 'star', 'share-2', 'trending-up', 'phone' ];
				foreach ( (array) ( $b['items'] ?? [] ) as $bi => $item ) :
					if ( ! is_array( $item ) ) {
						continue;
					}
					jcp_niche_factor_card(
						(string) ( $item['title'] ?? '' ),
						$benefit_icons[ $bi ] ?? 'badge-check',
						'',
						'',
						function () use ( $item ) {
							echo '<p>' . esc_html( (string) ( $item['body'] ?? '' ) ) . '</p>';
						}
					);
				endforeach;
				?>
			</div>
			<?php
			if ( ! empty( $b['closing'] ) ) {
				jcp_niche_render_section_closing( (string) $b['closing'], 'benefits.closing' );
			}
			?>
		</div>
	</section>
	<?php
}

/**
 * @param array<string, mixed> $c Content.
 * @param string               $niche_key Niche key.
 */
function jcp_niche_render_commission( array $c, string $niche_key ): void {
	$m = $c['commission'] ?? [];
	if ( empty( $m['headline'] ) ) {
		return;
	}
	$rows    = (array) ( $m['rows'] ?? [] );
	$primary = jcp_niche_resolve_cta( $m['cta_primary'] ?? [], $niche_key );
	?>
	<section class="jcp-section rankings-section jcp-niche-commission">
		<div class="jcp-container">
			<div class="rankings-header">
				<h2<?php jcp_niche_editable_attr( 'commission.headline' ); ?>><?php jcp_niche_e( (string) $m['headline'] ); ?></h2>
				<?php if ( ! empty( $m['subheadline'] ) ) : ?>
					<p class="rankings-subtitle"<?php jcp_niche_editable_attr( 'commission.subheadline' ); ?>><?php jcp_niche_e( (string) $m['subheadline'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $m['body'] ) ) : ?>
					<p class="jcp-niche-commission-lead"<?php jcp_niche_editable_attr( 'commission.body' ); ?>><?php jcp_niche_e( (string) $m['body'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $rows ) ) : ?>
				<div class="jcp-niche-commission-table-wrap">
					<table class="jcp-niche-commission-table">
						<thead>
							<tr>
								<th scope="col"><?php esc_html_e( 'Plan', 'jcp-core' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Monthly Price', 'jcp-core' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Your Monthly Commission', 'jcp-core' ); ?></th>
								<th scope="col"><?php esc_html_e( '12-Month Potential', 'jcp-core' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $rows as $i => $row ) : ?>
								<?php if ( ! is_array( $row ) ) { continue; } ?>
								<tr>
									<td data-label="<?php esc_attr_e( 'Plan', 'jcp-core' ); ?>"<?php jcp_niche_editable_attr( 'commission.rows.' . $i . '.plan' ); ?>><?php jcp_niche_e( (string) ( $row['plan'] ?? '' ) ); ?></td>
									<td data-label="<?php esc_attr_e( 'Monthly Price', 'jcp-core' ); ?>"<?php jcp_niche_editable_attr( 'commission.rows.' . $i . '.price' ); ?>><?php jcp_niche_e( (string) ( $row['price'] ?? '' ) ); ?></td>
									<td data-label="<?php esc_attr_e( 'Your Monthly Commission', 'jcp-core' ); ?>"<?php jcp_niche_editable_attr( 'commission.rows.' . $i . '.monthly' ); ?>><?php jcp_niche_e( (string) ( $row['monthly'] ?? '' ) ); ?></td>
									<td data-label="<?php esc_attr_e( '12-Month Potential', 'jcp-core' ); ?>"<?php jcp_niche_editable_attr( 'commission.rows.' . $i . '.twelve_month' ); ?>><?php jcp_niche_e( (string) ( $row['twelve_month'] ?? '' ) ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $m['footnote'] ) ) : ?>
				<p class="jcp-niche-commission-footnote"<?php jcp_niche_editable_attr( 'commission.footnote' ); ?>><?php jcp_niche_e( (string) $m['footnote'] ); ?></p>
			<?php endif; ?>
			<?php if ( $primary['label'] !== '' ) : ?>
				<div class="jcp-niche-cta-band-inner">
					<a class="btn btn-primary" href="<?php echo esc_url( $primary['url'] ); ?>"<?php jcp_niche_editable_link_attr( 'commission.cta_primary' ); ?>><?php jcp_niche_e( $primary['label'] ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * @param array<string, mixed> $c Content.
 * @param string               $niche_key Niche key.
 */
function jcp_niche_render_partners( array $c, string $niche_key ): void {
	$p = $c['partners'] ?? [];
	if ( empty( $p['headline'] ) ) {
		return;
	}
	$primary = jcp_niche_resolve_cta( $p['cta_primary'] ?? [], $niche_key );
	?>
	<section class="jcp-section rankings-section jcp-niche-partners">
		<div class="jcp-container">
			<div class="rankings-header">
				<h2<?php jcp_niche_editable_attr( 'partners.headline' ); ?>><?php jcp_niche_e( (string) $p['headline'] ); ?></h2>
			</div>
			<div class="real-job-proof-callout jcp-niche-partners-callout">
				<?php if ( ! empty( $p['body'] ) ) : ?>
					<p class="real-job-proof-callout-text"<?php jcp_niche_editable_attr( 'partners.body' ); ?>><?php jcp_niche_e( (string) $p['body'] ); ?></p>
				<?php endif; ?>
				<?php if ( $primary['label'] !== '' ) : ?>
					<div class="jcp-niche-cta-band-inner">
						<a class="btn btn-primary" href="<?php echo esc_url( $primary['url'] ); ?>"<?php jcp_niche_editable_link_attr( 'partners.cta_primary' ); ?>><?php jcp_niche_e( $primary['label'] ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * @param array<string, mixed> $c Content.
 * @param string               $niche_key Niche key.
 */
function jcp_niche_render_share( array $c, string $niche_key ): void {
	$s = $c['share'] ?? [];
	if ( empty( $s['headline'] ) ) {
		return;
	}
	$primary   = jcp_niche_resolve_cta( $s['cta_primary'] ?? [], $niche_key );
	$secondary = jcp_niche_resolve_cta( $s['cta_secondary'] ?? [], $niche_key );
	?>
	<section class="jcp-section rankings-section jcp-niche-share">
		<div class="jcp-container">
			<div class="rankings-header">
				<h2<?php jcp_niche_editable_attr( 'share.headline' ); ?>><?php jcp_niche_e( (string) $s['headline'] ); ?></h2>
				<?php if ( ! empty( $s['body'] ) ) : ?>
					<p class="rankings-subtitle"<?php jcp_niche_editable_attr( 'share.body' ); ?>><?php jcp_niche_e( (string) $s['body'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $s['quote'] ) ) : ?>
				<blockquote class="jcp-niche-share-quote"<?php jcp_niche_editable_attr( 'share.quote' ); ?>>
					<p><?php jcp_niche_e( (string) $s['quote'] ); ?></p>
				</blockquote>
			<?php endif; ?>
			<?php if ( ! empty( $s['note'] ) ) : ?>
				<p class="jcp-niche-share-note"<?php jcp_niche_editable_attr( 'share.note' ); ?>><?php jcp_niche_e( (string) $s['note'] ); ?></p>
			<?php endif; ?>
			<div class="jcp-actions directory-cta-row jcp-niche-share-actions">
				<?php if ( $primary['label'] !== '' ) : ?>
					<a class="btn btn-primary" href="<?php echo esc_url( $primary['url'] ); ?>"<?php jcp_niche_editable_link_attr( 'share.cta_primary' ); ?>><?php jcp_niche_e( $primary['label'] ); ?></a>
				<?php endif; ?>
				<?php if ( $secondary['label'] !== '' ) : ?>
					<a class="btn btn-secondary" href="<?php echo esc_url( $secondary['url'] ); ?>"<?php jcp_niche_editable_link_attr( 'share.cta_secondary' ); ?>><?php jcp_niche_e( $secondary['label'] ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * @param array<string, mixed> $c Content.
 */
function jcp_niche_render_differentiation( array $c ): void {
	$d = $c['differentiation'] ?? [];
	if ( empty( $d['headline'] ) ) {
		return;
	}
	?>
	<section class="jcp-section rankings-section jcp-niche-diff">
		<div class="jcp-container">
			<div class="rankings-header">
				<h2<?php jcp_niche_editable_attr( 'differentiation.headline' ); ?>><?php jcp_niche_e( (string) $d['headline'] ); ?></h2>
			</div>
			<div class="real-job-proof-callout jcp-niche-diff-callout">
				<?php if ( ! empty( $d['body'] ) ) : ?>
					<p class="real-job-proof-callout-text"<?php jcp_niche_editable_attr( 'differentiation.body' ); ?>><?php jcp_niche_e( (string) $d['body'] ); ?></p>
				<?php endif; ?>
				<?php jcp_niche_render_conversion_points( (array) ( $d['bullets'] ?? [] ) ); ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * @param array<string, mixed> $c Content.
 */
function jcp_niche_render_who_its_for( array $c ): void {
	$w = $c['who_its_for'] ?? [];
	if ( empty( $w['headline'] ) ) {
		return;
	}
	?>
	<section class="jcp-section rankings-section jcp-niche-audiences" id="who-its-for">
		<div class="jcp-container">
			<div class="rankings-header">
				<h2<?php jcp_niche_editable_attr( 'who_its_for.headline' ); ?>><?php jcp_niche_e( (string) $w['headline'] ); ?></h2>
			</div>
			<div class="ranking-factors-grid jcp-niche-split-grid">
				<?php
				$aud_icons = [ 'briefcase', 'hard-hat', 'trending-up' ];
				foreach ( (array) ( $w['audiences'] ?? [] ) as $ai => $aud ) :
					if ( ! is_array( $aud ) ) {
						continue;
					}
					jcp_niche_factor_card(
						(string) ( $aud['title'] ?? '' ),
						$aud_icons[ $ai ] ?? 'users',
						'',
						'',
						function () use ( $aud ) {
							echo '<p>' . esc_html( (string) ( $aud['body'] ?? '' ) ) . '</p>';
						}
					);
				endforeach;
				?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * @param array<string, mixed> $c Content.
 */
function jcp_niche_render_faq( array $c ): void {
	$f = $c['faq'] ?? [];
	$items = (array) ( $f['items'] ?? [] );
	if ( empty( $f['headline'] ) || empty( $items ) ) {
		return;
	}
	?>
	<section class="jcp-section rankings-section faq-section" id="faq">
		<div class="jcp-container">
			<div class="rankings-header">
				<h2<?php jcp_niche_editable_attr( 'faq.headline' ); ?>><?php jcp_niche_e( (string) $f['headline'] ); ?></h2>
			</div>
			<div class="faq-grid">
				<?php foreach ( $items as $i => $item ) : ?>
					<?php if ( ! is_array( $item ) ) { continue; } ?>
					<details class="faq-item" id="faq-<?php echo esc_attr( (string) $i ); ?>">
						<summary<?php jcp_niche_editable_attr( 'faq.items.' . $i . '.q' ); ?>><?php jcp_niche_e( (string) ( $item['q'] ?? '' ) ); ?></summary>
						<p<?php jcp_niche_editable_attr( 'faq.items.' . $i . '.a' ); ?>><?php jcp_niche_e( (string) ( $item['a'] ?? '' ) ); ?></p>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * @param array<string, mixed> $c Content.
 * @param string               $niche_key Niche key.
 */
function jcp_niche_render_final_cta( array $c, string $niche_key ): void {
	$f = $c['final_cta'] ?? [];
	if ( empty( $f['headline'] ) ) {
		return;
	}
	$primary = jcp_niche_resolve_cta( $f['cta_primary'] ?? [], $niche_key );
	$note    = ! empty( $f['cta_note'] ) ? (string) $f['cta_note'] : __( 'No signup required. Setup in minutes.', 'jcp-core' );
	$btn     = $primary['label'] !== '' ? $primary['label'] : __( 'See your business in the live demo', 'jcp-core' );
	$url     = $primary['url'] !== '' ? $primary['url'] : home_url( '/demo/' );
	?>
	<section class="jcp-section rankings-section jcp-niche-final">
		<div class="jcp-container">
			<div class="rankings-cta">
				<div class="cta-content">
					<h3<?php jcp_niche_editable_attr( 'final_cta.headline' ); ?>><?php jcp_niche_e( (string) $f['headline'] ); ?></h3>
					<?php if ( ! empty( $f['subheadline'] ) ) : ?>
						<p class="cta-paragraph"<?php jcp_niche_editable_attr( 'final_cta.subheadline' ); ?>><?php jcp_niche_e( (string) $f['subheadline'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="cta-button-wrapper">
					<a class="btn btn-primary rankings-cta-btn" href="<?php echo esc_url( $url ); ?>"<?php jcp_niche_editable_link_attr( 'final_cta.cta_primary' ); ?> data-cta-location="niche_footer"><?php echo esc_html( $btn ); ?></a>
					<p class="cta-note"<?php jcp_niche_editable_attr( 'final_cta.cta_note' ); ?>><?php echo esc_html( $note ); ?></p>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render industries archive hub.
 */
function jcp_niche_render_archive(): void {
	$posts = get_posts(
		[
			'post_type'      => 'jcp_niche_landing',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		]
	);
	?>
	<main class="jcp-marketing jcp-home jcp-niche jcp-niche-archive">
		<section class="jcp-section jcp-hero jcp-niche-hero jcp-niche-archive-hero">
			<div class="jcp-container">
				<div class="jcp-hero-copy hero-copy">
					<h1 class="jcp-hero-title"><?php esc_html_e( 'Marketing software for home service contractors', 'jcp-core' ); ?></h1>
					<p class="jcp-hero-subtitle"><?php esc_html_e( 'JobCapturePro turns completed jobs into Google visibility, website proof, social posts, and reviews — built for the trades you run every day.', 'jcp-core' ); ?></p>
				</div>
			</div>
		</section>
		<section class="jcp-section rankings-section">
			<div class="jcp-container">
				<div class="rankings-header">
					<h2><?php esc_html_e( 'Browse by industry', 'jcp-core' ); ?></h2>
					<p class="rankings-subtitle"><?php esc_html_e( 'See how JobCapturePro works for your trade.', 'jcp-core' ); ?></p>
				</div>
				<div class="jcp-niche-archive-grid">
					<?php foreach ( $posts as $post ) : ?>
						<?php
						$content = jcp_niche_get_content( (int) $post->ID );
						$label   = ! empty( $content['niche_label'] ) ? (string) $content['niche_label'] : get_the_title( $post );
						$excerpt = $content['hero']['subheadline'] ?? get_the_excerpt( $post );
						?>
						<a class="jcp-niche-archive-card" href="<?php echo esc_url( get_permalink( $post ) ); ?>">
							<h3><?php echo esc_html( $label ); ?></h3>
							<p><?php echo esc_html( wp_strip_all_tags( (string) $excerpt ) ); ?></p>
							<span class="jcp-niche-archive-link"><?php esc_html_e( 'Learn more', 'jcp-core' ); ?> →</span>
						</a>
					<?php endforeach; ?>
				</div>
				<?php if ( empty( $posts ) ) : ?>
					<p><?php esc_html_e( 'Industry pages coming soon.', 'jcp-core' ); ?></p>
				<?php endif; ?>
			</div>
		</section>
	</main>
	<?php
}
