$(document).ready(function(){

	// Using the load AJAX method to fetch the subscriber markup
	// from subscriber_count.php:
	
	$('#main').load('subscriber_count.php',function(){
													
		// Once loaded, convert the title attributes to tooltips
		// with the tipTip jQuery plugin:
		
		$('.subscriberStats div').tipTip({defaultPosition:'top'});
	})
	
});
