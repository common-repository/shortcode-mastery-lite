<?php
/**
 * Shortcode Mastery Table Class
 *
 * @class   Shortcode_Mastery_Table
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 */

class Shortcode_Mastery_Table extends WP_List_Table {
	
	/**
	 * Posts per page
	 *
	 * @access private
	 * @var int
	 */
	
	private $per_page = 10;
	
	/**
	 * Menu permission
	 *
	 * @access private
	 * @var string
	 */
	
	private $menu_permission = 'manage_options';
	
	/**
	 * Collected or custom shortcodes
	 *
	 * @access private
	 * @var bool
	 */
	
	private $collected;
	
	/**
	 * Single table or tabbed tables
	 *
	 * @access private
	 * @var bool
	 */
	
	private $multi;
		
    public function __construct( $collected = false, $multi = false ) {
	    
	    $this->collected = $collected;
	    
	    $this->multi = $multi;
	    
		parent::__construct( array(
		'singular' => 'shortcode_mastery_table',
		'plural' => 'shortcode_mastery_tables',
		'ajax' => false
		) );
    }
	
	public function get_columns() {
		
		$columns = array(
		'cb' => '<input type="checkbox" />',
		'shortcode_mastery_title' => __( 'Title', 'shortcode-mastery' ),
		'shortcode_mastery_code' => __( 'Shortcode', 'shortcode-mastery' ),
		'shortcode_mastery_actions' => __( 'Actions', 'shortcode-mastery' )
		);
		
		return $columns;
	}
	
	public function prepare_items() {
		
		global $wpdb;
		
        $table_name = $wpdb->prefix . 'posts';
        
        $meta_table_name = $wpdb->prefix . 'postmeta';

        $per_page = $this->per_page;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();
        
        if ( ! $this->collected ) $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE post_type='shortcode-mastery' AND post_content=''");
        
        if ( $this->collected ) $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE post_type='shortcode-mastery' AND post_content<>''");
        
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) * $per_page : 0;
        
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'post_title';
        
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';
		
