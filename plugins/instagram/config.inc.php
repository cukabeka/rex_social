<?php

$parent = 'rex_social';
$page = 'instagram';
$root = $REX['INCLUDE_PATH'].'/addons/'.$parent.'/plugins/'.$page.'/';

$REX['ADDON']['version'][$page] = '1.0';
$REX['ADDON']['author'][$page] = 'cukabeka';
$REX['ADDON']['supportpage'][$page] = 'forum.redaxo.de';
$REX['EXTPERM'][] = $parent.'['.$page.']';

require_once $root.'classes/RexSocialInstagram.php';

if(class_exists('RexSocialInstagram')) {$rexSocialInstagram = new RexSocialInstagram();}

?>