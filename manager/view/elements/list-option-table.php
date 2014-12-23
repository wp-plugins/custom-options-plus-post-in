<?php if( !empty( $list_options ) ) : ?>

	<div class="list-options">
		
		<div class="tablenav top">

			<div class="alignleft actions bulkactions">

				<form id="<?php echo $coppi->Plugin->ltd; ?>_bulk_update" class="<?php echo $coppi->Plugin->ltd; ?>_form" method="post" action="<?php echo $coppi->Helper->get_action_link(); ?>">

					<input type="hidden" name="<?php echo $coppi->Form->field; ?>" value="Y">
					<?php wp_nonce_field( $coppi->Form->nonce . 'bulk_update' , $coppi->Form->nonce . 'bulk_update' ); ?>

					<div class="alignleft actions">
						<select name="data[bulk_update][bulkaction]" class="bulk-action-selector">
							<option value="" selected="selected"><?php _e( 'Bulk Actions' ); ?></option>
							<option value="removes"><?php _e( 'Delete' ); ?></option>
							<option value="change_cat"><?php _e( 'Category change' , $coppi->Plugin->ltd ); ?></option>
						</select>
					</div>
					
					<div class="alignleft actions bulk_change_cat">
						<select name="data[bulk_update][cat_to]" class="bulk_cat_to">
							<?php foreach( $cats as $bulk_cat ) : ?>
								<option value="<?php echo $bulk_cat['cat_id']; ?>" <?php selected( $bulk_cat['cat_id'] , $list_cat['cat_id'] ); ?>><?php echo $bulk_cat['cat_name']; ?></option>
							<?php endforeach; ?>
						</select>
						<input type="hidden" name="data[bulk_update][cat_from]" class="bulk_cat_from" value="<?php echo $list_cat['cat_id']; ?>" />
					</div>
	
					<input type="button" class="button_update_bulk button action" value="<?php _e( 'Apply' ); ?>">
					
					<span class="spinner"></span>
					
				</form>
				
			</div>
			
		</div>

		<table class="wp-list-table widefat fixed">
			<thead>
				<tr>
					<th class="check-column"><input type="checkbox"></th>
					
					<?php $column_head = array( 'create_date' => __( 'Create Date' , $coppi->Plugin->ltd ) , 'option_name' => __( 'Option Name' , $coppi->Plugin->ltd ) , 'option_value' => __( 'Option Value' , $coppi->Plugin->ltd ) ); ?>
					
					<?php foreach( $column_head as $column_name => $column_label ) : ?>
					
						<?php $orderby = $column_name; ?>
						<?php $order = 'asc'; ?>

						<?php $add_class = false; ?>

						<?php if( $column_name == $this->Inputed->list_options->orderby ) : ?>

							<?php $add_class .= ' sorted'; ?>

							<?php if( 'asc' == $this->Inputed->list_options->order ) : ?>
							
								<?php $order = 'desc'; ?>
								<?php $add_class .= ' desc'; ?>
							
							<?php endif; ?>
	
						<?php else: ?>
								
							<?php $add_class .= ' sortable asc'; ?>
							
						<?php endif; ?>
						
						<th class="<?php echo  $column_name ?> <?php echo $add_class; ?>">
							<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => $orderby , 'order' => $order ) , $coppi->Helper->get_action_link() ) ); ?>">
								<span><?php echo $column_label; ?></span>
								<span class="sorting-indicator"></span>
							</a>
						</th>

					<?php endforeach; ?>
					
					<th class="template_tag"><?php _e( 'Tag of the template' , $coppi->Plugin->ltd ); ?></th>
					<th class="shortcode"><?php _e( 'Shortcode' , $coppi->Plugin->ltd ); ?></th>
					<th class="operation">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $list_options as $option ) : ?>
					<?php include( $this->view_dir . 'elements/list-option-single-row.php' ); ?>
				<?php endforeach; ?>
			</tbody>
		</table>
		
	</div>

<?php endif; ?>