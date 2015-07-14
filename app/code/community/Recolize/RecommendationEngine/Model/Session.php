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