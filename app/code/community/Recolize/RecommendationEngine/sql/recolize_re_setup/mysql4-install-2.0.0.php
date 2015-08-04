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
/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

// Required for Magento 1.5.x
$adminUserModel = Mage::getModel('admin/user')->setUserId(0);
Mage::getSingleton('admin/session')->setUser($adminUserModel);

$profileXml = <<<EOT
<action type="catalog/convert_adapter_product" method="load">
    <var name="store"><![CDATA[%d]]></var>
    <var name="filter/visibility"><![CDATA[4]]></var>
    <var name="filter/status"><![CDATA[1]]></var>
</action>

<action type="recolize_recommendation_engine/convert_parser_product" method="unparse">
    <var name="store"><![CDATA[%d]]></var>
    <var name="url_field"><![CDATA[1]]></var>
</action>

<action type="recolize_recommendation_engine/convert_mapper_column" method="map">
</action>

<action type="dataflow/convert_parser_csv" method="unparse">
    <var name="delimiter"><![CDATA[;]]></var>
    <var name="enclose"><![CDATA["]]></var>
    <var name="fieldnames">true</var>
</action>

<action type="dataflow/convert_adapter_io" method="save">
    <var name="type">file</var>
    <var name="path">media/</var>
    <var name="filename"><![CDATA[%s]]></var>
</action>
EOT;

// Create one Dataflow profile for each store.
// As the stores are initialized after the sql scripts we cannot use Mage::app()->getStores() for store collection.
$storeCollection = Mage::getModel('core/store')->getCollection()
    ->addFieldToFilter('is_active', array('eq' => 1))
    ->setLoadDefault(false);
foreach ($storeCollection as $store) {
    /** @var Mage_Core_Model_Store $store */
    if ($store->getIsActive() == false) {
        continue;
    }

    $feedFileName = Mage::getModel('recolize_recommendation_engine/feed')->getFeedFilename($store);

    $profileName = Recolize_RecommendationEngine_Model_Feed::DATAFLOW_PROFILE_NAME_PREFIX . ' ' . $store->getName();

    $profile = Mage::getModel('dataflow/profile')->load($profileName, 'name')
        ->setName($profileName)
        ->setActionsXml(sprintf($profileXml, $store->getId(), $store->getId(), $feedFileName))
        ->save();
}

// Create cron schedule for the first feed generation
Mage::getModel('cron/schedule')
    ->setJobCode('recolize_recommendation_engine_cronjob')
    ->setScheduledAt(strftime('%Y-%m-%d %H:%M', time()))
    ->setStatus(Mage_Cron_Model_Schedule::STATUS_PENDING)
    ->save();

$installer->endSetup();