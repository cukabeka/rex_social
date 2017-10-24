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
    
    if(RexSocialInstagram::saveSettings($aSettings))
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
    $aSettings = RexSocialInstagram::getSettings(); 
}

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
		<h3>Einbindung (Daten)</h3>
        <i>Beispiel:</i><br>
        <pre>
            RexSocialInstagram::getMedias();
        </pre>
		<p class="rex-tx1">
			Alle verfügbaren Properties können unter folgender URL eingesehen werden: https://github.com/postaddictme/instagram-php-scraper
		</p>
		<h3>Einbindung (HTML)</h3>
        <i>Beispiel:</i><br>
        <pre>
            RexSocialInstagram::getSnippet();
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
						<label for="settings[username]">Benutzer</label>
						<input type="text" name="settings[username]" value="'.$aSettings['username'].'"/>
					  </div>
					</div>
				  
					<div class="rex-form-row">
					  <div class="rex-form-col-a">
						<label for="settings[mediaCount]">Anzahl Medien</label>
						<input type="text" name="settings[mediaCount]" value="'.$aSettings['mediaCount'].'"/>
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