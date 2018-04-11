$(document).ready(function() {
	
	$('option', 'form#applyLeave select#fhDay').remove();
		
	$('option', 'form#applyLeave select#medCert').remove();
			
	$("form#formType select#type").change(function(event){
	 	event.preventDefault();
	 	var type = $("form#formType select#type option:selected").val();
 	 	var dataString = {type: type};
	 	$.ajax({
            type: 'post',
            url: 'functions/fullReport.php',
            data: dataString,
            success: function(response){
            	$("div#histContent").html(response);
            },
           error: function(ob,errStr) {
                    $('form#formType label#formProgress').html('');
                    alert('There was an error in your request.');
       		}
    	});
    
	 });
	 
	/*$("form#vCal select#staff").change(function(event){
	 	event.preventDefault();
	 	var staff = $("form#vCal select#staff option:selected").val();
	 	var link = "functions/json-events.php";
	 	
	 	if (staff == 'all')
	 	{
	 		var link = "functions/json-events.php";
	 	}
	 	else
	 	{
	 		var link = "functions/staff-json-events.php?staff="+staff;
	 		
	 	}
	 	
        	$('#calendar').html('');
        	$('#calendar').fullCalendar({
    
	            editable: false,
	            
	            events: link,
	            
	            eventDrop: function(event, delta) {
	                alert(event.title + ' was moved ' + delta + ' days\n' +
	                    '(should probably update your database)');
	            },
	            
	            loading: function(bool) {
	                if (bool) $('#loading').show();
	                else $('#loading').hide();
	            }
	            
	        });        	
            
    });*/
        	 
	$("form#login input#submit").click(function(event) {
        event.preventDefault();
        var isFocus=0;
        var isError=0;
        var username = $('form#login input#username').val();
		var password = $('form#login input#password').val();
		var url = "mainPortal.php";
				
		if(username=="") {
                $('form#login label#errorUsername').html('Username is required');
                $('form#login input#username').focus();
                isFocus=1;
                isError=1;
        }
		
		if(password=="") {
                $('form#login label#errorPassword').html('Password is required');
                $('form#login input#password').focus();
                isFocus=1;
                isError=1;
        }
                                   
        // Terminate the script if an error is found
        if(isError==1) {
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
            	if (response == 1){
            		$(location).attr('href',url);
                }else{
                    alert('Login unsuccessfull.');
                    $('form#newsletter input#email').val('');
                    $('form#newsletter label#errorEmail').html('');
                    
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
	 	if (type == ""){
	 		$('form#applyLeave input#lDays').val('');
	 		$('option', select_medCert).remove();
	 		return false;
	 	}
	 	if (type == 1){
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
            url: 'functions/getNumRemDays.php',
            data: dataString,
            success: function(response){
            	$('form#applyLeave input#lDays').val(response);
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
            url: 'functions/getNumDays.php',
            data: dataString,
            success: function(response){
            	var arr = response.split(',');
            	if (arr[1] < 0){
            		$('form#applyLeave label#reply').html("Insuffecient leave days!!");
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
            url: 'functions/getHours.php',
            data: dataString,
            success: function(response){
            	var arr = response.split(',');
            	$('form#applyLeave input#sTime').val(arr[0]);
        		$('form#applyLeave input#eTime').val(arr[1]);
        		$('form#applyLeave input#hours').val(arr[2]);
            
         },
		error: function(ob,errStr) {
                $('form#applyLeave label#formProgress').html('');
                alert('There was an error submitting your request. Please try again.');
                // Activate the submit button
          }
        });
		
    }); 
        	
	$("form#applyLeave input#submit").click(function(event) {
        event.preventDefault();
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
        
        if(type=="") {
                $('form#applyLeave label#errorType').html('select one');
                $('form#applyLeave input#type').focus();
                isFocus=1;
                isError=1;
        }
        if(sDate=="") {
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
        if (numDays=="1" && fhDay==""){
		    	$('form#applyLeave label#errorFHDay').html('select one');
		        $('form#applyLeave input#fhDay').focus();
		        isFocus=1;
		        isError=1;
		}
		 
        if (type=="1" && medCert==""){
		    	$('form#applyLeave label#errorMedCert').html('select one');
		        $('form#applyLeave select#medCert').focus();
		        isFocus=1;
		        isError=1;
		    
		}
                     
        // Terminate the script if an error is found
        if(isError==1) {
                $('form#applyLeave label#formProgress').html('');
                $('form#applyLeave label#formProgress').hide();
                // Activate the submit button
                $('form#applyLeave input#submit').removeAttr("disabled");
                return false;
        }
        $.ajaxSetup ({
                cache: false
        });
        var dataString = {type: type, sDate: sDate, eDate: eDate, numDays: numDays, hours:hours, fhDay: fhDay, medCert: medCert, comments: comments};
        
        $.ajax({
            type: 'post',
            url: 'functions/requestLeave.php',
            data: dataString,
            success: function(response){
            	if (response == 1){
                    $('form#applyLeave').get(0).reset();
                    $('form#applyLeave label#reply').text('Leave request successfull.');
                }else{
                    alert('There was an error sending your request. Please try again.');
                }
            },
           	error: function(ob,errStr) {
                    $('form#applyLeave label#formProgress').html('');
                    alert('There was an error submitting your request. Please try again.');
                                     
            }
        });
       
     });
     
    $("form.rAction input#submit").click(function(event) {
        event.preventDefault();
        var isFocus=0;
        var isError=0;
		var status = $('form.rAction input[name=decision]:radio').val();
		var sDate = $('form.rAction label#sDate').text();
		var eDate = $('form.rAction label#eDate').text();
		var comments = $('form.rAction textarea#rForDecline').val();
        var applicant = $('form.rAction label#applicant').text();
        
        if(status=="") {
                $('form.rAction label#errorStatus').html('select one');
                $('form.rAction input[name=decision]:radio').focus();
                isFocus=1;
                isError=1;
        }
        
        if(status=="dec" && comments=="") {
                $('form.rAction label#errorComments').html('Provide reason for decline');
                $('form.rAction input[name=decision]:radio').focus();
                isFocus=1;
                isError=1;
        }
                             
        // Terminate the script if an error is found
        if(isError==1) {
                $('form#applyLeave label#formProgress').html('');
                $('form#applyLeave label#formProgress').hide();
                // Activate the submit button
                $('form#applyLeave input#submit').removeAttr("disabled");
                return false;
        }
        $.ajaxSetup ({
                cache: false
        });
        var dataString = {type: type, sDate: sDate, eDate: eDate, numDays: numDays, hours:hours, fhDay: fhDay, medCert: medCert, comments: comments};
        
        $.ajax({
            type: 'post',
            url: 'functions/requestLeave.php',
            data: dataString,
            success: function(response){
            	if (response == 1){
                    $('form#applyLeave').get(0).reset();
                    $('form#applyLeave label#reply').text('Leave request successfull.');
                }else{
                    alert('There was an error sending your request. Please try again.');
                }
            },
           	error: function(ob,errStr) {
                    $('form#applyLeave label#formProgress').html('');
                    alert('There was an error submitting your request. Please try again.');
                                     
            }
        });
       
     });
    
	$("form.cLeaveForm").submit(function(event){
    	var $targetFrom = $(this);
    	var id = $targetFrom.find("#leaveAppID").val();
    	
    	$.ajaxSetup ({
                cache: false
        });
        var dataString = {id: id};
        
        $.ajax({
            type: 'post',
            url: 'functions/requestCancellation.php',
            data: dataString,
            success: function(response){
            	
            	if (response == 1){
            		
            		location.reload();
                    $('label#reply').html('request submitted successfull.');
                    return false;
                }else{
                    alert('There was an error sending your request. Please try again.');
                    return false;
                }
            },
           	error: function(ob,errStr) {
                    $('form#applyLeave label#formProgress').html('');
                    alert('There was an error submitting your request. Please try again.');
                    return false;
                                     
            }
        });
        event.preventDefault();
    });
    
    $("input#logout").click(function(event){
    	$.ajax({
	            type: 'get',
	            url: 'functions/logout.php',
	            success: function(response){
	            	$(location).attr('href',response);
	            },
	           error: function(ob,errStr) {
	                    $('form#formType label#formProgress').html('');
	                    alert('There was an error in your request.');
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
        
        events: {
        	url: "functions/json-events.php",
        	error: function(){
        		$('#script-warning').show();
        	}
        },
        
        eventDrop: function(event, delta) {
            alert(event.title + ' was moved ' + delta + ' days\n' +
                '(should probably update your database)');
        },
        
        loading: function(bool) {
            $('#loading').toggle(bool);
        }
        
    });
       
});


