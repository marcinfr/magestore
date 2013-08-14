<?php

class Alekseon_DataFlow_Block_Adminhtml_Schedule_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('schedule_tabs');
        $this->setDestElementId('schedule_form');
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'general_settings',
            array(
                'label'     => $this->__('General Settings'),
                'title'     => $this->__('General Settings'),
                'content'   => $this->getLayout()
                    ->createBlock(
                        'alekseon_dataFlow/adminhtml_schedule_edit_tabs_general'
                    )
                    ->toHtml(),
            )
        );
        
        $this->addTab(
            'parameters',
            array(
                'label'     => $this->__('Parameters'),
                'title'     => $this->__('Parameters'),
                'content'   => '',
            )
        );
        
        $this->addTab(
            'executions',
            array(
                'label'     => $this->__('Executions'),
                'title'     => $this->__('Executions'),
                'content'   => '',
            )
        );
        
        $this->addTab(
            'history',
            array(
                'label'     => $this->__('History'),
                'title'     => $this->__('History'),
                'content'   => '',
            )
        );
        
        $this->addTab(
            'statistics',
            array(
                'label'     => $this->__('Statistics'),
                'title'     => $this->__('Statistics'),
                'content'   => '',
            )
        );
        
        $this->addTab(
            'run',
            array(
                'label'     => $this->__('Run'),
                'title'     => $this->__('Run'),
                'content'   => '',
            )
        );

        return parent::_beforeToHtml();
    }
}