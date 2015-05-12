<?php

class eblBlacklistRemoveProcessor extends modProcessor {
	public $classKey = 'eblBlacklist';

	/** {inheritDoc} */
	public function process() {
		if (!$ids = explode(',', $this->getProperty('ids'))) {
			return $this->failure($this->modx->lexicon('ebl_items_err_ns'));
		}
		$items = $this->modx->getIterator($this->classKey, array('id:IN' => $ids));
		foreach ($items as $item) {
			$item->remove();
		}
		return $this->success();
	}

}

return 'eblBlacklistRemoveProcessor';