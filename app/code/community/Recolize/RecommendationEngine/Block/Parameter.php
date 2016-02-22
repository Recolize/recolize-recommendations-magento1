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
class Recolize_RecommendationEngine_Block_Parameter extends Mage_Core_Block_Template
{
    /**
     * Returns the Recolize cookie name.
     *
     * @return string the cookie name
     */
    public function getCookieName()
    {
        return Recolize_RecommendationEngine_Model_Cookie::COOKIE_NAME;
    }

    /**
     * Returns the default user status.
     *
     * @return string the user status
     */
    public function getDefaultUserStatus()
    {
        return Mage::getModel('recolize_recommendation_engine/user')->getDefaultCustomerStatus();
    }

    /**
     * Returns the default user group for logged out users.
     *
     * @return string the user group
     */
    public function getDefaultUserGroup()
    {
        return Mage::getModel('recolize_recommendation_engine/user')->getDefaultCustomerGroup();
    }
}