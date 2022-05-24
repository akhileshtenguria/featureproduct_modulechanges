<?php
/**
 * Created By : RH
 */
namespace Zestardtech\Categorytree\Block\Adminhtml\Tab;

use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\Store;

class Categorygrid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Zestardtech\Featureproduct\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context    $context
     * @param \Magento\Backend\Helper\Data               $backendHelper
     * @param \Magento\Catalog\Model\ProductFactory      $productFactory
     * @param \Magento\Framework\Registry                $coreRegistry
     * @param \Magento\Framework\Module\Manager          $moduleManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Visibility|null                            $visibility
     * @param array                                      $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        // \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollFactory,
        // \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollFactory,
        \Zestardtech\Categorytree\Model\ResourceModel\Assigncategory\CollectionFactory $assignCateFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        Visibility $visibility = null,
        array $data = []
    ) {
        $this->productFactory = $productFactory;
        $this->assignCateFactory = $assignCateFactory;
        $this->coreRegistry = $coreRegistry;
        $this->moduleManager = $moduleManager;
        $this->_storeManager = $storeManager;
        $this->categoryFactory = $categoryFactory;
        $this->visibility = $visibility ?: ObjectManager::getInstance()->get(Visibility::class);
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * [_construct description]
     * @return [type] [description]
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('rh_grid_category');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
         $this->setPagerVisibility(false);
        /*if ($this->getRequest()->getParam('entity_id')) {
            $this->setDefaultFilter(['in_products' => 1]);
        } else {
            $this->setDefaultFilter(['in_products' => 0]);
        }*/
        $this->setSaveParametersInSession(true);
    }

    /**
     * [get store id]
     *
     * @return Store
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    
       protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in category flag
        // echo $column->getId(); 
        // die; 
        if ($column->getId() == 'in_categories') {
            $categoryIds = $this->_getSelectedCategories();
            if (empty($categoryIds)) {
                $categoryIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $categoryIds]);
            } elseif (!empty($categoryIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $categoryIds]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Apply various selection filters to prepare the category grid collection.
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->categoryFactory->create()
            ->getCollection()
            ->addAttributeToSelect('*');

        $collection->getSelect()
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['entity_id']);
            
        $collection->addAttributeToSelect('*')
            ->addFieldToFilter('name', ["neq"=>null])
            ->addFieldToFilter('is_active', 1)
            ->addAttributeToFilter('level' , 2);
            
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @inheritdoc
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            "in_categories",
            [
                "type"     => "checkbox",
                "name"     => "in_categories",
                "align"    => "left",
                "width"    => "100px",
                "index"    => "entity_id",
                "values"   => $this->_getSelectedCategories(),
                "header"   => __("Select"),
                "sortable" => false
            ]
        );
        $this->addColumn(
            "entity_id",
            [
                "type"     => "number",
                "align"    => "left",
                "width"    => "30px",
                "index"    => "entity_id",
                "header"   => __("Category ID")
            ]
        );

        $this->addColumn(
            "name",
            [
                "index"    => "name",
                "align"    => "left",
                "header"   => __("Category Name")
            ]
        );
        $this->addColumn(
            'position',
            [
                'header' => __('Position'),
                'type' => 'number',
                'index' => 'position',
                'editable' => true,
                'filter' => false,
                // 'validate_class' => 'validate-number'
                // 'editable' => $this->getProductsPosition()


            ]
        );

    

     /*   $this->addColumn(
            "inv",
            [
                "type"     => "input",
                "class"    => "number_check loyalty_points",
                "width"    => "150px",
                "align"    => "center",
                "index"    => "inv",
                "filter"   => false,
                "header"   => __("Loyalty Points"),
                "sortable" => false,
                "renderer" => \Vendor\CustomModule\Block\Adminhtml\Points::class
            ]
        );*/
        
        return parent::_prepareColumns();
    }

    /**
     * @inheritdoc
     */
   

    /**
     * @return array
     */
    protected function _getSelectedCategories()
    {
        $categoryIds = [];
        $catId      = $this->getRequest()->getParam("id");
       
        $pointsCollection = $this->assignCateFactory->create()->addFieldToFilter('category_id', $catId);
       
        foreach ($pointsCollection as $each) {
            $categoryIds[] = $each->getAssignCategoryId();
        }
        // print_r($categoryIds);
        return $categoryIds;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('categorytree/index/grids', ['_current' => true]);
    }

    /**
     * @return array
     */
    /*protected function _getSelectedProducts()
    {
        $products = $this->getSelectedProducts();
        // print_r($products);
        // die; 
        return $products;
    }*/

    /**
     * @return array
     */
  /*  protected function getProductsPosition()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->productCollFactory->create()->addFieldToFilter('category_id', $id);
        $positions = [];
        foreach ($model as $key => $value) {
            $positions[] = $value->getPosition();
        }
        
        // echo '<pre>'; print_r($positions); die;
         // print_r($positions);
        // die; 
        return $positions;
    }*/

    /**
     * @return array
     */
    public function getSelectedProducts()
    {        
      /*  $id = $this->getRequest()->getParam('id');
        $collection = $this->categoryCollFactory->create();
        
        $grids = [];
        foreach ($collection as $key => $value) {
            $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $category = $_objectManager->create('Magento\Catalog\Model\Category')->load($value->getId());
            // echo ;s
            // print_r($value->getData()); 
            $grids[] = $category->getId();
        }*/
        // echo "getSelectedProducts";
        // print_r($grids); die; 
 /*
        $prodId = [];
        foreach ($grids as $obj) {
            $prodId[$obj] = ['position' => "12"];
        }
        return $prodId;*/
        // return $grids;
    }

   /* public function getSelectedProducts()
    {
        //echo "<pre>"; print_r($this->getRequest()->getParams()); die;
        $id = $this->getRequest()->getParam('id');
        $model = $this->productCollFactory->create()->addFieldToFilter('category_id', $id);
        echo "<pre>";
        print_r($model->getData()); 
        die; 
        $grids = [];
        foreach ($model as $key => $value) {
            $grids[] = array(
                'product_id' => $value->getProductId(),
                'position' => $value->getPosition(),
            );
        }
        
        return $grids;
    }*/

}