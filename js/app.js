//Konvert To RP
function toRp(angka){
    var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
    var rev2    = '';
    for(var i = 0; i < rev.length; i++){
        rev2  += rev[i];
        if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
            rev2 += '.';
        }
    }
    return 'Rp. ' + rev2.split('').reverse().join('') + ',00';
}
//Grafik Function
function gf(posisi,title, ytitle, tipe, cate, data, legend){
	$(function () {
	    var chart;
	    $(document).ready(function() {
	        chart = new Highcharts.Chart({
	            chart: {
	                renderTo: posisi, //letakan grafik di div id container
					//Type grafik, anda bisa ganti menjadi area,bar,column dan bar
	                type: tipe,  
	                marginRight: 130,
	                marginBottom: 25
	            },
	            title: {
	                text: title,
	                x: -20 //center
	            },
	            subtitle: {
	                text: '',
	                x: -20
	            },
	            xAxis: { //X axis menampilkan data bulan 
	                categories: cate
	            },
	            yAxis: {
	                title: {  //label yAxis
	                    text: ytitle
	                },
	                plotLines: [{
	                    value: 0,
	                    width: 1,
	                    color: '#808080' //warna dari grafik line
	                }],
	            },
	            tooltip: { 

				//fungsi tooltip, ini opsional, kegunaan dari fungsi ini 
				//akan menampikan data di titik tertentu di grafik saat mouseover
					    formatter: function() {
		                        if (ytitle == '(Rp)'){
		                        	return ''+this.x +': <em>'+ toRp(this.y) +'</em>';
		                        } else if (ytitle == '%'){
		                        	return ''+this.x +': '+ this.y +'%';
		                        } else if (ytitle == 'Tahun'){
                              return ''+this.x +': '+ this.y +'%';
                            } else {
		                        	return ''+this.x +': '+ this.y;
		                        }
						}
	                
	            },
	            legend: {
	                layout: 'vertical',
	                align: 'right',
	                verticalAlign: 'top',
	                x: -10,
	                y: 100,
	                borderWidth: 0,
	                enabled: legend
	            },
				//series adalah data yang akan dibuatkan grafiknya,
			
	            series: data
	        });
	    });
	    
	});
}



/* GOOGLE MAPS Function */
    var customIcons = {
      good: {
        icon: 'img/m0.png'
      },
      warning: {
        icon: 'img/m1.png'
      },
      danger: {
        icon: 'img/m2.png'
      }
    };
    function load() {
      var map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(-2.5684749326289116, 115.09863138198853),
        zoom: 5,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      //Legend
      
      map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(
        document.getElementById('legend')
      );
      
      
      var legend = document.getElementById('legend');
      var namaLegend = ['Aman', 'Siaga', 'Waspada'];
      for (i = 0; i < 3;i++) {
        var div = document.createElement('div');
        div.innerHTML = '<img src="img/m'+ i +'.png">' + namaLegend[i];
        legend.appendChild(div);
      }

      // Change this depending on the name of your PHP file
      downloadUrl("gmaps_xml.php", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
          var target = markers[i].getAttribute("target");
          var pencapaian = markers[i].getAttribute("pencapaian");
          var persentase = markers[i].getAttribute("persentase");
          var type;

          if (persentase > 60){
            type='good';
          } else if(persentase <= 60 && persentase > 30){
            type='warning';
          } else {
            type='danger';
          }

          var point = new google.maps.LatLng(
          		parseFloat(markers[i].getAttribute("lat")),
          		parseFloat(markers[i].getAttribute("lng"))
          	);
          var html = "<b>" + name + "</b> <br/>Target : Rp." + target + 
                     "<br /> Pencapaian : Rp." + pencapaian + 
                     "<br /> Persentase : " + persentase + "%" ;
          var icon = customIcons[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon,
          });
          bindInfoWindow(marker, map, infoWindow, html, markers[i].getAttribute("name"));
        }
      });
    }

    function bindInfoWindow(marker, map, infoWindow, html, name) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);

        $.ajax({
        	type: "POST",
        	url: "loadData.php?aksi=pencapaian",
        	data: 'kota=' + name,
        	cache: false,
        	success: function(html){
        		$("#loaddata").html(html);
        	}
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