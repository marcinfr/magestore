<?php

class Alekseon_DataFlow_Model_DataFlow_Profile_Test2 extends Alekseon_DataFlow_Model_DataFlow_Profile_Abstract
{
    public function getLabel()
    {
        return 'Test2';
    }

    public function getNote()
    {
        return 'just test 2';
    }

    public function run()
    {
        echo 'run';
    }
    
    public function addConfiguration($form)
    {
        $generalFieldset = $form->addFieldset(
            'general',
            array('legend' => 'General')
        );
        
        $generalFieldset->addField('code', 'text', 
            array(
                'label' => 'Code',
                'name'  => 'code',
            )
        );
    }
}