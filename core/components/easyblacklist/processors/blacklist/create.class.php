<?php

class eblBlacklistCreateProcessor extends modObjectCreateProcessor {
    public $objectType = 'ebl_items_err';
    public $classKey = 'eblBlacklist';
    public $languageTopics = array('easyblacklist:default');
    public $permission = 'new_document';

    /**
     * @return bool
     */
    public function beforeSet() {
        $ip = trim($this->getProperty('ip'));
        if (empty($ip)) {
            $this->modx->error->addField('ip', $this->modx->lexicon('field_required'));
        }
        elseif ($this->modx->getCount($this->classKey, array('ip' => $ip))) {
            $this->modx->error->addField('ip', $this->modx->lexicon('ebl_items_err_ae'));
        }
        if ($this->hasErrors()) {
            return false;
        }
        $this->setProperty('createdon', date('Y-m-d H:i:s'));
        $this->setProperty('uid', $this->modx->user->id);

        return parent::beforeSet();
    }

}

return 'eblBlacklistCreateProcessor';