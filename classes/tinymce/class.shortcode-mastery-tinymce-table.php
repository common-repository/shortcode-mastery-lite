<?php
/**
 * Shortcode Mastery TinyMCE Table Class
 *
 * @class   Shortcode_Mastery_TinyMCE_Table
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 */

class Shortcode_Mastery_TinyMCE_Table extends WP_List_Table {
	
    public function __construct() {

		parent::__construct( array(
			'singular'=> 'shortcode_mastery_tinymce_table', //Singular label
			'plural' => 'shortcode_mastery_tinymce_tables', //plural label, also this well be one of the table css class
			'ajax'   => true //We won't support Ajax for this table
		) );
    }
    
	public function get_columns() {
		return null;
	}
	
	protected function get_table_classes() {
		
		$classes = array( 'wp-list-table', 'widefat', 'fixed', $this->_args['plural'] );
				
	    return $classes;
	}
		
	public function prepare_items() {
				        		
        $per_page = 4;
        
        $search = '';
	    
        $paged = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 1;
        
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array( 'date', 'post_title' ) ) ) ? $_REQUEST['orderby'] : 'post_title';
                
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';
                
        $args = array(
	        'post_type' => 'shortcode-mastery',
	        'order'     => $order,
	        'orderby'   => $orderby,
	        'paged'     => $paged,
	        'showposts' => $per_page
        );
        
	    if ( isset( $_REQUEST['s'] ) ) {
		   	$args['s'] = esc_attr($_REQUEST['s']);
	    }
	    		
		if ( isset( $_REQUEST['tab'] ) ) {
			
			$tab = sanitize_title($_REQUEST['tab']);
			
			switch ($tab) {
				case 'custom': 
					$args['meta_key'] = 'not_editable';
					$args['meta_value'] = 0;
				break;
				default: break;
			}
			
		}
	   
		$this->items = query_posts( $args );
		
		global $wp_query;
		
		$total_items = $wp_query->found_posts;
						
		wp_reset_postdata();
        
		$this->set_pagination_args(
		    array(
		        //WE have to calculate the total number of items
		        'total_items'   => $total_items,
		        //WE have to determine how many items to show on a page
		        'per_page'  => $per_page,
		        //WE have to calculate the total number of pages
		        'total_pages'   => ceil( $total_items / $per_page ),
		        // Set ordering values if needed (useful for AJAX)
		        'orderby'   => ! empty( $_REQUEST['orderby'] ) && '' != $_REQUEST['orderby'] ? $_REQUEST['orderby'] : 'title',
		        'order'     => ! empty( $_REQUEST['order'] ) && '' != $_REQUEST['order'] ? $_REQUEST['order'] : 'asc'
		    )
		);

	}
	
	public function no_items() {
		
		echo '<p class="sm-no-items">' . __( 'There is no shortcodes yet.', 'shortcode-mastery' ) . '</p>';
		
	}
    
	public function display() {	?>
				
		<form id="shortcodes-tinymce-filter" method="get">
		
		<input type="hidden" name="action" value="shortcode-mastery-tinymce-page">
		
		<?php
			
	    wp_nonce_field( 'ajax_shortcode_mastery_tinymce_table_nonce', 'ajax_shortcode_mastery_tinymce_table_nonce' );
		
		if ( $this->has_items() ) {
		
		    echo '<input id="order" type="hidden" name="order" value="' . $this->_pagination_args['order'] . '" />';
		    echo '<input id="orderby" type="hidden" name="orderby" value="' . $this->_pagination_args['orderby'] . '" />';
	    
	    }
	    
	    if ( ! $this->has_items() ) $this->no_items();
	 
		$singular = $this->_args['singular'];
 
        $this->screen->render_screen_reader_content( 'heading_list' );
        
		if ( $this->has_items() ) {
			
			echo '<span id="sm-search-description">' . $this->display_searching_desc() . '</span>';
 
	        $this->display_tablenav( 'top' );
			?>
			<table class="wp-list-table sm-tinymce-wrap <?php echo implode( ' ', $this->get_table_classes() ); ?>">
		        <tbody id="the-list">
			    <?php
					$this->display_items_or_placeholder(); 
				?>
		        </tbody>
			</table>
			<?php
	        $this->display_tablenav( 'bottom' );
        
        }
        ?>
		</form>
		<?php
	}
	
	protected function display_tablenav( $which ) {
		
		if ( 'top' === $which ) {
			
		    wp_nonce_field( 'shortcode-mastery-tinymce-page', 'sm_nonce' );
		    
		}
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
            <?php if ( $this->has_items() ): ?>
            <div class="alignleft actions bulkactions">
                    <?php $this->bulk_actions( $which ); ?>
            </div>
            <?php endif;
            $this->extra_tablenav( $which );
            $this->pagination( $which );
		?>
            <br class="clear" />
		</div>
		<?php
    }
	
	public function display_searching_desc() {
		
		$tag = '';
		
		if ( isset( $_REQUEST['s'] ) ) { 
			
			if ( isset( $_REQUEST['s'] ) ) {
				
				$tag = esc_attr( $_REQUEST['s'] );
				
			}
			
			if ( $tag ) return '<p class="sm-collection-desc">' . __( 'Searching results by ', 'shortcode-mastery' ) . '<strong>' . $tag . '</strong>:</p>';
			
		}
		
		return '';
	}
	
    public function display_items_or_placeholder() {
	    
        if ( $this->has_items() ) {
	        
            $this->display_items();
            
        }
    }

    public function display_items() {
        
        foreach ( $this->items as $item ) {

			$sm = new Shortcode_Mastery_TinyMCE_Item( $item );
			
			$sm->render();			
			
		}
    }

	public function ajax_response() {
		
		if ( $this->items ) {
	 	 	 
	    extract( $this->_args );
	    
	    extract( $this->_pagination_args, EXTR_SKIP );
				 
		if ( isset( $_REQUEST['s'] ) ) $s = esc_attr( $_REQUEST['s'] );	    
		
		if ( isset( $_REQUEST['paged'] ) ) $paged = absint( $_REQUEST['paged'] );
	    
	    ob_start();
	    if ( ! empty( $_REQUEST['no_placeholder'] ) )
	        $this->display_items( $tab, $tag, $pack, $paged, $s );
	    else
	        $this->display_items_or_placeholder( $tab, $tag, $pack, $paged, $s );
	    $rows = ob_get_clean();
	 
	    ob_start();
	    $this->display_tablenav( 'top' );
	    $pagination_top = ob_get_clean();
	 
	    ob_start();
	    $this->display_tablenav( 'bottom' );
	    $pagination_bottom = ob_get_clean();
	 
	    $response = array( 'rows' => $rows );
	    $response['searchDescription'] = $this->display_searching_desc();
	    $response['pagination']['top'] = $pagination_top;
	    $response['pagination']['bottom'] = $pagination_bottom;
	    	    
	    } else {
		   $response = array( 'rows' => $this->no_items() ); 
	    }
	 
	    die( json_encode( $response ) );
	}
	
}