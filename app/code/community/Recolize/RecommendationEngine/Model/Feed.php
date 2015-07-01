<?php

class Recolize_RecommendationEngine_Model_Feed extends Mage_Core_Model_Abstract
{
    /**
     * The file name prefix for the export file.
     *
     * @var string
     */
    const EXPORT_FILE_NAME_PREFIX = 'product-export-';

    /**
     * Generate the Recolize product feed.
     *
     * This method is called by cron.
     *
     * @return Recolize_RecommendationEngine_Model_Feed
     */
    public function generate()
    {
        if ($this->isEnabled() === false) {
            return $this;
        }

        $this->setIsSuccessful(false);

        try {
            /** @var $model Mage_ImportExport_Model_Export */
            $model = Mage::getModel('importexport/export')
                ->setEntity(Mage_Catalog_Model_Product::ENTITY)
                ->setFileFormat('csv')
                ->setExportFilter(array())
                ->setSkipAttr($this->getExcludedAttributeIds());

            $writtenBytes = @file_put_contents($this->getExportFilePath(), $model->export());
            if ($writtenBytes !== false) {
                $this->setIsSuccessful(true);
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $this;
    }

    /**
     * Checks whether the extension is enabled and the product export feature is enabled or not.
     *
     * @return boolean
     */
    private function isEnabled()
    {
        return Mage::getStoreConfigFlag('recolize_recommendation_engine/general/enable_extension')
            && Mage::getStoreConfigFlag('recolize_recommendation_engine/product_feed/enable_export');
    }

    /**
     * Returns the name of the export file path including directory and file name.
     *
     * @return string
     */
    private function getExportFilePath()
    {
        $exportDirectory = Mage::getBaseDir() . DS . Mage::getStoreConfig('recolize_recommendation_engine/product_feed/file_path');
        $exportFilename = self::EXPORT_FILE_NAME_PREFIX . md5(Mage::getBaseDir()) . '.csv';

        $exportFilePath = $exportDirectory . DS . $exportFilename;
        if (is_writable($exportDirectory) === false) {
            Mage::throwException('Recolize Product Feed file path is not writable. Please check file permissions for folder ' . $exportDirectory . '.');
        }

        return $exportFilePath;
    }

    /**
     * Return the configured ids of the attributes that should be excluded from the export.
     *
     * @return array
     */
    private function getExcludedAttributeIds()
    {
        $configuredAttributesToExclude = Mage::getStoreConfig('recolize_recommendation_engine/product_feed/exclude_product_attributes');
        if (empty($configuredAttributesToExclude) === true) {
            return array();
        }
        $configuredAttributesToExclude = explode(',', $configuredAttributesToExclude);

        /** @var Mage_Eav_Model_Resource_Entity_Attribute_Collection $attributeCollection */
        $attributeCollection = Mage::getModel('eav/entity_attribute')->getCollection()
            ->addFieldToSelect('attribute_id')
            ->addFieldToFilter('attribute_code', array('in' => $configuredAttributesToExclude));

        return $attributeCollection->getAllIds();
    }
}