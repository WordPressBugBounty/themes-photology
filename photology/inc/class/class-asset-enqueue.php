<?php
/**
 * Block Pattern Class
 *
 * @author Jegstudio
 * @package photology
 */
namespace Photology;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Init Class
 *
 * @package photology
 */
class Asset_Enqueue {
	/**
	 * Class constructor.
	 */
	public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 20 );
	}

    /**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'photology-style', get_stylesheet_uri(), array(), PHOTOLOGY_VERSION );

				wp_enqueue_style( 'presset', PHOTOLOGY_URI . '/assets/css/presset.css', array(), PHOTOLOGY_VERSION );
		wp_enqueue_style( 'custom-styling', PHOTOLOGY_URI . '/assets/css/custom-styling.css', array(), PHOTOLOGY_VERSION );
		wp_enqueue_script( 'animation-script', PHOTOLOGY_URI . '/assets/js/animation-script.js', array(), PHOTOLOGY_VERSION, true );


        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
    }

	/**
	 * Enqueue admin scripts and styles.
	 */
	public function admin_scripts() {
		
    }
}
