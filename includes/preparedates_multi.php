<?php
//get all dates in query
	$sql0 = 'SELECT copyrightdate as label, count(*) as value FROM ' . THIS_TABLE . ' WHERE itemcallnumber REGEXP :lcclass GROUP BY copyrightdate';
	$stmt = $pdo->prepare($sql0);
	$stmt->execute( array(':lcclass' => "^" . $lcclass ) );
	$alldates = $stmt->fetchAll();

	//make array of dates only
	$datesonly = array();
	foreach ($alldates as $thisv){
	$datesonly[] =  $thisv['label'];
	}

	//get earliest date in array
	asort($datesonly);
	
	//make array of desired range (earliest book through present year)
	$allyears = array();


//check if the view is expanded; if so, add the empty years
	if (isset($_GET['view']) and ($_GET['view'] == 'compressed') )
{
	
			$allyears = $datesonly;
		
}
//if it's compressed, set the years array to only have existing ones
else{
	
	//check if first value is zero, if so, start with the next number, but manually insert it at the beginning (we don't want the range calcuated from 0-2016)
	if ($datesonly[0] == '0'){
		$startingval = $datesonly[1];
		$allyears[] = '0';	
	}
	
	else{$startingval = $datesonly[0];}


	foreach (range($startingval, date("Y")) as $number1) {
    $allyears[] = $number1;
    if (!in_array($number1, $datesonly)) {
    $alldates[] = array(
    "label" => $number1,
    "value" => '0',
			);

		}
	   
	}


}



	//sort the new array with the empty years
	// Obtain a list of columns
	foreach ($alldates as $key => $row) {
		$label[$key]  = $row['label'];
		$value[$key] = $row['value'];
	}
	// Sort the data 
	// Add $data as the last parameter, to sort by the common key
	array_multisort($label, SORT_ASC, $alldates);
	

?>