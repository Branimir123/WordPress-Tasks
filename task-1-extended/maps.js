//Function that initializes the map
function initMap() {
       var map = new google.maps.Map(document.getElementById('map'), {
           center: new google.maps.LatLng(25.789141, -80.140924),
       zoom: 5
});

var infoWindow = new google.maps.InfoWindow;
var marks = [];
var markerClusterer = new MarkerClusterer(map, marks, 
						{imagePath: 'clustererImages/'});
//Filter functions binded with the dropdowns
filterMarkersByState = function (state) {
  var newmarkers = [];
  
  for (i = 0; i < marks.length; i++) {
    marker = marks[i];
    // If it is the same state or not picked
    if (marker.state == state || state.length === 0 || state == "All States") {
      newmarkers.push(marker);
    }
  }
  
  markerClusterer.clearMarkers();
  markerClusterer.addMarkers(newmarkers);
}

filterMarkersByDriveThru = function (driveThru) {
  var newmarkers = [];
  
  for (i = 0; i < marks.length; i++) {
    marker = marks[i];
    // If is same category or category not picked
    if (marker.driveThru == driveThru.substring(0, 1)
	 || driveThru.length === 0
	 || driveThru == "Both") {
      newmarkers.push(marker);
    }
  }
  markerClusterer.clearMarkers();
  markerClusterer.addMarkers(newmarkers);
}

//downloadUrl gets the XML and creates markers that are applied on the map
// http://localhost returns an XML with data in my case, but it can be used with any other XML document in the same format
downloadUrl('http://localhost', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
         
	    Array.prototype.forEach.call(markers, function(markerElem) {
              var id = markerElem.getAttribute('id');
              var address = markerElem.getAttribute('address');
	      var state = markerElem.getAttribute('state');
	      var city = markerElem.getAttribute('city');
	      var driveThru = markerElem.getAttribute('driveThru');
              
	      var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lng')),
                  parseFloat(markerElem.getAttribute('lat')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = address;
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = state + ", " + city;
              infowincontent.appendChild(text);

	      infowincontent.appendChild(document.createElement('br'));

	      var text = document.createElement('text');
              text.textContent = 'Is drive through: ' + driveThru;
              infowincontent.appendChild(text);
             
	      var marker = new google.maps.Marker({
		//Uncomennting the next row will cause every marker to be set on the map, but we want marker clusterer to deal with this so it is ommited
                // map: map,
                position: point,
		state: state,
		city: city,
		address: address,
		driveThru: driveThru
              });
	     	
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
	     
	      marks.push(marker);
            });
	    
	    //This clusters the markers so they don't cover each other
	    markerClusterer.addMarkers(marks);
          });

}

function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = done;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
}


function done() {
    console.log("done");
}
