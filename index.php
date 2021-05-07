<!DOCTYPE HTML>
<head>
   <title>Sensing Station</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <script src="https://code.highcharts.com/highcharts.js"></script>
   <style>
      body {
      min-width: 310px;
      max-width: 800px;
      height: 400px;
      margin: 0 auto;
      }
      h2 {
      font-family: Arial;
      font-size: 2.5rem;
      text-align: center;
      }
   </style>
</head>
<body>
   <h2>Remote Sensing Station</h2>
   <div id="chart-pm" class="container"></div>
   <br>
   <br>
   <div id="chart-aqi" class="container"></div>
   <br>
   <br>
   <div id="chart-co" class="container"></div>
   <br>
</body>
<script>
   var freq = 31000;
   var qty = 35;
   var localtimezone = 19800000;
   var chartT = new Highcharts.Chart({
     chart:{ renderTo : 'chart-pm' },
     title: { text: 'Particulate Matter 1.0 ' },
     series: [{
    name: 'PM 1.0',
       showInLegend: false,
       data: []
     }],
     plotOptions: {
       line: { animation: false,
         dataLabels: { enabled: true }
       },
       series: { color: '#059e8a' }
     },
     xAxis: { type: 'datetime',
        dateTimeLabelFormats: { second: '%H:%M:%S' }
     },
     yAxis: {
       title: { text: 'ug/m3' }
     },
     credits: { enabled: false }
   });
   setInterval(function ( ) {
     var xhttp = new XMLHttpRequest();
     xhttp.onreadystatechange = function() {
       if (this.readyState == 4 && this.status == 200) {
           var rq = this.responseText;
           var rsp = rq.split(",");
           var y = parseFloat(rsp[0]);
          var x = ((new Date(rsp[1])).getTime()) + localtimezone;
            console.log(x);     
         if( this.responseText === "NaN"){
      console.log(this.responseText);
    }
    else{
      if(chartT.series[0].data.length > qty) {
                chartT.series[0].addPoint([x, y], true, true, true);
            } else {
              chartT.series[0].addPoint([x, y], true, false, true);
            }
    }
       }
     };
     xhttp.open("GET", "/pm-val.php", true);
     xhttp.send();
   }, freq ) ;
   
   var chartH = new Highcharts.Chart({
     chart:{ renderTo:'chart-aqi' },
     title: { text: 'Air Quality Index (AQI)' },
     series: [{
    name: 'AQI',
       showInLegend: false,
       data: []
     }],
     plotOptions: {
       line: { animation: false,
         dataLabels: { enabled: true }
       }
     },
     xAxis: {
       type: 'datetime',
       dateTimeLabelFormats: { second: '%H:%M:%S' }
     },
     yAxis: {
       title: { text: 'AQI' }
     },
     credits: { enabled: false }
   });
   setInterval(function ( ) {
     var xhttp = new XMLHttpRequest();
     xhttp.onreadystatechange = function() {
       if (this.readyState == 4 && this.status == 200) {
          var rq = this.responseText;
    var rsp = rq.split(",");
    var y = parseFloat(rsp[0]);
    var x = ((new Date(rsp[1])).getTime()) + localtimezone;
         if(chartH.series[0].data.length > qty) {
           chartH.series[0].addPoint([x, y], true, true, true);
         } else {
           chartH.series[0].addPoint([x, y], true, false, true);
         }
       }
     };
     xhttp.open("GET", "/aqi-val.php", true);
     xhttp.send();
   }, freq ) ;
   
   var chartP = new Highcharts.Chart({
     chart:{ renderTo:'chart-co' },
     title: { text: 'Carbon Monoxide Index' },
     series: [{
    name: 'CO',
       showInLegend: false,
       data: []
     }],
     plotOptions: {
       line: { animation: false,
         dataLabels: { enabled: true }
       },
       series: { color: '#18009c' }
     },
     xAxis: {
       type: 'datetime',
       dateTimeLabelFormats: { second: '%H:%M:%S' }
     },
     yAxis: {
       title: { text: 'CO Index' }
     },
     credits: { enabled: false }
   });
   setInterval(function ( ) {
     var xhttp = new XMLHttpRequest();
     xhttp.onreadystatechange = function() {
       if (this.readyState == 4 && this.status == 200) {
    var rq = this.responseText;
    var rsp = rq.split(",");
    var y = parseFloat(rsp[0]);
    var x = ((new Date(rsp[1])).getTime()) + localtimezone;
    
    console.log(this.responseText);
         if(chartP.series[0].data.length > qty) {
           chartP.series[0].addPoint([x, y], true, true, true);
         } else {
           chartP.series[0].addPoint([x, y], true, false, true);
         }
       }
     };
       xhttp.open("GET", "/co-val.php", true);
     xhttp.send();
   }, freq ) ;
</script>
</html>