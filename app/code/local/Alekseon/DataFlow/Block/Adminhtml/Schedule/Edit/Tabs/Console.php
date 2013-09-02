<?php

class Alekseon_DataFlow_Block_Adminhtml_Schedule_Edit_Tabs_Console extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('alekseon/dataflow/console.phtml');
    }

    protected function _getSchedule()
    {
        return Mage::registry('current_schedule');
    }

    public function getManualRunButtonHtml()
    {
        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('save')
            ->setLabel($this->__('Run'))
            ->setOnClick('run()')
            ->toHtml();
        return $html;
    }
    
    public function getManualRunUrl()
    {
        echo $this->getUrl('*/dataFlow_console/manualRun', array('id'=>$this->_getSchedule()->getId()));
    }
}