<?php

class functions {
 //******************** THUMBNAILS *****************************
	
	function GetWidHit($cur_wid, $cur_hit, $des_wid, $des_hit)
	{
			if( ($cur_wid<$des_wid || $cur_wid==$des_wid) && ($cur_hit<$des_hit || $cur_hit==$des_hit) )
			{
				$this->new_wid=$cur_wid;
				$this->new_hit=$cur_hit;				
			}
			elseif( ($cur_wid<$des_wid || $cur_wid==$des_wid) && ($cur_hit>$des_hit) )
            {               
                $cur_wid=floor((($des_hit * $cur_wid) / $cur_hit));
                $cur_hit=$des_hit;              
            }
            elseif( ($cur_wid>$des_wid) && ($cur_hit<$des_hit || $cur_hit==$des_hit) )
            {                
                $cur_hit = floor((($des_wid * $cur_hit) / $cur_wid));
                $cur_wid = $des_wid;              
            }
            elseif($cur_wid>$des_wid && $cur_hit>$des_hit)
            {
                if($cur_wid>$cur_hit)
                {
                    $cur_hit = floor((($des_wid * $cur_hit) / $cur_wid));
                    $cur_wid = $des_wid;
	
                }
                elseif($cur_hit>$cur_wid)
                {
                    $cur_wid = floor((($des_hit * $cur_wid) / $cur_hit));
                    $cur_hit = $des_hit;
                }
                elseif($cur_hit==$cur_wid)
                {
                    $cur_wid= floor((($des_hit * $cur_wid) / $cur_hit));
                    $cur_hit=$des_hit;
                }
            }
			
			if ($cur_wid> $des_wid) 
				{
					$cur_hit=floor((($des_hit * $des_wid) / $cur_wid));
					 $this->new_wid= $des_wid;
			         $this->new_hit=$cur_hit;
			
				}
			if ($cur_hit> $des_hit) 
				{
					$cur_wid = floor((($des_wid * $des_hit) / $cur_hit));
					 $this->new_wid=$cur_wid;
			         $this->new_hit= $des_hit;
				}
			
            else {
			$this->new_wid=$cur_wid;
			$this->new_hit=$cur_hit;
			}
	}
	
	function CreateThumbs($imgp, $thumb_path, $des_wid, $des_hit)
	{	 		 		 
		
	
		$ext_arr=explode(".",$imgp);
		$ext=$ext_arr[count($ext_arr)-1];
				
		if(strtolower($ext)=="jpg" || strtolower($ext)=="jpeg")
			$source = imagecreatefromjpeg($imgp);
		elseif(strtolower($ext)=="png")	
			$source = imagecreatefrompng($imgp);	
		elseif(strtolower($ext)=="gif")
			$source = imagecreatefromgif($imgp);
			
		list($width, $height) = getimagesize($imgp);

        $this->GetWidHit($width, $height, $des_wid, $des_hit);

		$thumb = imagecreatetruecolor($this->new_wid, $this->new_hit);
		
		imagepalettecopy($thumb,$source);
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $this->new_wid, $this->new_hit, $width, $height);
		
		if(strtolower($ext)=="jpg" || strtolower($ext)=="jpeg")
			imagejpeg($thumb, $thumb_path, 100);
		elseif(strtolower($ext)=="png")	
			imagepng($thumb, $thumb_path,9);
		elseif(strtolower($ext)=="gif")
			imagegif($thumb, $thumb_path);
			
		
		
		
		
