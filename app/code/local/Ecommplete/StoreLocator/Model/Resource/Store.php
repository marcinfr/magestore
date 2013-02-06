<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class Ecommplete_StoreLocator_Model_Resource_Store extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('ecommplete_storeLocator/store', 'id');
    }
}