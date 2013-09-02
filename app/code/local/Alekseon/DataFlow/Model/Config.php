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
                $defaultConfig = array();

                foreach($scheduleConfig->default->children() as $field => $value) {
                    $defaultConfig[$field] = $value->asArray();
                }

                $this->_schedules[$code] = $defaultConfig;
            }
        }
    }
    
    public function getDefaultConfiguration($schedule)
    {
        $schedules = $this->getSchedules();
        $defaultConfiguration = array();
        
        if (isset($schedules[$schedule->getCode()])) {
            $scheduleConfig = $schedules[$schedule->getCode()];

            if (isset($scheduleConfig['config'])) {
                foreach($scheduleConfig['config'] as $profileCode => $configs) {
                    foreach($configs as $elementId => $value) {
                        $configuration = Mage::getModel('alekseon_dataFlow/schedule_configuration');
                        $configuration->setValue($value);
                        $configuration->setElementId($elementId);
                        $configuration->setProfileCode($profileCode);
                        $defaultConfiguration[] = $configuration;
                    }
                }
            }
        }

        return $defaultConfiguration;
    }    
}