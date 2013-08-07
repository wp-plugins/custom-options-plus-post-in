<?php

$this->order();
$Data = $this->get_datas();
$nonce_v = wp_create_nonce( $this->Nonce );

// include js css
$ReadedJs = array( 'jquery' , 'thickbox' );
wp_enqueue_script( $this->PageSlug , $this->Url . $this->PluginSlug . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style( 'thickbox' );
wp_enqueue_style( $this->PageSlug , $this->Url . $this->PluginSlug . '.css', array() , $this->Ver );

?>
<div class="wrap">
	<div class="icon32" id="icon-tools"></div>
	<?php echo $this->Msg; ?>
	<h2><?php echo $this->Name; ?></h2>
	<p><?php _e ( 'Please create an option value.' , $this->ltd ); ?>

	<div class="metabox-holder columns-2">

		<div id="postbox-container-1" class="postbox-container">
			<div class="postbox">
				<h3 class="hndle"><span><?php _e( 'Create an Option' , $this->ltd ); ?></span></h3>
				<div class="inside">

					<form id="coppi_create" class="coppi_form" method="post" action="">
						<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
						<?php wp_nonce_field( $this->PageSlug ); ?>

						<?php $field = 'create'; ?>
						<p>
							<label>
								<?php _e( 'Option Name' , $this->ltd ); ?> *<br />
								<?php $val = ''; if( $this->Duplicated == true && !empty( $_POST["data"][$field] ) ) { $val = strip_tags( $_POST["data"][$field]["option_name"] ); } ?>
								<input type="text" name="data[<?php echo $field; ?>][option_name]" value="<?php echo $val; ?>" class="regular-text" />
							</label>
						</p>
						<p class="description"><?php _e( 'Please enter a value that does not duplicated.' , $this->ltd ); ?></p>
						<p>&nbsp;</p>
						<p>
							<label>
								<?php _e( 'Option Value' , $this->ltd ); ?> *<br />
								<?php $val = ''; if( $this->Duplicated == true && !empty( $_POST["data"][$field] ) ) { $val = stripslashes( $_POST["data"][$field]["option_value"] ); } ?>
								<textarea name="data[<?php echo $field; ?>][option_value]" rows="5" cols="60"><?php echo $val; ?></textarea>
							</label>
						</p>
						<p class="description"><?php _e( 'Usable Javascript and Html tag.' , $this->ltd ); ?></p>
						<p class="submit">
							<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save' ); ?>" />
						</p>
					</form>
				</div>
			</div>
		</div>

		<div id="postbox-container-2" class="postbox-container">

			<div class="stuffbox" style="border-color: #FFC426; border-width: 3px;">
				<h3 style="background: #FFF2D0; border-color: #FFC426;"><span class="hndle"><?php _e( 'Have you want to customize?' , $this->ltd_p ); ?></span></h3>
				<div class="inside">
					<p style="float: right;">
						<img src="<?php echo $this->Schema; ?>www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=46" width="46" /><br />
						<a href="<?php echo $this->AuthorUrl; ?>contact-us/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank">gqevu6bsiz</a>
					</p>
					<p><?php _e( 'I am good at Admin Screen Customize.' , $this->ltd_p ); ?></p>
					<p><?php _e( 'Please consider the request to me if it is good.' , $this->ltd_p ); ?></p>
					<p>
						<a href="http://wpadminuicustomize.com/blog/category/example/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank"><?php _e ( 'Example Customize' , $this->ltd_p ); ?></a> :
						<a href="<?php echo $this->AuthorUrl; ?>contact-us/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank"><?php _e( 'Contact me' , $this->ltd_p ); ?></a></p>
				</div>
			</div>

			<div class="stuffbox" id="donationbox" style="background: #87BCE4; border: 1px solid #227499;">
				<div class="inside">
					<p style="color: #FFFFFF; font-size: 20px;"><?php _e( 'Please donate.' , $this->ltd_p ); ?></p>
					<p style="text-align: center;">
						<a href="<?php echo $this->AuthorUrl; ?>please-donation/?utm_source=use_plugin&utm_medium=donate&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" class="button-primary" target="_blank"><?php _e( 'Please donate.' , $this->ltd_p ); ?></a>
					</p>
				</div>
			</div>

			<div class="stuffbox" id="aboutbox">
				<h3><span class="hndle"><?php _e( 'About plugin' , $this->ltd_p ); ?></span></h3>
				<div class="inside">
					<p><?php _e( 'Version check' , $this->ltd_p ); ?> : 3.4.2 - 3.6</p>

					<?php $moFile = $this->TransFileCk(); ?>
					<?php if( !$moFile ) : ?>
						<p>Would you like to translate your country language?</p>
						<p><a href="<?php echo $this->AuthorUrl; ?>please-translation/" target="_blank">Translate</a></p>
					<?php endif; ?>

					<ul>
						<li><a href="http://wordpress.org/extend/plugins/<?php echo $this->PluginSlug; ?>/" target="_blank"><?php _e( 'Plugin\'s site' , $this->ltd_p ); ?></a></li>
						<li><a href="<?php echo $this->AuthorUrl; ?>?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank"><?php _e( 'Developer\'s site' , $this->ltd_p ); ?></a></li>
						<li><a href="http://wordpress.org/support/plugin/<?php echo $this->PluginSlug; ?>" target="_blank"><?php _e( 'Support Forums' ); ?></a></li>
						<li><a href="http://wordpress.org/support/view/plugin-reviews/<?php echo $this->PluginSlug; ?>" target="_blank"><?php _e( 'Reviews' , $this->ltd_p ); ?></a></li>
						<li><a href="https://twitter.com/gqevu6bsiz" target="_blank">twitter</a></li>
						<li><a href="http://www.facebook.com/pages/Gqevu6bsiz/499584376749601" target="_blank">facebook</a></li>
					</ul>
				</div>
			</div>

			<div class="stuffbox" id="usefulbox">
				<h3><span class="hndle"><?php _e( 'Useful plugins' , $this->ltd_p ); ?></span></h3>
				<div class="inside">
					<p><strong><a href="http://wpadminuicustomize.com/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank">WP Admin UI Customize</a></strong></p>
					<p class="description"><?php _e( 'Customize a variety of screen management.' , $this->ltd_p ); ?></p>
					<p><strong><a href="http://wordpress.org/extend/plugins/post-lists-view-custom/" target="_blank">Post Lists View Custom</a></strong></p>
					<p class="description"><?php _e( 'Customize the list of the post and page. custom post type page, too. You can customize the column display items freely.' , $this->ltd_p ); ?></p>
					<p><strong><a href="http://wordpress.org/plugins/media-insert-from-themes-dir/" target="_blank">Media Insert from Themes Dir</a></strong></p>
					<p class="description"><?php _e( 'This Plugin is insert images in theme folder. Manipulated as easily as you would choose from Media Library.' , $this->ltd_p ); ?></p>
					<p>&nbsp;</p>
				</div>
			</div>

		</div>

		<div class="clear"></div>

	</div>


	<h3><?php _e( 'Option value of created.' , $this->ltd ); ?></h3>

	<div class="metabox-holder columns-1 update_table">

		<?php if( !empty( $Data ) ) : ?>

			<table cellspacing="0" class="widefat fixed">
				<thead>
					<tr>
						<?php
						$SortHeader = array(
							array( "sort_type" => "create_date" , "sort_name" => __( 'Create Date' , $this->ltd ) ),
							array( "sort_type" => "option_name" , "sort_name" => __( 'Option Name' , $this->ltd ) ),
							array( "sort_type" => "option_value" , "sort_name" => __( 'Option Value' , $this->ltd ) ),
						);
						?>
						<?php foreach( $SortHeader as $sorter ) : ?>
							<?php $Cls = 'sortable asc'; $Od = 'asc'; ?>
							<?php if( $this->Order["orderby"] == $sorter["sort_type"] ) : ?>
								<?php $Cls = 'sorted ' . $this->Order["order"]; ?>
							<?php endif; ?>
							<?php if( $this->Order["order"] == 'asc' ) : ?>
								<?php $Od = 'desc'; ?>
							<?php endif; ?>
							<th class="<?php echo $sorter["sort_type"]; ?> <?php echo $Cls; ?>">
								<a href="<?php echo esc_url( add_query_arg( array( "orderby" => $sorter["sort_type"] , "order" => $Od ) ) ); ?>">
									<span><?php echo $sorter["sort_name"]; ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
						<?php endforeach; ?>
						<th class="template_tag"><?php _e( 'Tag of the template' , $this->ltd ); ?></th>
						<th class="shortcode"><?php _e( 'Shortcode' , $this->ltd ); ?></th>
						<th class="operation">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php $field = 'update'; ?>
					<?php foreach( $Data as $key => $content ) : ?>
						<form class="coppi_form" method="post" action="">
							<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
							<input type="hidden" name="data[<?php echo $field; ?>][option_id]" value="<?php echo strip_tags( $content->option_id ); ?>" />
							<?php wp_nonce_field( $this->PageSlug ); ?>

							<tr id="tr_<?php echo $content->option_id; ?>">
								<td class="create_date">
									<?php echo strip_tags( $content->create_date ); ?>
								</td>
								<td class="option_name">
									<div class="off">
										<input type="text" name="data[<?php echo $field; ?>][option_name]" value="<?php echo strip_tags( $content->option_name ); ?>" />
									</div>
									<div class="on">
										<?php echo strip_tags( $content->option_name ); ?>
									</div>
								</td>
								<td class="option_value">
									<div class="off">
										<textarea name="data[<?php echo $field; ?>][option_value]" rows="10" cols="25"><?php echo stripslashes( $content->option_value ); ?></textarea>
									</div>
									<div class="on">
										<?php echo stripslashes( esc_html( $content->option_value ) ); ?>
									</div>
								</td>
								<td class="template_tag">
									<code>&lt;?php echo get_coppi('<?php echo esc_html( $content->option_name ); ?>'); ?&gt;</code>
								</td>
								<td class="shortcode">
									<code>[coppi key="<?php echo esc_html( $content->option_name); ?>"]</code>
								</td>
								<td class="operation">
									<div class="on">
										<div class="alignleft">
											<a class="edit button-primary" href="javascript:void(0)"><?php _e('Edit'); ?></a>
										</div>
										<div class="alignright">
											<a class="delete button" title="<?php _e('Confirm Deletion'); ?>" href="<?php echo esc_url( add_query_arg( array( "delete" => $content->option_id , '_wpnonce' => $nonce_v ) ) ); ?>"><?php _e('Delete'); ?></a>
										</div>
										<div class="clear"></div>
									</div>
									<div class="off">
										<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save' ); ?>" />
									</div>
								</td>
							</tr>
						</form>
					<?php endforeach; ?>
				</tbody>
			</table>

			<div id="Confirm">
				<div id="ConfirmSt">
					<div class="inner">
						<p><?php echo sprintf( __( 'You are about to delete <strong>%s</strong>.' ), '' ); ?></p>
					</div>
					<div class="alignleft">
						<a class="button" id="cancelbtn" href="javascript:void(0);"><?php _e('Cancel'); ?></a>
					</div>
					<div class="alignright">
						<a class="button" id="deletebtn" href=""><?php _e('Continue'); ?></a>
					</div>
				</div>
			</div>


		<?php else : ?>

			<p><strong><?php _e( 'Not created option.' , $this->ltd ); ?></strong></p>
			<p class="description"><?php _e( 'Please create an option value.' , $this->ltd ); ?></p>

		<?php endif; ?>

	</div>


</div>
