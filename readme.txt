=== Topical Tweets ===
Contributors: dfederighi
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7365126
Tags: twitter,tweet,plugin 
Requires at least: 2.7
Tested up to: 2.7
Stable tag: 1.2

Topical Tweets enables the display of Twitter Updates in your blog for a specific query

== Description ==

Topical Tweets will display the latest tweets, fully formatted, for any query you specify. Please 
send feedback, enhancement requests, bug details or any questions about installation to 
dfederighi@yahoo.com

== Installation ==

1. Upload the folder `topical-tweets` to the `wp-content/plugins` directory.

   The main script should be at `/wp-content/plugins/topical-tweets/topical-tweets.php`.

2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure under Settings -> Topical Tweets
4. If you want to use Topical Tweets in your sidebar (the recommended spot), simply add the following code 
   in your sidebar.php file
        `<?php wp_topical_tweets(); ?>`

   This function will generate an unordered list (UL) element in HTML. Here is a code snippet with how your 
   sidebar.php file should look. You can place the call to wp_topical_tweets inside sidebar directly, or you 
   can include this code inside of a LI element that's included as part of an existing list in your sidebar 
   display code.

   <!--- Sidebar Starts -->
   <div id="sidebar">
        <div id="search">
        <!-- removed to save space -->
        </div>

        <div id="sidebar_in">
            `<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?>`
            `<?php endif; ?>`
        </div>
        `<?php wp_topical_tweets(); ?>`
   </div>
   <!--- Sidebar Ends -->
 

== Frequently Asked Questions ==

= Why does your plugin suck so bad? =

This is my first attempt at creating a Wordpress plugin for public consumption. The quality will go up 
from here.

= What are the features of Topical Tweets =

- display the latest tweets for your keyword (supports advanced search syntax)
- update the query by clicking the keyword in the Topical Tweets title (only enabled for admins)
- topics, users and urls are automatically linked to the correct sources
- view the full list of search results for your keyword search
- configurable display through Settings

= List of configurable options =

- Show Title
- Show Footer
- Number of Results
- Show Credit (give me some props)

== Screenshots ==

1. This screenshot shows the default display for the plugin

== Changelog ==
= 1.0 =
* Initial add/check-in (includes basic functionality to configure keyword, number results etc...)
