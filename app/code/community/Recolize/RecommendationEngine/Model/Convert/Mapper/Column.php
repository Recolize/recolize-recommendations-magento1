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
class Recolize_RecommendationEngine_Model_Convert_Mapper_Column extends Mage_Dataflow_Model_Convert_Mapper_Abstract
{
    /**
     * Dataflow batch model
     *
     * @var Mage_Dataflow_Model_Batch
     */
    protected $_batch;

    /**
     * Dataflow batch export model
     *
     * @var Mage_Dataflow_Model_Batch_Export
     */
    protected $_batchExport;

    /**
     * Dataflow batch import model
     *
     * @var Mage_Dataflow_Model_Batch_Import
     */
    protected $_batchImport;

    /**
     * The image attribute name.
     *
     * @var string
     */
    protected $_imageAttribute = 'image';

    /**
     * The name of the price attribute.
     *
     * @var string
     */
    protected $_priceAttribute = 'price';

    /**
     * The name of the special price attribute.
     *
     * @var string
     */
    protected $_specialPriceAttribute = 'special_price';

    /**
     * Retrieve Batch model singleton
     *
     * @return Mage_Dataflow_Model_Batch
     */
    public function getBatchModel()
    {
        if (empty($this->_batch) === true) {
            $this->_batch = Mage::getSingleton('dataflow/batch');
        }

        return $this->_batch;
    }

    /**
     * Retrieve Batch export model
     *
     * @return Mage_Dataflow_Model_Batch_Export
     */
    public function getBatchExportModel()
    {
        if (empty($this->_batchExport) === true) {
            $object = Mage::getModel('dataflow/batch_export');
            $this->_batchExport = Varien_Object_Cache::singleton()->save($object);
        }

        return Varien_Object_Cache::singleton()->load($this->_batchExport);
    }

    /**
     * This method does some transformations of certain fields, e.g.
     * - add full URLs for product images
     * - replace category ids with category names
     *
     * @see Mage_Dataflow_Model_Convert_Mapper_Column::map()
     *
     * @return Recolize_RecommendationEngine_Model_Convert_Mapper_Column
     */
    public function map()
    {
        $batchModel = $this->getBatchModel();
        $batchExport = $this->getBatchExportModel();

        $batchExportIds = $batchExport
            ->setBatchId($this->getBatchModel()->getId())
            ->getIdCollection();

        foreach ($batchExportIds as $batchExportId) {
            $batchExport->load($batchExportId);

            $row = $batchExport->getBatchData();
            $storeCode = $row['store'];

            $appEmulation = Mage::getSingleton('core/app_emulation');

            // Apply attribute specific transformations
            foreach ($row as $attributeName => $attributeValue) {
                if ($attributeValue === null) {
                    continue;
                }

                // Generate smaller image and add full URL to export.
                if ($attributeName === $this->_imageAttribute) {
                    $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($row['store_id']);
                    $row[$this->_imageAttribute] = (string) Mage::helper('catalog/image')->init(Mage::getSingleton('catalog/product'), $attributeName, $attributeValue)
                        ->constrainOnly(true)
                        ->keepAspectRatio(true)
                        ->keepFrame(false)
                        ->resize(500);

                    $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
                }

                // Always export prices with tax and use the special price instead of the price, if available.
                if ($attributeName === $this->_priceAttribute) {
                    if ($row[$this->_specialPriceAttribute] !== null) {
                        $attributeValue = $row[$this->_specialPriceAttribute];
                        $row[$attributeName] = $attributeValue;
                    }

                    if ($this->_isRecalculatePriceWithTax($storeCode) === true) {
                        $product = Mage::getModel('catalog/product')->setStore($storeCode)->load($row['entity_id']);
                        $row[$attributeName] = Mage::helper('tax')->getPrice($product, $attributeValue);
                    }
                }
            }

            $batchExport->setBatchData($row)
                ->setStatus(2)
                ->save();

            $batchModel->parseFieldList($batchExport->getBatchData());
        }

        return $this;
    }

    /**
     * Check whether it is required to recalculate the product price including tax.
     *
     * @param string $storeCode
     *
     * @return boolean
     */
    protected function _isRecalculatePriceWithTax($storeCode)
    {
        $priceDisplayType = Mage::helper('tax')->getPriceDisplayType($storeCode);
        if ($priceDisplayType !== Mage_Tax_Model_Config::DISPLAY_TYPE_EXCLUDING_TAX && Mage::helper('tax')->priceIncludesTax($storeCode) === false) {
            return true;
        }

        return false;
    }
}