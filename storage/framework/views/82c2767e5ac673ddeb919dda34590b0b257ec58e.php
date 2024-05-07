<!DOCTYPE html>
<html>

<head>
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <meta name="viewport" content="initial-scale=1.0">
  <link href="<?php echo e(URL::asset('public/img/favicon.png')); ?>" rel="icon">
  <meta charset="utf-8">
  <title>World Clock Map</title>
  <style>
    html,
    body {
      margin: 0;
      padding: 0;
      width: 100vw;
      height: 100vh;
      background-color: #2b2b2b;
    }

    .map {
      width: 100vw;
      height: 100vh;
    }

    .map>div {
      background-color: #2b2b2b !important;
    }

    .gm-style-cc {
      display: none !important;
    }

    .gm-style a[href^="https://maps.google.com/maps"] {
      display: none !important;
    }
   
    .gm-style iframe+div {
      border: none !important;
    } 
   
    body .gm-style-iw.gm-style-iw-c {
      background: transparent !important;
      color: #fff;
      box-shadow: none;
      padding: 5px 2px !important;
    }

    body .gm-style .gm-style-iw-d {
      overflow: auto !important;
    }

    body .gm-style .gm-style-iw-tc {
      display: none;
    }

    div .map-pin {
      font-size: 13px;
      font-family: sans-serif;
    }

    div .map-time {
      font-size: 19px;
      font-family: sans-serif;
    }

    div .marker-div {
      text-align: center;
    }

    button.gm-ui-hover-effect {
      visibility: hidden;
    }

    .ei-date {
      position: fixed;
      top: 12px;
      left: 10px;
      z-index: 9;
      color: white;
      cursor: default;
      font-family: sans-serif;
    }
    .ei-date h2{
      margin: 0;
      font-size: 20px;
    }    

    .ei-logo-img {
      position: fixed;
      top: 10px;
      right: 10px;
      z-index: 9;
    }

    .ei-setting-img {
      position: fixed;
      bottom: 10px;
      left: 10px;
      z-index: 9;
    }

    .gm-style-iw gm-style-iw-c {
      max-height: 10px;
    }
    @media (max-width: 767px) {
      .ei-date h2{
        font-size: 17px;
      }      
      .ei-logo-img{
        top: 6px;
        right: 6px;
      }
    }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvtt0lycFTForXSvAX60V1GUQvUv7lgOU"></script>
</head>

<body>
  <div class="ei-date">
    <h2 class="ei-h3-date"></h2>   
  </div>
  <div class="ei-logo-img"><a href="https://www.ei-ie.org/en" target="_blank"><img src="<?php echo e(url('public/img/logo.png')); ?>" height="60px"></a></div>
  <div class="ei-setting-img"><a href="<?php echo e(url('/login')); ?>"><img src="<?php echo e(url('public/img/setting.png')); ?>" height="25px"></a></div>
  <div class="map" id="map"></div>
