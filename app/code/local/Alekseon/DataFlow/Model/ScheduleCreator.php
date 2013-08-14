<?php

class Alekseon_DataFlow_Model_ScheduleCreator extends Mage_Core_Model_Abstract
{

    public function create($scheduleCode)
    {
        $config = Mage::getSingleton('alekseon_dataFlow/config');
        $schedules = $config->getSchedules();
        
        if (isset($schedules[$scheduleCode])) {
            $scheduleConfig = $schedules[$scheduleCode];
        } else {
            Mage::throwException('Cannot find base configuration for schedule ' . $scheduleCode);
            return;
        }

        $newSchedule = Mage::getModel('alekseon_dataFlow/schedule')->load($scheduleCode, 'code');
        if (!$newSchedule->getId()) {
            foreach($scheduleConfig as $field => $value) {
                $newSchedule->setData($field, $value);
            }
        
            $newSchedule->setCode($scheduleCode);
            $newSchedule->save();
        }
        
        return $newSchedule;
    }

}