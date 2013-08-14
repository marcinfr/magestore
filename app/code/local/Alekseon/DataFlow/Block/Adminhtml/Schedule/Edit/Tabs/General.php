<?php

class Alekseon_DataFlow_Block_Adminhtml_Schedule_Edit_Tabs_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _getSchedule()
    {
        return Mage::registry('current_schedule');
    }

    protected function _prepareForm() 
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $generalFieldset = $form->addFieldset(
            'general',
            array('legend' => $this->__('General'))
        );

        $generalFieldset->addField('code', 'text', 
            array(
                'label' => $this->__('Code'),
                'name'  => 'code',
            )
        );
        
        $generalFieldset->addField('name', 'text', 
            array(
                'label' => $this->__('Name'),
                'name'  => 'name',
            )
        );
        
        $semaphoresFieldset = $form->addFieldset(
            'semaphores',
            array('legend' => $this->__('Semaphores'))
        );

        $form->setValues($this->_getSchedule()->getData());
        
        return parent::_prepareForm();
    }
}