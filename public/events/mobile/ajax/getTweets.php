<?php
include '../common.php';
$eventObj->setEvent($_GET["event_id"]);
$jsonSearch =$eventObj->getTwitterRequestMobile();

//file_get_contents('../files/twitterSearch.json');
    
//Tweets array, 20
$mailMsg="";
$arrayTweets=json_decode($jsonSearch);
$j = 0;
for($i=0;$i<count($arrayTweets->results);$i++) {
	
	
	$tweetText = $arrayTweets->results[$i]->text;

	//mentions
	if(isset($arrayTweets->results[$i]->entities->user_mentions) && count($arrayTweets->results[$i]->entities->user_mentions)>0) {
		$menzioniArr = $arrayTweets->results[$i]->entities->user_mentions;
		for($menzioneTweet=0;$menzioneTweet<count($menzioniArr);$menzioneTweet++) {
			$stringaSub = substr(utf8_decode($arrayTweets->results[$i]->text),$menzioniArr[$menzioneTweet]->indices[0],($menzioniArr[$menzioneTweet]->indices[1]-$menzioniArr[$menzioneTweet]->indices[0]));
			$tweetText=str_replace($stringaSub,'<a href="http://twitter.com/#!/'.$menzioniArr[$menzioneTweet]->screen_name.'" target="_blank">'.$stringaSub.'</a>',$tweetText);
			
		}
	}
	//urls
	if(isset($arrayTweets->results[$i]->entities->urls) && count($arrayTweets->results[$i]->entities->urls)>0) {
		$urlsArr = $arrayTweets->results[$i]->entities->urls;
		for($urlTweet=0;$urlTweet<count($urlsArr);$urlTweet++) {
			$stringaSub = substr(utf8_decode($arrayTweets->results[$i]->text),$urlsArr[$urlTweet]->indices[0],($urlsArr[$urlTweet]->indices[1]-$urlsArr[$urlTweet]->indices[0]));
			$tweetText=str_replace($stringaSub,'<a href="'.$urlsArr[$urlTweet]->url.'" target="_blank">'.$stringaSub.'</a>',$tweetText);
			
		}
	}
	//hashtags
	if(isset($arrayTweets->results[$i]->entities->hashtags) && count($arrayTweets->results[$i]->entities->hashtags)>0) {
		$hashArr = $arrayTweets->results[$i]->entities->hashtags;
		for($hashTweet=0;$hashTweet<count($hashArr);$hashTweet++) {
			$stringaSub = substr(utf8_decode($arrayTweets->results[$i]->text),$hashArr[$hashTweet]->indices[0],($hashArr[$hashTweet]->indices[1]-$hashArr[$hashTweet]->indices[0]));
			$tweetText=str_replace($stringaSub,'<a href="http://twitter.com/#!/search?q='.urlencode('#').$hashArr[$hashTweet]->text.'" target="_blank">'.$stringaSub.'</a>',$tweetText);
			
		}
	}
	//media
	if(isset($arrayTweets->results[$i]->entities->media) && count($arrayTweets->results[$i]->entities->media)>0) {
		$mediaArr = $arrayTweets->results[$i]->entities->media;
		for($mediaTweet=0;$mediaTweet<count($mediaArr);$mediaTweet++) {
			
			$stringaSub = substr(utf8_decode($arrayTweets->results[$i]->text),$mediaArr[$mediaTweet]->indices[0],($mediaArr[$mediaTweet]->indices[1]-$mediaArr[$mediaTweet]->indices[0]));
			$tweetText=str_replace($stringaSub,'<a href="'.$mediaArr[$mediaTweet]->url.'" target="_blank">'.$stringaSub.'</a>',$tweetText);
			
		}
	}
	
   
	if($settingObj->getDateFormat() == "UK") {
		if($settingObj->getTimeFormat() == '24') {
			$tweetDate = date('d-m-Y H:i', strtotime($arrayTweets->results[$i]->created_at));
		} else {
			$tweetDate = date('d-m-Y h:i a', strtotime($arrayTweets->results[$i]->created_at));
		}	
	} else if($settingObj->getDateFormat() == "EU") {
		if($settingObj->getTimeFormat() == '24') {
			$tweetDate = date('Y-m-d H:i', strtotime($arrayTweets->results[$i]->created_at));
		} else {
			$tweetDate = date('Y-m-d h:i a', strtotime($arrayTweets->results[$i]->created_at));
		}
	} else {
		if($settingObj->getTimeFormat() == '24') {
			$tweetDate = date('m-d-Y H:i', strtotime($arrayTweets->results[$i]->created_at));
		} else {
			$tweetDate = date('m-d-Y h:i a', strtotime($arrayTweets->results[$i]->created_at));
		}
	}
	
   
	//white list
	//check if he decided to publish them anyway or to publish them only after approve
	if($settingObj->getTwitterApprovation() == "1") {
		
		if($utilityObj->isTwitterApproved($arrayTweets->results[$i]->from_user) === true) { 
		
		
			?>
			<div>
				<div class="twitter_user_icon"><img src="<?php echo str_replace("|","",$arrayTweets->results[$i]->profile_image_url);?>" border = "0"></div>
				
				<div class="twitter_user_twit">
					<div class="twitter_user_name"><a href="http://twitter.com/#!/<?php echo $arrayTweets->results[$i]->from_user;?>" target="_blank"><?php echo str_replace("|","",$arrayTweets->results[$i]->from_user);?></a></div>
					<div class="twitter_user_text">
					  <?php echo str_replace("|","",$arrayTweets->results[$i]->from_user_name);?><br>
					  <?php echo str_replace("|","",$tweetText);?><br />
					  <?php echo $tweetDate;?>
					  <?php echo "<br><br>";?>
					</div>
				</div>
				<div class="cleardiv"></div>
				<div class="twit_divide"></div>
				<div class="cleardiv"></div>
			</div>
			<?php
			$j++;
			if($j == 20) {
				break;
			}
		
		
		} else if($utilityObj->isTwitterSpam($arrayTweets->results[$i]->from_user) === false) {
			//check spam,generate token, save to db, save tweet for future email
			$token=$utilityObj->addTwitterUser($arrayTweets->results[$i]->from_user);
			
			if($token != '0' && $settingObj->getTwitterEmail()!='') {
				$mailMsg.='
				<div>
					<div class="twitter_user_icon"><img src="'.$arrayTweets->results[$i]->profile_image_url.'" border = "0"></div>						
					<div class="twitter_user_twit">
						<a href="http://twitter.com/#!/'.$arrayTweets->results[$i]->from_user.'" target="_blank">'.$arrayTweets->results[$i]->from_user.'</a><br>
						'.$arrayTweets->results[$i]->from_user_name.'<br>
						'.$tweetText.'<br />
						'.$tweetDate.'
					</div>
				</div>';
				$mailMsg.="<a href='".CALENDAR_PATH."/redirect_twitter.php?token=".$token."'>Click here to manage this tweet</a><br><br><br>";
				
			}
		
		}
		
	} else {
		
		?>
		<div>
			<div class="twitter_user_icon"><img src="<?php echo str_replace("|","",$arrayTweets->results[$i]->profile_image_url);?>" border = "0"></div>
			
			<div class="twitter_user_twit">
				<div class="twitter_user_name"><a href="http://twitter.com/#!/<?php echo $arrayTweets->results[$i]->from_user;?>" target="_blank"><?php echo str_replace("|","",$arrayTweets->results[$i]->from_user);?></a></div>
                <div class="twitter_user_text">
				  <?php echo str_replace("|","",$arrayTweets->results[$i]->from_user_name);?><br>
                  <?php echo str_replace("|","",$tweetText);?><br />
				  <?php echo $tweetDate;?>
                  <?php echo "<br><br>";?>
                </div>
			</div>
            <div class="cleardiv"></div>
            <div class="twit_divide"></div>
            <div class="cleardiv"></div>
		</div>
		<?php
		$j++;
	    if($j == 20) {
			break;
		}
	
		
	}
	
}
if($mailMsg!='') {
	//send mail to admin for Twitter approval
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=UTF-8\n";
	$headers .= "X-Priority: 5\n";
	$headers .= "X-MSMail-Priority: Low\n";
	$headers .= "X-Mailer: php\n";
	$headers .= "From: twitter@eventscalendar.com\n" . "Reply-To: \n";
	$to = $settingObj->getTwitterEmail();//send mail to user
	$subject = "New tweets on your events calendar";
	$msg = $mailMsg;
	mail($to,$subject,$msg,$headers);
}
?>