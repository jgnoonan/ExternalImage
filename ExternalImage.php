<?php
if ( !defined( 'MEDIAWIKI' ) ) {
    exit;
}

class ExternalImage {
    public static function onParserFirstCallInit( Parser $parser ) {
        $parser->setHook( 'img', [ __CLASS__, 'renderExtImg' ] );
    }

    public static function renderExtImg( $input, array $args, Parser $parser, PPFrame $frame ) {
        // Allow fallback if src is embedded inside content (e.g., <img>https://url</img>)
        $input = trim( $input );
        if ( empty( $args['src'] ) && filter_var( $input, FILTER_VALIDATE_URL ) ) {
            $args['src'] = $input;
        }

        if ( empty( $args['src'] ) ) {
            return '<span style="color:red;">' . wfMessage( 'externalimage-error-src' )->escaped() . '</span>';
        }

        $src    = htmlspecialchars( $args['src'] );
        $width  = isset( $args['width'] ) ? htmlspecialchars( $args['width'] ) : '';
        $height = isset( $args['height'] ) ? htmlspecialchars( $args['height'] ) : '';
        $alt    = isset( $args['alt'] ) ? htmlspecialchars( $args['alt'] ) : '';
        $link   = isset( $args['href'] ) ? htmlspecialchars( $args['href'] ) : '';
        $newtab = isset( $args['newtab'] ) && strtolower( $args['newtab'] ) === 'true';

        $style = '';
        $imgSize = null;

        // Handle % dimensions
        if (( $width && strpos( $width, '%' ) !== false ) || ( $height && strpos( $height, '%' ) !== false )) {
            $imgSize = @getimagesize( $src );
        }

        if ( $width ) {
            if ( strpos( $width, '%' ) !== false && $imgSize ) {
                $computedWidth = $imgSize[0] * ( floatval( $width ) / 100 );
                $style .= "width:{$computedWidth}px;";
            } else {
                $style .= "width:{$width};";
            }
        }

        if ( $height ) {
            if ( strpos( $height, '%' ) !== false && $imgSize ) {
                $computedHeight = $imgSize[1] * ( floatval( $height ) / 100 );
                $style .= "height:{$computedHeight}px;";
            } else {
                $style .= "height:{$height};";
            }
        }

        // SVG pointer handling
        if ( preg_match( '/\.svg$/i', $src ) ) {
            $style .= "pointer-events:auto;";
        }

        $widthAttr = '';
        $heightAttr = '';

        if ( preg_match( '/\.svg$/i', $src ) ) {
            if ( $width && strpos( $width, '%' ) === false ) {
                $widthAttr = " width=\"{$width}\"";
            }
            if ( $height && strpos( $height, '%' ) === false ) {
                $heightAttr = " height=\"{$height}\"";
            }
        }

        $styleAttr = $style ? " style=\"{$style}\"" : '';
        $imgTag = "<img src=\"{$src}\" alt=\"{$alt}\"{$styleAttr}{$widthAttr}{$heightAttr} />";

        if ( $link ) {
            $target = $newtab ? " target=\"_blank\" rel=\"noopener noreferrer\"" : "";
            $imgTag = "<a href=\"{$link}\"{$target}>{$imgTag}</a>";
        }

        return $imgTag;
    }
}

