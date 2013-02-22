var jq = jQuery;

jq(document).ready(function() {

	/* ==============================================
	 * ======= Tutor List ===================
	 * ==============================================
	 */
	jq("[href='#tutor_list']").click(function() {

		jq('#tutor_list_modal').modal({
			overlayId : 'aonz-simplemodal-overlay',
			containerId : 'aonz-simplemodal-container',
			opacity : 85,
			// onShow: SimpleModalLogin.show,
			position : [ '15%', null ],
			zIndex : 10000
		});
	});
	
	/* ==============================================
	 * ======= Tutor registration ===================
	 * ==============================================
	 */
	/* Signup with facebook button*/
	jq("#signupwithfb_button").click(function() {
		
		// Validate form
		var isValid = jq("#tutor_registration_modal").valid();
		if(isValid)
		{
			showInstaPopup();
		}
	});
	
	
	jq("[href='#tutor_register']").click(function() {

		jq('#tutor_registration_modal').modal({
			overlayId : 'aonz-simplemodal-overlay',
			containerId : 'aonz-simplemodal-container',
			opacity : 85,
			onShow: function() {
				attachTutorRegistrationFormValidation();
            },
			overlayClose: true,
			position : [ '15%', null ],
			zIndex : 10000
		});
	});
	/*=====END! Tutor registration ===================*/
	
	/* ==============================================
	 * ======= Student - Class Registration =========
	 * ==============================================
	 */
	// Submit button
	jq("#submit_student_registration_button").click(function() {
		
		// Validate form
		var isValid = jq("#student_register_form").valid();
		if(isValid)
		{
			 jq.post(AonzAjax.ajaxurl, {
					action : 'aonz_student_registration',
					'name' : jq("#student_register_modal #student_register_form #cname").val(),
					'email' : jq("#student_register_modal #student_register_form #cemail").val(),
					'phone' : jq("#student_register_modal #student_register_form #cphone").val(),
					'level' : jq("#student_register_modal #student_register_form #clevel").val(),
					'study_program' : jq("#student_register_modal #student_register_form input[name='study_program']:checked").val(), 
					'student_number' : jq("#student_register_modal #student_register_form #cstudent_number").val(),
					'detail' : jq("#student_register_modal #student_register_form #cdetail").val(),
					'location' : jq("#student_register_modal #student_register_form #clocation").val(),
					'other' : jq("#student_register_modal #student_register_form #cother").val(),
					'rate' : jq("#student_register_modal #student_register_form #rate").val(),
					'cptch_result' : jq("#student_register_modal #student_register_form input[name='cptch_result']").val(),
					'cptch_number' : jq("#student_register_modal #student_register_form input[name='cptch_number']").val()
				}, function(response) {
		
					if(response=="success")
					{
						jq("#student_register_modal #student_register_form").hide();
						jq("#student_register_modal #request_success").show();
					}
					else if(response=="captcha_fail")
					{
						alert("ตัวเลขป้องกันความปลอดภัยไม่ถูกต้อง โปรดลองใหม่อีกครั้ง");
					}
					else
					{
						alert("ข้อมูลที่กรอกไม่ถูกต้อง กรุณากรอกแบบฟอร์มใหม่อีกครั้ง ทางทีมงานขออภัยในความไม่สะดวก");
					}
				});
		}
	});
	
	//Open Class Registration
	jq("[href='#student_register']").click(function() {
		
		jq("#clevel").change(function () {
			studyChangeHandler();
		});
		
		jq("input[name='study_program']").change(function () {
			studyChangeHandler();
		});
		
		jq('#student_register_modal').modal({
			overlayId : 'aonz-simplemodal-overlay',
			containerId : 'aonz-simplemodal-container',
			opacity : 85,
			onShow: function(){
				attachStudentRegistrationFormValidation();
			},
			overlayClose: true,
			position : [ '15%', null ],
			zIndex : 10000
		});
	});
	
	/*
	 * ==============================
	 * ===== Job List ===============
	 * ==============================
	 */
	jq("[href='#job_list']").click(function() {

		jq('#job_list_modal').modal({
			overlayId : 'aonz-simplemodal-overlay',
			containerId : 'aonz-simplemodal-container',
			opacity : 85,
			// onShow: SimpleModalLogin.show,
			position : [ '15%', null ],
			zIndex : 10000
		});
		
		jq("#job_list_modal #gifloader").show();
		jq("#job_list_modal .modal-close-button").hide();
		
		jq.post(AonzAjax.ajaxurl, {
			action : 'get_job_list',
		}, function(response) {
			jq("#gifloader").hide();
			jq("#job_list_modal #job_list_content").html(response);
			jq("#job_list_modal .modal-close-button").show();
		});
	});
	
	
	
	
	/*######################################################################*/
	/*=== Admin Side ===*/
	/*######################################################################*/
	//TODO
});
/*=== END! jq(document).ready ===*/

/* ==== Functions ==== */
function studyChangeHandler()
{
	var rate = 0;
	  var level = jq("#student_register_modal select[name='level']").val();
	  switch(level)
	  {
	  	case "1":
	  		rate=250;
	  		break;
	  	case "2":
	  		rate=300;
	  		break;
	  	case "3":
	  		rate=300;
	  		break;
	  	case "4":
	  		rate=400;
	  		break;
	  	case "5":
	  		rate=400;
	  		break;
	  	case "6":
	  		rate=500;
	  		break;
	  	case "7":
	  		rate=500;	
	  		break;
	  }
	  var study_program = jq("#student_register_modal input[name='study_program']:checked'").val();
	  var stdsuffix = "";
	  if(study_program=="en")
	  {
		  rate = rate + 100;// Add 100Baht for English Program
		  stdsuffix = "(หลักสูตรภาษาอังกฤษ)";
	  }
	  jq("span#rate_hr").html("อัตราค่าเรียน " + rate + " บาท/คน/ชม." + " " + stdsuffix);
}


/**
 * Callback function for tutor registration
 */
function tutor_register_callback()
{
	alert("signed up");
}

function attachTutorRegistrationFormValidation()
{
	// Add validation to tutor registration form
	jq("#tutor_registration_modal").validate({
		rules: {
			nickname: {
				required: true,
				maxlength: 50
			},
			phonenum: {
				required: true,
				maxlength: 10,
				digits: true
			},
		},
		messages: {
			nickname: {
				required:"Please enter your nickname",
			},
			phonenum: {
				required: "Please enter your phone number",
				maxlength: "Your phone number must not be more than 10 digits"
			},
		}
	});
}

function attachStudentRegistrationFormValidation()
{
	var form_name="#student_register_form";
	attachStudentRegistrationFormValidation(form_name);
}

function attachStudentRegistrationFormValidation(form_name)
{
	jq(form_name).validate({
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
		}
	});
}