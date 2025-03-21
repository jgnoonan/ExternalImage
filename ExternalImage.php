<?php
if ( !defined( 'MEDIAWIKI' ) ) {
    exit;
}

class ExternalImage {
    public static function onParserFirstCallInit( Parser $parser ) {
        $parser->setHook( 'img', [ __CLASS__, 'renderExtImg' ] );
    }

    public static function renderExtImg( $input, array $args, Parser $parser, PPFrame $frame ) {
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
        if (( $width !== '' && strpos( $width, '%' ) !== false ) || ( $height !== '' && strpos( $height, '%' ) !== false )) {
            $imgSize = @getimagesize( $src );
        }
        
        if ( $width !== '' ) {
            if ( strpos( $width, '%' ) !== false && $imgSize ) {
                $perc = floatval( $width );
                $computedWidth = $imgSize[0] * ($perc / 100);
                $style .= "width:{$computedWidth}px;";
            } else {
                $style .= "width:{$width};";
            }
        }
        if ( $height !== '' ) {
            if ( strpos( $height, '%' ) !== false && $imgSize ) {
                $perc = floatval( $height );
                $computedHeight = $imgSize[1] * ($perc / 100);
                $style .= "height:{$computedHeight}px;";
            } else {
                $style .= "height:{$height};";
            }
        }

        $widthAttr = '';
        $heightAttr = '';
        if ( preg_match( '/\.svg$/i', $src ) ) {
            if ( $width !== '' && strpos( $width, '%' ) === false ) {
                $widthAttr = " width=\"{$width}\"";
            }
            if ( $height !== '' && strpos( $height, '%' ) === false ) {
                $heightAttr = " height=\"{$height}\"";
            }
            $style .= "pointer-events:auto;";
        }

        $imgTag = "<img src=\"{$src}\" alt=\"{$alt}\" style=\"{$style}\"{$widthAttr}{$heightAttr} />";

        if ( $link !== '' ) {
            $target = $newtab ? " target=\"_blank\" rel=\"noopener noreferrer\"" : "";
            $imgTag = "<a href=\"{$link}\"{$target}>{$imgTag}</a>";
        }

        return $imgTag;
    }
}
