
<?php 


$con = mysqli_connect( 'db5001319256.hosting-data.io', 'dbu893281', 'Fhx317088!',  'dbs1122798');


/****************row_count functions ********************/



function row_count($result){


return mysqli_num_rows($result);

}



function escape($string) {

	global $con;

	return mysqli_real_escape_string($con, $string);


}


function confirm($result) {

	global $con;

	if(!$result) {

		die("QUERY FAILED" . mysqli_error($con));

	}

}



function query($query) {

	global $con;




	$result =  mysqli_query($con, $query);

	confirm($result);

	return $result;


}





function fetch_array($result) {

	global $con;


	return mysqli_fetch_array($result);



}








 ?>