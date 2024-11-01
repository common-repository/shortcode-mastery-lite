<?php
/**
 * Shortcode Mastery Twig Extension
 *
 * @class   Shortcode_Mastery_Twig_Extension
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 */

class Shortcode_Mastery_Twig_Extension extends Twig_Extension {
	
	/**
	 * Register functions
	 */		
    
    public function getFunctions() {
        
        return array();
        
    }

	/**
	 * Register filters
	 */	
	     
    public function getFilters() {
	    
	    return array(
		    
		    new Twig_SimpleFilter( 'wpautop', 'wpautop' ),
		    new Twig_SimpleFilter( 'sanitize', 'sanitize_title' ),
			new Twig_SimpleFilter( 'shortcodes', 'do_shortcode' ),
			new Twig_SimpleFilter( 'esc_url', 'esc_url' ),
			new Twig_SimpleFilter( 'esc_url_raw', 'esc_url_raw' ),
			new Twig_SimpleFilter( 'esc_html', 'esc_html' ),
			new Twig_SimpleFilter( 'esc_textarea', 'esc_textarea' ),
			new Twig_SimpleFilter( 'esc_js', 'esc_js' ),
			new Twig_SimpleFilter( 'esc_sql', 'esc_sql' ),
			new Twig_SimpleFilter( 'esc_attr', 'esc_attr' ),
			
			// PCRE
			
            new Twig_SimpleFilter( 'preg_quote', array( $this, 'quote' ) ),
            new Twig_SimpleFilter( 'preg_match', array( $this, 'match' ) ),
            new Twig_SimpleFilter( 'preg_get', array( $this, 'get' ) ),
            new Twig_SimpleFilter( 'preg_get_all', array( $this, 'getAll' ) ),
            new Twig_SimpleFilter( 'preg_grep', array( $this, 'grep' ) ),
            new Twig_SimpleFilter( 'preg_replace', array( $this, 'replace' ) ),
            new Twig_SimpleFilter( 'preg_filter', array( $this, 'filter' ) ),
            new Twig_SimpleFilter( 'preg_split', array( $this, 'split' ) ),
 
		    
	    );
	    
    }
    
    /**
     * Check that the regex doesn't use the eval modifier
     * 
     * @param string $pattern
     * @throws \LogicException
     */
    protected function assertNoEval($pattern)
    {
        $pos = strrpos($pattern, $pattern[0]);
        $modifiers = substr($pattern, $pos + 1);
        
        if (strpos($modifiers, 'e') !== false) {
            throw new Twig_Error_Runtime("Using the eval modifier for regular expressions is not allowed");
        }
    }
    
	/**
     * Quote regular expression characters.
     * 
     * @param string $value
     * @param string $delimiter
     * @return string
     */
    public function quote($value, $delimiter = '/')
    {
        if (!isset($value)) {
            return null;
        }
        
        return preg_quote($value, $delimiter);
    }
    /**
     * Perform a regular expression match.
     * 
     * @param string $value
     * @param string $pattern
     * @return boolean
     */
    public function match($value, $pattern)
    {
        if (!isset($value)) {
            return null;
        }
        
        return preg_match($pattern, $value);
    }
    /**
     * Perform a regular expression match and return a matched group.
     * 
     * @param string $value
     * @param string $pattern
     * @return string
     */
    public function get($value, $pattern, $group = 0)
    {
        if (!isset($value)) {
            return null;
        }
        
        return preg_match($pattern, $value, $matches) && isset($matches[$group]) ? $matches[$group] : null;
    }
    /**
     * Perform a regular expression match and return the group for all matches.
     * 
     * @param string $value
     * @param string $pattern
     * @return array
     */
    public function getAll($value, $pattern, $group = 0)
    {
        if (!isset($value)) {
            return null;
        }
        
        return preg_match_all($pattern, $value, $matches, PREG_PATTERN_ORDER) && isset($matches[$group])
            ? $matches[$group] : [];
    }
    /**
     * Perform a regular expression match and return an array of entries that match the pattern
     * 
     * @param array  $values
     * @param string $pattern
     * @param string $flags    Optional 'invert' to return entries that do not match the given pattern.
     * @return array
     */
    public function grep($values, $pattern, $flags = '')
    {
        if (!isset($values)) {
            return null;
        }
        
        if (is_string($flags)) {
            $flags = $flags === 'invert' ? PREG_GREP_INVERT : 0;
        }
        
        return preg_grep($pattern, $values, $flags);
    }
    /**
     * Perform a regular expression search and replace.
     * 
     * @param string $value
     * @param string $pattern
     * @param string $replacement
     * @param int    $limit
     * @return string
     */
    public function replace($value, $pattern, $replacement = '', $limit = -1)
    {
        $this->assertNoEval($pattern);
        
        if (!isset($value)) {
            return null;
        }
        
        return preg_replace($pattern, $replacement, $value, $limit);
    }
    /**
     * Perform a regular expression search and replace, returning only matched subjects.
     * 
     * @param string $value
     * @param string $pattern
     * @param string $replacement
     * @param int    $limit
     * @return string
     */
    public function filter($value, $pattern, $replacement = '', $limit = -1)
    {
        $this->assertNoEval($pattern);
        
        if (!isset($value)) {
            return null;
        }
        
        return preg_filter($pattern, $replacement, $value, $limit);
    }
    /**
     * Split text into an array using a regular expression.
     * 
     * @param string $value
     * @param string $pattern
     * @return array
     */
    public function split($value, $pattern)
    {
        if (!isset($value)) {
            return null;
        }
        
        return preg_split($pattern, $value);
    }

}
?>