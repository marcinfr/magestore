<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class Ecommplete_StoreLocator_Block_Adminhtml_Store extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'ecommplete_storeLocator';
        $this->_controller = 'adminhtml_store';
        $this->_headerText = Mage::helper('ecommplete_storeLocator')->__('Manage Stores');
        $this->_addButtonLabel = Mage::helper('ecommplete_storeLocator')->__('Add New Store');
        parent::__construct();
    }

}
