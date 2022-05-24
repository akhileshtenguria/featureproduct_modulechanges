<?php

namespace Zestardtech\Categorytree\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Contact Resource Model
 *
 * @author      Pierre FAY
 */
class Assigncategory extends AbstractDb
{

    const TBL_ASSIGN_CATEGORY = 'zestard_assign_category';
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('zestard_assign_category', 'id');
    }
}