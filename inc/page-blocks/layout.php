<?php
/**
 * Block layout options (alignment, width, hero variants).
 *
 * @package JCP_Core
 */

/**
 * Valid hero layout variants.
 *
 * @return array<int, string>
 */
function jcp_block_hero_variants(): array {
	return [ 'split', 'centered', 'stacked', 'home' ];
}

/**
 * Default layout per block type.
 *
 * @param string $type      Block type.
 * @param string $page_kind Page kind.
 * @return array<string, mixed>
 */
function jcp_block_default_layout( string $type, string $page_kind = 'industry' ): array {
	if ( $type === 'hero' ) {
		return [
			'hero_variant' => $page_kind === 'referral' ? 'centered' : ( $page_kind === 'home' ? 'home' : 'split' ),
		];
	}

	$layout = [
		'align' => 'center',
		'width' => 'contained',
	];

	if ( $type === 'breadcrumb' ) {
		$layout['align'] = 'left';
	}

	return $layout;
}

/**
 * Resolve hero variant from layout (with legacy migration).
 *
 * @param array<string, mixed> $layout Resolved/partial layout.
 */
function jcp_block_resolve_hero_variant( array $layout ): string {
	$variant = (string) ( $layout['hero_variant'] ?? '' );
	if ( in_array( $variant, jcp_block_hero_variants(), true ) ) {
		return $variant;
	}
	if ( array_key_exists( 'hero_visual', $layout ) && empty( $layout['hero_visual'] ) ) {
		return 'centered';
	}
	if ( ( $layout['align'] ?? '' ) === 'center' && empty( $layout['hero_visual'] ) ) {
		return 'centered';
	}
	return 'split';
}

/**
 * Resolve layout for a block (defaults + stored values + hero props).
 *
 * @param array<string, mixed> $block     Block array.
 * @param string               $page_kind Page kind.
 * @return array<string, mixed>
 */
function jcp_block_resolve_layout( array $block, string $page_kind = 'industry' ): array {
	$type   = (string) ( $block['type'] ?? '' );
	$stored = is_array( $block['layout'] ?? null ) ? $block['layout'] : [];
	$layout = array_merge( jcp_block_default_layout( $type, $page_kind ), $stored );

	if ( $type === 'hero' ) {
		$layout['hero_variant'] = jcp_block_resolve_hero_variant( $layout );
		return $layout;
	}

	$align = (string) ( $layout['align'] ?? 'center' );
	$width = (string) ( $layout['width'] ?? 'contained' );
	$layout['align'] = in_array( $align, [ 'left', 'center', 'right' ], true ) ? $align : 'center';
	$layout['width'] = in_array( $width, [ 'contained', 'wide', 'full' ], true ) ? $width : 'contained';

	return $layout;
}

/**
 * CSS classes for a block root from layout settings.
 *
 * @param array<string, mixed> $layout Resolved layout.
 * @param string               $type   Block type.
 */
function jcp_block_layout_classes( array $layout, string $type ): string {
	if ( $type === 'hero' ) {
		$variant = jcp_block_resolve_hero_variant( $layout );
		return 'jcp-block-root jcp-hero-variant-' . $variant;
	}

	$classes = [
		'jcp-block-root',
		'jcp-layout-align-' . (string) ( $layout['align'] ?? 'center' ),
		'jcp-layout-width-' . (string) ( $layout['width'] ?? 'contained' ),
	];

	return implode( ' ', $classes );
}

/**
 * Layout controls exposed to the front-end editor per block type.
 *
 * @param string $type Block type.
 * @return array<string, bool>
 */
function jcp_block_layout_options( string $type ): array {
	if ( $type === 'hero' ) {
		return [
			'hero_variant' => true,
		];
	}
	if ( $type === 'media_text' ) {
		return [
			'media_position' => true,
			'align'          => true,
			'width'          => true,
		];
	}
	return [
		'align' => true,
		'width' => true,
	];
}

/**
 * Human labels for hero variants (editor + docs).
 *
 * @return array<string, string>
 */
function jcp_block_hero_variant_labels(): array {
	return [
		'split'    => __( 'Split — copy + demo image', 'jcp-core' ),
		'centered' => __( 'Centered — headline & CTA focus', 'jcp-core' ),
		'stacked'  => __( 'Stacked — copy above visual', 'jcp-core' ),
		'home'     => __( 'Homepage — rotating headline + live phone', 'jcp-core' ),
	];
}
