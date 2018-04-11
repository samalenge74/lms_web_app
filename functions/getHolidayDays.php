<?php
	$y = date("Y");
	$dir = dirname(__FILE__);
	$file = $dir . "/holidays.txt";
	$json_string = file_get_contents("http://www.kayaposoft.com/enrico/json/v1.0/?action=getPublicHolidaysForYear&year=".$y."&country=zaf");
	//json string to array

	$parsed_arr = json_decode($json_string,true);
	
	$file = fopen("holidays.txt", "w+") or die("Unable to open file!");
	
	for ($i = 0; $i < count($parsed_arr); $i++){
		// Append a new person to the file
		$current = $parsed_arr[$i][date][year] . "-" . $parsed_arr[$i][date][month] . "-" . $parsed_arr[$i][date][day] ." ";

		fwrite($file, $current);
	}
	
	fclose($file);

?>