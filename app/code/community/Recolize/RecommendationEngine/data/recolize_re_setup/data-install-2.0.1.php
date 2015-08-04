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

    $feedFileName = Mage::getModel('recolize_recommendation_engine/feed')->getFeedFilename($store);

    $profileName = Recolize_RecommendationEngine_Model_Feed::DATAFLOW_PROFILE_NAME_PREFIX . ' ' . $store->getName();

    $profile = Mage::getModel('dataflow/profile')->load($profileName, 'name');

    if ($profile->isEmpty() === false) {
        $profile->run();
    }
}