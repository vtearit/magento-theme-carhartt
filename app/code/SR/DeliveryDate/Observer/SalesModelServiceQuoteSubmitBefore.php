<?php
namespace SR\DeliveryDate\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;
use SR\DeliveryDate\Model\Validator;

class SalesModelServiceQuoteSubmitBefore implements ObserverInterface
{
    /**
     * @param \Magento\Quote\Model\QuoteFactory
     */
    private $_quoteFactory;

    public function __construct(
        \Magento\Quote\Model\QuoteFactory $quoteFactory
    )
    {
        $this->_quoteFactory = $quoteFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quoteId = $order->getQuoteId();
        $quote = $this->_quoteFactory->create()->load($quoteId);
        $date = $quote->getDeliveryDate();
        $order->setData('delivery_date',  $quote->getDeliveryDate());
        $order->setData('delivery_comment', $quote->getDeliveryComment());
        $order->save();
    }
}