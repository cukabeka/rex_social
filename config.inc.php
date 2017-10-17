<?php
/**
* Rex Social
*
* @author Peter Thiel
*
*/

// ERROR_REPORTING
////////////////////////////////////////////////////////////////////////////////
/*ini_set('error_reporting', 'E_ALL');
ini_set('display_errors', 'On');*/

setLocale(LC_MONETARY, 'de_DE');
$page = 'rex_social';

// ADDON REX COMMONS
////////////////////////////////////////////////////////////////////////////////
$REX['ADDON']['rxid'][$page] = 'rexsocial';
$REX['ADDON']['page'][$page] = $page;
$REX['ADDON']['name'][$page] = "RexSocial";
$Revision = '';
$REX['ADDON'][$page]['VERSION'] = array
(
    'VERSION'      => 1,
    'MINORVERSION' => 0,
    'SUBVERSION'   => 0,
);
$REX['ADDON']['version'][$page]     = implode('.', $REX['ADDON'][$page]['VERSION']);
$REX['ADDON']['author'][$page]      = 'rexdev.de';
$REX['ADDON']['supportpage'][$page] = 'forum.redaxo.de';
$REX['ADDON']['perm'][$page]        = $page.'[]';
$REX['PERM'][]                       = $page.'[]';

// SUBPAGES
//////////////////////////////////////////////////////////////////////////////

$aPages = array(
    array('overview','Übersicht')
);

$aPlugins = OOPlugin::getAvailablePlugins($page);

foreach($aPlugins as $oPlugin) {
    $aPages[] = array($oPlugin,ucfirst($oPlugin));
}

$REX['ADDON'][$page]['SUBPAGES'] = $aPages;