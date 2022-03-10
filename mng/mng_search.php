<?php

/*
█████████████████████████████████████████████████████

					Manage search

		@ The purpose of this script is to
		@ search the database for a query 
		@ string and return the results
		@ in a JSON format. 

█████████████████████████████████████████████████████
*/

//include database connection file
include( '../inc/dbconnect.inc.php' );

//include the header template file
include( '../inc/header.php' );

/*
	Since we are going to searcing the database from the
	users search string, it is a good idea to remove
	any html special characters form the string so
	the databse cannot be exploited from a SQL injection.
*/
$search = htmlspecialchars($_GET['term']);

/*
	@ Create an mysqli query string to select
	@ all the items from the products table
	@ where the product name is simmilar to 
	@ a submitted query peramiter
*/
$result = mysqli_query( $dbname, "SELECT * FROM `PRODUCT` WHERE `p_name` LIKE %{$search}" );

// we can now check if there were any rows returned
// and only process if we have to. 
if($result->num_rows > 0){ 

	// Create an array to load the results into
	$mainarray = array(); 

	// Now we can loop over all of the results using a while loop
	while ( $row - mysqli_fetch_array( $result ) ) {

		// Add the result's ID and NAME to a temporary array
		$rowarray - array(
			"id" => $row[ 'product_id' ],
			"lable" => $row[ 'p_name' ]
		); 

		// push the temp array to the main array creating a nested array
		// later to be converted to JSON
		array_push( $mainarray, $rowarray );

	}; // ENd of the while loop

	// Return the array in a JSON encoded format
	echo json_encode( $mainarray ); //output listing 

}else{
	// If there were no results from the database, we can just
	// return the empty array without executing any more code. 
	echo json_encode( $mainarray  );

}; // End of num rows result. 

?>