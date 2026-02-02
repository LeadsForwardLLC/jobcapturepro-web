<?php
/**
 * JobCapture Pro API to Custom Post Type Integration
 * Registers jcp_company CPT, syncs from app.jobcapturepro.com API, and provides Import from API.
 * Previously run via Code Snippets; now part of the theme. Works with Firebase integration plugin
 * for credentials; daily cron and manual import populate the CPT used by the directory/profile.
 *
 * @package JCP_Core
 */

// ==================== 1. DEFINE CONSTANTS ====================
if ( ! defined( 'JCP_API_URL' ) ) {
	define( 'JCP_API_URL', 'https://app.jobcapturepro.com/api/companies' );
}

// Token: define JCP_API_TOKEN in wp-config.php, or set option jcp_core_api_token, or use filter jcp_core_api_token (e.g. from Firebase plugin).

/**
 * Get API token for JobCapture Pro requests.
 * Order: constant (e.g. from wp-config) → env var → filter → option.
 * Prefer JCP_API_TOKEN in environment or wp-config; avoid storing in DB when possible.
 *
 * @return string Token or empty string.
 */
function jcp_core_get_api_token(): string {
	if ( defined( 'JCP_API_TOKEN' ) && JCP_API_TOKEN !== '' ) {
		return (string) JCP_API_TOKEN;
	}
	$token = getenv( 'JCP_API_TOKEN' );
	if ( $token !== false && trim( (string) $token ) !== '' ) {
		return trim( (string) $token );
	}
	$token = apply_filters( 'jcp_core_api_token', get_option( 'jcp_core_api_token', '' ) );
	return is_string( $token ) ? trim( $token ) : '';
}

// ==================== 2. SINGLE CPT REGISTRATION WITH DUPLICATE PREVENTION ====================
add_action( 'init', 'jcp_register_company_cpt', 5 );

function jcp_register_company_cpt(): void {
	if ( post_type_exists( 'jcp_company' ) ) {
		return;
	}

	$labels = [
		'name'               => 'JCP Companies',
		'singular_name'      => 'JCP Company',
		'menu_name'          => 'JCP Companies',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Company',
		'edit_item'          => 'Edit Company',
		'new_item'           => 'New Company',
		'view_item'          => 'View Company',
		'search_items'       => 'Search Companies',
		'not_found'          => 'No companies found',
		'not_found_in_trash' => 'No companies found in Trash',
	];

	$args = [
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 30,
		'menu_icon'           => 'dashicons-building',
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
		'has_archive'         => true,
		'rewrite'             => [ 'slug' => 'jcp-companies' ],
		'query_var'           => true,
		'show_in_rest'        => true,
	];

	register_post_type( 'jcp_company', $args );
}

// ==================== 3. FIX DUPLICATE MENU ITEMS ====================
add_action( 'admin_menu', 'jcp_fix_duplicate_menu', 999 );

function jcp_fix_duplicate_menu(): void {
	global $menu;
	$jcp_menu_count = 0;
	foreach ( $menu as $key => $item ) {
		if ( isset( $item[2] ) && $item[2] === 'edit.php?post_type=jcp_company' ) {
			$jcp_menu_count++;
			if ( $jcp_menu_count > 1 ) {
				unset( $menu[ $key ] );
			}
		}
	}
	if ( $jcp_menu_count === 0 && post_type_exists( 'jcp_company' ) ) {
		add_menu_page(
			'JCP Companies',
			'JCP Companies',
			'edit_posts',
			'edit.php?post_type=jcp_company',
			'',
			'dashicons-building',
			30
		);
	}
}

// ==================== 4. ADD IMPORT SUBMENU ====================
add_action( 'admin_menu', 'jcp_add_import_submenu' );

function jcp_add_import_submenu(): void {
	add_submenu_page(
		'edit.php?post_type=jcp_company',
		'Import from API',
		'Import from API',
		'manage_options',
		'jcp-import',
		'jcp_render_import_page'
	);
}

