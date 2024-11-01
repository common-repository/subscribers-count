<?php
  /*
   Plugin Name: SubscribersCount
   Plugin URI: http://www.techpaf.fr/subscriber-count/
   Description: Shows the numbers of Rss,Twitter & facebook subscribers.
   Version: 1.0
   Author: Antoine Martin, Martin Angelov
   Author URI: http://antoine-martin.me, http://tutorialzine.com/
   License: GPL
   */
  

  if (!class_exists("Subscribers_count")) {
      class Subscribers_count extends WP_Widget {
          var $adminOptionsName = "SubscribersCountAdminOptions";
          var $adminUsersName = "SubscribersCountAdminUsersOptions";
          function Subscribers_count()
          {
              //constructor			  
          }
          function init()
          {
              $this->getAdminOptions();
          }
          //Returns an array of admin options
          function getAdminOptions()
          {
              $SubscribersCountAdminOptions = array('rss' => 'Default', 'twitter' => 'Default', 'fb' => 'Default', 'google' => 'Default');
              
              $SubsOptions = get_option($this->adminOptionsName);
              if (!empty($SubsOptions)) {
                  foreach ($SubsOptions as $key => $option)
                      $SubscribersCountAdminOptions[$key] = $option;
              }
              update_option($this->adminOptionsName, $SubscribersCountAdminOptions);
              add_option('subscribers_count', $SubsOptions, '', 'yes');
              return $SubscribersCountAdminOptions;
          }
          
          
          //Prints out the admin page
          function printAdminPage()
          {
              $SubsOptions = $this->getAdminOptions();
              
              if (isset($_POST['update_SubscribersCount'])) {
                  if (isset($_POST['SubsRss'])) {
                      $SubsOptions['rss'] = $_POST['SubsRss'];
                  }
                  if (isset($_POST['SubsTwitter'])) {
                      $SubsOptions['twitter'] = $_POST['SubsTwitter'];
                  }
                  if (isset($_POST['SubsFb'])) {
                      $SubsOptions['fb'] = $_POST['SubsFb'];
                  }
				  if (isset($_POST['SubsGoogle'])) {
                      $SubsOptions['google'] = $_POST['SubsGoogle'];
                  }

                  update_option('Subscribers_count', $SubsOptions);
?>
<div class="updated"><p><strong><?php
                  _e("Settings Updated.", "Subscribers_count");
?></strong></p></div>
          <?php
              }
?>
<div class=wrap>
<form method="post" action="<?php
              echo $_SERVER["REQUEST_URI"];
?>">
<h2>Subscribers Count Options</h2>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-29711919-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php $options = get_option('subscribers_count');  ?>
<h3>Rss</h3>
<p>Add the name of your rss feeds (only feedburner)</p>
<p><i>eg : techpaf for this url http://feeds.feedburner.com/<strong>techpaf</strong></i></p>
<label for="SubsRss"><input type="text" id="SubsRss" name="SubsRss" placeholder="<?php if(isset($options['rss'])){ echo $options['rss']; } ?>" /></label>

<h3>Twitter</h3>
<p>Add the username of your twitter account</p>
<p><i>eg : techpaf for this username @<strong>techpaf</strong></i></p>
<label for="SubsTwitter"><input type="text" id="SubsTwitter" name="SubsTwitter" placeholder="<?php if(isset($options['twitter'])){ echo $options['twitter']; } ?>" /></label>

<h3>Facebook</h3>
<p>Add the url of your facebook fan pages</p>
<p><i>eg : 285148825425 id of your facebook fan pages for this pages http://www.facebook.com/pages/Techpaf/<strong>285148825425</strong>?ref=ts</i></p>
<label for="SubsFb"><input type="text" id="SubsFb" name="SubsFb" placeholder="<?php if(isset($options['fb'])){ echo $options['fb']; } ?>"  /></label>

<h3>Google+</h3>
<p>Add the ID of your google + page</p>
<p><i>eg : 113331275469747810356 id of your Google+ pages for this user https://plus.google.com/<strong>113331275469747810356</strong>/posts</i></p>
<label for="SubsGoogle"><input type="text" id="SubsGoogle" name="SubsGoogle" placeholder="<?php if(isset($options['google'])){ echo $options['google']; } ?>"  /></label>

<div class="submit">
<input type="submit" name="update_SubscribersCount" value="<?php
              _e('Update Settings', 'Subscribers_count')
?>" /></div>
</form>

<h2>Once you get done with this, you just need to add the widget :)</h2>
 </div>
          <?php
              }//End function printAdminPage()
              
              //Save the updated options to the database
              function printAdminUsersPage()
              {
                  if (isset($_POST['update_SubscribersCount']) && isset($_POST['SubsTwitter']) && isset($_POST['SubsFb']) && isset($_POST['SubsRss']) && isset($_POST['SubsGoogle'])) {
                      $SubsOptions[$data] = $_POST['SubsTwitter'] . "," . $_POST['SubsFb'] . "," . $_POST['SubsRss']."," . $_POST['SubsGoogle'];?>
                <div class="updated"><p><strong>Settings successfully updated.</strong></p></div>
              <?php
                      update_option('subscribers_count', $SubsOptions);
                  }
              }
              //End function printAdminUsersPage()
          }
  

} //End Class Subscribers_count

