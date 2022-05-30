<?php 

namespace Zestardtech\Categorytree\ViewModel; 
 
use Magento\Framework\View\Element\Block\ArgumentInterface; 
use Magento\Catalog\Helper\ImageFactory as HelperFactory;
use Magento\Framework\View\Asset\Repository;
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku;
 
class OptimizationPlp implements ArgumentInterface 
{

        protected $_registry;

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
            \Magento\Backend\Block\Template\Context $context,
            \Magento\Framework\App\Request\Http $request,
            \Magento\Framework\Registry $registry,
            \Magento\Store\Model\StoreManagerInterface $storeManager,
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
            \Magento\Catalog\Model\CategoryRepository $categoryRepository,
            \Magento\Framework\App\ResourceConnection $resource,
            HelperFactory $helperFactory,
            Repository $repository,
            GetSalableQuantityDataBySku $getSalableQuantityDataBySku
        ) {
            $this->_registry = $registry;
            $this->_request = $request;
            $this->_storeManager = $storeManager;
            $this->_scopeConfig = $scopeConfig;
            $this->categoryRepository = $categoryRepository;
            $this->_resource = $resource;
            $this->helperFactory = $helperFactory;
            $this->assetRepos = $repository;
            $this->getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
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




                 
}