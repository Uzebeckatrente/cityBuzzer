$(".city").on("click", function(e){
	// alert($("#city").val());
	// console.log($(this).data()["cityName"]);
	$newVal = $(this).data()["cityName"];
	$("#city").val($newVal);
	// alert($("#city").val());
	$(".cityDisplay").text($newVal);
	$(".dropdown").unbind('mouseout');
	$(this).unbind('mouseout');
	$(".dropdown").off('mouseout');
	$(this).off('mouseout');
	$(".dropdown-content").unbind('mouseout');
	$(".dropdown-content").off('mouseout');
	// $('.dropdown-content').css({'pointer-events': 'none'})
	// $('.dropdown').css({'pointer-events': 'none'});


});



var allTweets = [];
var started = false;
var map;
var map2;
var heatmap;
var heatmap2;
var dict = {};
var tweetqueue = [];

var lat;
var lon;

var latLonArray = [];

mapHashDict = {};

heatMapPoints = {0:[],1:[]};

var extracounter = 0;

function addUnique(tweet){
	if (allTweets.indexOf(tweet) > -1){
		return -1;
	}
	allTweets.unshift(tweet);
	return 1;
}

function generateRandomNumber(max,min) {
	
    rand = Math.random() * (max - min) + min;

    return rand;
};


function addMarkerToMap(map, lat,long,icon,text){
	var position = {lat: lat, lng: long};
	var contentString = '<div id="content">'+
            '<h1 id="firstHeading" class="firstHeading" style="background: #00aced;border-radius: 10px;line-height: 37px">Tweet</h1>'+
            '<div id="bodyContent">'+
            '<p style="max-width: 300px; font-size: 20px;color: black">'+text+'</p>'
            '</div>';
    var infowindow = new google.maps.InfoWindow({
      content: contentString
    });
	if (icon != undefined){
		contentString = '<div id="content" >'+
            '<h1 style="width: 170px;border-radius: 10px;background: beige;color: black;" id="firstHeading" class="firstHeading">Your Position</h1></div>';
		infowindow = new google.maps.InfoWindow({
			content: contentString
		});
		var marker = new google.maps.Marker({
			position: position,
			map: map,
			icon: icon,
			size: new google.maps.Size(5, 5),
		});
		marker.addListener('click', function() {
          infowindow.open(map, marker);
        });
	}else{
		

        
		var marker = new google.maps.Marker({
			position: position,
			map: map,
			visible: true,
			opacity:0.05
		});
		marker.addListener('click', function() {
          infowindow.open(map, marker);
        });
	}
}

// $(window).keypress(function (e) {
//   if (e.keyCode === 49) {
//     extracounter = 1;
//     // alert("bop");
//   }
// })


function initMap(){
	$.ajax({
	    url:"/getLatLon.php",
	    type:'get',
	    success:function(sResponse){
	    
	    	// console.log(sResponse);
	    	var latLon = JSON.parse(sResponse);
	    	lat = parseFloat(latLon[0]);
	    	lon = parseFloat(latLon[1]);
	    	latLonArray[0] = lat;
	    	latLonArray[1] = lon;
	    	// console.log(typeof(lat));
	    	// console.log(lat);
	    	// console.log(typeof(lon));
	    	// console.log(lon);
	    	map = new google.maps.Map(document.getElementById('map'),{
	    		zoom:15,
	    		center:{lat: lat, lng: lon}
	    	});
	    	map2 = new google.maps.Map(document.getElementById('map2'),{
	    		zoom:15,
	    		center:{lat: lat, lng: lon}
	    	});
	    	addMarkerToMap(map,lat,lon,"you.png","");
	    	addMarkerToMap(map2,lat,lon,"you.png","");

	    	heatmap = new google.maps.visualization.HeatmapLayer({
	          data: [],
	          map: map
	        });
	        heatmap2 = new google.maps.visualization.HeatmapLayer({
	          data: [],
	          map: map2
	        });
	    }
	});
}


function tweetArrayToString(a){
	str = "";
	for (var i = 0; i < a.length; i ++){
		str = str+ a[i] + "<br>";
	}


	return str;
}

