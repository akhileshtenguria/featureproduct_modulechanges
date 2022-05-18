<?php

namespace Zestardtech\GroupedProduct\Block;

use Magento\Framework\View\Element\Block\ArgumentInterface; 
use Magento\Catalog\Helper\ImageFactory as HelperFactory;
use Magento\Framework\View\Asset\Repository;
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku; 
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Swatches\Helper\Data as SwatchData;
use Magento\Swatches\Helper\Media;
use Zestardtech\Optimization\Model\ThemeTable;

class GroupedProduct extends \Magento\Framework\View\Element\Template
{ 

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    protected $_registry;

    protected $_productloader;    

    protected $_customOptions;

    /**
         * @var HelperFactory
         */
    protected $helperFactory;
     /**
      * @param \Magento\Framework\Registry $registry,
     */
    /**
     * @var Repository
     */
    protected $assetRepos;

    protected $_reviewFactory;    

    protected $_ratingFactory;

    protected $_catalogProductTypeConfigurable;

    protected $attributeSetRepository; 

    private $objectManager; 

    private $swatchesHelperMedia;

    protected $_resource;    

    protected $httpHeader;

    const IS_CDN_ENABLE  = 'web/unsecure/base_media_url';      

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Catalog\Block\Product\ListProduct $listBlock,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product\Option $customOptions,
        HelperFactory $helperFactory,
        Repository $repository,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $catalogProductTypeConfigurable,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewFactory,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        GetSalableQuantityDataBySku $getSalableQuantityDataBySku,
        AttributeSetRepositoryInterface $attributeSetRepository,
        Product $product,
         \Magento\Framework\ObjectManagerInterface $objectmanager,
        Media $swatchMediaHelper,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\HTTP\Header $httpHeader,
        array $data = []
    )
    {    
        $this->_registry = $registry;
        $this->_productloader = $_productloader;
        $this->listBlock = $listBlock;
        $this->_storeManager = $storeManager;
        $this->_customOptions = $customOptions;
        $this->helperFactory = $helperFactory;
        $this->assetRepos = $repository;
        $this->_catalogProductTypeConfigurable = $catalogProductTypeConfigurable;
        $this->_reviewFactory = $reviewFactory;
        $this->_ratingFactory = $ratingFactory;
        $this->getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
        $this->attributeSetRepository = $attributeSetRepository;
        $this->product = $product;
        $this->objectManager = $objectmanager;
        $this->swatchMediaHelper = $swatchMediaHelper;
        $this->_resource = $resource;
        $this->_scopeConfig = $scopeConfig;
        $this->httpHeader = $httpHeader;
        parent::__construct($context, $data);
    }

    /**
     * Return catalog current category object
     * @return \Magento\Catalog\Model\Category
     */


    public function getObjManager(){

        return $this->objectManager;
    }
    public function getResource(){

        return $this->_resource;
    }

    public function getConfigProductObj(){
        $productTypeInstance = $this->objectManager->get('Magento\ConfigurableProduct\Model\Product\Type\Configurable');
        return $productTypeInstance;
    }

    public function getSwatchHelper(){

        return $this->swatchMediaHelper;
    }

    public function getSwatchCollection(){

        return $this->objectManager->create('Magento\Swatches\Model\ResourceModel\Swatch\Collection');
    }   
     
    public function getAttributeSetName($productId)
    {
        $product = $this->product->load($productId);
        $attributeSet = $this->attributeSetRepository->get($product->getAttributeSetId());
        return $attributeSet->getAttributeSetName();
    }

    public function getReviewCollection($productId){
        $collection = $this->_reviewFactory->create()
        ->addStatusFilter(
            \Magento\Review\Model\Review::STATUS_APPROVED
        )->addEntityFilter(
            'product',
            $productId
        )->setDateOrder();
        return $collection->getData();
    }

    public function getRatingCollection(){
        $ratingCollection = $this->_ratingFactory->create()
        ->getResourceCollection()
        ->addEntityFilter(
            'product' 
        )->setPositionOrder()->setStoreFilter(
            $this->_storeManager->getStore()->getId()
        )->addRatingPerStoreName(
            $this->_storeManager->getStore()->getId()
        )->load();

        return $ratingCollection->getData();
    }
    
    public function getMediaUrl()
    {
        $cdnValue = $this->_scopeConfig->getValue(self::IS_CDN_ENABLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($cdnValue == ''){
            return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        }
        else{
            return $cdnValue;
        }
    }

    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getLoadProduct($id)
    {
        return $this->_productloader->create()->load($id);
    }

    public function getAddToCartUrl($product)
    {
        return $this->listBlock->getAddToCartUrl($product);
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

    public function getCustomOptions($data)
    {
        return $this->_customOptions->getProductOptionCollection($data);
    }

    /**
     * Get small place holder image
     *
     * @return string
     */
    public function getPlaceHolderImage()
    {
        /** @var \Magento\Catalog\Helper\Image $helper */
        $helper = $this->helperFactory->create();
        return $this->assetRepos->getUrl($helper->getPlaceholder('image'));
    }
    public function getProductData($id) {
        $parentByChild = $this->_catalogProductTypeConfigurable->getParentIdsByChild($id);
        if (isset($parentByChild[0])) {
            $id = $parentByChild[0];
        }
        return $id;
    }

    public function getProductSalableQty($sku)
    {
        return $this->getSalableQuantityDataBySku->execute($sku);
        
    }

    public function get_attr_class($pr_id,$attribute_code,$objectManager)
    {
        $product_my_detail = $this->_productloader->create()->load($pr_id);
        $attr = $product_my_detail->getResource()->getAttribute($attribute_code);
        $optionId = $product_my_detail->getData($attribute_code); 

        $color_replace3  = preg_replace('/[^a-zA-Z0-9_ -]/s','',$attr->getSource()->getOptionText($optionId));
        $optionClass = strtolower(str_replace(" ", "", $color_replace3));
        return $optionClass;
    }
    public function get_attr_label_class($pr_id,$attribute_code,$objectManager)
    {
        $product_my_detail = $this->_productloader->create()->load($pr_id);
        $attr = $product_my_detail->getResource()->getAttribute($attribute_code);
        $optionId = $product_my_detail->getData($attribute_code); 
        $label  = $attr->getSource()->getOptionText($optionId);
        return $label;
    } 

    public function get_filter_class($pr_id,$optionClass='',$product_filters_array,$objectManager){
        $product_filters_array_total = sizeof($product_filters_array);
        if($product_filters_array_total > 0 ){
            for($dca=0;$dca<$product_filters_array_total;$dca++) {
                $product_my_detail = $this->_productloader->create()->load($pr_id);
                if($product_filters_array[$dca]!=''){
                    $attr = $product_my_detail->getResource()->getAttribute($product_filters_array[$dca]);
                    $optionId = $product_my_detail->getData($product_filters_array[$dca]); 
                    if ($attr->usesSource()) {
                        $color_replace3  = preg_replace('/[^a-zA-Z0-9_ -]/s','',$attr->getSource()->getOptionText($optionId));
                        $optionClass = $optionClass.'  '.strtolower(str_replace(" ", "", $color_replace3));
                    }
                }
            }
        }
        return $optionClass;
    }

    public function getOptionText($tableName,$get_attribute_id,$_product_id)
    {
        $tableName = $this->_resource->getTableName('catalog_product_entity_int');
        $sql = "Select * FROM " . $tableName ." where `attribute_id` =".$get_attribute_id." and `entity_id` =".$_product_id;
        $result = $this->_resource->getConnection()->fetchAll($sql); 
        return $result;
    }

    public function getFetchReview($review_id)
    {
        $rating_option_vote = $this->_resource->getTableName('rating_option_vote'); 
        $sql_review = "Select * FROM " . $rating_option_vote ." where `review_id` =".$review_id;
        $result_review = $this->_resource->getConnection()->fetchAll($sql_review); 
        return $result_review;
    } 

    public function isMobile(){
        $userAgent = $this->httpHeader->getHttpUserAgent();
        $isMobile = \Zend_Http_UserAgent_Mobile::match($userAgent, $_SERVER);
        $device = '';
            if ($isMobile) {
                $device = true;
            } else {
                $device = false;
            }
            return $device;
    }   

    public function getCmp($a,$b)
    {
        return (float) $a['product_position'] > (float)$b['product_position'];
    }
                        
}
?>