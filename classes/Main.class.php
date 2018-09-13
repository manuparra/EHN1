<?php

include("classes/Parsedown.php");
include('classes/Requests.php');

	
class Page {
	
	function __construct($contentconfig) {

		$this->contentconfig=$contentconfig;
		$str = file_get_contents($this->contentconfig);
		$this->config = json_decode($str, true);	   


		$this->head_text=               $this->config['head_text'];
		$this->subhead_text=            $this->config['subhead_text'];
		$this->button_subhead_text =    $this->config['button_subhead_text'];
		$this->left_menu_title =        $this->config['left_menu_title'];
		$this->left_menu_items =        $this->config['left_menu_items'];
		$this->detail_project_title =   $this->config['detail_project_title'];
		$this->detail_project_subtitle =$this->config['detail_project_subtitle'];
		$this->detail_project_content = $this->config['detail_project_test'];
		$this->authors =                $this->config['authors'];
		$this->copyright_text =         $this->config['copyright_text'];
	 }

	 

	 function detail_extended() {

	 	$Parsedown = new Parsedown();
		if (file_exists($this->detail_project_content)){
	    		$mdchunk=file_get_contents($this->detail_project_content);
	    		$content_text=$Parsedown->text($mdchunk);
	    		echo $content_text."<HR>";
	    	}
	 }
}

class Monitoring {

	function __construct($contentconfig){
		$this->contentconfig=$contentconfig;
		$str = file_get_contents($this->contentconfig);
		$this->config = json_decode($str, true);

		$this->racks=           $this->config['racks'];
		$this->nodes_rack=      $this->config['nodes_rack'];
		$this->nodes_prefix=    $this->config['nodes_prefix'];
	}

	function example_heatmap(){

		$lst_nodes=array();
		for ($i=1;$i<$this->racks;$i++)
			for ($j=1;$j<$this->racks;$j++){
				$lst_nodes[]="'".$this->nodes_prefix.str_pad($i,2,0,STR_PAD_LEFT).str_pad($j,2,0,STR_PAD_LEFT)."'";
			}

		echo join(",",$lst_nodes);


	}
}
	
	
class GetStats {
	
	function __construct($contentconfig,$TOKEN,$APIURL){

		$this->contentconfig=$contentconfig;
		$str = file_get_contents($this->contentconfig);
		$this->config = json_decode($str, true);	   


		$this->TOKEN=$TOKEN;
		$this->APIURL=$APIURL;
		
		$this->headers = array(
				'Authorization' => 'Bearer '.$this->TOKEN
				);
				
		
	}
	
