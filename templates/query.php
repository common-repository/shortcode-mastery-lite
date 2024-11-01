<?php
/**
 * Query block
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) die; ?>
<div class="sm-flex<?php echo $hiddenClass; ?>" id="sm-query-block">

	<div class="sm-block" :class="collapse">
		
		<button class="sm-collapse-button" type="button" @click="onCollapse" v-html="sign"></button>
		
		<div class="sm-flex-full">
					
			<h3><?php echo __( 'Query', 'shortcode-mastery' ); ?><a href="javascript:void(0);" data-placement="right" rel="tooltip" class="sm-info" title="<?php _e( 'Define repeatable content in Loop after', 'shortcode-mastery' ); ?>"><span class="icon-info"></span></a></h3>
									
			<sm-query-builder 
			component-id="query-builder" 
			@changed="onQueryChange" 
			@check="onCheckMethod" 
			:qb="<?php echo Shortcode_Mastery::getDefault( 'groups' ); ?>" 
			:params="paramsRows" 
			value-placeholder="<?php _e( 'Value of argument', 'shortcode-mastery' ); ?>" 
			button-title="<?php _e( 'value', 'shortcode-mastery' ); ?>" 
			back-title="<?php _e( 'Step back', 'shortcode-mastery' ); ?>"
			>
			</sm-query-builder>
				
			<h4 class="sm-bt sm-pt" v-if="rows.length > 0"><?php echo __( 'Query arguments list:', 'shortcode-mastery' ); ?></h4>	
							
			<sm-dic 
			component-id="query-dic" 
			@rows="onRows"
			@edit="editAtts" 
			@delete="deleteAtts" 
			meta-type="argument" 
			:rows="rows" 
			name-placeholder="<?php _e( 'Name of argument', 'shortcode-mastery' ); ?>" 
			value-placeholder="<?php _e( 'Value of argument', 'shortcode-mastery' ); ?>" 
			button-title="<?php _e( 'argument', 'shortcode-mastery' ); ?>" 
			:need-value="true" 
			:has-controls="false" 
			:def="<?php echo Shortcode_Mastery::getDefault( 'arguments' ); ?>"
			>
			</sm-dic>
		
		</div>
		
	</div>
		
</div>