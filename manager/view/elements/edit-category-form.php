<div id="update_categories">

	<div class="postbox update_categories">
	
		<h3 class="hndle"><span><?php _e( 'Edit Category' ); ?></span></h3>
		<div class="inside">
			
			<form id="<?php echo $coppi->Plugin->ltd; ?>_update_category" class="<?php echo $coppi->Plugin->ltd; ?>_form" method="post" action="<?php echo $coppi->Helper->get_action_link(); ?>">
	
				<input type="hidden" name="<?php echo $coppi->Form->field; ?>" value="Y">
				<?php wp_nonce_field( $coppi->Form->nonce . 'update_category' , $coppi->Form->nonce . 'update_category' ); ?>

				<table class="form-table">
					<tbody>
						<tr>
							<th><label for="edit_select_category"><?php _e( 'Select the category for edit.' , $coppi->Plugin->ltd ); ?></label></th>
							<td>
								<p>
									<select name="data[update_cat][cat_id]" id="edit_select_category">
										<option value=""><?php _e( 'Select Category' ); ?></option>
										<?php $selectable_cat = $cats; unset( $selectable_cat[0] ); ?>
										<?php foreach( $selectable_cat as $cat) : ?>
											<option value="<?php echo $cat['cat_id']; ?>"><?php echo $cat['cat_name']; ?></option>
										<?php endforeach; ?>
									</select>
									
									&gt;

									<label>
										<input type="text" name="data[update_cat][cat_name]" value="<?php echo $this->Inputed->add_cat->cat_name; ?>" class="regular-text" placeholder="<?php _e( 'Changed name' , $coppi->Plugin->ltd ); ?>" />
									</label>
								</p>

								<p class="description"><?php _e( 'Please enter a value that does not duplicated.' , $coppi->Plugin->ltd ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>

				<?php submit_button(); ?>
	
			</form>
	
		</div>
	
	</div>

	<div class="postbox remove_categories">
	
		<h3 class="hndle"><span><?php _e( 'Remove Category' , $coppi->Plugin->ltd ); ?></span></h3>
		<div class="inside">
			
			<form id="<?php echo $coppi->Plugin->ltd; ?>_remove_category" class="<?php echo $coppi->Plugin->ltd; ?>_form" method="post" action="<?php echo $coppi->Helper->get_action_link(); ?>">
	
				<input type="hidden" name="<?php echo $coppi->Form->field; ?>" value="Y">
				<?php wp_nonce_field( $coppi->Form->nonce . 'remove_category' , $coppi->Form->nonce . 'remove_category' ); ?>

				<table class="form-table">
					<tbody>
						<tr>
							<th><label for="remove_category"><?php _e( 'Select the category for Delete.' , $coppi->Plugin->ltd ); ?></label></th>
							<td>
								<select name="data[remove_cat][remove_ids][]" id="remove_category">
									<option value=""><?php _e( 'Select Category' ); ?></option>
									<?php $selectable_cat = $cats; unset( $selectable_cat[0] ); ?>
									<?php foreach( $selectable_cat as $cat) : ?>
										<option value="<?php echo $cat['cat_id']; ?>"><?php echo $cat['cat_name']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
					</tbody>
				</table>

				<?php submit_button( __( 'Delete' ) ); ?>
	
			</form>
	
		</div>
	
	</div>

</div>
