<?php
/**
 * Theme Functions
 *
 * @author Jegstudio
 * @package photology
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

defined( 'PHOTOLOGY_VERSION' ) || define( 'PHOTOLOGY_VERSION', '1.2.1' );
defined( 'PHOTOLOGY_DIR' ) || define( 'PHOTOLOGY_DIR', trailingslashit( get_template_directory() ) );
defined( 'PHOTOLOGY_URI' ) || define( 'PHOTOLOGY_URI', trailingslashit( get_template_directory_uri() ) );

require get_parent_theme_file_path( 'inc/autoload.php' );

Photology\Init::instance();
