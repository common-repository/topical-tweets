/**
 * The core javascript for the topical tweets plugin.
 * Contains the functions to fetch tweets and handle the 
 * onclick updation of the keyword in the title.  
 * author: Dale Federighi <dfederighi@yahoo.com>
 */
(function() {
    
    tt_conf = {
        'query':'dfederighi'
    };

    var editKeyword = function(e) {
        if (YAHOO.util.Dom.get('tt_kw_inp')) { return; }

        var el = document.getElementById('tt_kw');
        var keyw = el.innerHTML;
        var ti = document.createElement('input');
        ti.type = 'text';
        ti.value = YAHOO.util.Dom.get('tt_kw').innerHTML || 'keyword';
        ti.id = 'tt_kw_inp';
        el.innerHTML = '';
    
        var bt = document.createElement('input');
        bt.type = 'button';
        bt.value = 'update';
        bt.id = 'tt_kw_btn';

        el.appendChild(ti);
        el.appendChild(bt);
        ti.select();

        YAHOO.util.Event.addListener(YAHOO.util.Dom.get('tt_kw_btn'), 'click', function(e) {
            YAHOO.util.Event.stopEvent(e);
            var el = document.getElementById('tt_kw');
            var cef = YAHOO.util.Dom.get('tt_kw_inp').value;
            if (cef != keyw) { fetchThemedTweets(cef); }
            el.removeChild(YAHOO.util.Dom.get('tt_kw_inp'));
            el.removeChild(YAHOO.util.Dom.get('tt_kw_btn'));
            el.innerHTML = cef;
        });
    };

    var subLinks = function(t) {
        t = t.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&\?\/.=]+/g, function(url) {
            return url.link(url);
        });
        t = t.replace(/\@[A-Za-z0-9-_:%&\?\/.=]+/g, function(id) {
            return id.link("http://www.twitter.com/" + id.replace('@', ''));
        });
        t = t.replace(/\#[A-Za-z0-9-_:%&\?\/.=]+/g, function(topic) {
            return topic.link("http://search.twitter.com/search?q=" + topic.replace('#', '%23'));
        });
        return t;
    };

    var formatTweetTime = function(t_msec) {
        var secs = t_msec / 1000;
        var mins = secs > 60 ? (secs / 60) : 0;
        var hour = mins > 60 ? (mins / 60) : 0;
        var days = hour > 24 ? (hour / 24) : 0;
        if (days > 0) { return Math.round(days) + ' day' + (Math.round(days) > 1 ? 's' : ''); }
        if (hour > 0) { return Math.round(hour) + ' hour' + (Math.round(hour) > 1 ? 's' : ''); }
        if (mins > 0) { return Math.round(mins) + ' minute' + (Math.round(mins) > 1 ? 's' : ''); }
        return Math.round(secs) + ' seconds';
    };

    var fetchThemedTweets = function(q) {
        var query = q ? q : '';
        var ul_ref = YAHOO.util.Dom.get('ttweet_list');
        ul_ref.innerHTML = '';
        var callback = {
            success: function(o) {
                var data = eval('(' + o.responseText + ')');
                var results = data.results;
                if (!results.length) {
                    var nrli = document.createElement('li');
                    nrli.innerHTML = '<div align="center">' +
                                     'No tweets found for ' +
                                     '<b>' + q + '</b></div>';
                    ul_ref.appendChild(nrli);
                    return;
                }
                YAHOO.util.Event.addListener(YAHOO.util.Dom.get('tt_slink'), 'click', function(e) {
                    location.href = 'http://search.twitter.com/search?q=' + query;
                });
                if (YAHOO.util.Dom.get('tt_slink_kw')) {
                    YAHOO.util.Dom.get('tt_slink_kw').innerHTML = q;
                }
                for (var idx = 0; idx < results.length; idx++) {
                    var res = results[idx];
                    var li = document.createElement('li');
                    if (res.profile_image_url != '') {
                        var content = '<div class="tt_avatar"><a href="http://www.twitter.com/' +
                                      res.from_user + '">' + '<img src="' + res.profile_image_url +
                                      '" border="1"/></a></div>';
                    }
                    content += '<div class="msg">';
                    content += '<a class="tweet_uid" href="http://www.twitter.com/' + res.from_user +
                               '" target="_blank">' + res.from_user + '</a>:&nbsp;';
                    content += subLinks(res.text);
                    var tweet_date = new Date(res.created_at).getTime();
                    var curnt_date = new Date().getTime();
                    var was_msecs = curnt_date - tweet_date;
                    var post_time = formatTweetTime(was_msecs) + ' ago';
                    content += ' - <small>' + post_time.link('http://www.twitter.com/' +
                               res.from_user + '/statuses/' + res.id) + '</small>';
                    content += '</div>';
                    content += '<p class="clear"/>';
                    li.innerHTML = content;
                    ul_ref.appendChild(li);
                }
            },
            failure: function(o) {}
        };
        var purl = '/wp-content/plugins/topical-tweets/tt-proxy.php?q=' + encodeURIComponent(query);
        YAHOO.util.Connect.asyncRequest('GET', purl, callback);
    };

    YAHOO.util.Event.onDOMReady(function() {
        fetchThemedTweets(tt_conf.query);
        YAHOO.util.Event.addListener(YAHOO.util.Dom.get('tt_kw'), 'click', editKeyword);
    });

})();