        if ( $this->collected ) $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE post_type='shortcode-mastery' AND post_content<>'' ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);
        
        if ( ! $this->collected ) $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE post_type='shortcode-mastery' AND post_content='' ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);
        
		$this->set_pagination_args(
		    array(
		        //WE have to calculate the total number of items
		        'total_items'   => $total_items,
		        //WE have to determine how many items to show on a page
		        'per_page'  => $per_page,
		        //WE have to calculate the total number of pages
		        'total_pages'   => ceil( $total_items / $per_page ),
		    )
		);
		
		$_SERVER['REQUEST_URI'] = remove_query_arg( '_wp_http_referer', $_SERVER['REQUEST_URI'] );

	}
	
	public function no_items() {
		
		echo __( 'No shortcodes found.', 'shortcode-mastery' );
		
		if ( ! $this->collected ) {
			
			echo ' ' . '<a href="' . admin_url( 'admin.php' ) . '?page=shortcode-mastery-create' . '">' . __( 'Let\'s create one...', 'shortcode-mastery' ) . '</a>';
			
		} else {

			echo ' ' . '<a href="' . admin_url( 'admin.php' ) . '?page=shortcode-mastery-templates' . '">' . __( 'Let\'s collected one...', 'shortcode-mastery' ) . '</a>';
			
		}
		
	}
	
    public function column_default( $item, $column_name ) {
	    
	    switch( $column_name ) {
		    
		    case 'shortcode_mastery_title': 
		    
		    	$thumbnail_src = '';
		    	
		    	$icon_src = SHORTCODE_MASTERY_URL . 'images/sm-white.png';
		    	
		    	$icon_src_2x = SHORTCODE_MASTERY_URL . 'images/sm-white@2x.png';
		    	
		    	$edit_url = wp_nonce_url( admin_url( 'admin.php' ) . '?page=shortcode-mastery-create&action=edit&id='.$item['ID'], 'edit_'.$item['ID'],'sm_nonce');
		    	
		    	$sm_meta = get_post_meta( $item[ 'ID' ] );
				
				if ( $sm_meta ) {
							    	
		    		$editable = Shortcode_Mastery::getMeta( $sm_meta, 'not_editable' );
		    		
		    		$thumbnail_src = Shortcode_Mastery::getMeta( $sm_meta, 'thumbnail_source' );
		    		
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
			    				    	
		    		if ( ! $thumbnail_src ) {
			    		
						$thumbnail_src = get_the_post_thumbnail_url( $item[ 'ID' ], 'shortcode' );
									    		
					}
					
					$icon_src_2x = str_replace( array('http:', 'https:'), '', $icon_src_2x);
					$icon_src = str_replace( array('http:', 'https:'), '', $icon_src);
					$thumbnail_src = str_replace( array('http:', 'https:'), '', $thumbnail_src);
					$icon_src = set_url_scheme( $icon_src );
					$icon_src_2x = set_url_scheme( $icon_src_2x );
					$thumbnail_src = set_url_scheme( $thumbnail_src );   						
				}
				
				$excerpt = '';
				
				$more = '';
				
				if ( $icon_src ) {
					
					$icon_src = '<img class="default" src="' . $icon_src . '" srcset="' . $icon_src . ', ' . $icon_src_2x . ' 2x" />';
					
				}
				
				if ( $thumbnail_src ) {
					
					$thumbnail_src = '<div class="image-holder" style="background-image: url(' . $thumbnail_src . ');background-position: 50% 50%;background-size: cover"></div>';
					
				}
							
				$title = '<h3>'.$item['post_title'].'</h3>';
				
				if ( $item['post_excerpt'] != '' ) $excerpt = '<div class="desc">' . wpautop( $item['post_excerpt'] ) . '</div>';
				
				if ( $item['post_content'] != '' ) $more = '<a href="#content-' . $item['ID'] . '" class="sm-popup-inline sm-more-details">' . __( 'More Details', 'shortcode-mastery' ) . '</a><div class="mfp-hide table-details-popup" id="content-' . $item['ID'] . '">' . $thumbnail_src . wpautop( $item['post_content'] ) . '</div>';
				
				return '<div class="sm-title-media"><div class="img-container">' . $icon_src . '</div><div class="sm-titles">' . $title . $excerpt . $more . '</div></div>';
				
		    case 'shortcode_mastery_code':
		    
		    	$params = '';
		    	
		    	$sm_meta = get_post_meta( $item[ 'ID' ] );
				
				if ( $sm_meta ) {
											
					$values = Shortcode_Mastery::getMeta( $sm_meta, 'params' );
											
					$values = Shortcode_Mastery::decodeString( $values );
					
					if ( $values && is_array( $values ) ) {
						
						foreach( $values as $value ) {
							
							$value = (array) $value;

							$test = json_decode( $value['value'], true );

							if ( $test && is_array( $test ) ) {
								
								$value['value'] = '<strong>' . __( 'JSON', 'shortcode-mastery' ) . '</strong>';

							} else {

								$value['value'] = esc_html( $value['value'] );

							}

							$params .= ' ' . esc_html( $value['name'] ). '="' . str_replace( '&quot;', '&apos;', $value['value'] ) . '"';	
	
						}
						
					}
				 
				}
				
				$params = str_replace( array( '{[', ']}'), array( '{{', '}}' ), $params );

		    	return '[sm_' . $item['post_name'] . $params . ']';
		    			    	
		    case 'shortcode_mastery_actions':
		    
		    	$tab = 'custom';
		    	
		    	if ( $this->collected ) $tab = 'collected';
		    
		    	$del_url = wp_nonce_url('?page='.esc_attr($_REQUEST['page']).'&amp;action=delete&amp;id='.$item['ID'].'&amp;tab='.$tab, 'delete_'.$item['ID'],'sm_nonce');
		    	
		    	$delete = '<a class="sm-button sm-delete-button" href="#" onclick="deleteShortcode(\''.$del_url.'\')"><i class="icon-trash"></i>'.__('Delete', 'shortcode-mastery').'</a>';
		    	
		    	$edit_url = wp_nonce_url('?page=shortcode-mastery-create&amp;action=edit&amp;id='.$item['ID'], 'edit_'.$item['ID'],'sm_nonce');
		    	
		    	$edit = '<a class="sm-button sm-edit-button" href="'.$edit_url.'"><i class="icon-pencil"></i>'.__('Edit', 'shortcode-mastery').'</a>';
		    	
		    	$view = '<a class="sm-button sm-edit-button" href="'.$edit_url.'"><i class="icon-eye view"></i>'.__('View', 'shortcode-mastery').'</a>';
				
				$preview_url = add_query_arg( array(
				    'action'    => 'shortcode-mastery-preview-page',
				    'sm_nonce'  => wp_create_nonce( 'shortcode-mastery-preview-page' ),
				    'shortcode_id' => $item['ID']
				), admin_url( 'admin.php' ) );
						    	
				$preview = '<a href="' . $preview_url . '" class="sm-popup-link sm-button sm-loop-button"><i class="icon-eye view"></i>' . __( 'Preview', 'shortcode-mastery' ) . '</a>';
		    	
		    	$container = $preview . $edit . $delete;
		    	
		    	$sm_meta = get_post_meta( $item[ 'ID' ] );
								
				if ( $sm_meta ) {
							    	
		    		$editable = Shortcode_Mastery::getMeta( $sm_meta, 'not_editable' );
		    						
				}
				
				if ( ! current_user_can( $this->menu_permission ) && $editable ) $container = $preview . $view;
		    			    	
		    	return $container;
		    	
		    default: return false;
	    }
    }
	
	public function get_sortable_columns() {
		
		$sortable_columns = array(
			'shortcode_mastery_title' => array( 'post_title', true ),
		);
		
		return $sortable_columns;
	}
	
	public function get_bulk_actions() {
		
		$actions = array();
		
		if ( current_user_can( $this->menu_permission ) ) {
		
		    $actions = array(
		        'bulk-delete' => __( 'Delete', 'shortcode-mastery' ),
		    );
        
        }
        
        return $actions;
    }
    
	public function column_cb( $item ) {
        
        return sprintf(
            '<input type="checkbox" name="shortcodes[]" value="%s" />', $item['ID']
        );    
    }
    
	protected function get_table_classes() {
		
		$classes = array( 'wp-list-table', 'widefat', $this->_args['plural'] );
				
	    return $classes;
	}
	
    public function display() {
	    
		$tab = 'custom';
		   	
		if ( $this->collected ) $tab = 'collected';
	 
	    echo '<input type="hidden" name="tab" value="' . $tab . '" />';
        
        $singular = $this->_args['singular'];
 
		if ( $this->multi || $this->has_items() ) $this->display_tablenav( 'top' );
 
        $this->screen->render_screen_reader_content( 'heading_list' );
		?>
		<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
		    <thead>
		    <tr>
		        <?php $this->print_column_headers(); ?>
		    </tr>
		    </thead>
		 
		    <tbody id="the-list"<?php
		        if ( $singular ) {
		            echo " data-wp-lists='list:$singular'";
		        } ?>>
		        <?php $this->display_rows_or_placeholder(); ?>
		    </tbody>
		 
		    <tfoot>
		    <tr>
		        <?php $this->print_column_headers( false ); ?>
		    </tr>
		    </tfoot>
		 
		</table>
		<?php
        if ( $this->multi || $this->has_items() ) $this->display_tablenav( 'bottom' );
    }
  
	public function process_bulk_action() {
		
        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $action = 'bulk-' . $this->_args['plural'];

            if ( ! wp_verify_nonce( $nonce, $action ) )
                wp_die( 'Nope! Security check failed!' );

        }

        $action = $this->current_action();

        switch ( $action ) {

            case 'bulk-delete':
            
            	$ids = null;
            	
            	if ( isset( $_REQUEST['shortcodes'] ) ) $ids = $_REQUEST['shortcodes'];
            	
            	if ( $ids ) {
                
	                foreach ( $ids as $id ) {
		                
						Shortcode_Mastery::deleteShortcode( $id );
		                
	                }
	                
	                Shortcode_Mastery::clearCache();

                }
                
                break;

            default:
                return;
                break;
        }

        return;
    }
	
}