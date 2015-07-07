<?php

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