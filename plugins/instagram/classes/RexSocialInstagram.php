<?php

require_once $REX['INCLUDE_PATH'] . '/addons/rex_social/plugins/instagram/libs/InstagramScraper.php';

use InstagramScraper\Instagram;

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
        
        $sWidget = 'instagram widget ';
        
        return $sWidget;
	}
	
	public static function getMedias()
	{
        self::$aSettings = self::getSettings();
		
		return Instagram::getMedias(self::$aSettings['username'],100);
	}
	
	public static function getSnippet()
	{
        $aMedias = self::getMedias();
		if(!empty($aMedias))
		{
			$sSnippet = '';
			foreach($aMedias as $oMedia)
			{
				$sSnippet .= '
					<div class="entry">
						<div class="header">
							<div class="avatar"><a target="_blank" href="https://www.instagram.com/'.$oMedia->getCaption()['from']['username'].'"><img src="'.$oMedia->getCaption()['from']['profile_picture'].'"></a></div>
							<div class="author"><a target="_blank" href="https://www.instagram.com/'.$oMedia->getCaption()['from']['username'].'">'.$oMedia->getCaption()['from']['full_name'].'</a></div>
							<div class="date"><a target="_blank" href="'.$oMedia->getLink().'">am '.date('d.m.Y',$oMedia->getCreatedTime()).' um '.date('h:i',$oMedia->getCreatedTime()).'</a></div>
						</div>
						<div class="message">'.$oMedia->getCaption()['text'].'</div>
						<div class="picture"><img src="'.$oMedia->getImageThumbnailUrl().'"></div>
						<div class="functions">
							<a target="_blank" href="'.$oMedia->getLink().'">Auf Instagram ansehen</a>
						</div>
					</div>
				';
			}
			
			return $sSnippet;
		}
		
		return false;
	}
}
  
?>