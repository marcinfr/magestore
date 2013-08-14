<?php

class Alekseon_DataFlow_Adminhtml_DataFlow_ScheduleController extends Mage_Adminhtml_Controller_Action
{
    public function _initSchedule()
    {
        $scheduleId = $this->getRequest()->getParam('id');
        $schedule = Mage::getModel('alekseon_dataFlow/schedule');
        
        if ($scheduleId) {
            $schedule->load($scheduleId);
        }
        
        Mage::register('current_schedule', $schedule);
        
        return $schedule;
    }

    public function indexAction()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }  
    
        $this->loadLayout();
        $this->_setActiveMenu('alekseon_data_flow/shedule');
        $this->_addContent(
            $this->getLayout()->createBlock('alekseon_dataFlow/adminhtml_schedule')
        );        
        $this->renderLayout();
    }
    
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('alekseon_dataFlow/adminhtml_schedule_grid')->toHtml());
    }
    
    public function editAction()
    {
        $this->_initSchedule();
    
        $this->loadLayout();
        $this->_addContent(
            $this->getLayout()->createBlock('alekseon_dataFlow/adminhtml_schedule_edit')
        )
        ->_addLeft(
            $this->getLayout()->createBlock('alekseon_dataFlow/adminhtml_schedule_edit_tabs')
        );
            
        $this->renderLayout();
    }
}