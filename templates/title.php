<?php
/**
 * Title block
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) die; ?>
<div class="sm-block sm-flex-almost-half" :class="collapse" id="sm-title-block">
	
	<button class="sm-collapse-button" type="button" @click="onCollapse" v-html="sign"></button>
	
	<h3><?php echo __( 'Settings', 'shortcode-mastery' ); ?><a href="javascript:void(0);" data-placement="right" rel="tooltip" class="sm-info" title="<?php _e( 'Basic shortcode customization', 'shortcode-mastery' ); ?>"><span class="icon-info"></span></a></h3>
	
	<?php
	
	$icon_src = SHORTCODE_MASTERY_URL . 'images/sm-white.png';
	
	$icon_src_2x = SHORTCODE_MASTERY_URL . 'images/sm-white@2x.png';
	
	if ( Shortcode_Mastery::getDefault( 'icon_source' ) ) {
		
		$icon = explode('|', Shortcode_Mastery::getDefault( 'icon_source' ) );
				
		$icon_src = $icon_src_2x = $icon[0];
		
		if ( intval( $icon_src ) ) {
    		
    		$icon_src = wp_get_attachment_image_src( $icon_src, 'full' );
    		
    		$icon_src = $icon_src_2x = $icon_src[0];
    		
    	}
		
	} else {
				
		if ( Shortcode_Mastery::getDefault( 'ID' ) ) {
		
			$icon = get_the_post_thumbnail_url( Shortcode_Mastery::getDefault( 'ID' ), 'thumbnail' );
				    		
			if ( $icon ) $icon_src_2x = $icon_src = $icon;
		
		}
		
	}

	$icon_src_2x = str_replace( array('http:', 'https:'), '', $icon_src_2x);
	$icon_src = str_replace( array('http:', 'https:'), '', $icon_src);
	$icon_src = set_url_scheme( $icon_src );
	$icon_src_2x = set_url_scheme( $icon_src_2x );

	?>
	<div class="sm-flex sm-flex-align-center sm-flex-justify-start sm-icon-title-holder">
	
		<?php if ( Shortcode_Mastery::getDefault( 'icon_source' ) ) echo '<a class="sm-remove-icon" title="' . __( 'Remove Icon', 'shortcode-mastery' ) . '" href="javascript:void(0);"><i class="icon-cancel"></i></a>'; ?>
		<img class="default sm-def-icon" src="<?php echo esc_url( $icon_src ); ?>" srcset="<?php echo esc_url( $icon_src ); ?>, <?php echo esc_url( $icon_src_2x ); ?> 2x" />
		
		<input type="text" id="sm-shortcode-title" class="sm-large" name="shortcode_title" placeholder="<?php _e( 'Shortcode name', 'shortcode-mastery' ); ?>" value="<?php echo Shortcode_Mastery::getDefault( 'title' ); ?>">
	
	</div>
	<p class="description"><?php _e( 'Title is required and should contains only letters, numbers, dashes or underscores.', 'shortcode-mastery' ); ?></p>
		
	<textarea class="sm-tinymce-field-input sm-mt10 sm-mb0" placeholder="<?php _e( 'Shortcode description', 'shortcode-mastery' ); ?>" name="shortcode_excerpt"><?php echo Shortcode_Mastery::getDefault( 'excerpt' ); ?></textarea>
	<p class="description sm-mb10"><?php _e( 'Optional short description for your brand new shortcode. HTML allowed.', 'shortcode-mastery' ); ?></p>
	
	<?php $button_id = '';
	if ( current_user_can( $this->menu_permission ) ) $button_id = 'id="upload_icon_button" ';
	?>
	
	<button <?php echo $button_id; ?>class="sm-button sm-edit-button" type="button"><span class="icon-picture"></span><?php _e( 'Upload icon', 'shortcode-mastery' ); ?></button>
	<span class="description"><?php _e( 'Should be 128x128', 'shortcode-mastery' ); ?></span>
	<input type="hidden" name="icon_source" id="sm-icon-url" value="<?php echo Shortcode_Mastery::getDefault( 'icon_source' ); ?>">

	<sm-checkbox 
		id="sm-wpbackery-checkbox"  
		name="shortcode_integration_wpbakery" 
		@changed="onWPBackery" 
		header="<?php _e('Integrate with WPBakery Page Builder?','shortcode-mastery'); ?>" 
		title="<?php _e( 'Map shortcode with WPBakery Page Builder', 'shortcode-mastery' ); ?>" 
		:def="<?php echo Shortcode_Mastery::getDefault( 'shortcode_integration_wpbakery' ) ? Shortcode_Mastery::getDefault( 'shortcode_integration_wpbakery' ) : 0; ?>"
	></sm-checkbox>

	<sm-checkbox 
		id="sm-elementor-checkbox"  
		name="shortcode_integration_elementor" 
		@changed="onElementor" 
		header="<?php _e('Integrate with Elementor Page Builder?','shortcode-mastery'); ?>" 
		title="<?php _e( 'Map shortcode with Elementor Page Builder', 'shortcode-mastery' ); ?>" 
		:def="<?php echo Shortcode_Mastery::getDefault( 'shortcode_integration_elementor' ) ? Shortcode_Mastery::getDefault( 'shortcode_integration_elementor' ) : 0; ?>"
	></sm-checkbox>
	
	<p class="sm-bt sm-pt sm-mt"><?php _e( 'Shortcode code:', 'shortcode-mastery' ); ?></p>
	
	<p><strong id="sm-shortcode-code">[<span id="sm-shortcode-name"><?php echo Shortcode_Mastery::getDefault( 'code' ); ?></span><span v-html="shortcode_codes"></span>]</strong></p>
	
</div>