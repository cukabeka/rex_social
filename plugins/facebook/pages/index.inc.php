<?php
/**
* Rex Social
*
* @author Peter Thiel
*
*/

// ADDON PARAMETER AUS URL HOLEN
////////////////////////////////////////////////////////////////////////////////
$page    = rex_request('page'   , 'string');
$subpage   = rex_request('subpage', 'string');
$minorpage = rex_request('minorpage', 'string');
$func      = rex_request('func'   , 'string');

$aSettings = array();

global $REX;

// FORMULAR PARAMETER SPEICHERN
////////////////////////////////////////////////////////////////////////////////
if($func=='savesettings')
{
	$aSettings = (array) rex_post('settings', 'array', array());
    
    if(RexSocialFacebook::saveSettings($aSettings))
    {
        echo rex_info('Einstellungen wurden gespeichert.');
    }
    else
    {
        echo rex_warning('Beim speichern der Einstellungen ist ein Problem aufgetreten.');
    }
}
else
{
    $aSettings = RexSocialFacebook::getSettings(); 
}

// Access Token speichern
if(rex_request('setAccessToken','boolean'))
{
    $aSettings['api']['warningSend'] = false;
    $aSettings['api']['expiration'] = 0;
    $aSettings['api']['access_token'] = RexSocialFacebook::getAccessToken();
}

if($aSettings['api']['access_token'] == '')
{
   $aSettings['api']['expiration'] = 0; 
}
if($aSettings['api']['access_token'] != '' && $aSettings['api']['expiration'] == 0)
{
    $aFeedToken = RexSocialFacebook::getTokenData($aSettings['api']['access_token']);
    $aSettings['api']['expiration'] = strtotime($aFeedToken['expires_at']);
}
if(!$aSettings['api']['warningSend'] && $aSettings['api']['expiration'] > 0 && $aSettings['api']['expiration'] <= strtotime('+1 day', time()))
{
    $body = 'Bitte erneuen Sie den Facebook Access Token für das Addon RexSocial unter '.$REX['SERVER'].'redaxo/index.php?page=rex_social&subpage=facebook&setAccessToken=true';
    
    $mail = new rex_mailer();
    $mail->Subject = $REX['SERVERNAME'].' : Addon RexSocial - Facebook Access Token';
    $mail->Body    = $body;
    $mail->AltBody = $body;
    // $mail->AddAddress($REX['ERROR_EMAIL']);
    $mail->AddAddress('thiel.peter@googlemail.com');

    if($mail->Send())
    {
        $aSettings['api']['warningSend'] = true;
    }
}

RexSocialFacebook::saveSettings($aSettings);

// Output

$select_1 = new rex_select();
$select_1->setMultiple(TRUE);
$select_1->setName("settings[widget][tabs][]");
$select_1->setAttribute('class', 'rex-form-select');
$select_1->setStyle('width: 250px');
$select_1->addOptions(
    array(
        'timeline'=>'Timeline',
        'events'=>'Events',
        'messages'=>'Messages',
    )
);
$select_1->setSelected($aSettings['widget']['tabs']);

$select_2 = new rex_select();
$select_2->setName("settings[widget][smallHeader]");
$select_2->setSize(1);
$select_2->setAttribute('class', 'rex-form-select');
$select_2->setStyle('width: 250px');
$select_2->addOptions(
    array(
        'true'=>'Ja',
        'false'=>'Nein'
    )
);
$select_2->setSelected($aSettings['widget']['smallHeader']);

$select_3 = new rex_select();
$select_3->setName("settings[widget][adaptContainerWidth]");
$select_3->setSize(1);
$select_3->setAttribute('class', 'rex-form-select');
$select_3->setStyle('width: 250px');
$select_3->addOptions(
    array(
        'true'=>'Ja',
        'false'=>'Nein'
    )
);
$select_3->setSelected($aSettings['widget']['adaptContainerWidth']);

$select_4 = new rex_select();
$select_4->setName("settings[widget][hideCover]");
$select_4->setSize(1);
$select_4->setAttribute('class', 'rex-form-select');
$select_4->setStyle('width: 250px');
$select_4->addOptions(
    array(
        'true'=>'Ja',
        'false'=>'Nein'
    )
);
$select_4->setSelected($aSettings['widget']['hideCover']);

$select_5 = new rex_select();
$select_5->setName("settings[widget][showFacepile]");
$select_5->setSize(1);
$select_5->setAttribute('class', 'rex-form-select');
$select_5->setStyle('width: 250px');
$select_5->addOptions(
    array(
        'true'=>'Ja',
        'false'=>'Nein'
    )
);
$select_5->setSelected($aSettings['widget']['showFacepile']);

