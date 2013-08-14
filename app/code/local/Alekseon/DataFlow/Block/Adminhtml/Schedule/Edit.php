<?php

class Alekseon_DataFlow_Block_Adminhtml_Schedule_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'alekseon_dataFlow';
        $this->_controller = 'adminhtml_schedule';

        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Save schedule'));
        $this->_removeButton('delete');
    }

    public function getHeaderText()
    {
        $schedule = Mage::registry('current_schedule');
        if ($schedule->getId()) {
            return $this->__('Schedule') . ': ' . $schedule->getName();
        } else {
            return $this->__('New Schedule');
        }
    }
}