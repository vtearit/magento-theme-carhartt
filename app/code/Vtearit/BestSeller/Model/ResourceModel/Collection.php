<?php

namespace Vtearit\BestSeller\Model\ResourceModel;

use Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection as BestsellersCollection;
use Vtearit\BestSeller\Block\Widget\BestSellers;

class Collection extends BestsellersCollection
{	
	/**
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Sales\Model\ResourceModel\Report $resource
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Sales\Model\ResourceModel\Report $resource,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null
    ) {
		parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $resource, $connection);
		$this->_ratingLimit = 100; // increase default bestsellers product limit
	}
}
