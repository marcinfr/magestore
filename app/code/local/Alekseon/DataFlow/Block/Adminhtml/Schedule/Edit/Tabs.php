<?php

class Alekseon_DataFlow_Block_Adminhtml_Schedule_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('schedule_tabs');
        $this->setDestElementId('edit_form');
    }

    protected function _beforeToHtml()
    {
        $this->_addGeneralTab();
        $this->_addConfigurationTab();
        $this->_addExecutionsTab();
        $this->_addHistoryTab();       
        $this->_addStatsTab();
        $this->_addConsoleTab();

        return parent::_beforeToHtml();
    }
    
    protected function _addGeneralTab()
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
    }
    
    protected function _addConfigurationTab()
    {
        $this->addTab(
            'configuration',
            array(
                'label'     => $this->__('Configuration'),
                'title'     => $this->__('Configuration'),
                'content'   => $this->getLayout()
                    ->createBlock(
                        'alekseon_dataFlow/adminhtml_schedule_edit_tabs_configuration'
                    )
                    ->toHtml(),
            )
        );
    }
    
    protected function _addExecutionsTab()
    {
        $this->addTab(
            'executions',
            array(
                'label'     => $this->__('Executions'),
                'title'     => $this->__('Executions'),
                'content'   => $this->getLayout()
                    ->createBlock(
                        'alekseon_dataFlow/adminhtml_schedule_edit_tabs_executions'
                    )
                    ->toHtml(),
            )
        );
    }
    
    protected function _addHistoryTab()
    {
        $this->addTab(
            'history',
            array(
                'label'     => $this->__('History'),
                'title'     => $this->__('History'),
                'content'   => 'Not implemented.',
            )
        );
    }
     
    protected function _addStatsTab()
    {
        $this->addTab(
            'statistics',
            array(
                'label'     => $this->__('Statistics'),
                'title'     => $this->__('Statistics'),
                'content'   => 'Not implemented.',
            )
        );
    }
    
    protected function _addConsoleTab()
    {    
        $this->addTab(
            'manual_run',
            array(
                'label'     => $this->__('Console'),
                'title'     => $this->__('Console'),
                'content'   => $this->getLayout()
                    ->createBlock(
                        'alekseon_dataFlow/adminhtml_schedule_edit_tabs_console'
                    )
                    ->toHtml(),
            )
        );
    }
}