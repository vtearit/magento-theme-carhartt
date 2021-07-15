<?php

namespace Vtearit\BestSeller\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Block\Product\AbstractProduct;
use Vtearit\BestSeller\Model\ResourceModel\Collection;
use Vtearit\BestSeller\Helper\Data;
use Magento\Catalog\Helper\Image;

class BestSellers extends Template implements BlockInterface 
{
	const MODULE_ENABLE = 'zshape_bestsellers/options/enable';
	
	/**
     * Path to template file 
     *
     * @var string
     */
    protected $_template = 'widget/bestsellers.phtml';
	
	/**
	 * @var \Vtearit\BestSeller\Model\ResourceModel\Collection 
	 */
	protected $bestsellers;
	
	/**
	 * @var \Magento\Catalog\Model\ProductRepository;
	 */
	protected $productRepository;
	
	/**
	 * @var \Magento\Catalog\Block\Product\AbstractProduct
	 */ 
	protected $abstractProduct;
	
	/**
	 * @var \Vtearit\BestSeller\Helper\Data
	 */
	protected $helper;
	
	/**
	 * @param \Magento\Framework\View\Element\Template\Context $context 
	 * @param \Vtearit\BestSeller\Model\ResourceModel\Collection $bestsellers
	 * @param array $data
	 */
	public function __construct(
		Template\Context $context, 
		Collection $bestsellers, 
		ProductRepository $productRepository,
		AbstractProduct $abstractProduct,
		\Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory $collectionFactory,
		\Magento\Catalog\Model\ProductFactory $productFactory,
		\Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
		Data $helper,
        \Magento\Catalog\Helper\ImageFactory $imageHelperFactory,
		// \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $catalogProductTypeConfigurable,
		\Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurable,
		array $data = []
	)
	{
		parent::__construct($context, $data);
		$this->bestsellers = $bestsellers;
		$this->productRepository = $productRepository;
        $this->imageHelperFactory = $imageHelperFactory;
		$this->abstractProduct = $abstractProduct;
		$this->helper = $helper;
		$this->_collectionFactory = $collectionFactory;
		$this->productFactory = $productFactory;
		$this->priceCurrency = $priceCurrency;
		//for getting parent id of simple
		// $this->_catalogProductTypeConfigurable = $catalogProductTypeConfigurable;
		$this->configurable = $configurable;
	}

	public function getImageBestSeller($id){
        $product = $this->productFactory->create()->load($id);
        // $imageUrl = $this->imageHelperFactory->create()->init($product, 'category_page_grid')->getUrl();
        $imageUrl = $this->imageHelperFactory->create()->init($product, 'recently_viewed_products_grid_content_widget')->getUrl();
        return $imageUrl;
    }

	// Get parent by child
	public function getParentProductId($childProductId)
    {
		$parentConfigObject = $this->configurable->getParentIdsByChild($childProductId);
		if($parentConfigObject) {
			return $parentConfigObject[0];
		}
		return false;
    }


	/**
	 * This method returns the url from the product id
	 *
	 * @return string|null
	 */
	public function getProductUrlById($id)
	{
		// $sku = "24-MB01";
		try {
			$productUrl = $this->productRepository->get($id)
				->getProductUrl();

		} catch (NoSuchEntityException $noSuchEntityException) {
		$productUrl = null;
		}
		return $productUrl;
	}

	public function getCurrencyWithFormat($price)
    {
        return $this->priceCurrency->format($price,true,2);
    }


	public function getBestSellerData(){
		$bestSellerProdcutCollection = $this->_collectionFactory->create()
		->setModel('Magento\Catalog\Model\Product')
		->setPeriod('month') //you can add period daily,yearly
		;	
		return $bestSellerProdcutCollection;
	}

	public function getPriceById($id)
{
    //$id = '21'; //Product ID
    $product = $this->productFactory->create();
    $productPriceById = $product->load($id)->getPrice();
    return $productPriceById;
}
	
	/**
	 * @return array 
	 */
	public function getBestSellers()
	{
		$bestsellers = array();
		
		if(count($this->bestsellers)) {
			foreach($this->bestsellers as $bestseller) {
				$productId = $bestseller->getProductId();
				$bestsellers[] = $this->productRepository->getById($productId);
			}
		}

		return $bestsellers;
	}
	
	/**
	 * @param \Magento\Catalog\Model\Product $product
	 * @param string $imageId 
	 * @param array $attributes
	 * @return \Magento\Catalog\Block\Product\Image
	 */
	public function getImage($product, $imageId, $attributes = [])
	{
		return $this->abstractProduct->getImage($product, $imageId, $attributes = []);
	}
	
	/**
	 * @param \Magento\Catalog\Model\Product $product
     * @param array $additional
     * @return string
     */
	public function getAddToCartUrl($product, $additional = []) 
	{
		return $this->abstractProduct->getAddToCartUrl($product, $additional = []);
	}
	
	/**
     * @param \Magento\Catalog\Model\Product $product
     * @param bool $templateType
     * @param bool $displayIfNoReviews
     * @return string
     */
    public function getReviewsSummaryHtml(
        \Magento\Catalog\Model\Product $product,
        $templateType = false,
        $displayIfNoReviews = false
    ) {
		return $this->abstractProduct->getReviewsSummaryHtml($product, $templateType, $displayIfNoReviews);
	}

	/**
     * @return bool
     */
    public function getModuleEnable()
    {
		return $this->helper->getConfigValue(self::MODULE_ENABLE);
	}
    	
	/**
	 * @var string $param
	 * @return string
	 */ 
	public function getBestSellerParam($param)
	{
		return $this->getData($param);
	}
}
