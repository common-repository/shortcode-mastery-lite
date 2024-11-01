<?php
/**
 * Shortcode Mastery Twig Image
 *
 * @class   Shortcode_Mastery_Twig_Image
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 */

class Shortcode_Mastery_Twig_Image {
	
	/**
	 * Image ID
	 *
	 * @access protected
	 * @var int
	 */
	
	protected $id;
	
	/**
	 * Constructor
	 *
	 * @param int $id Image ID
	 */
	
	public function __construct( $id ) {
		
		$this->id = $id;
		
	}
	
	/**
	 * Print Class
	 *
	 * @return string Image source
	 */
    
	public function __toString() {
		
		return $this->src();
		
	}
	
	/**
	 * Image source
	 *
	 * @param string $size Thumbnail Size
	 * @return string Image source
	 */

	public function src( $size = 'full' ) {
		
		$src = wp_get_attachment_image_src( $this->id, $size );
		
		$src = $src[0];

		return $src;
		
	}
	
	/**
	 * Image permalink
	 *
	 * @return string Permalink
	 */
	
	public function link() {

		return get_permalink($this->id);
		
	}
	
	/**
	 * Image alt
	 *
	 * @return string Image Alt
	 */
	
	public function alt() {
		
		$alt = trim( strip_tags( get_post_meta( $this->id, '_wp_attachment_image_alt', true ) ) );
		
		return $alt;
	}
	
	/**
	 * Image height
	 *
	 * @return string|int Image Height
	 */
	
	public function height( $size = 'full' ) {
		
		$src = wp_get_attachment_image_src( $this->id, $size );
		
		$src = $src[2];

		return $src;
	}
	
	/**
	 * Image width
	 *
	 * @return string|int Image Width
	 */
	
	public function width( $size = 'full' ) {
		
		$src = wp_get_attachment_image_src( $this->id, $size );
		
		$src = $src[1];

		return $src;
	}
}