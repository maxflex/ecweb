// Generated by CoffeeScript 1.9.3
var ICON_BLUE, ICON_GREEN, ICON_HOME, ICON_HOME_BLUE, ICON_RED, ICON_SCHOOL, ICON_SCHOOL_BLUE, ICON_SEARCH, INIT_COORDS, MAP_CENTER, RECOM_BOUNDS;

ICON_WHITE = {
  url: "/img/maps/whitepin.png",
  scaledSize: new google.maps.Size(22, 40),
  origin: new google.maps.Point(0, 0)
};

ICON_BLUE = {
  url: "/img/maps/bluepin.png",
  scaledSize: new google.maps.Size(22, 40),
  origin: new google.maps.Point(0, 0)
};

ICON_RED = {
  url: "/img/maps/redpin.png",
  scaledSize: new google.maps.Size(22, 40),
  origin: new google.maps.Point(0, 0)
};

ICON_GREEN = {
  url: "/img/maps/greenpin.png",
  scaledSize: new google.maps.Size(22, 40),
  origin: new google.maps.Point(0, 0)
};

ICON_SCHOOL = {
  url: "/img/maps/schoolpin.png",
  scaledSize: new google.maps.Size(22, 40),
  origin: new google.maps.Point(0, 0)
};

ICON_SCHOOL_BLUE = {
  url: "/img/maps/schoolpin_blue.png",
  scaledSize: new google.maps.Size(22, 40),
  origin: new google.maps.Point(0, 0)
};

ICON_HOME = {
  url: "/img/maps/homepin.png",
  scaledSize: new google.maps.Size(22, 40),
  origin: new google.maps.Point(0, 0)
};

ICON_HOME_BLUE = {
  url: "/img/maps/homepin_blue.png",
  scaledSize: new google.maps.Size(22, 40),
  origin: new google.maps.Point(0, 0)
};

ICON_SEARCH = {
  url: "/img/maps/bluepin.png",
  scaledSize: new google.maps.Size(22, 40),
  origin: new google.maps.Point(0, 0)
};

INIT_COORDS = {
  lat: 55.7387,
  lng: 37.6032
};

MAP_CENTER = new google.maps.LatLng(55.7387, 37.6032);

RECOM_BOUNDS = new google.maps.LatLngBounds(new google.maps.LatLng(INIT_COORDS.lat - 0.5, INIT_COORDS.lng - 0.5, new google.maps.LatLng(INIT_COORDS.lat + 0.5, INIT_COORDS.lng + 0.5)));

BRANCH_COORDS = {
    TRG: {
        lat: 55.76678,
        lng: 37.64141,
    },
	PVN: {
        lat: 55.676986,
        lng: 37.509622
    },
	BGT: {
        lat: 55.744683,
        lng: 37.494297
    },
	IZM: {
        lat: 55.789345,
        lng: 37.777526
    },
	OPL: {
        lat: 55.789709,
        lng: 37.498896
    },
	RPT: {
        lat: 55.714663,
        lng: 37.790893
    },
	VKS: {
        lat: 55.824602,
        lng: 37.501384
    },
	ORH: {
        lat: 55.612853,
        lng: 37.702113
    },
	UJN: {
        lat: 55.620901,
        lng: 37.610449
    },
	PER: {
        lat: 55.750038,
        lng: 37.787192
    },
	KLG: {
        lat: 55.656311,
        lng: 37.551384
    },
	BRT: {
        lat: 55.664055,
        lng: 37.753245
    },
	MLD: {
        lat: 55.73591,
        lng: 37.41111,
    },
	VLD: {
        lat: 55.842661,
        lng: 37.579619
    }
}

BRANCH_ADDRESS = {
    TRG: {
        name: 'ЕГЭ-Центр-Тургеневская',
        time: '11:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'ул. Мясницкая, д. 40, стр. 1'
    },
	PVN: {
        name: 'ЕГЭ-Центр-Калужская',
        time: '12:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'Научный проезд, д. 8, стр. 1'
    },
	BGT: {
        name: 'ЕГЭ-Центр-Калужская',
        time: '12:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'Научный проезд, д. 8, стр. 1'
    },
	IZM: {
        name: 'ЕГЭ-Центр-Калужская',
        time: '12:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'Научный проезд, д. 8, стр. 1'
    },
	OPL: {
        name: 'ЕГЭ-Центр-Калужская',
        time: '12:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'Научный проезд, д. 8, стр. 1'
    },
	RPT: {
        name: 'ЕГЭ-Центр-Калужская',
        time: '12:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'Научный проезд, д. 8, стр. 1'
    },
	VKS: {
        name: 'ЕГЭ-Центр-Калужская',
        time: '12:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'Научный проезд, д. 8, стр. 1'
    },
	ORH: {
        name: 'ЕГЭ-Центр-Калужская',
        time: '12:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'Научный проезд, д. 8, стр. 1'
    },
	UJN: {
        name: 'ЕГЭ-Центр-Калужская',
        time: '12:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'Научный проезд, д. 8, стр. 1'
    },
	PER: {
        name: 'ЕГЭ-Центр-Калужская',
        time: '12:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'Научный проезд, д. 8, стр. 1'
    },
	KLG: {
        name: 'ЕГЭ-Центр-Калужская',
        time: '12:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'Научный проезд, д. 8, стр. 1'
    },
	BRT: {
        name: 'ЕГЭ-Центр-Калужская',
        time: '12:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'Научный проезд, д. 8, стр. 1'
    },
	MLD: {
        name: 'ЕГЭ-Центр-Калужская',
        time: '12:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'Научный проезд, д. 8, стр. 1'
    },
	VLD: {
        name: 'ЕГЭ-Центр-Калужская',
        time: '12:00-20:00',
        phone: '+7 (495) 646-85-92',
        address: 'Научный проезд, д. 8, стр. 1'
    }
}

newMarker = function(latLng, map, type) {
  if (type == null) {
    type = 'green';
  }
  return new google.maps.Marker({
    map: map,
    position: latLng,
    icon: getMarkerType(type),
    type: type,
    lat: latLng.lat(),
    lng: latLng.lng(),
  });
};

newTooltip = function(marker, branch) {
    text = ['<span class="font-medium">' + branch.name + '</span>',
          'Часы работы: ' + branch.time,
          'Телефон: ' + branch.phone,
          'Адрес: ' + branch.address].join('<br>')
    tooltipOptions = {
	  marker: marker,    // required
	  content: text,    // required
	  cssClass: 'speech-bubble' // name of a css class to apply to tooltip
	};
    return new Tooltip(tooltipOptions)
}

getMarkerType = function(type) {
    switch (type) {
        case 'green': {
            return ICON_GREEN
        }
        case 'red': {
            return ICON_RED
        }
        case 'blue': {
            return ICON_BLUE
        }
        case 'white': {
            return ICON_WHITE
        }
    }
}

addMarker = function(map, latLng) {
  return new google.maps.Marker({
    map: map,
    position: latLng
  });
};
