<?php
/**
 * Shortcode Mastery Class
 *
 * @class   Shortcode_Mastery
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

class Shortcode_Mastery {
	
	/**
	 * Version number
	 *
	 * @access public
	 * @var float
	 */
	
	public $version = SHORTCODE_MASTERY_VERSION;
	
	/**
	 * Array of post meta fields
	 *
	 * @access private
	 * @var array
	 */
		
	private $meta_array = array( 
		'params', 
		'arguments', 
		'main_loop', 
		'main_content', 
		'scripts', 
		'styles', 
		'not_editable', 
		'embed_scripts', 
		'embed_styles', 
		'thumbnail_source', 
		'icon_source',
		'shortcode_integration_wpbakery', // @since 1.2.4,
		'shortcode_integration_elementor', // @since 2.0.0,
		'write_scripts', // @since 2.0.0
		'write_styles' // @since 2.0.0
	);
	
	private $rendered = false;
	
	/**
	 * All registered shortcodes
	 *
	 * @access private
	 * @var array
	 */
		
	private $shortcodes = array();
	
	/**
	 * All default values
	 *
	 * @access private
	 * @var array
	 */
	
	private $defaults;
	
	/**
	 * Menu permission
	 *
	 * @access private
	 * @var string
	 */
	
	private $menu_permission = 'manage_options';
	
	/**
	 * Twig engine
	 *
	 * @access private
	 * @var object
	 */
	
	private $twig;

	/**
	 * Elementor engine
	 *
	 * @access private
	 * @var object
	 * @since 2.0.0
	 */
	
	private $elementor;
	
	/**
	 * Shortcode TinyMCE Object
	 *
	 * @access private
	 * @var object
	 */
	
	private $tinymce;
		
    /**
	 * Instance
	 *
	 * @static
	 * @access private
	 * @var object
	 */
			
	private static $_instance;
		
	/**
	 * Singleton
	 */

	private function __construct() {
				
		$this->twig = new Shortcode_Mastery_Twig( $this->version );

		if ( SHORTCODE_MASTERY_TINYMCE ) { 
		
			$this->tinymce = new Shortcode_Mastery_TinyMCE( $this->version );
		
		}
		
		$this->defaults = include_once( SHORTCODE_MASTERY_DIR . 'includes/defaults.php' );

		/**
		 * Elementor
		 * 
		 * @since 2.0.0
		 */

		if ( did_action( 'elementor/loaded' ) && defined( 'ELEMENTOR_VERSION' ) ) {

			$this->elementor = new Shortcode_Mastery_Elementor();
			
			$this->elementor->plugins_loaded();

		}
								
		$this->hooks();
				
	}
	
	private function __clone() {}
    
	private function __wakeup() {}
	
	public static function app() {
	
		if (self::$_instance === null) {
			self::$_instance = new self();

			/**
			 * Plugin loaded.
			 *
			 * Fires when Plugin was fully loaded and instantiated.
			 *
			 * @since 2.0.0
			 */
			 
			do_action( 'shortcode_mastery/loaded' );
		}
				
		return self::$_instance;
	}
	
	/**
	 * Static get default value
	 *
	 * @static
	 * @param string $key Key of value
	 * @return string Default value
	 */
	
	public static function getDefault( $key ) {
		
		return self::app()->getDef( $key );
		
	}
	
	/**
	 * Static set default value
	 *
	 * @static
	 * @param string $key Key
	 * @param string $value Value
	 */
	
	public static function setDefault( $key, $value ) {
		
		self::app()->setDef( $key, $value );
		
	}
	
	/**
	 * Static set default value
	 *
	 * @static
	 * @param array $sm_meta Post meta
	 * @param string $part Meta part
	 * @return string Meta value
	 */
	
	public static function getMeta( $sm_meta, $part ) {
		
		return self::app()->get_meta_part( $sm_meta, $part );
		
	}
	
	/**
     * JSON decode with swap booleans
     *
     * @static
     * @param string $string JSON string
     * @return array Array of data
     */
	
	public static function decodeString( $string ) {
		
		return self::app()->decode( $string );
		
	}
	
	/**
     * Add shortcode to database
     *
     * @static
     * @param array $data Shortcode with data
     * @return int ID
     */
	
	public static function addShortcode( $data, $ajax = false ) {
		
		return self::app()->add_shortcode_process( $data, $ajax );
		
	}
	
	/**
     * Delete shortcode from database
     *
     * @static
     * @param int $id Shortcode ID
     */
	
	public static function deleteShortcode( $id ) {
		
		return self::app()->delete_shortcode_process( $id );
		
	}
	
	/**
     * Get Twig object
     *
     * @static
     * @return object
     */
	
	public static function getTwig() {
		
		if ( self::app()->twig ) return self::app()->twig;
		
		return null;
		
	}
	
	/**
     * Clear all cache
     *
     * @static
     */
	
	public static function clearCache( $path = null ) {
		
		self::app()->clear_all_cache( $path );
		
	}
	
	/**
	 * Get Shortcode Value
	 *
	 * @static
	 * @param string $name Shortcode name
	 * @param string $field Key of field
	 * @return string Default value	 
	 */
	
	public static function getValue( $name, $field ) {
		
		return self::app()->get_value( $name, $field );
		
	}
	
	/**
	 * Set Shortcode Value
	 *
	 * @static
	 * @param string $name Shortcode name
	 * @param string $field Key of field
	 * @param string $value New value
	 */
	
	public static function setValue( $name, $field, $value ) {
		
		self::app()->set_value( $name, $field, $value );
		
	}
	
	/**
	 * Set Default Value
	 *
	 * @param string $key Key
	 * @param string $value Value
	 */
	
	public function setDef( $key, $value ) {
		
		if ( $key && $value ) {
			
			$this->defaults[ $key ] = $value;
			
		}
		
	}
	
	/**
	 * Get Default Value
	 *
	 * @param string $key Key of value
	 * @return string Default value	 
	 */
	
	public function getDef( $key ) {
		
		if ( $key && isset( $this->defaults[ $key ] ) ) return $this->defaults[ $key ];
		
	}
	
	/**
	 * Wordpress Hooks
	 */

	public function hooks() {
		
		/**
		 * Load Text Domain
		 */
		
		add_action( 'init', array( $this, 'load_text_domain' ) );
		
		/**
		 * Register Shortcodes
		 */
		 
        add_action( 'init', array( $this, 'register_post_type' ) );
		 		
		add_action( 'init', array( $this, 'query_all_shortcodes' ), 1 );		
		
		add_action( 'init', array( $this, 'register_all_shortcodes' ), 2 );
				
		/**
		 * Register frontend scripts
		 */
				 		
		add_action( 'wp_enqueue_scripts', array( $this, 'load_shortcodes_styles' ) );
		
		/**
		 * POST Requests
		 */
		
		add_action( 'admin_init', array( $this, 'add_shortcode_to_database' ) );
		
		add_action( 'admin_init', array( $this, 'edit_shortcode_in_database' ) );
	
		/**
		 * Add Menu Item
		 */
		
		add_action( 'admin_menu', array( $this, 'add_menu_item' ) );
		
		/**
		 * Admin Bar Menu
		 */
		
		add_action( 'admin_bar_menu', array( $this, 'submit_top_button' ), 999 );
		
		/**
		 * Add Admin Styles and Scripts
		 */
		
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles_scripts' ) );
				
		/**
		 * Ajax logic
		 */
		
		add_action( 'wp_ajax_ajax_sm_submit', array( $this, 'ajax_sm_submit' ) );
		
		add_action( 'admin_action_shortcode-mastery-preview-page', array( $this, 'render_preview_page' ) );
				
		/**
		 * Post and Page Body Class
		 */
		 
		add_filter( 'admin_body_class', array( $this, 'add_sm_body_class' ) );
		
		/**
		 * Other filters
		 */
		
		add_filter( 'the_content', array( $this, 'sm_clean_shortcodes' ) );
	
	}
	
    /**
     * Register post type
     */
     
    function register_post_type() {

    	$args = array(
    		'public'             => false,
    		'publicly_queryable' => false,
    		'show_ui'            => false,
    		'query_var'          => false,
    		'show_in_rest'       => false,
    		'capability_type'    => 'post'
    	);
    
    	register_post_type( 'shortcode-mastery', $args );
    }
	
	/**
	 * Clean shortcodes
	 */
	
	public function sm_clean_shortcodes( $content ) {
		
		$array = array (
			'<p>[' => '[',
			']</p>' => ']',
			']<br />' => ']'
		);
		
		$content = strtr( $content, $array );
		
		return $content;
	}
	
	/**
	 * Load Text Domain
	 */
	
	public function load_text_domain() {

		$domain = 'shortcode-mastery';

		$locale = apply_filters( 'plugin_locale', is_admin() ? get_user_locale() : get_locale(), $domain );

		$mofile = $domain . '-' . $locale . '.mo';

		load_textdomain( $domain, SHORTCODE_MASTERY_DIR . '/languages/' . $mofile );
		
	}
	
	/**
	 * Add admin body classes
	 */
	
	public function add_sm_body_class( $classes ) {
	
		$screen = get_current_screen();
		
		if ( 'post' == $screen->base ) $classes .= ' ' . 'shortcode-mastery-admin';
		
		if ( 'page' == $screen->base ) $classes .= ' ' . 'shortcode-mastery-admin';
		
		return $classes;
	
	}
	
	/**
	 * Admin Styles and Scripts
	 */	
	 
	public function admin_styles_scripts() {
		
		$screen = get_current_screen();
				
		wp_enqueue_style( 'shortcode-mastery-admin', SHORTCODE_MASTERY_URL . 'css/shortcode-mastery-admin.css', array( 'wp-admin' ), $this->version, 'all' );
		
		switch ( $screen->id ) {
			
			/* All Shortcodes Page */
			
			case 'toplevel_page_shortcode-mastery':
			
				wp_enqueue_style( 'magnific-popup', SHORTCODE_MASTERY_URL . 'css/magnific-popup.css' );
			
				wp_enqueue_script( 'magnific-popup', SHORTCODE_MASTERY_URL . 'js/magnific-popup.js', array( 'jquery' ), $this->version, true);	
								
				wp_enqueue_script( 'shortcode-mastery-popup', SHORTCODE_MASTERY_URL . 'js/shortcode-mastery-popup.js', array( 'jquery' ), $this->version, true);
			
			break;
			
			/* Create or Edit Shortcode Page */
			
			case 'shortcodes_page_shortcode-mastery-create':
			case 'admin_page_shortcode-mastery-create':
			
				$strings = require_once( SHORTCODE_MASTERY_DIR . 'includes/strings.php' );
				
				wp_enqueue_media();

				wp_enqueue_script( 'shortcode-mastery-vue', 'https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js', null, $this->version, true );
							
				wp_enqueue_script( 'shortcode-mastery-ace', SHORTCODE_MASTERY_URL . 'js/ace/ace.js', null, $this->version, true );
								
				wp_enqueue_script( 'shortcode-mastery-custom', SHORTCODE_MASTERY_URL . 'js/shortcode-mastery.min.js', array('shortcode-mastery-vue'), $this->version, true );
				
				wp_enqueue_script( 'shortcode-mastery-tooltip', SHORTCODE_MASTERY_URL . 'js/bootstrap-tooltip-3.3.7.min.js', array( 'jquery' ), '3.3.7', true );
				
				wp_enqueue_style( 'shortcode-mastery-tooltip', SHORTCODE_MASTERY_URL . 'css/tooltip.min.css' );
				
				wp_localize_script( 'shortcode-mastery-custom', 'sm', $strings );
			
				wp_enqueue_script( 'shortcode-mastery-create-ajax', SHORTCODE_MASTERY_URL . 'js/shortcode-mastery-create.min.js', array( 'jquery' ), $this->version, true );
					
				wp_localize_script( 'shortcode-mastery-create-ajax', 'smajax', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'ajaxnonce' => wp_create_nonce( 'shortcode-mastery-ajax' ),
					'optionsSaving' => __( 'Shortcode saving...', 'shortcode-mastery' ),
					'shortcodeCreating' => __( 'Shortcode creating...', 'shortcode-mastery' ),
					'successMessage' =>  __( 'Shortcode saved', 'shortcode-mastery' ),
					'successMessageAdded' =>  __( 'Shortcode added', 'shortcode-mastery' ),
					'errorMessage' => __( 'Error in system', 'shortcode-mastery' ),
					'iconUrl' => SHORTCODE_MASTERY_URL . 'images/sm-new.svg',
					'defUrl' => SHORTCODE_MASTERY_URL . 'images/sm-white.png',
					'defUrl2x' => SHORTCODE_MASTERY_URL . 'images/sm-white@2x.png',
					'removeButton' => __( 'Remove Icon', 'shortcode-mastery' ),
					'uploaderTitle' => __( 'Select an image to upload', 'shortcode-mastery' ),
					'useTitle' => __( 'Use this image', 'shortcode-mastery' ),
					)
				);			
			
			break;
			
			default: break;
			
		}

	}
	
	public function render_preview_page() {
		
		if ( ! defined( 'IFRAME_REQUEST' ) && isset( $_REQUEST[ 'sm_nonce' ] ) && wp_verify_nonce( $_REQUEST[ 'sm_nonce' ], 'shortcode-mastery-preview-page' ) ) {
				    
		    define( 'IFRAME_REQUEST', true );
		    
		    $item = get_post( $_REQUEST[ 'shortcode_id' ], ARRAY_A );
		    
	    	$preview_content = '';
	    	
	    	$params = $r_params = '';
	    	
	    	$random = '';
	    	
	    	$sm_meta = get_post_meta( $item[ 'ID' ] );
			
			if ( $sm_meta ) {
										
				$values = Shortcode_Mastery::getMeta( $sm_meta, 'params' );
										
				$values = Shortcode_Mastery::decodeString( $values );
				
				if ( $values && is_array( $values ) ) {
					
					foreach( $values as $value ) {
						
						$value = (array) $value;
						
						if ( isset( $value['checkbox'] ) && $value['checkbox'] && $value['value'] == '' ) {
							
							$value['value'] = 'sm-' . $item['post_name'] . '-' . mt_rand(10000, 99999);
							
							$random .= '<p><i>' . __( 'Random required parameter', 'shortcode-mastery' ) . ' ' . '<strong>' . esc_html( $value['name'] ) . '</strong>: ' . esc_html( $value['value'] ) . '</i></p>';
							
							$r_params .= ' ' . esc_html( $value['name'] ). '="' . esc_html( $value['value'] ) . '"';						
							
						}

						$test = json_decode( $value['value'], true );

						if ( $test && is_array( $test ) ) {
							
							$value['value'] = '<strong>' . __( 'JSON', 'shortcode-mastery' ) . '</strong>';

						} else {

							$value['value'] = esc_html( $value['value'] );

						}
						
						$params .= ' ' . esc_html( $value['name'] ). '="' . str_replace( '&quot;', '&apos;', $value['value'] ) . '"';	
							
					}
					
				}
				
		    	$icon_src = SHORTCODE_MASTERY_URL . 'images/sm-white.png';
				$icon_src_2x = SHORTCODE_MASTERY_URL . 'images/sm-white@2x.png';	
							
				$icon = Shortcode_Mastery::getMeta( $sm_meta, 'icon_source' );
	    		
	    		if ( $icon ) {
		    		
		    		$icon = explode( '|', $icon );
		    		
		    		$icon_src = $icon[0];
		    		
		    		if ( intval( $icon_src ) ) {
			    		
			    		$icon_src = wp_get_attachment_image_src( $icon_src, 'full' );
			    		
			    		$icon_src = $icon_src[0];
			    		
			    	}
		    		
		    		$icon_src_2x = $icon_src;
		    		
		    	} else {
		    		
		    		$icon = get_the_post_thumbnail_url( $item[ 'ID' ], 'thumbnail' );
		    		
		    		if ( $icon ) $icon_src_2x = $icon_src = $icon;
		    		
		    	}
			 
			}

			$icon_src_2x = str_replace( array('http:', 'https:'), '', $icon_src_2x);
			$icon_src = str_replace( array('http:', 'https:'), '', $icon_src);
			$icon_src = set_url_scheme( $icon_src );
			$icon_src_2x = set_url_scheme( $icon_src_2x );
			
			if ( $icon_src ) {
				
				$icon_src = '<img class="default" src="' . $icon_src . '" srcset="' . $icon_src . ', ' . $icon_src_2x . ' 2x" />';
				
			}
			
			$preview_content = '<div class="sm-title-media"><div class="img-container divided">' . $icon_src . '</div><div class="sm-titles"><h3>'.$item['post_title'].'</h3></div></div>';
			
			$preview_content .= '<p>'.'[sm_' . $item['post_name'] . $params . ']'.'</p>';
			
			$preview_content .= '<div class="sm-random-params">' . $random . '</div>';
			
			$inside_content = Shortcode_Mastery::getMeta( $sm_meta, 'main_content' );
			
			if ( ! Shortcode_Mastery::getTwig()->check_inside_content( $inside_content ) ) {
				
				$inside_content = '';
				
			} else {
				
				$inside_content = __( 'Dummy Content', 'shortcode-mastery' ) . '[/sm_' . $item['post_name'] . ']';
				
			}
						
			$preview_content .= do_shortcode( '[sm_' . $item['post_name'] . $r_params . ']' . $inside_content );
						
			add_filter( 'admin_body_class', array( $this, 'filter_preview_admin_body_class' ) );
					    
			iframe_header();
			
			echo '<div class="sm-wrap-all" style="margin-top:5px">';
			
			echo $preview_content;
			
			echo '</div>';
						
			iframe_footer();
		
		}
		
		exit();
		
	}
	
	public function filter_preview_admin_body_class( $classes ) {
		
		$classes .= 'shortcode-mastery-preview-page';
	
		return $classes;
	}

	/**
	 * Add status bar in WP Admin Bar
	 *
	 * @param object $wp_admin_bar Admin Bar
	 */
	
	public function submit_top_button( $wp_admin_bar ) {
					
		$args = array(
			'id' => 'sm-submit-top-button',
		);
		
		$wp_admin_bar->add_node($args);
				
	}
	
	/**
	 * Clear all Twig cache files
	 */
	
	public function clear_all_cache( $path = null ) {
		
		$cache_dir = SHORTCODE_MASTERY_DIR . 'cache';

		$cache_dir = $path ? $cache_dir . '/' . $path : $cache_dir;

		if ( file_exists( $cache_dir ) ) {
		
			$it = new RecursiveDirectoryIterator( $cache_dir, RecursiveDirectoryIterator::SKIP_DOTS );
			
			$files = new RecursiveIteratorIterator( $it, RecursiveIteratorIterator::CHILD_FIRST );
			
			foreach( $files as $file ) {
				
				if ( $file->isDir() ) {
					
					rmdir( $file->getRealPath() );
					
				} else {
					
					unlink( $file->getRealPath() );
					
				}
			}

			if ( $path ) rmdir( $cache_dir );

		}
	}

	/**
	 * Create menu item
	 */
	
	public function add_menu_item() {
		
		$parent = null;
		
		if ( current_user_can( $this->menu_permission ) ) $parent = 'shortcode-mastery';

		add_menu_page( 
			__( 'Shortcode Mastery', 'shortcode-mastery' ),
			__( 'Shortcodes', 'shortcode-mastery' ),
			$this->menu_permission, 
			'shortcode-mastery',
			array( $this, 'shortcode_mastery_page' ), 
			SHORTCODE_MASTERY_URL . 'images/sm-menu-new.svg'
		);
		
		add_submenu_page( 
			'shortcode-mastery', 
			__( 'All Shortcodes', 'shortcode-mastery' ),
			__( 'All Shortcodes', 'shortcode-mastery' ),
			$this->menu_permission, 
			'shortcode-mastery',
			array( $this, 'shortcode_mastery_page' )
		);

		add_submenu_page( 
			$parent, 
			__( 'Create Shortcode', 'shortcode-mastery' ),
			__( 'Create Shortcode', 'shortcode-mastery' ),
			$this->menu_permission, 
			'shortcode-mastery-create',
			array( $this, 'shortcode_mastery_create_page' )
		);
		
	}
	
	/**
	 * Query shortcodes
	 */
	
	public function query_all_shortcodes() {
						
		$args = array(
			'post_type' => 'shortcode-mastery',
			'order' => 'name',
			'posts_per_page' => -1
		);
				
		$the_query = new WP_Query( $args );
		
		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) {
				
				global $post;
				
				$the_query->the_post();
				
				$name = 'sm_' . $post->post_name;
								
				$this->shortcodes[$name] = array();

				$this->shortcodes[$name]['id'] = get_the_ID();
												
				$this->shortcodes[$name]['params'] = array();
				
				$this->shortcodes[$name]['arguments'] = array();
				
				$this->shortcodes[$name]['embed_scripts'] = array();
				
				$this->shortcodes[$name]['embed_styles'] = array();
								
				$this->shortcodes[$name]['main_loop'] = '';
																
				$this->shortcodes[$name]['main_content'] = '';
				
				$this->shortcodes[$name]['scripts'] = '';
				
				$this->shortcodes[$name]['styles'] = '';

                /**
                 * WPBackery
                 *
        		 * @since 1.2.4
        		 */
        		 
				$this->shortcodes[$name]['shortcode_integration_wpbakery'] = '0';
				
				/**
				 * Write Scripts and Styles
				 * 
				 * @since 2.0.0
				 */

				$this->shortcodes[$name]['shortcode_integration_elementor'] = '0';

				$this->shortcodes[$name]['write_scripts'] = '0';

				$this->shortcodes[$name]['write_styles'] = '0';
								
				$sm_meta = get_post_meta( get_the_ID() );
										
				if ( $sm_meta ) {
					
					foreach( $sm_meta as $k => $v ) {
						
						switch( $k ) {
							
							case 'params':
							case 'arguments':
							case 'embed_scripts':
							case 'embed_styles':
																							
								$values = $this->decode( $v[0] );
								
								if ( $values && is_array( $values ) ) {
								
									foreach( $values as $value ) {
									
										$true_value = array();
										
										if ( isset( $value['value'] ) ) $true_value['value'] = $value['value'];
										
										if ( isset( $value['radio'] ) ) $true_value['radio'] = $value['radio'];
																				
										if ( isset( $value['checkbox'] ) ) $true_value['checkbox'] = $value['checkbox'];
										
										if ( sizeof( $true_value ) == 1 ) {
											
											$true_value = $value['value'];

										} elseif ( sizeof( $true_value ) == 0 ) {
											
											$true_value = '';

										}
										
										$this->shortcodes[$name][$k][$value['name']] = $true_value;
									
									}
								
								}
								
								break;
								
							case 'main_loop':
							case 'main_content':
							case 'scripts':
							case 'styles': $this->shortcodes[$name][$k] = $v[0]; break;
							
                            /**
                             * WPBackery
                             *
                    		 * @since 1.2.4
                    		 */
                    		 
							case 'shortcode_integration_wpbakery': $this->shortcodes[$name][$k] = $v[0]; break;
							
                            /**
                             * Write Scripts and Styles
                             *
                    		 * @since 2.0.0
                    		 */
							 
							case 'shortcode_integration_elementor': $this->shortcodes[$name][$k] = $v[0]; break;
							case 'write_scripts': $this->shortcodes[$name][$k] = $v[0]; break;
							case 'write_styles': $this->shortcodes[$name][$k] = $v[0]; break;
                            
							default: break;
							
						}
											
					}	
								
				}
												
			}
			
			wp_reset_postdata();
		}
		
	}
		
	/**
	 * Load shortcodes custom styles and scripts
	 */	
	
	public function load_shortcodes_styles() {
		
		$search = '';
		    
	    global $post;
	    
	    if ( $post ) $search = $post->post_content;
		      
	    $pattern = get_shortcode_regex( array_keys( $this->shortcodes ) );
	    	    	
	    if ( preg_match_all( '/'. $pattern .'/s', $search, $matches )
	        && array_key_exists( 2, $matches ) ) {
		        		        
			$keys = array();
			
		    $atts = null;
		    
		    foreach( $matches[0] as $key => $value) {
			    		        
		        $str = preg_match_all("/[^\\s=]+(=\"[^\"]+\")?/", $matches[3][$key], $mat );
		        			        
		        $get = implode( "&", $mat[0] );
		        			        
		        parse_str($get, $output);
		
		        $keys = array_unique( array_merge( $keys, array_keys( $output ) ) );
		        			        
				foreach ( $output as $k=>$o ) {
					
					$output[$k] = trim( $o, '"' );
					
				}
									
				$atts = $output;
						        
		        $name = $matches[2][$key];
		        
				$shortcode_params = $this->rendered_params( $name );
																								
				$a = $this->sm_shortcode_atts( $shortcode_params, $atts, $name );
				
				$render = true;
								
				foreach ( $a as $k=>$v ) {
															
					if ( in_array( $k, $this->get_value( $name, 'required' ) ) && $v == '' ) {
																		
						$render = false;
											
					}
										
				}
												
				if ( $render ) $this->enqueue_embed_scripts_and_styles( $a, $name );
				
			}
											    
	    }
	    	    	    
	}
	
	/**
	 * Enqueue custom styles and scripts for current shortcode
	 *
	 * @param array $a Shortcode Attributes
	 * @param string $name Shortcode name
	 */	
	 
	public function enqueue_scripts_and_styles( $a, $name, $admin = false ) {
				
		$scripts = $this->twig->render_content( $name . '.scripts', $a );
																		
		if ( $scripts )	{
																	
			$write = $this->get_value( $name, 'write_scripts' );
									
			new Shortcode_Mastery_Scripts( $name, $scripts, false, $admin, true, $write );
			
		}
		
	}
	
	/**
	 * Enqueue embedded styles and scripts for current shortcode
	 *
	 * @param array $a Shortcode Attributes
	 * @param string $name Shortcode name
	 */	
	
	public function enqueue_embed_scripts_and_styles( $a, $name, $loop = true, $admin = false ) {
												
		$embed_scripts = $this->get_value( $name, 'embed_scripts' );
		
		$is_head = false;
										
		if ( $embed_scripts ) {
					
			if ( is_array( $embed_scripts ) && sizeof( $embed_scripts ) > 0 ) {
							
				foreach ( $embed_scripts as $k => $arg ) {
												
					$argname = $name . '.embed_scripts.' . sanitize_title( $k );
										
					if ( is_array( $embed_scripts[ $k ] ) ) {
						
						if ( isset( $embed_scripts[ $k ]['radio'] ) && $embed_scripts[ $k ]['radio'] != '' ) $is_head = boolval( $embed_scripts[ $k ]['radio'] );
												
					}
										
					$embed_scripts[ $k ] = $this->twig->render_content( $argname, $a );
					
					if ( filter_var( $embed_scripts[ $k ], FILTER_VALIDATE_URL, ~FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_PATH_REQUIRED ) !== FALSE ) {
					
						if ( ! wp_script_is( sanitize_title( $k ), 'registered' ) ) {
																											
							wp_enqueue_script( sanitize_title( $k ), esc_url( $embed_scripts[ $k ] ), array( 'jquery' ), NULL, ! $is_head );

						}
					
					}
																
				}		
				
			}			
			
		}
		
		$embed_styles = $this->get_value( $name, 'embed_styles' );
						
		if ( $embed_styles ) {
					
			if ( is_array( $embed_styles ) && sizeof( $embed_styles ) > 0 ) {
							
				foreach ( $embed_styles as $k => $arg ) {
												
					$argname = $name . '.embed_styles.' . sanitize_title( $k );
					
					$embed_styles[ $k ] = $this->twig->render_content( $argname, $a );
					
					if ( filter_var( $embed_styles[ $k ], FILTER_VALIDATE_URL, ~FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_PATH_REQUIRED ) !== FALSE ) {
					
						if ( ! wp_style_is( sanitize_title( $k ), 'registered' ) ) {
																												
							wp_enqueue_style( sanitize_title( $k ), esc_url( $embed_styles[ $k ] ), NULL, NULL ); 
						
						}
					
					}
																
				}		
				
			}			
			
		}
		
		$styles = $this->twig->render_content( $name . '.styles', $a );
		
		if ( $styles ) {
						
			$write = $this->get_value( $name, 'write_styles' );
									
			new Shortcode_Mastery_Scripts( $name, $styles, $loop, $admin, false, $write );
						
		}
		
	}
	
	/**
	 * Register shortcodes
	 */
	
	public function register_all_shortcodes() {
	
		foreach ( $this->shortcodes as $name => $shortcode ) {
			
			add_shortcode( $name , array( $this, 'template_shortcode_function' ) );
			
            /**
             * WPBakery
             *
    		 * @since 1.2.4
    		 */		

            if ( defined( 'WPB_VC_VERSION' ) && absint( $this->shortcodes[$name]['shortcode_integration_wpbakery'] ) == 1 ) {
    
                $sm_meta = get_post_meta( absint( $this->shortcodes[$name]['id'] ) );
                
                $icon_src = $this->get_meta_part( $sm_meta, 'icon_source' ) ? $this->get_meta_part( $sm_meta, 'icon_source' ) : SHORTCODE_MASTERY_URL . 'images/sm-white@2x.png';
                
                $icon_src = explode('|', $icon_src );
                
                $params = array();
                
                $shortcode_params = $this->get_meta_part( $sm_meta, 'params' ) ? $this->decode( $this->get_meta_part( $sm_meta, 'params' ) ) : null;
				
                if ( $shortcode_params ) {
                
                    foreach( $shortcode_params as $param ) {
                        
                        $param_name = $param['name'];
                        
                        $param_value = $param['value'];
                        
                        $desc = isset( $param['desc'] ) ? wp_kses_post( $param['desc'] ) : '';
                        
						$title = isset( $param['title'] ) && $param['title'] != '' ? sanitize_text_field( $param['title'] ) : ucfirst( $param['name'] );
						
						if ( ! isset( $param['wpbackery'] ) ) {
                        
							$type = isset( $param['radio'] ) && absint( $param['radio'] ) == 1 ? 'checkbox' : 'textfield';
							
						} else {

							$type = $param['wpbackery'] ? sanitize_text_field( $param['wpbackery'] ) : 'textfield';
						}
                        
                        $current_params = array(
                            "type" => $type,
                            "heading" => $title,
                            "param_name" => $param_name,
                            "value" => $param_value,
							"description" => $desc,
							"admin_label" => true,
                        );
                        
                        if ( isset( $param['checkbox'] ) && absint( $param['checkbox'] ) == 1 ) $current_params['holder'] = 'div';
                        
                        $params[] = $current_params;
                    }
                
                }

                vc_map( array(
                    "name" => get_the_title( absint( $this->shortcodes[$name]['id'] ) ), 
                    "description" => wp_strip_all_tags( get_the_excerpt( absint( $this->shortcodes[$name]['id'] ) ) ),
                    "base" => $name,
                    "class" => "sm_shortcode",
                    "controls" => "full",
					"icon" => $icon_src[0],
					"save_always" => true,
                    "category" => __('Shortcode Mastery', 'shortcode-mastery'),
                    "params" => $params
                ) );
                
			}
			
            /**
             * Elementor
             *
    		 * @since 2.0.0
    		 */	

			if ( did_action( 'elementor/loaded' ) && defined( 'ELEMENTOR_VERSION' ) && absint( $this->shortcodes[$name]['shortcode_integration_elementor'] ) == 1 ) {
				
				$sm_meta = get_post_meta( absint( $this->shortcodes[$name]['id'] ) );
                $shortcode_params = $this->get_meta_part( $sm_meta, 'params' ) ? $this->decode( $this->get_meta_part( $sm_meta, 'params' ) ) : null;

				$this->elementor->set_shortcode_name( $name );
				$this->elementor->write_elementor_class( $name, $shortcode_params );
				
			}

		}
						
	}
	
	/**
	 * Shortcode template function
	 *
	 * @param attributes $atts Shortcode Attributes
	 * @param string $content Shortcode content
	 * @param string $name Shortcode name
	 * @return string Shortcode output
	 */	

	public function template_shortcode_function( $atts, $content, $name ) {

		global $post;
		
		$shortcode_params = $this->rendered_params( $name );
								
		$a = $this->sm_shortcode_atts( $shortcode_params, $atts, $name );
		
		$render = true;
				
		foreach ( $a as $k=>$v ) {
																
			if ( in_array( $k, $this->get_value( $name, 'required' ) ) && $v == '' ) {
																
				$render = false;
													
			}
								
		}
										
		if ( $render && ! get_post_meta( $post->ID, '_elementor_edit_mode', true ) ) $this->enqueue_scripts_and_styles( $a, $name, is_admin() );
										
		$query_arguments = $this->get_value( $name, 'arguments' );
						
		$temp_loop = '';
																		
		if ( is_array( $query_arguments ) && sizeof( $query_arguments ) > 0 ) {
						
			foreach ( $query_arguments as $k=>$arg ) {
								
				$argname = $name . '.arguments.' . $k;
				
				$query_arguments[ $k ] = $this->twig->render_content( $argname, $this->bool_unswap( $a ) );
								
				$query_arguments[ $k ] = $this->decode( $query_arguments[ $k ] );
								
			}
																	
			$the_query = new WP_Query( $this->bool_swap( $query_arguments ) );
														
			if ( $the_query->have_posts() ) {
			
				$current_post = 1;
				
				$post_count = $the_query->post_count;
				
				$loop_obj = array();
													
				while ( $the_query->have_posts() ) : $the_query->the_post();
												
					global $post;
					
					$loop_obj['current'] = $current_post;
					
					$loop_obj['all'] = $post_count;
					
					$loop_obj['last'] = false;
					
					$loop_obj['first'] = false;
					
					if ( $post_count == $current_post ) $loop_obj['last'] = true; 
					
					if ( $current_post == 1 ) $loop_obj['first'] = true; 
															
					$temp_loop .= $this->twig->render_content( $name . '.main_loop', $a, $post, $loop_obj );
										
					$current_post++;
				
				endwhile;
				
				wp_reset_postdata();
									
			}
			
		}
		
		if ( ! in_the_loop() && $render ) $this->enqueue_embed_scripts_and_styles( $a, $name, false, is_admin() );
		
		global $post;
																
		$shortcode_content = $this->twig->render_content( $name . '.main_content', $a, $post );
										
		$shortcode_name = get_page_by_path( str_replace( 'sm_', '', $name ), OBJECT, 'shortcode-mastery' );
		
		$output = '';
		
		//$output = '<!-- Shortcode Mastery ' . $shortcode_name->post_title . ' Start -->';
				
		$shortcode_content = $this->twig->render_inside_loop_content( $shortcode_content, $temp_loop );
				
		$shortcode_content = $this->twig->render_inside_content( $shortcode_content, $content );
				
		$output .= do_shortcode( $shortcode_content );
		
		//$output .= '<!-- Shortcode Mastery ' . $shortcode_name->post_title . ' End -->';
				
		if ( $render ) return $output;
		
		return null;

	}
	
	/**
	 * Get global values
	 *
	 * @param string $name Key of value
	 * @return string Shortcode value
	 */
	
	public function get_value( $name, $field ) {
		
		$value = '';
		
		if ( isset ( $this->shortcodes[$name][$field] ) ) $value = $this->shortcodes[$name][$field];
		
		return $value;
		
	}
	
	/**
	 * Set global values
	 *
	 * @param string $name Key of value
	 * @return string Shortcode value
	 */
	
	public function set_value( $name, $field, $value ) {
				
		$this->shortcodes[$name][$field] = $value;
				
	}
	
	/**
	 * Add Shortcode Post
	 *
	 * @param array $data $_REQUEST data
	 * @return string Redirect url
	 */
	
	public function add_shortcode_to_database( $data = null, $ajax = false ) {
				
		if ( ! $data ) $data = $_REQUEST;
		
		if ( isset( $data[ 'sm_new_shortcode'] ) && wp_verify_nonce( $data[ 'sm_new_shortcode' ], 'create_shortcode' ) ) {
			
			$data['not_editable'] = 0;
			
			$id = $this->add_shortcode_process( $data, $ajax );
			
			if ( intval( $id ) ) {
								
				$nonce = wp_create_nonce( 'edit_' . $id );

				$url = '?page=shortcode-mastery-create&action=edit&id=' . $id . '&sm_nonce=' . $nonce;
				
				if ( $ajax ) return $url;
				
				header( 'Location: ' . $url );
				
			}
	    }   
	}
	
	/**
	 * Edit Shortcode Post
	 *
	 * @param array $data $_REQUEST data
	 * @return int Post ID
	 */
	
	public function edit_shortcode_in_database( $data = null, $ajax = false ) {
		
		$id = null;
		
		$content = $excerpt = '';
		
		if ( ! $data ) $data = $_REQUEST;
		
		if ( isset( $data[ 'sm_edit_shortcode'] ) && wp_verify_nonce( $data[ 'sm_edit_shortcode' ], 'edit_shortcode' ) ) {
			
			$result = $this->validate_inputs( $data );
			
			if ( is_bool ( $result ) ) {			
							
				$title = sanitize_text_field( $data[ 'shortcode_title' ] );
								
				if ( isset( $data[ 'shortcode_excerpt' ] ) ) $excerpt = wp_kses_post( $data[ 'shortcode_excerpt' ] );
								
				$new_sm = array(
				  'ID'            => absint( $data[ 'shortcode_id' ] ),
				  'post_name'     => sanitize_title( wp_strip_all_tags( trim( $title ) ) ),
				  'post_title'    => wp_strip_all_tags( trim( $title ) ),
				  'post_excerpt'  => $excerpt
				);
				 
				$id = wp_update_post( $new_sm, true );
				
				if ( $id ) {
					
					$clean = $this->sanitize_inputs( $data );
					
					/* Delete old icon */
					
			    	$sm_meta = get_post_meta( absint( $data[ 'shortcode_id' ] ) );
					
					if ( $sm_meta ) {
						
						$uploads = wp_upload_dir();
								    		    				    		
			    		$icon_src = $this->get_meta_part( $sm_meta, 'icon_source' );
			    		
			    		if ( $icon_src && $icon_src != $clean['icon_source'] ) {
				    		
				    		$icon_src = str_replace( $uploads['baseurl'], $uploads['basedir'], $icon_src );
				    		
				    		$icon_src = explode( '|', $icon_src );
				    		
				    		if ( file_exists( $icon_src[0] ) && ! isset( $icon_src[1] ) ) unlink( $icon_src[0] );
				    		
				    	}
			    		
			    	}
			    	
			    	/* End old delete icon */	
						
					foreach ( $clean as $k => $v ) {
						
						if ( in_array( $k, $this->meta_array ) ) update_post_meta( $id, $k, $v );
						
					}

					/**
					 * Clean scripts
					 * 
					 * @since 2.0.0
					 */
					
					$this->clear_all_cache( sanitize_title( wp_strip_all_tags( trim( $title ) ) ) );

					return $id;
				}
								
			} else {
				
				$error = '<div class="notice notice-error is-dismissible" style="margin-top:1rem"><p>' . $result . '</p></div>';
				
				if ( ! $ajax ) echo $error;
				
				$id = $error;
												
			}
		}
		
		return $id;

	}
	
	/**
	 * Ajax Edit Shortcode
	 */
	
	public function ajax_sm_submit() {
		
		check_admin_referer( 'shortcode-mastery-ajax', 'security' );
		
		parse_str($_REQUEST['value'], $data);
				
		$id = '';
				
		if ( isset( $data[ 'sm_new_shortcode'] ) && wp_verify_nonce( $data[ 'sm_new_shortcode' ], 'create_shortcode' ) ) {
			
			$url = $this->add_shortcode_to_database( $data, true );
			
			echo $url;
									
		} elseif ( isset( $data[ 'sm_edit_shortcode'] ) && wp_verify_nonce( $data[ 'sm_edit_shortcode' ], 'edit_shortcode' ) ) {
				
			$id = $this->edit_shortcode_in_database( $data, true );
			
			if ( intval( $id ) ) {
				
				$sm = get_post( $id );
				
				if ( $sm ) {
					
					$values = array();
					
					$values['title'] = $sm->post_title;
					
					$values['code'] = 'sm_' . $sm->post_name;
					
					echo json_encode( $values );
					
				}
			
			} else {
				
				$values = array();
				
				$values['error'] = $id;
								
				echo json_encode( $values );
				
			}
		}
		
		wp_die();
	}
	
	/**
	 * Validation
	 *
	 * @param array $data $_REQUEST data
	 * @return bool Valid or NOT
	 */
	
	public function validate_inputs( $data = null ) {
		
		// Title
				
		if ( ! isset( $data[ 'shortcode_title' ] ) ) return __( 'Bad title', 'shortcode_mastery' );
		
		$title = sanitize_title( $data[ 'shortcode_title' ] );
		
		if ( ! $title ) return __( 'Bad title', 'shortcode_mastery' );
		
		foreach ( $data as $k => $value ) {
		
			switch ( $k ) {
				case 'params':
				case 'arguments':
				case 'main_loop':
				case 'main_content':
				case 'scripts':
				case 'styles':
							
					$result = $this->twig->validate_template( stripslashes( $value ), sanitize_title( $data[ 'shortcode_title' ] . '.' . $k ) );
										
					if ( $result ) return $result;
					
				break;
				
				default: break;	
				
			}
		
		}
						
		return true;
		
	}
	
	/**
	 * Sanitization
	 *
	 * @param array $inputs $_REQUEST data
	 * @return array Clean inputs
	 */
	 
	public function sanitize_inputs( $inputs ) {
		
		$clean_inputs = array();
		
		foreach ( $inputs as $key => $v ) {
		
			switch( $key ) {
				
				case 'editable': $clean_inputs[ $key ] = absint( $v ); break;
				
				case 'arguments':
								
				case 'params': 
				
				case 'embed_scripts':
				
				case 'embed_styles': $clean_inputs[ $key ] = esc_sql( $v ); break;
				
				case 'main_loop':
				
				case 'main_content':
				
				case 'scripts':
				
				case 'styles':
				
				case 'thumbnail_source':
				
				case 'icon_source': $clean_inputs[ $key ] = wp_slash( $v ); break;
				
                /**
                 * WPBakery
                 *
        		 * @since 1.2.4
        		 */
        		 
				case 'shortcode_integration_wpbakery': $clean_inputs[ $key ] = absint( $v ); break;
				
                /**
                 * Write Scripts and Styles
                 *
        		 * @since 2.0.0
        		 */

				case 'shortcode_integration_elementor': $clean_inputs[ $key ] = absint( $v ); break;
        		 
				case 'write_scripts': $clean_inputs[ $key ] = absint( $v ); break;
				
				case 'write_styles': $clean_inputs[ $key ] = absint( $v ); break;
																									
				default: $clean_inputs[ $key ] = $v; break;
				
			}
		
		}
		
		return $clean_inputs;
		
	}
	
	/**
     * JSON decode with swap booleans
     *
     * @param string JSON string
     * @return array Array of data
     */
	
	public function decode( $string ) {
		
		$str = str_replace( "\'", "'", $string );
						
		$temp_str = json_decode( $str, true );
		
		if ( is_array( $temp_str ) ) {
			
			$string = $temp_str;
			
		}
		
		return $string;
				
	}
	
	/**
	 * Get meta
	 *
	 * @param array $sm_meta Post meta
	 * @param string $part Meta part
	 * @return string Meta value
	 */
	
	public function get_meta_part( $sm_meta, $part ) {
		
		$html = '';
		
		foreach( $sm_meta as $k=>$v ) {
			
			if ( $k == $part ) {
				
				$html = $v[0];
				
			}
			
		}
								
		return $html;
		
	}
	
	/**
	 * Header layout
	 */
	
	public function header_template( $nav = true, $class = '' ) {
		
		if ( $class ) $class = ' ' . $class;
		
		?>
		<div class="wrap<?php echo $class; ?>" id="shortcode-mastery">
			
			<h1><a class="sm-link" href="<?php echo admin_url('admin.php?page=shortcode-mastery'); ?>"><img src="<?php echo SHORTCODE_MASTERY_URL . 'images/sm-new.png'; ?>" alt="<?php _e( 'Shortcode Mastery', 'shortcode-mastery' ); ?>">Shortcode Mastery<span class="version">v<?php echo $this->version; ?></span></a></h1>
			<?php do_action('sm_render_notices'); ?>
			<?php if ( $nav ) { ?>
			<div class="shortcode-mastery-nav">
				<ul>
					<li <?php echo $this->active_menu_item('shortcode-mastery'); ?>>
						<a href="<?php echo admin_url('admin.php?page=shortcode-mastery'); ?>"><?php _e( 'All Shortcodes', 'shortcode-mastery' ); ?></a>
					</li>
					<?php if ( current_user_can( $this->menu_permission ) ) { ?>
					<li <?php echo $this->active_menu_item('shortcode-mastery-create'); ?>>
						<a href="<?php echo admin_url('admin.php?page=shortcode-mastery-create'); ?>"><?php _e( 'Create Shortcode', 'shortcode-mastery' ); ?></a>
					</li>
					<?php } ?>
				</ul>
			</div>
		<?php }
	}

	private function active_menu_item( $item ) {
		
		$page = isset( $_REQUEST[ 'page' ] ) ? sanitize_text_field( $_REQUEST[ 'page' ] ) : null;

		$result = '';

		if ( $page == $item ) $result = 'class="active"';

		echo $result;

	}
	
	/**
	 * Footer layout
	 */
	
	public function footer_template() {
		?>
		</div>
		<?php
	}
	
	/**
	 * Render main page
	 */
	
	public function shortcode_mastery_page() {

		$id = '';
		
		$current_tab = 'custom';
		
		if ( isset( $_REQUEST['id'] ) ) $id = absint( $_REQUEST['id'] );
    	
    	if ( isset( $_REQUEST['sm_nonce'] ) && wp_verify_nonce( $_REQUEST['sm_nonce'], 'delete_' . $id ) ) {
			
			$this->clear_all_cache( sanitize_title( wp_strip_all_tags( trim( get_post( $id )->post_title ) ) ) );

			$this->delete_shortcode_process( $id );
							
		}
		
		if ( isset( $_REQUEST[ 'tab' ] ) ) $current_tab = esc_attr( $_REQUEST[ 'tab' ] );
		
		$this->header_template();
		
		echo '<div class="sm-title"><h2>' . __( 'All Shortcodes', 'shortcode-mastery' ) . '</h2></div>';
		
		global $wpdb;
		
        $table_name = $wpdb->prefix . 'posts';
        
        $meta_table_name = $wpdb->prefix . 'postmeta';

        $total_collected_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name, $meta_table_name WHERE post_type='shortcode-mastery' AND $table_name.ID = $meta_table_name.post_id AND $meta_table_name.meta_key = 'not_editable' AND $meta_table_name.meta_value = '1'");
        
        $multi = false;
        
        if ( intval( $total_collected_items ) > 0 ) {
	        
	        $multi = true;
				
			$tabs = array( 'custom' => __( 'Your Shortcodes', 'shortcode-mastery' ), 'collected' => __( 'Collected Shortcodes', 'shortcode-mastery' ) );
		
			echo '<h2 class="nav-tab-wrapper hide-if-no-js">';
			
		    foreach( $tabs as $tab => $name ) {
			    
		        $class = ( $tab == $current_tab ) ? ' nav-tab-active' : '';
		        
		        echo "<a class='nav-tab$class' href='?page=shortcode-mastery&tab=$tab'>$name</a>";
		
		    }
			
			echo '</h2>';
		
		}
		
		echo '<form id="shortcodes-table-filter" method="get">';
		
		echo '<input type="hidden" name="page" value="' . esc_attr( $_REQUEST['page'] ) . '" />';
		
		$collected = false;
		
		$whitch = 'custom';
		
		if ( $current_tab == 'collected' && intval( $total_collected_items ) > 0 ) {
			
			$collected = true;
			
			$whitch = 'collected';
								
		}
					
		echo '<div class="sm-shortcodes-panel">';
			
		$table = new Shortcode_Mastery_Table( $collected, $multi );
		
		$table->prepare_items();
		
		$table->display();
		
		echo '</div>';
		
		echo '</form>';
		
		$this->footer_template();
		
		$page = '';
		
		if ( isset ( $_REQUEST['page'] ) ) $page = esc_attr( $_REQUEST['page'] );

	}
	
	/**
	 * Render create page
	 */
	
	public function shortcode_mastery_create_page() {
		
		$hiddenClass = ' ' . 'sm-hidden';
		
		$id = null;
		
		if ( ! current_user_can( $this->menu_permission ) && ! isset( $_REQUEST['id'] ) ) {
			
			wp_die(
		        '<h1>' . __( 'Cheatin&#8217; uh?' ) . '</h1>' .
		        '<p>' . __( 'You are not allowed to create shortcodes.' ) . '</p>',
		        403
		    );
		}
					
		/* Edit request */
		
		if ( isset( $_REQUEST['id'] ) ) $id = absint( $_REQUEST['id'] );
		
		if ( isset( $_REQUEST['sm_nonce'] ) && wp_verify_nonce( $_REQUEST['sm_nonce'], 'edit_' . $id ) ) {
			
			$hiddenClass = '';

			$sm = get_post( $id );
			
			if ( $sm ) {
				
				$this->setDef( 'ID', $id );
			
				$this->setDef( 'title', $sm->post_title );
								
				$this->setDef( 'code', 'sm_' . $sm->post_name );
				
				$this->setDef( 'excerpt', wp_kses_post( $sm->post_excerpt ) );
								
				$sm_meta = get_post_meta( $id );
		
				if ( $sm_meta ) {
					
					foreach( $this->meta_array as $meta ) {
						
						$temp = $this->get_meta_part( $sm_meta, $meta );
																		
						switch( $meta ) {
								
							case 'params': 
							case 'arguments': 
							case 'embed_scripts':
							case 'embed_styles': $temp = esc_html( $temp ); break;
							case 'main_loop':
							case 'main_content':
							case 'scripts':
							case 'styles': 
							case 'thumbnail_source': 
							case 'icon_source': $temp = str_replace( "\'", "'", $temp ); break;
                            /**
                            * WPBakery
                            *
                            * @since 1.2.4
                            */
							case 'shortcode_integration_wpbakery': $temp = absint( $temp ); break;
                            /**
                            * Write Scripts and Styles
                            *
                            * @since 2.0.0
							*/
							case 'shortcode_integration_elementor': $temp = absint( $temp ); break;
							case 'write_scripts': $temp = absint( $temp ); break;
							case 'write_styles': $temp = absint( $temp ); break;
							default: break; 
							
						}
																										
						$this->setDef( $meta, $temp );

					}
				 
				}
											
			}

		}
				
		$this->setDef( 'groups', esc_html( json_encode( $this->getDef( 'groups' ) ) ) );

		/* Form action url */
		
		$page = $action = $sm_id = $sm_nonce = '';
		
		if ( isset ( $_REQUEST['page'] ) ) $page = esc_attr( $_REQUEST['page'] );
		if ( isset ( $_REQUEST['action'] ) ) $action = '&action=' . esc_attr( $_REQUEST['action'] );
		if ( isset ( $_REQUEST['id'] ) ) $sm_id = '&id=' . absint( $_REQUEST['id'] );
		if ( isset ( $_REQUEST['sm_nonce'] ) ) $sm_nonce = '&sm_nonce=' . esc_html( $_REQUEST['sm_nonce'] );
		
		/* Layout */
				
		$this->header_template();
		
		if ( current_user_can( $this->menu_permission ) ) { ?>
			
		<form id="shortcode-mastery-form" action="<?php echo admin_url( 'admin.php?page=' . $page . $action . $sm_id . $sm_nonce ); ?>" method="POST">		
		<?php }
			
		if ( isset($_REQUEST[ 'sm_nonce' ]) && wp_verify_nonce( $_REQUEST[ 'sm_nonce' ], 'edit_' . $id )  ) {
		
			wp_nonce_field( 'edit_shortcode', 'sm_edit_shortcode' );
			
			echo '<input type="hidden" name="shortcode_id" value="' . $id . '">';
			
			?>
			
			<div class="sm-title"><h2><?php echo __( 'Edit shortcode', 'shortcode-mastery' ); ?></h2></div>
			
			<?php
		
		} else {
			
			wp_nonce_field( 'create_shortcode', 'sm_new_shortcode' ); 
			
			?>
			
			<div class="sm-title"><h2><?php echo __( 'Create shortcode', 'shortcode-mastery' ); ?></h2></div>

			<?php
		}
		
		?>
						
		<div class="sm-flex">
		
		<?php include_once( SHORTCODE_MASTERY_DIR . 'templates/title.php' ); ?>
				
		<?php include_once( SHORTCODE_MASTERY_DIR . 'templates/params.php' ); ?>
						
		</div>
						
		<?php include_once( SHORTCODE_MASTERY_DIR . 'templates/query.php' ); ?>
				
		<?php include_once( SHORTCODE_MASTERY_DIR . 'templates/loop.php' ); ?>
				
		<?php include_once( SHORTCODE_MASTERY_DIR . 'templates/markup.php' ); ?>
		
		<?php include_once( SHORTCODE_MASTERY_DIR . 'templates/scripts.php' ); ?>
		
		<?php include_once( SHORTCODE_MASTERY_DIR . 'templates/styles.php' ); ?>
				
		<?php if ( current_user_can( $this->menu_permission ) ) { ?>
						
		<p class="sm-flex sm-flex-justify-start"><input type="submit" class="sm-submit sm-submit-shortcode sm-edit-button" value="<?php echo __( 'Save shortcode', 'shortcode-mastery' ); ?>"></p>

		</form>
		
		<div id="sm-popup-wrapper" @click.self="closePopup" :class="{ 'show' : cssClass }">
			
			<div id="sm-popup">
				<button title="<?php _e( 'Close (Esc)', 'shortcode-mastery' ); ?>" type="button" class="mfp-close" @click.self="closePopup" style="color:#222">×</button>
				<div v-html="content"></div>
			</div>

		</div>

		<?php }
		
		$this->footer_template();	
		
	}
	
	/* PRIVATE METHODS */
	
	/**
	 * Process Adding Shortcode
	 *
	 * @private
	 * @param array $data $_REQUEST data
	 * @return int Post ID
	 */
	
	private function add_shortcode_process( $data, $ajax = false ) {
		
		$id = null;
		
		$content = $excerpt = '';
		
		$result = $this->validate_inputs( $data );
		
		if ( is_bool( $result ) ) {
						
			$title = sanitize_text_field( $data[ 'shortcode_title' ] );
			
			if ( isset( $data[ 'shortcode_content' ] ) ) $content = wp_kses_post( $data[ 'shortcode_content' ] );
									
			if ( isset( $data[ 'shortcode_excerpt' ] ) ) $excerpt = wp_kses_post( $data[ 'shortcode_excerpt' ] );
						
			$new_sm = array(
			  'post_title'    => wp_strip_all_tags( trim( $title ) ),
			  'post_type'     => 'shortcode-mastery',
			  'post_status'   => 'publish',
			  'post_author'   => get_current_user_id(),
			  'post_content'  => $content,
			  'post_excerpt'  => $excerpt
			);
						 
			$id = wp_insert_post( $new_sm, true );
			
			if ( $id ) {
				
				$clean = $this->sanitize_inputs( $data );
				
				foreach ( $clean as $k => $v ) {
					
					if ( in_array( $k, $this->meta_array ) ) add_post_meta( $id, $k, $v );
					
				}
			}
						
		} else {
			
			$error = '<div class="notice notice-error is-dismissible"><p>' . $result . '</p></div>';
			
			if ( ! $ajax ) echo $error;
			
			$id = $error;
						
		}
		
		return $id;
	}
	
	/**
	 * Process deleting shortcode by ID
	 *
	 * @private
	 * @param int $id Shortcode ID
	 */	
	
	private function delete_shortcode_process( $id ) {
		
    	$sm_meta = get_post_meta( absint( $id ) );
		
		if ( $sm_meta ) {
			
			$uploads = wp_upload_dir();
					    		    		
    		$thumbnail_src = $this->get_meta_part( $sm_meta, 'thumbnail_source' );
    		
    		$icon_src = $this->get_meta_part( $sm_meta, 'icon_source' );
    		
    		if ( $thumbnail_src ) {
	    		
	    		$thumbnail_src = str_replace( $uploads['baseurl'], $uploads['basedir'], $thumbnail_src );
	    		
	    		if ( file_exists( $thumbnail_src ) ) unlink( $thumbnail_src );
	    		
	    	}
    		
    		if ( $icon_src ) {
	    		
	    		$icon_src = str_replace( $uploads['baseurl'], $uploads['basedir'], $icon_src );
	    		
	    		$icon_src = explode( '|', $icon_src );
	    		
	    		if ( file_exists( $icon_src[0] ) && ! isset( $icon_src[1] ) ) unlink( $icon_src[0] );
	    		
	    	}
    		
    	}
		
		wp_delete_post( absint( $id ), false );
				
	}
	
	/**
     * Render Parameters
     *
     * @param string $name Shortcode Name
     * @return array Rendered parameters
     */
	
	public function rendered_params( $name ) {
		
		$shortcode_params = $this->get_value( $name, 'params' );
										
		if ( ! $this->get_value( $name, 'rendered' ) ) {
			
			$required = array();
				
			if ( is_array( $shortcode_params ) && sizeof( $shortcode_params ) > 0 ) {
							
				foreach ( $shortcode_params as $k=>$arg ) {
					
					if ( is_array( $arg ) && isset( $arg[ 'checkbox' ] ) && $arg[ 'checkbox' ] ) $required[] = $k;
																
					$paramname = $name . '.params.' . $k;
					
					$shortcode_params[ $k ] = $this->twig->render_content( $paramname );
					
					if ( is_array( $arg ) && isset( $arg[ 'radio' ] ) && $arg[ 'radio' ] == 1 ) {
						
						if ( 'true' == strtolower( $shortcode_params[ $k ] ) ) {
													
							$shortcode_params[ $k ] = true;
							
						}
						
						if ( 'false' == strtolower( $shortcode_params[ $k ] ) ) {
							
							$shortcode_params[ $k ] = false;
							
						}	
						
					}
													
				}
																				
			}
			
			$this->set_value( $name, 'required', $required );

			$this->set_value( $name, 'params', $shortcode_params );
			
			$this->set_value( $name, 'rendered', true );
		
		}
		
		return $shortcode_params;
	}
	
	/**
     * Swap string with boolean value
     *
     * @param array $arr Array with strings like booleans
     * @return array Array of data
     */
	
	private function bool_swap( $arr ) {
		
		$array = array();
						
		if ( is_array( $arr ) ) {
		
			foreach( $arr as $k=>$v ) {
				
				if ( is_array( $v ) ) { 
				 
					$array[ $k ] = $this->bool_swap( $v );
					
				} else {
						
					if ( 'true' == strtolower( $v ) ) {
												
						$arr[ $k ] = true;
						
					}
					
					if ( 'false' == strtolower( $v ) ) {
						
						$arr[ $k ] = false;
						
					}	
					
					$array[ $k ] = $arr[ $k ];
				
				}
			
			}
		
		} else {
			
			$array = $arr;
		}
		
		return $array;
		
	}
	
	/**
     * Unswap string with boolean value
     *
     * @param array $arr Array with strings like booleans
     * @return array Array of data
     */
	
	private function bool_unswap( $arr ) {
		
		$array = array();
						
		if ( is_array( $arr ) ) {
		
			foreach( $arr as $k=>$v ) {
				
				if ( is_array( $v ) ) { 
				 
					$array[ $k ] = $this->bool_unswap( $v );
					
				} else {
						
					if ( true === $v && is_bool( $v ) ) {
												
						$arr[ $k ] = 'true';
						
					}
					
					if ( false === $v && is_bool( $v ) ) {
						
						$arr[ $k ] = 'false';
						
					}	
					
					$array[ $k ] = $arr[ $k ];
				
				}
			
			}
		
		} else {
			
			$array = $arr;
		}
		
		return $array;
		
	}
	
	/**
     * Custom Shortcode Atts Function with Boolean convert
     *
     * @param array $pairs Default values
     * @param array $atts User Inputs
     * @param string $shortcode Shortcode Name
     * @return array Completed parameters
     */	
	
	public function sm_shortcode_atts( $pairs, $atts, $shortcode = '' ) {
	    
		$atts = (array)$atts;
	    $out = array();
	    if ( ! empty( $pairs ) ) {
	    	foreach ($pairs as $name => $default) {
		        if ( array_key_exists($name, $atts) ) {
					
			        $atts[$name] = preg_replace('/{{(.*?)}}/', '{{ ${1} }}', $atts[$name] );
					
					/**
					 * Filtered by security reasons
					 *
					 * @ since 2.0.0
					 */
					//if ( ! preg_match('/{{.*fn\s*\(.*\).*|.*function\s*\(.*\).*}}/', $atts[$name] ) ) $atts[$name] = $this->twig->render_inline_content( $atts[$name] );
														
		        	if ( is_bool( $atts[$name] ) ) {
			      
			        	if ( 'true' == strtolower( $atts[$name] ) ) {
													
							$atts[$name] = true;
							
						}
						
						if ( 'false' == strtolower( $atts[$name] ) ) {
							
							$atts[$name] = false;
							
						}	
		        	}
		            $out[$name] = $atts[$name];
		        } else {
		            $out[$name] = $default;
		        }
		    }
	    }
	    /**
	     * Filters a shortcode's default attributes.
	     *
	     * If the third parameter of the shortcode_atts() function is present then this filter is available.
	     * The third parameter, $shortcode, is the name of the shortcode.
	     *
	     * @param array  $out       The output array of shortcode attributes.
	     * @param array  $pairs     The supported attributes and their defaults.
	     * @param array  $atts      The user defined shortcode attributes.
	     * @param string $shortcode The shortcode name.
	     */
	    if ( $shortcode ) {
	        $out = apply_filters( "shortcode_atts_{$shortcode}", $out, $pairs, $atts, $shortcode );
	    }
	 
	    return $out;
	}
	
	/**
	 * Dump Helper
	 */
	
	private function dump( $value ) {
		
		echo '<pre>';
		var_dump( $value );
		echo '</pre>';
		
	}
	
}
?>