$select_6 = new rex_select();
$select_6->setName("settings[widget][hideCTA]");
$select_6->setSize(1);
$select_6->setAttribute('class', 'rex-form-select');
$select_6->setStyle('width: 250px');
$select_6->addOptions(
    array(
        'true'=>'Ja',
        'false'=>'Nein'
    )
);
$select_6->setSelected($aSettings['widget']['hideCTA']);

echo '

<style type="text/css">

#rex-page-rex-social{}
#rex-page-rex-social textarea{
    width:575px;
    height:50px;
}

</style>

<div class="rex-addon-output">
    <h2 class="rex-hl2">Facebook</h2>
    <div class="rex-addon-content">
        <p class="rex-tx1">Facebook kann entweder per API oder Widget eingebunden werden.</p>
        <h3>Einbindung per API</h3>
        <i>Beispiel:</i><br>
        <pre>
            RexSocialFacebook::getFeed();
            RexSocialFacebook::getPage();
        </pre>
        <h3>Einbindung per Widget</h3>
        <i>Beispiel:</i><br>
        <pre>
            RexSocialFacebook::getWidget();
        </pre>
        
    </div>
	<div class="rex-form">
		<form action="index.php" method="POST" id="settings">
			<input type="hidden" name="page" value="'.$page.'" />
			<input type="hidden" name="subpage" value="'.$subpage.'" />
			<input type="hidden" name="func" value="savesettings" />
			
				<fieldset class="rex-form-col-1">
				  <legend>Einstellungen API</legend>
				  <div class="rex-form-wrapper">
                  
                    <div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[api][app][id]">App-Id</label>
						<input type="text" name="settings[api][app][id]" value="'.$aSettings['api']['app']['id'].'"/>
					  </div>
					</div>
                  
                    <div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[api][app][secret]">App-Secret</label>
						<input type="text" name="settings[api][app][secret]" value="'.$aSettings['api']['app']['secret'].'"/>
					  </div>
					</div>
                  
                    <div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[api][page]">Page</label>
						<input type="text" name="settings[api][page]" value="'.$aSettings['api']['page'].'"/>
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[api][access_token]">Access-Token</label>
						<textarea name="settings[api][access_token]">'.$aSettings['api']['access_token'].'</textarea>
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
                        <input type="hidden" name="settings[api][expiration]" value="'.$aSettings['api']['expiration'].'"/>
                        <p class="rex-button">
                            <a href="' . RexSocialFacebook::getLoginUrl() . '">Access-Token erzeugen</a>
                            '.($aSettings['api']['expiration'] > 0 ? '<span>Token ist gültig bis: '.strftime('%d.%m.%Y %H:%M:%S',$aSettings['api']['expiration']).'</span>' : '').'
                        </p>
					  </div>
					</div>

				  </div>
				</fieldset>
			
				<fieldset class="rex-form-col-1">
				  <legend>Einstellungen Widget (Nähere Informationen zum Plugin unter: <a target="_blank" href="https://developers.facebook.com/docs/plugins/page-plugin">Facebook Page-Plugin</a>)</legend>
                  
				  <div class="rex-form-wrapper">
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][user]">Benutzer</label>
						<input type="text" name="settings[widget][user]" value="'.$aSettings['widget']['user'].'"/>
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][width]">Weite (180-500)</label>
						<input type="text" name="settings[widget][width]" value="'.$aSettings['widget']['width'].'"/>
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][tabs]">Tabs</label>
						'.$select_1->get().'
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][smallHeader]">Kleiner Header</label>
						'.$select_2->get().'
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][adaptContainerWidth]">Adaptive Weite</label>
						'.$select_3->get().'
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][hideCover]">Hintergrundbild verbergen</label>
						'.$select_4->get().'
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][showFacepile]">Zeige Freunde</label>
						'.$select_5->get().'
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][hideCTA]">Verberge Call-To-Action-Button</label>
						'.$select_6->get().'
					  </div>
					</div>
                    
                    <div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][activateText]">Aktivierungs-Text</label>
						<textarea class="rex-markitup" name="settings[widget][activateText]">'.$aSettings['widget']['activateText'].'</textarea>
					  </div>
					</div>
                    
                    <div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][buttonText]">Button-Text</label>
						<input type="text" name="settings[widget][buttonText]" value="'.$aSettings['widget']['buttonText'].'"/>
					  </div>
					</div>

				  </div>
				</fieldset>
				
				<div class="rex-form-row rex-form-element-v2">
				  <p class="rex-form-submit">
					<input class="rex-form-submit" type="submit" id="submit" name="submit" value="Einstellungen speichern" />
				  </p>
				</div>
			</form>
	</div>
</div>
';