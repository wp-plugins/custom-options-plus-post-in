<?php

global $date_format;

$memo = Coppi_Manager_Controller_Memo::get_data();
$options = $this->get_data();
$cats = Coppi_Manager_Controller_Category::get_data();

?>
<div class="wrap <?php echo $coppi->Plugin->ltd; ?>">

	<h2><?php echo $coppi->name; ?></h2>

	<div class="metabox-holder columns-2 <?php echo $coppi->Info->get_add_class(); ?>">

		<div id="postbox-container-1" class="postbox-container">

			<?php include_once( $this->view_dir . 'elements/information.php' ); ?>
		
		</div>

		<div id="postbox-container-2" class="postbox-container">
		
			<?php include_once( $this->view_dir . 'elements/memo.php' ); ?>
		
			<p>
				<input type="button" id="button_add_custom_option" class="button button-primary" value="<?php _e( 'Add New Custom Option' , $coppi->Plugin->ltd ); ?>" />
				<input type="button" id="button_add_category" class="button button-primary" value="<?php _e( 'Add New Category' ); ?>" />
				<?php if( count( $cats ) > 1 ) : ?>
					<input type="button" id="button_update_category" class="button button-primary" value="<?php _e( 'Edit Category' ); ?>" />
				<?php endif; ?>
			</p>

			<?php include_once( $this->view_dir . 'elements/add-option-form.php' ); ?>

			<?php include_once( $this->view_dir . 'elements/add-category-form.php' ); ?>
			
			<?php if( count( $cats ) > 1 ) : ?>

				<?php include_once( $this->view_dir . 'elements/edit-category-form.php' ); ?>

			<?php endif; ?>
			
			<?php if( !empty( $options ) ) : ?>
			
				<h3><?php _e( 'Custom options of created.' , $coppi->Plugin->ltd ); ?></h3>
			
				<?php if( count( $cats ) > 1 ) : ?>

					<?php $selectable_cat = $cats; unset( $selectable_cat[0] ); ?>
					
					<?php foreach( $selectable_cat as $cat ) : ?>
					
						<div class="list_categories" id="cat_<?php echo $cat['cat_id']; ?>">
						
							<h4>
								<?php if( !empty( $options[$cat['cat_id']] ) ) : ?>
								
									<a href="javascript:void(0);" class="list_show"><?php echo $cat['cat_name']; ?> ( <?php echo count( $options[$cat['cat_id']] ); ?> )</a>
								
								<?php else : ?>
								
									<?php echo $cat['cat_name']; ?> (0)
								
								<?php endif; ?>
							</h4>
							
							<?php $list_options = $options[$cat['cat_id']]; ?>
							<?php $list_cat = $cat; ?>
							<?php include( $this->view_dir . 'elements/list-option-table.php' ); ?>

						</div>
					
					<?php endforeach; ?>
					
				<?php endif; ?>

				<div class="list_categories" id="cat_0">
					<h4>
						<?php if( !empty( $options[0] ) ) : ?>
									
							<a href="javascript:void(0);" class="list_show"><?php echo $cats[0]['cat_name']; ?> ( <?php echo count( $options[0] ); ?> )</a>
									
						<?php else : ?>
									
							<?php echo $cats[0]['cat_name']; ?> (0)
									
						<?php endif; ?>
					</h4>

					<?php if( !empty( $options[0] ) ) : ?>

						<?php $list_options = $options[0]; ?>
						<?php $list_cat = $cats[0]; ?>
						<?php include( $this->view_dir . 'elements/list-option-table.php' ); ?>
							
					<?php endif; ?>

				</div>
			
			<?php else: ?>
			
				<h3><?php _e( 'Not created option.' , $coppi->Plugin->ltd ); ?></h3>
				<p class="description"><?php _e( 'Please create custom options.' , $coppi->Plugin->ltd ); ?></p>

			<?php endif; ?>
			
		</div>
		
		<div class="clear"></div>
		
	</div>
	

	<div id="Confirm">

		<div id="ConfirmSt">

			<div class="inner">
				<p><?php echo sprintf( __( 'You are about to delete <strong>%s</strong>.' ), '' ); ?></p>
				<p><span class="spinner"></span></p>
				<input type="hidden" id="confirm_remove_id" value="" />
			</div>

			<div class="alignleft">
				<input type="button" class="button-secondary" id="button_remove_cancel" value="<?php _e( 'Cancel' ); ?>"  />
			</div>

			<div class="alignright">
				<input type="button" class="button-primary" id="button_remove_do" value="<?php _e( 'Continue' ); ?>"  />
			</div>

		</div>

	</div>
	
	<div id="bulk_form">
		<form id="<?php echo $coppi->Plugin->ltd; ?>_bulk_form" class="<?php echo $coppi->Plugin->ltd; ?>_form" method="post" action="<?php echo $coppi->Helper->get_action_link(); ?>">
		
			<input type="hidden" name="<?php echo $coppi->Form->field; ?>" value="Y">
			<?php wp_nonce_field( $coppi->Form->nonce . 'bulk' , $coppi->Form->nonce . 'bulk' ); ?>

		</form>
	</div>

</div>

