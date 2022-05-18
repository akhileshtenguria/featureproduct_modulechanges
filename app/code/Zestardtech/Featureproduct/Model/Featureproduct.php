<?php

namespace Zestardtech\Featureproduct\Model;

use Magento\Cron\Exception;
use Magento\Framework\Model\AbstractModel;

/**
 * Contact Model
 *
 * @author      Pierre FAY
 */
class Featureproduct extends AbstractModel
{



    const TBL_FEATURED_PRODUCTS = 'zestard_featured_products';
    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $_dateTime;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Zestardtech\Featureproduct\Model\ResourceModel\Featureproduct::class);
    }


        public function getProducts(\Zestardtech\Featureproduct\Model\Featureproduct $object, $entityId)
    {
        //echo "<Pre>"; print_r($object->getData()); die;
        $tbl = $this->getResource()->getTable(
            \Zestardtech\Featureproduct\Model\ResourceModel\Featureproduct::TBL_FEATURED_PRODUCTS
        );
        $select = $this->getResource()->getConnection()->select()
        ->from(
            $tbl,
            ['product_id']
        )->where(
            'category_id = ?',
            $entityId
        );

        //echo '<pre>'; print_r($select->__toString()); die;
        //echo '<pre>'; print_r($this->getResource()->getConnection()->fetchCol($select)); die;
        return $this->getResource()->getConnection()->fetchCol($select);
    }

    
}
