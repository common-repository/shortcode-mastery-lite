<?php
/**
 * Shortcode Mastery Twig Cache
 *
 * @class   Shortcode_Mastery_Twig_Cache
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 */

class Shortcode_Mastery_Twig_Cache extends Twig_Cache_Filesystem {
    
    public $directory;
    public $shortcode;
    public $param;

    /**
     * Constructor.
     *
     * @param string        $directory
     * @param string        $shortcode
     */
    public function __construct( $directory, $shortcode, $param ) {
        
        $this->directory = $directory;
        $this->shortcode = $shortcode;
        $this->param = $param;

        parent::__construct($this->directory);
    }

    /**
     * {@inheritdoc}
     */
    public function generateKey($name, $className) {
        
        $parts = explode( '.', $name );

        unset($parts[0]);

        $paramName = implode( '_', $parts );

        return $this->directory.'/'.$this->shortcode.'/twig/'.$paramName.'.php';
    }

}