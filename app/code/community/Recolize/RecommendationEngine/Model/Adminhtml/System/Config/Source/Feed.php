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
class Recolize_RecommendationEngine_Model_Adminhtml_System_Config_Source_Feed extends Mage_Core_Model_Config_Data
{
    /**
     * Return the dynamic comment for the Recolize product feed export.
     *
     * @param Mage_Core_Model_Config_Element $element
     * @param $currentValue
     *
     * @return string
     */
    public function getCommentText(Mage_Core_Model_Config_Element $element, $currentValue)
    {
        $commentString = Mage::helper('recolize_recommendation_engine')->__('If set to \'Yes\' the Recolize Product Feed will be generated each night. Please copy the path depending on your StoreView into your domain settings in the <a href="https://tool.recolize.com/domains?utm_source=magento-extension-admin-area&utm_medium=web&utm_campaign=Magento Extension Admin" target="_blank">Recolize Tool</a>:') . '<br />';
        foreach (Mage::app()->getStores() as $store) {
            $commentString .= sprintf(
                '<b>%s</b>: <nobr>%s</nobr><br />',
                $store->getName(),
                Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . Mage::getSingleton('recolize_recommendation_engine/feed')->getFeedFileName($store)
            );
        }
        $commentString .= '<br />' . Mage::helper('recolize_recommendation_engine')->__('You can set this setting to \'No\' if you already have other product feeds like Google Shopping, CSV-based product exports, etc. Then you have to enter these feed urls into the <a href="https://tool.recolize.com/domains?utm_source=magento-extension-admin-area&utm_medium=web&utm_campaign=Magento Extension Admin" target="_blank">Recolize Tool</a>.');

        return $commentString;
    }
}