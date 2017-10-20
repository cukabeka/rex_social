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

// FORMULAR PARAMETER SPEICHERN
////////////////////////////////////////////////////////////////////////////////
if($func=='savesettings')
{
	$aSettings = (array) rex_post('settings', 'array', array());
    
    if(RexSocialTwitter::saveSettings($aSettings))
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
    $aSettings = RexSocialTwitter::getSettings(); 
}

$select_1 = new rex_select();
$select_1->setMultiple(TRUE);
$select_1->setName("settings[widget][chrome][]");
$select_1->setAttribute('class', 'rex-form-select');
$select_1->setStyle('width: 250px');
$select_1->addOptions(
    array(
        'noheader'=>'Kein Header',
        'nofooter'=>'Kein Footer',
        'noborders'=>'Kein Rand',
        'noscrollbar'=>'Keine Scrollbar',
        'transparent'=>'Transparent',
    )
);
$select_1->setSelected($aSettings['widget']['chrome']);

$select_2 = new rex_select();
$select_2->setName("settings[widget][theme]");
$select_2->setSize(1);
$select_2->setAttribute('class', 'rex-form-select');
$select_2->setStyle('width: 250px');
$select_2->addOptions(
    array(
        'light'=>'Hell',
        'dark'=>'Dunkel',
    )
);
$select_1->setSelected($aSettings['widget']['theme']);

echo '

<style type="text/css">

#rex-page-rex-social{}
#rex-page-rex-social textarea{
    width:575px;
    height:50px;
}

</style>

<div class="rex-addon-output">
    <h2 class="rex-hl2">Instagram</h2>
    <div class="rex-addon-content">
        <p class="rex-tx1">Instagram kann entweder per API oder Widget eingebunden werden.</p>
        <h3>Einbindung per API</h3>
        <i>Beispiel:</i><br>
        <pre>
            RexSocialInstagram::getItems();
        </pre>
        <p class="rex-tx1">
            Es wird eine App benötigt die unter <a href="https://apps.twitter.com">https://apps.twitter.com</a> erstellt werden kann. Folgende Daten der App müssen anschließend im unteren Bereich hinterlegt werden:
            <ul>
                <li>Consumer Key (API Key) und Consumer Secret (API Secret)</li>
                <li>Access Token und Access Token Secret</li>
            </ul>
       </p>
        <h3>Einbindung per Widget</h3>
        <i>Beispiel:</i><br>
        <pre>
            RexSocialInstagram::getWidget();
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
						<label for="settings[api][user]">Benutzer</label>
						<input type="text" name="settings[api][user]" value="'.$aSettings['api']['user'].'"/>
					  </div>
					</div>
                    
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[api][tokens][oauth_access_token]">Access-Token</label>
						<textarea name="settings[api][tokens][oauth_access_token]">'.$aSettings['api']['tokens']['oauth_access_token'].'</textarea>
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[api][tokens][oauth_access_token_secret]">Access-Token Secret</label>
						<textarea name="settings[api][tokens][oauth_access_token_secret]">'.$aSettings['api']['tokens']['oauth_access_token_secret'].'</textarea>
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[api][tokens][consumer_key]">Consumer-Key</label>
						<textarea name="settings[api][tokens][consumer_key]">'.$aSettings['api']['tokens']['consumer_key'].'</textarea>
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[api][tokens][consumer_secret]">Consumer-Token</label>
						<textarea name="settings[api][tokens][consumer_secret]">'.$aSettings['api']['tokens']['consumer_secret'].'</textarea>
					  </div>
					</div>

				  </div>
				</fieldset>
			
				<fieldset class="rex-form-col-1">
				  <legend>Einstellungen Widget</legend>
				  <div class="rex-form-wrapper">
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][user]">Benutzer</label>
						<input type="text" name="settings[widget][user]" value="'.$aSettings['widget']['user'].'"/>
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][chrome]">Erscheinungsbild</label>
						'.$select_1->get().'
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][theme]">Theme</label>
						'.$select_2->get().'
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[widget][tweetLimit]">Tweet-Limit</label>
						<input type="text" name="settings[widget][tweetLimit]" value="'.$aSettings['widget']['tweetLimit'].'"/>
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