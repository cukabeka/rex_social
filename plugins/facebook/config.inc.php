<?php

$parent = 'rex_social';
$page = 'facebook';

$REX['ADDON']['version'][$page] = '1.0';
$REX['ADDON']['author'][$page] = 'Peter Thiel';
$REX['ADDON']['supportpage'][$page] = 'forum.redaxo.de';
$REX['EXTPERM'][] = $parent.'['.$page.']';

require_once $REX['INCLUDE_PATH'].'/addons/'.$parent.'/plugins/'.$page.'/'.'classes/RexSocialFacebook.php';

if(!class_exists('RexSocialFacebook')) {$rexSocialFacebook = new RexSocialFacebook();}

?>