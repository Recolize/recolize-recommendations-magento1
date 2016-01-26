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
class Recolize_RecommendationEngine_Model_Feed extends Mage_Core_Model_Abstract
{
    /**
     * The name prefix for the Recolize DataFlow profiles.
     *
     * This is used to select the appropriate profiles to run.
     *
     * @var string
     */
    const DATAFLOW_PROFILE_NAME_PREFIX = 'Recolize: Product Feed';

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

        $this->initialize();

        foreach ($this->getFeedProfileCollection() as $profileModel) {
            /** @var Mage_DataFlow_Model_Profile $profileModel */
            try {
                //The dataflow core module always uses getSingleton for the dataflow batch model instance.
                //Therefore we have to remove the existing instance with every profile run, because otherwise
                //the dataflow batch ids for the different profile runs keep the same, and the whole data of
                //all profile runs get aggregated with every next profile run (within one cron run).
                Mage::unregister('_singleton/dataflow/batch');
                $profileModel->run();
            } catch (Exception $exception) {
                Mage::logException($exception);
            }
        }

        return $this;
    }

    /**
     * Return the product export feed name.
     *
     * @param Mage_Core_Model_Store $store The store to get the file name for.
     *
     * @return string
     * @throws Mage_Core_Exception
     */
    public function getFeedFilename(Mage_Core_Model_Store $store)
    {
        return sprintf('product-export-%s.csv', md5($store->getId() . '#' . $store->getName() . '#' . $store->getCode()) . '-' . $store->getCode());
    }

    /**
     * Return a unique name for the DataFlow profile for the given store.
     *
     * @param Mage_Core_Model_Store $store
     *
     * @return string
     */
    public function getFeedProfileName(Mage_Core_Model_Store $store)
    {
        return self::DATAFLOW_PROFILE_NAME_PREFIX . ' ' . $store->getName() . '/' . $store->getCode();
    }

    /**
     * Initialize the feed exporter.
     *
     * @return Recolize_RecommendationEngine_Model_Feed
     */
    private function initialize()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '-1');

        // Required for Magento 1.5.x
        $adminUserModel = Mage::getModel('admin/user')->setUserId(0);
        Mage::getSingleton('admin/session')->setUser($adminUserModel);

        return $this;
    }

    /**
     * Return the Recolize DataFlow profiles.
     *
     * @return Mage_Dataflow_Model_Resource_Profile_Collection
     */
    private function getFeedProfileCollection()
    {
        return Mage::getModel('dataflow/profile')
            ->getCollection()
            ->addFieldToFilter('name', array('like' => self::DATAFLOW_PROFILE_NAME_PREFIX . '%'));
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
}