<?php
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
		<div id="map"></div>
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
									//console.log(name + " " + typeof(parseFloat(lat)) + " " + typeof(parseFloat(long)));
									var loc = new google.maps.LatLng(parseFloat(long),parseFloat(lat));
									//var loc = {lat: parseFloat(lat), lng: parseFloat(long)};
									//console.log("adding markers");
									addMarker(loc, name, time);
									//setMapOnAll(map);
							}
					}
			}

		function addMarker(location, name, time) {
			var marker = new google.maps.Marker({
				position: location,
				map: map,
				title: "Name: " + name + "\nTime: " + time
			});
			markers.push(marker);
		}

		// Sets the map on all markers in the array.
		function setMapOnAll(map) {
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(map);
			}
		}

		// Removes the markers from the map, but keeps them in the array.
		function clearMarkers() {
			setMapOnAll(null);
		}

		// Shows any markers currently in the array.
		function showMarkers() {
			setMapOnAll(map);
		}

		// Deletes all markers in the array by removing references to them.
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
