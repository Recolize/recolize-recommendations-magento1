<?php

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

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
    <var name="filename"><![CDATA[product-export-%s.csv]]></var>
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

    $profile = Mage::getModel('dataflow/profile')
        ->setName(Recolize_RecommendationEngine_Model_Feed::DATAFLOW_PROFILE_NAME_PREFIX . ' ' . $store->getName())
        ->setActionsXml(sprintf($profileXml, $store->getId(), $store->getId(), uniqid(time()) . '-' . $store->getCode()))
        ->save();
}

$installer->endSetup();