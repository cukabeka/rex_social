<?php

class RexSocialTwitter {
    
	private static $aSettings;
	
	function __construct()
	{
		self::init();
	}
	
	private function init()
	{
        global $REX;
	}
	
	public static function getSettings()
	{
        global $REX;
        
        $file = $REX['INCLUDE_PATH'].'/addons/rex_social/plugins/twitter/settings.conf';
        
        return json_decode(file_get_contents($file),true);
	}
	
	public static function saveSettings($aSettings)
	{
        global $REX;
        
        $file = $REX['INCLUDE_PATH'].'/addons/rex_social/plugins/twitter/settings.conf';
        
        if(file_put_contents($file,json_encode($aSettings)))
        {
            return true;
        }
        return false;
	}
    
    public static function getWidget()
	{
        self::$aSettings = self::getSettings();
        
        $sWidget = '
            <a class="twitter-timeline" href="https://twitter.com/'.self::$aSettings['widget']['user'].'" data-widget-id="705165645072228352" data-chrome="'.implode(' ',self::$aSettings['widget']['chrome']).'" data-tweet-limit="'.self::$aSettings['widget']['tweetLimit'].'" data-theme="'.self::$aSettings['widget']['theme'].'">Tweets von @'.self::$aSettings['widget']['user'].'</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        ';
        
        return $sWidget;
	}
	
	public static function getTweets()
	{
        self::$aSettings = self::getSettings();
        
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name='.self::$aSettings['api']['user'].'';
        $requestMethod = 'GET';
        
		$twitter = new TwitterAPIExchange(self::$aSettings['api']['tokens']);
        $sTweets = $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();
            
        return json_decode($sTweets, true);
	}
}
  
?>