<script type="text/javascript">
jQuery(document).ready(function($){
	
	$('#button_edit_memo').on('click', function( ev ) {
		
		$('#edit_memo').slideToggle();
		
	});
	
	$('#button_add_custom_option').on('click', function( ev ) {
		
		$('#add_custom_option').slideToggle();
		
	});
	
	$('#button_add_category').on('click', function( ev ) {
		
		$('#add_categories').slideToggle();
		
	});
	
	$('#button_update_category').on('click', function( ev ) {
		
		$('#update_categories').slideToggle();
		
	});
	
	$('.list_categories .list_show').on('click', function( ev ) {
		
		$(this).parent().siblings('.list-options').slideToggle();
		
	});
	
	$('.list-options .button_edit_option').on('click', function( ev ) {
		
		var $tr_el = $(this).parent().parent().parent().parent().parent();
		
		$tr_el.find('.edit').show();
		$tr_el.find('.show').hide();

	});
	
	$('.list-options .button_remove_option').on('click', function( ev ) {
		
		var $tr_el = $(this).parent().parent().parent().parent().parent();
		var option_id = $tr_el.data('option_id');
		var option_name = $tr_el.find('.option_name .show').text();
		var $ConfirmSt = $('#ConfirmSt');
		
		$ConfirmSt.find('.inner strong').text( option_name );
		$ConfirmSt.find('.inner #confirm_remove_id').val( option_id );

		tb_show( '<?php _e( 'Confirm Deletion' ); ?>' , '#TB_inline?height=140&width=240&inlineId=Confirm' , '' );
		
	});
	
	$(document).on('click', '#button_remove_cancel', function( ev  ) {
		
		var $ConfirmSt = $('#ConfirmSt');

		$ConfirmSt.find('.inner #confirm_remove_id').val( '' );

		tb_remove();
		
	});
	
	$(document).on('click', '#button_remove_do', function( ev  ) {
		
		var $ConfirmSt = $('#ConfirmSt');
		var option_id = $ConfirmSt.find('#confirm_remove_id').val();
		
		$ConfirmSt.find('.inner .spinner').show();

		var PostData = {
			action: '<?php echo $coppi->Plugin->ltd; ?>_remove_do',
			<?php echo $coppi->Form->nonce; ?>remove_do: '<?php echo wp_create_nonce( $coppi->Form->nonce . 'remove_do' ); ?>',
			data: {
				option_id: option_id
			}
		};
		
		$.ajax({

			type: 'post',
			url: ajaxurl,
			data: PostData

		}).done(function( xhr ) {

			response = xhr.data;

			if( xhr.success ) {
				
				$('.list-options .wp-list-table #tr_' + option_id).fadeOut();
				tb_remove();
				
			} else {
				
				if( response.errors.empty_data ) {
					
					alert( response.errors.empty_data );
					
				}
				
			}

		}).always(function() {
			
			$ConfirmSt.find('.inner .spinner').hide();
			
		});

	});
	
	$('.list-options .button_update_option').on('click', function( ev ) {
		
		var $tr_el = $(ev.target).parent().parent().parent().parent();
		var option_id = $tr_el.data('option_id');
		var option_name = $tr_el.find('.edit .option_name').val();
		var option_value = $tr_el.find('.edit .option_value').val();
		var cat_id = $tr_el.find('.edit .cat_id').val();

		$tr_el.find('.spinner').show();

		var PostData = {
			action: '<?php echo $coppi->Plugin->ltd; ?>_update_do',
			<?php echo $coppi->Form->nonce; ?>update_do: '<?php echo wp_create_nonce( $coppi->Form->nonce . 'update_do' ); ?>',
			data: {
				option_id: option_id,
				option_name: option_name,
				option_value: option_value,
				cat_id: cat_id
			}
		};

		$.ajax({

			type: 'post',
			url: ajaxurl,
			data: PostData

		}).done(function( xhr ) {

			response = xhr.data;
			
			if( xhr.success ) {
				
				$tr_el.addClass('saved');
				
			} else {
				
				if( response.errors.empty_option_name ) {
					
					alert( response.errors.empty_option_name );
					
				} else if( response.errors.empty_option_value ) {
					
					alert( response.errors.empty_option_value );
					
				} else if( response.errors.dupilicate_option_name ) {
					
					alert( response.errors.dupilicate_option_name );
					
				}
				
			}

		}).always(function() {
			
			$tr_el.find('.spinner').hide();
			
		});

	});
	
	$('.list-options .bulk-action-selector').on('change', function( ev ) {
		
		var $selector = $(ev.target);
		var select_bulk = $(ev.target).val();
		
		if( select_bulk && select_bulk == 'change_cat' ) {
			
			$selector.parent().siblings('.bulk_change_cat').show();

		} else {
			
			$selector.parent().siblings('.bulk_change_cat').hide();
			
		}
		
	});
	
	$('.list-options .button_update_bulk').on('click', function( ev ) {
		
		var $bulk_form = $(ev.target).parent();
		var $list_table = $bulk_form.parent().parent().parent();

		var bulk_selector = $bulk_form.find('.bulk-action-selector').val();
		var bulk_cat_from = $bulk_form.find('.bulk_cat_from').val();
		var bulk_cat_to = $bulk_form.find('.bulk_cat_to').val();
		
		if( bulk_selector == '' ) {

			return false;
			
		}
		
		var option_ids = [];
		
		$list_table.find('tbody tr').each( function( index , tr_el ) {
			
			var $tr_el = $(tr_el);
			var checked = $tr_el.find('.check-column input[type=checkbox]').prop('checked');
			
			if( checked ) {
				
				option_ids.push( $tr_el.data('option_id') )
				
			}
				
		});
		
		if( option_ids.length < 1 ) {
			
			return false;
			
		}
		
		$bulk_form.find('.spinner').show();
		
		$.each(option_ids,  function( index , option_id ) {
			
			$bulk_form.append( '<input type="hidden" name="data[bulk_update][ids][]" class="bulk_update_ids" value="' + option_id + '" />' );
			
		});
		
		if ( window.confirm( '<?php _e( 'Are you sure you want to bulk action?' , $coppi->Plugin->ltd ); ?>' ) ) {
			
			$bulk_form.submit();
			
		} else {
		
			$(document).find('.list-options .bulk_update_ids').remove();
			$bulk_form.find('.spinner').hide();
		
		}
		
	});
	
});
</script>
