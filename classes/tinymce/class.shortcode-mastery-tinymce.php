<?php
/**
 * Shortcode Mastery TinyMCE
 *
 * @class   Shortcode_Mastery_TinyMCE
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 */

class Shortcode_Mastery_TinyMCE {

	/**
	 * Version number
	 *
	 * @access public
	 * @var float
	 */
	
	public $version;
	
	/**
	 * Constructor
	 */	

	public function __construct( $version ) {
		
		$this->version = $version;
				
		$this->hooks();
		
	}

	/**
	 * Wordpress Hooks
	 */
	
	public function hooks() {
		
		add_action( 'admin_action_shortcode-mastery-tinymce-page', array( $this, 'render_tinymce_page' ) );
				
		add_action( 'media_buttons', array( $this, 'sm_media_button' ), 15 );
		
		add_action( 'wp_enqueue_media', array( $this, 'include_sm_button_js' ) );
						
	}
	
	public function render_tinymce_page() {
		
		if ( ! defined( 'IFRAME_REQUEST' ) && isset( $_REQUEST[ 'sm_nonce' ] ) && wp_verify_nonce( $_REQUEST[ 'sm_nonce' ], 'shortcode-mastery-tinymce-page' ) ) {
				    
		    define( 'IFRAME_REQUEST', true );
		    
			$table = new Shortcode_Mastery_TinyMCE_Table();
			
			$table->prepare_items();
			
			add_action( 'admin_enqueue_scripts', array( $this, 'render_tinymce_scripts' ) );
					    
			iframe_header();
			
			$this->render_menu();
			
			echo '<div class="sm-wrap-all">';
			
			$table->display();
			
			echo '</div>';
						
			iframe_footer();
		
		}
		
		exit();
		
	}
	
	public function render_tinymce_scripts() {
				
		wp_enqueue_script( 'shortcode-mastery-tinymce-script', SHORTCODE_MASTERY_URL . 'js/shortcode-mastery-media-button.js', array( 'jquery' ), $this->version, true );
		
		wp_localize_script( 'shortcode-mastery-tinymce-script', 'sm', array(
			'required' => __( 'Please fill required fields!', 'shortcode-mastery' ),
			)
		);	
	}
	
	private function render_menu() {
		
		$search = '';
							
		?>
		<div class="wp-filter sm-filter sm-tinymce-filter">
			
			<div class="shortcode-mastery-nav">
				<ul>
					<li>
						<a href="<?php echo wp_nonce_url('?action=shortcode-mastery-tinymce-page&tab=all', 'shortcode-mastery-tinymce-page','sm_nonce'); ?>" id="sm-tinymce-all-shortcodes"><?php _e( 'All Shortcodes', 'shortcode-mastery' ); ?></a>
					</li>
					<li>
						<a href="<?php echo wp_nonce_url('?action=shortcode-mastery-tinymce-page&tab=custom', 'shortcode-mastery-tinymce-page','sm_nonce'); ?>" id="sm-tinymce-all-custom-shortcodes"><?php _e( 'Your Custom Shortcodes', 'shortcode-mastery' ); ?></a>
					</li>
				</ul>
			</div>
		
			<form class="search-form tinymce-shortcodes" method="get">
				<input type="hidden" name="action" value="shortcode-mastery-tinymce-page">
				<input type="hidden" name="sm_nonce" value="<?php echo wp_create_nonce( 'shortcode-mastery-tinymce-page' ); ?>">
				<?php wp_nonce_field( 'ajax_shortcode_mastery_tinymce_search_nonce', 'ajax_shortcode_mastery_tinymce_search_nonce' ); ?>
				<label>
					<span class="screen-reader-text"><?php _e( 'Search Shortcodes', 'shortcode-mastery' ); ?></span>
					<input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" class="wp-filter-search sm-filter-search sm-tinymce-search" placeholder="<?php _e( 'Search shortcodes...', 'shortcode-mastery' ); ?>">
				</label>
				<input type="submit" id="search-submit" class="button hide-if-js" value="<?php _e( 'Search Shortcodes', 'shortcode-mastery' ); ?>" aria-describedby="live-search-desc">	
			</form>
		</div> <?php
	}
	
	public function include_sm_button_js() {
		
		wp_enqueue_style( 'magnific-popup', SHORTCODE_MASTERY_URL  . 'css/magnific-popup.css' );
		
		wp_enqueue_script( 'magnific-popup', SHORTCODE_MASTERY_URL  . 'js/magnific-popup.js', array( 'jquery' ), $this->version, true);
		
		wp_enqueue_script( 'shortcode-mastery-popup', SHORTCODE_MASTERY_URL  . 'js/shortcode-mastery-popup.js', array( 'jquery', 'magnific-popup' ), $this->version, true);
	
		wp_enqueue_script( 'shortcode-mastery-media-button', SHORTCODE_MASTERY_URL  . 'js/shortcode-mastery-media-button.js', array( 'jquery' ), $this->version, true);
		
	}

	public function sm_media_button() {
		
		$url = add_query_arg( array(
		    'action'    => 'shortcode-mastery-tinymce-page',
		    'sm_nonce'  => wp_create_nonce( 'shortcode-mastery-tinymce-page' )
		), admin_url( 'admin.php' ) );
		
	?>
		<a href="<?php echo $url; ?>" id="sm-media-button" class="sm-popup-link sm-media-button button" title="Shortcode Mastery">
			<img src="<?php echo SHORTCODE_MASTERY_URL . 'images/sm-tiny.png'; ?>">
		</a>
		
	<?php }
	
}