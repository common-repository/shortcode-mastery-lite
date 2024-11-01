<?php
/**
 * Shortcode Mastery TinyMCE Item
 *
 * @class   Shortcode_Mastery_TinyMCE_Item
 * @package Shortcode_Mastery
 * @version 2.0.0
 *
 */

class Shortcode_Mastery_TinyMCE_Item {
	
	/**
	 * Requested data
	 *
	 * @access protected
	 * @var array
	 */
	
	protected $data;
	
	/**
	 * Constructor
	 *
	 * @param array $data Shortcode data
	 */

	public function __construct( $data ) {
		
		$this->data = $data;
						
	}
	
	/**
     * Render collection item
     */
	
	public function render() {
		
		$values = null;
		
		$inside_content = null;
		
    	$sm_meta = get_post_meta( $this->data->ID );
		
		if ( $sm_meta ) {
									
			$values = Shortcode_Mastery::getMeta( $sm_meta, 'params' );
									
			$values = Shortcode_Mastery::decodeString( $values );
			
			$inside_content = Shortcode_Mastery::getMeta( $sm_meta, 'main_content' );
			
			if ( Shortcode_Mastery::getTwig()->check_inside_content( $inside_content ) ) {
				
				$inside_content = 'false';
				
			} else {
				
				$inside_content = 'true';
				
			}
		 
		}
		
		?>
		
		<tr class="sm-tinymce-item">
			
			<td class="info">
				<div class="sm-title-media">
					<?php $this->render_image(); ?>
					
					<div class="sm-titles">	
						<h3>
							<strong><?php echo $this->data->post_title; ?></strong>
						</h3>
						<?php if ( $this->data->post_excerpt != '' ) { ?>
						<div class="desc">
							<?php echo $this->data->post_excerpt; ?>
						</div>
						<?php } ?>
						<?php if ( $this->data->post_content != '' ) { ?>
						<a href="javascript:void(0);" data-id="<?php echo $this->data->ID; ?>" class="sm-more-details"><?php _e( 'More Details', 'shortcode-mastery' ); ?></a>
						<?php } ?>
					</div>
				</div>								
			</td>

			<td>
				<?php
		    	$params = '';

				if ( $values && is_array( $values ) ) {
					
					foreach( $values as $value ) {
						
						$value = (array) $value;
							
						$params .= ' ' . esc_html( $value['name'] ). '="' . esc_html( $value['value'] ) . '"';						
	
					}
					
				}
				
				$params = str_replace( array( '{[', ']}'), array( '{{', '}}' ), $params );

		    	echo '[sm_' . $this->data->post_name . $params . ']'; ?>
			</td>
			
			<td class="buttons">
				<a href="javascript:void(0);" class="sm-button sm-edit-button sm-quick-insert" data-content="<?php echo $this->data->post_name; ?>" data-id="<?php echo $this->data->ID; ?>" data-single="<?php echo $inside_content; ?>"><i class="icon-plus"></i><?php _e( 'Quick Insert', 'shortcode-mastery' ); ?></a>
				<?php if ( $values || $inside_content != 'true' ) { ?>
				<a href="javascript:void(0);" class="sm-button sm-export-button sm-customize" data-id="<?php echo $this->data->ID; ?>"><i class="icon-pencil"></i><?php _e( 'Customize', 'shortcode-mastery' ); ?></a>
				<?php } ?>
			</td>
					
		</tr>
		<?php if ( $values || $inside_content != 'true' ) { ?>
		<tr class="sm-details sm-details-<?php echo $this->data->ID; ?>" style="display: none;">
			<td colspan="3">
				<div class="sm-tinymce-fields">
					<?php
						$i = 0;
						
						$values = array_merge( array_filter( $values, array( $this, 'get_required_values' ) ), array_filter( $values, array( $this, 'get_values' ) ) );
						
						foreach( $values as $value ) { 
	
							$value = (array) $value;
							
							$placeholder = esc_html( $value['name'] );
							
							if ( $value['value'] != '' ) $placeholder .= ' (' . __( 'default', 'shortcode-mastery') . ': ' . esc_html( $value['value'] ) . ')';
					?>
					<div class="sm-tinymce-field<?php if ( $i == 0 ) echo ' first'; ?>">
						<?php $required = '';
						if ( isset( $value['checkbox'] ) && $value['checkbox'] ) $required = ' required';
						if ( ! isset( $value['radio'] ) || $value['radio'] == 0 ) { ?>
						<input class="sm-tinymce-field-input<?php echo $required; ?>" data-name="<?php echo esc_html( $value['name'] ); ?>" id="field-<?php echo $this->data->ID . '-' . $i; ?>" type="text" placeholder="<?php echo $placeholder; if ( $required ) _e( ' (Required)', 'shortcode-mastery' ); ?>">
						<?php /*if ( $value['value'] != '' ) { ?>
						<label style="margin-left:12px" for="field-<?php echo $this->data->ID . '-' . $i; ?>"><?php echo __( 'Default', 'shortcode-mastery') . ': '; ?><strong><?php echo esc_html( $value['value'] ); ?></strong></label>
						<?php }*/ ?>
						<?php } else { 
							$def_yes = $def_no = '';
							$def = ' (' . __( 'default', 'shortcode-mastery' ) . ')';
							$value['value'] == 'true' ? $def_yes = $def : $def_no = $def; 
						?>
						<select class="sm-tinymce-field-select" data-name="<?php echo esc_html( $value['name'] ); ?>" id="field-<?php echo $this->data->ID . '-' . $i; ?>">
							<option value="true" <?php selected( $value['value'], 'true' ); ?>><?php echo $value['name'] . ': ' . __( 'True', 'shortcode-mastery' ) . $def_yes; ?></option>
							<option value="false" <?php selected( $value['value'], 'false' ); ?>><?php echo $value['name'] . ': ' . __( 'False', 'shortcode-mastery' ) . $def_no; ?></option>
						</select>
						<?php } ?>
					</div>	
					<?php $i++; } ?>
				</div>
				<?php if ( $inside_content != 'true' ) { ?>
				<div class="sm-tinymce-fields">
					<div class="sm-tinymce-field-full">
						<textarea class="sm-tinymce-field-input"><?php _e( 'Dummy Content', 'shortcode-mastery' ); ?></textarea>
					</div>
				</div>	
				<?php } ?>
				<div class="sm-tinymce-fields">
					<div class="sm-tinymce-field">
						<a href="javascript:void(0);" class="sm-button sm-loop-button sm-tinymce-submit" data-content="<?php echo $this->data->post_name; ?>" data-single="<?php echo $inside_content; ?>"><i class="icon-plus"></i><?php _e( 'Insert Shortcode', 'shortcode-mastery' ); ?></a>
					</div>	
				</div>
			</td>
		</tr>	
		<?php } ?>
		<?php if ( $this->data->post_content != '' ) { ?>
		<tr class="sm-content sm-content-<?php echo $this->data->ID; ?>" style="display: none;">
			<td colspan="3">
				<div class="sm-tinymce-more-details">
					<?php 
					$thumbnail_src = Shortcode_Mastery::getMeta( $sm_meta, 'thumbnail_source' );	
					if ( $thumbnail_src ) { ?>
					<div class="image-holder" style="background-image: url(<?php echo $thumbnail_src; ?>);background-position: 50% 50%;background-size: cover">
					</div>
					<?php } ?>
					<div class="sm-shortcode-details-content sm-tinymce-details-content">
					<?php $content = apply_filters( 'the_content', $this->data->post_content ); echo wpautop( $content ); ?>
					</div>
				</div>
			</td>
		</tr>
		<?php } ?>		
	<?php 
	}
	
	/**
     * Render thumbnail
     */
	
	protected function render_image() {
		
		$image = '<img class="default" src="' . SHORTCODE_MASTERY_URL . 'images/sm-white@2x.png' . '" />';
		
    	$sm_meta = get_post_meta( $this->data->ID );
		
		if ( $sm_meta ) {
			
			if ( isset( $sm_meta['icon_source'] ) && $sm_meta['icon_source'][0] != '' ) {
				
				$icon = explode( '|', $sm_meta['icon_source'][0] );

				$icon[0]  = str_replace( array('http:', 'https:'), '', $icon[0]);
				
				$image = '<img class="default" src="' . $icon[0] . '" />';
			
			}
		}
		
		echo '<div class="img-container">' . $image . '</div>';
		
	}
	
	protected function get_values( $value ) {
		
		if ( isset( $value['checkbox'] ) && $value['checkbox'] ) return null;
		
		return $value;
		
	}
	
	protected function get_required_values( $value ) {
		
		if ( isset( $value['checkbox'] ) && $value['checkbox'] ) return $value;
		
		return null;
		
	}

}
?>