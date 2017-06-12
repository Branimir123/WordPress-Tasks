<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>PHP, MySQL and GoogleMaps API</title>
    
    <!--Styles-->
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>

  <body>

	<!--Dropdowns for filtering by state and driveThru-->
    	<p>Select State: </p>
 	<select name="state" onchange="filterMarkersByState(this.value);">
	<option>All States</option>
	<?php 
	$connection = new mysqli('localhost', 'wordpressuser', 'wordpresspass', 'wordpress') 
	or die ('Cannot connect to db');
	
	$sql = mysqli_query($connection, "SELECT DISTINCT state FROM wp_geo_coordinates");	

	while ($row = $sql->fetch_assoc()){
	echo '<option value='. $row['state']. '>' . $row['state']. '</option>';
	}
	?>
	</select>

	<p>Drive Through?: </p>
 	<select name="state" onchange="filterMarkersByDriveThru(this.value);">
	<option>Both</option>
	<option>No</option>
	<option>Yes</option>
	</select>

    	<div id="map"></div>

    	<!--Scripts-->
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/js-marker-clusterer/1.0.0/markerclusterer_compiled.js"></script> 		
    	<script src="maps.js"></script> 
    	<script async defer
    	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA9vpsDKKDlVn9bvLjxH6deeij1l3S1TB8&callback=initMap">
    	</script>

  </body>
</html>
