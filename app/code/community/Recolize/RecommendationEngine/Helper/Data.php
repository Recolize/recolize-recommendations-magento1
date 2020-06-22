<?php
/**
 * BESUGRE Recommendation helper data
 *
 * @section LICENSE
 * This source file is subject to the EULA that is bundled with this package in the file LICENSE.txt.
 * It is also available on our website: http://www.besugre.com/BESUGRE_LICENSE.txt
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it, please send an email to service@besugre.com so we can send you a copy immediately.
 *
 * This software is distributed under a commercial license.
 * Any redistribution, copy or direct modification is explicitly not allowed.
 *
 * @category Besugre
 * @package Besugre_Recommendation
 * @author Michael Stork <m.stork@besugre.com>
 * @author Christoph Massmann <c.massmann@besugre.com>
 * @copyright 2013-2014 BESUGRE (http://www.besugre.com)
 * @license http://www.besugre.com/BESUGRE_LICENSE.txt commercial software license
 */
class Recolize_RecommendationEngine_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Returns whether the BESUGRE Recommendation extension is enabled in configuration or not.
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