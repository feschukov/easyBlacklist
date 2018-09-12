<?php

abstract class easyBlacklistMainController extends modExtraManagerController {
    /** @var easyBlacklist $easyBlacklist */
    public $easyBlacklist;


    public function initialize() {
        require_once dirname(__FILE__) . '/model/easyblacklist/easyblacklist.class.php';
        $this->easyBlacklist = new easyBlacklist($this->modx);

        $this->addJavascript($this->easyBlacklist->config['jsUrl'] . 'easyblacklist.js');
        $this->addHtml(str_replace('		', '', '
		<script type="text/javascript">
			easyBlacklist.config = ' . $this->modx->toJSON($this->easyBlacklist->config) . ';
			easyBlacklist.config.connector_url = "' . $this->easyBlacklist->config['connectorUrl'] . '";
		</script>'));

        parent::initialize();
    }

    public function getLanguageTopics() {
        return array('easyblacklist:default');
    }

    public function checkPermissions() {
        return true;
    }
}
/**
 * @package quip
 * @subpackage controllers
 */
class IndexManagerController extends easyBlacklistMainController {
    public static function getDefaultController() {
        return 'blacklist';
    }
}
