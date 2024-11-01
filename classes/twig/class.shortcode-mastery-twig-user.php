<?php
/**
 * Shortcode Mastery Twig User
 *
 * @class   Shortcode_Mastery_Twig_User
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 */

class Shortcode_Mastery_Twig_User {
	
	/**
	 * User data
	 *
	 * @access protected
	 * @var object
	 */
		
	protected $data;
	
	/**
	 * Constructor
	 *
	 * @param int $author Author ID
	 */
	
	public function __construct( $author ) {
				
		$this->data = get_userdata( $author )->data;
						
	}
	
	/**
	 * Print Class
	 *
	 * @return string Image source
	 */
    
	public function __toString() {
		
		return $this->data->display_name;
		
	}
	
	/**
	 * Author Name
	 *
	 * @return string Author Name
	 */
	
	public function name() {
		
		return $this->data->display_name;
		
	}
	
	/**
	 * Author Nicename
	 *
	 * @return string Author Nicename
	 */
	
	public function nicename() {
		
		return $this->data->user_nicename;
		
	}
	
	/**
	 * Author Email
	 *
	 * @return string Author Email
	 */
	
	public function email() {
		
		return $this->data->user_email;
		
	}
	
	/**
	 * Author ID
	 *
	 * @return string Author ID
	 */
	
	public function id() {
		
		return $this->data->ID;
	}
}