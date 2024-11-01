<?php
/**
 * Markup block
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) die; ?>
<div class="sm-flex<?php echo $hiddenClass; ?>" id="sm-main-block">
		
	<div class="sm-flex sm-block" :class="collapse">
		
		<button class="sm-collapse-button" type="button" @click="onCollapse" v-html="sign"></button>
				
		<div class="sm-flex-full">
						
			<h3><?php echo __( 'Markup', 'shortcode-mastery' ); ?><a href="javascript:void(0);" data-placement="right" rel="tooltip" class="sm-info" title="<?php _e( 'Main section - your shortcode body', 'shortcode-mastery' ); ?>"><span class="icon-info"></span></a></h3>
	
			<p><?php _e( 'Edit shortcode markup. Use <strong>text</strong>, <strong>html</strong>, <strong>parameters</strong>, <strong><span v-html="\'&#123;&#123;\'"></span> fn( "php_function_name", "param_1", "param_2", ... ) <span v-html="\'&#125;&#125;\'"></span></strong> or php global objects <strong><span v-html="\'&#123;&#123;\'"></span> GLOBALS|COOKIE|POST|GET... <span v-html="\'&#125;&#125;\'"></span></strong>.', 'shortcode-mastery' ); ?></p>
			
			<p><?php _e( 'Use predefined template buttons to access <strong>current post or page data</strong> from where the shortcode was called:', 'shortcode-mastery' ); ?></p>
			
			<div class="methods-panel">
				
				<strong v-if="rows.length"><?php echo __( 'Parameters:', 'shortcode-mastery' ); ?></strong>
				
				<sm-method-button 
				v-if="rows.length" 
				v-for="(row, index) in rows" 
				:key="index" 
				:method="row.name" 
				@method="addMethod" 
				class-name="sm-params-button" 
				:text="row.name"
				></sm-method-button>			
				
				<br v-if="rows.length" />
								
				<strong><?php echo __( 'Current Post:', 'shortcode-mastery' ); ?></strong>
					
				<?php
							
				foreach ( Shortcode_Mastery::getDefault( 'post' ) as $k=>$tag ) {
					
					echo '<sm-method-button method="'.$k.'" @method="addMethod" text="'.$tag.'"></sm-method-button>';
				}
				
				?>
				
				<br/>
				
				<strong><?php echo __( 'Current Post Thumbnail:', 'shortcode-mastery' ); ?></strong>
					
				<?php
							
				foreach ( Shortcode_Mastery::getDefault( 'post_thumbnail' ) as $k=>$tag ) {
					
					echo '<sm-method-button method="'.$k.'" @method="addMethod" text="'.$tag.'"></sm-method-button>';
				}
				
				?>
				
				<br/>
				
				<strong><?php echo __( 'Current Post Author:', 'shortcode-mastery' ); ?></strong>
					
				<?php
							
				foreach ( Shortcode_Mastery::getDefault( 'post_author' ) as $k=>$tag ) {
					
					echo '<sm-method-button method="'.$k.'" @method="addMethod" text="'.$tag.'"></sm-method-button>';
				}
				
				?>
				
				<br/>
				
				<strong><?php echo __( 'Current Post Terms:', 'shortcode-mastery' ); ?></strong>
					
				<?php
							
				foreach ( Shortcode_Mastery::getDefault( 'post_terms' ) as $k=>$tag ) {
					
					echo '<sm-method-button method="'.$k.'" @method="addMethod" text="'.$tag.'"></sm-method-button>';
				}
				
				?>
				
				<br/>
											
				<strong><?php echo __( 'Your Custom Loop:', 'shortcode-mastery' ); ?></strong>
				
				<sm-method-button :content="true" method="LOOP" @method="addMethod" class="last" text="LOOP"></sm-method-button>
				<a href="javascript:void(0);" data-placement="right" rel="tooltip" class="sm-info" title="<?php _e( 'Insert repeatable content from Loop', 'shortcode-mastery' ); ?>"><span class="icon-info"></span></a>
	
				<br/>
				
				<strong><?php echo __( 'Shortcode Content:', 'shortcode-mastery' ); ?></strong>
				
				<sm-method-button :content="true" method="CONTENT" @method="addMethod" class="last" text="CONTENT"></sm-method-button>
				<a href="javascript:void(0);" rel="tooltip" data-placement="right" class="sm-info" title="<?php _e( 'Insert shortcode content', 'shortcode-mastery' ); ?>"><span class="icon-info"></span></a>
			
			</div>
			
			<div class="sm-txt-part">
				
				<sm-editor def="<?php echo esc_textarea( Shortcode_Mastery::getDefault( 'main_content' ) ); ?>" v-model="main_content" @init="onInit"></sm-editor>
		
				<textarea style="visibility:hidden;position:absolute;" name="main_content" v-model="main_content"></textarea>		
				
			</div>
		
		</div>
		
	</div>
	
</div>