=== Thisismyurl BMP to PNG ===
Contributors: thisismyurl
Tags: bmp, png, images, media, upload
Requires at least: 6.7
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.6291
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Converts BMP images to PNG as they are uploaded to the Media Library.

== Description ==

BMP files are large and uncompressed, and browsers handle them poorly compared with PNG. WordPress lets you upload a BMP, but it keeps the file exactly as it is.

This plugin does one thing. When you upload a BMP, it saves a PNG copy of the same image and uses that in the Media Library instead. PNG is lossless, so the pixels are identical, and the file is usually much smaller. WordPress then makes its normal thumbnail sizes from the PNG.

There is nothing to configure. Activate it and upload.

* Works with uploads from the Media Library, the block editor, the REST API and WP-CLI.
* Uses the image library WordPress already uses (GD or Imagick). Nothing is installed on your server.
* No settings page, no database options, no scheduled tasks.
* No external services. Nothing leaves your site.
* If your server's image library cannot read a BMP, the file is uploaded as it was and the upload still succeeds.

= What it does not do =

* It does not touch BMP files that are already in your Media Library. Only new uploads are converted.
* It does not keep the original BMP. After a successful conversion the BMP is deleted and only the PNG remains.

= For developers =

To skip conversion for a particular upload, return false from the `thisismyurl_bmp_to_png_convert` filter. It receives the upload array (`file`, `url`, `type`) and the context (`upload` or `sideload`).

`add_filter( 'thisismyurl_bmp_to_png_convert', '__return_false' );`

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`, or use Plugins > Add New Plugin > Upload Plugin.
2. Activate the plugin.
3. Upload a BMP. It is converted to PNG automatically.

== Frequently Asked Questions ==

= Does WordPress not already support BMP uploads? =

It does. WordPress accepts BMP uploads on its own. This plugin does not add upload support. It changes what happens after the upload, by converting the file to PNG.

= Is any quality lost? =

No. PNG is lossless, so the converted image has the same pixels as the BMP. An embedded colour profile, which only some newer BMP files carry, is not kept.

= Why PNG and not JPEG? =

BMP files are usually screenshots, diagrams and graphics with sharp edges and flat colour. PNG keeps those exactly, where JPEG blurs them and loses detail.

= Can I get my original BMP back? =

No. The BMP is deleted once the PNG has been saved. Keep your own copy if you need the original file.

= Why was my BMP not converted? =

Your server's image library could not read the file, or it was not a real BMP. In that case the file is uploaded unchanged. Imagick reads every common kind of BMP. If your server only has GD, it reads the usual 24-bit and palette BMPs but can fail on rarer ones, such as 32-bit files with a newer header. Ask your host to enable Imagick if that happens.

= Does it convert the BMP files already in my library? =

No, only new uploads.

== Changelog ==

= 1.6291 =
* First release.
