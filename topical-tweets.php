<?php
/*
Plugin Name: Topical Tweets 
Version: 1.0
Description: Uses the Twitter API to fetch tweets on your favorite topic  
Author: Dale Federighi
Author URI: http://www.federighi.net
*/

/*
Copyright 2009 Dale Federighi  (http://www.federighi.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function wp_topical_tweets() {

    $tt_query = (get_option('wp_tt_query')) ? get_option('wp_tt_query') : 'yahoo';
    $tt_rpp = (get_option('wp_tt_rpp')) ? get_option('wp_tt_rpp') : '5';
    $tt_show_title = (get_option('wp_tt_show_title')) ? get_option('wp_tt_show_title') : 0;
    $tt_show_footer = (get_option('wp_tt_show_footer')) ? get_option('wp_tt_show_footer') : 0;


echo <<<E_O_HTML
	<link rel="stylesheet" type="text/css" href="/wp-content/plugins/topical-tweets/css/ttweets.css"/>
	<script src="http://yui.yahooapis.com/combo?2.7.0/build/yuiloader-dom-event/yuiloader-dom-event.js&2.7.0/build/connection/connection-min.js"></script>
	<script src="/wp-content/plugins/topical-tweets/js/ttweets.js"></script>
    <script>
        tt_conf.query = "{$tt_query}";
    </script>
E_O_HTML;

    if (get_option('wp_tt_show_title') == 1) {
        $kw_id = (current_user_can('level_10') ? 'tt_kw' : 'tt_kw_noedit');
        echo <<<E_O_HTML
        <h3 align='center'>Twitter Updates for <span id='${kw_id}' title='Change Keyword'>${tt_query}</span></h3>
E_O_HTML;
    }

    echo <<<E_O_HTML
    <ul id="ttweet_list"></ul>
E_O_HTML;


    if (get_option('wp_tt_show_footer') == 1) {
        echo <<<E_O_HTML
        <h4 align='center' id='tt_slink' class='tt_slink'>
            <a href='javascript:void(0)'>See all tweets re: <span id="tt_slink_kw">${tt_query}</span></a>
        </h4>
E_O_HTML;
    }

    if (get_option('wp_tt_show_creditline') == 1) {
    echo <<<E_O_HTML
    <h4 style="font-size:85%;padding:5px 0 0 5px;">
        <a href="http://www.federighi.net" target="_blank">Topical Tweets</a> developed by Dale Federighi 
        (<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7365126" target="_blank">Donate</a>)
    </h4>
E_O_HTML;
    }
}

function wp_ttweets_admin_page() {
    /* handle form submission */
	if(isset($_POST['submitted'])){
        	$query = isset($_POST['ttweets_query']) ? $_POST['ttweets_query'] : '';
        	$rpp = isset($_POST['ttweets_rpp']) ? $_POST['ttweets_rpp'] : '5';
            $show_title = isset($_POST['ttweets_show_title']) ? $_POST['ttweets_show_title'] : 0; 
            $show_footer = isset($_POST['ttweets_show_footer']) ? $_POST['ttweets_show_footer'] : 0;
            $show_creditline = isset($_POST['ttweets_show_creditline']) ? $_POST['ttweets_show_creditline'] : 0;

        	update_option("wp_tt_query", $query);
            update_option("wp_tt_rpp", $rpp);
            update_option("wp_tt_show_title", $show_title);
            update_option("wp_tt_show_footer", $show_footer);
            update_option("wp_tt_show_creditline", $show_creditline);
		    echo '<p style="padding:2px;width:90%;border:1px solid #000;background-color:#ccc;font-weight:bold">';
            echo 'Topical Tweets Plugin Updated.</p>';
   	}
    /* display configuration form */
	echo <<<E_O_HTML
    	<h2>Topical Tweets Plugin Configuration</h2>
	    <br />
    	<form method="post" name="options" target="_self">
            <div><b>Keyword</b> <span style="color:#666;">(e.g. 'warcraft', 'yankees OR mets', 'movie -scary')</span>
            - Check out the <a href="http://search.twitter.com/operators" target="_blank">twitter search operators document</a> for all supported keyword strings</div>
E_O_HTML;

        echo '<input type="text" name="ttweets_query" value="'.((get_option('wp_tt_query') != '')?get_option('wp_tt_query'):'').'"/><br/>';
        echo '<br/>';
        echo '<div style="font-weight:bold">Number of Results</div>';
        echo '<select name="ttweets_rpp">';
        $nr_arr = array(5,10,15,20,25);
        foreach ($nr_arr as $nr) {
            if (get_option('wp_tt_rpp') && (get_option('wp_tt_rpp') == $nr)) {
                echo '<option value="' . $nr . '" selected="">' . $nr . '</option>';
            } else {
                echo '<option value="' . $nr . '">' . $nr . '</option>';
            }
        }
        echo '</select><br/>';
        echo '<br/>';
        echo '<input type="checkbox" name="ttweets_show_title" value="1" ' . (get_option('wp_tt_show_title') && (get_option('wp_tt_show_title') == 1) ? 'checked=""' : '') . '/> Show Title<br/>';
        echo '<br/>';
        echo '<input type="checkbox" name="ttweets_show_footer" value="1" ' . (get_option('wp_tt_show_footer') && (get_option('wp_tt_show_footer') == 1) ? 'checked=""' : '') . '/> Show Footer<br/>';
        echo '<br/>';
        echo '<input type="checkbox" name="ttweets_show_creditline" value="1" ' . (get_option('wp_tt_show_creditline') && (get_option('wp_tt_show_creditline') == 1) ? 'checked=""' : '') . '/> Show Credit - <span style="font-size: 85%;"><a href="http://www.federighi.net" target="_blank">Topical Tweets</a> developed by Dale Federighi (<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7365126" target="_blank">Donate</a>)</span><br/>';
        echo <<<E_O_HTML
        <br/>
		<input type="hidden" name="submitted" value="true"/>
		<input type="submit" value="Update" />
	</form>
E_O_HTML;
}

function wp_ttweets_add_to_menu() {
    add_submenu_page('options-general.php', 'Topical Tweets', 'Topical Tweets', 10, __FILE__, 'wp_ttweets_admin_page');
}

add_action('admin_menu', 'wp_ttweets_add_to_menu');

?>
