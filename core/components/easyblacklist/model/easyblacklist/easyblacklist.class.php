<?php
/**
 * The base class for easyBlacklist.
 */

class easyBlacklist {

    /* @var modX $modx */
    public $modx;
    public $namespace = 'easyblacklist';
    public $cache = null;
    public $config = array();

    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct (modX &$modx, array $config = array()) {
        $this->modx =& $modx;
        $this->namespace = $this->modx->getOption('namespace', $config, 'easyblacklist');
        $corePath = $this->modx->getOption('easyblacklist_core_path', $config, $this->modx->getOption('core_path') . 'components/easyblacklist/');
        $assetsUrl = $this->modx->getOption('easyblacklist_assets_url', $config, $this->modx->getOption('assets_url') . 'components/easyblacklist/');
        $connectorUrl = $assetsUrl . 'connector.php';
        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $connectorUrl,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'templatesPath' => $corePath . 'elements/templates/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'processorsPath' => $corePath . 'processors/'
        ), $config);
        $this->modx->addPackage('easyblacklist', $this->config['modelPath']);
        $this->modx->lexicon->load('easyblacklist:default');
    }

    public function increment(eblBlacklist $object)
    {
        $object->attempts = $object->attempts + 1;
        $object->save();
    }

    public function log()
    {
        $this->modx->log(modX::LOG_LEVEL_ERROR, '[easyBlackList] Request from ' . $_SERVER['REMOTE_ADDR'] . '.');
    }

}