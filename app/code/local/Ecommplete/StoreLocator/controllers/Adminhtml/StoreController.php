<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class Ecommplete_StoreLocator_Adminhtml_StoreController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Stores'))->_title($this->__('Manage Stores'));

        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }
        
        $this->loadLayout();
        
        $this->_addContent(
            $this->getLayout()->createBlock('ecommplete_storeLocator/adminhtml_store', 'store')
        );

        $this->renderLayout();
    }

    public function gridAction()
    {
    }
}