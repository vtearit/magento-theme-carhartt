<?php
namespace AHT\ShippingEstimate\Model\Cart;
use Magento\Checkout\Model\Cart;
use Magento\Framework\View\Element\Template;
use Magento\Backend\Block\Template\Context;
class Shippingperitem extends Template
{
    protected $cart;
    public function __construct(Context $context, Cart $cart, array $data = [])
    {
        $this->cart = $cart;
        parent::__construct($context, $data);
    }
    public function getItems()
    {
        $itemsCollection = $this->cart->getQuote()->getItemsCollection();
        // get quote items array
        $items = $this->cart->getQuote()->getAllItems();
        foreach ($items as $item)
        {
            echo 'ID: ' . $item->getProductId() . '<br />';
            echo 'Name: ' . $item->getName() . '<br />';
            echo 'Sku: ' . $item->getSku() . '<br />';
            echo 'Quantity: ' . $item->getQty() . '<br />';
            echo 'Price: ' . $item->getPrice() . '<br />';
        }
    }
    //Get number of items and total quantity from cart.
    public function getNumberOfItems()
    {
        $totalItems = $this->cart->getQuote()->getItemsCount();
        $totalQuantity = $this->cart->getQuote()->getItemsQty();
        echo 'Items count :' . $totalItems . '<br />';
        echo 'Items qty :' . $totalQuantity . '<br />';
    }
    //Get base total price and grand total price of items from cart.
    public function getPrice()
    {
        $subTotal = $this->cart->getQuote()->getSubtotal();
        $grandTotal = $this->cart->getQuote()->getGrandTotal();
        echo 'Sub Total :' . $subTotal . '<br />';
        echo 'Grand Total :' . $grandTotal . '<br />';
    }
    //Get selected Shipping and Billing address from cart.
    public function getAddress()
    {
        $billingAddress = $this->cart->getQuote()->getBillingAddress();
        $shippingAddress = $this->cart->getQuote()->getShippingAddress();
        echo '<pre>';
        print_r($billingAddress->getData());
        echo '</pre>';
        echo '<pre>';
        print_r($shippingAddress->getData());
        echo '</pre>';
    }
}