</body>
<script>
  var nite = {
    map: null,
    date: null,
    sun_position: null,
    earth_radius_meters: 6371008,
    marker_twilight_civil: null,

    init: function(map) {
      if (typeof google === 'undefined' ||
        typeof google.maps === 'undefined') throw "Nite Overlay: no google.maps detected";
      this.map = map;
      this.sun_position = this.calculatePositionOfSun();

      this.marker_twilight_civil = new google.maps.Circle({
        map: this.map,
        center: this.getShadowPosition(),
        radius: this.getShadowRadiusFromAngle(0.566666),
        fillColor: "#000",
        fillOpacity: 0.3,
        strokeOpacity: 0,
        clickable: false,
        editable: false,
        
      });
    },
    getShadowRadiusFromAngle: function(angle) {
      var shadow_radius = this.earth_radius_meters * Math.PI * 0.5;
      var twilight_dist = ((this.earth_radius_meters * 2 * Math.PI) / 360) * angle;
      return shadow_radius - twilight_dist;
    },
    getSunPosition: function() {
      return this.sun_position;
    },
    getShadowPosition: function() {
      return (this.sun_position) ? new google.maps.LatLng(-this.sun_position.lat(), this.sun_position.lng() + 180) : null;
    },
    refresh: function() {
      //if (!this.isVisible()) return;
      this.sun_position = this.calculatePositionOfSun(this.date);
      var shadow_position = this.getShadowPosition();
      this.marker_twilight_civil.setCenter(shadow_position);
      //console.log('Refresh Successfully');
    },
    jday: function(date) {
      return (date.getTime() / 86400000.0) + 2440587.5;
    },
    calculatePositionOfSun: function(date) {
      date = (date instanceof Date) ? date : new Date();

      var rad = 0.017453292519943295;

      // based on NOAA solar calculations
      var ms_past_midnight = ((date.getUTCHours() * 60 + date.getUTCMinutes()) * 60 + date.getUTCSeconds()) * 1000 + date.getUTCMilliseconds();
      var jc = (this.jday(date) - 2451545) / 36525;
      var mean_long_sun = (280.46646 + jc * (36000.76983 + jc * 0.0003032)) % 360;
      var mean_anom_sun = 357.52911 + jc * (35999.05029 - 0.0001537 * jc);
      var sun_eq = Math.sin(rad * mean_anom_sun) * (1.914602 - jc * (0.004817 + 0.000014 * jc)) + Math.sin(rad * 2 * mean_anom_sun) * (0.019993 - 0.000101 * jc) + Math.sin(rad * 3 * mean_anom_sun) * 0.000289;
      var sun_true_long = mean_long_sun + sun_eq;
      var sun_app_long = sun_true_long - 0.00569 - 0.00478 * Math.sin(rad * 125.04 - 1934.136 * jc);
      var mean_obliq_ecliptic = 23 + (26 + ((21.448 - jc * (46.815 + jc * (0.00059 - jc * 0.001813)))) / 60) / 60;
      var obliq_corr = mean_obliq_ecliptic + 0.00256 * Math.cos(rad * 125.04 - 1934.136 * jc);

      var lat = Math.asin(Math.sin(rad * obliq_corr) * Math.sin(rad * sun_app_long)) / rad;

      var eccent = 0.016708634 - jc * (0.000042037 + 0.0000001267 * jc);
      var y = Math.tan(rad * (obliq_corr / 2)) * Math.tan(rad * (obliq_corr / 2));
      var rq_of_time = 4 * ((y * Math.sin(2 * rad * mean_long_sun) - 2 * eccent * Math.sin(rad * mean_anom_sun) + 4 * eccent * y * Math.sin(rad * mean_anom_sun) * Math.cos(2 * rad * mean_long_sun) - 0.5 * y * y * Math.sin(4 * rad * mean_long_sun) - 1.25 * eccent * eccent * Math.sin(2 * rad * mean_anom_sun)) / rad);
      var true_solar_time_in_deg = ((ms_past_midnight + rq_of_time * 60000) % 86400000) / 240000;

      var lng = -((true_solar_time_in_deg < 0) ? true_solar_time_in_deg + 180 : true_solar_time_in_deg - 180);

      return new google.maps.LatLng(lat, lng);
    },
    setDate: function(date) {
      this.date = date;
      this.refresh();
    },
    setMap: function(map) {
      this.map = map;
      this.marker_twilight_civil.setMap(this.map);
    },
    show: function() {
      this.marker_twilight_civil.setVisible(true);
      this.refresh();
    },
    hide: function() {
      this.marker_twilight_civil.setVisible(false);
    },
    isVisible: function() {
      return this.marker_night.getVisible();
    }
  };

  var googleMap;

  function initMap() {
    var uluru = {
      lat: 26.2041,
      lng: 28.0473
    };

    googleMap = new google.maps.Map(document.getElementById('map'), {
      zoom: 2,
      center: uluru,
      disableDefaultUI: true,
      // panControl: false,
      // draggable: false,
      streetViewControl: false,
      // disableDoubleClickZoom: true,
      // scrollwheel: false,
      minZoom: 2.4,
      restriction: {
        latLngBounds: {
          north: 85,
          south: -85,
          west: -180,
          east: 180
        },
        strictBounds: true
      },
      styles: [{
        "featureType": "all",
        "elementType": "labels.text.fill",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "all",
        "elementType": "labels.text.stroke",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "all",
        "elementType": "labels.icon",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "administrative",
        "elementType": "labels",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "administrative.country",
        "elementType": "all",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "administrative.country",
        "elementType": "geometry",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "administrative.country",
        "elementType": "labels.text",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "administrative.province",
        "elementType": "all",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "administrative.locality",
        "elementType": "all",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "administrative.neighborhood",
        "elementType": "all",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "administrative.land_parcel",
        "elementType": "all",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [{
          "visibility": "simplified"
        }, {
          "lightness": "5"
        }]
      }, {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [{
          "color": "#000000"
        }, {
          "lightness": 28
        }]
      }, {
        "featureType": "landscape.man_made",
        "elementType": "all",
        "stylers": [{
          "lightness": "3"
        }]
      }, {
        "featureType": "poi",
        "elementType": "all",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [{
          "color": "#000000"
        }, {
          "lightness": 21
        }]
      }, {
        "featureType": "road",
        "elementType": "geometry",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [{
          "color": "#000000"
        }, {
          "lightness": 17
        }]
      }]
    });

    
  $(document).ready(function() {
  var icon = {
    url: "<?php echo e(url('public/img/pin.png')); ?>",
    scaledSize: new google.maps.Size(16, 16), // size
  };
  
  var url = "<?php echo e(url('')); ?>" + "/getCity";
  
  var markers = []; // Array to store markers

  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: url,
    type: "GET",
    success: function(response) {
      const address = JSON.parse(response);

      address.forEach((key, value) => {
        const latitude = parseFloat(address[value].latitude);
        const longitude = parseFloat(address[value].longitude);
        const positionValue = {
          lat: latitude,
          lng: longitude
        };
        var secretMessage = address[value].name;
        var timeZoneId = address[value].timezones;
        var options = {
          timeZone: timeZoneId,
          timeStyle: 'medium',
          hourCycle: 'h23',
        };

        var marker = new google.maps.Marker({
          position: positionValue,
          map: googleMap,
          icon: icon,
        });
        markers.push(marker);

        var infowindow = new google.maps.InfoWindow({          
          pixelOffset: new google.maps.Size(0, 20),
        });       

        infowindow.open(map, marker);

        function updateInfoWindow() {
          var currentTime = new Date().toLocaleString('en-US', options);
          var currentarray = currentTime.split(":");
          var time = currentarray[0] + ":" + currentarray[1];
          console.log(secretMessage + "  -->  " + time);

          var contentString = '<div class="marker-div"><span class="map-pin"><b>' + secretMessage + '</b></span><br><span class="map-time">' + time + '</span></div>';
          infowindow.setContent(contentString);
        }

        updateInfoWindow();
        setInterval(updateInfoWindow, 1000);
      })
    }
  });
});

  }
  (() => {
    var nightMap = initMap()
    nite.init(googleMap);
    setInterval(function() {
      nite.refresh()
    }, 1000); // every 1s
  })();
