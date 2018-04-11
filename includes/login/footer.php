			
          
        </div>
        <script type="text/javascript" src="Scripts/moment.min.js"></script>
        <script type="text/javascript" src="Scripts/jquery-2.0.3.min.js"></script>
        <script src='Scripts/fullcalendar.min.js'></script>
        <script src='Scripts/jquery.dataTables.min.js'></script>
        <script src='Scripts/dataTables.buttons.min.js'></script>
        <script src='bower_components/gasparesganga-jquery-loading-overlay/src/loadingoverlay.min.js'></script>
        <script src='bower_components/sweetalert/dist/sweetalert.min.js'></script>
        <script type="text/javascript" src="Scripts/jquery-ui-1.10.4.custom.min.js"></script>
        <script type="text/javascript" src="Scripts/jquery.printpage.js"></script>
        <script type="text/javascript" src="Scripts/functions.js"></script>
        
        <script type="text/javascript">
        	$(function($){
        	     
        	    $.datepicker.setDefaults({
                    howOn: "button",
                    buttonImage: "../images/calendar.gif",
                    buttonImageOnly: true,
                    showButtonPanel: true,
                    dateFormat: 'yy-mm-dd'
        	    });
        	    
        	    $('span.print').printPage();
        	    
	        	$('form#applyLeave input#sDate').datepicker({
	        		minDate: 0,
					onSelect: function(selectedDate){
					    $('form#applyLeave input#eDate').datepicker('option', 'minDate', selectedDate);
					}
				});
				
				$('form#applyLeave input#eDate').datepicker();
				
				$('form#formType input#dFrom').datepicker().datepicker('setDate', '2014-01-01');
				$('form#formType input#dTo').datepicker().datepicker('setDate', '2014-12-31');
			});
        </script>
    </body>
</html>
