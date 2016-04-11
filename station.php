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
      <script src="js/vendor/jquery-1.9.1.js"></script>

        <style>

        .node {
          cursor: pointer;
        }

        .node:hover {
          stroke: #000;
          stroke-width: 1.5px;
        }

        .node--root {
          stroke: #777;
          stroke-width: 2px;
        }

        .node--leaf {
          fill: white;
        }

        .label {
          font: 11px "Helvetica Neue", Helvetica, Arial, sans-serif;
          text-anchor: middle;
          text-shadow: 0 1px 0 #fff, 1px 0 0 #fff, -1px 0 0 #fff, 0 -1px 0 #fff;
        }

        .label,
        .node--root,
        .node--leaf {
          pointer-events: none;
        }

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
      		<div class="callout2">
            <?php
      			    $station = $_GET["name"];
      			    echo "<p>Now you can connect to stations for a deeper, more personal experience.</p>  
                <p>For example, that red circle on the right is ".$station."";
      			?>
            <div id="stationdata"></div>
            <a href="funding.php?name=3PBS+-+MELBOURNE" class="btn btn-primary btn-lg active" role="button">Next</a>
          </div>

    		<div id="chart2"></div>
        </div>
        <div class="title2">
        <h3>By genre & audience size</h3>
        <h4>(Click to zoom)</h4>
        </div>


        </div> <!-- /container -->

      <script>
        var station = "<?php echo $station; ?>";

         $.getJSON('data/broadcasters.json', function(data) {
              var output="It's home to ";
                output+=" "  + data[station].Staff + " staff, " 
                + data[station].Volunteers+" volunteers & around 250,000 listeners";
          output+=" - a vibrant community that shares programs, events & conversations. By connecting to this community, you'll be able to share in many new & interesting ways.</p>";
              document.getElementById("stationdata").innerHTML=output;
              var callsign = data[station].Callsign;
              i = callsign;
        });
      </script>

       <script>

        var margin = 10,
            outerDiameter = 560,
            innerDiameter = outerDiameter - margin - margin;

        var x = d3.scale.linear()
            .range([0, innerDiameter]);

        var y = d3.scale.linear()
            .range([0, innerDiameter]);

        var color = d3.scale.linear()
            .domain([-1, 5])
            .range(["hsl(152,80%,80%)", "hsl(228,30%,40%)"])
            .interpolate(d3.interpolateHcl);

        var pack = d3.layout.pack()
            .padding(2)
            .size([innerDiameter, innerDiameter])
            .value(function(d) { return d.size; })

        var svg = d3.select("#chart2").append("svg")
            .attr("width", outerDiameter)
            .attr("height", outerDiameter)
          .append("g")
            .attr("transform", "translate(" + margin + "," + margin + ")");

        d3.csv("data/bubble.csv", function(error, csv) {
            csv.forEach(function(d) {
                d.size = +d.size;
            });

        var nestdata = d3.nest()
          .key(function(d) {return d.genre;})
          .key(function(d) {return d.community;})
          .entries(csv);

        var root = new Object();
        root.name = "Radio";
        root.children = nestdata;

        var focus = root,
        nodes = pack.nodes(root);

          svg.append("g").selectAll("circle")
              .data(nodes)
            .enter().append("circle")
              .attr("class", function(d) { return d.parent ? d.children ? "node" : "node node--leaf" : "node node--root"; })
              .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
              .attr("r", function(d) { return d.r; })
              .style("fill", function(d) { return d.children ? color(d.depth) : null; })
              .on("click", function(d) { return zoom(focus == d ? root : d); });

          d3.selectAll("circle.node.node--leaf").filter(function(d) { return d.name===i})
              .style("fill","#d9001b");

          svg.append("g").selectAll("text")
              .data(nodes)
            .enter().append("text")
              .attr("class", "label")
              .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
              .style("fill-opacity", function(d) { return d.parent === root ? 1 : 0; })
              .style("display", function(d) { return d.parent === root ? null : "none"; })
              .text(function(d) { return d.name; });

          d3.select(window)
              .on("click", function() { zoom(root); });

          function zoom(d, i) {
            var focus0 = focus;
            focus = d;

            var k = innerDiameter / d.r / 2;
            x.domain([d.x - d.r, d.x + d.r]);
            y.domain([d.y - d.r, d.y + d.r]);
            d3.event.stopPropagation();

            var transition = d3.selectAll("text,circle").transition()
                .duration(d3.event.altKey ? 7500 : 750)
                .attr("transform", function(d) { return "translate(" + x(d.x) + "," + y(d.y) + ")"; });

            transition.filter("circle")
                .attr("r", function(d) { return k * d.r; });

            transition.filter("text")
              .filter(function(d) { return d.parent === focus || d.parent === focus0; })
                .style("fill-opacity", function(d) { return d.parent === focus ? 1 : 0; })
                .each("start", function(d) { if (d.parent === focus) this.style.display = "inline"; })
                .each("end", function(d) { if (d.parent !== focus) this.style.display = "none"; });
          }
        });

        d3.select(self.frameElement).style("height", outerDiameter + "px");

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


        <script src="js/vendor/bootstrap.min.js"></script>



    </body>
</html>
