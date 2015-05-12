<?php

if ($object->xpdo) {
	/* @var modX $modx */
	$modx =& $object->xpdo;

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
		case xPDOTransport::ACTION_UPGRADE:

            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('ebl_core_path',null,$modx->getOption('core_path').'components/easyblacklist/').'model/';
            $modx->addPackage('easyblacklist',$modelPath);
            $m = $modx->getManager();
            $m->createObjectContainer('eblBlacklist');

			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;