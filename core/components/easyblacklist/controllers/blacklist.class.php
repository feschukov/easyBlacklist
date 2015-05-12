<?php
require_once dirname(dirname(__FILE__)) . '/index.class.php';

class ControllersBlacklistManagerController extends easyBlacklistMainController {
	public static function getDefaultController() {
		return 'blacklist';
	}
}

class EasyblacklistBlacklistManagerController extends easyBlacklistMainController {

	public function getPageTitle() {
		return $this->modx->lexicon('ebl_blacklist');
	}
	
	public function getLanguageTopics() {
		return array('easyblacklist:default');
	}

	public function loadCustomCssJs() {
		$this->addJavascript($this->easyBlacklist->config['jsUrl'] . 'blacklist.grid.js');
		$this->addJavascript($this->easyBlacklist->config['jsUrl'] . 'blacklist.panel.js');
		$this->addHtml(str_replace('			', '', '
			<script type="text/javascript">
				Ext.onReady(function() {
					MODx.load({ xtype: "ebl-page-blacklist"});
				});
			</script>'
		));
		$this->modx->invokeEvent('msOnManagerCustomCssJs', array('controller' => &$this, 'page' => 'blacklist'));
	}

	public function getTemplateFile() {
		return $this->easyBlacklist->config['templatesPath'] . 'blacklist.tpl';
	}

}

// MODX 2.3
class ControllersMgrBlacklistManagerController extends ControllersBlacklistManagerController {
	public static function getDefaultController() {
		return 'blacklist';
	}
}

class EblMgrBlacklistManagerController extends EasyblacklistBlacklistManagerController {
}