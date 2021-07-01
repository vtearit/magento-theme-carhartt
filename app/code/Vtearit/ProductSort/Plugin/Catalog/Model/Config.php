<?php

namespace Vtearit\ProductSort\Plugin\Catalog\Model;

class Config
{
    public function afterGetAttributeUsedForSortByArray(
        \Magento\Catalog\Model\Config $catalogConfig,
        $options
    ) {

        // unset($options['position']);
        unset($options['price']);
        unset($options['name']);
        unset($options['fit']);
        $options['price'] = 'Price Low To High';
        $options['high_to_low'] = 'Price High To Low';
        $options['position'] = 'New In';
        return $options;

    }

}