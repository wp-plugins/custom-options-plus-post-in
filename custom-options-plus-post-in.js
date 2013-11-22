jQuery(document).ready(function($) {

	// update table
	var $UpdateTable = $(".update_table");
	var $UpdateTr = $("table tbody tr" , $UpdateTable );

	// update category
	var $UpdateCat = $("form#coppi_update_cat");

	// show
	$("a.show" , $UpdateTable ).click(function() {
		var $Lists = $(this).parent().parent().children(".lists");
		$Lists.slideToggle();
		
		return false;
	});

	// update line
	$(document).on("submit", 'form[name=save_line]', function () {

		if( coppi.UPFN = 'Y' ) {

			var $SaveLineTr = $(this).parent().parent().parent();
			$SaveLineTr.children(".operation").find(".spinner").show();
			
			var PostData = {
				action: 'coppi_update_line',
				nonce: $("input[name=_wpnonce]", this).val(),
				UPFN: 'Y',
				data: {
					option_id:    $("input[name='data[update][option_id]']", this).val(),
					option_name:  $SaveLineTr.children(".option_name").find("input[name='data[update][option_name]']").val(),
					cat_id:       $SaveLineTr.children(".option_name").find("select[name='data[update][cat_id]'] :selected").val(),
					option_value: $SaveLineTr.children(".option_value").find("textarea[name='data[update][option_value]']").val()
				}
			};
			
			$.post(coppi.ajax_url, PostData, function( response ) {
				if( typeof( response ) != 'string' && !response.success ) {
					
					$dialog = $('<div id="dialog" />').html( '<div style="padding: 0 20px;">' + response.data.msg + '</div>' ).appendTo("body");
					
					$dialog.dialog({
						dialogClass  : 'wp-dialog',
						modal        : true,
						autoOpen     : false,
						closeOnEscape: true,
						text         : 'closings',
						class        : 'primary',
						resizable    : false,
						width        : 320,
						height       : 'auto',
						zIndex       : 300000,
						title        : 'Error',
						buttons: {
							Close: function() {
								$( this ).dialog( "close" );
							}
						}
					}).dialog('open');
					$SaveLineTr.children(".operation").find(".spinner").hide();
					
				} else {
					$SaveLineTr.replaceWith( response );
				}
				
			});
		}
		
		return false;
	});

	// confirm 
	$(document).on("click", '.update_table a.delete', function( $el ) {
		
		var DeleteID = $(this).parent().parent().parent().find("input[name='data[update][option_id]']").val();
		var $ConfDlg = $("#Confirm #ConfirmSt");

		var $DelName = $(this).parent().parent().parent().parent().children('td.option_name').children('div.on').text();
		$( ".inner" , $ConfDlg ).children('p').children('strong').text( $DelName );
		$( ".inner" , $ConfDlg ).find('input[name=delete_id]').val( DeleteID );

		var $DelTitle = $(this).attr("title");
		tb_show( $DelTitle , '#TB_inline?height=100&width=240&inlineId=Confirm' , '' );
		
		return false;
	});
	
	// confirm delete
	$(document).on("click", "#confirm_deletebtn", function( event ) {

		$(event.target).parent().parent().find(".spinner").show();
		var DeleteID = $(this).parent().parent().find("input[name=delete_id]").val();
		var PostData = {
			action: 'coppi_delete_line',
			nonce: $(this).parent().parent().find("input[name=_wpnonce]").val(),
			data: {
				option_id: DeleteID
			}
		};

		$.post(coppi.ajax_url, PostData, function( response ) {
			if( typeof( response ) != 'string' && response.success ) {
					
				$(event.target).parent().parent().find(".spinner").hide();
				tb_remove();
				$(".update_table", document).find("tr[id=tr_" + DeleteID + "]").slideUp();
				
			}
			
		});

		return false;
	});

	// delete cancel
	$(document).on("click", "a#cancelbtn", function() {
		tb_remove();
	});

	// edit
	$(document).on("click", '.update_table a.edit', function () {
		var $ParentTr = $(this).parent().parent().parent().parent();
		$ParentTr.children("td").each(function() {
			if( 0 < $(".on" , this).size() ) {
				$(".on" , this).hide();
				$(".off" , this).show();
			}
		});

		return false;
	});

	// category edit
	$( "select" , $UpdateCat ).change(function() {
		var SelectedVal = $(this).val();
		
		if( SelectedVal != "" ) {
			var CatID = $(this).val();
			var CatLabel = $( "option:selected" , this).text();
			$( "#cat_edit_id" , $UpdateCat ).val( CatID );
			$( "#cat_edit_name" , $UpdateCat ).val( CatLabel );
			$( "#cat_current_name" , $UpdateCat ).val( CatLabel );
			$( "a.delete" , $UpdateCat ).attr( "href" , coppi.url + '&delete_cat=' + CatID );
			$( ".category_edit" , $UpdateCat ).slideDown();
		} else {
			$( "#cat_edit_id" , $UpdateCat ).val( "" );
			$( "#cat_edit_name" , $UpdateCat ).val( "" );
			$( "#cat_current_name" , $UpdateCat ).val( "" );
			$( "a.delete" , $UpdateCat ).attr( "href" , "" );
			$( ".category_edit" , $UpdateCat ).slideUp();
		}
	});


	// bulk select 
	$( "select[name=bulkaction]" , $UpdateTable ).change(function() {
		var SelectedVal = $(this).val();
		if( SelectedVal == 'change_cat' ) {
			$( ".bulk_change_cat", $(this).parent() ).show();
		} else {
			$( ".bulk_change_cat", $(this).parent() ).hide();
		}
	});

	// bulk action 
	$(".tablenav .action" , $UpdateTable ).click(function() {
		var BulkAction = $(this).parent().children( "select[name=bulkaction]" ).val();
		var $Table = $(this).parent().parent().children("table");

		if( BulkAction ) {
			var Checked = new Array();
			$Table.children("tbody").children("tr").each(function() {
				$CheckInput = $(this).children("th.check-column").children("input");
				if( $CheckInput.attr("checked") ) {
					Checked.push( $CheckInput.val() );
				}
			});

			if( Checked.length ) {
				if ( confirm( coppi.confirm_message ) ) {
					var BulkForm = '';
					if( BulkAction == 'delete' ) {
						BulkForm = $('<form method="post" action="' + coppi.url + '"></form>');
						BulkForm.html( '<input type="hidden" name="bulk" value="delete" />' );
					} else {
						BulkForm = $('<form method="post" action="' + coppi.url + '"></form>');
						BulkForm.html( '<input type="hidden" name="bulk" value="change_cat" />' );
						BulkForm.append( '<input type="hidden" name="to" value="' + $(this).parent().children(".bulk_change_cat").children("select[name=cat_to]").val() + '" />' );
					}
					$.each(Checked, function( index, value) {
						BulkForm.append( '<input type="hidden" name="data[option_id][]" value="' + value + '" />' );
					});
					BulkForm.appendTo($('body'));
					BulkForm.submit();
				}
			}
		}
		
		return false;
	});

	// memo
	$("a.meno_edit" ).click(function() {
		$("#postbox-container-1 .postbox.memo").slideToggle();
		return false;
	});

	// donate toggle
	function donation_toggle_set( s ) {
		if( s ) {
			$(".columns-2").addClass('full-width');
		} else {
			$(".columns-2").removeClass('full-width');
		}
	}

	$(".columns-2 #postbox-container-2 .toggle-plugin .icon a" ).click(function() {

		if( $(".columns-2").hasClass('full-width') ) {
			donation_toggle_set( false );
			$.post(ajaxurl, {
				'action': 'coppi_set_donation_toggle',
				'f': 0,
			});

		} else {
			donation_toggle_set( true );
			$.post(ajaxurl, {
				'action': 'coppi_set_donation_toggle',
				'f': 1,
			});
		}

		return false;
	});

});
