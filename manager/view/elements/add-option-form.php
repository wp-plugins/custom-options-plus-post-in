<div id="add_custom_option">

	<div class="postbox add_custom_option">
	
		<h3 class="hndle"><span><?php _e( 'Add New Custom Option' , $coppi->Plugin->ltd ); ?></span></h3>
		<div class="inside">
			
			<form id="<?php echo $coppi->Plugin->ltd; ?>_add_option" class="<?php echo $coppi->Plugin->ltd; ?>_form" method="post" action="<?php echo $coppi->Helper->get_action_link(); ?>">
	
				<input type="hidden" name="<?php echo $coppi->Form->field; ?>" value="Y">
				<?php wp_nonce_field( $coppi->Form->nonce . 'add_option' , $coppi->Form->nonce . 'add_option' ); ?>

				<table class="form-table">
					<tbody>
						<tr>
							<th><label for="add_option_name"><?php _e( 'Option Name' , $coppi->Plugin->ltd ); ?> *</label></th>
							<td>
								<input type="text" id="add_option_name" name="data[add_option][option_name]" value="<?php echo $this->Inputed->add_option->option_name; ?>" class="regular-text" />
								<p class="description"><?php _e( 'Please enter a value that does not duplicated.' , $coppi->Plugin->ltd ); ?></p>

							</td>
						</tr>
						<tr>
							<th><label for="add_option_cat"><?php _e( 'Categories' ); ?></label></th>
							<td>
								<select name="data[add_option][cat_id]">
									<?php foreach( $cats as $cat ) : ?>
										<option value="<?php echo $cat['cat_id']; ?>" <?php selected( $cat['cat_id'] , $this->Inputed->add_option->cat_id ); ?>><?php echo $cat['cat_name']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<th><label for="add_option_value"><?php _e( 'Option Value' , $coppi->Plugin->ltd ); ?> *</label></th>
							<td>
								<textarea id="create_option_value" name="data[add_option][option_value]" rows="5" cols="60"><?php echo stripslashes( $this->Inputed->add_option->option_value ); ?></textarea>
								<p class="description"><?php _e( 'Usable Javascript and Html tag.' , $coppi->Plugin->ltd ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>
	
				<?php submit_button(); ?>
	
			</form>
	
		</div>
	
	</div>

</div>
