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
$boxes = $modx->getCount('eblBlacklist', $query);
if ( $boxes > 0 ) {
	$box = $modx->getObject('eblBlacklist', $query);
	$bid = (int) $modx->getOption('ebl_blockpage', $config, 0);
    if (!is_object($modx->resource)) {
        $modx->resource = $modx->request->getResource($modx->resourceMethod, $bid);
    }
    $modx->resource->cacheable = false;
	$modx->setPlaceholder('reason', $box->reason);
}