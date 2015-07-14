<?php

class Recolize_RecommendationEngine_Model_Convert_Parser_Product extends Mage_Catalog_Model_Convert_Parser_Product
{
    /**
     * Extend original constructor to support exporting the entity_id in our Dataflow export.
     *
     * Therefore we have to remove the entity_id from the system fields array.
     */
    public function __construct()
    {
        parent::__construct();

        $entityIdKey = array_search('entity_id', $this->_systemFields);
        if ($entityIdKey !== false) {
            unset($this->_systemFields[$entityIdKey]);
        }
    }
}