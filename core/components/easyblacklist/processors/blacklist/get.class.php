<?php

class eblBlacklistGetProcessor extends modObjectGetProcessor {
	public $objectType = 'eblBlacklist';
	public $classKey = 'eblBlacklist';
	public $languageTopics = array('easyblacklist:default');

	/** {inheritDoc} */
	public function cleanup() {
		$array = $this->object->toArray('', true);
		return $this->success('', $array);
	}

}

return 'eblBlacklistGetProcessor';
