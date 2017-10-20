<?php

class RexSocialInstagram {
    
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
        
        $file = $REX['INCLUDE_PATH'].'/addons/rex_social/plugins/instagram/settings.conf';
        
        return json_decode(file_get_contents($file),true);
	}
	
	public static function saveSettings($aSettings)
	{
        global $REX;
        
        $file = $REX['INCLUDE_PATH'].'/addons/rex_social/plugins/instagram/settings.conf';
        
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
            <a class="instagram-timeline" href="https://instagram.com/'.self::$aSettings['widget']['user'].'" data-widget-id="705165645072228352" data-chrome="'.implode(' ',self::$aSettings['widget']['chrome']).'" data-tweet-limit="'.self::$aSettings['widget']['tweetLimit'].'" data-theme="'.self::$aSettings['widget']['theme'].'">Tweets von @'.self::$aSettings['widget']['user'].'</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.instagram.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","instagram-wjs");</script>
        ';
        
        return $sWidget;
	}
	
	public static function getItems()
	{
        self::$aSettings = self::getSettings();
        
        $url = 'https://api.instagram.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name='.self::$aSettings['api']['user'].'';
        $requestMethod = 'GET';
        
		$instagram = new InstagramAPIExchange(self::$aSettings['api']['tokens']);
        $sTweets = $instagram->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();
            
        return json_decode($sTweets, true);
	}
}
  
?>