// ==================== 5. API CLIENT FUNCTIONS ====================
function jcp_fetch_companies( bool $force_refresh = false ): array {
	$token = jcp_core_get_api_token();
	$cache_key = 'jcp_companies_data_' . md5( $token ?: 'no-token' );

	if ( ! $force_refresh ) {
		$cached = get_transient( $cache_key );
		if ( $cached !== false ) {
			return $cached;
		}
	}

	$response = wp_remote_get(
		defined( 'JCP_API_URL' ) ? JCP_API_URL : 'https://app.jobcapturepro.com/api/companies',
		[
			'headers' => [
				'Authorization' => 'Bearer ' . $token,
				'Accept'         => 'application/json',
			],
			'timeout' => 30,
		]
	);

	if ( is_wp_error( $response ) ) {
		error_log( 'JCP API Error: ' . $response->get_error_message() );
		return [ 'success' => false, 'error' => $response->get_error_message() ];
	}

	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );

	if ( empty( $data ) ) {
		return [ 'success' => false, 'error' => 'Empty response' ];
	}

	if ( isset( $data['data'] ) && is_array( $data['data'] ) ) {
		$companies = $data['data'];
	} elseif ( isset( $data['companies'] ) && is_array( $data['companies'] ) ) {
		$companies = $data['companies'];
	} else {
		$companies = $data;
	}

	$result = [
		'success'   => true,
		'data'       => $companies,
		'total'      => count( $companies ),
		'fetched_at' => current_time( 'mysql' ),
	];

	set_transient( $cache_key, $result, HOUR_IN_SECONDS );
	return $result;
}

