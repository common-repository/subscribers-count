<?php

class SubscriberStats{
	
	public	$twitter,$rss,$facebook,$google;
	public	$services = array();
	

	
	public function __construct($arr){
	
		function gplus_count($id){
		//
		$count = get_transient('gplus_count');
		if ($count !== false) return $count;
		$count = 0;
		$dataOrig = file_get_contents('https://plus.google.com/'.$id.'/posts'); 
		if (is_wp_error($dataOrig)) {
			return 'Error!!!';
		}
		else {
			$matchOrig = preg_match('/<h4 class="nPQ0Mb pD8zNd">(.*?)<\/h4>/s', $dataOrig, $matches);
			if (isset($matches) && !empty($matches)){
				$count2 = $matches[1];
				$count = preg_replace('/\D/', '', $count2);
			}
		}
	//	set_transient('gplus_count', $count, 24); 
		return $count;
	}
	
	function fb_count($fb_id){
			 
		$count = get_transient('fb5_count');
		if ($count !== false) return $count;
			 $count = 0;
			 $data = wp_remote_get('http://api.facebook.com/restserver.php?method=facebook.fql.query&query=SELECT%20fan_count%20FROM%20page%20WHERE%20page_id='.$fb_id.'');
	   if (is_wp_error($data)) {
			 return 'Error!!';
	   }else{
			$countOrig = strip_tags($data[body]);
		$count = preg_replace('/\s+/','',$countOrig); // strip whitespace
	   }
	set_transient('fb5_count', $count, 24); // 24 hour cache
	return $count;
	}
	
	function twitter_count($user){
	 $count = get_transient('twitter_count');
		if ($count !== false) return $count;
			 $count = 0;
			 if (isset($user)){
				$dataOrig = file_get_contents('http://twitter.com/users/show/'.$user);
			 }
	   if (is_wp_error($dataOrig)) {
			 return 'Error!!!';
	   }else{
			 $profile = new SimpleXMLElement ( $dataOrig );
			 $countOrig = $profile->followers_count;
			 $count = strval ( $countOrig );
			 }
	//set_transient('twitter_count', $count, 24); // 24 hour cache
	return $count;
	}

		$this->services = $arr;
		

	
	function rss_count($user){
	 $count = get_transient('rss_count');
		if ($count !== false) return $count;
			 $count = 0;
	   if (is_wp_error($dataOrig)) {
			 return 'Error!!!';
	   }else{
				$xml = new SimpleXMLElement(file_get_contents('http://feedburner.google.com/api/awareness/1.0/GetFeedData?uri='.$user));
				$count = $xml->feed->entry['circulation'];
			 }
	//set_transient('rss_count', $count, 24); // 24 hour cache
	return $count;
	}
	
	/*delete_transient('gplus_count');
	delete_transient('twitter_count');
	delete_transient('rss_count');
	delete_transient('fb5_count');*/

		if(strlen($arr['feedBurnerURL'])!=0){
			$this->rss 		= 	rss_count($arr['feedBurnerURL']);	
		}
		if(strlen($arr['twitterName'])!=0){
			$this->twitter	=	twitter_count($arr['twitterName']);
		}
		if(strlen($arr['facebookFanPageURL'])!=0){
			$this->facebook	=	fb_count($arr['facebookFanPageURL']);
		}
		
		if(strlen($arr['googleName'])!=0){
			$this->google 	= 	gplus_count($arr['googleName']);			
		}
		
		
		
	}
	
	
	
	public function generate(){

		$total = number_format($this->rss+$this->twitter+$this->facebook+$this->google);
		
		echo '
		<div id="sidebarSubscribe">

			<div class="social">		    

                <h3 class="title">Join our community</h3>   '; 
		if (isset($this->rss)){
			echo '	<a id="subscribeRSS" title="'.$this->rss.'" href="http://feeds.feedburner.com/'.$this->services['feedBurnerURL'].'" target="_blank">
						<span class="icon"><span class="text-subscriber">'.$this->rss.' subscribers</span></span>   
					</a>';
		}
            
		if (isset($this->facebook)){	
			echo '
					<a id="followFacebook" title="'.$this->facebook.'" href="http://www.facebook.com/'.$this->services['facebookFanPageURL'].'" target="_blank">
						<span class="icon"><span class="text-subscriber">'.$this->facebook.' fans</span></span>          
					</a> ';
		}
		if (isset($this->twitter)){		
			echo '
					<a id="followTwitter" title="'.$this->twitter.'" href="http://www.twitter.com/'.$this->services['twitterName'].'" target="_blank">
						<span class="icon"><span class="text-subscriber">'.$this->twitter.' followers</span></span>         
					</a>';
		}	
		
		if (isset($this->google)){	
			echo '
					<a id="followGoogle" title="'.$this->google.'" href="https://plus.google.com/'.$this->services['googleName'].'/posts" target="_blank">
						<span class="icon"><span class="text-subscriber">'.$this->google.' plus one</span></span>         
					</a>';
		}			
		
echo '			
		</div>
        </div>';

		
	}
}

?>