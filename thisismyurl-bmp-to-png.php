<?php
/**
 * Plugin Name:       Thisismyurl BMP to PNG
 * Description:       Converts BMP images to PNG as they are uploaded to the Media Library. No settings, no external services.
 * Version:           1.6291
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Christopher Ross
 * Author URI:        https://thisismyurl.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       thisismyurl-bmp-to-png
 *
 * @package ThisIsMyURL\BmpToPng
 */

namespace ThisIsMyURL\BmpToPng;

defined( 'ABSPATH' ) || exit;

/**
 * Replaces an uploaded BMP with a PNG copy of the same image.
 *
 * Runs on `wp_handle_upload`, which WordPress applies to browser, REST API and
 * sideloaded (WP-CLI) uploads alike, after the file has been moved into the
 * uploads folder and before the attachment is created. If the file is not a BMP,
 * or the image library cannot convert it, the upload is returned unchanged and
 * still succeeds.
 *
 * @param array  $upload {
 *      Result of the upload.
 *
 *     @type string $file Absolute path of the uploaded file.
 *     @type string $url  URL of the uploaded file.
 *     @type string $type MIME type of the uploaded file.
 * }
 * @param string $context Either 'upload' or 'sideload', as passed by WordPress.
 * @return array The upload array, pointing at the PNG when conversion succeeded.
 */
function convert_upload( $upload, $context = 'upload' ) {
	if ( ! is_array( $upload ) || ! empty( $upload['error'] ) || empty( $upload['file'] ) || empty( $upload['url'] ) ) {
		return $upload;
	}

	$extension = strtolower( pathinfo( $upload['file'], PATHINFO_EXTENSION ) );
	if ( 'bmp' !== $extension ) {
		return $upload;
	}

	/**
	 * Filters whether an uploaded BMP is converted to PNG.
	 *
	 * @since 1.6291
	 *
	 * @param bool   $convert Whether to convert. Default true.
	 * @param array  $upload  The upload array (file, url, type).
	 * @param string $context 'upload' for form and multipart uploads; 'sideload' for files WordPress fetched or received as a raw body (raw REST uploads, URL imports, WP-CLI).
	 */
	if ( ! apply_filters( 'thisismyurl_bmp_to_png_convert', true, $upload, $context ) ) {
		return $upload;
	}

	// Decoding a large bitmap needs more memory than a normal request; WordPress does the same before image work.
	wp_raise_memory_limit( 'image' );

	// WordPress picks the editor from the file's own type (image/bmp), which GD does not list as writable.
	// Asking for a PNG editor lets GD and Imagick both do the work; either can still read the BMP.
	$editor = wp_get_image_editor( $upload['file'], array( 'mime_type' => 'image/png' ) );
	if ( is_wp_error( $editor ) ) {
		return $upload;
	}

	$directory = dirname( $upload['file'] );
	$filename  = wp_unique_filename( $directory, pathinfo( $upload['file'], PATHINFO_FILENAME ) . '.png' );
	$saved     = $editor->save( trailingslashit( $directory ) . $filename, 'image/png' );
	if ( is_wp_error( $saved ) || empty( $saved['path'] ) || ! file_exists( $saved['path'] ) ) {
		return $upload;
	}

	wp_delete_file( $upload['file'] );

	$upload['file'] = $saved['path'];
	$upload['url']  = trailingslashit( dirname( $upload['url'] ) ) . wp_basename( $saved['path'] );
	$upload['type'] = ! empty( $saved['mime-type'] ) ? $saved['mime-type'] : 'image/png';

	return $upload;
}
add_filter( 'wp_handle_upload', __NAMESPACE__ . '\convert_upload', 10, 2 );
