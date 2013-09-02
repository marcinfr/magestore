<?php

class Alekseon_DataFlow_Model_Schedule_Configuration_Form extends Varien_Data_Form
{
    public function addField($elementId, $type, $config, $after=false)
    {
        $profile = $this->getForm()->getProfile();
        $fieldset = $this->addFieldset(
            'profile_' . $profile->getCode(),
            array('legend' => 'Profile: ' . $profile->getLabel())
        );
        return $fieldset->addField($elementId, $type, $config, $after);
    }

    public function addFieldset($elementId, $config, $after=false)
    {
        $elementId = 'schedule_config_fieldset_' . $elementId;
        $element = $this->getElement($elementId);
        if ($element === null) {
            $element = Mage::getModel('alekseon_dataFlow/schedule_configuration_form_element_fieldset', $config);
            $element->setId($elementId);
            $this->addElement($element, $after);
        }
        
        return $element;
    }
}