// ==================== 6. CREATE/UPDATE CPT POSTS WITH ALL FIELDS ====================
function jcp_create_company_post( array $company_data ) {
	if ( ! post_type_exists( 'jcp_company' ) ) {
		error_log( 'JCP Company CPT does not exist when trying to create post' );
		return false;
	}

	$existing_posts = get_posts( [
		'post_type'      => 'jcp_company',
		'meta_key'       => '_jcp_company_id',
		'meta_value'     => $company_data['id'],
		'posts_per_page' => 1,
	] );

	$post_data = [
		'post_title'   => ! empty( $company_data['name'] ) ? sanitize_text_field( $company_data['name'] ) : 'Company #' . $company_data['id'],
		'post_status'  => 'publish',
		'post_type'    => 'jcp_company',
		'post_content' => ! empty( $company_data['description'] ) ? wp_kses_post( $company_data['description'] ) : '',
		'post_excerpt' => ! empty( $company_data['excerpt'] ) ? sanitize_text_field( $company_data['excerpt'] ) : '',
		'meta_input'   => [
			'_jcp_company_id'   => $company_data['id'],
			'_jcp_last_synced' => current_time( 'mysql' ),
		],
	];

	if ( ! empty( $company_data['url'] ) ) {
		$post_data['meta_input']['_jcp_website_url'] = esc_url_raw( $company_data['url'] );
	}

	if ( ! empty( $company_data['address'] ) ) {
		if ( is_array( $company_data['address'] ) ) {
			$post_data['meta_input']['_jcp_address'] = wp_json_encode( $company_data['address'] );
			$address_parts = [];
			if ( ! empty( $company_data['address']['street'] ) ) {
				$address_parts[] = $company_data['address']['street'];
			}
			if ( ! empty( $company_data['address']['city'] ) ) {
				$address_parts[] = $company_data['address']['city'];
			}
			if ( ! empty( $company_data['address']['state'] ) ) {
				$address_parts[] = $company_data['address']['state'];
			}
			if ( ! empty( $company_data['address']['zip'] ) ) {
				$address_parts[] = $company_data['address']['zip'];
			}
			if ( ! empty( $company_data['address']['country'] ) ) {
				$address_parts[] = $company_data['address']['country'];
			}
			if ( ! empty( $address_parts ) ) {
				$post_data['meta_input']['_jcp_address_formatted'] = implode( ', ', $address_parts );
			}
		} else {
			$post_data['meta_input']['_jcp_address'] = sanitize_text_field( $company_data['address'] );
		}
	}

	if ( ! empty( $company_data['phoneNumberString'] ) ) {
		$post_data['meta_input']['_jcp_phone'] = sanitize_text_field( $company_data['phoneNumberString'] );
	} elseif ( ! empty( $company_data['phone'] ) ) {
		$post_data['meta_input']['_jcp_phone'] = sanitize_text_field( $company_data['phone'] );
	}

	if ( ! empty( $company_data['logoUrl'] ) ) {
		$post_data['meta_input']['_jcp_logo_url'] = esc_url_raw( $company_data['logoUrl'] );
	}

	if ( ! empty( $company_data['selectedIndustries'] ) ) {
		if ( is_array( $company_data['selectedIndustries'] ) ) {
			$industries = [];
			foreach ( $company_data['selectedIndustries'] as $industry ) {
				if ( is_array( $industry ) && ! empty( $industry['name'] ) ) {
					$industries[] = sanitize_text_field( $industry['name'] );
				} elseif ( is_string( $industry ) ) {
					$industries[] = sanitize_text_field( $industry );
				}
			}
			if ( ! empty( $industries ) ) {
				$post_data['meta_input']['_jcp_selected_industries'] = $industries;
				$post_data['meta_input']['_jcp_selected_industries_string'] = implode( ', ', $industries );
			}
		} elseif ( is_string( $company_data['selectedIndustries'] ) ) {
			$post_data['meta_input']['_jcp_selected_industries_string'] = sanitize_text_field( $company_data['selectedIndustries'] );
			$industries = array_map( 'trim', explode( ',', $company_data['selectedIndustries'] ) );
			$post_data['meta_input']['_jcp_selected_industries'] = $industries;
		}
	}

	if ( ! empty( $company_data['checkinTags'] ) ) {
		if ( is_array( $company_data['checkinTags'] ) ) {
			$tags = [];
			foreach ( $company_data['checkinTags'] as $tag ) {
				if ( is_array( $tag ) && ! empty( $tag['name'] ) ) {
					$tags[] = sanitize_text_field( $tag['name'] );
				} elseif ( is_string( $tag ) ) {
					$tags[] = sanitize_text_field( $tag );
				}
			}
			if ( ! empty( $tags ) ) {
				$post_data['meta_input']['_jcp_checkin_tags'] = $tags;
				$post_data['meta_input']['_jcp_checkin_tags_string'] = implode( ', ', $tags );
			}
		} elseif ( is_string( $company_data['checkinTags'] ) ) {
			$post_data['meta_input']['_jcp_checkin_tags_string'] = sanitize_text_field( $company_data['checkinTags'] );
			$tags = array_map( 'trim', explode( ',', $company_data['checkinTags'] ) );
			$post_data['meta_input']['_jcp_checkin_tags'] = $tags;
		}
	}

	if ( ! empty( $existing_posts ) ) {
		$post_data['ID'] = $existing_posts[0]->ID;
		$post_id = wp_update_post( $post_data );
	} else {
		$post_id = wp_insert_post( $post_data );
	}

	if ( is_wp_error( $post_id ) ) {
		error_log( 'JCP Post Creation Error: ' . $post_id->get_error_message() );
		return false;
	}

	return $post_id;
}

// ==================== 7. IMPORT FUNCTION ====================
function jcp_import_companies( bool $force_update = false ): array {
	$result = jcp_fetch_companies( $force_update );
	if ( ! $result['success'] ) {
		return [
			'success'  => false,
			'message'  => 'API Error: ' . $result['error'],
			'imported' => 0,
		];
	}

	$companies = $result['data'];
	$imported  = 0;
	$updated   = 0;
	$errors    = 0;

	foreach ( $companies as $company ) {
		if ( empty( $company['id'] ) ) {
			$errors++;
			continue;
		}
		$post_id = jcp_create_company_post( $company );
		if ( $post_id ) {
			$existing = get_post( $post_id );
			$created_date  = strtotime( $existing->post_date );
			$modified_date  = strtotime( $existing->post_modified );
			if ( $modified_date > $created_date + 10 ) {
				$updated++;
			} else {
				$imported++;
			}
		} else {
			$errors++;
		}
	}

	update_option( 'jcp_last_import', [
		'time'     => current_time( 'mysql' ),
		'total'    => count( $companies ),
		'imported' => $imported,
		'updated'  => $updated,
		'errors'   => $errors,
	] );

	return [
		'success'   => true,
		'message'   => sprintf( 'Import complete: %d new, %d updated, %d errors', $imported, $updated, $errors ),
		'imported'  => $imported,
		'updated'   => $updated,
		'errors'    => $errors,
		'total'     => count( $companies ),
	];
}

