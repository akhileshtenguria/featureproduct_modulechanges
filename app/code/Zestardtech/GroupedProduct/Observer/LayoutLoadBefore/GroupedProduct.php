<?php

namespace Zestardtech\GroupedProduct\Observer\LayoutLoadBefore;

class GroupedProduct implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
       \Magento\Framework\Registry $registry
    ){
        $this->registry = $registry;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $this->registry->registry('current_product');

        if (!$product) {
          return $this;
        }
        $pageLayout = $product->getPageLayout();

        if ($pageLayout == 'grouped_product') 
        {
           $layout = $observer->getLayout();
           $productId = $product->getId(); 
           $layout->getUpdate()->addHandle("catalog_product_view_grouped_product");
        }

        return $this;
    }
}