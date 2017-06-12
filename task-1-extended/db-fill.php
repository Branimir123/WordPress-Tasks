<?php 

global $wpdb;
$wpdb->show_errors(true);

//URL to some random geoJSON with McDonalds shops coordinates
$url = "https://raw.githubusercontent.com/gavinr/usa-mcdonalds-locations/master/mcdonalds.geojson";

//In this case the json is not that big, but this can be a problem if dealing with bigger JSON file. However most APIs deal with this using pagination so you get the data in chunks. 
$json = file_get_contents($url);
$result = json_decode($json,true);

//This works but is is slow because it executes at every row

//foreach($result['features'] as $line){
//	$wpdb->insert("wp_coordinates", array(
//		'name' => $line["properties"]["city"],
//		'address' => $line["properties"]["address"],
//		'lat' => $line["geometry"]["coordinates"][0],
//		'lng' => $line["geometry"]["coordinates"][1]	
//	));
//}


$sql = "INSERT INTO wp_coordinates (name, address, lat, lng) VALUES";

//Idea here is to chop the array into peaces and execute the query for 1000 rows at one time. This works a lot faster.
foreach(array_chunk($result['features'], 1000) as $chunk){
	$sql = "INSERT INTO wp_geo_coordinates (storeType, address, city, state, phone, driveThru, freeWifi, lat, lng) VALUES ";
	
	foreach($chunk as $line){
        $city = addslashes($line["properties"]["city"]);
        $address = addslashes($line["properties"]["address"]);
        $storeType = $line["properties"]["storeType"];
        $state = $line["properties"]["state"];
        $phone = $line["properties"]["phone"];
        $driveThru = $line["properties"]["driveThru"];
        $freeWifi = $line["properties"]["freeWifi"];
        $lat = $line["geometry"]["coordinates"][0];
        $lng = $line["geometry"]["coordinates"][1];        

        $sql.= 
		"(
		'{$storeType}', 
		'{$address}',
		'{$city}',
		'{$state}',
		'{$phone}',
		'{$driveThru}',
		'{$freeWifi}', 
		 {$lat},
		 {$lng} 
		), ";		
	}
	
	$sql = substr($sql, 0, -2);
	$sql.= ";";
	$wpdb->query($sql);		
}
?>
