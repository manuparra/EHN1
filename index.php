<?php 

include_once("classes/Main.class.php");

$node=$_GET['node'];


$webpage = new Page($contentconfig="configuration.json");
$monit =   new Monitoring($contentconfig="configuration.json");
$stats =   new GetStats  ($contentconfig="configuration.json",
                        $TOKEN="eyJrIjoiU0lyaVZHejg3c2VsQXdjYUE5d3UwbW9HaG96NEg1bDAiLCJuIjoiV0VCLUlOVEVSRkFDRSIsImlkIjozNH0=",
                        $APIURL="https://monit-grafana.cern.ch/api/datasources",
						$node=$node);
	




?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $webpage->head_text; ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/stylish-portfolio.min.css" rel="stylesheet">

 <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>

  </head>

  <body id="page-top">

    <!-- Navigation -->
    <a class="menu-toggle rounded" href="#">
      <i class="fas fa-bars"></i>
    </a>
    <nav id="sidebar-wrapper">
      <ul class="sidebar-nav">
        <li class="sidebar-brand">
          <a class="js-scroll-trigger" href="#page-top"><?php echo $webpage->left_menu_title; ?></a>
        </li>      
        <li class="sidebar-nav-item">
          <a class="js-scroll-trigger" href="#about"><?php echo $webpage->left_menu_items[0]; ?></a>
        </li>
        <li class="sidebar-nav-item">
          <a class="js-scroll-trigger" href="#services"><?php echo $webpage->left_menu_items[1]; ?></a>
        </li>
      </ul>
    </nav>

    <!-- Header -->
    <header class="masthead d-flex">
      <div class="container text-center my-auto">
        <h1 class="mb-1"><?php echo $webpage->head_text; ?></h1>
        <h3 class="mb-5">
          <em><?php echo $webpage->subhead_text; ?></em>
        </h3>
        <a class="btn btn-primary btn-xl js-scroll-trigger" href="#about"><?php echo $webpage->button_subhead_text; ?></a>
      </div>
      <div class="overlay"></div>
    </header>

    <!-- About -->
    <section style="padding-top: 2.5rem;" class="content-section bg-light" id="about">
        <div class="container text-center">
          <div class="row">
			  <div class="col-md-6 mx-auto">
			 	 <h3><span class="badge badge-primary"><?php echo "Node: ".$node;?>.cern.ch</span></h3>
			 </div>
			 <div class="col-md-6 mx-auto">
				 <h3><span class="badge badge-info"><span id="temp_external">ºC</span> <span class="badge badge-info"><span id="temp_internal">ºC</span></span></h3>
			 </div>							  
		  </div>
		</div>
      <div class="container text-center">
        <div class="row">	
          <div class="col-md-12 mx-auto">
             <h5>CPU percent</h5>
            <div id="CPU"></div>
            <script>
            <?php echo $stats->_getCPU(); ?>
				var layout = {
					title: '',
					 height: 210,
					annotations: [],
					margin: {
						l: 50,
						r: 0,
						b: 50,
						t: 20,
						pad: 4
  				  },
				  paper_bgcolor: 'rgba(0,0,0,0)', 
				  plot_bgcolor: 'rgba(0,0,0,0)'
			  	};
				Plotly.newPlot('CPU', data,layout);
            </script>
          </div>
		  
          <div class="col-md-12 mx-auto">
             <h5>Network performance</h5>
            <div id="Network"></div>
            <script>
            <?php echo $stats->_getNETWORK(); ?>
				var layout = {
					title: '',
					height: 200,
					annotations: [],
					margin: {
						l: 50,
						r: 0,
						b: 50,
						t: 20,
						pad: 4
  				  },
				  paper_bgcolor: 'rgba(0,0,0,0)', 
				  plot_bgcolor: 'rgba(0,0,0,0)'
			  	};
				Plotly.newPlot('Network', data,layout);
            </script>
          </div>
		  
          <div class="col-md-12 mx-auto">
             <h5>System Load</h5>
            <div id="SystemLoad"></div>
            <script>
            <?php echo $stats->_getSYSLOAD(); ?>
				var layout = {
					title: '',
					height: 200,
					annotations: [],
					margin: {
						l: 50,
						r: 0,
						b: 50,
						t: 20,
						pad: 4
  				  },
				  paper_bgcolor: 'rgba(0,0,0,0)', 
				  plot_bgcolor: 'rgba(0,0,0,0)'
			  	};
				Plotly.newPlot('SystemLoad', data,layout);
            </script>
          </div>
		  
          <div class="col-md-12 mx-auto">
             <h5>Node Temperature</h5>
            <div id="NodeTemp"></div>
            <script>
            <?php echo $stats->_getNODETEMP(); ?>
				var layout = {
					title: '',
					height: 200,
					annotations: [],
					margin: {
						l: 50,
						r: 0,
						b: 50,
						t: 20,
						pad: 4
  				  },
				  paper_bgcolor: 'rgba(0,0,0,0)', 
				  plot_bgcolor: 'rgba(0,0,0,0)'
			  	};
				Plotly.newPlot('NodeTemp', data,layout);
            </script>
          </div>
		  

		<div class="col-md-6 mx-auto">
             <h4>General status ENH1 cluster</h4>
            <div id="myDiv"></div>
            <script>
          
            <?php

            $monit->example_heatmap();

            ?>

            var data = [
                {
                  z: [[0,1,1,0,1,1,1,1,1,1,1],[0,0,1,0,1,1,0,1,0,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1],[0,1,1,0,1,1,1,1,1,1,1]],
                  x: ['Rack 01', 'Rack 02', 'Rack 03','Rack 04','Rack 05','Rack 06','Rack 07','Rack 08','Rack 09','Rack 10','Rack 11'],
                  y: ['N1','N2','N3','N4','N5','N6','N7','N8','N9','N10','N11','N12','N13','N14','N15','N16','N17','N18','N19','N20','N21','N22','N23','N24'],
                  type: 'heatmap',
                  showscale: false,
                  colorscale: [[0, '#B03060'],[1, '#3CB371']],
                }
              ];

