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
class Recolize_RecommendationEngine_Model_Enterprise_PageCache_Container extends Enterprise_PageCache_Model_Container_Abstract
{
    /**
     * Get unique cache identifier as we do not want to cache the block at all.
     *
     * @return string
     */
    protected function _getCacheId()
    {
        return 'CONTAINER_RECOLIZE_RECOMMENDATION_ENGINE_' . md5(microtime() . rand());
    }

    /**
     * Retrieve cache identifier.
     *
     * @return string
     */
    public function getCacheId()
    {
        return $this->_getCacheId();
    }

    /**
     * Render block content from placeholder.
     *
     * @return string
     */
    protected function _renderBlock()
    {
        $block = $this->_getPlaceHolderBlock();
        return $block->toHtml();
    }
}