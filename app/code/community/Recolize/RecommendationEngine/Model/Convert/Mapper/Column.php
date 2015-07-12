<?php

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
     * The image attributes where URL is rewritten.
     *
     * @var array
     */
    protected $_imageAttributes = array('image', 'small_image', 'thumbnail');

    /**
     * The name of the category ids attribute.
     *
     * @var string
     */
    protected $_categoryIdsAttribute = 'category_ids';

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
     * @return Recolize_RecommendationEngine_Model_DataFlow_Convert_Mapper_Column
     */
    public function map()
    {
        $batchModel  = $this->getBatchModel();
        $batchExport = $this->getBatchExportModel();

        $batchExportIds = $batchExport
            ->setBatchId($this->getBatchModel()->getId())
            ->getIdCollection();

        foreach ($batchExportIds as $batchExportId) {
            $batchExport->load($batchExportId);

            $row = $batchExport->getBatchData();
            // Apply attribute specific transformations
            foreach ($row as $attributeName => $attributeValue) {
                // Add full URL for image attributes.
                if (in_array($attributeName, $this->_imageAttributes) === true) {
                    $row[$attributeName] = Mage::getModel('catalog/product_media_config')->getMediaUrl($attributeValue);
                }

                // Add category names instead of ids.
                if ($attributeName === $this->_categoryIdsAttribute) {
                    $categoryNames = array();
                    $categoryIds = explode(',', $attributeValue);
                    foreach ($categoryIds as $categoryId) {
                        /** @var Mage_Catalog_Model_Category $category */
                        $category = Mage::getModel('catalog/category')->load($categoryId);
                        if (empty($category) === false) {
                            $categoryNames[] = $category->getName();
                        }
                    }

                    $row[$attributeName] = implode(', ', $categoryNames);
                }
            }

            $batchExport->setBatchData($row)
                ->setStatus(2)
                ->save();

            $batchModel->parseFieldList($batchExport->getBatchData());
        }

        return $this;
    }
}
