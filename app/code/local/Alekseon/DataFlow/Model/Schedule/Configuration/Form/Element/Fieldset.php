<?php

class Alekseon_DataFlow_Model_Schedule_Configuration_Form_Element_Fieldset extends Varien_Data_Form_Element_Fieldset
{
    public function addField($elementId, $type, $config, $after=false)
    {
        $profileCode = $this->getForm()->getProfile()->getCode();
        $elementId = 'schedule_config_' . $profileCode . '_' . $elementId;
        if (isset($config['name'])) {
            $config['name'] = 'schedule_config[' . $profileCode . '][' . $config['name'] . ']';
        }
        return parent::addField($elementId, $type, $config, $after);
    }
}