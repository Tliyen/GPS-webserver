<?php
/*---------------------------------------------------------------------------------------
--	Source File:		index.php - Webpage to display user location transmitted by android
--															device on a Google maps image.
--
--	Methods:		see functions.php
--							processData(allText)
--							addMarker(location, name, time)
--							setMapOnAll(map)
--							clearMarkers()
--							deleteMarkers()
--
--	Date:			April 2, 2018
--
--	Revisions:		(Date and Description)
--                April 2, 2018
--                Initialize and Set up Project
--                April 5, 2018
--                Code Comments
--
--	Designer:		  Anthony Vu, Li-Yan Tong, Morgan Ariss, John Tee
--
--	Programmer:		Li-Yan Tong & Morgan Ariss
--
--	Notes:
--	Website that verifies user has an account and logged in.  After this check, the
--  site displays a Google Maps image and a marker of the user's last known device
--  location.
---------------------------------------------------------------------------------------*/
include('functions.php');

if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<title>COMP4985 GPS</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style>
	#map {
		height: 600px;
		width: 100%;
	}
	</style>

</head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<body>
	<div class="header">
		<h2>Home Page</h2>
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
			<img src="images/user_profile.png"  >

			<div>
				<?php  if (isset($_SESSION['user'])) : ?>
					<strong><?php echo $_SESSION['user']['username']; ?></strong>

					<small>
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i>
						<br>
						<a href="index.php?logout='1'" style="color: red;">logout</a>
					</small>

				<?php endif ?>
			</div>
		</div>

		<!-- logged in user information -->
		<div id="map"></div>
		<h2>Current User Location</h2>
		<script>
		var map;
		var markers = [];
		function initMap() {
			var bcit = new google.maps.LatLng( 49.2513601,-123.003341);
			var name;
			var long;
			var lat;
			var ip;
			var time;
			map = new google.maps.Map(document.getElementById('map'), {
				zoom: 15,
				center: bcit
			});

			setInterval(function() {
				$.ajax({
					type: "GET",
					url: "/geo.csv",
					dataType: "text/csv",
					success: function(data) {processData(data);}
				});
			}, 1000);
		}

		/*------------------------------------------------------------------------------------
		-- FUNCTION: processData(allText)
		--
		-- DATE:  April 2, 2018
		--
		-- REVISIONS: April 2, 2018
		--							Initial file set up
		--            April 5, 2018
		--              Code Comments
		--
		-- DESIGNER: Anthony Vu & Morgan Ariss
		--
		-- PROGRAMMER: Morgan Ariss
		--
		-- INTERFACE: processData(allText)
		--						allText - A .csv file with following sent user information to webserver:
		--											user name, longitute, latitude, ip and time sent.
		--
		-- RETURNS: Google Map with Markers based on text data
		--
		-- NOTES:
		-- Refreshes google map markers based on inputted text data on map.
		---------------------------------------------------------------------------------------*/
		function processData(allText) {
			var allTextLines = allText.split(/\r\n|\n/);
			var headers = allTextLines[0].split(',');
			//console.log("clearing markers");
			deleteMarkers();
			clearMarkers();
			for (var i=1; i<allTextLines.length; i++) {
				var data = allTextLines[i].split(',');
				if (data.length == headers.length) {
					name = data[0];
					long = data[1];
					lat = data[2];
					ip = data[3];
					time = data[4];
					var loc = new google.maps.LatLng(parseFloat(long),parseFloat(lat));
					addMarker(loc, name, time);
				}
			}
		}

		/*------------------------------------------------------------------------------------
		-- FUNCTION: addMarker(location, name, time)
		--
		-- DATE:  April 2, 2018
		--
		-- REVISIONS: April 2, 2018
		--							Initial file set up
		--            April 5, 2018
		--              Code Comments
		--
		-- DESIGNER: Anthony Vu & Morgan Ariss
		--
		-- PROGRAMMER: Morgan Ariss
		--
		-- INTERFACE: addMarker(location, name, time)
		--						location - The reference to a google map to place a marker
		--						name - the user name to put in marker information
		--						time - the time user sent coordiates
		--
		-- RETURNS: Marker placed on a google map
		--
		-- NOTES:
		-- Adds a marker based on inputted values onto a Google maps window.
		---------------------------------------------------------------------------------------*/
		function addMarker(location, name, time) {
			var marker = new google.maps.Marker({
				position: location,
				map: map,
				title: "Name: " + name + "\nTime: " + time
			});
			markers.push(marker);
		}

		/*------------------------------------------------------------------------------------
		-- FUNCTION: setMapOnAll(map)
		--
		-- DATE:  April 2, 2018
		--
		-- REVISIONS: April 2, 2018
		--							Initial file set up
		--            April 5, 2018
		--              Code Comments
		--
		-- DESIGNER: Anthony Vu & Morgan Ariss
		--
		-- PROGRAMMER: Morgan Ariss
		--
		-- INTERFACE: setMapOnAll(map)
		--
		-- RETURNS: void
		--
		-- NOTES:
		-- Sets markers in an array of markers on a google map.
		---------------------------------------------------------------------------------------*/
		function setMapOnAll(map) {
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(map);
			}
		}

		/*------------------------------------------------------------------------------------
		-- FUNCTION: clearMarkers()
		--
		-- DATE:  April 2, 2018
		--
		-- REVISIONS: April 2, 2018
		--							Initial file set up
		--            April 5, 2018
		--              Code Comments
		--
		-- DESIGNER: Anthony Vu & Morgan Ariss
		--
		-- PROGRAMMER: Morgan Ariss
		--
		-- INTERFACE: clearMarkers()
		--
		-- RETURNS: void
		--
		-- NOTES:
		-- Clears markers from a google maps image
		---------------------------------------------------------------------------------------*/
		function clearMarkers() {
			setMapOnAll(null);
		}

		/*------------------------------------------------------------------------------------
		-- FUNCTION: showMarkers()
		--
		-- DATE:  April 2, 2018
		--
		-- REVISIONS: April 2, 2018
		--							Initial file set up
		--            April 5, 2018
		--              Code Comments
		--
		-- DESIGNER: Anthony Vu & Morgan Ariss
		--
		-- PROGRAMMER: Morgan Ariss
		--
		-- INTERFACE: showMarkers()
		--
		-- RETURNS: void
		--
		-- NOTES:
		-- Draws markers on a google maps image.
		---------------------------------------------------------------------------------------*/
		function showMarkers() {
			setMapOnAll(map);
		}

		/*------------------------------------------------------------------------------------
		-- FUNCTION: deleteMarkers()
		--
		-- DATE:  April 2, 2018
		--
		-- REVISIONS: April 2, 2018
		--							Initial file set up
		--            April 5, 2018
		--              Code Comments
		--
		-- DESIGNER: Anthony Vu & Morgan Ariss
		--
		-- PROGRAMMER: Morgan Ariss
		--
		-- INTERFACE: deleteMarkers()
		--
		-- RETURNS: void
		--
		-- NOTES:
		-- Clears an array of markers.
		---------------------------------------------------------------------------------------*/
		function deleteMarkers() {
			clearMarkers();
			markers = [];
		}
		</script>
		<script async defer
		src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuWN3_CMdk4GGb6W67f2drNhzzsNu0fqw&callback=initMap">
		</script>

	</div>

</body>
</html>
