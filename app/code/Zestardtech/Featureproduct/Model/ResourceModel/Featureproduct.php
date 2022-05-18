<?php

namespace Zestardtech\Featureproduct\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Contact Resource Model
 *
 * @author      Pierre FAY
 */
class Featureproduct extends AbstractDb
{

    const TBL_FEATURED_PRODUCTS = 'zestard_featured_products';
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('zestard_featured_products', 'id');
    }
}