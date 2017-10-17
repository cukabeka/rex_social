<?php

require_once $REX['INCLUDE_PATH'] . '/addons/rex_social/plugins/facebook/libs/Facebook/autoload.php';
require_once $REX['INCLUDE_PATH'] . '/addons/rex_social/plugins/facebook/libs/Facebook/FacebookRequest.php';

class RexSocialFacebook {
    
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
        
        $file = $REX['INCLUDE_PATH'].'/addons/rex_social/plugins/facebook/settings.conf';
        
        return json_decode(file_get_contents($file),true);
	}
	
	public static function saveSettings($aSettings)
	{
        global $REX;
        
        $file = $REX['INCLUDE_PATH'].'/addons/rex_social/plugins/facebook/settings.conf';
        
        if(file_put_contents($file,json_encode($aSettings)))
        {
            return true;
        }
        return false;
	}
    
    public static function getWidget()
	{
        self::$aSettings = self::getSettings();
        
        if($_GET['activateFacebook']=='1')
        {
            $_SESSION['rex-social']['facebook']['activate'] = true;
            rex_redirect('');
        }
        else if($_GET['activateFacebook']=='0')
        {
            $_SESSION['rex-social']['facebook']['activate'] = false;
            rex_redirect('');
        }
        
        if($_SESSION['rex-social']['facebook']['activate'])
        {
            $sWidget = '
                <div id="fb-root"></div>
                <script>(function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = "//connect.facebook.net/de_DE/sdk.js#xfbml=1&version=v2.5";
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, "script", "facebook-jssdk"));</script>

                <div class="fb-page" data-width="'.self::$aSettings['widget']['width'].'" data-href="https://www.facebook.com/'.self::$aSettings['widget']['user'].'" data-tabs="'.implode(',',self::$aSettings['widget']['tabs']).'" data-small-header="'.self::$aSettings['widget']['smallHeader'].'" data-adapt-container-width="'.self::$aSettings['widget']['adaptContainerWidth'].'" data-hide-cover="'.self::$aSettings['widget']['hideCover'].'" data-show-facepile="'.self::$aSettings['widget']['showFacepile'].'" data-hide-cta="'.self::$aSettings['widget']['hideCTA'].'"></div>
            ';
        }
        else
        {
            $sWidget = '
                <div class="fb-layer">
                    <div class="fb-layer-wrapper">
                        <div class="fb-layer-logo"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAF4AAABeCAYAAACq0qNuAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NzlFQzI0NTAwN0NBMTFFNjk0MjBGQzVEQzI3RTU0QjgiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NzlFQzI0NTEwN0NBMTFFNjk0MjBGQzVEQzI3RTU0QjgiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3OUVDMjQ0RTA3Q0ExMUU2OTQyMEZDNURDMjdFNTRCOCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3OUVDMjQ0RjA3Q0ExMUU2OTQyMEZDNURDMjdFNTRCOCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PpEyjhcAAASXSURBVHja7N3Pb9NmGAfwJ3GcNE5RlkJj1CyowNpFLFlFLTQQv4QqdpmmMQk07R8Yl+XCpbfddw6XnUBw4MBpB9iOG0hUQosESg9BJRJVq2mpVoWwJV3tOJkfq4auJG1m1yZNvl/JrVq7Tvvp69fPayd5faVSibZJjhAnyXZaEegE3mw2gysrK+eMJVWpVEhVVWq1WqDcJj6fj4LBIMViMYrH40Vj+cHv96vt/gG+LS3ebOHlcvl8sVjM6LpOjUYDojYSCARIEARKpVIFWZZ/3XoEbIbPcYteWFj4dmlpiRgdcR7GTyaTNDExcZ2PCAvfv3kjoO9+2JJN2Xbz9y34HHcvQHcXn42t7tyE5xOp1acj7uGzMVtb8DmuXoDuDT5bs7nZ4rlkRPXiftiYrV93NVynI97EsjbheXCEeBPL2oTHiNS7WNZ+ULybAB7wgEcAD3gE8P2RAAiIogfHKZkYuy/HR/KxfdLyUHCo7BeFmkDm9ZUIX9gyPhuLFtE0NaL9o8XW1Lq8/tcf8uPHz75QAd999n+gzJ3IHL2ZGB3Ji2+O+/z/2okeVYoG/J+A3zlScqrw6YVPrh6UzHuheUc7azZJQ1ezcz6auZw9O3FgzjE4+viu2zmd/vKrixk5VOkF9AGBD9Lpy1+fzxwQa72CPhDlZOLMpau9ht7/8JGpwkx6JN9r6H0P//HM9DWpB9H7G/69qYIyFurZe5p9C39UOfZ9yO3W7uCJGX1a1Uh0JBEt7sKOFP6grdcjTRKC/ARUQRC0jSeiEhlDXhHwmxI6VJYlpztZVxafPrryKP9stqq+XaJKUpjCYaJVwL9J+HDy7rCjbqaq/HL7zr1ijeT261Wq13lBH/+fiKKzk2pl/mGmMzpOri5lnZ4Xlr9BVeN1WjXl1RqlAO95g6/TikoxwHsdAQMo29GcPCNRb9i+udHX5aQYfZ+Oje8v8L3Q9ls0KChLy7YfYChB0+kPf3xJYqbzUSHUqLocmX+xemRg4MOHp787dXLsPrl2OUDMp89cuNTNACtw+8aNJzXKDEZXozdF6o0rjnkhQH+jj38XbYBoGPB7LIAHPOARwAMeGfSRq9bS+SXpSjd1ts2HULptswLZr+P3HPza/E+zt5ais6Kmtb+m0mhQ7Phn1z4/LtuD136ne3d+/m11BxrRGMatVQeoxXPq1e3/4kBds39ZV2/SK76tR+6+6Bp9/NbgsjCqGgTwgEcAD3gE8IBHAA94BPCABzwCeMAjgAc8AnjAI4AHPAJ4wCOABzzgEcADHgE84BHn8BvzjCIexLI24Xm6Y8SbWNYmPM8xjXgTy9qE54m9eY5pxN2wMVtb8Fnjiwc8tzTibtiYrdncbPH8zqE8mzrw3UVnY+tdWq1yMstT2PNs6sB3B51t2ZjazVLPU9gD3x10tu00gMpyjTk5OXk9nU4XQqEQ4YTr7ETKhmzJphv1e/b1+i3bmyuMQyI3Ojo6xxN78xzTPN0xz7yLCXd3Hhxxnc4lI1cvfCLd6NOzb21bKpW221duTw7HI/EH4/LwXVs/rNeUxcXyFZ0osgu/SrbTin8FGAAVuJUqQmmjwgAAAABJRU5ErkJggg=="/></div>
                        <div class="fb-layer-text">'.rex_a79_textile(self::$aSettings['widget']['activateText']).'</div>
                        <a class="fb-layer-button" rel="nofollow" href="'.rex_getURL('','',array('activateFacebook'=>1)).'">'.self::$aSettings['widget']['buttonText'].'</a>
                    </div>
                </div>';
        }
        
        return $sWidget;
	}
    
    public static function initFacebook() {
        
        self::$aSettings = self::getSettings();
        
        if(isset(self::$aSettings['api']['app']['id']) && self::$aSettings['api']['app']['id'] != '')
        {
            $fb = new Facebook\Facebook([
                'app_id' => self::$aSettings['api']['app']['id'],
                'app_secret' => self::$aSettings['api']['app']['secret'],
                'default_graph_version' => 'v2.6',
                'persistent_data_handler'=>'session'
            ]);
            return $fb;
        }
        return false;
    }
    
    public static function getAccessToken() {
        
        $fb = self::initFacebook();
        
        if(is_object($fb))
        {
            $helper = $fb->getRedirectLoginHelper();
            $_SESSION['FBRLH_state']=$_GET['state'];
            
            try {
              $accessToken = $helper->getAccessToken();
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              // When Graph returns an error
              echo 'Graph returned an error: ' . $e->getMessage();
              return false;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
              // When validation fails or other local issues
              echo 'Facebook SDK returned an error: ' . $e->getMessage();
              return false;
            }

            if (isset($accessToken)) {
              
                // OAuth 2.0 client handler
                $oAuth2Client = $fb->getOAuth2Client();

                // Exchanges a short-lived access token for a long-lived one
                $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                
                return $longLivedAccessToken->getValue();
            }
        }
        
        return false;
    }
    
    public static function getLoginUrl() {
        
        $fb = self::initFacebook();
        
        if(is_object($fb))
        {
            $helper = $fb->getRedirectLoginHelper();
            $permissions = ['user_posts'];
            return $helper->getLoginUrl('http://'.$_SERVER['HTTP_HOST'].'/redaxo/index.php?page=rex_social&subpage=facebook&setAccessToken=true', $permissions);
        }
        
        return false;
    }
    
    public static function getFeed() {
        
        $fb = self::initFacebook();
        
        $result = '';
        
        if(is_object($fb))
        {
            $fb->setDefaultAccessToken(self::$aSettings['api']['access_token']);

            try {
                // Requires the "read_stream" permission
                $response = $fb->get('/me/feed?fields=id,message,created_time,full_picture,from,permalink_url');
                $result = json_decode($response->getGraphEdge(),true);
                self::saveCache($result);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                $result = self::loadCache();
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                $result = self::loadCache();
            }
        }
        
        return $result;
    }
    
    public static function getPage() {
        
        $fb = self::initFacebook();
        
        $result = '';
        
        if(is_object($fb))
        {
            $fb->setDefaultAccessToken(self::$aSettings['api']['access_token']);

            try {
                // Requires the "read_stream" permission
                $response = $fb->get('/'.self::$aSettings['api']['page'].'/feed?fields=id,message,created_time,full_picture,from,permalink_url');
                $result = json_decode($response->getGraphEdge(),true);
                self::saveCache($result);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                $result = self::loadCache();
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                $result = self::loadCache();
            }
        }
        
        return $result;
    }
    
    public static function getProfilePicture() {
        
        $fb = self::initFacebook();
        
        if(is_object($fb))
        {
            $fb->setDefaultAccessToken(self::$aSettings['api']['access_token']);
            $response = $fb->get('/'.self::$aSettings['api']['page'].'/picture?redirect=false');
            return json_decode($response->getGraphObject(),true);
        }
        
        return false;
    }
    
    public static function getTokenData($sToken) {
        
        $fb = self::initFacebook();

        if(is_object($fb) && $sToken != '')
        {
            $fb->setDefaultAccessToken($sToken);

            try {          
                $oRequest = $fb->get('/debug_token?input_token='.$sToken);
                $oResponse = json_decode($oRequest->getGraphObject(),true);
              
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              return false;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
              return false;
            }

            return $oResponse;
        }
        
        return false;
    }
    
    private static function loadCache() {
        global $REX;
        
        $file = $REX['INCLUDE_PATH'].'/addons/rex_social/plugins/facebook/cache.json';
        
        return json_decode(file_get_contents($file),true);
    }
    
    private static function saveCache($sData) {
        global $REX;
        
        $file = $REX['INCLUDE_PATH'].'/addons/rex_social/plugins/facebook/cache.json';
        
        if(file_put_contents($file,json_encode($sData)))
        {
            return true;
        }
        return false;
    }
}
  
?>