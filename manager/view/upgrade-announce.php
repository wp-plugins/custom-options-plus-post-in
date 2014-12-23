<div class="wrap">

	<h2><?php echo $coppi->name; ?></h2>

	<form action="<?php echo $coppi->Helper->get_action_link(); ?>" method="post">
	
		<input type="hidden" name="<?php echo $coppi->Form->field; ?>" value="Y">
		<?php wp_nonce_field( $coppi->Form->nonce . 'db_upgrade' , $coppi->Form->nonce . 'db_upgrade' ); ?>
		<input type="hidden" name="upgrade_action" value="install" />

		<p><?php _e( 'Please update the database.' , $coppi->Plugin->ltd ); ?></p>
	
		<div class="submit">
		
			<?php submit_button( __( 'Update the Database' , $coppi->Plugin->ltd ) ); ?>
		
		</div>
	
	</form>

</div>
