# ExternalImage MediaWiki Extension

This extension allows you to embed external images in MediaWiki pages with additional functionality for resizing, linking, and alt text support.

## Features

- Embed external images using the `<img>` tag
- Support for width and height (both pixel and percentage values)
- Add clickable links to images with optional new tab opening
- Special handling for SVG images
- Alt text support for accessibility

## Installation

1. Download and place the extension in your MediaWiki's `extensions/` directory
2. Add the following line to your `LocalSettings.php`:
```php
wfLoadExtension( 'ExternalImage' );
```

## Usage

Basic usage:
```
<img src="https://example.com/image.jpg" />
```

Full example with all options:
```
<img 
    src="https://example.com/image.jpg"
    width="500"
    height="300"
    alt="Description of the image"
    href="https://example.com"
    newtab="true"
/>
```

### Parameters

- `src` (required): URL of the external image
- `width` (optional): Width in pixels or percentage (e.g., "500" or "50%")
- `height` (optional): Height in pixels or percentage (e.g., "300" or "50%")
- `alt` (optional): Alternative text for accessibility
- `href` (optional): URL to link the image to
- `newtab` (optional): Set to "true" to open links in a new tab

## Requirements

- MediaWiki 1.35 or later

## License

This extension is licensed under the GPL-2.0-or-later license.

## Author

Joe

## Support

For bug reports and feature requests, please visit the [extension's page on MediaWiki.org](https://www.mediawiki.org/wiki/Extension:ExternalImage). 