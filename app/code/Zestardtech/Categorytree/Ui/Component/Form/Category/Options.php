<?php
namespace Zestardtech\Categorytree\Ui\Component\Form\Category;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\App\RequestInterface;

class Options implements OptionSourceInterface
{
    protected $categoryCollectionFactory;
    protected $request;
    protected $categoryTree;


    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory,
        RequestInterface $request
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->request = $request;
    }


    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getCustomerTree();
    }

    /**
     * Retrieve categories tree
     *
     * @return array
     */
    protected function getCustomerTree()
    {
       // if ($this->customerTree === null) {
            $collection = $this->categoryCollectionFactory->create()->load(1);

            // $collection->addAttributeToSelect();

            foreach ($collection as $customer) {

                // echo "<pre>";
                // print_r($customer->getData()); 
                // die; 
                $customerId = $customer->getEntityId();
                if($customerId){

                if (!isset($customerById[$customerId])) {
                    $customerById[$customerId] = [
                        'value' => $customerId
                    ];
                }
                }
                $customerById[$customerId]['label'] = $customer->getName();
            }
            $this->customerTree = $customerById;
        // }
        return $this->customerTree;
    }

}