var layout = {
  title: '',
  annotations: [],
  xaxis: {
    ticks: '',
    side: 'top'
  },
  yaxis: {
    ticks: '',
    ticksuffix: ' ',
    width: 700,
    height: 700,
    autosize: false
  },
  margin: {
    l: 50,
    r: 0,
    b: 0,
    t: 50,
    pad: 4
  },
  paper_bgcolor: 'rgba(0,0,0,0)',
  plot_bgcolor: 'rgba(0,0,0,0)'
};

            Plotly.newPlot('myDiv', data,layout);
            </script>
            <!--
            <h2>Stylish Portfolio is the perfect theme for your next project!</h2>
            <p class="lead mb-5">This theme features a flexible, UX friendly sidebar menu and stock photos from our friends at
              <a href="https://unsplash.com/">Unsplash</a>!</p>
            <a class="btn btn-dark btn-xl js-scroll-trigger" href="#services">What We Offer</a>
          -->
  
        </div>

      </div>
    </section>

    <!-- Services -->
    <section class="content-section bg-primary text-white text-center" id="services">
      <div class="container">
        <div class="content-section-heading">
          <h3 class="text-secondary mb-0"><?php echo $webpage->detail_project_title; ?></h3>
          <h2 class="mb-5"><?php echo $webpage->detail_project_subtitle; ?></h2>
        </div>
        <div class="row">
          <div style="text-align:justify"  class="col-lg-12 col-md-12 mb-5 mb-lg-0">
            <p class="text-faded mb-0">

                <?php echo $webpage->detail_extended(); ?>

            </p>
          </div>          
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer text-center">
      <div class="container">
                <img src="https://cenf.web.cern.ch/sites/cenf.web.cern.ch/themes/cern_overwrite/img/cern-logo-large.png">
        <ul class="list-inline mb-5">
          <li class="list-inline-item">
            <a class="social-link rounded-circle text-white mr-3" href="http://facebook.com/cern">
              <i class="icon-social-facebook"></i>
            </a>
          </li>
          <li class="list-inline-item">
            <a class="social-link rounded-circle text-white mr-3" href="http://twitter.com/cern">
              <i class="icon-social-twitter"></i>
            </a>
          </li>
          <li class="list-inline-item">
            <a class="social-link rounded-circle text-white" href="http://youtube.com/cern">
              <i class="icon-social-youtube"></i>
            </a>
          </li>
        </ul>

    
        <p class="text-muted small mb-0"><?php echo $webpage->authors; ?> | <?php echo $webpage->copyright_text; ?> </p>
     
      </div>
    </footer>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded js-scroll-trigger" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/stylish-portfolio.min.js"></script>

	<script>
		
		$( document ).ready(function() {
		    console.log( "ready!" );
			var temp_external=d_nodetempexternal.y[d_nodetempexternal.y.lenght-1];
			var temp_internal=d_nodetemp.y[d_nodetemp.y.lenght-1];
			
			$("#temp_external").html(temp_external);
			$("#temp_internal").html(temp_internal);
			
		});
		
	</script>

  </body>

</html>