function enqueue_my_styles(){
	wp_enqueue_style('styles', '/wp-content/plugins/subscribers-count/css/styles.css');  
}

function show_widget(){
	if (is_dynamic_sidebar()){
		$options = get_option('subscribers_count'); 
                if (!isset($options)) {
                    echo 'You need to check options pages ! Something is missing.';
                } else {
                    
                    // HTML
                   // $options['rss'] .'<br />'. $options['twitter'] .'<br />'.$options['fb'].'<br />'; 
                    
                    error_reporting(E_ALL ^ E_NOTICE);

					require "includes/subscriber_stats.class.php";

					$cacheFileName = "cache.txt";

					// IMPORTANT: after making changes to this file (or the SubscriberStats class)
					// remeber to delete cache.txt from your server, otherwise you wont see your changes.

					// If a cache file exists and it is less than 6*60*60 seconds (6 hours) old, use it:

					// if(file_exists($cacheFileName) && time() - filemtime($cacheFileName) < 6*60*60)
					// {
					//	$stats = unserialize(file_get_contents($cacheFileName));
					// }

					if(!$stats)
					{
						// If no cache was found, fetch the subscriber stats and create a new cache:
						
						$stats = new SubscriberStats(array(
							'facebookFanPageURL'	=> $options['fb'],
							'feedBurnerURL'			=> $options['rss'],
							'twitterName'			=> $options['twitter'],
							'googleName'			=> $options['google']
						));
						
						// Serialize turns the object into a string,
						// which can later be restored with unserialize():
						
					//	file_put_contents($cacheFileName,serialize($stats));
					}


					//	You can access the individual stats like this:
					//	$stats->twitter;
					//	$stats->facebook;
					//	$stats->rss;

					//	Output the markup for the stats:

					$stats->generate();
                }
	}
}

if (class_exists("Subscribers_count")) {
  $dl_subscribersCount = new Subscribers_count();
}

//Initialize the admin and users panel
if (!function_exists("SubscribersCount_ap")) {
  function SubscribersCount_ap() {
    global $dl_subscribersCount;
    if (!isset($dl_subscribersCount)) {
      return;
    }
    if (function_exists('add_options_page')) {
  add_options_page('SubscribersCount', 'Subscriber Count', 9, basename(__FILE__), array(&$dl_subscribersCount, 'printAdminPage'));
    }
  }  
}


	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 **/
wp_register_sidebar_widget(
    'Subscribers_count_1',        // your unique widget id
    'SubscribersCount',          // widget name
    'show_widget',  // callback function
    array(                  // options
        'description' => 'Show up your subscribers stats'
    )
);




//Actions and Filters  
if (isset($dl_subscribersCount)) {

  //Actions
  add_action('admin_menu', 'SubscribersCount_ap');
  add_action('activate_Subscribers-count/Subscribers-count.php',  array(&$dl_subscribersCount, 'init'));
  add_action('activate_Subscribers-count/Subscribers-count.php',  array(&$dl_subscribersCount, 'init'));
  add_action('widget', 'show_widget');
  add_action( 'wp_print_styles', 'enqueue_my_styles' );

}


?>