<?php
if ($modx->event->name != 'OnWebPageInit') return false;

$easyBlacklist = $modx->getService('easyblacklist','easyBlacklist',$modx->getOption('ebl_core_path',null,$modx->getOption('core_path').'components/easyblacklist/').'model/easyblacklist/',$scriptProperties);
if (!($easyBlacklist instanceof easyBlacklist)) return false;

$query = $modx->newQuery('eblBlacklist');
$query->where(array(
	'ip' => $_SERVER['REMOTE_ADDR']
	,'active' => 1
));
$query->limit(1);
$boxes = $modx->getCollection('eblBlacklist', $query);
if ( count($boxes) > 0 ) {
	die('Error 404!');
}