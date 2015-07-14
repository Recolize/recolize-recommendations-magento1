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
class Recolize_RecommendationEngine_Model_Observer
{
    /**
     * Called, if a product was successfully added to the cart.
     * Saves the necessary add to cart action data to the Recolize session namespace.
     *
     * Event: checkout_cart_product_add_after
     *
     * @param Varien_Event_Observer $eventObject event object
     * @return Recolize_RecommendationEngine_Model_Observer chaining
     */
    public function addToCart($eventObject)
    {
        Mage::getSingleton('recolize_recommendation_engine/session')->setIsProductAddedToCart(true)
            ->setProductId($eventObject->getProduct()->getId());

        return $this;
    }
}