	function _getCPU(){		

		//Select metric from the template API: CPU
		$api_params=$this->config['template_nodes']['cpu'];

		$q='SELECT mean("mean_value") FROM ""."cpu_percent" WHERE ("submitter_hostgroup" =~ /^(npcmp\/worker)$/ AND "host" =~ /^(np-cmp-0411\.cern\.ch)$/ AND "submitter_environment" =~ /^(npcmp_master)$/) AND time >= now() - 5h GROUP BY time(1m), "type_instance"';
		$db='monit_production_collectd';
		
		Requests::register_autoloader();
		$request = Requests::post(
					$this->APIURL."/proxy/".$api_params['id']."/query/",
					$this->headers,
					array(
						'q'=>$q,
						'db'=>$db
					)
				  );

		$results=json_decode($request->body, true);
		
		$idle =      $results['results'][0]['series'][0]['values'];
		$interrupt = $results['results'][0]['series'][1]['values'];
		$nice =      $results['results'][0]['series'][2]['values'];
		$softriq =   $results['results'][0]['series'][3]['values'];
		$steal =     $results['results'][0]['series'][4]['values'];
		$system =    $results['results'][0]['series'][5]['values'];
		$user =      $results['results'][0]['series'][6]['values'];
		$wait =      $results['results'][0]['series'][7]['values'];
		
		$js_data=         "var d_idle={".$this->__toJS($idle,"CPU_idle")."};";
		$js_data=$js_data."var d_interrupt={".$this->__toJS($interrupt,"CPU_interrupt")."};";
		$js_data=$js_data."var d_softriq={".$this->__toJS($nice,"CPU_softriq")."};";
		$js_data=$js_data."var d_steal={".$this->__toJS($softriq,"CPU_steal")."};";
		$js_data=$js_data."var d_system={".$this->__toJS($steal,"CPU_system")."};";
		$js_data=$js_data."var d_user={".$this->__toJS($user,"CPU_user")."};";
		$js_data=$js_data."var d_wait={".$this->__toJS($wait,"CPU_wait")."};";		
		$js_data=$js_data."var data = [d_idle,d_interrupt,d_softriq,d_steal,d_system,d_user,d_wait];";
		
		return $js_data;
	}
	
	
	function _getNETWORK(){	
		
		//Select metric from the template API: CPU
		$api_params=$this->config['template_nodes']['network'];
		
		$q='SELECT sum("mean_value") FROM ""."interface_if_octets" WHERE ("submitter_hostgroup" =~ /^npcmp\/worker$/ AND "host" =~ /^np-cmp-0411\.cern\.ch$/ AND "submitter_environment" =~ /^npcmp_master$/) AND time >= now() - 5h GROUP BY time(5m), "value_instance"';
		$db='monit_production_collectd';

		
		
		Requests::register_autoloader();
		$request = Requests::post(
					$this->APIURL."/proxy/".$api_params['id']."/query/",
					$this->headers,
					array(
						'q'=>$q,
						'db'=>$db
					)
				  );

		$results=json_decode($request->body, true);
		
		$rx =      $results['results'][0]['series'][0]['values'];
		$tx =      $results['results'][0]['series'][1]['values'];
		
		$js_data=         "var d_rx={".$this->__toJS($rx,"NET_rx")."};";
		$js_data=$js_data."var d_tx={".$this->__toJS($tx,"NET_tx")."};";
			
		$js_data=$js_data."var data = [d_rx,d_tx];";
		
		return $js_data;
	}
	
	
	function _getSYSLOAD(){		

		//Select metric from the template API: CPU
		$api_params=$this->config['template_nodes']['sysload'];
		
		$q='SELECT mean("mean_value") FROM ""."load" WHERE ("submitter_hostgroup" =~ /^npcmp\/worker$/ AND "host" =~ /^np-cmp-0411\.cern\.ch$/ AND "submitter_environment" =~ /^npcmp_master$/) AND time >= now() - 5h GROUP BY time(5m), "value_instance" fill(null)';
		$db='monit_production_collectd';

		
		
		Requests::register_autoloader();
		$request = Requests::post(
					$this->APIURL."/proxy/".$api_params['id']."/query/",
					$this->headers,
					array(
						'q'=>$q,
						'db'=>$db
					)
				  );

		$results=json_decode($request->body, true);
		
		$longterm =      $results['results'][0]['series'][0]['values'];
		$midterm =       $results['results'][0]['series'][1]['values'];
		$sortterm =      $results['results'][0]['series'][2]['values'];
		
		$js_data=         "var d_longterm={".$this->__toJS($longterm,"SYS_longterm")."};";
		$js_data=$js_data."var d_midterm= {".$this->__toJS($midterm,"SYS_midterm")."};";
		$js_data=$js_data."var d_shortterm= {".$this->__toJS($sortterm,"SYS_shortterm")."};";
			
		$js_data=$js_data."var data = [d_longterm,d_midterm,d_shortterm];";
		
		return $js_data;
	}
	
	
	function _getNODETEMP(){		

		//Select metric from the template API: CPU
		$api_params=$this->config['template_nodes']['node_temperature'];
		
		$q='SELECT mean("mean_value") FROM ""."sensors_temperature" WHERE ("submitter_hostgroup" =~ /^npcmp\/worker$/ AND "host" =~ /^np-cmp-0411\.cern\.ch$/ AND "submitter_environment" =~ /^npcmp_master$/) AND time >= now() - 5h GROUP BY time(5m), "value_instance" fill(null)';
		$db='monit_production_neutrino';
		
		Requests::register_autoloader();
		$request = Requests::post(
					$this->APIURL."/proxy/".$api_params['id']."/query/",
					$this->headers,
					array(
						'q'=>$q,
						'db'=>$db
					)
				  );

		$results=json_decode($request->body, true);			
		
		$temp =      $results['results'][0]['series'][0]['values'];
		
		
		// Get ambient Temperature in another serie.
		
		$q='SELECT mean("mean_value") FROM ""."ipmi_temperature" WHERE ("submitter_hostgroup" =~ /^npcmp\/worker$/ AND "host" =~ /^np-cmp-0411\.cern\.ch$/ AND "submitter_environment" =~ /^npcmp_master$/) AND time >= now() - 5h GROUP BY time(5m), "value_instance" fill(null)';
		$db='monit_production_neutrino';
		
		Requests::register_autoloader();
		$request = Requests::post(
					$this->APIURL."/proxy/".$api_params['id']."/query/",
					$this->headers,
					array(
						'q'=>$q,
						'db'=>$db
					)
				  );

		$results=json_decode($request->body, true);			
		
		$tempexternal =      $results['results'][0]['series'][0]['values'];
		
		
		$js_data="var d_nodetemp={".$this->__toJS($temp,"TEMP_node")."};";
		$js_data=$js_data."var d_nodetempexternal={".$this->__toJS($tempexternal,"TEMP_external")."};";
					
		$js_data=$js_data."var data = [d_nodetemp,d_nodetempexternal];";
		
		return $js_data;
	}
	
	
	
	function __toJS($data,$name){
		
		$x= array();
		$y= array();		
		
		foreach($data as $k){
			$x[]="'".date("Y-m-d H:i:s", strtotime($k[0]))."'";
			$y[]="".$k[1]."";
		}
		
		return "x: [".implode(",",$x)."],\ny: [".implode(",",$y)."],\nname: '".$name."'\n,type: 'scatter'\n";
	}
	
	
}
	
	
?>