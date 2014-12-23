<tr class="" id="tr_<?php echo $option['option_id']; ?>" data-option_id="<?php echo $option['option_id']; ?>">
	<th class="check-column">
		<input type="checkbox" name="data[update_option][<?php echo $option['option_id']; ?>][option_id]" value="<?php echo $option['option_id']; ?>">
	</th>
	<td class="create_date">
		<?php global $date_format; ?>
		<?php echo mysql2date( $date_format , $option['create_date'] ); ?><br />
		<span style="font-size: 10px;">(<?php echo $option['create_date']; ?>)</span>
	</td>
	<td class="option_name">
		<div class="edit">
			<p>
				<input type="text" name="data[update_option][<?php echo $option['option_id']; ?>][option_name]" value="<?php echo $option['option_name']; ?>" class="large-text option_name" />
			</p>
			<p>
				<?php _e( 'Categories' ); ?>
				<select name="data[update_option][<?php echo $option['option_id']; ?>][cat_id]" class="cat_id">
					<?php foreach( $cats as $single_list_cat ) : ?>
						<option value="<?php echo $single_list_cat['cat_id']; ?>" <?php selected( $single_list_cat['cat_id'] , $option['cat_id'] ); ?>><?php echo $single_list_cat['cat_name']; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
		</div>
		<div class="show">
			<?php echo $option['option_name']; ?>
		</div>
	</td>
	<td class="option_value">
		<div class="edit">
			<textarea name="data[update_option][<?php echo $option['option_id']; ?>][option_value]" rows="10" cols="25" class="large-text option_value"><?php echo stripslashes( $option['option_value'] ); ?></textarea>
		</div>
		<div class="show">
			<?php echo stripslashes( esc_html( $option['option_value'] ) ); ?>
		</div>
	</td>
	<td class="template_tag">
		<code>&lt;?php echo get_coppi('<?php echo esc_html( $option['option_name'] ); ?>'); ?&gt;</code>
	</td>
	<td class="shortcode">
		<code>[coppi key="<?php echo esc_html( $option['option_name'] ); ?>"]</code>
	</td>
	<td class="operation">
		<div class="show">
			<div class="alignleft">
				<p><input type="button" class="button_edit_option button-primary" value="<?php _e( 'Edit' ); ?>"  /></p>
			</div>
			<div class="alignleft">
				<p><input type="button" class="button_remove_option button-secondary" value="<?php _e( 'Delete' ); ?>"  /></p>
			</div>

			<div class="clear"></div>

		</div>
		<div class="edit">
			<p><input type="button" class="button_update_option button-primary" value="<?php _e( 'Save' ); ?>"  /></p>
			<span class="spinner"></span>
		</div>
		<div class="saved_notice"><?php _e( 'Saved' ); ?></div>
	</td>
</tr>