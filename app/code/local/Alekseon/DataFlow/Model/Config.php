<?php

class Alekseon_DataFlow_Model_Config extends Mage_Core_Model_Abstract
{
    protected $_schedules;

    public function getSchedules()
    {
        if (is_null($this->_schedules)) {
            $this->_initSchedules();
        }
        
        return $this->_schedules;
    }
    
    protected function _initSchedules()
    {
        $config = Mage::getConfig()->loadModulesConfiguration('schedules.xml');
        $schedulesNode = $config->getNode('schedules');
        $this->_schedules = array();
        if ($schedulesNode) {
            foreach($schedulesNode->children() as $code => $scheduleConfig) {
                $this->_schedules[$code] = $scheduleConfig;
            }
        }
    }
}