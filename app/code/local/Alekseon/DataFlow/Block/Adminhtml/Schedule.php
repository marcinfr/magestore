<?php

 class Alekseon_DataFlow_Block_Adminhtml_Schedule extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'alekseon_dataFlow';
        $this->_controller = 'adminhtml_schedule';
        $this->_headerText = Mage::helper('alekseon_dataFlow')->__('Schedules');
        parent::__construct();
    }
}