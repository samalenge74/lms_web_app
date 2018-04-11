$(document).ready(function() {

	$('form#rPass input#submit').attr('disabled', true);

	$('option', 'form#applyLeave select#fhDay').remove();

	$('option', 'form#applyLeave select#medCert').remove();

	$("form#formType select#type").change(function(event){
	 	event.preventDefault();
        $("form#formType").LoadingOverlay("show");
	 	var type = $("form#formType select#type option:selected").val();
	 	var sDate = $("form#formType input#dFrom").val();
	 	var eDate = $("form#formType input#dTo").val();
                if ( type === ""){
                    $("form#formType").LoadingOverlay("hide", true);
                    $("div#histContent").html('');
                    return false;
                }else{
                    $.ajaxSetup ({
                cache: false
                });

                var dataString = {type: type, sDate: sDate, eDate: eDate};
                $.ajax({
                    type: 'post',
                    url: './../functions/fullReport.php',
                    data: dataString,
                    success: function(response){
                        $("form#formType").LoadingOverlay("hide", true);
                        $("div#histContent").html(response);
                    },
                    error: function(ob,errStr) {
                        $("form#formType").LoadingOverlay("hide", true);
                        swal("Problem!", "There was an error sending your request. Please try again.", "error");
                    }
            });
    	}

	 });

	$("form#login button#submit").click(function(event) {
            event.preventDefault();
            var isFocus=0;
            var isError=0;
            var username = $('form#login input#username').val();
            var password = $('form#login input#password').val();
            var url = "pages/leaveRequest.php";

            if(username==="") {
                $('form#login label#errorUsername').html('Username is required');
                $('form#login input#username').focus();
                isFocus=1;
                isError=1;
            }

            if(password==="") {
                $('form#login label#errorPassword').html('Password is required');
                $('form#login input#password').focus();
                isFocus=1;
                isError=1;
            }

            // Terminate the script if an error is found
            if(isError===1) {
                    $('form#login label#formProgress').html('');
                    $('form#login label#formProgress').hide();
                   // Activate the submit button
                    $('form#login input#submit').removeAttr("disabled");
                    return false;
            }

            $.ajaxSetup ({
                    cache: false
            });

            var dataString = {username: username, password: password};

            $.ajax({
                type: 'post',
                url: 'functions/login.php',
                data: dataString,
                success: function(response){

                    if (response === "1"){
                        $(location).attr('href',url);
                    }else{
                        if (response === ""){
                            swal("Sorry!", "Your username and password do not match.", "error");
                            return false;
                        }else{
                            swal("Problem!", "There was a problem with the login process. Please contact the system administrator.", "error");
                            return false;
                        }
                    }

                },
               error: function(ob,errStr) {
                        $('form#login label#formProgress').html('');
                        swal("Problem!", "There was an error sending your request. Please try again.", "error");
                        // Activate the submit button
                        $('form#LOGIN input#submit').removeAttr("disabled");
                }
            });

         });

    $("form#admin-signin input#submit").click(function(event) {
        event.preventDefault();
        var isFocus=0;
        var isError=0;
        var username = $('form#admin-signin input#username').val();
        var password = $('form#admin-signin input#password').val();
        var url = "admin/dashboard.php";

        if(username==="") {
            isFocus=1;
            isError=1;
        }

        if(password==="") {
            isFocus=1;
            isError=1;
        }

        // Terminate the script if an error is found
        if(isError===1) {

                alert('Username and Password are required');
                $('form#login label#formProgress').html('');
                $('form#login label#formProgress').hide();
               // Activate the submit button
                $('form#login input#submit').removeAttr("disabled");
                return false;
        }

        $.ajaxSetup ({
                cache: false
        });

        var dataString = {username: username, password: password};

        $.ajax({
            type: 'post',
            url: 'functions/login-admin.php',
            data: dataString,
            success: function(response){

                if (response === "1"){
                    $(location).attr('href',url);
                }else{
                    if (response === ""){
                        alert('Either your username and password do not match or you are not a an administrator');
                        return false;
                    }else{
                       alert('There was a problem with the login process. Please contact the system administrator.');
                        return false;
                    }
                }

            },
           error: function(ob,errStr) {
                    $('form#login label#formProgress').html('');
                    alert('There was a system error. Please try again.');
                    // Activate the submit button
                    $('form#LOGIN input#submit').removeAttr("disabled");
            }
        });

    });

    $("form#applyLeave select#type").change(function(event){
	 	event.preventDefault();
                //$("form#applyLeave").clearForm();
	 	var medCertOptions = {
		'0' : 'Select One',
                '1' : 'Yes',
                '2' : 'No'
                };
		var selected_medCert = '0';
		var select_medCert = $('form#applyLeave select#medCert');
		if(select_medCert.prop) {
		  var options = select_medCert.prop('options');
		}
		else {
		  var options = select_medCert.attr('options');
		}
	 	var type = $("form#applyLeave select#type option:selected").val();
	 	if (type === ""){
	 		$('form#applyLeave input#lDays').val('');
	 		$('option', select_medCert).remove();
	 		return false;
	 	}
	 	if (type === "1"){
	 		{$.each(medCertOptions, function(val, text) {
			    options[options.length] = new Option(text, val);
			});
			select_medCert.val(selected_medCert);}
	 	}else{
	 		$('option', select_medCert).remove();
	 	}
	 	var dataString = {type: type};
        $.ajax({
            type: 'post',
            url: './../functions/getNumRemDays.php',
            data: dataString,
            success: function(response){
            	$('form#applyLeave input#lDays').val(response);

                if (response === '0.00'){
                    var t = $("form#applyLeave select#type option:selected").text();
                    $(':submit').attr("disabled", true);
                    swal("Oops...","You have "+ response + " " + t + " days.", "error");
                }else{
                    $(':submit').removeAttr("disabled");
                }
            }
         });
	});

    $('form#rPass input#pwd1').keyup(function() {
        var pwd1 = $(this).val();
        var pwd = $('form#rPass input#pwd').val();
        if (pwd1 === "") {
            $('label#errorPWD1').html('');
        }
        if (pwd1 !== pwd) {
            $('label#errorPWD1').html('Not a match!!!');
        } else {
            $('label#emailErrorPO').html('');
        }
    });

    $("form#rPass input#submit").on('click', function(event){

        if($('input[type=password]').val() == ""){
            alert("Both fields are required ");
            return false;
        }

        if($('label#errorPWD1').html() !=''){
            swal("Oops..","A field needs your attention!!!","error");
            return false;
        }

        var pwd = $('form#rPass input#pwd').val();
        var dataString = {pwd: pwd};

        $.ajax({
            type: 'post',
            url: './../functions/changePassword.php',
            success: function(response){
                if (response === '1'){
                        swal("Great","Password Successfully Changed.","success");
                        location.reload();

                }else{
                    swal("Oops..","There was an error sending your request. Please try again.", "error");
                    return false;
                }
            },
           error: function(ob,errStr) {

                    swal("Problem!", "There was a system error. Please contact the system administrator.", "error");
            }
        });

    });

	$("form#applyLeave input#eDate").change(function(event){
	 	event.preventDefault();

                var isFocus=0;
                var isError=0;
                var newOptions = {
                    '0' : 'Select One',
                    '1' : 'Full Day',
                    '2' : 'Half Day - Morning',
                    '3' : 'Half Day - Afternoon'
                    };
		var selected_fhDay = '0';
		var select_fhDay = $('form#applyLeave select#fhDay');
		if(select_fhDay.prop) {
		  var options = select_fhDay.prop('options');
		}
		else {
		  var options = select_fhDay.attr('options');
		}
	 	var sDate = $("form#applyLeave input#sDate").val();
	 	var eDate = $("form#applyLeave input#eDate").val();
	 	var type = $("form#applyLeave select#type option:selected").val();
	 	$('form#applyLeave label#errorSDate').html('');
	 	if(sDate=="") {
                $('form#applyLeave label#errorSDate').html('required');
                $('form#applyLeave input#sDate').focus();
                isFocus=1;
                isError=1;
        }
	 	// Terminate the script if an error is found
        if(isError==1) {
        		$('form#applyLeave label#formProgress').html('');
                $('form#applyLeave label#formProgress').hide();
                return false;
        }

        $.ajaxSetup ({
                cache: false
        });
        var dataString = {sDate: sDate, eDate: eDate, type: type};
        $.ajax({
            type: 'post',
            url: './../functions/getNumDays.php',
            data: dataString,
            success: function(response){
            	var arr = response.split(',');
            	if (arr[1] < 0){
            		swal("Problem","Insuffecient leave days!!","error");
            		return false;
            	}
            	$('form#applyLeave input#numDays').val(arr[0]);
            	if (arr[0] > 1){
        			$('option', select_fhDay).remove();
        			$('form#applyLeave input#sTime').val('00:00:00');
                	$('form#applyLeave input#eTime').val('00:00:00');
                	$('form#applyLeave label#reply').html('');
        		}else{
        			if (arr[0] == 1)
        			{
        				$.each(newOptions, function(val, text) {
					    options[options.length] = new Option(text, val);
						});
						select_fhDay.val(selected_fhDay);
					}
					$('form#applyLeave input#sTime').val('');
                	$('form#applyLeave input#eTime').val('');
                	$('form#applyLeave label#reply').html('');
        		}

            }
         });
	});

    $("form#applyLeave select#fhDay").change(function(event){
        event.preventDefault();
        var fhDay = $('form#applyLeave select#fhDay').val();
                if(fhDay == 0){
                $('form#applyLeave input#sTime').val('');
                $('form#applyLeave input#eTime').val('');
                return false;
        }

        $.ajaxSetup ({
                cache: false
        });
        var dataString = {fhDay: fhDay};
        $.ajax({
            type: 'post',
            url: './../functions/getHours.php',
            data: dataString,
            success: function(response){
                var arr = response.split(',');
                $('form#applyLeave input#sTime').val(arr[0]);
                        $('form#applyLeave input#eTime').val(arr[1]);
                        $('form#applyLeave input#hours').val(arr[2]);

         },
                error: function(ob,errStr) {

                swal("Problem!", "There was an error sending your request. Please try again.", "error");
                // Activate the submit button
          }
        });

    });

	$("form#applyLeave input#submit").click(function(event) {
        event.preventDefault();
        $("form#applyLeave input#submit").LoadingOverlay("show");
        var isFocus=0;
        var isError=0;
        var type = $('form#applyLeave select#type').val();
        var sDate = $('form#applyLeave input#sDate').val();
        var eDate = $('form#applyLeave input#eDate').val();
        var numDays = $('form#applyLeave input#numDays').val();
        var fhDay = $('form#applyLeave select#fhDay').val();
        var hours = $('form#applyLeave input#hours').val();
        var medCert = $('form#applyLeave select#medCert').val();
        var comments = $('form#applyLeave textarea#comments').val();
        var emplNum = $('form#applyLeave input#emplNum').val();

        if(type==="") {
                $('form#applyLeave label#errorType').html('select one');
                $('form#applyLeave input#type').focus();
                isFocus=1;
                isError=1;
        }
        if(sDate==="") {
                $('form#applyLeave label#errorSDate').html('required');
                $('form#applyLeave input#sDate').focus();
                isFocus=1;
                isError=1;
        }
                if(eDate=="") {
                $('form#applyLeave label#errorEDate').html('required');
                $('form#applyLeave input#eDate').focus();
                isFocus=1;
                isError=1;
        }
        if (numDays==="1" && fhDay===""){
                    $('form#applyLeave label#errorFHDay').html('select one');
                    $('form#applyLeave input#fhDay').focus();
                    isFocus=1;
                    isError=1;
                }

        if (type==="1" && medCert===""){
                    $('form#applyLeave label#errorMedCert').html('select one');
                    $('form#applyLeave select#medCert').focus();
                    isFocus=1;
                    isError=1;

                }

        // Terminate the script if an error is found
        if(isError===1) {
                $('form#applyLeave label#formProgress').html('');
                $('form#applyLeave label#formProgress').hide();
                // Activate the submit button
                $('form#applyLeave input#submit').removeAttr("disabled");
                $("form#applyLeave").LoadingOverlay("hide", true);
                return false;
        }
        $.ajaxSetup ({
                cache: false
        });
        var dataString = {type: type, sDate: sDate, eDate: eDate, numDays: numDays, hours:hours, fhDay: fhDay, medCert: medCert, comments: comments};

        $.ajax({
            type: 'post',
            url: './../functions/requestLeave.php',
            data: dataString,
            success: function(response){

                if (response == 1){
                    $("form#applyLeave input#submit").LoadingOverlay("hide", true);
                    $('form#applyLeave').get(0).reset();
                    $('option', 'form#applyLeave select#medCert').remove();
                    $('option', 'form#applyLeave select#fhDay').remove();
                    swal("Done!", "Leave Request successfull!", "success");
                }else{
                    $("form#applyLeave input#submit").LoadingOverlay("hide", true);
                    swal("Problem!", "There was an error sending your request. Please try again.", "error");
                }
            },
                error: function(ob,errStr) {
                    $("form#applyLeave input#submit").LoadingOverlay("hide", true);
                    swal("Problem!", "There was a system error. Please contact the system administrator.", "error");

            }
        });

    });

    $("form#lReqAct input#approve").click(function(event) {
        event.preventDefault();
        $("form#lReqAct").LoadingOverlay("show");
        var id = $('#lApplID').val();
        var url = './../pages/pendingDecision.php';

        $.ajaxSetup ({
                cache: false
        });
        var dataString = {id: id};
        
        $.ajax({
            type: 'post',
            url: './../functions/actionsLA.php',
            data: dataString,
            success: function(response){
                
                if (response === '1'){
                        $("form#lReqAct").LoadingOverlay("hide", true);
                        $( location ).attr("href", url);
                        swal("Done!", "Approved Successfully.", "success");
                }else{
                    $("form#lReqAct").LoadingOverlay("hide", true);
                    swal("Problem!", "There was an error sending your request. Please try again.", "error");
                    return false;
                }
            },
                error: function(ob,errStr) {
                    $("form#lReqAct").LoadingOverlay("hide", true);
                    swal("Problem!", "There was a system error. Please contact the system administrator.", "error");

            }
        });

    });

    $("form#lCancAct input#approve").click(function(event) {
        event.preventDefault();
        $("form#lCancActl").LoadingOverlay("show");
        var id = $('#lApplID').val();
        var url = './../pages/pendingDecision.php';

        $.ajaxSetup ({
                cache: false
        });
        var dataString = {id: id};

        $.ajax({
            type: 'post',
            url: './../functions/actionsCA.php',
            data: dataString,
            success: function(response){
                if (response === '1'){
                        $("form#lCancAct").LoadingOverlay("hide", true);
                        swal("Done!", "Approved Successfully.", "success");
                        $( location ).attr("href", url);
                }else{
                    $("form#lCancAct").LoadingOverlay("hide", true);
                    swal("Problem!", "There was an error sending your request. Please try again.", "error");
                    return false;
                }
            },
                error: function(ob,errStr) {
                    $("form#lCancAct").LoadingOverlay("hide", true);
                    swal("Problem!", "There was a system error. Please contact the system administrator.", "error");

            }
        });

    });

    $("form#lReqAct input#decline").click(function(event) {
        event.preventDefault();
        $("form#lReqAct").LoadingOverlay("show");
        var isFocus=0;
        var isError=0;

        var id = $('#lApplID').val();
        var reason = $("textarea#rDecline").val();
        var url = './../pages/pendingDecision.php';

        if (reason === ""){
            $('textarea#rDecline').focus();
            isError=1;
        }

        if (isError === 1){
            swal("Ooops!", "Provide a reason for declining.", "warning");
            $("form#lReqAct").LoadingOverlay("hide", true);
            return false;
        }

        $.ajaxSetup ({
                cache: false
        });
        var dataString = {id: id, reason: reason};

        $.ajax({
            type: 'post',
            url: './../functions/actionsLD.php',
            data: dataString,
            success: function(response){
                if (response === '1'){
                        $("form#lReqAct").LoadingOverlay("hide", true);
                        $( location ).attr("href", url);
                }else{
                    $("form#lReqAct").LoadingOverlay("hide", true);
                    swal("Problem!", "There was an error sending your request. Please try again.", "error");
                    return false;
                }
            },
                error: function(ob,errStr) {
                    $("form#lReqAct").LoadingOverlay("hide", true);
                    swal("Problem!", "There was a system error. Please contact the system administrator.", "error");

            }
        });

    });

    $("form#lCancAct input#decline").click(function(event) {
        event.preventDefault();
        $("form#lCancAct").LoadingOverlay("show");
        var isFocus=0;
        var isError=0;

        var id = $('#lApplID').val();
        var reason = $("textarea#rDecline").val();
        var url = './../pages/pendingDecision.php';

        if (reason === ""){
            $('textarea#rDecline').focus();
            isError=1;
        }

        if (isError === 1){
            $("form#lCancAct").LoadingOverlay("hide", true);
            swal("Ooops!", "Provide a reason for declining.", "warning");
            return false;
        }

        $.ajaxSetup ({
                cache: false
        });
        var dataString = {id: id, reason: reason};

        $.ajax({
            type: 'post',
            url: './../functions/actionsCD.php',
            data: dataString,
            success: function(response){
                if (response === '1'){
                        $("form#lCancAct").LoadingOverlay("hide", true);
                        $( location ).attr("href", url);
                }else{
                    $("form#lCancAct").LoadingOverlay("hide", true);
                    swal("Problem!", "There was an error sending your request. Please try again.", "warning");
                    return false;
                }
            },
                error: function(ob,errStr) {
                    $("form#lCancAct").LoadingOverlay("hide", true);
                    swal("Problem!", "There was a system error. Please contact the system administrator.", "error");
                    return false;

            }
        });

    });

    $("input#logout").click(function(event){
    	$.ajax({
	            type: 'get',
	            url: './../functions/logout.php',
	            success: function(response){
	            	$(location).attr('href',response);
	            },
	           error: function(ob,errStr) {
	                    $('form#formType label#formProgress').html('');
	                    swal("Oops..","There was an error in your request.","error");
           		}
        	});
    });

	$('#calendar').fullCalendar({

		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},

        editable: false,
        navLinks: false,
        eventLimit: true,

        events: {
        	url: "./../functions/json-events.php",
        	error: function(){
        		$('#script-warning').show();
        	}
        },

        timeFormat: '',

        eventDrop: function(event, delta) {
            alert(event.title + ' was moved ' + delta + ' days\n' +
                '(should probably update your database)');
        },

        loading: function(bool) {
            $('#loading').toggle(bool);
        }

    });

    $("form#applyLeave button#resetting").on('click', function(event){
        event.preventDefault();
        $("form#applyLeave button#resetting").LoadingOverlay("show");
        $('form#applyLeave').get(0).reset();
        $('option', 'form#applyLeave select#medCert').remove();
        $('option', 'form#applyLeave select#fhDay').remove();
        $("form#applyLeave button#resetting").LoadingOverlay("hide", true);
    })

    $("form#rPass button#resetting").on('click', function(event){
        event.preventDefault();
        $("form#rPass button#resetting").LoadingOverlay("show");
        $('form#rPass').get(0).reset();
        $("form#rPass button#resetting").LoadingOverlay("hide", true);
    })

    $('table.display').DataTable();

    $('#cancelLeave tbody').on('click', 'td.delete', function(event) {

        event.preventDefault();
        var id = $(this).find('input.lID').val();
        var tr = $(this).parents('tr');
        var reason = tr.find('textarea.reason').val();
        $(this).LoadingOverlay("show");
        swal({
          title: "Are you sure?",
          text: "This is subject to approval by your manager!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "No, cancel!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function(isConfirm){
          if (isConfirm) {
            
            if (reason == ""){
                swal("Something is missing","Provide a reason for cancelling this leave!!!","error");
                return false;
            }else{
                
                $.ajaxSetup ({
                    cache: false
                });
                var dataString = {id: id, reason: reason};

                $.ajax({
                    type: 'post',
                    url: './../functions/requestCancellation.php',
                    data: dataString,
                    success: function(response){

                        if (response === '1'){
                            $(this).LoadingOverlay("hide", true);
                            swal("Done!", "The request was Successfully submitted.", "success");
                            location.reload();

                        }else{
                            $(this).LoadingOverlay("hide", true);
                            swal("Problem!", "There was an error sending your request. Please try again.", "error");
                            return false;
                        }
                    },
                        error: function(ob,errStr) {
                            $(this).LoadingOverlay("hide", true);
                            swal("Problem!", "There was a system error. Please contact the system administrator.", "error");
                            return false;

                    }
                });

            }

          } else {
                swal("Cancelled", "Request not submitted :)", "error");
          }
        });

    });

    $(".toggle-accordion").on("click", function() {
        var accordionId = $(this).attr("accordion-id"),
        numPanelOpen = $(accordionId + ' .collapse.in').length;

        $(this).toggleClass("active");

        if (numPanelOpen == 0) {
            openAllPanels(accordionId);
        } else {
            closeAllPanels(accordionId);
        }
    })

    openAllPanels = function(aId) {
        console.log("setAllPanelOpen");
        $(aId + ' .panel-collapse:not(".in")').collapse('show');
    }
    closeAllPanels = function(aId) {
        console.log("setAllPanelclose");
        $(aId + ' .panel-collapse.in').collapse('hide');
    }

});
