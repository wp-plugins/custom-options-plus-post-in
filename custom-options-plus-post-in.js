jQuery(document).ready(function($) {

	var $Form = $("#coppi_form");
	
	// create submit
	$("p.submit input:button").click(function() {
		$Form.submit();
	});

	// update
	var $UpdateTr = $Form.children("div#update").children("table").children("tbody").children("tr");
	$UpdateTr.children("td.create-date").children("input").hide();
	$UpdateTr.children("td.key").children("input").hide();
	$UpdateTr.children("td.val").children("textarea").hide();
	$UpdateTr.children("td.operation").children("p.submit").hide();

	$UpdateTr.children("td.operation").children("span").children("a.edit").click(function() {
		var $ParentTr = $(this).parent().parent().parent();
		$ParentTr.children("td.create-date").children("input").val('');
		$ParentTr.children("td.val").children("span").hide();
		$ParentTr.children("td.val").children("textarea").show();
		$(this).parent().hide();
		$(this).parent().parent().children("p.submit").show();
		
		return false;
	});

});
