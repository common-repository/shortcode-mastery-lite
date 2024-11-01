<?php
/**
 * Shortcode Mastery Twig Loader
 *
 * @class   Shortcode_Mastery_Twig_Loader
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 */

class Shortcode_Mastery_Twig_Loader implements Twig_LoaderInterface, Twig_ExistsLoaderInterface, Twig_SourceContextLoaderInterface {
	
    public function getSource( $name ) {
	    
        return $name;
        
    }
    
	/**
	 * @since 1.0.1
	 */   

    public function getSourceContext( $name ) {
	    
	    $fullname = $name;
	    	    	    
		$name = explode( '.', $name );
		
		$values = Shortcode_Mastery::getValue( $name[0], $name[1] );
							    
	    switch ( $name[1] ) {
		    
		    case 'embed_scripts':
		    case 'embed_styles':
		    case 'params':
		    case 'arguments':
		    $values = $values[ $name[2] ];
		    if ( is_array( $values ) ) $values = $values['value'];  
		    break;
		    default: break;
		    
	    }
	    	    	    
		$values = str_replace( "\'", "'", $values );
		$values = preg_replace('/{[(.*?)]}/', '{{ ${1} }}', $values );
	    	    	    	    	    	    	    
	    return new Twig_Source( $values, $fullname );
	            
    }

    public function exists( $name ) {
	    
        return true;
        
    }

    public function getCacheKey( $name ) {
	    
        return $name;
        
    }
    
    /**
	 * @since 1.0.1
	 */

    public function isFresh( $name, $time ) {
	    
	    $name = explode( '.', $name );
	    
	    $name = $name[0];
	    
	    $name = explode( '_', $name );
	    
	    $name = $name[1];
	    	    
		$id = get_page_by_path( $name, OBJECT, 'shortcode-mastery' );
			    
	    $lastModified = get_post_modified_time( 'U', true, $id->ID );
	    	    	    
        return $lastModified <= $time;
        
    }
	
}