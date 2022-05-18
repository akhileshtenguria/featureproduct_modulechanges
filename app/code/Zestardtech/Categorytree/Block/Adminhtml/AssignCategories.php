<?php
/**
 * Created By : RH
 */
namespace Zestardtech\Categorytree\Block\Adminhtml;

class AssignCategories extends \Magento\Backend\Block\Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'category/assign_categories.phtml';

    /**
     * @var \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
     */
    protected $blockGrid;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var \Zestardtech\Featureproduct\Model\ResourceModel\Product\CollectionFactory
     */
    protected $categoryFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context                           $context
     * @param \Magento\Framework\Registry                                       $registry
     * @param \Magento\Framework\Json\EncoderInterface                          $jsonEncoder
     * @param \Zestardtech\Featureproduct\Model\ResourceModel\Product\CollectionFactory $productFactory
     * @param array                                                             $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryFactory,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve instance of grid block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                'Zestardtech\Categorytree\Block\Adminhtml\Tab\Categorygrid',
                'rh.custom.tab.categorygrid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * Return HTML of grid block
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }

    /**
     * @return string
     */
    public function getProductsJson()
    {
        // $entity_id = $this->getRequest()->getParam('id');
        // die("sdfsdfs"); 
        // $categoryFactory = $this->categoryFactory->create();
        // $categoryFactory->addFieldToSelect('*');
          // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $categoryFactory = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');
        $categories = $categoryFactory->create();   
        $categories->addAttributeToSelect('*');     
        $categories->addAttributeToFilter('level' , 2); 
        
       
        
        $this->_options  = [];
        foreach($categories as $cat) {
                $catId = $cat->getId();
                $category = $objectManager->create('Magento\Catalog\Model\Category')->load($catId);
                // print_r($category->getData()); 
                // die; 
                $result[$category->getId()] = $category->getName();
            }
            //return json_encode($result);  
             return $this->jsonEncoder->encode($result);
        
        // return '{}';
    }


    /*
    public function getItem()
    {
        return $this->registry->registry('my_item');
    }
    */

}