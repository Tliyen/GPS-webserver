<?php
	include('functions.php');

	// if (!isLoggedIn()) {
	// 	$_SESSION['msg'] = "You must log in first";
	// 	header('location: login.php');
	// }
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style>
	/* Always set the map height explicitly to define the size of the div
	 * element that contains the map. */
	#map {
		height: 100%;
	}
	/* Optional: Makes the sample page fill the window. */
	html, body {
		height: 100%;
		margin: 0;
		padding: 0;
	}
</style>

</head>
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
	</div>

	<div id="map"></div>

     <script>
       var customLabel = {
         restaurant: {
           label: 'R'
         },
         bar: {
           label: 'B'
         }
       };

         function initMap() {
         var map = new google.maps.Map(document.getElementById('map'), {
           center: new google.maps.LatLng(-33.863276, 151.207977),
           zoom: 12
         });
         var infoWindow = new google.maps.InfoWindow;

           // Change this depending on the name of your PHP or XML file
           downloadUrl('https://storage.googleapis.com/mapsdevsite/json/mapmarkers2.xml', function(data) {
             var xml = data.responseXML;
             var markers = xml.documentElement.getElementsByTagName('marker');
             Array.prototype.forEach.call(markers, function(markerElem) {
               var id = markerElem.getAttribute('id');
               var name = markerElem.getAttribute('name');
               var address = markerElem.getAttribute('address');
               var type = markerElem.getAttribute('type');
               var point = new google.maps.LatLng(
                   parseFloat(markerElem.getAttribute('lat')),
                   parseFloat(markerElem.getAttribute('lng')));

               var infowincontent = document.createElement('div');
               var strong = document.createElement('strong');
               strong.textContent = name
               infowincontent.appendChild(strong);
               infowincontent.appendChild(document.createElement('br'));

               var text = document.createElement('text');
               text.textContent = address
               infowincontent.appendChild(text);
               var icon = customLabel[type] || {};
               var marker = new google.maps.Marker({
                 map: map,
                 position: point,
                 label: icon.label
               });
               marker.addListener('click', function() {
                 infoWindow.setContent(infowincontent);
                 infoWindow.open(map, marker);
               });
             });
           });
         }



       function downloadUrl(url, callback) {
         var request = window.ActiveXObject ?
             new ActiveXObject('Microsoft.XMLHTTP') :
             new XMLHttpRequest;

         request.onreadystatechange = function() {
           if (request.readyState == 4) {
             request.onreadystatechange = doNothing;
             callback(request, request.status);
           }
         };

         request.open('GET', url, true);
         request.send(null);
       }

       function doNothing() {}
     </script>
		 <script async defer
	 src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDtnnSgfOR_ZFvqxQ8SiHdjeqKHDrZfsOE&callback=initMap">
	 </script>
</body>
</html>
