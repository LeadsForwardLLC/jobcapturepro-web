<?php
/**
 * Shared media slot rendering for block sections (image / video / phone mockup).
 *
 * @package JCP_Core
 */

/**
 * Normalize media type string.
 *
 * @param string $type Raw type.
 */
function jcp_media_normalize_type( string $type ): string {
	$type = sanitize_key( $type );
	return in_array( $type, [ 'image', 'video', 'phone_mockup' ], true ) ? $type : 'image';
}

/**
 * Echo data attributes for an editable media field group.
 *
 * @param string               $path   Flat content path prefix (e.g. conversion, hero).
 * @param array<string, mixed> $extra  Extra data attributes.
 */
function jcp_media_editable_attrs( string $path, array $extra = [] ): void {
	$attrs = array_merge(
		[
			'data-jcp-media-path' => $path,
		],
		$extra
	);
	foreach ( $attrs as $key => $value ) {
		if ( $value === null || $value === '' ) {
			continue;
		}
		printf( ' %s="%s"', esc_attr( (string) $key ), esc_attr( (string) $value ) );
	}
}

/**
 * Render a video embed or file player.
 *
 * @param string $url   Video URL.
 * @param string $title Accessible title.
 */
function jcp_media_render_video( string $url, string $title = '' ): void {
	$url   = trim( $url );
	$title = $title !== '' ? $title : __( 'Video', 'jcp-core' );
	if ( $url === '' ) {
		return;
	}
	?>
	<div class="jcp-media-text-video-wrap jcp-media-video-wrap">
		<?php if ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $yt ) ) : ?>
			<iframe src="https://www.youtube.com/embed/<?php echo esc_attr( $yt[1] ); ?>" title="<?php echo esc_attr( $title ); ?>" allowfullscreen loading="lazy"></iframe>
		<?php elseif ( preg_match( '/vimeo\.com\/(\d+)/', $url, $vm ) ) : ?>
			<iframe src="https://player.vimeo.com/video/<?php echo esc_attr( $vm[1] ); ?>" title="<?php echo esc_attr( $title ); ?>" allowfullscreen loading="lazy"></iframe>
		<?php else : ?>
			<video class="jcp-media-text-video jcp-media-video-file" src="<?php echo esc_url( $url ); ?>" controls playsinline preload="metadata"></video>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Render image with per-instance alt text.
 *
 * @param string               $url          Image URL.
 * @param string               $alt          Alt text for this block instance.
 * @param string               $path         Flat path prefix for editor.
 * @param array<string, mixed> $img_attrs    Extra img attributes.
 */
function jcp_media_render_image( string $url, string $alt, string $url_path, string $alt_path, array $img_attrs = [] ): void {
	$url = trim( $url );
	$class = 'jcp-editable-media-image';
	if ( ! empty( $img_attrs['class'] ) ) {
		$class .= ' ' . (string) $img_attrs['class'];
		unset( $img_attrs['class'] );
	}
	?>
	<img
		<?php if ( $url !== '' ) : ?>src="<?php echo esc_url( $url ); ?>" <?php endif; ?>
		alt="<?php echo esc_attr( $alt ); ?>"
		class="<?php echo esc_attr( $class ); ?>"
		data-jcp-media-url-path="<?php echo esc_attr( $url_path ); ?>"
		data-jcp-media-alt-path="<?php echo esc_attr( $alt_path ); ?>"
		<?php
		foreach ( $img_attrs as $attr => $val ) {
			printf( '%s="%s" ', esc_attr( (string) $attr ), esc_attr( (string) $val ) );
		}
		?>
	/>
	<?php
}

/**
 * Render a media slot with image, video, and optional phone mockup variants.
 *
 * @param array<string, mixed> $config {
 *   @type string        $path          Flat content path prefix.
 *   @type string        $media_type    image|video|phone_mockup.
 *   @type string        $media_url     Media URL.
 *   @type string        $media_alt     Per-instance alt text.
 *   @type string        $url_path      Flat path for URL (default path.media_url).
 *   @type string        $alt_path      Flat path for alt (default path.media_alt).
 *   @type string        $default_image Fallback image URL.
 *   @type callable|null $phone_render  Callback to render phone mockup markup.
 *   @type array<string,mixed> $img_attrs Extra img tag attributes.
 * }
 */
