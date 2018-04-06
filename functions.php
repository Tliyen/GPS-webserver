<?php
/*---------------------------------------------------------------------------------------
--	Source File:		functions.php - Scripts for Login
--
--	Methods:		register()
--							getUserById()
--							login()
--							isLoggedIn()
--							isAdmin()
--							e()
--							display_error()
--
--	Date:			April 3, 2018
--
--	Revisions:		(Date and Description)
--                April 3, 2018
--                Initialize and Set up Project
--                April 5, 2018
--                Code Comments
--
--	Designer:		  Anthony Vu, Li-Yan Tong, Morgan Ariss, John Tee
--                Source: Awa Melvine: User Registration Tutorial
--
--	Programmer:		Li-Yan Tong
--
--	Notes:
--	Handles functions used by the website related to the user login system.  It is based
--  off a PHP tutorial at the following site:
--  https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database
---------------------------------------------------------------------------------------*/

session_start();

// connect to database
$db = mysqli_connect('localhost', 'root', 'ok', 'multi_login');

// variable declaration
$username = "";
$email    = "";
$errors   = array();

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
	register();
}

// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
	login();
}

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: ../login.php");
}

/*------------------------------------------------------------------------------------
-- FUNCTION: Register()
--
-- DATE:  April 3, 2018
--
-- REVISIONS: April 3, 2018
--							Initial file set up
--
-- DESIGNER: Li-Yan Tong & John Tee
--           Source: Awa Melvine: User Registration Tutorial
--
-- PROGRAMMER: Li-Yan Tong
--
-- INTERFACE: register()
--
-- RETURNS: void
--
-- NOTES:
-- This function takes in a user's input for name, email and password (plus retyped)
-- to create a mysql database entry for for a user account.
---------------------------------------------------------------------------------------*/
function register(){
	global $db, $errors;

	// receive all input values from the form
	$username    =  e($_POST['username']);
	$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);

	// form validation: ensure that the form is correctly filled
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($email)) {
		array_push($errors, "Email is required");
	}
	if (empty($password_1)) {
		array_push($errors, "Password is required");
	}
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password_1);//encrypt the password before saving in the database

		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO users (username, email, user_type, password)
			VALUES('$username', '$email', '$user_type', '$password')";
			mysqli_query($db, $query);
			$_SESSION['success']  = "New user successfully created!!";
			header('location: home.php');
		}else{
			$query = "INSERT INTO users (username, email, user_type, password)
			VALUES('$username', '$email', 'user', '$password')";
			mysqli_query($db, $query);

			// get id of the created user
			$logged_in_user_id = mysqli_insert_id($db);

			$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
			$_SESSION['success']  = "You are now logged in";
			header('location: index.php');
		}

	}

}

/*------------------------------------------------------------------------------------
-- FUNCTION: getUserById()
--
-- DATE:  April 3, 2018
--
-- REVISIONS: April 3, 2018
--							Initial file set up
--
-- DESIGNER: Li-Yan Tong & John Tee
--           Source: Awa Melvine: User Registration Tutorial
--
-- PROGRAMMER: Li-Yan Tong
--
-- INTERFACE: getUserById()
--
-- RETURNS: User array from their id
--
-- NOTES:
-- Queries a user's data from a user database.
---------------------------------------------------------------------------------------*/	//
function getUserById($id){
	global $db;
	$query = "SELECT * FROM users WHERE id=" . $id;
	$result = mysqli_query($db, $query);

	$user = mysqli_fetch_assoc($result);
	return $user;
}

/*------------------------------------------------------------------------------------
-- FUNCTION: login()
--
-- DATE:  April 3, 2018
--
-- REVISIONS: April 3, 2018
--							Initial file set up
--
-- DESIGNER: Li-Yan Tong & John Tee
--           Source: Awa Melvine: User Registration Tutorial
--
-- PROGRAMMER: Li-Yan Tong
--
-- INTERFACE: login()
--
-- RETURNS: If sucessful directed to Main website;
--					If an error, prompts with error message and redirects user to try login again
--
-- NOTES:
-- Gets user input from html form and attempts to compare the form data to a user in the
-- database.
---------------------------------------------------------------------------------------*/
function login(){
	global $db, $username, $errors;

	// grap form values
	$username = e($_POST['username']);
	$password = e($_POST['password']);

	// make sure form is filled properly
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	// attempt login if no errors on form
	if (count($errors) == 0) {
		$password = md5($password);

		$query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) { // user found
			// check if user is admin or user
			$logged_in_user = mysqli_fetch_assoc($results);
			if ($logged_in_user['user_type'] == 'admin') {

				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";
				header('location: admin/home.php');
			}else{
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";

				header('location: index.php');
			}
		}else {
			array_push($errors, "Wrong username/password combination");
		}
	}
}

/*------------------------------------------------------------------------------------
-- FUNCTION: isLoggedIn()
--
-- DATE:  April 3, 2018
--
-- REVISIONS: April 3, 2018
--							Initial file set up
--
-- DESIGNER: Li-Yan Tong & John Tee
--           Source: Awa Melvine: User Registration Tutorial
--
-- PROGRAMMER: Li-Yan Tong
--
-- INTERFACE: isLoggedIn()
--
-- RETURNS: True is user is still logged into system
--					False if user is logged out of system
--
-- NOTES:
-- Checks if the user is still logged into the website.
---------------------------------------------------------------------------------------*/
function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}

/*------------------------------------------------------------------------------------
-- FUNCTION: isAdmin()
--
-- DATE:  April 3, 2018
--
-- REVISIONS: April 3, 2018
--							Initial file set up
--
-- DESIGNER: Li-Yan Tong & John Tee
--           Source: Awa Melvine: User Registration Tutorial
--
-- PROGRAMMER: Li-Yan Tong
--
-- INTERFACE: isLoggedIn()
--
-- RETURNS: True if user is admin type
--					False if user is not admin type
--
-- NOTES:
-- Boolean check to allow user access to websites administrator options.
---------------------------------------------------------------------------------------*/
function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
		return true;
	}else{
		return false;
	}
}

/*------------------------------------------------------------------------------------
-- FUNCTION: e()
--
-- DATE:  April 3, 2018
--
-- REVISIONS: April 3, 2018
--							Initial file set up
--
-- DESIGNER: Li-Yan Tong & John Tee
--           Source: Awa Melvine: User Registration Tutorial
--
-- PROGRAMMER: Li-Yan Tong
--
-- INTERFACE: e()
--
-- RETURNS: Properly formatted string for database
--
-- NOTES:
-- Strips characters and values in string that are invalid characters for a mysql
-- server database.
---------------------------------------------------------------------------------------*/
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

/*------------------------------------------------------------------------------------
-- FUNCTION: display_error()
--
-- DATE:  April 3, 2018
--
-- REVISIONS: April 3, 2018
--							Initial file set up
--
-- DESIGNER: Li-Yan Tong & John Tee
--           Source: Awa Melvine: User Registration Tutorial
--
-- PROGRAMMER: Li-Yan Tong
--
-- INTERFACE: display_error()
--
-- RETURNS: Error message from user input.
--
-- NOTES:
-- Displays an error to user
---------------------------------------------------------------------------------------*/
function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="error">';
		foreach ($errors as $error){
			echo $error .'<br>';
		}
		echo '</div>';
	}
}

?>
