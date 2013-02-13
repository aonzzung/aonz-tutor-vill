var jq = jQuery;

jq(document).ready(function() {

	// register for register button
	jq("[href='#tutor_register']").click(function() {

		jq('#tutor_register_modal').modal({
			overlayId : 'aonz-simplemodal-overlay',
			containerId : 'aonz-simplemodal-container',
			opacity : 85,
			// onShow: SimpleModalLogin.show,
			position : [ '15%', null ],
			zIndex : 10000
		});
	});

	/**
	 * Student Registration
	 */
	jq("[href='#student_register']").click(function() {
		
		jq("#student_register_empty_area #student_register_modal").html(""); //Empty drawing area for student register modal
		jq("#student_register_empty_area #student_register_modal").html(jq("#student_register_modal_wrapper").html()); //Get form
		
		jq("#student_register_empty_area #student_register_form").validate({
				rules: {
					name: {
						required: true,
						maxlength: 50
					},
					email: {
						email: true,
						maxlength: 100
					},
					phone: {
						required: true,
						maxlength: 10,
						digits: true
					},
					level: {
						required: true,
						minlength: 1
					},
					student_number: {
						required: true,
						minlength: 1
					},
					detail: {
						required: true,
						maxlength: 500
					},
					location: {
						required: true,
						maxlength: 500
					},
					other: {
						maxlength: 500
					},
				},
				messages: {
					name: {
						required:"Please enter your name",
					},
					email: {
						required:"Please enter a valid email address"
					},
					phone: {
						required: "Please enter your phone number",
						maxlength: "Your phone number must not be more than 10 digits"
					},
					level: {
						required: "Please enter student level",
					},
					student_number: {
						required: "Please enter the number of students",
					},
					detail: {
						required: "Please enter detail",
						maxlength: "Only 500 charactors are allowed to enter",
					},
					location: {
						required: "Please enter location",
						maxlength: "Only 500 charactors are allowed to enter",
					},
					other: {
						maxlength: "Only 500 charactors are allowed to enter",
					},
				},
				submitHandler: function(form) 
				{
					 jq.post(AonzAjax.ajaxurl, {
							action : 'aonz_student_registration',
							'name' : jq("#student_register_modal #student_register_form #cname").val(),
							'email' : jq("#student_register_modal #student_register_form #cemail").val(),
							'phone' : jq("#student_register_modal #student_register_form #cphone").val(),
							'level' : jq("#student_register_modal #student_register_form #clevel").val(),
							'student_number' : jq("#student_register_modal #student_register_form #cstudent_number").val(),
							'detail' : jq("#student_register_modal #student_register_form #cdetail").val(),
							'location' : jq("#student_register_modal #student_register_form #clocation").val(),
							'other' : jq("#student_register_modal #student_register_form #cother").val(),
						}, function(response) {
				
							jq("#student_register_modal #student_register_form").hide();
							if(response=="success")
							{
								jq("#student_register_modal #request_success").show();
							}
							else
							{
								jq("#student_register_modal #request_fail").show();
							}
						});
				 }
		});
		
		jq('#student_register_empty_area #student_register_modal').modal({
			overlayId : 'aonz-simplemodal-overlay',
			containerId : 'aonz-simplemodal-container',
			opacity : 85,
			// onShow: SimpleModalLogin.show,
			position : [ '15%', null ],
			zIndex : 10000
		});
	});
});

