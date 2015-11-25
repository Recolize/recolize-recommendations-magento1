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
class Recolize_RecommendationEngine_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Returns whether the Recolize Recommendation extension is enabled in configuration or not.
     *
     * @return boolean
     */
    public function isExtensionEnabled()
    {
        return Mage::getStoreConfigFlag('recolize_recommendation_engine/general/enable_extension');
    }

    /**
     * Determines if we have a Magento Enterprise Edition
     *
     * @return bool true, if we are in Magento Enterprise Edition
     */
    public function isMagentoEnterprise()
    {
        return (boolean) Mage::getConfig()->getModuleConfig('Enterprise_Enterprise');
    }

    /**
     * Compares the given Magento versions with the actual Magento version used.
     * Both versions (community and enterprise) must always be passed.
     *
     * @param string $relation a relation like '>' or '<=' etc.
     * @param string $communityVersion the Magento community version (e.g. 1.5.0.0)
     * @param string $enterpriseVersion the Magento community version (e.g. 1.12.0.0)
     * @return boolean true, if the given version and relations match the actual Magento version
     */
    public function compareMagentoVersion($relation, $communityVersion, $enterpriseVersion)
    {
        if (empty($communityVersion) === true || empty($enterpriseVersion) === true) {
            return false;
        }

        if ($this->isMagentoEnterprise() === false) {
            return version_compare(Mage::getVersion(), $communityVersion, $relation);
        } else {
            return version_compare(Mage::getVersion(), $enterpriseVersion, $relation);
        }

        return false;
    }
}