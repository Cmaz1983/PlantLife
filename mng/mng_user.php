<?php

/*
 
	@ The purpose of this document is to handle
	@ the users login and register forms. 
	@ Once the forms are submitted, the data is sent
	@ to this script and is processed accordingly

*/
	



// Include database connection
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/inc/dbconnect.inc.php' );


// Start the PHP session 
session_start(); 



/*
█████████████████████████████████████████████████████

				REGISTER SCRIPT

		@ The purpose of the register script
		@ is to allow a user to submit
		@ their information like username,
		@ email and password and 

█████████████████████████████████████████████████████
*/

// @ This if statement checks if the mode requested is the
// @ register script and will only execute if required
if ( $_POST[ 'mode' ] == "register" ) { 

	// ╔═█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████═╗
	// ║╔═════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╗║
	// ║║																		║║
	/*	
			@ This initial part of the registration script 
			@ is to verify the data that was submitted
			@ is valid, safe data.
			
			@ To do this we are checking for a condition
			@ and if the input meets the condition, we 
			@ know that the input is invalid. 

			@ We are going to use a $valid boolean
			@ to keep track of if any of the inputs are
			@ invalid, then after all the tests have
			@ been performed, we can check to see if 
			@ this $valid boolean is false, meaning 
			@ that somewhere in the checks, their was
			@ invalid data and we can handle this 
			@ properly. 
	

	First of all, we will compare the username
	string with an empty string, if this test
	returns true, this means that the
	username string matches the empty string
	meaning the submitted username is empty
	and execute the code inside this if function.       	*/
	if ( $_POST[ "r_uname" ] == "" ){
		
		// Set the $valid boolean to false indicating we have invalid inputs
		$vaild = false;
	
	/*
	We will do the same test as the username but
	for the password input this time.	*/
	}else if ( $_POST[ "r_pword1" ] == "" ){
	
		// Set the $valid boolean to false indicating we have invalid inputs
		$vaild = false;
	
	/*
	The last test we will perform here will 
	be to check the password field with
	the confirm password field to make sure
	they inputted their password correctly. 

	To do this, we will just compare the two 
	passwords using the '!=' function that will
	Check if they do not match each other.	*/
	}else if ( $_POST[ "r_pword1" ] != $_POST[ 'r_pword2' ] ) {
	
		// Set the $valid boolean to false indicating we have invalid inputs
		$vaild = false;
	
	/*
	If all of these tests passed, we can use
	an else statement to set the $valid boolean
	to true 		*/
	}else{

		// Set the $valid boolean to true indicating we have valid inputs
		$vaild = true; 

	}; //End of else statement


	/*
		@ After running all the checks on the inputs, we can 
		@ now check if the flag was set to true or false 
		@ telling us if the inputs were valid or not. 
	*/
	if (!$vaild ) {
		// If the inputs were not valid and the $valid flag is false

			// Create a session variable called message and we can
			// set a user friendly error message.
			$_SESSION[ 'message' ] = "Please enter valid data";

			// Create the location header to move the user back to 
			// the home page with a registration error
			header( "location: /?registerSuccess" );

			// Stop executing code. 
			exit();

	}; //end user check

	// ║║																	    ║║
	// ║╚█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╝║
    // ╚══════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚═════╝



    // ╔═█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████═╗
    // ║╔═════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╗║
	// ║║																		║║
	/*
				
	*/

	//Get the submitted username and set it to a variable
	$username = $_POST[ 'r_uname' ];

	/*
		
		@ Now we have the username the user wants to use, 
		@ we can check the database to see if the username
		@ has already been used becuase each username
		@ has to be unique.

	*/ 
	// Create a mysqli query string to select all the rows with the username

	$query = "SELECT * 
	FROM `user` 
	WHERE `u_username` = '{$username}'";

	$userCheck = mysqli_query( $dbconnect, $query);

	/* 
		@ Now we have all the rows in the databse with 
		@ the username, we can check the ammount of rows
		@ returned to see if the username has already 
		@ been taken.
	*/

	//If the number of returned rows is bigger than 0
	if ( mysqli_num_rows( $userCheck ) > 0 ) {

		/*
			@ The number of rows returned was
			@ bigger than 0, this means that
			@ the username has already been
			@ used by ether, another user or
			@ this user is already registered. 
		*/

		// Here we create a message session in the session data
		// to give a user friendly error.
		$_SESSION[ 'message' ] = "This username is taken, please choose another. ";

		// Set the location header back to the 
		// homepage with a registerError flag 
		// in the url to display the error 
		// message
		header( "location: /?registerSuccess" );

		// Stop executing code and exit the script. 
		exit();

	}
	else // Else, the database returned 0 rows so the username hasnt been taken. 
	{

		//Get the posted password
		$password = md5( $_POST[ 'r_pword1' ] );
		/*
					^^^
					Here we are getting the posted password
					and running it through the md5 function.

					The purpose of the md5 function is to encrypt the
					input so only the person that knows the original 
					password will be able to read it

					You can read more about MD5 here: 
					https://searchsecurity.techtarget.com/definition/MD5
		*/
			
		/*
			@ Create a SQL string to insert the submitted data from the
			@ user into the correct fields of the database. 
		*/
		$sql = "INSERT INTO `user` (`u_username`,`u_password`,`u_level`) VALUES ('{$username}' , '{$password}', 'user')";

		/*
			@ Now we have checked the data to make
			@ sure that is it valid and have checked
			@ that the username hasnt already been 
			@ used by another user, we can add
			@ this users details to the database
			@ and create their user for the website. 
		*/
		// Create a mysqli query string to execute the SQL we have written
		// to add the user to the database. 
		$register = mysqli_query( $dbconnect, $sql );

		/*
			@ Here we can check if the SQL was executed successfuly
			@ by creating an if statement. If the SQL executed and 
			@ inserted the data, the function will return true.
		*/
		if ( $register ) {

			// Function returned true, data inserted successfuly.

			// Set a status message to tell the user they can now login. 
			$_SESSION['message'] = "Thank you for registering to Tutto! Happy Shopping! Please Log in!";
			
		} else {

			// Else, there was an error with the SQL

			// Set a message session to give the user a user friendly error message
			$_SESSION['message'] = "There has been a registration error!!";

		}; // End of if register. 

		// Set the location header to redirect the user back to 
		// the homeback.
		header("location: /?registerSuccess");
		
		//Stop executing code and exit the script. 
		exit();

	}; // End of the username checking else.  

	// ║║																	    ║║
	// ║╚█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╝║
    // ╚══════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚═════╝



}; // End of register script



