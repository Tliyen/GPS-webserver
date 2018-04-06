<?php
/*---------------------------------------------------------------------------------------
--	Source File:		login.php - Webpage to prompt for user credentials to use website.
--
--	Methods:	see functions.php
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
--	Website to prompt user to login to main site or create a new account.
--  https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database
---------------------------------------------------------------------------------------*/
include('functions.php') ?>
<!DOCTYPE html>
<html>
<head>
	<title>GPS Tracker Login</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header">
		<h2>Login</h2>
	</div>

	<form method="post" action="login.php">

		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" >
		</div>
		<div class="input-group">
			<label>Password</label>
			<input type="password" name="password">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="login_btn">Login</button>
		</div>
		<p>
			Not yet a member? <a href="register.php">Sign up</a>
		</p>
	</form>
</body>
</html>
