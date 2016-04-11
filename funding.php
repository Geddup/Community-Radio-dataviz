<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Support Community Broadcasting</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,  user-scalable=no">
        <link rel="stylesheet" href="css/main.css">
        <link href="http://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" type="text/css">
        
        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		<script src="js/d3.v3.js"></script>

        <style>

form {
    position: absolute;
    left: 800px;
    top: 550px;
    font-size: 18px;
}


        </style>
        <form>
          <h3>Relative resources of non-profit media</h3>
          <label><input type="radio" name="dataset" value="funding" checked> Govt funding</label>
          <label><input type="radio" name="dataset" value="workers"> Staff + Volunteers</label>
        </form>
 

        </style>

    </head>

    <body class="homepage">
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- NAV -->
        <div class="navbar navbar-fixed-top" id="main-nav">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="index.html">
                        <img src="img/radio.jpg" class="desktop-logo">
                        <h3 class="desktop-tagline1">COMMUNITY BROADCASTING</h3>
                        <p class="desktop-tagline2">...of the People, by the People, for the People</p>
                    </a>
 	            </div>
 	        </div>
        </div>
        <!-- /NAV -->

        <div class="container">
        <div class="content">

    		<div class="callout3">
            <p> You can also help us to be heard.</p>
            <p>
                Government support is a small but important part of the resources that breath life
                into Community Radio. For example, while the ABC will receive $52.81 per Australian
                in 2013/14, Community Broadcasting will get a total of $0.70 per Australian. 
                Government will listen if we have a strong, united voice.
            </p>
            <a href="support.php?name=3PBS+-+MELBOURNE" class="btn btn-primary btn-lg active" role="button">Next</a>
            </div>

    		<div id="chart3"></div>
        </div>
        </div> <!-- /container -->
 
        <script>

        var dataset = {
          labels: ["ABC", "SBS", "CB"],
          funding: [52.8, 16.4, 0.7],
          workers: [4500, 800, 22000]
        };

        var width = 760,
          height = 500,
          radius = Math.min(width, height) / 2;

        var enterClockwise = {
          startAngle: 0,
          endAngle: 0
        };

        var enterAntiClockwise = {
          startAngle: Math.PI * 2,
          endAngle: Math.PI * 2
        };

        var color = d3.scale.category20();

        var pie = d3.layout.pie()
          .sort(null);

        var arc = d3.svg.arc()
          .innerRadius(radius - 120)
          .outerRadius(radius - 20);

        var svg = d3.select("#chart3").append("svg")
          .attr("width", width)
          .attr("height", height)
          .append("g")
          .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

        // set the start and end angles to 0 so we can transition
        // clockwise to the actual values later
        var path = svg.selectAll("path")
          .data(pie(dataset.workers))
          .enter().append("path")
            .attr("fill", function(d, i) { return color(i); })
            .attr("d", arc(enterClockwise))
            .each(function(d) {
              this._current = {
                data: d.data,
                value: d.value,
                startAngle: enterClockwise.startAngle,
                endAngle: enterClockwise.endAngle
              }
            }); // store the initial values

        path.transition()  // update
            .duration(750)
            .attrTween("d", arcTween);

        d3.selectAll("input").on("change", change);

        var timeout = setTimeout(function() {
          d3.select("input[value=\"funding\"]").property("checked", true).each(change);
        }, 2000);

        function change() {
          clearTimeout(timeout);
          path = path.data(pie(dataset[this.value])); // update the data
          // set the start and end angles to Math.PI * 2 so we can transition
          // anticlockwise to the actual values later
          path.enter().append("path")
              .attr("fill", function (d, i) {
                return color(i);
              })
              .attr("d", arc(enterAntiClockwise))
              .each(function (d) {
                this._current = {
                  data: d.data,
                  value: d.value,
                  startAngle: enterAntiClockwise.startAngle,
                  endAngle: enterAntiClockwise.endAngle
                };
              }); // store the initial values

          path.exit()
              .transition()
              .duration(750)
              .attrTween('d', arcTweenOut)
              .remove() // now remove the exiting arcs

          path.transition().duration(750).attrTween("d", arcTween); // redraw the arcs
        }

        var legend = svg.append("g")
          .attr("class", "legend")
          .attr("height", 140)
          .attr("width", 100)
          .attr('transform', 'translate(-45,-30)');
        legend.selectAll('rect')
          .data(dataset.labels)
          .enter()
          .append("rect")
          .attr("x", 10)
          .attr("y", function(d, i){ return i *  25;})
          .attr("width", 20)
          .attr("height", 20)
          .style("fill", function(d, i) { return color(i); })
      legend.selectAll('text')
          .data(dataset.labels)
          .enter()
          .append("text")
          .attr("x", 40)
          .attr("y", function(d, i){ return i *  25 + 16;})
          .text(function(d) { return d; });

        // Store the displayed angles in _current.
        // Then, interpolate from _current to the new angles.
        // During the transition, _current is updated in-place by d3.interpolate.
        function arcTween(a) {
          var i = d3.interpolate(this._current, a);
          this._current = i(0);
          return function(t) {
          return arc(i(t));
          };
        }
        function arcTweenOut(a) {
          var i = d3.interpolate(this._current, {startAngle: Math.PI * 2, endAngle: Math.PI * 2, value: 0});
          this._current = i(0);
          return function (t) {
            return arc(i(t));
          };
        }

        </script>


        <footer>
            <div class="container">
                <div class="row">
                    <ul class="footer-nav">
                        <li><a href="https://www.cbaa.org.au"><img src="img/cbaa.jpeg" Alt="CBAA"></a></li>
                        <li>COPYRIGHT © COMMUNITY BROADCASTING ASSOCIATION OF AUSTRALIA 2014</li> 
                        <li><a href="https://www.twitter.com/CBAA_"><img src="img/twitter.svg" Alt="Twitter"></a></li>
                        <li><a href="https://www.facebook.com/CommunityBroadcasting"><img src="img/facebook.svg" Alt="Facebook"></a></li>
                    </ul>
                </div>
            </div>
        </footer>


        <script src="js/vendor/jquery-1.9.1.js"></script>
        <script src="js/vendor/bootstrap.min.js"></script>

    </body>
</html>
