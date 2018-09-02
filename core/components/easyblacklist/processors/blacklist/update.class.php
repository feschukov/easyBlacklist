<?php

class eblBlacklistUpdateProcessor extends modObjectUpdateProcessor {
    public $objectType = 'ebl_items_err';
    public $classKey = 'eblBlacklist';
    public $languageTopics = array('easyblacklist:default');
    public $permission = 'edit_document';

    /**
     * @return bool
     */
    public function beforeSet() {
        $id = (int)($this->getProperty('id'));
        $ip = trim($this->getProperty('ip'));
        if (empty($ip)) {
            $this->modx->error->addField('ip', $this->modx->lexicon('field_required'));
        }
        elseif ($this->modx->getCount($this->classKey, array('ip' => $ip, 'id:!=' =>$id ))) {
            $this->modx->error->addField('ip', $this->modx->lexicon('ebl_items_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'eblBlacklistUpdateProcessor';