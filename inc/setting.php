<?php

$this->order();
$Data = $this->get_datas();

// include js css
$ReadedJs = array( 'jquery' , 'thickbox' );
wp_enqueue_script( $this->PageSlug , $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style( 'thickbox' );
wp_enqueue_style( $this->PageSlug , $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.css', array() , $this->Ver );
?>
<div class="wrap">
	<div class="icon32" id="icon-tools"></div>
	<?php echo $this->Msg; ?>
	<h2><?php echo $this->Name; ?></h2>
	<p><?php _e ( 'Add the value of the option. and Available for use in the post article.' , $this->ltd ); ?>

	<div class="metabox-holder columns-2">

		<div id="postbox-container-1" class="postbox-container">
			<div class="postbox">
				<h3 class="hndle"><span><?php _e( 'Create an option' , $this->ltd ); ?></span></h3>
				<div class="inside">

					<form id="coppi_create" class="coppi_form" method="post" action="">
						<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
						<?php wp_nonce_field( 'coppi' ); ?>

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
			<div class="postbox">
				<h3 class="hndle"><span><?php _e( 'Plugin About' , $this->ltd ); ?></span></h3>
				<div class="inside">
					<?php $moFile = $this->TransFileCk(); ?>
					<?php if( !$moFile ) : ?>
						<p><strong>Please translate to your language.</strong><br />Looking for someone who will translate.</p>
						<p><a href="http://gqevu6bsiz.chicappa.jp/please-translation/" target="_blank">To translate</a></p>
					<?php endif; ?>
					<p><strong><?php _e( 'Please donation.' , $this->ltd ); ?></strong></p>
					<p><?php _e( 'When you are satisfied with my plugin,<br />I\'m want a gift card.<br />Thanks!' , $this->ltd ); ?></p>
					<p><img src="http://gqevu6bsiz.chicappa.jp/wp-content/uploads/2013/01/email.gif"  /></p>
					<p><a href="<?php _e( 'http://www.amazon.com/gp/gc' , $this->ltd ); ?>" target="_blank">Amazon Gift Card</a></p>
					<p><strong><?php _e( 'Other' , $this->ltd ); ?></strong></p>
					<p>
						<span><a href="http://gqevu6bsiz.chicappa.jp/" target="_blank">blog</a></span> &nbsp; 
						<span><a href="https://twitter.com/gqevu6bsiz" target="_blank">twitter</a></span> &nbsp; 
						<span><a href="http://www.facebook.com/pages/Gqevu6bsiz/499584376749601" target="_blank">facebook</a></span> &nbsp; 
						<span><a href="http://wordpress.org/support/plugin/custom-options-plus-post-in" target="_blank">support forum</a></span> &nbsp; 
						<span><a href="http://wordpress.org/support/view/plugin-reviews/custom-options-plus-post-in" target="_blank">review</a></span>
					</p>
				</div>
			</div>
		</div>

		<div class="clear"></div>

	</div>


	<h3><?php _e( 'List of options that you created' , $this->ltd ); ?></h3>

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
							<?php wp_nonce_field( 'coppi' ); ?>

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
											<a class="delete button" title="<?php _e('Confirm Deletion'); ?>" href="<?php echo esc_url( add_query_arg( array( "delete" => $content->option_id ) ) ); ?>"><?php _e('Delete'); ?></a>
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

			<?php _e( 'Not created option.' , $this->ltd ); ?>

		<?php endif; ?>

	</div>


</div>
