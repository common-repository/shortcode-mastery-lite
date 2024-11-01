<?php
/**
 * Scripts block
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) die; ?>
<div class="sm-flex<?php echo $hiddenClass; ?>" id="sm-scripts-block">
		
	<div class="sm-flex sm-block" :class="collapse">
		
		<button class="sm-collapse-button" type="button" @click="onCollapse" v-html="sign"></button>
				
		<div class="sm-flex-full">
						
			<h3><?php echo __( 'Scripts', 'shortcode-mastery' ); ?><a href="javascript:void(0);" data-placement="right" rel="tooltip" class="sm-info" title="<?php _e( 'JS for your shortcode', 'shortcode-mastery' ); ?>"><span class="icon-info"></span></a></h3>
						
			<p><?php _e( 'Whether to enqueue the scripts:', 'shortcode-mastery' ); ?></p>
			
			<?php $strings = include( SHORTCODE_MASTERY_DIR . 'includes/strings.php' ); ?>
			
			<p v-if="paramsRows.length" class="description"><?php echo $strings['queryDesc']; ?>
				<span class="sm-inline-attr sm-inline-attr-query" v-for="(row, index) in paramsRows" :key="index"><strong><span v-html="'&#123;&#123;'"></span> <span v-text="row.name"></span> <span v-html="'&#125;&#125;'"></span></strong></span>
			</p>
			<p v-if="paramsRows.length" class="description sm-pb10"><?php echo $strings['queryDescCP']; ?></p>		
			<sm-dic 
			component-id="scripts-dic" 
			:radios="<?php echo esc_html( json_encode( Shortcode_Mastery::getDefault( 'script_position' ) ) ); ?>" 
			:has-radios="true" 
			radio-desc="<?php _e( 'Where to enqueue:', 'shortcode-mastery' ); ?>" 
			@rows="onRows" 
			meta-type="embed_script" 
			:rows="rows" 
			name-placeholder="<?php _e( 'Name of script', 'shortcode-mastery' ); ?>" 
			value-placeholder="<?php _e( 'URL of script', 'shortcode-mastery' ); ?>" 
			button-title="<?php _e( 'script', 'shortcode-mastery' ); ?>" 
			:need-value="true" 
			:def="<?php echo Shortcode_Mastery::getDefault( 'embed_scripts' ); ?>"
			>
			</sm-dic>

			<p class="sm-bt sm-pt sm-mt"><?php _e( 'Edit shortcode inline scripts. Use <strong>javascript</strong>, <strong><span v-html="\'&#123;&#123;\'"></span> parameter_name <span v-html="\'&#125;&#125;\'"></span></strong>, <strong><span v-html="\'&#123;&#123;\'"></span> fn( "php_function_name", "param_1", "param_2", ... ) <span v-html="\'&#125;&#125;\'"></span></strong>.', 'shortcode-mastery' ); ?></p>
			
			<div class="methods-panel">
				
				<strong v-if="paramsRows.length"><?php echo __( 'Parameters:', 'shortcode-mastery' ); ?></strong>
				
				<sm-method-button 
				v-if="paramsRows.length" 
				v-for="(row, index) in paramsRows" 
				:key="index" 
				:method="row.name" 
				@method="addMethod" 
				class-name="sm-params-button" 
				:text="row.name"
				></sm-method-button>			
				
				<br v-if="paramsRows.length" />
			
			</div>
			
			<div class="sm-txt-part">

				<sm-editor def="<?php echo esc_textarea( Shortcode_Mastery::getDefault( 'scripts' ) ); ?>" v-model="scripts" @init="onInit"></sm-editor>
		
				<textarea style="visibility:hidden;position:absolute;" name="scripts" v-model="scripts"></textarea>		
				
			</div>

			<sm-checkbox
				id="sm-write-scripts" 
				name="write_scripts" 
				header="<?php _e('Write inline scripts to file?','shortcode-mastery'); ?>" 
				title="<?php _e( 'Enqueue JS file instead', 'shortcode-mastery' ); ?>" 
				:def="<?php echo Shortcode_Mastery::getDefault( 'write_scripts' ) ? Shortcode_Mastery::getDefault( 'write_scripts' ) : 0; ?>"
			></sm-checkbox>
		
		</div>
		
	</div>
	
</div>