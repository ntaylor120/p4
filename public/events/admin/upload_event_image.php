<?php
@include 'common.php';
	if(isset($_GET["qqfile"])) {
		
		$filename = $_GET["qqfile"];
		
		$input = fopen("php://input", "r");
		
		$ext=explode(".",$filename);
		$str_ext=strtolower($ext[count($ext)-1]); 
		
		$randName=rand(1,getrandmax());
		$dest = fopen("../contents/events/temp/".$randName.".".$str_ext, 'w');;
		$realSize = stream_copy_to_stream($input, $dest);
		fclose($input);
		
		
		echo "{'success':true, 'filename':'".$randName.".".$str_ext."'}";
		
		
		
		 
	} else {
		
		$filename = $_FILES["qqfile"]["name"];
		$ext=explode(".",$filename);
		$str_ext=strtolower($ext[count($ext)-1]); 
		$randName=rand(1,getrandmax());
		move_uploaded_file($_FILES["qqfile"]["tmp_name"],"../contents/events/temp/".$randName.".".$str_ext);
		
		echo "{'success':true, 'filename':'".$randName.".".$str_ext."'}";
		
		
		
	}

?>
