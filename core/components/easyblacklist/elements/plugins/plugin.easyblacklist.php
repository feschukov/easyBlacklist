<?php
if ($modx->event->name != 'OnWebPageInit') return false;

$easyBlacklist = $modx->getService('easyblacklist','easyBlacklist',$modx->getOption('ebl_core_path',null,$modx->getOption('core_path').'components/easyblacklist/').'model/easyblacklist/',$scriptProperties);
if (!($easyBlacklist instanceof easyBlacklist)) return false;

if (empty($_SERVER['HTTP_CLIENT_IP'])==FALSE) //"расшаренный"
  $ip=$_SERVER['HTTP_CLIENT_IP'];
elseif (empty($_SERVER['HTTP_X_FORWARDED_FOR'])==FALSE) //если прокси
  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
else
  $ip=$_SERVER['REMOTE_ADDR'];
  
$query = $modx->newQuery('eblBlacklist');
$query->where(array(
	'ip' => $ip
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
