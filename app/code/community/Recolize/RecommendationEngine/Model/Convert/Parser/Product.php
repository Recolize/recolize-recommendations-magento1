<?php
/**
 * Recolize GmbH
 *
 * @section LICENSE
 * This source file is subject to the GNU General Public License Version 3 (GPLv3).
 *
 * @category Recolize
 * @package Recolize_RecommendationEngine
 * @author Recolize GmbH <service@recolize.com>
 * @copyright 2015 Recolize GmbH (http://www.recolize.com)
 * @license http://opensource.org/licenses/GPL-3.0 GNU General Public License Version 3 (GPLv3).
 */
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