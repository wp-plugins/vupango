/*************************************************************************
	Twitter AJAX Interface v1.2
	  This script adds tweets to Pango page
	  jQuery is required

	Written by Joe Clay

	Revision History
	v0.1 - 26 Jan 2011
		Modified from an earlier PHP version for use with Pango

	v1.0 - 2 Feb 2011
		First completely working AJAX version

	v1.1 - 2 Feb 2011
		Made into a jQuery plugin

	v1.2 - 9 Feb 2011
		First installed version
		Required some fixes to accomodate Wordpress
*************************************************************************/

(function($){
	$.fn.VuPangoTweets = function(options) {


/*************************************************************************
	Configuration
*************************************************************************/

		var defaults = {
			div:		'div#tweet-display',
			searchTerm:	'#TangoPanopticon',
			lang:		'en',
			rpp:		'20',
			delay:		'15000',
			tweetDelay:	'5000',
			fadeDelay:	'800',
			imgSize:	'48'
			};
		
		var options = $.extend(defaults,options);


/*************************************************************************
	Variable Initialization
*************************************************************************/

		searchTerm = encodeURIComponent(options.searchTerm);
		var sinceID = '0';
		var lang = 'en';
		var allTweets = '';
		var tweetCount = '0';

		
/*************************************************************************
	Main Loop
*************************************************************************/
		
		//$(options.div).append('<ul id="tweets"></ul>'); Broken in wordpress for some reason had to hard code it
		
		showTweets();
		getTweets();
		
		function getTweets()
		{
			
			$.ajax({
				type:'GET',
				dataType:'jsonp',
				url:'http://search.twitter.com/search.json?callback=?&lang=' + options.lang + '&q=' + searchTerm + '&rpp=' + options.rpp + '&since_id=' + sinceID,
				success:function(tweets){
					appendTweets(tweets);
					//window.setTimeout(getTweets, options.delay);
					}
			});
		}
		
		function appendTweets(tweets)
		{
			sinceID = tweets.max_id_str;
			
			tweets = $.makeArray(tweets.results).reverse();
			
			$.each(tweets, function(i,tweet)
			{
				var text = tweet.text;
				var user = tweet.from_user;
				var user_link = 'http://twitter.com/#!/' + user;
				var user_img = '<a href="' + user_link + '"><img src="' + tweet.profile_image_url + '" height="' + options.imgSize + '" width="' + options.imgSize + '" style="float:left;margin-right:15px;" /></a>';
				var time = tweet.created_at;
				user_link = '<strong><a href="' + user_link + '">' + user + '</a>:</strong>';
				
				text = linkUp(text);
				
				tweetCount++;
				$('<li id="tweet-' + tweetCount + '" style="list-style:none;">' + user_img + user_link + ' ' + text + '</li>').hide().prependTo('ul#tweets');
			});
		}
		
		function showTweets()
		{
			var j = '0';
			window.setInterval(function(){
				if(j < tweetCount)
				{
					j++;
					$('li#tweet-' + (j-1)).hide()
					$('li#tweet-' + j).fadeIn(options.fadeDelay);
					if(j== tweetCount)
					{
						j = 0;
						window.setTimeout(function(){
							$('li#tweet-' + tweetCount).hide();
						},options.tweetDelay);
					}
				}
			},options.tweetDelay);
			
		}


/*************************************************************************
	Formatting
*************************************************************************/

		function linkUp(text)
		{	
			var exp = /(((http|https|ftp):\/\/)[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&#!;,]*[a-z0-9\/]{1})/gi;
			var rep = '<a href="$1" rel=\"nofollow\">$1</a>';
			text = text.replace(exp,rep);
			
			exp = /(^|[^\/])(www\.[\S]+(\b|$))/gi;
			rep = '<a href="http://$2">$2</a>'
			text = text.replace(exp,rep);
			
			exp = /(@)([a-z0-9_]+)/gi;
			rep = '<a href="http://twitter.com/#!/$2">$1$2</a>';
			text = text.replace(exp,rep);
			
			exp = /(#)([a-z0-9_\-]+)/gi;
			rep = '<a href="http://search.twitter.com/search?q=%23$2">$1$2</a>';
			text = text.replace(exp,rep);
			
			return text;
		}


/*************************************************************************
	End
*************************************************************************/
		return this;
	};
})(jQuery);