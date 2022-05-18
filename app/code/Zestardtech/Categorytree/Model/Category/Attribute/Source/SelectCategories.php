<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Zestardtech\Categorytree\Model\Category\Attribute\Source;

use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\Data\CategoryTreeInterface;
use Magento\Catalog\Api\CategoryManagementInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class SelectCategories extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
     /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var CategoryManagementInterface
     */
    private $categoryManagement;
    /**
     * @var ExtensibleDataObjectConverter
     */
    private $objectConverter;

    /**
     * Categories constructor.
     * @param StoreManagerInterface $storeManager
     * @param RequestInterface $request
     * @param CategoryManagementInterface $categoryManagement
     * @param ExtensibleDataObjectConverter $objectConverter
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        RequestInterface $request,
        CategoryManagementInterface $categoryManagement,
        ExtensibleDataObjectConverter $objectConverter
    ) {
        $this->storeManager = $storeManager;
        $this->request = $request;
        $this->categoryManagement = $categoryManagement;
        $this->objectConverter = $objectConverter;
    }

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        /*if ($this->_options === null) {
            $this->_options = [
                ['value' => (string) 'test', 'label' => __('test')],
                ['value' => (string) 'test2', 'label' => __('test2')],
                ['value' => (string) 'test3', 'label' => __('test3')]
            ];
        }
        return $this->_options;*/

        /*$data = [];
        $rootCategory = $this->getRootCategory();

        
        $tree = $this->categoryManagement->getTree($rootCategory);
        $categoryArray = $this->objectConverter->toNestedArray($tree, [], CategoryTreeInterface::class);
        
        if (count($categoryArray)) {
            $data[] = ['value' => $categoryArray["id"], 'label' => $categoryArray["name"]];
            $this->getArray($data, $categoryArray["children_data"], 1);
        }
        return $data;*/

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $categoryFactory = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');
        $categories = $categoryFactory->create();   
        $categories->addAttributeToSelect('*');     
        $categories->addAttributeToFilter('level' , 2); 
        
        $this->_options  = [];
        foreach($categories as $cat){
            $catId = $cat->getId();
            $category = $objectManager->create('Magento\Catalog\Model\Category')->load($catId);
             $this->_options[] = ['value' => (string) $category->getId(), 'label' => $category->getName()];
        }

         return $this->_options;

    }

    /*public function getRootCategory()
    {
        $websiteId = $this->request->getParam("website", null);
        $store = $this->storeManager->getStore();
        if ($websiteId) {

            $website = $this->storeManager->getWebsite($websiteId);
            $store = $website->getDefaultStore();
        }

        return $store->getRootCategoryId()+1;
    }

    public function getArray(&$data, $array, $level = 0)
    {
        foreach ($array as $category) {
            $arrow = str_repeat("---", $level);
            $data[] = ['value' => $category["id"], 'label' => __($arrow." ".$category["name"])];
            if ($category["children_data"]) {
                $this->getArray($data, $category["children_data"], $level+1);
            }
        }
    }*/
}