function updateTweets(){
	console.log("updating tweeets\n");
	$.ajax({
        url:"/action_page.php",
        type:'post',
        data:$('#myForm').serialize(),
        success:function(sResponse){
        	console.log("alertinnn");
        	// console.log(sResponse);
        	jResponse = JSON.parse(sResponse);

        	// console.log(sResponse);
        	console.log("len",jResponse.length);

			
        	// alert(sResponse);
        	// var oResponse = JSON.parse(sResponse);
        	// we only return the geometrz 
/*NULL
{"geometry":{"bounds":{"northeast":{"lat":52.67545419999999722904249210841953754425048828125,"lng":13.7611174999999992252242009271867573261260986328125},"southwest":{"lat":52.33823399999999992360244505107402801513671875,"lng":13.0883459999999995915231920662336051464080810546875}},"location":{"lat":52.520006599999987884075380861759185791015625,"lng":13.404954000000000036152414395473897457122802734375},"location_type":"APPROXIMATE","viewport":{"northeast":{"lat":52.67545419999999722904249210841953754425048828125,"lng":13.7611174999999992252242009271867573261260986328125},"southwest":{"lat":52.33823399999999992360244505107402801513671875,"lng":13.0883459999999995915231920662336051464080810546875}}}}
*/


				// var oGeometry = {
				// 	lat: oResponse.location.lat,
				// 	lng: oResponse.location.lng
				// };

				// console.log(oGeometry);
				// alert(oGeometry);
				// now we need to some jquerz dideling 

			var rands = [];
			for (var i = 0; i < 5; i ++){
				var myRand = parseInt(generateRandomNumber(0,jResponse.length));
				while (myRand in rands){
					var myRand = parseInt(generateRandomNumber(0,jResponse.length));
				}
				console.log("inter: "+jResponse[myRand]);
				tweetqueue[i] = jResponse[myRand];
				rands.push(myRand);
			}
			// console.log(tweetqueue);
			// console.log(jResponse);
			// alert("bop:);");
			

			

			var hashtags = $(".hashtags").val();
			hashtagsArray = hashtags.split(',');

			mapHashDict[hashtagsArray[0]] = map;
			mapHashDict[hashtagsArray[1]] = map2;
			$("#map1hashtag").text(hashtagsArray[0]);
			$("#map2hashtag").text(hashtagsArray[1]);

			console.log("hashtagsarray: ",hashtagsArray);
			console.log(hashtagsArray);


			for (var i = 0; i < hashtagsArray.length; i ++){

				if (!(hashtagsArray[i] in dict)){
					dict[hashtagsArray[i]]=0;
					
				}
			}
			// console.log(jResponse.length + " " + hashtagsArray.length);
			// console.log(jResponse);
			// console.log(hashtagsArray);



			for (var i = 0; i < jResponse.length; i ++){
				// console.log(jResponse[i]);
				// console.log(allTweets.indexOf(jResponse[i]) > -1);
				if (addUnique(jResponse[i]) == -1){

					continue;
				}
				console.log(extracounter);
				for (var j = 0; j < hashtagsArray.length; j ++){
					if ((jResponse[i]).toLowerCase().indexOf((hashtagsArray[j]).toLowerCase()) != -1){
						dict[hashtagsArray[j]] = dict[hashtagsArray[j]] + 1;
						myLat = latLonArray[0]+Math.pow((-1),Math.floor(Math.random()*2))*Math.log(generateRandomNumber(0.15,1))/1000;
						myLon = latLonArray[1]+Math.pow((-1),Math.floor(Math.random()*2))*Math.log(generateRandomNumber(0.15,1))/1000;
						addMarkerToMap(mapHashDict[hashtagsArray[j]],myLat,myLon,undefined,jResponse[i]);
						heatMapPoints[j].push(new google.maps.LatLng(myLat,myLon));
					}
				}

			}
			// if (generateRandomNumber(0,2) > 1){
			// 	for (var i = 0; i < generateRandomNumber(1,2); i ++){
			// 		addMarkerToMap(mapHashDict[hashtagsArray[1]],latLonArray[0]+generateRandomNumber(-0.005,0.005),latLonArray[1]+generateRandomNumber(-0.005,0.005),undefined);
			// 		addMarkerToMap(mapHashDict[hashtagsArray[0]],latLonArray[0]+generateRandomNumber(-0.005,0.005),latLonArray[1]+generateRandomNumber(-0.005,0.005),undefined);

			// 	}
			// }
			heatmap.setData(heatMapPoints[0]);
			heatmap2.setData(heatMapPoints[1]);

			console.log("first point: ",heatMapPoints[0][0]);
			console.log(heatmap.getData()==heatMapPoints[0]);

			// console.log(dict);
			// console.log(tweetqueue);
			// console.log(tweetArrayToString(tweetqueue));
			$("#latestTweets").show().html(tweetArrayToString(tweetqueue));
			// $("#latestTweets").show().html(tweetArrayToString(jResponse.slice(0,6)));

        	//console.log(oResponse);
        	//alert("wop wop wop");
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("Status: " + textStatus); alert("Error: " + errorThrown); 
        }
    });
}

$(window).keypress(function (e) {
  if (started && (e.keyCode === 0 || e.keyCode === 32)) {
    e.preventDefault();
    console.log('Space pressed')
    updateTweets();
  }
})


$(".go").on("click",function(e){
	started=true;
	// alert("m189");
	e.preventDefault();
	updateTweets();
	$(".gridContainer").css({"display":"none"});
	// setInterval(updateTweets, 14000);
	// setInterval(updateTweets, 100000);
	
})


