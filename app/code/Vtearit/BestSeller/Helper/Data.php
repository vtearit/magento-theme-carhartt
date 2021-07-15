<?php

namespace Vtearit\BestSeller\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper {
	
	/**
	 * Retrieve config value by path
	 * 
	 * @param string $path
	 * @return string
	 */ 
	public function getConfigValue($path)
	{
		return $this->scopeConfig->getValue($path);
	}
}
