<?php

namespace Zestardtech\Categorytree\Model;

use Magento\Cron\Exception;
use Magento\Framework\Model\AbstractModel;

/**
 * Contact Model
 *
 * @author      Pierre FAY
 */
class Assigncategory extends AbstractModel
{



    const TBL_ASSIGN_CATEGORY = 'zestard_assign_category';
    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $_dateTime;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Zestardtech\Categorytree\Model\ResourceModel\Assigncategory::class);
    }


    public function getCategory(\Zestardtech\Categorytree\Model\Assigncategory $object, $entityId)
    {
       // echo "testsss";  echo "<Pre>"; print_r($object->getData()); die;
        $tbl = $this->getResource()->getTable(
            \Zestardtech\Categorytree\Model\ResourceModel\Assigncategory::TBL_ASSIGN_CATEGORY
        );
        $select = $this->getResource()->getConnection()->select()
        ->from(
            $tbl,
            ['assign_category_id']
        )->where(
            'category_id = ?',
            $entityId
        );

        // echo '<pre>'; print_r($select->__toString()); die;
        //echo '<pre>'; print_r($this->getResource()->getConnection()->fetchCol($select)); die;
        return $this->getResource()->getConnection()->fetchCol($select);
    }

    
}
