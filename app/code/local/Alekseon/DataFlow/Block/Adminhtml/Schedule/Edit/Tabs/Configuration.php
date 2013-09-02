<?php

class Alekseon_DataFlow_Block_Adminhtml_Schedule_Edit_Tabs_Configuration extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _getSchedule()
    {
        return Mage::registry('current_schedule');
    }

    protected function _prepareForm() 
    {
        $form = Mage::getModel('alekseon_dataFlow/schedule_configuration_form');
        
        foreach($this->_getSchedule()->getProfiles() as $profileCode => $profile) {
            $profile = $this->_getSchedule()->getProfileModel($profileCode);
            
            if (!$profile) {
                continue;
            }
            
            $form->setProfile($profile);
            $profile->addConfiguration($form);
        }
        
        $scheduleConfiguration = $this->_getSchedule()->getScheduleConfiguration();
        $data = array();
        
        foreach($scheduleConfiguration as $profileCode => $configs) {
            foreach($configs as $elementId => $configuration) {
                $data['schedule_config_' . $profileCode . '_' . $elementId] = $configuration->getValue();
            }
        }

        $form->setValues($data);
        $this->setForm($form);
        return $this;
    }
}