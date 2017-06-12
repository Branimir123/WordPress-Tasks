<?php 

//This file generates an XML file, later to be used by google maps to show markers according to coordinates. It acts as an intermediary between the database and the map. (Source developers.google.com)

//Global variable for WP db 
global $wpdb;

//Helper function to parse some symbols
function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}

//Coordinates are stored in wp db as wp_coordinates
$table_name = $wpdb->prefix . "geo_coordinates";

$wpdb->show_errors(true);

$result = $wpdb->get_results ("SELECT * FROM $table_name", ARRAY_A);

// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
foreach ($result as $row){
  // Add to XML document node
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("id", $row['Id']);
  $newnode->setAttribute("state",$row['state']);
  $newnode->setAttribute("city", $row['city']);
  $newnode->setAttribute("address", $row['address']);
  $newnode->setAttribute("driveThru", $row['driveThru']);
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
}

//Output the xml to the DOM doc
echo $dom->saveXML();

?>
