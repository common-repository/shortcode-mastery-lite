<?php
/**
 * Query Main block
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) die; ?>
<div class="sm-flex<?php echo $hiddenClass; ?>" id="sm-query-main-block">
		
	<div class="sm-flex sm-block" :class="collapse">
		
		<button class="sm-collapse-button" type="button" @click="onCollapse" v-html="sign"></button>
				
		<div class="sm-flex-full">
						
			<h3><?php echo __( 'Loop', 'shortcode-mastery' ); ?><a href="javascript:void(0);" data-placement="right" rel="tooltip" class="sm-info" title="<?php _e( 'Renders only if Query presented and {@ LOOP @} added in Markup', 'shortcode-mastery' ); ?>"><span class="icon-info"></span></a></h3>
	
			<p><?php _e( 'Edit loop section that repeats every loop. Use <strong>text</strong>, <strong>html</strong>, <strong>parameters</strong>, <strong><span v-html="\'&#123;&#123;\'"></span> post.post_property <span v-html="\'&#125;&#125;\'"></span></strong>, <strong><span v-html="\'&#123;&#123;\'"></span> fn( "php_function_name", "param_1", "param_2", ... ) <span v-html="\'&#125;&#125;\'"></span></strong> or php global objects <strong><span v-html="\'&#123;&#123;\'"></span> GLOBALS|COOKIE|POST|GET... <span v-html="\'&#125;&#125;\'"></span></strong>.', 'shortcode-mastery' ); ?></p>
			
			<p><?php _e( 'Use predefined template buttons to access <strong>loop object data</strong>:', 'shortcode-mastery' ); ?></p>
			
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
				
			<strong><?php echo __( 'Loop Post:', 'shortcode-mastery' ); ?></strong>
				
			<?php
						
			foreach ( Shortcode_Mastery::getDefault( 'post' ) as $k=>$tag ) {
				
				echo '<sm-method-button method="'.$k.'" @method="addMethod" text="'.$tag.'"></sm-method-button>';
			}
			
			?>
			
			<br/>
			
			<strong><?php echo __( 'Loop Post Thumbnail:', 'shortcode-mastery' ); ?></strong>
				
			<?php
						
			foreach ( Shortcode_Mastery::getDefault( 'post_thumbnail' ) as $k=>$tag ) {
				
				echo '<sm-method-button method="'.$k.'" @method="addMethod" text="'.$tag.'"></sm-method-button>';
			}
			
			?>
			
			<br/>
			
			<strong><?php echo __( 'Loop Post Author:', 'shortcode-mastery' ); ?></strong>
				
			<?php
						
			foreach ( Shortcode_Mastery::getDefault( 'post_author' ) as $k=>$tag ) {
				
				echo '<sm-method-button method="'.$k.'" @method="addMethod" text="'.$tag.'"></sm-method-button>';
			}
			
			?>
			
			<br/>
			
			<strong><?php echo __( 'Loop Post Terms:', 'shortcode-mastery' ); ?></strong>
				
			<?php
						
			foreach ( Shortcode_Mastery::getDefault( 'post_terms' ) as $k=>$tag ) {
				
				echo '<sm-method-button method="'.$k.'" @method="addMethod" text="'.$tag.'"></sm-method-button>';
			}
			
			?>
			
			<br/>
			
			<strong><?php echo __( 'Loop counts:', 'shortcode-mastery' ); ?></strong>
			
			<?php
			
			foreach ( Shortcode_Mastery::getDefault( 'loop' ) as $k=>$tag ) {
				
				echo '<sm-method-button method="'.$k.'" @method="addMethod" class-name="sm-loop-button" text="'.$tag.'"></sm-method-button>';
			}
			
			?>
			
			<br/>
			
			<strong><?php echo __( 'Shortcode Content:', 'shortcode-mastery' ); ?></strong>
		
			<sm-method-button :content="true" method="CONTENT" @method="addMethod" class="last" text="CONTENT"></sm-method-button>
			<a href="javascript:void(0);" rel="tooltip" data-placement="right" class="sm-info" title="<?php _e( 'Insert shortcode content', 'shortcode-mastery' ); ?>"><span class="icon-info"></span></a>
			
			</div>
			
			<div class="sm-txt-part">

				<sm-editor def="<?php echo esc_textarea( Shortcode_Mastery::getDefault( 'main_loop' ) ); ?>" v-model="main" @init="onInit"></sm-editor>
		
				<textarea style="visibility:hidden;position:absolute;" name="main_loop" v-model="main"></textarea>		
				
			</div>
		
		</div>
		
	</div>
	
</div>