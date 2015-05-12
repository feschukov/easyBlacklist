<?php

class eblBlacklistUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'eblBlacklist';
	public $classKey = 'eblBlacklist';
	public $languageTopics = array('easyblacklist:default');
	public $permission = 'edit_document';

	/**
	 * @return bool
	 */
	public function beforeSet() {
		$required = array('ip');
		foreach ($required as $tmp) {
			if (!$this->getProperty($tmp)) {
				$this->addFieldError($tmp, $this->modx->lexicon('field_required'));
			}
		}
		$active = $this->getProperty('active');
		$this->setProperty('active', !empty($active) && $active != 'false');
		return !$this->hasErrors();
	}

}

return 'eblBlacklistUpdateProcessor';