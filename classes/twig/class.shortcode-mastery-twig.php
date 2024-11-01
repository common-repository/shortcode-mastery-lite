<?php
/**
 * Shortcode Mastery Twig
 *
 * @class   Shortcode_Mastery
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 */

class Shortcode_Mastery_Twig {
	
	/**
	 * Version number
	 *
	 * @access public
	 * @var float
	 */
	
	public $version;
	
	/**
	 * Twig Object
	 *
	 * @access private
	 * @var object
	 */	
	 
	public $twig;
	
	/**
	 * Regular expression for all options swap
	 *
	 * @access private
	 * @var float
	 */
	
	private $template = "/{{((?>[^{\[\]}]+|(?R))*)}}/";
	
	/**
	 * Regular expression for if statement
	 *
	 * @access private
	 * @var float
	 */
	
	private $template_content = "/{\@((?>[^{\@\@}]+|(?R))*)\@}/";

	/**
	 * Rescticted functions
	 *
	 * @access private
	 * @var array
	 */
	
	private $restricted = array(
		'phpinfo', 'eval'
	);

	/**
	 * Init Twig Engine 
	 *
	 * @static
	 */		
	
	public function __construct( $version ) {
		
		$this->version = $version;
		
		$this->init(); 
	}
	
	/**
	 * Allow all php functions 
	 */	
	
	public function twig_callback( $name ) {
		    
		if ( function_exists( $name ) && ! in_array( $this->restricted ) ) {
		        
		    return new Twig_SimpleFunction( $name, $name );
		    
		}
		
	    return false;	
	    	
	}
	
	/**
	 * Wordpress escaping 
	 */		
	
	public function esc_url_callback( Twig_Environment $env, $string ) {
		
		return esc_url( $string );
		
	}
	
	public function esc_html_callback( Twig_Environment $env, $string ) {
		
		return esc_html( $string );
		
	}

	public function esc_attr_callback( Twig_Environment $env, $string ) {
		
		return esc_attr( $string );
		
	}
	
	public function esc_js_callback( Twig_Environment $env, $string ) {
		
		return esc_js( $string );
		
	}
	
	public function wp_kses_post_callback( Twig_Environment $env, $string ) {
		
		return wp_kses_post( $string );
		
	}
	
	/**
	 * Init Twig
	 */	
	
	private function init() {
		
		if ( ! file_exists( SHORTCODE_MASTERY_DIR . 'cache' ) ) {
		    
		    mkdir( SHORTCODE_MASTERY_DIR . 'cache', 0755, true );
		    
		}

		$this->twig = new Twig_Environment( 
			new Shortcode_Mastery_Twig_Loader(),
			array( 
				'auto_reload' => true,
				'strict_variables' => false,
			)
		);
		
		$this->twig->addExtension( new Shortcode_Mastery_Twig_Extension() );
		
		$this->twig->addGlobal( 'GLOBALS', $GLOBALS );
		$this->twig->addGlobal( 'COOKIE', $_COOKIE );
		$this->twig->addGlobal( 'GET', $_GET );
		$this->twig->addGlobal( 'POST', $_POST );
		$this->twig->addGlobal( 'FILES', $_FILES );
		if ( isset( $_SESSION ) ) $this->twig->addGlobal( 'SESSION', $_SESSION );
		$this->twig->addGlobal( 'REQUEST', $_REQUEST );
		$this->twig->addGlobal( 'ENV', $_ENV );
		$this->twig->addGlobal( 'SERVER', $_SERVER );
		
		$this->twig->getExtension('Twig_Extension_Core')->setEscaper( 'esc_url', array( $this, 'esc_url_callback' ) );
		$this->twig->getExtension('Twig_Extension_Core')->setEscaper( 'esc_js', array( $this, 'esc_js_callback' ) );
		$this->twig->getExtension('Twig_Extension_Core')->setEscaper( 'esc_html', array( $this, 'esc_html_callback' ) );
		$this->twig->getExtension('Twig_Extension_Core')->setEscaper( 'esc_attr', array( $this, 'esc_attr_callback' ) );
		$this->twig->getExtension('Twig_Extension_Core')->setEscaper( 'wp_kses_post', array( $this, 'wp_kses_post_callback' ) );

		$function = new Twig_SimpleFunction( 'function',  array( $this, 'function_wrapper' ) );
		$this->twig->addFunction($function);

		$fn= new Twig_SimpleFunction( 'fn',  array( $this, 'function_wrapper' ) );
		$this->twig->addFunction($fn);
		
		$lexer = new Twig_Lexer( $this->twig, array(
		    'tag_comment'   => array( '{#', '#}' ),
		    'tag_block'     => array( '{%', '%}' ),
		    'tag_variable'  => array( '{{', '}}' ),
		    'interpolation' => array( '#{', '}' ),
		));
		
		$this->twig->setLexer( $lexer );
		
		/**
		 * Disabled by security reasons
		 *
		 * @ since 1.2.5
		 */
		//$this->twig->registerUndefinedFunctionCallback( array( $this, 'twig_callback' ) );
				
	}

	/**
	 * @since 1.0.1
	 *
	 * Render content
	 *
	 * @param string $name Shortcode name
	 * @param array $a Shortcode parameters
	 * @param object $post Current post object
	 * @param object $loop_obj Loop counts object
	 *
	 * @return string Rendered content
	 */	
	 	
