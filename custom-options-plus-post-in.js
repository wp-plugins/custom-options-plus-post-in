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

	// delete 
	$("a.delete" , $UpdateTable ).click(function() {
		var $ConfDlg = $("#Confirm #ConfirmSt" , $UpdateTable );

		var $DelUrl = $(this).attr("href");
		$( "a#deletebtn" , $ConfDlg ).attr( "href" , $DelUrl );

		var $DelName = $(this).parent().parent().parent().parent().children('td.option_name').children('div.on').text();
		$( ".inner" , $ConfDlg ).children('p').children('strong').text( $DelName );

		var $DelTitle = $(this).attr("title");
		tb_show( $DelTitle , '#TB_inline?height=100&width=240&inlineId=Confirm' , '' );
		
		return false;
	});

	$("a#cancelbtn").click(function() {
		tb_remove();
	});

	// edit
	$( "a.edit" , $UpdateTr ).click(function() {
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
