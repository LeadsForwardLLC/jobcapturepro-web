<?php
/**
 * Block layout options (alignment, width, hero visual).
 *
 * @package JCP_Core
 */

/**
 * Default layout per block type.
 *
 * @param string $type      Block type.
 * @param string $page_kind Page kind.
 * @return array<string, mixed>
 */
function jcp_block_default_layout( string $type, string $page_kind = 'industry' ): array {
	$layout = [
		'align' => 'center',
		'width' => 'contained',
	];

	if ( $type === 'hero' ) {
		$layout['align']       = $page_kind === 'referral' ? 'center' : 'left';
		$layout['hero_visual'] = $page_kind !== 'referral';
	}

	if ( $type === 'breadcrumb' ) {
		$layout['align'] = 'left';
	}

	return $layout;
}

/**
 * Resolve layout for a block (defaults + stored values + hero props).
 *
 * @param array<string, mixed> $block     Block array.
 * @param string               $page_kind Page kind.
 * @return array<string, mixed>
 */
function jcp_block_resolve_layout( array $block, string $page_kind = 'industry' ): array {
	$type     = (string) ( $block['type'] ?? '' );
	$stored   = is_array( $block['layout'] ?? null ) ? $block['layout'] : [];
	$layout   = array_merge( jcp_block_default_layout( $type, $page_kind ), $stored );
	$align    = (string) ( $layout['align'] ?? 'center' );
	$width    = (string) ( $layout['width'] ?? 'contained' );
	$layout['align'] = in_array( $align, [ 'left', 'center', 'right' ], true ) ? $align : 'center';
	$layout['width'] = in_array( $width, [ 'contained', 'wide', 'full' ], true ) ? $width : 'contained';

	if ( $type === 'hero' ) {
		if ( isset( $block['props']['show_visual'] ) && ! isset( $stored['hero_visual'] ) ) {
			$layout['hero_visual'] = ! empty( $block['props']['show_visual'] );
		}
		$layout['hero_visual'] = ! empty( $layout['hero_visual'] );
	}

	return $layout;
}

/**
 * CSS classes for a block root from layout settings.
 *
 * @param array<string, mixed> $layout Resolved layout.
 * @param string               $type   Block type.
 */
function jcp_block_layout_classes( array $layout, string $type ): string {
	$classes = [
		'jcp-layout-align-' . (string) ( $layout['align'] ?? 'center' ),
		'jcp-layout-width-' . (string) ( $layout['width'] ?? 'contained' ),
	];

	if ( $type === 'hero' && empty( $layout['hero_visual'] ) ) {
		$classes[] = 'jcp-layout-hero-copy-only';
	}

	return implode( ' ', $classes );
}

/**
 * Layout controls exposed to the front-end editor per block type.
 *
 * @param string $type Block type.
 * @return array<string, bool>
 */
function jcp_block_layout_options( string $type ): array {
	return [
		'align'       => ! in_array( $type, [], true ),
		'width'       => true,
		'hero_visual' => $type === 'hero',
	];
}
