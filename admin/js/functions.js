$(document).ready(function() {
    
    var v = 0;
    
    function validateEmail(sEmail) {
        var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
        if (filter.test(sEmail)) {
            return true;
        }
        else {
            return false;
        }
    }
       
    $("form#add_employee input#empl_email").keyup(function(){
        var email = $('form#add_employee input#empl_email').val();
        if(validateEmail(email)){
            $("form#add_employee input#empl_email").css({"border-color": "#5FBA7D", 
                                                        "border-width":"1px", 
                                                        "border-style":"solid"});
            v = 0;
        }else{
            $("form#add_employee input#empl_email").css({"border-color": "#CA1D20", 
                                                        "border-width":"1px", 
                                                        "border-style":"solid"});
            v = 1;
        }     
    });
    
    $("form#admin-signin button#submit").click(function(event) {
            event.preventDefault();
            var isFocus=0;
            var isError=0;
            var username = $('form#admin-signin input#username').val();
            var password = $('form#admin-signin input#password').val();
            var url = "dashboard.php";

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
                url: 'PHP_Functions/login_admin.php',
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
         
    $.getJSON("PHP_Functions/managers_list.php", function(data){
            $.each(data.data, function(key,value){
                $("#managers").append(
                            "<option value=" + value.emplNum + ">" + value.name + "</option>"
                       );
            });
        });
      	
    $("form#add_employee button#submit").click(function(event) {
            event.preventDefault();
            var isFocus=0;
            var isError=0;
            var empl_num = $('form#add_employee input#empl_num').val();
            var empl_name = $('form#add_employee input#empl_name').val();
            var empl_email = $('form#add_employee input#empl_email').val();
            var empl_job_title = $('form#add_employee input#empl_job_title').val();
            var empl_ext = $('form#add_employee input#empl_ext').val();
            var empl_username = $('form#add_employee input#empl_username').val();
            var empl_password = $('form#add_employee input#password').val();
            var manager = $('form#add_employee select#managers').val();
            var is_manager = $('form#add_employee #is_manager:checked').length;
            var is_admin = $('form#add_employee #is_admin:checked').val();

            if(manager==="") {
                $("form#add_employee select#manager").css({"border-color": "#CA1D20", 
                                                            "border-width":"1px", 
                                                            "border-style":"solid"});
                isError=1;
            }
            
            if(v === 1){
                isError=1;
            }

            // Terminate the script if an error is found
            if(isError===1) {
                    alert('Some fields need your attention!!!');
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
                url: 'PHP_Functions/add_employee.php',
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

    $("form#lReqAct input#approve").click(function(event) {
                event.preventDefault();

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
                                $( location ).attr("href", url);
                        }else{
                            alert('There was an error sending your request. Please try again.');
                            return false;
                        }
                    },
                        error: function(ob,errStr) {

                            alert('There was an error submitting your request. Please try again.');

                    }
                });

            });
        
    $("form#lCancAct input#approve").click(function(event) {
            event.preventDefault();

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
                            $( location ).attr("href", url);
                    }else{
                        alert('There was an error sending your request. Please try again.');
                        return false;
                    }
                },
                    error: function(ob,errStr) {

                        alert('There was an error submitting your request. Please try again.');

                }
            });
       
        });
        
    $("form#lReqAct input#decline").click(function(event) {
            event.preventDefault();
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
                alert("Provide reasons for declining....");
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
                            $( location ).attr("href", url);
                    }else{
                        alert('There was an error sending your request. Please try again.');
                        return false;
                    }
                },
                    error: function(ob,errStr) {

                        alert('There was an error submitting your request. Please try again.');

                }
            });
       
        });
        
    $("form#lCancAct input#decline").click(function(event) {
            event.preventDefault();
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
                alert("Provide reasons for declining....");
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
                            $( location ).attr("href", url);
                    }else{
                        alert('There was an error sending your request. Please try again.');
                        return false;
                    }
                },
                    error: function(ob,errStr) {

                        alert('There was an error submitting your request. Please try again.');

                }
            });
       
        });
    
    $("form.cLeaveForm").submit(function(event){

        var isFocus=0;
        var isError=0;
        var $targetFrom = $(this);
        var id = $targetFrom.find("input#leaveAppID").val();
        var reason = $targetFrom.find("textarea#reasons").val();

        alert(id + " " + reason);

        if (reason === ""){
            isError=1;
        }

        if(isError==1) {
            alert("Provide reasons for cancelling leave!!!");
            return false;
        }


        $.ajaxSetup ({
                cache: false
        });
        var dataString = {id: id, reason: reason};

        $.ajax({
            type: 'post',
            url: './../functions/requestCancellation.php',
            data: dataString,
            success: function(response){
                alert(response);

                if (response === '1'){
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

    $("button#logout").click(function(event){
    $.ajax({
                type: 'get',
                url: 'PHP_Functions/logout.php',
                success: function(response){
                    $(location).attr('href',response);
                },
               error: function(ob,errStr) {
                        $('form#formType label#formProgress').html('');
                        alert('There was an error in your request.');
                    }
            });
});
    
    $.fn.clearForm = function() {
        return this.each(function() {
            var type = this.type, tag = this.tagName.toLowerCase();
            if (tag === 'form')
                return $(':input',this).clearForm();
            if (type === 'text' || type === 'password' || tag === 'textarea')
                this.value = '';
            else if (type === 'checkbox' || type === 'radio')
                this.checked = false;
            else if (tag === 'select')
                this.selectedIndex = -1;
        });
    };
    
    // Function that validates email address through a regular expression.
    
});

