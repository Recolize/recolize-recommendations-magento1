<?php
class Recolize_RecommendationEngine_Model_Session extends Mage_Core_Model_Session_Abstract
{
    /**
     * Initializes the Recolize Recommendation Engine session namespace.
     *
     * @return Recolize_RecommendationEngine_Model_Session chaining
     */
    public function __construct()
    {
        $this->init('recolize_recommendation_engine');

        return $this;
    }
}