<?php if( !empty( $memo ) ) : ?>

	<div class="memo_show">
		<?php echo $memo; ?>
		<input type="button" class="button-secondary" id="button_edit_memo" value="<?php _e( 'Edit to Memo' , $coppi->Plugin->ltd ); ?>" />
	</div>

<?php else: ?>

	<input type="button" class="button-secondary" id="button_edit_memo" value="<?php _e( 'Use of Memo' , $coppi->Plugin->ltd ); ?>" />

<?php endif; ?>

<div id="edit_memo">

	<div class="postbox memo">
	
		<h3 class="hndle"><span><?php _e( 'Edit to Memo' , $coppi->Plugin->ltd ); ?></span></h3>
		<div class="inside">
			
			<p class="description"><?php _e( 'Please use it as reminder for your manage.' , $coppi->Plugin->ltd ); ?></p>
	
			<form id="<?php echo $coppi->Plugin->ltd; ?>_update_memo" class="<?php echo $coppi->Plugin->ltd; ?>_form" method="post" action="<?php echo $coppi->Helper->get_action_link(); ?>">
	
				<input type="hidden" name="<?php echo $coppi->Form->field; ?>" value="Y">
				<?php wp_nonce_field( $coppi->Form->nonce . 'update_memo' , $coppi->Form->nonce . 'update_memo' ); ?>
				<input type="hidden" name="update_field" value="update_memo" />
	
				<p>
					<textarea name="data[memo]" class="large-text" rows="3" cols="60"><?php echo stripslashes( $memo ); ?></textarea>
				</p>
	
				<?php submit_button(); ?>
	
			</form>
	
		</div>
	
	</div>

</div>