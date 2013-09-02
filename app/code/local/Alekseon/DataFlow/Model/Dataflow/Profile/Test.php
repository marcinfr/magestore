<?php

class Alekseon_DataFlow_Model_DataFlow_Profile_Test extends Alekseon_DataFlow_Model_DataFlow_Profile_Abstract
{
    public function getLabel()
    {
        return 'Test';
    }

    public function getNote()
    {
        return 'just test';
    }

    public function run()
    {
        echo 'run';
    }
    
    public function addConfiguration($form)
    {
        //$generalFieldset = $form->addFieldset(
         //   'general',
         //   array('legend' => 'General')
        //);
        
        $form->addField('code', 'text', 
            array(
                'label' => 'Code',
                'name'  => 'code',
            )
        );
    }
}