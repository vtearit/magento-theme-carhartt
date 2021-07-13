<?php

namespace Vtearit\MageBanner\Plugin\Block\Adminhtml\Banner\Edit\Tab;

use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Button;
use Magento\Backend\Block\Widget\Form\Element\Dependence;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;
use Magento\Config\Model\Config\Source\Enabledisable;
use Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Mageplaza\BannerSlider\Block\Adminhtml\Banner\Edit\Tab\Render\Image as MobileBannerImage;
use Mageplaza\BannerSlider\Block\Adminhtml\Banner\Edit\Tab\Render\Slider;
use Mageplaza\BannerSlider\Helper\Data;
use Mageplaza\BannerSlider\Helper\Image as HelperImage;
use Mageplaza\BannerSlider\Model\Config\Source\Template;
use Mageplaza\BannerSlider\Model\Config\Source\Type;

class Banner
{
    protected $registry;
    /**
     * Custom options
     *
     * @var Type
     */
    protected $mobileTypeOptions;

       /**
     * @var HelperImage
     */
    protected $imageHelper;
    
    /**
     * @var WysiwygConfig
     */
    protected $_wysiwygConfig;

    public function __construct(
        \Magento\Framework\Registry $registry,
        Type $mobileTypeOptions,
        HelperImage $imageHelper,
        WysiwygConfig $wysiwygConfig
        )
    {        
        $this->registry = $registry;
        $this->mobileTypeOptions = $mobileTypeOptions;
        $this->imageHelper = $imageHelper;
        $this->_wysiwygConfig = $wysiwygConfig;
    }
      
    public function aroundGetFormHtml(
        \Mageplaza\BannerSlider\Block\Adminhtml\Banner\Edit\Tab\Banner $subject,
        \Closure $proceed
    )
    {
        $banner = $this->registry->registry('mpbannerslider_banner');

        $form = $subject->getForm();
        if (is_object($form)) {

            $fieldset = $form->getElement('base_fieldset');

            $customBanner = $fieldset->addField('mobile_banner_type', 'select', [
                'name'  => 'mobile_banner_type',
                'label' => __('Mobile Banner Type'),
                'title' => __('Mobile Banner Type'),
                'values' => $this->mobileTypeOptions->toOptionArray()
            ]);

            $customImage = $fieldset->addField('mobile_banner_image', MobileBannerImage::class, [
                'name' => 'mobile_banner_image',
                'label' => __('Mobile Banner Image'),
                'title' => __('Mobile Banner Image'),
                'path' => $this->imageHelper->getBaseMediaPath(HelperImage::TEMPLATE_MEDIA_TYPE_BANNER)
            ]);

            $mobileBannerContent = $fieldset->addField('mobile_banner_content', 'editor', [
                'name' => 'mobile_banner_content',
                'required' => false,
                'config' => $this->_wysiwygConfig->getConfig([
                    'hidden' => true,
                    'add_variables' => false,
                    'add_widgets' => false,
                    'add_directives' => true
                ])
            ]);

        
            if($banner->getData()){
                $form->addValues($banner->getData());
            }
            $subject->setForm($form);
        }

        return $proceed();
    }
}