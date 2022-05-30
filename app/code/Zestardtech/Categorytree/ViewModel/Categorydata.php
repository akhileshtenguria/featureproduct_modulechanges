<?php

namespace Zestardtech\Categorytree\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface; 
use Magento\Catalog\Helper\ImageFactory as HelperFactory;
use Magento\Framework\View\Asset\Repository;
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku;

class Categorydata implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected $_registry;

     /**
      * @param \Magento\Framework\Registry $registry,
     */
    protected $_categoryFactory;


            protected $_request;    
        /**
         * @var \Magento\Framework\App\Config\ScopeConfigInterface
         */
        protected $_scopeConfig;

        protected $categoryRepository;
        /**
         * @var HelperFactory
         */
        protected $helperFactory;

        /**
         * @var Repository
         */
        protected $assetRepos;
        /**
         * Admin configuration paths
         *
         */
        protected $_resource;

        private $getSalableQuantityDataBySku;

        const IS_ENABLED  = 'noproductimage/general/enable';

        const NO_PRODUCT_IMAGE_CONTENT = 'noproductimage/general/editor_textarea';

        const NO_PRODUCT_IMAGE = 'noproductimage/general/upload_image_no_product';

        const IS_CDN_ENABLE  = 'web/unsecure/base_media_url';


    public function __construct(
        \Magento\Framework\Registry $registry,
        \Zestardtech\Categorytree\Model\ResourceModel\Assigncategory\Collection $assigncollFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
       \Magento\Framework\App\Request\Http $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Framework\App\ResourceConnection $resource,
        HelperFactory $helperFactory,
        Repository $repository,
        GetSalableQuantityDataBySku $getSalableQuantityDataBySku,
        \Zestardtech\Featureproduct\Model\ResourceModel\Featureproduct\Collection $featurePrdouctFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productrepository,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Helper\Image $imageHelper
    ) {
        $this->_registry = $registry;
        $this->assigncollFactory = $assigncollFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->featurePrdouctFactory = $featurePrdouctFactory;

        $this->imageHelper = $imageHelper;
        $this->_request = $request;
        $this->_storeManager = $storeManager;
        $this->productrepository = $productrepository;
        $this->_scopeConfig = $scopeConfig;
        $this->categoryRepository = $categoryRepository;
        $this->_resource = $resource;
        $this->helperFactory = $helperFactory;
        $this->assetRepos = $repository;
        $this->getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
        $this->_productCollectionFactory = $productCollectionFactory;  

    }

    /**
     * Return catalog current category object
     * @return \Magento\Catalog\Model\Category
     */

    public function getCurrentCategoryOb()
    {
        return $this->_registry->registry('current_category');
    }      
    public function getAssignCategory()
    {
        $categoryObj = $this->_registry->registry('current_category');
        $catId = $categoryObj->getId();
        // die; 

        $collection = $this->assigncollFactory->addFieldToFilter('category_id', $catId)
        ->setOrder('position', 'ASC')
        ->load();
        return $collection; 
    }

    public function getCategoryName($categoryId)
    {
        $category = $this->_categoryFactory->create()->load($categoryId);
        $categoryName = $category->getName();
        return $categoryName;
    }



    /*    public function getMediaUrl()
    {
       return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }*/
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

    /**
     * Return the category object by its id.
     * 
     * @param categoryId (Integer)
     */
    public function getCategory($categoryId)
    {
      return $this->categoryRepository->get($categoryId);
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

    public function getFeatureProduct()
    {
        $categoryObj = $this->_registry->registry('current_category');
        $catId = $categoryObj->getId();

        $collection = $this->featurePrdouctFactory->addFieldToFilter('category_id', $catId)
        ->setOrder('position', 'ASC')
        ->load();
        return $collection; 
    }

    Public function getProductImageUsingCode($productId)
    {
         $store = $this->_storeManager->getStore();
         // $productId = 95;
         $product = $this->productrepository->getById($productid);
 
         $productImageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();
         $productUrl = $product->getProductUrl();
         return $productUrl;
    }

     public function getProductCollection($productId)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addFieldToFilter('entity_id', array('eq' => $productId));
        $collection->setPageSize(3); // fetching only 3 products
        return $collection;
    }

    public function getItemImage($productId)
    {
        try {
            $_product = $this->productrepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return 'product not found';
        }
        $image_url = $this->imageHelper->init($_product, 'product_base_image')->getUrl();
        return $image_url;
    } 
    public function getProductbyId($productId)
    {
        $_product = $this->productrepository->getById($productId);
        
        return $_product;
    }
}