<?php
if ($modx->context->key == 'mgr') return;

switch ($modx->event->name) {
    case 'OnWebPageInit':
        $blockPage = $modx->getOption('ebl_blockpage');
        if (is_numeric($blockPage) && $blockPage == $modx->resourceIdentifier) return;

        $easyBlacklist = $modx->getService('easyblacklist', 'easyBlacklist', $modx->getOption('ebl_core_path', null, $modx->getOption('core_path').'components/easyblacklist/').'model/easyblacklist/', $scriptProperties);
        if (!($easyBlacklist instanceof easyBlacklist)) return false;

        $ip = $_SERVER['REMOTE_ADDR'];
        /** @var eblBlacklist $object */
        if ($object = $modx->getObject('eblBlacklist', array('ip' => $ip, 'active' => 1))) {
            $easyBlacklist->increment($object);
            if ($modx->getOption('ebl_log_request', null, false)) {
                $easyBlacklist->log();
            };
            if (is_numeric($blockPage)) {
                $modx->resource = $modx->getObject('modResource', intval($blockPage));
                $modx->resource->cacheable = false;
                $modx->setPlaceholder('reason', $object->reason);
            } else {
                $modx->sendRedirect($blockPage);
            }
        }
        break;
}