<?php
namespace Zestardtech\Categorytree\Block;

class Brands extends \Magento\Framework\View\Element\Template
{        
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,      
        array $data = []
    )
    {   
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context, $data);
    }
    
    public function getBrandCollection()
    {
        // echo "tests"; die; 

        $categoryId = 7;
        $category = $this->categoryRepository->get($categoryId);
        $subCategories = $category->getChildrenCategories();
        return $subCategories; 
        

    }
    
}