<?php
/**
* Rex Social
*
* @author Peter Thiel
*
*/

// GET PARAMS
////////////////////////////////////////////////////////////////////////////////
$page   = rex_request('page', 'string');
$subpage  = rex_request('subpage', 'string') ? rex_request('subpage', 'string') : 'overview';

// REX BACKEND LAYOUT TOP
//////////////////////////////////////////////////////////////////////////////
require $REX['INCLUDE_PATH'] . '/layout/top.php';

// TITLE & SUBPAGE NAVIGATION
//////////////////////////////////////////////////////////////////////////////
rex_title($REX['ADDON']['name'][$page].' <span class="addonversion">'.$REX['ADDON']['version'][$page].'</span>', $REX['ADDON'][$page]['SUBPAGES']);

// INCLUDE REQUESTED SUBPAGE
//////////////////////////////////////////////////////////////////////////////

$sFile = $REX['INCLUDE_PATH'] . '/addons/'.$page.'/pages/'.$subpage.'.inc.php';

if(!file_exists($sFile))
{
    $sFile = $REX['INCLUDE_PATH'] . '/addons/'.$page.'/plugins/'.$subpage.'/pages/index.inc.php'; 
}

require $sFile;

// REX BACKEND LAYOUT BOTTOM
//////////////////////////////////////////////////////////////////////////////
require $REX['INCLUDE_PATH'] . '/layout/bottom.php';