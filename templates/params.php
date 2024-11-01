<?php
/**
 * Parameters block
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) die; ?>
<div class="sm-block sm-flex-almost-half<?php echo $hiddenClass; ?>" :class="collapse" id="sm-parameters-block">
	
	<button class="sm-collapse-button" type="button" @click="onCollapse" v-html="sign"></button>
	
	<h3><?php echo __( 'Parameters', 'shortcode-mastery' ); ?><a href="javascript:void(0);" data-placement="right" rel="tooltip" class="sm-info" title="<?php _e( 'Shortcode parameters', 'shortcode-mastery' ); ?>"><span class="icon-info"></span></a></h3>
	
	<p><?php echo __( 'Add custom parameters to your brand new shortcode. ', 'shortcode-mastery' ); ?></p>
	
	<p><?php //_e( 'Use <strong>text</strong>, <strong><span v-html="\'&#123;&#123;\'"></span> fn( "php_function_name", "param_1", "param_2", ... ) <span v-html="\'&#125;&#125;\'"></span></strong> or php global objects <strong><span v-html="\'&#123;&#123;\'"></span> GLOBALS|COOKIE|POST|GET... <span v-html="\'&#125;&#125;\'"></span></strong>.', 'shortcode-mastery' ); ?></p>
	
	<?php $chkTooltip = __( 'If parameter is required and value is empty, <br>the markup will not be rendered', 'shortcode-mastery' ); ?>
	
	<sm-dic 
	component-id="params-dic" 
	@changed="onChange" 
	@rows="onRows"
	:rows="rows" 
	meta-type="param" 
	:has-checkbox="true" 
	checkbox-desc="<?php _e( 'Required?', 'shortcode-mastery' ); ?>" 
	checkbox-tooltip="<?php echo esc_html( $chkTooltip ); ?>" 
	:radios="<?php echo esc_html( json_encode( Shortcode_Mastery::getDefault( 'standard_types' ) ) ); ?>" 
	radio-desc="<?php _e( 'Parameter type:', 'shortcode-mastery' ); ?>" 
	:has-radios="true" 
	:booleans="true" 
	:wpbackery="wpbackery" 
	:wpbackery-types="<?php echo esc_html( json_encode( Shortcode_Mastery::getDefault( 'wpbackery_types' ) ) ); ?>" 
	:elementor="elementor" 
	:elementor-types="<?php echo esc_html( json_encode( Shortcode_Mastery::getDefault( 'elementor_types' ) ) ); ?>" 
	:has-extended-controls="true" 
	name-placeholder="<?php _e( 'Parameter name', 'shortcode-mastery' ); ?>" 
	value-placeholder="<?php _e( 'Default value', 'shortcode-mastery' ); ?>" 
	title-placeholder="<?php _e( 'Parameter title (for builders)', 'shortcode-mastery' ); ?>" 
	desc-placeholder="<?php _e( 'Parameter description (for builders)', 'shortcode-mastery' ); ?>" 
	options-placeholder="<?php _e( 'Additional options (for builders, use JSON)', 'shortcode-mastery' ); ?>" 
	button-title="<?php _e( 'parameter', 'shortcode-mastery' ); ?>" 
	:def="<?php echo Shortcode_Mastery::getDefault( 'params' ); ?>"
	>
	</sm-dic>

</div>

