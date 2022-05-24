<?php

namespace Zestardtech\Categorytree\Block\Adminhtml\Custom;

class Tab extends \Magento\Backend\Block\Template
{
    // protected $_template = 'catalog/category/tab.phtml';
    protected $_template = 'category/assign_categories.phtml';
    
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
}
?>  