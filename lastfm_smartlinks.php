<?php
/*
Plugin Name: Last.Fm SmartLinks Widget
Description: Adds a sidebar widget to display your favorite Last.Fm Artists and Albums
Author: AdaptiveBlue, Inc.
Version: 1.0
Author URI: http://adaptiveblue.com
*/


// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_lastfm_smartlinks_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

    // Adds top artists on Last.fm to the Sidebar
	function widget_lastfm_top_artists_smartlinks($args) {

		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);

	        // get last.fm username
		$options = get_option('widget_lastfm_top_artists_smartlinks');
		$title = $options['title'];
		$float = $options['float'];
		$width = $options['width'];
		$numItems = $options['numItems'];
		$amazonId = $options['amazonId'];
		$ebayId = $options['ebayId'];
		$googleId = $options['googleId'];

		$feed = 'http%3A%2F%2Fws.audioscrobbler.com%2F1.0%2Fplace%2FUnited%252BStates%2Ftopartists.xml';

		echo $before_widget;
		echo widget_lastfm_smartlinks_createScript($feed, $title, $numItems, $width, $float, $amazonId, $ebayId, $googleId);
		echo $after_widget;
	}

	function widget_lastfm_top_artists_smartlinks_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_lastfm_top_artists_smartlinks');
		if ( !is_array($options) )
		  $options = array('username'=>__('', 'widgets'), 'title'=>__('Top Last.Fm Artists', 'widgets'), 'float'=>__('none', 'widgets'), 'numItems'=>__('3', 'widgets'), 'width'=>__('200', 'widgets'));
		if ( $_POST['lastfm_top_artists_smartlinks-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['lastfm_top_artists_smartlinks-title']));
			$options['float'] = strip_tags(stripslashes($_POST['lastfm_top_artists_smartlinks-float']));
			$options['width'] = strip_tags(stripslashes($_POST['lastfm_top_artists_smartlinks-width']));
			$options['numItems'] = strip_tags(stripslashes($_POST['lastfm_top_artists_smartlinks-numItems']));
			$options['amazonId'] = strip_tags(stripslashes($_POST['lastfm_top_artists_smartlinks-amazonId']));
			$options['ebayId'] = strip_tags(stripslashes($_POST['lastfm_top_artists_smartlinks-ebayId']));
			$options['googleId'] = strip_tags(stripslashes($_POST['lastfm_top_artists_smartlinks-googleId']));
			update_option('widget_lastfm_top_artists_smartlinks', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$username = htmlspecialchars($options['username']);
		$title = htmlspecialchars($options['title']);
		$float = htmlspecialchars($options['float']);
		$width = htmlspecialchars($options['width']);
		$numItems = htmlspecialchars($options['numItems']);
		$amazonId = htmlspecialchars($options['amazonId']);
		$ebayId = htmlspecialchars($options['ebayId']);
		$googleId = htmlspecialchars($options['googleId']);

        if(empty($numItems)) {
            $numItems = 4;
        }

        if(empty($width)) {
            $width = 200;
        }

		// form
		echo '<table>';
        echo '<tr><td><label for="lastfm_top_artists_smartlinks-title">' . __('Title:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_top_artists_smartlinks-title" name="lastfm_top_artists_smartlinks-title" type="text" value="'.$title.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_top_artists_smartlinks-width">' . __('Widget Width:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_top_artists_smartlinks-width" name="lastfm_top_artists_smartlinks-width" type="text" value="'.$width.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_top_artists_smartlinks-numItems">' . __('Num Items:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_top_artists_smartlinks-numItems" name="lastfm_top_artists_smartlinks-numItems" type="text" value="'.$numItems.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_top_artists_smartlinks-amazonId">' . __('Amazon Affiliate:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_top_artists_smartlinks-amazonId" name="lastfm_top_artists_smartlinks-amazonId" type="text" value="'.$amazonId.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_top_artists_smartlinks-ebayId">' . __('eBay Affiliate:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_top_artists_smartlinks-ebayId" name="lastfm_top_artists_smartlinks-ebayId" type="text" value="'.$ebayId.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_top_artists_smartlinks-googleId">' . __('Google Affiliate:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_top_artists_smartlinks-googleId" name="lastfm_top_artists_smartlinks-googleId" type="text" value="'.$googleId.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_top_artists_smartlinks-float">' . __('Float (optional):', 'widgets') . '</label></td><td> <select id="lastfm_top_artists_smartlinks-float" name="lastfm_top_artists_smartlinks-float">' . widget_lastfm_smartlinks_getFloatOptions($float) . '</select></td></tr>';
        echo '</table>';
        echo '<p>(The float option is necessary if your widget pushes the content of your sidebar to the bottom, or is stretched to the bottom of the sidebar)</p>';
		echo '<input type="submit" id="lastfm_top_artists_smartlinks-submit" name="lastfm_top_artists_smartlinks-submit" value="submit" />';
	}

	// Adds your favorite artists on Last.fm to the Sidebar
	function widget_lastfm_artists_smartlinks($args) {

		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);

	        // get last.fm username
		$options = get_option('widget_lastfm_artists_smartlinks');
		$username = $options['username'];
		$title = $options['title'];
		$timeframe = $options['timeframe'];
		$float = $options['float'];
		$width = $options['width'];
		$numItems = $options['numItems'];
		$amazonId = $options['amazonId'];
		$ebayId = $options['ebayId'];
		$googleId = $options['googleId'];

		$feed = 'http%3A%2F%2Fws.audioscrobbler.com%2F1.0%2Fuser%2F' . $username . '%2Ftopartists.xml';
		if(strcmp($timeframe, "3 Months") === 0){
		    $feed .= '%3Ftype%3D3month';
		}

		echo $before_widget;
		echo widget_lastfm_smartlinks_createScript($feed, $title, $numItems, $width, $float, $amazonId, $ebayId, $googleId);
		echo $after_widget;
	}

	function widget_lastfm_artists_smartlinks_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_lastfm_artists_smartlinks');
		if ( !is_array($options) )
		  $options = array('username'=>__('', 'widgets'), 'title'=>__('My Last.Fm Artists', 'widgets'), 'float'=>__('none', 'widgets'), 'numItems'=>__('3', 'widgets'), 'width'=>__('200', 'widgets'), 'timeframe'=>__('All Time', 'widgets'));
		if ( $_POST['lastfm_artists_smartlinks-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['username'] = strip_tags(stripslashes($_POST['lastfm_artists_smartlinks-username']));
			$options['timeframe'] = strip_tags(stripslashes($_POST['lastfm_artists_smartlinks-timeframe']));
			$options['title'] = strip_tags(stripslashes($_POST['lastfm_artists_smartlinks-title']));
			$options['float'] = strip_tags(stripslashes($_POST['lastfm_artists_smartlinks-float']));
			$options['width'] = strip_tags(stripslashes($_POST['lastfm_artists_smartlinks-width']));
			$options['numItems'] = strip_tags(stripslashes($_POST['lastfm_artists_smartlinks-numItems']));
			$options['amazonId'] = strip_tags(stripslashes($_POST['lastfm_artists_smartlinks-amazonId']));
			$options['ebayId'] = strip_tags(stripslashes($_POST['lastfm_artists_smartlinks-ebayId']));
			$options['googleId'] = strip_tags(stripslashes($_POST['lastfm_artists_smartlinks-googleId']));
			update_option('widget_lastfm_artists_smartlinks', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$username = htmlspecialchars($options['username']);
		$timeframe = htmlspecialchars($options['timeframe']);
		$title = htmlspecialchars($options['title']);
		$float = htmlspecialchars($options['float']);
		$width = htmlspecialchars($options['width']);
		$numItems = htmlspecialchars($options['numItems']);
		$amazonId = htmlspecialchars($options['amazonId']);
		$ebayId = htmlspecialchars($options['ebayId']);
		$googleId = htmlspecialchars($options['googleId']);

        if(empty($numItems)) {
            $numItems = 4;
        }

        if(empty($width)) {
            $width = 200;
        }

		// form
		echo '<table>';
        echo '<tr><td><label for="lastfm_artists_smartlinks-title">' . __('Title:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_artists_smartlinks-title" name="lastfm_artists_smartlinks-title" type="text" value="'.$title.'" /></td></tr>';
		echo '<tr><td><label for="lastfm_artists_smartlinks-username">' . __('Last.Fm User:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_artists_smartlinks-username" name="lastfm_artists_smartlinks-username" type="text" value="'.$username.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_artists_smartlinks-timeframe">' . __('Timeframe:', 'widgets') . '</label></td><td> <select id="lastfm_artists_smartlinks-timeframe" name="lastfm_artists_smartlinks-timeframe">' . widget_lastfm_smartlinks_getTimeFrameOptions($timeframe) . '</select></td></tr>';
        echo '<tr><td><label for="lastfm_artists_smartlinks-width">' . __('Widget Width:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_artists_smartlinks-width" name="lastfm_artists_smartlinks-width" type="text" value="'.$width.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_artists_smartlinks-numItems">' . __('Num Items:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_artists_smartlinks-numItems" name="lastfm_artists_smartlinks-numItems" type="text" value="'.$numItems.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_artists_smartlinks-amazonId">' . __('Amazon Affiliate:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_artists_smartlinks-amazonId" name="lastfm_artists_smartlinks-amazonId" type="text" value="'.$amazonId.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_artists_smartlinks-ebayId">' . __('eBay Affiliate:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_artists_smartlinks-ebayId" name="lastfm_artists_smartlinks-ebayId" type="text" value="'.$ebayId.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_artists_smartlinks-googleId">' . __('Google Affiliate:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_artists_smartlinks-googleId" name="lastfm_artists_smartlinks-googleId" type="text" value="'.$googleId.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_artists_smartlinks-float">' . __('Float (optional):', 'widgets') . '</label></td><td> <select id="lastfm_artists_smartlinks-float" name="lastfm_artists_smartlinks-float">' . widget_lastfm_smartlinks_getFloatOptions($float) . '</select></td></tr>';
		echo '</table>';
        echo '<p>(The float option is necessary if your widget pushes the content of your sidebar to the bottom, or is stretched to the bottom of the sidebar)</p>';
		echo '<input type="submit" id="lastfm_artists_smartlinks-submit" name="lastfm_artists_smartlinks-submit" value="submit" />';
	}

	// Adds your favorite albums on Last.fm to the Sidebar
	function widget_lastfm_albums_smartlinks($args) {

		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);

	        // get last.fm username
		$options = get_option('widget_lastfm_albums_smartlinks');
		$username = $options['username'];
        $timeframe = $options['timeframe'];		
		$title = $options['title'];
		$float = $options['float'];
		$width = $options['width'];
		$numItems = $options['numItems'];
		$amazonId = $options['amazonId'];
		$ebayId = $options['ebayId'];
		$googleId = $options['googleId'];
		$feed = 'http%3A%2F%2Fws.audioscrobbler.com%2F1.0%2Fuser%2F' . $username . '%2Ftopalbums.xml';
		if(strcmp($timeframe, "3 Months") === 0){
		    $feed .= '%3Ftype%3D3month';
		}

		echo $before_widget;
		echo widget_lastfm_smartlinks_createScript($feed, $title, $numItems, $width, $float, $amazonId, $ebayId, $googleId);
		echo $after_widget;
	}

	function widget_lastfm_albums_smartlinks_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_lastfm_albums_smartlinks');
		if ( !is_array($options) )
		  $options = array('username'=>__('', 'widgets'), 'title'=>__('My Last.Fm Albums', 'widgets'), 'float'=>__('none', 'widgets'), 'numItems'=>__('3', 'widgets'), 'width'=>__('200', 'widgets'), 'timeframe'=>__('All Time', 'widgets'));
		if ( $_POST['lastfm_albums_smartlinks-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['username'] = strip_tags(stripslashes($_POST['lastfm_albums_smartlinks-username']));
			$options['timeframe'] = strip_tags(stripslashes($_POST['lastfm_albums_smartlinks-timeframe']));
			$options['title'] = strip_tags(stripslashes($_POST['lastfm_albums_smartlinks-title']));
			$options['float'] = strip_tags(stripslashes($_POST['lastfm_albums_smartlinks-float']));
			$options['width'] = strip_tags(stripslashes($_POST['lastfm_albums_smartlinks-width']));
			$options['numItems'] = strip_tags(stripslashes($_POST['lastfm_albums_smartlinks-numItems']));
			$options['amazonId'] = strip_tags(stripslashes($_POST['lastfm_albums_smartlinks-amazonId']));
			$options['ebayId'] = strip_tags(stripslashes($_POST['lastfm_albums_smartlinks-ebayId']));
			$options['googleId'] = strip_tags(stripslashes($_POST['lastfm_albums_smartlinks-googleId']));

			update_option('widget_lastfm_albums_smartlinks', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$username = htmlspecialchars($options['username']);
		$timeframe = htmlspecialchars($options['timeframe']);
		$title = htmlspecialchars($options['title']);
		$float = htmlspecialchars($options['float']);
		$width = htmlspecialchars($options['width']);
		$numItems = htmlspecialchars($options['numItems']);
		$amazonId = htmlspecialchars($options['amazonId']);
		$ebayId = htmlspecialchars($options['ebayId']);
		$googleId = htmlspecialchars($options['googleId']);

        if(empty($numItems)) {
            $numItems = 4;
        }

        if(empty($width)) {
            $width = 200;
        }

		// form
		echo '<table>';
        echo '<tr><td><label for="lastfm_albums_smartlinks-title">' . __('Title:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_albums_smartlinks-title" name="lastfm_albums_smartlinks-title" type="text" value="'.$title.'" /></td></tr>';
		echo '<tr><td><label for="lastfm_albums_smartlinks-username">' . __('LastFm User:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_albums_smartlinks-username" name="lastfm_albums_smartlinks-username" type="text" value="'.$username.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_albums_smartlinks-timeframe">' . __('Timeframe:', 'widgets') . '</label></td><td> <select id="lastfm_albums_smartlinks-timeframe" name="lastfm_albums_smartlinks-timeframe">' . widget_lastfm_smartlinks_getTimeFrameOptions($timeframe) . '</select></td></tr>';
        echo '<tr><td><label for="lastfm_albums_smartlinks-width">' . __('Widget Width:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_albums_smartlinks-width" name="lastfm_albums_smartlinks-width" type="text" value="'.$width.'" /></td>';
        echo '<tr><td><label for="lastfm_albums_smartlinks-numItems">' . __('Num Items:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_albums_smartlinks-numItems" name="lastfm_albums_smartlinks-numItems" type="text" value="'.$numItems.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_albums_smartlinks-amazonId">' . __('Amazon Affiliate:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_albums_smartlinks-amazonId" name="lastfm_albums_smartlinks-amazonId" type="text" value="'.$amazonId.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_albums_smartlinks-ebayId">' . __('eBay Affiliate:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_albums_smartlinks-ebayId" name="lastfm_albums_smartlinks-ebayId" type="text" value="'.$ebayId.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_albums_smartlinks-googleId">' . __('Google Affiliate:', 'widgets') . '</label></td><td> <input style="width: 200px;" id="lastfm_albums_smartlinks-googleId" name="lastfm_albums_smartlinks-googleId" type="text" value="'.$googleId.'" /></td></tr>';
        echo '<tr><td><label for="lastfm_albums_smartlinks-float">' . __('Float (optional):', 'widgets') . '</label></td><td> <select id="lastfm_albums_smartlinks-float" name="lastfm_albums_smartlinks-float">' . widget_lastfm_smartlinks_getFloatOptions($float) . '</select></td></tr>';
        echo '</table>';
		echo '<p>(The float option is necessary if your widget pushes the content of your sidebar to the bottom, or is stretched to the bottom of the sidebar)</p>';
		echo '<input type="submit" id="lastfm_albums_smartlinks-submit" name="lastfm_albums_smartlinks-submit" value="submit" />';
	}

	function widget_lastfm_smartlinks_createScript($feed, $title, $numItems, $width, $float, $amazonId, $ebayId, $googleId) {
        if(empty($numItems)) {
            $numItems = 4;
        }

        if(empty($width)) {
            $width = 200;
        }

        $script = '<script src="http://' . widget_lastfm_smartlinks_getHostname() . '/users/GenerateBlueLinks.php?skin=white&width=' . $width . '&display=both&numItems=' . $numItems . '&auto=yes&title=' . $title . '&xsl=lastfm.xsl&feedUrl=' . $feed . '&layout=list&blueAmazonId=' . $amazonId . '&blueEbayId=' .$ebayId .'&blueGoogleId=' . $googleId .'" type="text/javascript"></script>';

	    if(strcmp($float, "left") === 0 || strcmp($float, "right") === 0) {
	        return '<div style="float:' . $float . '">' . $script . '</div><div style="clear:' . $float .'"></div>';
	    }
	    else {
	        return $script;
	    }

	}

	function widget_lastfm_smartlinks_getHostname() {
        $serverIndex = rand(1, 10);
        return "s" . $serverIndex . ".smrtlnks.com";
	}

	function widget_lastfm_smartlinks_getFloatOptions($float) {
	    $floatOptions = array("none", "right", "left");
	    return widget_lastfm_smartlinks_getOptions($float, $floatOptions);
	}

	function widget_lastfm_smartlinks_getTimeframeOptions($timeframe) {
	    $options = array("All Time", "3 Months");
	    return widget_lastfm_smartlinks_getOptions($timeframe, $options);
	}

	function widget_lastfm_smartlinks_getOptions($selected, $options) {
	    $result = '';

		foreach($options as $option){
		    $result .= '<option value="' . $option . '"';
		    if(strcmp($selected, $option) ===0) {
		        $result .= ' selected ';
		    }
		    $result .= '>' . $option . '</option>';
		}

		return $result;
	}

	register_sidebar_widget(array('My Last.Fm Artists', 'widgets'), 'widget_lastfm_artists_smartlinks');
	register_widget_control(array('My Last.Fm Artists', 'widgets'), 'widget_lastfm_artists_smartlinks_control', 320, 415);

	register_sidebar_widget(array('My Last.Fm Albums', 'widgets'), 'widget_lastfm_albums_smartlinks');
	register_widget_control(array('My Last.Fm Albums', 'widgets'), 'widget_lastfm_albums_smartlinks_control', 320, 415);

	register_sidebar_widget(array('Top Last.Fm Artists', 'widgets'), 'widget_lastfm_top_artists_smartlinks');
	register_widget_control(array('Top Last.Fm Artists', 'widgets'), 'widget_lastfm_top_artists_smartlinks_control', 320, 355);
}

add_action('widgets_init', 'widget_lastfm_smartlinks_init');

?>