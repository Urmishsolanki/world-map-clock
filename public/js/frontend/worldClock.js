var nite = {
    map: null,
    date: null,
    sun_position: null,
    earth_radius_meters: 6371008,
    marker_twilight_civil: null,
    marker_twilight_nautical: null,
    marker_twilight_astronomical: null,
    marker_night: null,

    init: function(map) {
      console.log("Excuted")
      if (typeof google === 'undefined' ||
        typeof google.maps === 'undefined') throw "Nite Overlay: no google.maps detected";
      this.map = map;
      this.sun_position = this.calculatePositionOfSun();
      
      this.marker_twilight_civil = new google.maps.Circle({
        map: this.map,
        center: this.getShadowPosition(),
        radius: this.getShadowRadiusFromAngle(0.566666),
        fillColor: "#000000",
        fillOpacity: 0.1,
        strokeOpacity: 0,
        clickable: false,
        editable: false
      });
      this.marker_twilight_civil = new google.maps.Circle({
        map: this.map,
        center: this.getShadowPosition(),
        radius: this.getShadowRadiusFromAngle(0.566666),
        fillColor: "#000000",
        fillOpacity: 0.3,
        strokeOpacity: 0,
        clickable: false,
        editable: false
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
      if (!this.isVisible()) return;      
      this.sun_position = this.calculatePositionOfSun(this.date);
      var shadow_position = this.getShadowPosition();
      this.marker_twilight_civil.setCenter(shadow_position);
      this.marker_twilight_nautical.setCenter(shadow_position);
      this.marker_twilight_astronomical.setCenter(shadow_position);
      this.marker_night.setCenter(shadow_position);
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
      this.marker_twilight_nautical.setMap(this.map);
      this.marker_twilight_astronomical.setMap(this.map);
      this.marker_night.setMap(this.map);
    },
    show: function() {
      this.marker_twilight_civil.setVisible(true);
      this.marker_twilight_nautical.setVisible(true);
      this.marker_twilight_astronomical.setVisible(true);
      this.marker_night.setVisible(true);
      this.refresh();
    },
    hide: function() {
      this.marker_twilight_civil.setVisible(false);
      this.marker_twilight_nautical.setVisible(false);
      this.marker_twilight_astronomical.setVisible(false);
      this.marker_night.setVisible(false);
    },
    isVisible: function() {
      return this.marker_night.getVisible();
    }
};

var googleMap;

function initMap() {
  
  var uluru = {
    lat: 24.2155,
    lng: 12.8858
  };

  googleMap = new google.maps.Map(document.getElementById('map'),{
    zoom: 2,
    center: uluru,
    disableDefaultUI: true,
    //scrollwheel: false, 
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
        "gamma": "0.00"
      }, {
        "lightness": "74"
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

  const API_KEY = "AIzaSyCfxUDM8y9U9Kh9SU5x-ol9ReIppkUUbvw";
  const address = ["Los Angeles", "Toronto", "Seoul", "Moscow", "Mexico City", "Frankfurt", "Hong kong", "Johannesburg", "Wellington"];
  var image = 'http://192.168.1.173/urmish/demo/world-map-clock/public/img/pin.png';

  address.forEach((key, value) => {
      const url = `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(key)}&key=${API_KEY}`;
      fetch(url).then(response => response.json()).then(data => {           
      let location = data.results[0].geometry.location;      
      const secretMessage = data.results[0].address_components[0].long_name;
      //console.log(secretMessage);

      fetch(`https://maps.googleapis.com/maps/api/timezone/json?location=${location.lat},${location.lng}&timestamp=${Math.floor(Date.now() / 1000)}&key=${API_KEY}`).then(response => response.json()).then(data => {
          const currentDate = new Date();
          const offset = data.rawOffset + data.dstOffset;
          const localTime = new Date(currentDate.getTime() + offset * 1000);
          console.log(data)
          
          var marker = new google.maps.Marker({
            position: location,
            map: googleMap,
            icon: image,
          });
          const infowindow = new google.maps.InfoWindow({
            content:"<span style='font-size:1.400em;'><b>" + secretMessage + "</b></span",

          });
          infowindow.open(map, marker); 

          // Do something with localTime
          })
          .catch(error => console.error(error));     

      });
  });    
}

(() => {
  var nightMap = initMap()    
  nite.init(googleMap);
})();

