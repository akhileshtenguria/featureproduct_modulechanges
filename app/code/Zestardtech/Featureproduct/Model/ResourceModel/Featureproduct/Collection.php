<?php

namespace Zestardtech\Featureproduct\Model\ResourceModel\Featureproduct;

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
        $this->_init('Zestardtech\Featureproduct\Model\Featureproduct', 'Zestardtech\Featureproduct\Model\ResourceModel\Featureproduct');
    }
}
