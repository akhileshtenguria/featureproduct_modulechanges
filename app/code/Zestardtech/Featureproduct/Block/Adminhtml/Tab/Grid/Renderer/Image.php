<?php 
namespace Zestardtech\Featureproduct\Block\Adminhtml\Tab\Grid\Renderer;

class Image extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_storeManager;

    protected $productFactory;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;        
        $this->productFactory = $productFactory;
    }


    public function render(\Magento\Framework\DataObject $row)
    {
        $product_id = $row->getData('entity_id');
        $product = $this->productFactory->create()->load($product_id);

        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
           \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
       );
        $urlPath = $mediaDirectory.'catalog/product';
         $productImagePath = ($product->getData('thumbnail')) ? $product->getData('thumbnail') :  'catalog/product/no-img.jpg'; 
         $productImageUrl = $urlPath.$productImagePath;
           //if($productImageUrl!=''):
            $img='<img src="'.$productImageUrl.'" width="50" height="50"/>';
            //endif;
        return $img;
    }
}