</script>
<script type="text/javascript">
$(document).ready(function() {
  // Display Date Start
  var date = new Date();
  var day = date.getDate();
  var formattedDay;

  // Add the appropriate suffix to the day
  if (day >= 11 && day <= 13) {
    formattedDay = day + "th";
  } else {
    switch (day % 10) {
      case 1:
        formattedDay = day + "st";
        break;
      case 2:
        formattedDay = day + "nd";
        break;
      case 3:
        formattedDay = day + "rd";
        break;
      default:
        formattedDay = day + "th";
    }
  }

  var dateFormat = new Intl.DateTimeFormat('en-GB', {
    month: 'short',
    year: 'numeric',
    timeZone: 'Europe/Brussels'
  });

  const formattedDate = formattedDay + " " + dateFormat.format(date);
  $(".ei-h3-date").text(formattedDate);
  // Display Date End  
});

$(document).ready(function() {
  document.addEventListener('keydown', function(event) {
    if (event.ctrlKey && (event.key === '-' || event.key === 'Subtract')) {
        event.preventDefault();
    }
  });

  document.addEventListener('keydown', function(event) {
    if (event.ctrlKey && (event.key === '+' || event.key === '=')) {
      event.preventDefault();
      // Optionally, you can add custom behavior here
    }
  });

  document.addEventListener('keydown', event => {
    console.log(`User pressed: ${event.key}`);
    event.preventDefault();
    return false;
  });

});
</script>

</html><?php /**PATH /home3/cyblasjd/wixwebsitesbuilder.com/world-map-clock/resources/views/welcome.blade.php ENDPATH**/ ?>