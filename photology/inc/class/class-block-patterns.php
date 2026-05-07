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

use WP_Block_Pattern_Categories_Registry;

/**
 * Init Class
 *
 * @package photology
 */
class Block_Patterns {

	/**
	 * Instance variable
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Class instance.
	 *
	 * @return BlockPatterns
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->register_block_patterns();
		$this->register_synced_patterns();
	}

	/**
	 * Register Block Patterns
	 */
	private function register_block_patterns() {
		$block_pattern_categories = array(
			'photology-core' => array( 'label' => __( 'Photology Core Patterns', 'photology' ) ),
		);

		if ( defined( 'GUTENVERSE' ) ) {
			$block_pattern_categories['photology-gutenverse'] = array( 'label' => __( 'Photology Gutenverse Patterns', 'photology' ) );
			$block_pattern_categories['photology-pro'] = array( 'label' => __( 'Photology Gutenverse PRO Patterns', 'photology' ) );
		}

		$block_pattern_categories = apply_filters( 'photology_block_pattern_categories', $block_pattern_categories );

		foreach ( $block_pattern_categories as $name => $properties ) {
			if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $name ) ) {
				register_block_pattern_category( $name, $properties );
			}
		}

		$block_patterns = array(
            'photology-photology-gutenverse-footer',			'photology-photology-gutenverse-header',			'photology-photology-footer',			'photology-photology-gutenverse-404-hero',			'photology-photology-gutenverse-about-title',			'photology-photology-gutenverse-about',			'photology-photology-gutenverse-about-cta',			'photology-photology-gutenverse-about-team',			'photology-photology-gutenverse-about-funfact',			'photology-photology-gutenverse-archive-hero',			'photology-photology-gutenverse-blog-hero',			'photology-photology-gutenverse-contact-title',			'photology-photology-gutenverse-contact-content',			'photology-photology-gutenverse-hero',			'photology-photology-gutenverse-front-page-services',			'photology-photology-gutenverse-portfolio-2',			'photology-photology-gutenverse-testimonials',			'photology-photology-gutenverse-team',			'photology-photology-gutenverse-index-hero',			'photology-photology-gutenverse-page-hero',			'photology-photology-gutenverse-portfolio-grid-title',			'photology-photology-gutenverse-portfolio-grid',			'photology-photology-gutenverse-portfolio-masonry-title',			'photology-photology-gutenverse-portfolio-masonry',			'photology-photology-gutenverse-search-hero',			'photology-photology-gutenverse-service-title',			'photology-photology-gutenverse-service-content',			'photology-photology-gutenverse-benefits',			'photology-photology-gutenverse-testimonials',			'photology-photology-gutenverse-single-portfolio-title',			'photology-photology-gutenverse-single-portfolio',			'photology-photology-gutenverse-single-hero',			'photology-photology-gutenverse-single-content',			'photology-photology-404',			'photology-photology-hero-title-archive',			'photology-photology-section-3-hero',			'photology-photology-feature',			'photology-photology-section-4-gallery',			'photology-photology-testimonial',			'photology-photology-team',			'photology-photology-hero-title-index',			'photology-photology-post-title',			'photology-photology-hero-title-search',			'photology-photology-post-title',
		);

		if ( defined( 'GUTENVERSE' ) ) {
            
            
		}

		$block_patterns = apply_filters( 'photology_block_patterns', $block_patterns );
		$pattern_list   = get_option( 'photology_synced_pattern_imported', false );
		if ( ! $pattern_list ) {
			$pattern_list = array();
		}

		if ( function_exists( 'register_block_pattern' ) ) {
			foreach ( $block_patterns as $block_pattern ) {
				$pattern_file = get_theme_file_path( '/inc/patterns/' . $block_pattern . '.php' );
				$pattern_data = require $pattern_file;

				if ( (bool) $pattern_data['is_sync'] ) {
					$post = get_page_by_path( $block_pattern . '-synced', OBJECT, 'wp_block' );
					if ( empty( $post ) ) {
						$post_id = wp_insert_post(
							array(
								'post_name'    => $block_pattern . '-synced',
								'post_title'   => $pattern_data['title'],
								'post_content' => wp_slash( $pattern_data['content'] ),
								'post_status'  => 'publish',
								'post_author'  => 1,
								'post_type'    => 'wp_block',
							)
						);
						if ( ! is_wp_error( $post_id ) ) {
							$pattern_category = $pattern_data['categories'];
							foreach( $pattern_category as $category ){
								wp_set_object_terms( $post_id, $category, 'wp_pattern_category' );
							}
						}
						$pattern_data['content']  = '<!-- wp:block {"ref":' . $post_id . '} /-->';
						$pattern_data['inserter'] = false;
						$pattern_data['slug']     = $block_pattern;

						$pattern_list[] = $pattern_data;
					}
				} else {
					register_block_pattern(
						'photology/' . $block_pattern,
						require $pattern_file
					);
				}
			}
			update_option( 'photology_synced_pattern_imported', $pattern_list );
		}
	}

	/**
	 * Register Synced Patterns
	 */
	 private function register_synced_patterns() {
		$patterns = get_option( 'photology_synced_pattern_imported' );

		 foreach ( $patterns as $block_pattern ) {
			 register_block_pattern(
				'photology/' . $block_pattern['slug'],
				$block_pattern
			);
		 }
	 }
}
