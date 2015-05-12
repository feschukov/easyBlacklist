<?php
/**
 * Get a list of Bans
 *
 * @package easyblacklist
 * @subpackage processors
 */
class eblBlacklistGetListProcessor extends modObjectGetListProcessor {
	public $classKey = 'eblBlacklist';
	public $defaultSortField = 'id';
	public $defaultSortDirection  = 'ASC';

	/**
	 * @param xPDOObject $object
	 *
	 * @return array
	 */
	public function prepareRow(xPDOObject $object) {
		$array = $object->toArray('', true);
		return $array;
	}

}

return 'eblBlacklistGetListProcessor';