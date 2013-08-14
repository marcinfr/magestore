<?php

class Alekseon_DataFlow_Model_Mysql4_Schedule_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('alekseon_dataFlow/schedule');
    }

    public function load($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        
        $config = Mage::getSingleton('alekseon_dataFlow/config');
        $schedules = $config->getSchedules();
        
        $scheduleCodes = array_keys($schedules);
        $this->addFieldToFilter('code', array('in' => $scheduleCodes));
        
        parent::load();
        
        $loadedSchedulesCodes = array();
        
        foreach($this as $schedule) {
            $loadedSchedulesCodes[] = $schedule->getCode();
        }
           
        $notLoadedSchedulesCodes = array_diff($scheduleCodes, $loadedSchedulesCodes);

        if (!empty($notLoadedSchedulesCodes)) {
            foreach($notLoadedSchedulesCodes as $scheduleCode) {
                Mage::getSingleton('alekseon_dataFlow/scheduleCreator')->create($scheduleCode);
            }
            $this->_reset();
            parent::load();
        }
        
        $this->_setIsLoaded();
        
        return $this;
    }
}