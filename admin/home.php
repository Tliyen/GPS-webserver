<?php
/*---------------------------------------------------------------------------------------
--	Source File:		home.php - Webpage to create a new user
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
include('../functions.php');

if (!isAdmin()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: ../login.php');
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="../style.css">
	<style>
	.header {
		background: #003366;
	}
	button[name=register_btn] {
		background: #003366;
	}
	</style>
</head>
<body>
	<div class="header">
		<h2>Admin - Home Page</h2>
	</div>
	<div class="content">
		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
			<div class="error success" >
				<h3>
					<?php
					echo $_SESSION['success'];
					unset($_SESSION['success']);
					?>
				</h3>
			</div>
		<?php endif ?>

		<!-- logged in user information -->
		<div class="profile_info">
			<img src="../images/admin_profile.png"  >

			<div>
				<?php  if (isset($_SESSION['user'])) : ?>
					<strong><?php echo $_SESSION['user']['username']; ?></strong>

					<small>
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i>
						<br>
						<a href="home.php?logout='1'" style="color: red;">logout</a>
						&nbsp; <a href="create_user.php"> + add user</a>
					</small>

				<?php endif ?>
			</div>
		</div>
	</div>
</body>
</html>
