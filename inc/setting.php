<?php

$this->order();
$Data = $this->get_datas();
$Categories = $this->get_categories();
$nonce_v = wp_create_nonce( $this->Nonce );
$donatedKey = get_option( $this->Record["donate"] );
$Memo = get_option( $this->Record["memo"] );

// include js css
$ReadedJs = array( 'jquery' , 'thickbox' , 'jquery-ui-dialog' );
wp_enqueue_script( $this->PageSlug , $this->Url . $this->PluginSlug . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style( 'thickbox' );
wp_enqueue_style( 'wp-jquery-ui-dialog' );
wp_enqueue_style( $this->PageSlug , $this->Url . $this->PluginSlug . '.css', array() , $this->Ver );

$translation_array = array( 'url' => esc_url( add_query_arg( array( '_wpnonce' => $nonce_v ) ) ) , 'confirm_message' => __( 'Are you sure you want to bulk action?' , $this->ltd) , 'ajax_url' => admin_url( 'admin-ajax.php' ) , 'UPFN' => 'Y' );
wp_localize_script( $this->PageSlug , $this->PageSlug , $translation_array );
?>
<div class="wrap">
	<div class="icon32" id="icon-tools"></div>
	<?php echo $this->Msg; ?>
	<h2><?php echo $this->Name; ?></h2>
	<p><?php _e ( 'Please create custom options.' , $this->ltd ); ?>

	<?php $class = ""; ?>
	<?php if( get_option( $this->Record["donate_width"] ) ) $class .= ' full-width'; ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<?php if( !empty( $Memo ) ) : ?>
				<div class="memo_show"><?php echo $Memo; ?> <a href="" class="meno_edit button-secondary"><?php _e( 'Edit to Memo' , $this->ltd ); ?></a></div>
			<?php else: ?>
				<p><a href="" class="meno_edit button-secondary"><?php _e( 'Use of Memo' , $this->ltd ); ?></a></p>
			<?php endif; ?>
			
			<div class="postbox memo">
				<h3 class="hndle"><span><?php _e( 'Edit to Memo' , $this->ltd ); ?></span></h3>
				<div class="inside">
		
					<p class="description"><?php _e( 'Please use it as reminder for your manage.' , $this->ltd ); ?></p>
					<form id="coppi_update_memo" class="coppi_form" method="post" action="<?php echo remove_query_arg( array( '_wpnonce' , 'delete_cat' ) ); ?>">
						<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
						<input type="hidden" name="record_field" value="coppi_memo" />
						<?php wp_nonce_field( $this->PageSlug ); ?>
						<?php $field = 'memo'; ?>
						<p>
							<textarea id="create_option_value" name="<?php echo $field; ?>" rows="3" cols="60" style="width: 98%;"><?php echo stripslashes( $Memo ); ?></textarea>
						</p>
						<p class="submit">
							<input type="submit" class="button-primary" name="update_memo" value="<?php _e( 'Save' ); ?>" />
						</p>
					</form>
		
				</div>
			</div>

			<div class="postbox">
				<h3 class="hndle"><span><?php _e( 'Add New Custom Option' , $this->ltd ); ?></span></h3>
				<div class="inside">

					<form id="coppi_create" class="coppi_form" method="post" action="<?php echo remove_query_arg( array( '_wpnonce' , 'delete_cat' ) ); ?>">
						<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
						<?php wp_nonce_field( $this->PageSlug ); ?>

						<?php $field = 'create'; ?>
						<table class="form-table">
							<tbody>
								<tr>
									<th>
										<label for="create_option_name"><?php _e( 'Option Name' , $this->ltd ); ?> *</label>
									</th>
									<td>
										<?php $val = ''; if( $this->Duplicated == true && !empty( $_POST["data"][$field] ) ) { $val = strip_tags( $_POST["data"][$field]["option_name"] ); } ?>
										<input type="text" id="create_option_name" name="data[<?php echo $field; ?>][option_name]" value="<?php echo $val; ?>" class="regular-text" />
										<p class="description"><?php _e( 'Please enter a value that does not duplicated.' , $this->ltd ); ?></p>
									</td>
								</tr>
								<tr>
									<th>
										<label for="create_option_cat"><?php _e( 'Categories' ); ?></label>
									</th>
									<td>
										<select name="data[<?php echo $field; ?>][cat_id]">
											<option value="0"><?php _e( 'Uncategorized' ); ?></option>
											<?php if( !empty( $Categories ) ) : ?>
												<?php foreach( $Categories as $k => $cat) : ?>
													<option value="<?php echo strip_tags( $cat->cat_id ); ?>"><?php echo strip_tags( $cat->cat_name ); ?></option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</td>
								</tr>
								<tr>
									<th>
										<label for="create_option_value"><?php _e( 'Option Value' , $this->ltd ); ?> *</label>
									</th>
									<td>
										<?php $val = ''; if( $this->Duplicated == true && !empty( $_POST["data"][$field] ) ) { $val = stripslashes( $_POST["data"][$field]["option_value"] ); } ?>
										<textarea id="create_option_value" name="data[<?php echo $field; ?>][option_value]" rows="5" cols="60"><?php echo $val; ?></textarea>
										<p class="description"><?php _e( 'Usable Javascript and Html tag.' , $this->ltd ); ?></p>
									</td>
								</tr>
							</tbody>
						</table>
						
						<p class="submit">
							<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save' ); ?>" />
						</p>
					</form>

				</div>
			</div>


			<div class="postbox">
				<h3 class="hndle"><span><?php _e( 'Add New Category' ); ?></span></h3>
				<div class="inside">

					<form id="coppi_create_cat" class="coppi_form" method="post" action="<?php echo remove_query_arg( array( '_wpnonce' , 'delete_cat' ) ); ?>">
						<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
						<?php wp_nonce_field( $this->PageSlug ); ?>

						<?php $field = 'create_cat'; ?>
						<table class="form-table">
							<tbody>
								<tr>
									<th><label for="create_cat_name"><?php _e( 'Name' ); ?></label></th>
									<td>
										<?php $val = ''; if( $this->DuplicatedCat == true && !empty( $_POST["data"][$field] ) ) { $val = strip_tags( $_POST["data"][$field]["cat_name"] ); } ?>
										<input type="text" name="data[<?php echo $field; ?>][cat_name]" value="<?php echo $val; ?>" class="regular-text" />
										<p class="description"><?php _e( 'Please enter a value that does not duplicated.' , $this->ltd ); ?></p>
									</td>
								</tr>
							</tbody>
						</table>
						<p class="submit">
							<input type="submit" class="button-primary" name="update_cat" value="<?php _e( 'Save' ); ?>" />
						</p>
					</form>

				</div>
			</div>

			<div class="postbox">
				<h3 class="hndle"><span><?php _e( 'Edit Category' ); ?></span></h3>
				<div class="inside">

					<?php if( !empty( $Categories ) ) : ?>

						<form id="coppi_update_cat" class="coppi_form" method="post" action="<?php echo remove_query_arg( array( '_wpnonce' , 'delete_cat' ) ); ?>">
							<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
							<?php wp_nonce_field( $this->PageSlug ); ?>
	
							<?php $field = 'update_cat'; ?>
							<p>
								<?php _e( 'Select the category for edit.' , $this->ltd ); ?>: 
								<select name="data[<?php echo $field; ?>][cat]">
									<option value=""><?php _e( 'Select Category' ); ?></option>
									<?php foreach( $Categories as $k => $cat) : ?>
										<option value="<?php echo strip_tags( $cat->cat_id ); ?>"><?php echo strip_tags( $cat->cat_name ); ?></option>
									<?php endforeach; ?>
								</select>
							</p>
							
							<div class="category_edit">
								<p>
									<label>
										<?php _e( 'Changed name' , $this->ltd ); ?>: 
										<input id="cat_edit_name" type="text" name="data[<?php echo $field; ?>][cat_name]" value="" class="regular-text" />
										<input id="cat_edit_id" type="hidden" name="data[<?php echo $field; ?>][cat_id]" value="" />
										<input id="cat_current_name" type="hidden" name="data[<?php echo $field; ?>][cat_current_name]" value="" />
									</label>
								</p>
								<p class="description"><?php _e( 'Please enter a value that does not duplicated.' , $this->ltd ); ?></p>
	
								<p class="submit">
									<input type="submit" class="button-primary" name="update_cat" value="<?php _e( 'Save' ); ?>" />
									&nbsp
									<a href="" class="delete button-secondary alignright"><?php _e( 'Delete' ); ?></a>
								</p>
							</div>
						</form>

					<?php else : ?>
					
						<p><?php _e( 'Not created category.' , $this->ltd ); ?></p>
					
					<?php endif; ?>

				</div>
			</div>

		</div>

		<div id="postbox-container-2" class="postbox-container">

			<?php if( $donatedKey == $this->DonateKey ) : ?>
				<div class="toggle-plugin"><p class="icon"><a href="#"><?php echo esc_html__( 'Collapse' ); ?></a></p></div>
			<?php endif; ?>

			<div class="stuffbox" style="border-color: #FFC426; border-width: 3px;">
				<h3 style="background: #FFF2D0; border-color: #FFC426;"><span class="hndle"><?php _e( 'Have you want to customize?' , $this->ltd ); ?></span></h3>
				<div class="inside">
					<p style="float: right;">
						<img src="<?php echo $this->Schema; ?>www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=46" width="46" /><br />
						<a href="<?php echo $this->AuthorUrl; ?>contact-us/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank">gqevu6bsiz</a>
					</p>
					<p><?php _e( 'I am good at Admin Screen Customize.' , $this->ltd ); ?></p>
					<p><?php _e( 'Please consider the request to me if it is good.' , $this->ltd ); ?></p>
					<p>
						<a href="http://wpadminuicustomize.com/blog/category/example/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank"><?php _e ( 'Example Customize' , $this->ltd ); ?></a> :
						<a href="<?php echo $this->AuthorUrl; ?>contact-us/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank"><?php _e( 'Contact me' , $this->ltd ); ?></a></p>
				</div>
			</div>

			<?php if( $donatedKey == $this->DonateKey ) :  ?>

				<p class="description"><?php _e( 'Thank you for your donation.' , $this->ltd ); ?></p>
			
			<?php else : ?>

				<div class="stuffbox" id="donationbox" style="background: #87BCE4; border: 1px solid #227499; color: #FFFFFF; padding-bottom: 20px;">
					<div class="inside">
						<p style="font-size: 20px;"><?php _e( 'Please donate.' , $this->ltd ); ?></p>
						<div>
							<p><strong><?php _e( 'The primary use of donations' , $this->ltd ); ?></strong></p>
							<ul>
								<li>- <?php _e( 'Liquidation of time and value' , $this->ltd ); ?></li>
								<li>- <?php _e( 'Additional suggestions feature' , $this->ltd ); ?></li>
								<li>- <?php _e( 'Maintain motivation' , $this->ltd ); ?></li>
								<li>- <?php _e( 'Ensure time as the father of Sunday' , $this->ltd ); ?></li>
							</ul>
						</div>
						<p>&nbsp;</p>
						<p style="text-align: center;">
							<a href="<?php echo $this->AuthorUrl; ?>please-donation/?utm_source=use_plugin&utm_medium=donate&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" class="button-primary" target="_blank"><?php _e( 'Please donate.' , $this->ltd ); ?></a>
						</p>
						<p>&nbsp;</p>
						<form id="donation_form" class="coppi_form" method="post" action="<?php echo remove_query_arg( array( '_wpnonce' , 'delete_cat' ) ); ?>">
							<p><?php _e( 'If you have already donated to.' , $this->ltd ); ?></p>
							<p><?php _e( 'Please enter the \'Donation delete key\' that have been described in the \'Line Break First and End download page\'.' , $this->ltd ); ?></p>
							<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
							<?php wp_nonce_field( $this->PageSlug ); ?>
							<label for="donate_key"><?php _e( 'Donation delete key' , $this->ltd ); ?></label>
							<input type="text" name="donate_key" id="donate_key" value="" class="small-text" />
							<input type="submit" class="button-primary" name="donate" value="<?php _e( 'Submit' ); ?>" />
						</form>
					</div>
				</div>
				
			<?php endif; ?>

			<div class="stuffbox" id="aboutbox">
				<h3><span class="hndle"><?php _e( 'About plugin' , $this->ltd ); ?></span></h3>
				<div class="inside">
					<p><?php _e( 'Version checked' , $this->ltd ); ?> : 3.5.2 - 3.9.2</p>

					<?php $moFile = $this->TransFileCk(); ?>
					<?php if( !$moFile ) : ?>
						<p>Would you like to translate your country language?</p>
						<p><a href="<?php echo $this->AuthorUrl; ?>please-translation/" target="_blank">Translate</a></p>
					<?php endif; ?>

					<ul>
						<li><a href="http://wordpress.org/extend/plugins/<?php echo $this->PluginSlug; ?>/" target="_blank"><?php _e( 'Plugin\'s site' , $this->ltd ); ?></a></li>
						<li><a href="<?php echo $this->AuthorUrl; ?>?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank"><?php _e( 'Developer\'s site' , $this->ltd ); ?></a></li>
						<li><a href="http://wordpress.org/support/plugin/<?php echo $this->PluginSlug; ?>" target="_blank"><?php _e( 'Support Forums' ); ?></a></li>
						<li><a href="http://wordpress.org/support/view/plugin-reviews/<?php echo $this->PluginSlug; ?>" target="_blank"><?php _e( 'Reviews' , $this->ltd ); ?></a></li>
						<li><a href="https://twitter.com/gqevu6bsiz" target="_blank">twitter</a></li>
						<li><a href="http://www.facebook.com/pages/Gqevu6bsiz/499584376749601" target="_blank">facebook</a></li>
					</ul>
				</div>
			</div>

			<div class="stuffbox" id="usefulbox">
				<h3><span class="hndle"><?php _e( 'Useful plugins' , $this->ltd ); ?></span></h3>
				<div class="inside">
					<p><strong><a href="http://wpadminuicustomize.com/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank">WP Admin UI Customize</a></strong></p>
					<p class="description"><?php _e( 'Customize a variety of screen management.' , $this->ltd ); ?></p>
					<p><strong><a href="http://wordpress.org/extend/plugins/post-lists-view-custom/" target="_blank">Post Lists View Custom</a></strong></p>
					<p class="description"><?php _e( 'Customize the list of the post and page. custom post type page, too. You can customize the column display items freely.' , $this->ltd ); ?></p>
					<p><strong><a href="http://wordpress.org/plugins/media-insert-from-themes-dir/" target="_blank">Media Insert from Themes Dir</a></strong></p>
					<p class="description"><?php _e( 'This Plugin is insert images in theme folder. Manipulated as easily as you would choose from Media Library.' , $this->ltd ); ?></p>
					<p>&nbsp;</p>
				</div>
			</div>

		</div>

		<div class="clear"></div>

	</div>

	<h3><?php _e( 'Custom options of created.' , $this->ltd ); ?></h3>

	<div class="metabox-holder columns-1 update_table">

		<?php if( !empty( $Data ) ) : ?>

			<?php if( !empty( $Categories ) ) : ?>
			
				<?php foreach( $Categories as $k => $cat ) : ?>

					<div class="list_categories">
						<h4>
							<?php echo $cat->cat_name; ?>
							<?php $option_count = $this->category_option_count( $cat ); ?>
							<?php if( !empty( $option_count ) ) : ?>
								<a href="#" class="show">( <?php echo $option_count; ?> )</a>
							<?php else :?>
								(0)
							<?php endif; ?>
						</h4>

						<?php $this->get_list_option( $cat->cat_id , $option_count ); ?>
					</div>
				
				<?php endforeach; ?>
			
			<?php endif; ?>

			<h4>
				<?php _e( 'Uncategorized' ); ?>
			</h4>

			<?php $this->get_list_option( 0 , "all" ); ?>

			<div id="Confirm">
				<div id="ConfirmSt">
					<div class="inner">
						<p><?php echo sprintf( __( 'You are about to delete <strong>%s</strong>.' ), '' ); ?> <span class="spinner"></span></p>
						<input type="hidden" name="delete_id" value="" />
						<?php wp_nonce_field( $this->PageSlug ); ?>
					</div>
					<div class="alignleft">
						<a class="button" id="cancelbtn" href="javascript:void(0);"><?php _e( 'Cancel' ); ?></a>
					</div>
					<div class="alignright">
						<a class="button" id="confirm_deletebtn" href=""><?php _e( 'Continue' ); ?></a>
					</div>
				</div>
			</div>


		<?php else : ?>

			<p><strong><?php _e( 'Not created option.' , $this->ltd ); ?></strong></p>
			<p class="description"><?php _e( 'Please create custom options.' , $this->ltd ); ?></p>

		<?php endif; ?>

	</div>

</div>
