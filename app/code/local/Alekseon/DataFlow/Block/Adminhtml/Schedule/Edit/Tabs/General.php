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

        if ($this->_getSchedule()->getId()) {
            $generalFieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }
        
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

        $profilesFieldset = $form->addFieldset(
            'profiles',
            array('legend' => $this->__('Profiles'))
        );

        if ($this->_getSchedule()) {
            foreach($this->_getSchedule()->getProfiles() as $code => $profile) {
                $profileModel = $this->_getSchedule()->getProfileModel($code);
                if ($profileModel) {
                    $note = $profileModel->getNote();
                    $label = $profileModel->getLabel();
                } else {
                    $label = false;
                    $note = '<span style="color: red;">Unable to load this profile</span>';
                }
            
                $profilesFieldset->addField('profile_' . $code, 'label', 
                    array(
                        'label' => $label ? $label : 'Profile ' . $code,
                        'note'  => $note,
                    )
                );
            }
        }
        
        $semaphoresFieldset = $form->addFieldset(
            'semaphores',
            array('legend' => $this->__('Semaphores'))
        );

        $data = $this->_getSchedule()->getData();
        foreach($this->_getSchedule()->getProfiles() as $code => $profile) {
            $data['profile_' . $code] = $profile;
        }

        $form->setValues($data);
        
        return parent::_prepareForm();
    }
}