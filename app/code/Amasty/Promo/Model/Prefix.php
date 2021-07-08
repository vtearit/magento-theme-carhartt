<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Promo
 */


namespace Amasty\Promo\Model;

use Amasty\Promo\Helper\Item;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Sales\Model\Order\Item as OrderItem;

class Prefix
{
    /**
     * @var Item
     */
    private $promoItemHelper;

    /**
     * @var Config
     */
    private $configProvider;

    /**
     * @var array
     */
    private $itemIds = [];

    public function __construct(Item $promoItemHelper, Config $configProvider)
    {
        $this->promoItemHelper = $promoItemHelper;
        $this->configProvider = $configProvider;
    }

    /**
     * @param AbstractItem $item
     * @return bool
     */
    public function isNeedPrefix(AbstractItem $item)
    {
        if (!in_array($item->getItemId(), $this->itemIds) && $this->promoItemHelper->isPromoItem($item)) {
            $this->itemIds[] = $item->getItemId();
            return true;
        }

        return false;
    }

    /**
     * @param AbstractItem|OrderItem $item
     */
    public function addPrefixToName($item)
    {
        if ($prefix = $this->configProvider->getProductPrefix()) {
            $buyRequest = $item->getBuyRequest();

            if (isset($buyRequest['options'][Rule::OPTION_ID])) {
                $item->setName($prefix . ' ' . $item->getName());
            }
        }
    }
}
