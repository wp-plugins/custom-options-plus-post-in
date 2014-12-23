<div id="add_categories">

	<div class="postbox add_categories">
	
		<h3 class="hndle"><span><?php _e( 'Add New Category' ); ?></span></h3>
		<div class="inside">
			
			<form id="<?php echo $coppi->Plugin->ltd; ?>_add_category" class="<?php echo $coppi->Plugin->ltd; ?>_form" method="post" action="<?php echo $coppi->Helper->get_action_link(); ?>">
	
				<input type="hidden" name="<?php echo $coppi->Form->field; ?>" value="Y">
				<?php wp_nonce_field( $coppi->Form->nonce . 'add_category' , $coppi->Form->nonce . 'add_category' ); ?>

				<table class="form-table">
					<tbody>
						<tr>
							<th><label for="add_cat_name"><?php _e( 'New category name' ); ?> *</label></th>
							<td>
								<input type="text" id="add_cat_name" name="data[add_cat][cat_name]" value="<?php echo $this->Inputed->add_cat->cat_name; ?>" class="regular-text" />
								<p class="description"><?php _e( 'Please enter a value that does not duplicated.' , $coppi->Plugin->ltd ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>
	
				<?php submit_button(); ?>
	
			</form>
	
		</div>
	
	</div>

</div>
