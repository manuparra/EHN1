<?php 

include_once("classes/Main.class.php");

if(isset($_GET['node'])){
	$node=$_GET['node'];
}
else{
	$node=false;
}


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
				 <?php
				 	if ($node=="" || !isset($_GET['node'])){
				 ?>			  
					 <h3><span id="global_active" class="badge badge-success">Loading..., wait please</span> <span id="global_not_active" class="badge badge-danger"></span></h3>	
				 <?php
			 		}
					else {
				?>
					<h3><span class="badge badge-primary">Node </span>&nbsp; <?php echo "".$node;?>.cern.ch</h3>
				<?php
					}
				?>
			 </div>
			 <div style="text-align:right" class="col-md-6 mx-auto">
				 <?php
				 	if ($node=="" || !isset($_GET['node'])){
				 ?>
				 <h3><span class="badge badge-success"><span id="temp_external">...</span>ºC</span>&nbsp;<span class="badge badge-info"><span id="temp_internal">...</span>ºC</span></h3>
 				 <?php
 			 		}
 					else {
 				?>
				<h3><span class="badge badge-success"><span id="temp_external">...</span>ºC</span>&nbsp;<span class="badge badge-info"><span id="temp_internal">...</span>ºC</span> &nbsp;<span id="uptime">...</span></h3>
				<?php
					}
				?>
			 </div>							  
		  </div>
		</div>
      <div class="container text-center">
		<?php
		//Looking for General Cluster Status
		if ($node=="" || !isset($_GET['node'])){
		?>
		
		<div class="row">
				<div class="col-md-6 mx-auto">
		             <h4>Global Status: UPTIME</h4>
		            <div id="DIV_UPTIME_GENERAL"></div>

		            <script>
         
		            var data_uptime_global = [
		                {
						<?php
					
							echo $stats->getGlobalUptime();
					
						?>
		                  type: 'heatmap',
		                  showscale: false,
						  colorscale: [
							[0, '#001f3f'],
							[1, '#3D9970']
						  ]
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
		    l: 75,
		    r: 0,
		    b: 0,
		    t: 50,
		    pad: 4
		  },
		  paper_bgcolor: 'rgba(0,0,0,0)',
		  plot_bgcolor: 'rgba(0,0,0,0)'
		};
		Plotly.newPlot('DIV_UPTIME_GENERAL', data_uptime_global,layout);
		</script>
	
		</div>
				<div class="col-md-6 mx-auto">
		             <h4>Global System Load (medium term)</h4>
		            <div id="DIV_SYSLOAD_GENERAL"></div>

		            <script>
         
		            var data_sysload_global = [
		                {
						<?php
							echo $stats->getGlobalSysLoad();
						?>
		                  type: 'heatmap',
		                  showscale: false,
						  colorscale: [
							[0, '#2D9970'],
							[1, '#d02c56']
						  ]
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
		    l: 75,
		    r: 0,
		    b: 0,
		    t: 50,
		    pad: 4
		  },
		  paper_bgcolor: 'rgba(0,0,0,0)',
		  plot_bgcolor: 'rgba(0,0,0,0)'
		};

		Plotly.newPlot('DIV_SYSLOAD_GENERAL', data_sysload_global,layout);
		</script>
	
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12 mx-auto"></div>
	</div>	
	<div class="row">
			<div class="col-md-6 mx-auto">
	             <h4>Node selector</h4>
				 <?php
				 	
				 $Rows = 24; //Dynamic number for Rowss
				 $Cols = 11; // Dynamic number for Colsumns
				 echo '<table class="table table-sm">';
				 for($i=1;$i<=$Rows;$i++){ echo '<tr>';
				   for($j=1;$j<=$Cols;$j++){ echo '<td style="padding: .0rem;"><a href="?node=np-cmp-'.str_pad($j,2,0,STR_PAD_LEFT).str_pad($i,2,0,STR_PAD_LEFT).'">'.str_pad($j,2,0,STR_PAD_LEFT).str_pad($i,2,0,STR_PAD_LEFT). '</a></td>'; }
				   echo '</tr>';
				 }
				 echo '</table>';
					
				 ?>
		 </div>
			<div class="col-md-6 mx-auto">
	             <h4>Status of Master Nodes</h4>
				 
		 </div>
	</div>
		
		
		<?php
 		}
		//Looking for Node Status
		else {
		?>  
		
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
				Plotly.newPlot('CPU', data_cpu,layout);
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
				Plotly.newPlot('Network', data_network,layout);
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
				Plotly.newPlot('SystemLoad', data_sysload,layout);
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
				Plotly.newPlot('NodeTemp', data_temp,layout);
            </script>
			<script>
				<?php $stats->_getUPTIME(); ?>				
			</script>	
				
          </div>
		  
		  <?php
	  	  }
		  ?>
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
		
		String.prototype.toHHMMSS = function () {
		    var sec_num = parseInt(this, 10); // don't forget the second param
		
			var days = Math.floor(sec_num / (3600*24));
			sec_num  -= days*3600*24;
			var hours   = Math.floor(sec_num / 3600);
			sec_num  -= hours*3600;
			var minutes = Math.floor(sec_num / 60);
			sec_num  -= minutes*60;

		    if (hours   < 10) {hours   = "0"+hours;}
		    if (minutes < 10) {minutes = "0"+minutes;}

		    var time    = days + "d " +  hours+'h '+minutes+'m';
		    return time;
		}
		
		$( document ).ready(function() {
		    console.log( "ready!" );
			
		    <?php
		 	  if ($node=="" || !isset($_GET['node'])){
		    ?>	
			var i,j;
			n_uptime0=0;
			n_uptime1=0;
			for (i = 0; i < data_uptime_global[0].z.length; i++) {
				lst_up=data_uptime_global[0].z[i];
				for (j = 0; j < lst_up.length; j++) {
					if(lst_up[j]==0) n_uptime0++;
					else n_uptime1++;
				}
			};
			
			$("#global_active").html("Active: " + n_uptime1);
			$("#global_not_active").html("N/Active: " + n_uptime0);

			// Average Temperature Nodos/External
			<?php 
			$stats->node="np-cmp-0612";
			echo $stats->_getNODETEMP(); ?>

			var temp_external=d_nodetempexternal.y[d_nodetempexternal.y.length-1];
			var temp_internal=d_nodetemp.y[d_nodetemp.y.length-1];
			
			$("#temp_external").html(temp_external);
			$("#temp_internal").html(temp_internal);


			<?php
			 }
			else {
			?>
			var temp_external=d_nodetempexternal.y[d_nodetempexternal.y.length-1];
			var temp_internal=d_nodetemp.y[d_nodetemp.y.length-1];
			
			$("#temp_external").html(temp_external);
			$("#temp_internal").html(temp_internal);
			
			
			var uptime=d_uptime.y[d_uptime.y.length-1];
			
			var uptime_hhmmss = (uptime + "").toHHMMSS();
			
			$("#uptime").html(uptime_hhmmss);
			
			
			var temp_external=d_nodetempexternal.y[d_nodetempexternal.y.length-1];
			var temp_internal=d_nodetemp.y[d_nodetemp.y.length-1];
			
			<?php
		 	}
			?>

			
		});
		
	</script>

  </body>

</html>
