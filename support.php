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
            <div class="callout4">
            <p> So join your favourite station and help us create some noise. 
                With 5.2 million weekly listeners, we're aiming to be louder than the MCG on grand final day.</p>
            <p>By joining you:</br>1. Register your support; and,</br>
                2. Can get more from your broadcaster.</br>
                It's easy & it won't cost you a penny.</p>
            <a href="join.php" class="btn btn-primary btn-lg active" role="button">Connect to my station</a>
            </div>

            <div id="chart4"></div>
        </div>
        </div> <!-- /container -->
 
            <script type="text/javascript">

            var m = [30, 40, 10, 100],
                w = 550 - m[1] - m[3],
                h = 500 - m[0] - m[2];

            var format = d3.format(",.0f");

            var x = d3.scale.linear().range([0, w]),
                y = d3.scale.ordinal().rangeRoundBands([0, h], .1);

            var xAxis = d3.svg.axis().scale(x).orient("top").tickSize(-h),
                yAxis = d3.svg.axis().scale(y).orient("left").tickSize(0);

            var svg = d3.select("#chart4").append("svg")
                .attr("width", w + m[1] + m[3])
                .attr("height", h + m[0] + m[2])
              .append("g")
                .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

            d3.csv("data/supporters.csv", function(data) {

              data.forEach(function(d) { d.value = +d.value; });
              data.sort(function(a, b) { return b.value - a.value; });

              x.domain([0, d3.max(data, function(d) { return d.value; })]);
              y.domain(data.map(function(d) { return d.name; }));

              var bar = svg.selectAll("g.bar")
                  .data(data)
                .enter().append("g")
                  .attr("class", "bar")
                  .attr("transform", function(d) { return "translate(0," + y(d.name) + ")"; });

              bar.append("rect")
                  .attr("width", function(d) { return x(d.value); })
                  .attr("height", y.rangeBand());

            d3.selectAll("rect").filter(function(d){return d.name==="Community Radio"})
                .style("fill", "#d9001b");


              bar.append("text")
                  .attr("class", "value")
                  .attr("x", function(d) { return x(d.value); })
                  .attr("y", y.rangeBand() / 2)
                  .attr("dx", 1)
                  .attr("dy", ".35em")
                  .text(function(d) { return format(d.value); });

              svg.append("g")
                  .attr("class", "x axis")
                  .call(xAxis);

              svg.append("g")
                  .attr("class", "y axis")
                  .call(yAxis);
            });

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
