<?php

class calendar{
	
	function getWorkingDays($startDate,$endDate,$holidays){
	    // do strtotime calculations just once
	    $endDate = strtotime($endDate);
	    $startDate = strtotime($startDate);
	
	
	    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
	    //We add one to inlude both dates in the interval.
	    $days = ($endDate - $startDate) / 86400 + 1;
	
	    $no_full_weeks = floor($days / 7);
	    $no_remaining_days = fmod($days, 7);
	
	    //It will return 1 if it's Monday,.. ,7 for Sunday
	    $the_first_day_of_week = date("N", $startDate);
	    $the_last_day_of_week = date("N", $endDate);
	
	    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
	    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
	    if ($the_first_day_of_week <= $the_last_day_of_week) {
	        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
	        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
	    }
	    else {
	        // (edit by Tokes to fix an edge case where the start day was a Sunday
	        // and the end day was NOT a Saturday)
	
	        // the day of the week for start is later than the day of the week for end
	        if ($the_first_day_of_week == 7) {
	            // if the start date is a Sunday, then we definitely subtract 1 day
	            $no_remaining_days--;
	
	            if ($the_last_day_of_week == 6) {
	                // if the end date is a Saturday, then we subtract another day
	                $no_remaining_days--;
	            }
	        }
	        else {
	            // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
	            // so we skip an entire weekend and subtract 2 days
	            $no_remaining_days -= 2;
	        }
	    }
	
	    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
	//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
	   $workingDays = $no_full_weeks * 5;
	    if ($no_remaining_days > 0 )
	    {
	      $workingDays += $no_remaining_days;
	    }
	
	    //We subtract the holidays
	    foreach($holidays as $holiday){
	        $time_stamp=strtotime($holiday);
	        //If the holiday doesn't fall in weekend
	        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
	            $workingDays--;
	    }
	
	    return $workingDays;
	}
	
	/* draws a calendar */
	function draw_calendar($month,$year,$events = array()){
	
		/* draw table */
		$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
	
		/* table headings */
		$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
	
		/* days and weeks vars now ... */
		$running_day = date('w',mktime(0,0,0,$month,1,$year));
		$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
		$days_in_this_week = 1;
		$day_counter = 0;
		$dates_array = array();
	
		/* row for week one */
		$calendar.= '<tr class="calendar-row">';
	
		/* print "blank" days until the first of the current week */
		for($x = 0; $x < $running_day; $x++):
			$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
			$days_in_this_week++;
		endfor;
	
		/* keep going with days.... */
		for($list_day = 1; $list_day <= $days_in_month; $list_day++):
			$calendar.= '<td class="calendar-day"><div style="position:relative;height:100px;">';
				/* add in the day number */
				$calendar.= '<div class="day-number">'.$list_day.'</div>';
				
				$event_day = $year.'-'.$month.'-'.$list_day;
				if(isset($events[$event_day])) {
					foreach($events[$event_day] as $event) {
						$calendar.= '<div class="event">'.$event['title'].'</div>';
					}
				}
				else {
					$calendar.= str_repeat('<p>&nbsp;</p>',2);
				}
			$calendar.= '</div></td>';
			if($running_day == 6):
				$calendar.= '</tr>';
				if(($day_counter+1) != $days_in_month):
					$calendar.= '<tr class="calendar-row">';
				endif;
				$running_day = -1;
				$days_in_this_week = 0;
			endif;
			$days_in_this_week++; $running_day++; $day_counter++;
		endfor;
	
		/* finish the rest of the days in the week */
		if($days_in_this_week < 8):
			for($x = 1; $x <= (8 - $days_in_this_week); $x++):
				$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
			endfor;
		endif;
	
		/* final row */
		$calendar.= '</tr>';
		
	
		/* end the table */
		$calendar.= '</table>';
	
		/** DEBUG **/
		$calendar = str_replace('</td>','</td>'."\n",$calendar);
		$calendar = str_replace('</tr>','</tr>'."\n",$calendar);
		
		/* all done, return result */
		return $calendar;
	}
	
	function random_number() {
		srand(time());
		return (rand() % 7);
	}
	}
	

?>