jQuery(document).ready(function($) {

	// update table
	var $UpdateTable = $(".update_table");
	var $UpdateTr = $("table tbody tr" , $UpdateTable );


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

});
