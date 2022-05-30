<?php

namespace Zestardtech\Categorytree\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;


class Index extends Action
{
    /**
     * The PageFactory to render with.
     *
     * @var PageFactory
     */
    protected $_resultsPageFactory;

    /**
     * Set the Context and Result Page Factory from DI.
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->_resultsPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Show the Hello World Index Page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute() {

       /* $resultPage = $this->_resultsPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__(' heading '));

        $block = $resultPage->getLayout()
                ->createBlock('Magento\Framework\View\Element\Template')
                ->setTemplate('Magento_Theme::html/test.phtml')
                ->toHtml();
        $this->getResponse()->setBody($block);*/
        // echo "Rajesh Pal"; 
        return $this->_resultsPageFactory->create();
    }
}