/*
█████████████████████████████████████████████████████

				END OF REGISTER SCRIPT

█████████████████████████████████████████████████████
*/





/*
█████████████████████████████████████████████████████

					LOGIN SCRIPT

		@ The purpose of the login script
		@ is to check if the users credentials
		@ that have been submitted to the script
		@ match any credentials that are 
		@ stored in the database. 

		@ If any of the credentials are invalid or
		@ not found in the databse, the script
		@ will return a helpful error message to 
		@ let the user know that there was 
		@ an issue with the credentials they 
		@ provided.

█████████████████████████████████████████████████████
*/


// @ This if statement checks if the mode requested is the
// @ login script and will only execute if required
if ( $_POST[ 'mode' ] == "login" ) { 


	// Set valid flag ( If changed throughout the script, 
	// 				    it will tell the checker that the input is invalid )
	$valid = true; 

	// ╔═█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████═╗
    // ║╔═════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╗║
	// ║║																		║║
	/*

				@ This small if statement is responsible for checking
				@ if the user even submitted any credentials for 
				@ checking.
	*/

	// This if statement checks if both the username and password fields
	// submitted are not empty by checking if the lengh of the string is bigger than 0
	if ( strlen( $_POST[ 'l_uname' ] ) === 0 || strlen( $_POST[ 'l_pword' ] ) === 0 ) {

		// If ether of the strings are empty, the script will 
		// echo an error message
		echo 'Username or password is empty';

		// Set the valid flag to false to tell the script
		// the submitted information is invalid.
		$valid = false;

	}; //End if statement
    
	// ║║																	    ║║
	// ║╚█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╝║
    // ╚══════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚═════╝


	/*
		@ This array is used as a response to the ajax script
		@ to manage showing the user an error message 
		@ or logging the user in. 

		@ The array holds 2 vales that are set during the script
		@
		@ 1. A success boolean to tell the ajax if the login
		@ 	 was succesful or not
		@
		@ 2. A message string to the user trying to login 
		@ 	 to alert them of the issue with their credentials. 
	*/
	$response = array(
		'success' => false,
		'message' => ""
	);


	// This if statement checks if the valid flag, that was set
	// earlier in the code is true or false.
	if ( !$valid ) {

	// ╔═█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████═╗
    // ║╔═════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╗║
	// ║║																		║║

		/*

			@ This code will be executed if the valid flag was
			@ false as we used a '!' before the variable, 
			@ checking if the flag was NOT true

		*/

		// Here we set the message variable in the response array defined above.
		$response['message'] = "There was an issue with your username and password";

		/*
			@ Here we are echoing a json object created from the response
			@ array, this means that we can parse the data at the ajax
			@ script and easily read the variables
		*/
		echo json_encode($response);

		// Tell the server to leave the script and stop executing code
		exit();


	// ║║																	    ║║
	// ║╚█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╝║
    // ╚══════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚═════╝

	} else {

	// ╔═█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████═╗
    // ║╔═════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╗║
	// ║║																		║║
		/*

			@ This section of the code takes the submitted
			@ credentials by the user and checks if the
			@ database holds this users information.
			@ If the credentials are not found, the
			@ script returns a user friendly error
			@ message. If the credentials are found, 
			@ the script logs the user in and lets 
			@ the user know.
	
		*/


		// █====================================█
			/*
				@ Here we are getting the credentials submitted
				@ by the user and setting them to variables
			*/

		//Get the posted username
		$username = $_POST[ 'l_uname' ];

		//Get the posted password
		$password = md5( $_POST[ 'l_pword' ] ); 
		/*
					^^^
					Here we are getting the posted password
					and running it through the md5 function.

					The purpose of the md5 function is to encrypt the
					input so only the person that knows the original 
					password will be able to read it

					You can read more about MD5 here: 
					https://searchsecurity.techtarget.com/definition/MD5
		*/

		// █================END=================█




		// █====================================█
			/*
				@ Here we are asking the database for all
				@ the rows that contain the username and
				@ password the user submitted
			*/

		// Write the SQL to a string for convenience
		$sql = "SELECT * FROM 
				`user` 
				WHERE 
				`u_username` ='{$username}' 
				AND 
				`u_password` = '{$password}'";


		// Here we execute the SQL on the database and  
		// put the results from the server into a variable 
		$login = mysqli_query( $dbconnect,  $sql);
        
       


		// █================END=================█

		
		/*
			@ Here we are checking the number of rows
			@ returned from the server. If the number
			@ of rows is greater than 0, we know that
			@ the users credentials were found in the
			@ database, if the number of rows are 0,
			@ we know that the database couldnt find
			@ the users credentials
		*/
		if ( mysqli_num_rows( $login ) > 0 ) {

		// ╔═█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████═╗
	    // ║╔═════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╗║
		// ║║																		║║
			/*

				@ This block will execute if the user is found in the database,
				@ we need to get the users information and set the users 
				@ PHP session to tell the server that we are logged in.
			
			*/

			/*
				@ Now we have confirmed that the user was found in the database
				@ we can fetch the result from the SQL query in an array
				@ to use and manipulate in any way we choose. 

				@ Here we are getting the array and loading it into
				@ the variable row to represent the row in the database

				@ We use a while() loop to recursivly loop through each of the
				@ rows in the database, since we should only have one
				@ user, it will only loop once and exit.
			*/
			while ( $row = mysqli_fetch_array( $login ) ) {

				/*
					@ Now we have the users information in an array, we can set the users
					@ session and use the data throughout the website to 
					@ confirm the user is logged in and to get the correct
					@ user information for the logged in user. 
				*/

				// Set the users ID to the session data
				$_SESSION[ 'user_id' ] = $row[ 'user_id' ];
				// Set the users username for quick access for application such as the header
				$_SESSION[ 'u_username' ] = $row[ 'u_username' ];
				// Set the users access level for quick access and security between users and user areas
				$_SESSION[ 'u_level' ] = $row[ 'u_level' ];


			}; // Close the wile loop

			/*
				@ Now we have confirmed the user has an account on the website
				@ from checking the credentials in the database and we have set
				@ the users session for the website, we can now set the response 
				@ 'success' flag to true so we can manipulate the respose
				@ differently with the login being successful. 
			*/

			// set the response success flag to true
			$response['success'] = true;
			// Set the response message to welcome the user to the website
			$response['message'] = "Welcome to the wonderful world of Tutto ".$_SESSION ['u_username']."!";

			/*
				@ Here we are echoing a json object created from the response
				@ array, this means that we can parse the data at the ajax
				@ script and easily read the variables
			*/
			echo json_encode($response);

			// Tell the server to leave the script and stop executing code
			exit();
		
		
		// ║║																	    ║║
		// ║╚█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╝║
	    // ╚══════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚═════╝

		} else {

		// ╔═█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████═╗
	    // ║╔═════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╗║
		// ║║																		║║
			/*

				@ This block of code will only execute if the number of rows
				@ in the database were 0, meaning the users credentials were
				@ not found. 
			
			*/

			// Set a user friendly error message so the user understands the
			// credentials submitted were not found in the database
			$response['message'] = "There was an issue with your username and password";

			/*
				@ Here we are echoing a json object created from the response
				@ array, this means that we can parse the data at the ajax
				@ script and easily read the variables
			*/
			echo json_encode($response);

			// Tell the server to leave the script and stop executing code
			exit();


		// ║║																	    ║║
		// ║╚█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╝║
    	// ╚══════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚═════╝


		}; //End of IF user rows


	// ║║																	    ║║
	// ║╚█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╗█████╝║
    // ╚══════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚════╝╚═════╝


	}; // End of IF NOT valid

    


/*
	@ Close the if statement that checks if the
	@ login script is requested
*/

}; // End of if statement.

/*
█████████████████████████████████████████████████████

				END OF LOGIN SCRIPT

█████████████████████████████████████████████████████
*/


?>