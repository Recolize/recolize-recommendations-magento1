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
$storeCollection = Mage::getModel('core/store')->getCollection()
    ->addFieldToFilter('is_active', array('eq' => 1))
    ->setLoadDefault(false);

foreach ($storeCollection as $store) {
    /** @var Mage_Core_Model_Store $store */
    if ($store->getIsActive() == false) {
        continue;
    }

    $feedModel = Mage::getModel('recolize_recommendation_engine/feed');
    $feedFileName = $feedModel->getFeedFilename($store);
    $profileName = $feedModel->getFeedProfileName($store);

    $profile = Mage::getModel('dataflow/profile')->load($profileName, 'name');

    if ($profile->isEmpty() === false) {
        //The dataflow core module always uses getSingleton for the dataflow batch model instance.
        //Therefore we have to remove the existing instance with every profile run, because otherwise
        //the dataflow batch ids for the different profile runs keep the same, and the whole data of
        //all profile runs get aggregated with every next profile run (within one cron run).
        Mage::unregister('_singleton/dataflow/batch');

        $profile->run();
    }
}