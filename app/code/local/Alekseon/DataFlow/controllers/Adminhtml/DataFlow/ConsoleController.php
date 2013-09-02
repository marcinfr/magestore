<?php

class Alekseon_DataFlow_Adminhtml_DataFlow_ConsoleController extends Mage_Adminhtml_Controller_Action
{
    protected function _initSchedule()
    {
        $scheduleId = $this->getRequest()->getParam('id');
        $schedule = Mage::getModel('alekseon_dataFlow/schedule');
        
        if ($scheduleId) {
            $schedule->load($scheduleId);
        }
        
        Mage::register('current_schedule', $schedule);
        
        return $schedule;
    }

    public function manualRunAction()
    {
        $schedule = $this->_initSchedule();
        $schedule->manualRun();
    }
}