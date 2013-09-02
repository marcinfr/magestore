<?php

class Alekseon_DataFlow_Model_ScheduleCreator extends Mage_Core_Model_Abstract
{
    protected function _getScheduleConfig($scheduleCode)
    {
        $config = Mage::getSingleton('alekseon_dataFlow/config');
        $schedules = $config->getSchedules();
        
        if (isset($schedules[$scheduleCode])) {
            $scheduleConfig = $schedules[$scheduleCode];
        } else {
            Mage::throwException('Cannot find base configuration for schedule ' . $scheduleCode);
            return;
        }
        return $scheduleConfig;
    }

    public function create($scheduleCode)
    {
        $scheduleConfig = $this->_getScheduleConfig($scheduleCode);

        $schedule = Mage::getModel('alekseon_dataFlow/schedule')->load($scheduleCode, 'code');
        if (!$schedule->getId()) {
            foreach($scheduleConfig as $field => $value) {
                if (is_array($value)) {
                    $value = serialize($value);
                }
                $schedule->setData($field, $value);
            }
        
            $schedule->setCode($scheduleCode);
            $schedule->save();
        }        
        
        return $schedule;
    }
}