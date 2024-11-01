<?php
/**
 * @package           Shortcode_Mastery
 *
 * @wordpress-plugin
 * Plugin Name:       Shortcode Mastery
 * Description:       Wordpress Shortcodes Creator
 * Version:           2.0.0
 * Author:            Uncleserj
 * Author URI:        http://uncleserj.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       shortcode-mastery
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'SHORTCODE_MASTERY_VERSION', '2.0.0' );

define( 'SHORTCODE_MASTERY_URL', plugin_dir_url( __FILE__ ) );

define( 'SHORTCODE_MASTERY_DIR', plugin_dir_path( __FILE__ ) );

define( 'SHORTCODE_MASTERY_TINYMCE', true );

require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

require_once( SHORTCODE_MASTERY_DIR . 'includes/vendor/autoload.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/twig/class.shortcode-mastery-twig-cache.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/twig/class.shortcode-mastery-twig-post.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/twig/class.shortcode-mastery-twig-image.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/twig/class.shortcode-mastery-twig-user.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/twig/class.shortcode-mastery-twig-extension.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/twig/class.shortcode-mastery-twig-loader.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/twig/class.shortcode-mastery-twig.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/tinymce/class.shortcode-mastery-tinymce-item.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/tinymce/class.shortcode-mastery-tinymce-table.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/tinymce/class.shortcode-mastery-tinymce.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/class.shortcode-mastery-scripts.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/class.shortcode-mastery-elementor.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/class.shortcode-mastery-table.php' );

require_once( SHORTCODE_MASTERY_DIR . 'classes/class.shortcode-mastery.php' );

Shortcode_Mastery::app();