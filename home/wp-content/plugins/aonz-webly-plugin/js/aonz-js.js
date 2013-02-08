var jq = jQuery;

jq(document).ready(function() {
	
	// register for register button
	jq("[href='#tutor_register']").click(function() {

//		jq.modal("<div><h1>Aonz SimpleModal</h1></div>");
		
		jq('#tutor_register_modal').modal(
		{
			overlayId: 'aonz-simplemodal-login-overlay',
			containerId: 'aonz-simplemodal-login-container',
			opacity:85,
			//onShow: SimpleModalLogin.show,
			position: ['15%', null],
			zIndex:10000
		});
	});
	
	/* Comment form submit */
	jq("#commentform input#submit").click(function() {

		var content = jq("textarea#whats-new").val();

		jq.post(ajaxurl, {
			action : 'add_activity_for_comment_post',
			'content' : content,
		}, function(response) {

			//alert("response" + response);
		});
		// alert("Aonz");
		// return false; //This will cancel submit form
	});
});
