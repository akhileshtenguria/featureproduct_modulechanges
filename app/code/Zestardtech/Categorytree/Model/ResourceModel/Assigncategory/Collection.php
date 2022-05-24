<?php

namespace Zestardtech\Categorytree\Model\ResourceModel\Assigncategory;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Contact Resource Model Collection
 *
 * @author      Pierre FAY
 */
class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Zestardtech\Categorytree\Model\Assigncategory', 'Zestardtech\Categorytree\Model\ResourceModel\Assigncategory');
    }
}