function jcp_media_render_slot( array $config ): void {
	$path          = (string) ( $config['path'] ?? '' );
	$media_type    = jcp_media_normalize_type( (string) ( $config['media_type'] ?? 'image' ) );
	$media_url     = trim( (string) ( $config['media_url'] ?? '' ) );
	$media_alt     = trim( (string) ( $config['media_alt'] ?? '' ) );
	$default_image = trim( (string) ( $config['default_image'] ?? '' ) );
	$phone_render  = $config['phone_render'] ?? null;
	$img_attrs     = is_array( $config['img_attrs'] ?? null ) ? $config['img_attrs'] : [];
	$url_path      = (string) ( $config['url_path'] ?? ( $path !== '' ? $path . '.media_url' : '' ) );
	$alt_path      = (string) ( $config['alt_path'] ?? ( $path !== '' ? $path . '.media_alt' : '' ) );
	$image_url     = $media_url !== '' ? $media_url : $default_image;

	if ( $media_type === 'phone_mockup' && ! is_callable( $phone_render ) ) {
		$media_type = 'image';
	}
	$types_attr = is_callable( $phone_render ) ? 'image,video,phone_mockup' : 'image,video';
	?>
	<div
		class="jcp-media-slot"
		<?php
		jcp_media_editable_attrs(
			$path,
			[
				'data-jcp-media-type'     => $media_type,
				'data-jcp-media-types'    => $types_attr,
				'data-jcp-media-url-path' => $url_path,
				'data-jcp-media-alt-path' => $alt_path,
			]
		);
		?>
	>
		<?php if ( is_callable( $phone_render ) ) : ?>
			<div class="jcp-media-variant jcp-media-variant--phone_mockup"<?php echo $media_type !== 'phone_mockup' ? ' hidden' : ''; ?>>
				<?php $phone_render(); ?>
			</div>
		<?php endif; ?>
		<div class="jcp-media-variant jcp-media-variant--image"<?php echo $media_type !== 'image' ? ' hidden' : ''; ?>>
			<?php
			if ( $url_path !== '' && $alt_path !== '' ) {
				jcp_media_render_image( $image_url, $media_alt, $url_path, $alt_path, $img_attrs );
			}
			?>
		</div>
		<div class="jcp-media-variant jcp-media-variant--video"<?php echo $media_type !== 'video' ? ' hidden' : ''; ?>>
			<?php jcp_media_render_video( $media_url, $media_alt ); ?>
		</div>
	</div>
	<?php
}

/**
 * Split-layout modifier class for media on left or right.
 *
 * @param string $position left|right.
 */
function jcp_media_position_class( string $position ): string {
	return 'jcp-split-layout--media-' . ( $position === 'left' ? 'left' : 'right' );
}

/**
 * Default photo shown inside the hero phone mockup.
 */
function jcp_media_default_phone_image(): string {
	return 'https://jobcapturepro.com/wp-content/uploads/2025/12/jcp-user-photo.jpg';
}

/**
 * Resolve the image URL for a phone mockup screen photo.
 *
 * @param array<string, mixed> $props Block / hero props.
 */
function jcp_media_resolve_phone_image( array $props ): string {
	$url = trim( (string) ( $props['phone_image_url'] ?? '' ) );
	if ( $url !== '' ) {
		return $url;
	}
	return jcp_media_default_phone_image();
}

/**
 * Read media fields from props with legacy image_url / image_alt aliases.
 *
 * @param array<string, mixed> $props Block props.
 * @return array{media_type:string,media_url:string,media_alt:string,media_position:string,phone_image_url:string}
 */
function jcp_media_props_from_block( array $props ): array {
	$url = trim( (string) ( $props['media_url'] ?? $props['image_url'] ?? '' ) );
	$alt = trim( (string) ( $props['media_alt'] ?? $props['image_alt'] ?? '' ) );
	$type = (string) ( $props['media_type'] ?? '' );
	if ( $type === '' && ! empty( $props['phone_image_url'] ) ) {
		$type = 'phone_mockup';
	}

	return [
		'media_type'       => $type !== '' ? jcp_media_normalize_type( $type ) : 'image',
		'media_url'        => $url,
		'media_alt'        => $alt,
		'phone_image_url'  => trim( (string) ( $props['phone_image_url'] ?? '' ) ),
		'media_position'   => in_array( (string) ( $props['media_position'] ?? 'right' ), [ 'left', 'right' ], true )
			? (string) $props['media_position']
			: 'right',
	];
}
