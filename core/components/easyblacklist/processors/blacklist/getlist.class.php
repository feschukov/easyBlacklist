<?php
/**
 * Get a list of Bans
 *
 * @package easyblacklist
 * @subpackage processors
 */
class eblBlacklistGetListProcessor extends modObjectGetListProcessor {
    public $objectType = 'ebl_items_err';
    public $classKey = 'eblBlacklist';
    public $defaultSortField = 'id';
    public $defaultSortDirection  = 'DESC';
    public $languageTopics = array('easyblacklist:default');

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->innerJoin('modUser','User');
        $c->select('eblBlacklist.*, User.username');

        return $c;
    }

    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $array = $object->toArray();
        $array['createdon'] = date('d.m.Y', strtotime($array['createdon'])) . ' <small>' . date('H:i', strtotime($array['createdon'])) . '</small>';
        $array['actions'] = array();
        // Edit
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('ebl_blacklist_update'),
            //'multiple' => $this->modx->lexicon('fullcalendar_items_update'),
            'action' => 'updateItem',
            'button' => true,
            'menu' => true,
        );
        if (!$array['active']) {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('ebl_blacklist_item_enable'),
                'multiple' => $this->modx->lexicon('ebl_blacklist_items_enable'),
                'action' => 'enableItem',
                'button' => true,
                'menu' => true,
            );
        }
        else {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-power-off action-red',
                'title' => $this->modx->lexicon('ebl_blacklist_item_disable'),
                'multiple' => $this->modx->lexicon('ebl_blacklist_items_disable'),
                'action' => 'disableItem',
                'button' => true,
                'menu' => true,
            );
        }
        // Remove
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('ebl_blacklist_remove'),
            'multiple' => $this->modx->lexicon('ebl_blacklist_remove_selected'),
            'action' => 'removeItem',
            'button' => true,
            'menu' => true,
        );

        return $array;
    }

}

return 'eblBlacklistGetListProcessor';