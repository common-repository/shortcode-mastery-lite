<?php
/**
 * Shortcode Mastery Twig Post
 *
 * @class   Shortcode_Mastery_Twig_Post
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 */

class Shortcode_Mastery_Twig_Post {
	
	/**
	 * Post object
	 *
	 * @access protected
	 * @var object
	 */
	
	protected $post;
	
	/**
	 * Constructor
	 *
	 * @param object $post Post Object
	 */
	
	public function __construct( $post ) {
		
		$this->post = $post;
		
	}
	
	/**
	 * Getter
	 *
	 * @param string $name Post property name
	 */
	
    public function __get( $name ) {
        
        if ( isset( $this->post->$name ) ) {
	        
            echo $this->post->$name;
            
        }

    }
    
	/**
	 * Isset property
	 *
	 * @param string $name Post property name
	 * @return bool Yes or No
	 */

    public function __isset( $name ) {
	    
        if ( isset( $this->post->$name ) ) {
	        
            return true;
            
        }

        return false;
        
    }

	/**
	 * Print Class
	 *
	 * @return string Image source
	 */
    
	public function __toString() {
				
		if ( isset( $this->post->post_title ) ) return $this->post->post_title;
		
		return '';
		
	}
	
	/**
	 * Post ID
	 *
	 * @return string|int Post ID
	 */
	
	public function id() {
		
		return $this->post->ID;
		
	}
	
	/**
	 * Post Permalink
	 *
	 * @return string Post Permalink
	 */
	
	public function link() {

		return get_permalink( $this->post->ID );
		
	}
	
	/**
	 * Post Class
	 *
	 * @return string Post classes
	 */
	
	public function postClass( $class = '' ) {

		$class_array = get_post_class($class, $this->post->ID);

		if ( is_array( $class_array ) ) {
			
			return implode(' ', $class_array);
			
		}
		
		return $class_array;
	}

	/**
	 * Post Parent
	 *
	 * @return string Post parent title
	 */

	public function postParent() {
		
		if ( $this->post->post_parent != 0 ) {
			
			$parent = get_post( $this->post->post_parent );
			
			return $parent->post_title;
			
		}
		
		return '';
		
	}
	
	/**
	 * Post Author
	 *
	 * @return object Shortcode Mastery Twig User object
	 */
	
	public function author() {
		
		return new Shortcode_Mastery_Twig_User( $this->post->post_author );
		
	}
	
	/**
	 * Post Title
	 *
	 * @return string Post Title
	 */
	
	public function title() {
		
		return apply_filters( 'the_title', $this->post->post_title, $this->post->ID );
		
	}
	
	/**
	 * Post Content
	 *
	 * @return string Post Content
	 */
	
	public function content() {
		
		return str_replace( [ "[" , "]" ] , [ "&#91;" , "&#93;" ] , $this->post->post_content );
		
	}
	
	/**
	 * Post Excerpt
	 *
	 * @return string Post Excerpt
	 */
	
	public function excerpt() {
		
		return $this->post->post_excerpt;
		
	}
	
	/**
	 * Post Status
	 *
	 * @return string Post Status
	 */
	
	public function status() {
		
		return $this->post->post_status;
		
	}
	
	/**
	 * Post Name
	 *
	 * @return string Post Name
	 */
	
	public function name() {
		
		return $this->post->post_name;
		
	}
	
	/**
	 * Post Modified
	 *
	 * @return string Post Modified
	 */
	
	public function modified() {
		
		return $this->post->post_modified;
		
	}
	
	/**
	 * Post Type
	 *
	 * @return string Post Type
	 */
	 
	public function type() {
		
		return $this->post->post_type;
		
	}
	
	/**
	 * Post Categories
	 *
	 * @return array Post Categories
	 */
	
	public function categories() {
		
		return $this->terms( 'category' );
		
	}
	
	/**
	 * Post Tags
	 *
	 * @return array Post Tags
	 */
	
	public function tags() {
		
		return $this->terms( 'tag' );
		
	}
	
	/**
	 * Post Terms
	 *
	 * @param string $tax Taxonomy
	 * @param bool $merge Merge output
	 * @return array Post Terms
	 */
	
