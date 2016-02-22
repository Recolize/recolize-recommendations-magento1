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
class Recolize_RecommendationEngine_Model_Cookie extends Mage_Core_Model_Cookie
{
    /**
     * The cookie name.
     *
     * @var string
     */
    const COOKIE_NAME = 'recolize_parameter';

    /**
     * The cookie lifetime (browser session based).
     *
     * @var integer
     */
    const COOKIE_LIFETIME = 0;

    /**
     * Updates the user data in the Recolize cookie.
     *
     * @param string $userId user id
     * @param string $status user status
     * @param string $group user group
     * @return Recolize_RecommendationEngine_Model_Cookie chaining
     */
    public function updateUserData($userId, $status, $group)
    {
        $userDataArray = array();

        if (empty($userId) === false) {
            $userDataArray = array(
                'User' => array(
                    'id' => $userId
                )
            );
        }

        $userDataArray = array_merge_recursive($userDataArray, array(
            'User' => array(
                'status' => $status,
                'group' => $group
            )
        ));

        return $this->_saveCookie($userDataArray);
    }

    /**
     * Saves the Recolize cookie.
     *
     * @param array $additionalData the cookie data
     * @return Recolize_RecommendationEngine_Model_Cookie chaining
     */
    protected function _saveCookie($additionalData)
    {
        try {
            $cookieValue = Zend_Json::decode($this->get(self::COOKIE_NAME));

            if (empty($cookieValue) === true) {
                $cookieValue = array();
            }

            $cookieValue = Zend_Json::encode(array_replace($cookieValue, $additionalData));
            $this->set(self::COOKIE_NAME, $cookieValue, self::COOKIE_LIFETIME, null, null, null, false);
        } catch (Exception $exception) {

        }

        return $this;
    }
}