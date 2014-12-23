<?php if( $coppi->Info->is_donated() ) : ?>

	<p class="donated_message description"><?php _e( 'Thank you for your donation.' , $coppi->Plugin->ltd ); ?></p>

	<div class="toggle-width">

		<p><a href="javascript:void(0);" class="collapse-sidebar button-secondary mini">&lt;</a></p>

	</div>

<?php else: ?>

	<div class="stuffbox" id="donationbox">

		<h3><span class="hndle"><?php _e( 'Please consider making a donation.' , $coppi->Plugin->ltd ); ?></span></h3>

		<div class="inside">

			<p><?php _e( 'Thank you very much for your support.' , $coppi->Plugin->ltd ); ?></p>
			<p><a href="<?php echo $coppi->Info->author_url( array( 'donate' => 1 , 'tp' => 'use_plugin' , 'lc' => 'donate' ) ); ?>" class="button button-primary" target="_blank"><?php _e( 'Donate' , $coppi->Plugin->ltd ); ?></a></p>
			<p><?php _e( 'Please enter the \'Donation delete key\' that have been described in the \'Line Break First and End download page\'.' , $coppi->Plugin->ltd ); ?></p>

			<form id="<?php echo $coppi->Plugin->ltd; ?>_donation_form" class="<?php echo $coppi->Plugin->ltd; ?>_form" method="post" action="<?php echo $coppi->Helper->get_action_link(); ?>">

				<input type="hidden" name="<?php echo $coppi->Form->field; ?>" value="Y">
				<?php wp_nonce_field( $coppi->Form->nonce . 'update_donate' , $coppi->Form->nonce . 'update_donate' ); ?>
				<label for="donate_key"><?php _e( 'Donation delete key' , $coppi->Plugin->ltd ); ?></label>
				<input type="text" name="donate_key" id="donate_key" value="" class="large-text" />

				<?php submit_button( __( 'Submit' ) , 'secondary' ); ?>

			</form>

		</div>

	</div>

<?php endif; ?>

<div class="stuffbox" id="considerbox">

	<h3><span class="hndle"><?php _e( 'Have you want to customize?' , $coppi->Plugin->ltd ); ?></span></h3>

	<div class="inside">

		<p style="float: right;">
			<a href="<?php echo $coppi->Info->author_url( array( 'contact' => 1 , 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank">
				<img src="<?php echo $coppi->Info->get_gravatar_src( '46' ); ?>" width="46" />
			</a>
		</p>

		<p><?php _e( 'I am good at Admin Screen Customize.' , $coppi->Plugin->ltd ); ?></p>
		<p><?php _e( 'Please consider the request to me if it is good.' , $coppi->Plugin->ltd ); ?></p>
		<p>
			<a href="<?php echo $coppi->Info->author_url( array( 'contact' => 1 , 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank"><?php _e( 'Contact' , $coppi->Plugin->ltd ); ?></a>
			| 
			<a href="http://wpadminuicustomize.com/blog/category/example/<?php echo $coppi->Info->get_utm_link( array( 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank"><?php _e( 'Example Customize' , $coppi->Plugin->ltd ); ?></a>

	</div>

</div>

<div class="stuffbox" id="aboutbox">

	<h3><span class="hndle"><?php _e( 'About plugin' , $coppi->Plugin->ltd ); ?></span></h3>

	<div class="inside">

		<p><?php _e( 'Version checked' , $coppi->Plugin->ltd ); ?> : <?php echo $coppi->Info->version_checked(); ?></p>
		<ul>
			<li><a href="<?php echo $coppi->Info->author_url( array( 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank"><?php _e( 'Developer\'s site' , $coppi->Plugin->ltd ); ?></a></li>
			<li><a href="<?php echo $coppi->Info->forum_url; ?>" target="_blank"><?php _e( 'Support Forums' ); ?></a></li>
			<li><a href="<?php echo $coppi->Info->review_url; ?>" target="_blank"><?php _e( 'Reviews' , $coppi->Plugin->ltd ); ?></a></li>
			<li><a href="https://twitter.com/gqevu6bsiz" target="_blank">twitter</a></li>
			<li><a href="http://www.facebook.com/pages/Gqevu6bsiz/499584376749601" target="_blank">facebook</a></li>
		</ul>

	</div>

</div>

<div class="stuffbox" id="usefulbox">

	<h3><span class="hndle"><?php _e( 'Useful plugins' , $coppi->Plugin->ltd ); ?></span></h3>
	<div class="inside">

		<p><strong><a href="http://wpadminuicustomize.com/<?php echo $coppi->Info->get_utm_link( array( 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank">WP Admin UI Customize</a></strong></p>
		<p class="description"><?php _e( 'Customize a variety of screen management.' , $coppi->Plugin->ltd ); ?></p>
		<p><strong><a href="http://wordpress.org/extend/plugins/post-lists-view-custom/" target="_blank">Post Lists View Custom</a></strong></p>
		<p class="description"><?php _e( 'Customize the list of the post and page. custom post type page, too. You can customize the column display items freely.' , $coppi->Plugin->ltd ); ?></p>
		<p><strong><a href="http://wordpress.org/extend/plugins/announce-from-the-dashboard/" target="_blank">Announce from the Dashboard</a></strong></p>
		<p class="description"><?php _e( 'Announce to display the dashboard. Change the display to a different user role.' , $coppi->Plugin->ltd  ); ?></p>
		<p>&nbsp;</p>
		<p><a href="<?php echo $coppi->Info->profile_url; ?>" target="_blank"><?php _e( 'Plugins' ); ?></a></p>

	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	
	$('.wrap.<?php echo $coppi->Plugin->ltd; ?> .toggle-width .collapse-sidebar').on('click', function( ev ) {
		
		var $button = $(ev.target);
		
		if( $button.hasClass( 'mini' ) ) {
			
			$('.metabox-holder.columns-2').removeClass('full-width');
			$button.removeClass('mini');
			$button.text( '>' );
			
		} else {
			
			$('.metabox-holder.columns-2').addClass('full-width');
			$button.addClass('mini');
			$button.text( '<' );

		}
		
	});
	
});
</script>
