<?php
/**
 * Disable an Item
 */
class eblBlacklistDisableProcessor extends modObjectProcessor {
    public $objectType = 'ebl_items_err';
    public $classKey = 'eblBlacklist';
    public $languageTopics = array('easyblacklist:default');
    public $permission = 'edit_document';


	/**
	 * @return array|string
	 */
	public function process() {
		$ids = $this->modx->fromJSON($this->getProperty('ids'));
		if (empty($ids)) {
			return $this->failure($this->modx->lexicon('ebl_items_err_ns'));
		}

		foreach ($ids as $id) {
			/** @var eblBlacklist $object */
			if (!$object = $this->modx->getObject($this->classKey, $id)) {
				return $this->failure($this->modx->lexicon('ebl_items_err_nf'));
			}

			$object->set('active', false);
			$object->save();
		}

		return $this->success();
	}

}

return 'eblBlacklistDisableProcessor';