	public function terms( $tax = '', $merge = true ) {
		
		$taxonomies = array();
		
		if ( is_array( $tax ) ) $taxonomies = $tax;
		
		if ( is_string( $tax ) ) {
			
			if ( in_array( $tax, array( 'all', 'any', '' ) ) ) {
				
				$taxonomies = get_object_taxonomies( $this->post->post_type );
				
			} else {
				
				$taxonomies = array( $tax );
				
			}
		}

		$term_class_objects = array();

		foreach ( $taxonomies as $taxonomy ) {
			
			if ( in_array( $taxonomy, array( 'tag', 'tags' ) ) ) {
				
				$taxonomy = 'post_tag';
				
			}
			
			if ( $taxonomy == 'categories' ) {
				
				$taxonomy = 'category';
			}

			$terms = wp_get_post_terms( $this->post->ID, $taxonomy );

			if ( is_wp_error( $terms ) ) {

				print_r( 'WP_Error: '.$terms->get_error_message() );

				return $term_class_objects;
			}
				
			foreach( $terms as $k => $term ) {
				
				$terms[ $k ] = $term->name;
				
			}
			
			if ( $merge && is_array( $terms ) ) {
								
				$term_class_objects = array_merge( $term_class_objects, $terms );
				
			} else if ( count( $terms ) ) {
								
				$term_class_objects[$taxonomy] = $terms;
				
			}
			
		}
								
		return $term_class_objects;
	}
	
	/**
	 * Post Comment Count
	 *
	 * @return int Post Comment Count
	 */
	
	public function comment_count() {
		
		return get_comments_number( $this->post->ID );
		
	}
	
	/**
	 * Post Comment Count
	 *
	 * @return array Post Children
	 */
	
	public function children( $post_type = 'any', $childPostClass = false ) {
		
		if ( $childPostClass === false ) {
			
			$childPostClass = 'Shortcode_Mastery_Twig_Post';
			
		}
		
		if ( $post_type == 'parent' ) {
			
			$post_type = $this->post->post_type;
			
		}
		
		if ( is_array($post_type) ) {
			
			$post_type = implode('&post_type[]=', $post_type);
			
		}
		
		$query = 'post_parent='.$this->post->ID.'&post_type[]='.$post_type.'&numberposts=-1&orderby=menu_order title&order=ASC&post_status[]=publish';
		
		if ( $this->post->post_status == 'publish' ) {
			
			$query .= '&post_status[]=inherit';
			
		}
		
		$children = get_children($query);
				
		foreach ( $children as &$child ) {
			
			$child = new $childPostClass($child);
						
		}
		
		$children = array_values($children);
				
		return $children;
	}
	
	/**
	 * Post Meta Field
	 *
	 * @param string Field Name
	 * @return array Post Meta Fields
	 */
	
	public function meta( $field_name ) {
		
		$value = null;

		if ( $value === null ) {
			
			$value = get_post_meta( $this->post->ID, $field_name );
			
			if ( is_array( $value ) && count( $value ) == 1 ) {
				
				//$value = $value[0];
				
			}
			
			if ( is_array( $value ) && count( $value ) == 0 ) {
				
				$value = null;
				
			}
		}
		
		return $value;
	}
	
	/**
	 * Post Date
	 *
	 * @param string Date Format string
	 * @return string Post Date
	 */	
	
	public function date( $date_format = '' ) {
		
		$df = $date_format ? $date_format : get_option('date_format');
		
		$the_date = (string) mysql2date($df, $this->post->post_date);
		
		return apply_filters('get_the_date', $the_date, $df);
	}
	
	/**
	 * Post Time
	 *
	 * @param string Time Format string
	 * @return string Post Time
	 */

	public function time( $time_format = '' ) {
		
		$tf = $time_format ? $time_format : get_option('time_format');
		
		$the_time = (string) mysql2date($tf, $this->post->post_date);
	 	
		return apply_filters('get_the_time', $the_time, $tf);
	}
	
	/**
	 * Post Thumbnail
	 *
	 * @return object Shortcode Mastery Twig Image object
	 */
	
	public function thumbnail() {
		
		$tid = get_post_thumbnail_id( $this->post->ID );
		
		if ( $tid ) {
			
			return new Shortcode_Mastery_Twig_Image( $tid );
			
		}	
		
	}
	
	/**
	 * Edit weather
	 *
	 * @return bool Yes or No
	 */
	
	public function can_edit() {
		
		if ( !function_exists( 'current_user_can' ) ) {
			
			return false;
			
		}
		
		if ( current_user_can( 'edit_post', $this->post->ID ) ) {
			
			return true;
			
		}
		
		return false;
	}
	
}