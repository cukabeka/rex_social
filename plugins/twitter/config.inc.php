<?php

$parent = 'rex_social';
$page = 'twitter';
$root = $REX['INCLUDE_PATH'].'/addons/'.$parent.'/plugins/'.$page.'/';

$REX['ADDON']['version'][$page] = '1.0';
$REX['ADDON']['author'][$page] = 'Peter Thiel';
$REX['ADDON']['supportpage'][$page] = 'forum.redaxo.de';
$REX['EXTPERM'][] = $parent.'['.$page.']';

require_once $root.'libs/TwitterAPIExchange.php';
require_once $root.'classes/RexSocialTwitter.php';

if(class_exists('RexSocialTwitter')) {$rexSocialTwitter = new RexSocialTwitter();}

?>