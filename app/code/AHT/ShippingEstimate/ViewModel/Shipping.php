<?php

namespace AHT\ShippingEstimate\ViewModel;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;

class Shipping implements ArgumentInterface
{
    /**
     * System XML config path for ChrisMallory_FreeShippingBanner - Uses default checkout cart section
     */
    protected const CHECKOUT_CART_XML_CONFIG_PATH = 'checkout/cart/';

    /**
     * System XML config path for core Free Shipping method
     */
    protected const CARRIERS_FREE_SHIPPING_XML_CONFIG_PATH = 'carriers/freeshipping/';

    /**
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @var Session $session
     */
    protected $session;

    /**
     * @var PriceCurrencyInterface $priceCurrency
     */
    protected $priceCurrency;

    /**
     * Countdown constructor.
     *
     * @param ScopeConfigInterface      $scopeConfig
     * @param Session                   $session
     * @param PriceCurrencyInterface    $priceCurrency
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Session $session,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->session = $session;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * Get minimum order total required to be eligible for free shipping
     *
     * @return float
     */
    public function getFreeShippingMinValue(): float
    {
        if ($this->scopeConfig->getValue(self::CHECKOUT_CART_XML_CONFIG_PATH
                . 'use_freeshipping_method_config', ScopeInterface::SCOPE_STORE)
            && $this->scopeConfig->getValue(self::CARRIERS_FREE_SHIPPING_XML_CONFIG_PATH
                . 'active', ScopeInterface::SCOPE_STORE)
        ) {
            return $this->getFreeShippingMethodMinValue();
        }

        return (float)$this->scopeConfig->getValue(self::CHECKOUT_CART_XML_CONFIG_PATH
            . 'freeshipping_progress_min_total', ScopeInterface::SCOPE_STORE);
    }

    public function getFreeShippingMethodMinValue(): float
    {
        return (float)$this->scopeConfig->getValue(self::CARRIERS_FREE_SHIPPING_XML_CONFIG_PATH
            . 'free_shipping_subtotal', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param float $price
     * @param int   $precision
     *
     * @return string
     */
    public function getFormattedPrice(float $price, int $precision = 2): string
    {
        return $this->priceCurrency->format($price, false, $precision);
    }
}
