<?php
    require_once("../../../wp-blog-header.php");

    if (!isset($_REQUEST['q'])) { return false; }
    update_option('wp_tt_query', $_REQUEST['q']);
    $rpp = (get_option('wp_tt_rpp')) ? get_option('wp_tt_rpp') : 5;
    $tweets = file_get_contents("http://search.twitter.com/search.json?rpp={$rpp}&q=" . urlencode($_REQUEST['q']));
    echo $tweets;
?>
