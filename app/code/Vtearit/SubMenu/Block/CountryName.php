<?php
namespace Vtearit\SubMenu\Block;

class CountryName extends \Magento\Framework\View\Element\Template
{
    protected $_registry;
    protected $_categoryFactory;
    
public function __construct(
    \Magento\Backend\Block\Template\Context $context,
    \Magento\Catalog\Model\CategoryFactory $categoryFactory,    
    \Magento\Framework\Registry $registry,
    array $data = []
)
{        
    $this->_registry = $registry;
    $this->_categoryFactory = $categoryFactory; 
    parent::__construct($context, $data);
}

public function _prepareLayout()
{
    return parent::_prepareLayout();
}

public function getCurrentCategory()
{        
    return $this->_registry->registry('current_category');
}

/* Get category object */
public function getCategory($categoryId)
{
	$category = $this->_categoryFactory->create()->load($categoryId);
	return $category;
}

/* Get all children categories IDs */
public function getAllChildren($asArray = false, $categoryId)
{
	return $this->getCategory($categoryId)->getAllChildren($asArray);
}

/* Get children ids comma separated */
public function getChildren($categoryId)
{
	return $this->getCategory($categoryId)->getChildren();
}

}
?>