		//@unlink($imgp);
		
	}
	
	function CreateCrop($imgp, $thumb_path, $des_wid, $des_hit)
	{	 		 		 
		
	
		$ext_arr=explode(".",$imgp);
		$ext=$ext_arr[count($ext_arr)-1];
				
		if(strtolower($ext)=="jpg" || strtolower($ext)=="jpeg")
			$source = imagecreatefromjpeg($imgp);
		elseif(strtolower($ext)=="png")	
			$source = imagecreatefrompng($imgp);	
		elseif(strtolower($ext)=="gif")
			$source = imagecreatefromgif($imgp);
			
		list($width, $height) = getimagesize($imgp);

        if($width>=$height) {
			if($height>=$des_hit) {
				$newHeight=$des_hit;
				$newWidth=$des_hit*$width/$height;
				$this->CreateThumbs($imgp, $thumb_path, $newWidth, $des_hit);
				$newthumb = imagecreatetruecolor($des_wid, $des_hit);
				
				imagepalettecopy($newthumb,$source);
				imagecopyresampled($newthumb, $source, 0, 0, ($newWidth-$des_wid)/2, 0, $this->new_wid, $this->new_hit, $width, $height);
			} else {
				$newHeight=$des_hit;
				$newWidth=$des_hit*$width/$height;
				$thumb = imagecreatetruecolor($newWidth, $newHeight);//immagine stretchata
		
				imagepalettecopy($thumb,$source);
				imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
				
				if(strtolower($ext)=="jpg" || strtolower($ext)=="jpeg")
					imagejpeg($thumb, $thumb_path, 100);
				elseif(strtolower($ext)=="png")	
					imagepng($thumb, $thumb_path,9);
				elseif(strtolower($ext)=="gif")
					imagegif($thumb, $thumb_path);
				
				//$this->CreateThumbs($imgp, $thumb_path, $newWidth, $des_hit);
				$newthumb = imagecreatetruecolor($des_wid, $des_hit);
				
				imagepalettecopy($newthumb,$thumb);
				imagecopyresized($newthumb, $thumb, 0, 0, ($newWidth-$des_wid)/2, 0, $this->new_wid, $this->new_hit, $width, $height);
				
			}
		} else {
			if($width>=$des_wid) {
				$newWidth=$des_wid;
				$newHeight=$des_wid*$height/$width;
				$this->CreateThumbs($imgp, $thumb_path, $des_wid, $newHeight);
				$newthumb = imagecreatetruecolor($des_wid, $des_hit);
				
				imagepalettecopy($newthumb,$source);
				imagecopyresampled($newthumb, $source, 0, 0, 0, ($newHeight-$des_hit)/2, $this->new_wid, $this->new_hit, $width, $height);
			} else {
				$newWidth=$des_wid;
				$newHeight=$des_wid*$height/$width;
				$thumb = imagecreatetruecolor($newWidth, $newHeight);//immagine stretchata
		
				imagepalettecopy($thumb,$source);
				imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
				
				if(strtolower($ext)=="jpg" || strtolower($ext)=="jpeg")
					imagejpeg($thumb, $thumb_path, 100);
				elseif(strtolower($ext)=="png")	
					imagepng($thumb, $thumb_path,9);
				elseif(strtolower($ext)=="gif")
					imagegif($thumb, $thumb_path);
				
				//$this->CreateThumbs($imgp, $thumb_path, $newWidth, $des_hit);
				$newthumb = imagecreatetruecolor($des_wid, $des_hit);
				
				imagepalettecopy($newthumb,$thumb);
				imagecopyresized($newthumb, $thumb, 0, 0, 0, ($newHeight-$des_hit)/2, $this->new_wid, $this->new_hit, $width, $height);
				
			}
		}

		
		
		if(strtolower($ext)=="jpg" || strtolower($ext)=="jpeg")
			imagejpeg($newthumb, $thumb_path, 100);
		elseif(strtolower($ext)=="png")	
			imagepng($newthumb, $thumb_path,9);
		elseif(strtolower($ext)=="gif")
			imagegif($newthumb, $thumb_path);
			
		
		
		
		
		//@unlink($imgp);
		
	}
	
	function CreateResized($imgp, $thumb_path, $des_wid, $des_hit)
	{	 		 		 
		
	
		$ext_arr=explode(".",$imgp);
		$ext=$ext_arr[count($ext_arr)-1];
				
		if(strtolower($ext)=="jpg" || strtolower($ext)=="jpeg")
			$source = imagecreatefromjpeg($imgp);
		elseif(strtolower($ext)=="png")	
			$source = imagecreatefrompng($imgp);	
		elseif(strtolower($ext)=="gif")
			$source = imagecreatefromgif($imgp);
			
		list($width, $height) = getimagesize($imgp);

       

		$thumb = imagecreatetruecolor($des_wid, $des_hit);
		
		imagepalettecopy($thumb,$source);
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $des_wid, $des_hit, $width, $height);
		
		if(strtolower($ext)=="jpg" || strtolower($ext)=="jpeg")
			imagejpeg($thumb, $thumb_path, 100);
		elseif(strtolower($ext)=="png")	
			imagepng($thumb, $thumb_path,9);
		elseif(strtolower($ext)=="gif")
			imagegif($thumb, $thumb_path);
			
		
		
	}

	
       

}
	?>