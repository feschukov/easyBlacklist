<?php

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('ebl_core_path', null, $modx->getOption('core_path') . 'components/easyblacklist/');
require_once $corePath . 'model/easyblacklist/easyblacklist.class.php';
$modx->easyBlacklist = new easyBlacklist($modx);

$modx->lexicon->load(array('easyblacklist:default'));

/* handle request */
$path = $modx->getOption('processorsPath', $modx->easyBlacklist->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));