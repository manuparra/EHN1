<?php

include("classes/Parsedown.php");
	
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
	
	
?>