	public function render_content( $name, $a = null, $post = null, $loop_obj = null ) {

		$tempName = explode( '.', $name );

		$shortcode_name = str_replace( 'sm_', '', $tempName[0] );

		unset( $tempName[0] );
		
		$params = implode( '_', $tempName );

		if ( ! $this->twig->getCache() || ( $this->twig->getCache() && $this->twig->getCache()->shortcode != $shortcode_name ) ) {
			
			$this->twig->setCache( new Shortcode_Mastery_Twig_Cache( SHORTCODE_MASTERY_DIR . 'cache', $shortcode_name, $tempName[1] ) );

		}
		
		if ( ! $a ) $a = array();
		
		$atts = $a;
									
		if ( $post ) {
			
			$a = array_merge( [ 'post' => new Shortcode_Mastery_Twig_Post( $post ) ], $a );
						
		}
		
		if ( $loop_obj ) {
			
			$a = array_merge( [ 'loop' => $loop_obj ], $a );

		}
		
		$a = array_merge( [ 'atts' => $atts ], $a );
		
		$shortcode_name = str_replace( 'sm_', '', $name );
		
		$shortcode_name = explode( '.', $shortcode_name );
				
		$shortcode_name = get_page_by_path( $shortcode_name[0], OBJECT, 'shortcode-mastery' );
		
		$a = array_merge( [ 'shortcode' => $shortcode_name ], $a );
										
		$content = $this->twig->render( $name, $a );
												
		return $content;		
	}
		
	/**
	 * Replace {@ CONTENT @} with Shortcode content
	 *
	 * @param string $content Dirty content
	 * @param string $shortcode_content Shortcode content
	 *
	 * @return string Rendered content
	 */	
	
	public function render_inside_content( $content, $shortcode_content ) {
								
		if ( preg_match_all( $this->template_content, $content, $m ) ) {
								   
		    foreach ( $m[1] as $i => $varname ) {
			    			    				    		    				    
			    $varname = trim( $varname );
			    			    			    			    
				if ( $varname == 'CONTENT' ) {
							    	
		    		$content = str_replace($m[0][$i], sprintf( '%s', $shortcode_content ), $content );
		    		
		    				    	
		    	}
		    	    			    
		    }  
		    
		}		
		
		return $content;
	}
	
	/**
	 * Check {@ CONTENT @} availablity
	 *
	 * @param string $content Dirty content
	 *
	 * @return bool Yes or No
	 */	
	
	public function check_inside_content( $content ) {
								
		if ( preg_match_all( $this->template_content, $content, $m ) ) {
								   
		    foreach ( $m[1] as $i => $varname ) {
			    			    				    		    				    
			    $varname = trim( $varname );
			    			    			    			    
				if ( $varname == 'CONTENT' ) {
							    	
		    		return true;
		    		
		    				    	
		    	}
		    	    			    
		    }  
		    
		}		
		
		return false;
	}
	
	/**
	 * Replace {@ LOOP @} with Loop content
	 *
	 * @param string $content Dirty content
	 * @param string $shortcode_content Shortcode content
	 *
	 * @return string Rendered content
	 */
	
	public function render_inside_loop_content( $content, $shortcode_content ) {
								
		if ( preg_match_all( $this->template_content, $content, $m ) ) {
								   
		    foreach ( $m[1] as $i => $varname ) {
			    			    				    		    				    
			    $varname = trim( $varname );
			    			    			    			    
				if ( $varname == 'LOOP' ) {
		    	
		    		$content = str_replace($m[0][$i], sprintf( '%s', $shortcode_content ), $content );
		    				    	
		    	}
		    	    			    
		    }  
		    
		}
		
		return $content;
	}
	
	/**
	 * @since 1.1.0
	 *
	 * Render inline content
	 *
	 * @param string $name Inline content
	 * @param array $a Shortcode parameters
	 *
	 * @return string Rendered content
	 */	
	 
	public function render_inline_content( $string, $a = array() ) {
		
		$this->twig->setCache( false );

		try {
			
		    $this->twig->parse( $this->twig->tokenize( new Twig_Source( $string, 'inline_content') ) );
		    
			$template = $this->twig->createTemplate( $string );
		
			return $template->render( $a );

		} catch ( Twig_Error_Syntax $e ) {
		   
			return $string;
		   	
		}
		
	}

	/**
	 * @since 1.1.0
	 *
	 * Validate template
	 *
	 * @param string $template Template
	 * @param string $name Template name
	 *
	 * @return bool Result
	 */	
	 	
	public function validate_template( $template, $name ) {
		
		try {
			
		    $this->twig->parse( $this->twig->tokenize( new Twig_Source( $template, $name ) ) );
		    
		    return null;

		} catch ( Twig_Error_Syntax $e ) {
		   
			return 'Twig Error: '.$e->getRawMessage();
		   	
		}
		
	}

	/**
	 * @since 1.2.5
	 *
	 * Call native PHP function
	 *
	 * @param string $func PHP function name
	 * @param string $params Array of parameters
	 */	

	public function function_wrapper( $func, ...$params ) {
		if ( function_exists( $func ) && ! in_array( $func, $this->restricted ) ) call_user_func_array( $func, $params );
	}

}