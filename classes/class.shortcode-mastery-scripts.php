<?php
/**
 * Shortcode Mastery Scripts
 *
 * @class   Shortcode_Mastery_Scripts
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 */

class Shortcode_Mastery_Scripts {
	
	/**
	 * Shortcode name
	 *
	 * @access private
	 * @var string
	 */
	
	private $name;
	
	/**
	 * Shortcode scripts
	 *
	 * @access private
	 * @var string
	 */
	
	private $scripts;
	
	/**
	 * Enqueue weather
	 *
	 * @access private
	 * @var Bool
	 */
	
	private $is_head;
	
	/**
	 * Enqueue admin weather
	 *
	 * @access private
	 * @var Bool
	 */
	
	private $is_admin;
	
	/**
	 * Script or Style
	 *
	 * @access private
	 * @var Bool
	 */
	
	private $type;

	/**
	 * Write to file
	 *
	 * @access private
	 * @var Bool
	 */
	
	private $write;
	
	/**
	 * Constructor
	 *
	 * @param string $name Shortcode name
	 * @param string $scripts Shortcode scripts
	 * @param bool $is_head Header or footer
	 * @param bool $is_admin Admin or Not
	 * @param string $type Script or Style
	 */	

	public function __construct( $name, $scripts = null, $is_head = true, $is_admin = false, $type = true, $write = false ) {
	
		$this->name = $name;
				
		$this->scripts = $scripts;
		
		$this->is_head = $is_head;
		
		$this->is_admin = $is_admin;
		
		$this->type = $type;

		$this->write = $write;
				
		$this->hooks();
		
	}

	/**
	 * Wordpress Hooks
	 */
	
	public function hooks() {

		if ( ! $this->write ) {

			$action = ! $this->is_admin ? ( $this->is_head ? 'wp_head' : 'wp_footer' ) : ( $this->is_head ? 'admin_print_scripts' : 'admin_print_footer_scripts' );
			
			add_action( $action, array( $this, 'print_shortcode_scripts' ) );

		} else {

			$ext = $this->type ? '.js' : '.css';

			$name = str_replace( 'sm_', '', $this->name );

			if ( ! file_exists( SHORTCODE_MASTERY_DIR . 'cache/' . $name . '/' . $name . $ext ) ) {

				$this->write_script_file();

			}

			if ( $this->type ) {

				if ( ! wp_script_is( 'sm-' . $name . '-embedded-script', 'registered' ) ) {
																												
					wp_enqueue_script( 'sm-' . $name . '-embedded-script', SHORTCODE_MASTERY_URL . 'cache/' . $name . '/' . $name . $ext, array( 'jquery' ), NULL, ! $this->is_head );

				}

			} else {

				if ( ! wp_style_is( 'sm-' . $name . '-embedded-style', 'registered' ) ) {
																												
					wp_enqueue_style( 'sm-' . $name . '-embedded-style', SHORTCODE_MASTERY_URL . 'cache/' . $name . '/' . $name . $ext, NULL, NULL ); 
				
				}

			}

		}
							
	}

	/**
	 * Write file
	 */
	
	private function write_script_file() {

		$name = str_replace( 'sm_', '', $this->name );

		if ( ! file_exists( SHORTCODE_MASTERY_DIR . 'cache/' . $name  ) ) {

			mkdir( SHORTCODE_MASTERY_DIR . 'cache/' . $name, 0755, true );

		}

		$ext = $this->type ? '.js' : '.css';

		$file = SHORTCODE_MASTERY_DIR . 'cache/' . $name . '/' . $name . $ext;

		$content = htmlspecialchars_decode( esc_html( $this->scripts ), ENT_QUOTES );

		return file_put_contents( $file, $content );

	}
	
	/**
	 * Print shortcodes scripts
	 */
	
	public function print_shortcode_scripts() {
		
		$shortcode_name = '';
		
		$shortcode_name = str_replace( 'sm_', '', $this->name );
				
		$shortcode_name = explode( '.', $shortcode_name );
				
		$shortcode_name = get_page_by_path( $shortcode_name[0], OBJECT, 'shortcode-mastery' );
		
		if ( $shortcode_name ) $shortcode_name = $shortcode_name->post_title;
		
		if ( $this->type ) {
		
			if ( wp_script_is( 'jquery', 'done' ) && $this->scripts ) {
				
				//echo "<!-- Shortcode Mastery $shortcode_name Scripts Start -->\r\n";
				echo "<script type=\"text/javascript\">\r\n" . htmlspecialchars_decode( esc_html( $this->scripts ), ENT_QUOTES ) . "\r\n</script>\r\n";
				//echo "<!-- Shortcode Mastery $shortcode_name Scripts End -->\r\n";
				
			}
		
		} else {
			
			//echo "<!-- Shortcode Mastery $shortcode_name Styles Start -->\r\n";
			echo "<style type=\"text/css\">\r\n" . htmlspecialchars_decode( esc_html( $this->scripts ), ENT_QUOTES ) . "\r\n</style>\r\n";
			//echo "<!-- Shortcode Mastery $shortcode_name Styles End -->\r\n";
			
		}
		
	}

}
?>