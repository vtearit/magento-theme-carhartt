<?php

namespace Vtearit\MageBanner\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
class InstallSchema implements InstallSchemaInterface
{


public function install(SchemaSetupInterface $setup, ModuleContextInterface    $context)
  {
    $installer = $setup;

    $installer->startSetup();

    $eavTable = $installer->getTable('mageplaza_bannerslider_banner');

    $columns = [
        'mobile_banner_type' => [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            'nullable' => true,
            'default' => '0',
            'length' => '225',
            'comment' => 'Mobile Banner Type',
        ],
        'mobile_banner_content' => [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => '64k',
            'comment' => 'Mobile Banner Content'
        ],
    
        'mobile_banner_image' => [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => '255',
            'comment' => 'Mobile Banner Image'
        ]
    ];

    $connection = $installer->getConnection();
    foreach ($columns as $name => $definition) {
        $connection->addColumn($eavTable, $name, $definition);
    }

    $installer->endSetup();
}
}