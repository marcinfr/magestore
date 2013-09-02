<?php

class Alekseon_DataFlow_Model_Schedule extends Mage_Core_Model_Abstract
{
    protected $_configuration;
    protected $_profileModels = array();
    
    public function __construct($args = array())
    {
		$this->_init('alekseon_dataFlow/schedule');
    }
    
    protected function _afterLoad()
    {
        if (parent::getProfiles()) {
            $this->setProfiles(unserialize(parent::getProfiles()));
        }
        
        return parent::_afterLoad();
    }
    
    public function getProfiles()
    {        
        $profiles = parent::getProfiles();
        if (is_array($profiles)) {
            return $profiles;
        } else {
            return array();
        }
    }
    
    public function getProfileModel($profileCode)
    {
        if (!isset($this->_profileModels[$profileCode])) {
            $profiles = $this->getProfiles();
            $profileModel = false;
            
            if (isset($profiles[$profileCode])) {
                try {
                    $profileModel = Mage::getModel($profiles[$profileCode]);
                    $profileModel->setCode($profileCode);
                } catch(Exception $e) {}
            }
            
            $this->_profileModels[$profileCode] = $profileModel;
        }
        
        return $this->_profileModels[$profileCode];
    }

    public function getScheduleConfiguration()
    {
        if (is_null($this->_configuration)) {
            $this->_configuration = array();

            $defaultConfiguration = Mage::getSingleton('alekseon_dataFlow/config')->getDefaultConfiguration($this);
        
            foreach($defaultConfiguration as $configuration) {
                $profileCode = $configuration->getProfileCode();
                $elementId   = $configuration->getElementId();
                $this->_configuration[$profileCode][$elementId] = $configuration;
            }
        
            if ($this->getId()) {
                $dbConfiguration = Mage::getModel('alekseon_dataFlow/schedule_configuration')->getCollection()
                    ->addFieldToFilter('schedule_id', $this->getId());
              
                foreach($dbConfiguration as $configuration) {
                    $profileCode = $configuration->getProfileCode();
                    $elementId   = $configuration->getElementId();
                    $this->_configuration[$profileCode][$elementId] = $configuration;
                }
            }
        }
        
        return $this->_configuration;
    }
    
    public function getScheduleConfig($profileCode, $elementId, $asObject = false)
    {
        $configuration = $this->getScheduleConfiguration();
        if (array_key_exists($profileCode, $configuration)) {
            if (array_key_exists($elementId, $configuration[$profileCode])) {
                $config = $configuration[$profileCode][$elementId];
                if ($asObject) {
                    return $config;
                } else {
                    return $config->getValue();
                }
            }
        }
        
        if ($asObject) {
            $config = Mage::getModel('alekseon_dataFlow/schedule_configuration');
            $config->setProfileCode($profileCode);
            $config->setElementId($elementId);
            return $config;
        } else {
            return null;
        }
    }
    
    protected function _loadConfiguration()
    {

    }

    public function manualRun()
    {
        $execution = Mage::getModel('alekseon_dataFlow/schedule_execution');
        $execution->setScheduleId($this->getId());
        $execution->setSchedule($this);
        $execution->setType(Alekseon_DataFlow_Model_Schedule_Execution::EXECUTION_TYPE_MANUAL);
        $execution->execute();               
    }    
}