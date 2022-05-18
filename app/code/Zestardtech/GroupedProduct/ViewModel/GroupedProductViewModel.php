<?php 

namespace Zestardtech\GroupedProduct\ViewModel; 

use Magento\Framework\View\Element\Block\ArgumentInterface; 
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku;  

class GroupedProductViewModel implements ArgumentInterface 
{

    protected $_storeManager;

    private $getSalableQuantityDataBySku;        

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        GetSalableQuantityDataBySku $getSalableQuantityDataBySku

    ) {
        $this->_storeManager = $storeManager;
        $this->getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
    }
        

    public function getCurrencySymbol() {
        return $this ->_storeManager->getStore()->getBaseCurrency()->getCurrencySymbol();
    }

    public function getCurrencyCode() {
        return $this ->_storeManager->getStore()->getCurrentCurrencyCode();
    }

    public function getCurrentStoreId() {
        return $this ->_storeManager->getStore()->getId();
    }

    public function getProductSalableQty($sku)
    {
        return $this->getSalableQuantityDataBySku->execute($sku);
        
    }

}