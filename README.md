# Thisismyurl BMP to PNG

Converts BMP images to PNG as they are uploaded to the WordPress Media Library. No settings, no external services.

## Install

Download the zip from the [latest release](https://github.com/thisismyurl/thisismyurl-bmp-to-png/releases/latest), then in WordPress go to Plugins > Add New Plugin > Upload Plugin. More about it: https://plugins.thisismyurl.com/thisismyurl-bmp-to-png/

This plugin is distributed from GitHub and plugins.thisismyurl.com, not the WordPress.org directory, so WordPress will not offer automatic updates for it. Its conversion is being folded into a larger image-format plugin (Thisismyurl Image Formats), which will become the maintained home for BMP, HEIC, TIFF and other format handling.

## What it does

On `wp_handle_upload` (browser, REST API and WP-CLI uploads alike) a BMP is converted to PNG with WordPress's own image editor and the original BMP is removed once the PNG is saved and verified. Anything that is not a BMP, or that the server cannot convert, is left untouched and the upload still succeeds.

See `readme.txt` for the full description, FAQ and changelog.

Licensed GPL-2.0-or-later.