// ==================== 8. IMPORT PAGE ====================
function jcp_render_import_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'jcp-core' ) );
	}

	if ( isset( $_POST['jcp_action'] ) && $_POST['jcp_action'] === 'import' && check_admin_referer( 'jcp_import_nonce' ) ) {
		$force_update = isset( $_POST['force_update'] );
		$result = jcp_import_companies( $force_update );
		if ( $result['success'] ) {
			echo '<div class="notice notice-success"><p>' . esc_html( $result['message'] ) . '</p></div>';
		} else {
			echo '<div class="notice notice-error"><p>' . esc_html( $result['message'] ) . '</p></div>';
		}
	}

	$last_import   = get_option( 'jcp_last_import', [] );
	$company_count = wp_count_posts( 'jcp_company' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Import Companies from JobCapture Pro', 'jcp-core' ); ?></h1>
		<div class="card" style="max-width: 800px; padding: 20px;">
			<h2><?php esc_html_e( 'Statistics', 'jcp-core' ); ?></h2>
			<table class="widefat">
				<tr>
					<td><strong><?php esc_html_e( 'Published Companies:', 'jcp-core' ); ?></strong></td>
					<td><?php echo (int) $company_count->publish; ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Draft Companies:', 'jcp-core' ); ?></strong></td>
					<td><?php echo (int) $company_count->draft; ?></td>
				</tr>
				<?php if ( ! empty( $last_import ) ) : ?>
					<tr>
						<td><strong><?php esc_html_e( 'Last Import:', 'jcp-core' ); ?></strong></td>
						<td><?php echo esc_html( $last_import['time'] ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Last Import Results:', 'jcp-core' ); ?></strong></td>
						<td><?php echo esc_html( $last_import['total'] . ' total, ' . $last_import['imported'] . ' new, ' . $last_import['updated'] . ' updated, ' . $last_import['errors'] . ' errors' ); ?></td>
					</tr>
				<?php endif; ?>
			</table>
			<h2><?php esc_html_e( 'Import Options', 'jcp-core' ); ?></h2>
			<form method="post">
				<?php wp_nonce_field( 'jcp_import_nonce' ); ?>
				<input type="hidden" name="jcp_action" value="import">
				<p>
					<label>
						<input type="checkbox" name="force_update" value="1">
						<?php esc_html_e( 'Force update existing companies', 'jcp-core' ); ?>
					</label>
					<br><small><?php esc_html_e( 'Update all companies even if they already exist', 'jcp-core' ); ?></small>
				</p>
				<p>
					<button type="submit" class="button button-primary button-large"><?php esc_html_e( 'Start Import', 'jcp-core' ); ?></button>
				</p>
			</form>
		</div>
		<?php
		$api_status = jcp_test_api_connection();
		?>
		<div class="card" style="max-width: 800px; padding: 20px; margin-top: 20px;">
			<h2><?php esc_html_e( 'API Status', 'jcp-core' ); ?></h2>
			<div style="display: flex; align-items: center; gap: 20px;">
				<div style="font-size: 40px;">
					<?php if ( $api_status['success'] ) : ?>
						<span style="color: green;">✓</span>
					<?php else : ?>
						<span style="color: red;">✗</span>
					<?php endif; ?>
				</div>
				<div>
					<?php if ( $api_status['success'] ) : ?>
						<p style="color: green; font-weight: bold;"><?php esc_html_e( 'Connected', 'jcp-core' ); ?></p>
						<p><?php echo esc_html( sprintf( __( 'Found %d companies', 'jcp-core' ), $api_status['count'] ) ); ?></p>
					<?php else : ?>
						<p style="color: red; font-weight: bold;"><?php esc_html_e( 'Connection Failed', 'jcp-core' ); ?></p>
						<p><?php echo esc_html( $api_status['message'] ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

// ==================== 9. SHORTCODE (optional; theme directory uses jcp_core_company_data) ====================
add_shortcode( 'jcp_companies', 'jcp_companies_shortcode' );

function jcp_companies_shortcode( $atts ): string {
	if ( ! post_type_exists( 'jcp_company' ) ) {
		return '<p>' . esc_html__( 'Company directory is not available.', 'jcp-core' ) . '</p>';
	}
	$atts = shortcode_atts( [
		'limit'            => 10,
		'columns'          => 3,
		'show_description' => true,
		'show_address'     => true,
		'show_phone'       => true,
		'show_logo'        => true,
		'show_industries'  => true,
		'show_tags'        => true,
	], $atts, 'jcp_companies' );

	$query = new WP_Query( [
		'post_type'      => 'jcp_company',
		'posts_per_page' => (int) $atts['limit'],
		'post_status'    => 'publish',
	] );

	if ( ! $query->have_posts() ) {
		return '<p>' . esc_html__( 'No companies found.', 'jcp-core' ) . '</p>';
	}

	ob_start();
	?>
	<div class="jcp-companies-grid">
		<?php
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();
			$post    = get_post( $post_id );
			$data    = function_exists( 'jcp_core_company_data' ) ? jcp_core_company_data( $post ) : [];
			?>
			<div class="jcp-company-card">
				<?php if ( ! empty( $atts['show_logo'] ) && ! empty( $data['logo'] ) ) : ?>
					<div class="jcp-company-logo"><img src="<?php echo esc_url( $data['logo'] ); ?>" alt="<?php the_title_attribute( [ 'post' => $post_id ] ); ?>"></div>
				<?php endif; ?>
				<h3><?php the_title(); ?></h3>
				<?php if ( ! empty( $atts['show_description'] ) && ! empty( $data['description'] ) ) : ?>
					<div class="jcp-company-description"><?php echo esc_html( wp_trim_words( $data['description'], 30, '...' ) ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $atts['show_industries'] ) && ! empty( $data['service'] ) ) : ?>
					<div class="jcp-company-industries"><strong><?php esc_html_e( 'Industries:', 'jcp-core' ); ?></strong> <?php echo esc_html( $data['service'] ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $atts['show_address'] ) && ! empty( $data['addressFormatted'] ) ) : ?>
					<div class="jcp-company-address"><strong><?php esc_html_e( 'Address:', 'jcp-core' ); ?></strong> <?php echo esc_html( $data['addressFormatted'] ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $atts['show_phone'] ) && ! empty( $data['phone'] ) ) : ?>
					<div class="jcp-company-phone"><strong><?php esc_html_e( 'Phone:', 'jcp-core' ); ?></strong> <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $data['phone'] ) ); ?>"><?php echo esc_html( $data['phone'] ); ?></a></div>
				<?php endif; ?>
				<?php if ( ! empty( $data['website'] ) ) : ?>
					<div class="jcp-company-website"><a href="<?php echo esc_url( $data['website'] ); ?>" target="_blank" rel="noopener" class="button"><?php esc_html_e( 'Visit Website', 'jcp-core' ); ?></a></div>
				<?php endif; ?>
			</div>
			<?php
		}
		wp_reset_postdata();
		?>
	</div>
	<style>
		.jcp-companies-grid { display: grid; grid-template-columns: repeat(<?php echo (int) $atts['columns']; ?>, 1fr); gap: 20px; margin: 20px 0; }
		.jcp-company-card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; background: #fff; }
		.jcp-company-card h3 { margin-top: 0; margin-bottom: 10px; color: #2c3e50; font-size: 1.3em; }
		.jcp-company-description { margin-bottom: 12px; color: #555; line-height: 1.5; font-size: 0.95em; }
		.jcp-company-website .button { background: #0073aa; color: #fff; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; }
		@media (max-width: 768px) { .jcp-companies-grid { grid-template-columns: 1fr; } }
	</style>
	<?php
	return ob_get_clean();
}

// ==================== 10. API TEST FUNCTION ====================
function jcp_test_api_connection(): array {
	$result = jcp_fetch_companies( false );
	if ( $result['success'] ) {
		return [
			'success' => true,
			'count'   => $result['total'],
			'message' => __( 'Connected successfully', 'jcp-core' ),
		];
	}
	return [
		'success' => false,
		'message' => $result['error'],
		'count'   => 0,
	];
}

// ==================== 11. CUSTOM ADMIN COLUMNS ====================
add_filter( 'manage_jcp_company_posts_columns', 'jcp_add_custom_columns' );
add_action( 'manage_jcp_company_posts_custom_column', 'jcp_render_custom_columns', 10, 2 );

function jcp_add_custom_columns( array $columns ): array {
	return [
		'cb'              => $columns['cb'],
		'title'           => $columns['title'],
		'jcp_id'          => __( 'Company ID', 'jcp-core' ),
		'jcp_industries'  => __( 'Industries', 'jcp-core' ),
		'jcp_tags'        => __( 'Tags', 'jcp-core' ),
		'jcp_phone'       => __( 'Phone', 'jcp-core' ),
		'jcp_last_sync'   => __( 'Last Synced', 'jcp-core' ),
		'date'            => $columns['date'],
	];
}

function jcp_render_custom_columns( string $column, int $post_id ): void {
	switch ( $column ) {
		case 'jcp_id':
			echo esc_html( get_post_meta( $post_id, '_jcp_company_id', true ) ?: '—' );
			break;
		case 'jcp_industries':
			$industries = get_post_meta( $post_id, '_jcp_selected_industries_string', true );
			if ( $industries === '' ) {
				$arr = get_post_meta( $post_id, '_jcp_selected_industries', true );
				$industries = is_array( $arr ) ? implode( ', ', $arr ) : '';
			}
			echo esc_html( $industries ?: '—' );
			break;
		case 'jcp_tags':
			$tags = get_post_meta( $post_id, '_jcp_checkin_tags_string', true );
			if ( $tags === '' ) {
				$arr = get_post_meta( $post_id, '_jcp_checkin_tags', true );
				$tags = is_array( $arr ) ? implode( ', ', $arr ) : '';
			}
			echo esc_html( $tags ?: '—' );
			break;
		case 'jcp_phone':
			echo esc_html( get_post_meta( $post_id, '_jcp_phone', true ) ?: '—' );
			break;
		case 'jcp_last_sync':
			echo esc_html( get_post_meta( $post_id, '_jcp_last_synced', true ) ?: '—' );
			break;
	}
}

// ==================== 12. META BOX ====================
add_action( 'add_meta_boxes', 'jcp_add_meta_boxes' );

function jcp_add_meta_boxes(): void {
	if ( ! post_type_exists( 'jcp_company' ) ) {
		return;
	}
	add_meta_box( 'jcp_company_info', __( 'Company Information', 'jcp-core' ), 'jcp_render_meta_box', 'jcp_company', 'normal', 'high' );
}

function jcp_render_meta_box( WP_Post $post ): void {
	$company_id = get_post_meta( $post->ID, '_jcp_company_id', true );
	$website    = get_post_meta( $post->ID, '_jcp_website_url', true );
	$phone      = get_post_meta( $post->ID, '_jcp_phone', true );
	$address    = get_post_meta( $post->ID, '_jcp_address_formatted', true );
	if ( $address === '' ) {
		$address = get_post_meta( $post->ID, '_jcp_address', true );
	}
	$industries = get_post_meta( $post->ID, '_jcp_selected_industries_string', true );
	if ( $industries === '' ) {
		$arr = get_post_meta( $post->ID, '_jcp_selected_industries', true );
		$industries = is_array( $arr ) ? implode( ', ', $arr ) : '';
	}
	$tags = get_post_meta( $post->ID, '_jcp_checkin_tags_string', true );
	if ( $tags === '' ) {
		$arr  = get_post_meta( $post->ID, '_jcp_checkin_tags', true );
		$tags = is_array( $arr ) ? implode( ', ', $arr ) : '';
	}
	?>
	<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
		<div>
			<label style="display: block; margin-bottom: 5px; font-weight: bold;"><?php esc_html_e( 'Company ID:', 'jcp-core' ); ?></label>
			<input type="text" value="<?php echo esc_attr( $company_id ); ?>" readonly style="width: 100%; padding: 8px; border: 1px solid #ddd;">
		</div>
		<div>
			<label style="display: block; margin-bottom: 5px; font-weight: bold;"><?php esc_html_e( 'Website:', 'jcp-core' ); ?></label>
			<input type="url" value="<?php echo esc_url( $website ); ?>" readonly style="width: 100%; padding: 8px; border: 1px solid #ddd;">
		</div>
		<div>
			<label style="display: block; margin-bottom: 5px; font-weight: bold;"><?php esc_html_e( 'Phone:', 'jcp-core' ); ?></label>
			<input type="text" value="<?php echo esc_attr( $phone ); ?>" readonly style="width: 100%; padding: 8px; border: 1px solid #ddd;">
		</div>
		<div>
			<label style="display: block; margin-bottom: 5px; font-weight: bold;"><?php esc_html_e( 'Last Synced:', 'jcp-core' ); ?></label>
			<input type="text" value="<?php echo esc_attr( get_post_meta( $post->ID, '_jcp_last_synced', true ) ); ?>" readonly style="width: 100%; padding: 8px; border: 1px solid #ddd;">
		</div>
		<div style="grid-column: span 2;">
			<label style="display: block; margin-bottom: 5px; font-weight: bold;"><?php esc_html_e( 'Address:', 'jcp-core' ); ?></label>
			<textarea readonly style="width: 100%; padding: 8px; border: 1px solid #ddd; height: 60px; resize: vertical;"><?php echo esc_textarea( $address ); ?></textarea>
		</div>
		<div style="grid-column: span 2;">
			<label style="display: block; margin-bottom: 5px; font-weight: bold;"><?php esc_html_e( 'Industries:', 'jcp-core' ); ?></label>
			<input type="text" value="<?php echo esc_attr( $industries ); ?>" readonly style="width: 100%; padding: 8px; border: 1px solid #ddd;">
		</div>
		<div style="grid-column: span 2;">
			<label style="display: block; margin-bottom: 5px; font-weight: bold;"><?php esc_html_e( 'Tags:', 'jcp-core' ); ?></label>
			<input type="text" value="<?php echo esc_attr( $tags ); ?>" readonly style="width: 100%; padding: 8px; border: 1px solid #ddd;">
		</div>
	</div>
	<?php
}

// ==================== 13. CRON JOB ====================
add_action( 'init', 'jcp_schedule_cron' );

function jcp_schedule_cron(): void {
	if ( ! wp_next_scheduled( 'jcp_daily_import' ) ) {
		wp_schedule_event( time(), 'daily', 'jcp_daily_import' );
	}
}

add_action( 'jcp_daily_import', 'jcp_do_daily_import' );

function jcp_do_daily_import(): void {
	$result = jcp_import_companies( false );
	error_log( 'JCP Daily Import: ' . $result['message'] );
}

// ==================== 14. ADMIN STYLES ====================
add_action( 'admin_head', 'jcp_admin_styles' );

function jcp_admin_styles(): void {
	$screen = get_current_screen();
	if ( ! $screen || $screen->post_type !== 'jcp_company' ) {
		return;
	}
	?>
	<style>
		.column-jcp_id { width: 100px; }
		.column-jcp_industries { width: 150px; }
		.column-jcp_tags { width: 150px; }
		.column-jcp_phone { width: 120px; }
		.column-jcp_last_sync { width: 150px; }
	</style>
	<?php
}

// ==================== 15. ADMIN NOTICE IF NO TOKEN ====================
add_action( 'admin_notices', 'jcp_admin_notice' );

function jcp_admin_notice(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( post_type_exists( 'jcp_company' ) && jcp_core_get_api_token() !== '' ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || strpos( $screen->id, 'jcp_company' ) === false ) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'JCP Companies:', 'jcp-core' ) . '</strong> ';
	echo esc_html__( 'API token not set. Define JCP_API_TOKEN in wp-config.php or set the option jcp_core_api_token. Import from API will not work until configured.', 'jcp-core' );
	echo '</p></div>';
}
