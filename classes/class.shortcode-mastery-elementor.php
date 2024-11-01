<?php
/**
 * Shortcode Mastery Elementor
 *
 * @class   Shortcode_Mastery_Elementor
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 */

final class Shortcode_Mastery_Elementor {

	const SHORTCODE_MASTERY_MINIMUM_ELEMENTOR_VERSION = '2.0.0';
	const SHORTCODE_MASTERY_MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Version number
	 *
	 * @access public
	 * @var float
	 */
	
	public $version = SHORTCODE_MASTERY_VERSION;

	/**
	 * Registered widget names
	 *
	 * @access private
	 * @var float
	 */

	private $names = array();

    /**
	 * Constructor
	 */	

	public function __construct() {}

	/**
	 * Plugins loaded Hook
	 */

	public function plugins_loaded() {

		add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_action( 'sm_render_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
		add_action( 'sm_render_notices', array( $this, 'admin_notice_minimum_php_version' ) );

	}

	/**
	 * Init Elementor Hooks
	 */

	public function init() {

		add_action( 'elementor/elements/categories_registered', array( $this, 'add_categories' ) );
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'init_widgets' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'widget_styles' ) );
		add_action( 'elementor/editor/after_save', array( $this, 'clear_files' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts' ) );
		//add_action( 'elementor/preview/enqueue_styles', array( $this, 'preview_styles' ) );
	}

	/**
	 * Admin Notice: Elementor Version
	 */

	public function admin_notice_minimum_elementor_version() {
		
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$shortcode_name = $elementor = null;

		if ( isset( $_REQUEST['id'] ) ) $id = absint( $_REQUEST['id'] );
	
		if ( isset( $_REQUEST['sm_nonce'] ) && wp_verify_nonce( $_REQUEST['sm_nonce'], 'edit_' . $id ) ) {
			
			$sm = get_post( $id );
			
			$shortcode_name = $sm->post_title;

			$sm_meta = get_post_meta( $id );
					
			$elementor = Shortcode_Mastery::app()->get_meta_part( get_post_meta( $id ), 'shortcode_integration_elementor' );

		}

		if ( $elementor && ! version_compare( ELEMENTOR_VERSION, self::SHORTCODE_MASTERY_MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
				
			$screen = get_current_screen();
			
			if (
				$shortcode_name && (
				$screen->id == 'shortcodes_page_shortcode-mastery-create' || 
				$screen->id == 'admin_page_shortcode-mastery-create'
				)
			) {		

				$message = sprintf(
					/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
					esc_html__( '"%1$s" requires "%2$s" version %3$s or greater for success integration.', 'shortcode-mastery' ),
					'<strong>' . $shortcode_name . '</strong>',
					'<strong>' . esc_html__( 'Elementor', 'shortcode-mastery' ) . '</strong>',
					self::SHORTCODE_MASTERY_MINIMUM_ELEMENTOR_VERSION
				);

				printf( '<div class="notice notice-warning"><p>%1$s</p></div>', $message );

			}
		
		}

	}

	/**
	 * Admin Notice: PHP Version
	 */

	public function admin_notice_minimum_php_version() {
		
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$shortcode_name = $elementor = null;

		if ( isset( $_REQUEST['id'] ) ) $id = absint( $_REQUEST['id'] );
	
		if ( isset( $_REQUEST['sm_nonce'] ) && wp_verify_nonce( $_REQUEST['sm_nonce'], 'edit_' . $id ) ) {
			
			$sm = get_post( $id );
			
			$shortcode_name = $sm->post_title;

			$sm_meta = get_post_meta( $id );
					
			$elementor = Shortcode_Mastery::app()->get_meta_part( get_post_meta( $id ), 'shortcode_integration_elementor' );

		}

		if ( $elementor && version_compare( ELEMENTOR_VERSION, self::SHORTCODE_MASTERY_MINIMUM_PHP_VERSION, '>' ) ) {
				
			$screen = get_current_screen();
			
			if (
				$shortcode_name && (
				$screen->id == 'shortcodes_page_shortcode-mastery-create' || 
				$screen->id == 'admin_page_shortcode-mastery-create'
				)
			) {		

				$message = sprintf(
					/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
					esc_html__( '"%1$s" requires "%2$s" version %3$s or greater for success integration.', 'shortcode-mastery' ),
					'<strong>' . $shortcode_name . '</strong>',
					'<strong>' . esc_html__( 'PHP', 'shortcode-mastery' ) . '</strong>',
					self::SHORTCODE_MASTERY_MINIMUM_PHP_VERSION
				);
		
				printf( '<div class="notice notice-warning"><p>%1$s</p></div>', $message );

			}

		}

	}

	/**
	 * Clear cache of widget
	 */

	public function clear_files() {

		foreach( $this->names as $name ) {

			$name = str_replace( 'sm_', '', $name);

			Shortcode_Mastery::clearCache( $name );

		}

	}

	/**
	 * Enqueue Widget styles
	 */

	public function widget_styles() {

		if ( $this->check() ) $this->enqueue_frontend_scripts( false );

	}

	/**
	 * Enqueue Widget scripts
	 */

	public function widget_scripts() {
		
		if ( $this->check() ) $this->enqueue_frontend_scripts( true );

	}

	/**
	 * Register widgets
	 */

	public function init_widgets() {
		
		foreach( $this->names as $name ) {
			
			$name = str_replace( 'sm_', '', $name);

			if ( $this->check() && file_exists( SHORTCODE_MASTERY_DIR . 'cache/' . $name. '/' . $name . '-elementor-widget.php' ) ) {
				require_once( SHORTCODE_MASTERY_DIR . 'cache/' . $name. '/' . $name . '-elementor-widget.php' );
				$class_name = '\Shortcode_Mastery_' . str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $name ) ) ) . '_Elementor_Widget';
				$class = \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $class_name );
			}
		}
	}

	/**
	 * Set shortcode name
	 * @param string $name Name
	 */

	public function set_shortcode_name( $name ) {
		
		$this->names[] = $name;

	}

	/**
	 * Add Shortcode Mastery category for Elementor
	 */

	public function add_categories( $elements_manager ) {

		$elements_manager->add_category(
			'shortcode-mastery',
			[
				'title' => __( 'Shortcode Mastery', 'shortcode-mastery' ),
				'icon' => 'fa fa-code',
			]
		);
	
	}

	/**
	 * Write Elementor Class
	 * @param string $name Name
	 * @param array $shortcode_params Shortcode Attributes
	 */
	
	public function write_elementor_class( $name, $shortcode_params ) {

		$name = str_replace( 'sm_', '', $name);

		if ( file_exists( SHORTCODE_MASTERY_DIR . 'cache/' . $name. '/' . $name . '-elementor-widget.php' ) ) return;

		if ( ! file_exists( SHORTCODE_MASTERY_DIR . 'cache/' . $name  ) ) {

			mkdir( SHORTCODE_MASTERY_DIR . 'cache/' . $name, 0755, true );

		}

		$file = SHORTCODE_MASTERY_DIR . 'cache/' . $name. '/' . $name . '-elementor-widget.php';

		$class_name = 'Shortcode_Mastery_' . str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $name ) ) ) . '_Elementor_Widget';

		$content = "<?php\n";

		// Comments
		$content = $this->header( $class_name, $content );

		// Class defination
		$content .= "class $class_name extends \Elementor\Widget_Base {\n\r";

		// Method 'get_name'
		$content = $this->write_get_name( $name, $content );

		// Method 'get_title'
		$content = $this->write_get_title( $name, $content );

		// Method 'get_icon'
		$content = $this->write_get_icon( $content );

		// Method 'get_categories'
		$content = $this->write_get_categories( $content );

		// Method '_register_controls'
		$content = $this->write_controls( $name, $shortcode_params, $content );

		// Method 'render'
		$content = $this->write_render( $name, $shortcode_params, $content );

		$content .= "}";
		$content .= "\n?>";

		return file_put_contents( $file, $content );

	}

	/**
	 * Write widget header
	 */

	private function header( $class_name, $content ) {

		$title = str_replace( '_', ' ', $class_name );

		$content .= "/**\n";
		$content .= " * $title\n";
		$content .= " * Auto Generated by Shortcode Mastery\n";
		$content .= " *\n";
		$content .= " * @class   $class_name\n";
		$content .= " * @package Shortcode_Mastery\n";
		$content .= " * @version $this->version\n";
		$content .= " *\n";
		$content .= " */\n\r";

		return $content;

	}

	/**
	 * Write widget get_name method
	 */

	private function write_get_name( $name, $content ) {
		
		$content .=	"\tpublic function get_name() {\n";
		$content .= "\t\treturn 'sm-$name';\n";
		$content .= "\t}\n\r";

		return $content;
	}

	/**
	 * Write widget get_title method
	 */

	private function write_get_title( $name, $content ) {

		$title = ucwords( str_replace( '-', ' ', $name ) );
		
		$content .=	"\tpublic function get_title() {\n";
		$content .= "\t\treturn __( '$title', 'shortcode-mastery' );\n";
		$content .= "\t}\n\r";

		return $content;
	}

	/**
	 * Write widget get_icon method
	 */

	private function write_get_icon( $content ) {
		
		$content .=	"\tpublic function get_icon() {\n";
		$content .= "\t\treturn 'eicon-shortcode';\n";
		$content .= "\t}\n\r";

		return $content;
	}

	/**
	 * Write widget get_categories method
	 */

	private function write_get_categories( $content ) {
		
		$content .=	"\tpublic function get_categories() {\n";
		$content .= "\t\treturn ['shortcode-mastery'];\n";
		$content .= "\t}\n\r";

		return $content;
	}

	/**
	 * Write widget write_controls method
	 */

	private function write_controls( $name, $shortcode_params, $content ) {
		
		$content .=	"\tprotected function _register_controls() {\n";

		$content .=	"\t\t" . '$this->start_controls_section' . "(\n";
		$content .=	"\t\t\t'content_section',\n";
		$content .=	"\t\t\t[\n";
		$content .= "\t\t\t\t'label' => __( 'Attributes', 'shortcode-mastery' ),\n";
		$content .=	"\t\t\t\t'tab' => \Elementor\Controls_Manager::TAB_CONTENT,\n";
		$content .=	"\t\t\t]\n";
		$content .=	"\t\t);\n";

		$content .=	"\t\t" . '$this->add_control' . "(\n";
		$content .=	"\t\t\t'shortcode-mastery-title',\n";
		$content .=	"\t\t\t[\n";
		$content .= "\t\t\t\t'label' => __( 'This is a Shortcode Mastery Widget', 'shortcode-mastery' ),\n";
		$content .= "\t\t\t\t'type' => \Elementor\Controls_Manager::HEADING,\n";
		$content .=	"\t\t\t]\n";
		$content .=	"\t\t);\n";
		
		$content .=	"\t\t" . '$this->add_control' . "(\n";
		$content .=	"\t\t\t'shortcode-mastery-notice',\n";
		$content .=	"\t\t\t[\n";
		$content .= "\t\t\t\t'type' => \Elementor\Controls_Manager::RAW_HTML,\n";
		$content .= "\t\t\t\t'separator' => 'after',\n";
		$content .= "\t\t\t\t'raw' => __( '<p style=" . '"line-height: 16px"' . ">You can change all registered attributes on the <a href=" . '"' . admin_url('admin.php?page=shortcode-mastery') . '"' . " target=" . '"_blank"' . ">Shortcode Mastery</a> page.</p>', 'shortcode-mastery' ),\n";
		$content .=	"\t\t\t]\n";
		$content .=	"\t\t);\n";

		$params = array();
                		
		if ( $shortcode_params ) {
		
			foreach( $shortcode_params as $param ) {
				
				$param_name = $param['name'];
                        
				$param_value = json_decode( $param['value'], true ) ? json_decode( $param['value'], true ) : str_replace( '"', "'", $param['value'] );

				$param_options = isset( $param['options'] ) ? $param['options'] : null;
				
				$desc = isset( $param['desc'] ) ? wp_kses_post( $param['desc'] ) : '';
				
				$title = isset( $param['title'] ) && $param['title'] != '' ? sanitize_text_field( $param['title'] ) : ucfirst( $param['name'] );
			
				if ( ! isset( $param['elementor'] ) ) {
                        
					$type = isset( $param['radio'] ) && absint( $param['radio'] ) == 1 ? 'SWITCHER' : 'TEXT';
					
				} else {

					$type = $param['elementor'] ? sanitize_text_field( $param['elementor'] ) : 'TEXT';

				}

				$inline = $options = $key = null;

				$default = '';

				switch ( $type ) {
					case 'SELECT':
					case 'SELECT2':
					case 'CHOOSE':
						$key = 'options';
						$options = json_decode( $param_options, true );
						$default = $param_options;
						$param_value = '"'.$param_value.'"';
						break;
					case 'ICON':
						$key = 'include';
						$options = json_decode( $param_options, true );
						$default = '';
						$param_value = '"'.$param_value.'"';
						break;
					case 'ANIMATION':
						$key = 'prefix_class';
						$inline = 'animated ';
						$default = $param_value;
						$param_value = '"'.$param_value.'"';
						break;
					case 'HOVER_ANIMATION':
						$key = 'prefix_class';
						$inline = 'elementor-animation-';
						$default = $param_value;
						$param_value = '"'.$param_value.'"';
						break;
					case 'GALLERY':
						$default = json_encode( $param_value );
						$param_value = is_array( $param_value ) ? str_replace( PHP_EOL, '', var_export( $param_value, true ) ) : 'array()';
						break;
					default: 
						$default = $param_value;
						$param_value = '"'.$param_value.'"';
						break;
				}

				if ( $default ) $desc .= '<p>Default: <strong>' . str_replace('"','\"', $default ) . '</strong></p>';
		
				$content .=	"\t\t" . '$this->add_control' . "(\n";
				$content .=	"\t\t\t'$param_name',\n";
				$content .=	"\t\t\t[\n";
				$content .= "\t\t\t\t\"label\" => \"$title\",\n";
				$content .= "\t\t\t\t\"type\" => \Elementor\Controls_Manager::$type,\n";
				$content .= "\t\t\t\t\"description\" => \"$desc\",\n";
				$content .= "\t\t\t\t\"default\" => $param_value,\n";
				if ( $type == 'SWITCHER' ) $content .= "\t\t\t\t\"return_value\" => \"true\",\n";
				if ( $type == 'COLOR' ) $content .= "\t\t\t\t\"alpha\" => \"true\",\n";
				if ( $options && $key ) $content .= "\t\t\t\t\"$key\" => ".str_replace( PHP_EOL, '', var_export( $options, true ) ).",\n";
				if ( $inline && $key ) $content .= "\t\t\t\t\"$key\" => \"$inline\",\n";
				$content .=	"\t\t\t]\n";
				$content .=	"\t\t);\n";
			
			}

		}

		$content .=	"\t\t" . '$this->end_controls_section();' . "\n";

		$content .= "\t}\n\r";

		return $content;
	}

	/**
	 * Write widget render method
	 */

	private function write_render( $name, $shortcode_params, $content ) {
		
		$content .=	"\tprotected function render() {\n";
		$content .= "\t\t" . '$settings = $this->get_settings_for_display()' . ";\n";
		$content .= "\t\t" . '$params = ""' . ";\n";
		
		if ( $shortcode_params ) {
		
			foreach( $shortcode_params as $param ) {

				$content .= "\t\tif ( is_array( ".'$settings['."'".$param['name']."'".']'." ) ) {\n";
				$content .= "\t\t\t$".$param['name']." = urlencode( json_encode( ".'$settings['."'".$param['name']."'".']'." ) );\n";
				$content .= "\t\t} else {\n";
				$content .= "\t\t\t$".$param['name']." = ".'str_replace("\"","'."'".'",$settings['."'".$param['name']."'".'])'.";\n";
				$content .= "\t\t}\n";
				$content .= "\t\t".'$flatParam = '."' ".$param['name'].'="'."'.".'$'.$param['name'].".'".'"'."'".";\n";
				$content .= "\t\t" . '$params .= $flatParam' . ";\n";

			}

		}
		$content .= "\t\techo do_shortcode('[sm_$name'.".'$params'.".']');\n";
		$content .= "\t}\n\r";

		return $content;
	}

	/**
	 * Search Shortcode Mastery widgets on page
	 */

	private function search( $array, $key, $value ) { 
		
		$results = array(); 
	
		if ( is_array( $array ) ) { 

			if ( isset( $array[$key] ) && ! is_array( $array[$key] ) && strpos( $array[$key], $value ) !== FALSE  ) {

				$results[] = $array;
			}
	
			foreach ($array as $subarray) {

				$results = array_merge($results, $this->search( $subarray, $key, $value ) ); 
			}

		} 
	
		return $results; 
	}

	/**
	 * Enqueue frontend scripts and styles
	 */

	private function enqueue_frontend_scripts( $type ) {

		global $post;

		if ( ! isset( $post->ID ) ) return;

		$data = json_decode( get_post_meta( $post->ID, '_elementor_data', true ), true );

		$widgets = $this->search( $data, 'widgetType', 'sm-' );
		
		foreach( $widgets as $widget ) {

			$name = str_replace( 'sm-', 'sm_', $widget['widgetType'] );

			if ( in_array( $name, $this->names ) && $this->check() ) {

				$atts = $widget['settings'];

				$shortcode_params = Shortcode_Mastery::app()->rendered_params( $name );
				
				$a = Shortcode_Mastery::app()->sm_shortcode_atts( $shortcode_params, $atts, $name );

				$ext = $type ? '.scripts' : '.styles';
			
				$content = Shortcode_Mastery::getTwig()->render_content( $name . $ext, $a );
		
				if ( $content ) {

					$write = Shortcode_Mastery::app()->get_value( $name, 'write_scripts' );
																					
					new Shortcode_Mastery_Scripts( $name, $content, ! $type, false, $type, $write );
								
				}

				// Third party scripts

				$embed_scripts = Shortcode_Mastery::app()->get_value( $name, 'embed_scripts' );

				$is_head = false;
										
				if ( $embed_scripts ) {
							
					if ( is_array( $embed_scripts ) && sizeof( $embed_scripts ) > 0 ) {
									
						foreach ( $embed_scripts as $k => $arg ) {
														
							$argname = $name . '.embed_scripts.' . sanitize_title( $k );
												
							if ( is_array( $embed_scripts[ $k ] ) ) {
								
								if ( isset( $embed_scripts[ $k ]['radio'] ) && $embed_scripts[ $k ]['radio'] != '' ) $is_head = boolval( $embed_scripts[ $k ]['radio'] );
														
							}
												
							$embed_scripts[ $k ] = Shortcode_Mastery::getTwig()->render_content( $argname, $a );
							
							if ( filter_var( $embed_scripts[ $k ], FILTER_VALIDATE_URL, ~FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_PATH_REQUIRED ) !== FALSE ) {
							
								if ( ! wp_script_is( sanitize_title( $k ), 'registered' ) ) {
																													
									wp_enqueue_script( sanitize_title( $k ), esc_url( $embed_scripts[ $k ] ), array( 'jquery' ), NULL, ! $is_head );
		
								}
							
							}
																		
						}		
						
					}			
					
				}

				// Third party styles

				$embed_styles = Shortcode_Mastery::app()->get_value( $name, 'embed_styles' );
						
				if ( $embed_styles ) {
							
					if ( is_array( $embed_styles ) && sizeof( $embed_styles ) > 0 ) {
									
						foreach ( $embed_styles as $k => $arg ) {
														
							$argname = $name . '.embed_styles.' . sanitize_title( $k );
							
							$embed_styles[ $k ] = Shortcode_Mastery::getTwig()->render_content( $argname, $a );
							
							if ( filter_var( $embed_styles[ $k ], FILTER_VALIDATE_URL, ~FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_PATH_REQUIRED ) !== FALSE ) {
							
								if ( ! wp_style_is( sanitize_title( $k ), 'registered' ) ) {
																														
									wp_enqueue_style( sanitize_title( $k ), esc_url( $embed_styles[ $k ] ), NULL, NULL ); 
								
								}
							
							}
																		
						}		
						
					}			
					
				}

			}
			
		}

	}

	/**
	 * Check PHP and ELEMENTOR minimum versions
	 */

	private function check( $elementor = null, $php = null ) {
		
		$elementor = $elementor ? sanitize_text_field( $elementor ) : self::SHORTCODE_MASTERY_MINIMUM_ELEMENTOR_VERSION;
		$php = $php ? sanitize_text_field( $php ) : self::SHORTCODE_MASTERY_MINIMUM_PHP_VERSION;

		$check = true;

		if ( ! version_compare( ELEMENTOR_VERSION, $elementor , '>=' ) ) $check = false;
		if ( version_compare( PHP_VERSION, $php, '<' ) ) $check = false;

		return $check;
	}
    
}