<?php

class Alekseon_DataFlow_Model_Schedule_Execution extends Mage_Core_Model_Abstract
{
    const EXECUTION_TYPE_AUTO    = 0;
    const EXECUTION_TYPE_ONESHOT = 1;
    const EXECUTION_TYPE_MANUAL  = 2;
    
    const EXECUTION_STATUS_PENDING = 0;
    const EXECUTION_STATUS_RUNNING = 1;
    const EXECUTION_STATUS_SUCCESS = 2;
    const EXECUTION_STATUS_FAILED  = 3;    
    const EXECUTION_STATUS_SKIPPED = 4;

    public function __construct($args = array())
    {
		$this->_init('alekseon_dataFlow/schedule_execution');
    }
    
    protected function _beforeSave()
    {
        if (!$this->getId()) {
            $user = Mage::getSingleton('admin/session')->getUser();
            if ($user->getId()) {
                $user = $user->getName();
            } else {
                $user = Mage::helper('alekseon_dataFlow')->__('System');
            }
            $this->setUser($user);
        }
        
        return parent::_beforeSave();
    }
    
    public function getTypeOptions()
    {
        return array(
            self::EXECUTION_TYPE_AUTO    => Mage::helper('alekseon_dataFlow')->__('Automatic'),
            self::EXECUTION_TYPE_ONESHOT => Mage::helper('alekseon_dataFlow')->__('One Shot'),
            self::EXECUTION_TYPE_MANUAL  => Mage::helper('alekseon_dataFlow')->__('Manual'),
        );
    }
    
    public function getStatusOptions()
    {
        return array(
            self::EXECUTION_STATUS_PENDING  => Mage::helper('alekseon_dataFlow')->__('Pending'),
            self::EXECUTION_STATUS_RUNNING  => Mage::helper('alekseon_dataFlow')->__('Running'),
            self::EXECUTION_STATUS_SUCCESS  => Mage::helper('alekseon_dataFlow')->__('Success'),
            self::EXECUTION_STATUS_FAILED   => Mage::helper('alekseon_dataFlow')->__('Failed'),
            self::EXECUTION_STATUS_SKIPPED  => Mage::helper('alekseon_dataFlow')->__('Skipped'),
        );
    }    
    
    public function isManual()
    {
        return $this->getType() == self::EXECUTION_TYPE_MANUAL;
    }

    public function execute()
    {
        $this->setExecutedAt(now());
        $this->setStatus(self::EXECUTION_STATUS_RUNNING);
        $this->save();               
        
        try {        
            $this->_runProfile();
            $this->setStatus(self::EXECUTION_STATUS_SUCCESS);
            $this->setFinishedAt(now());
            $this->save();
        } catch (Exception $e) {
            $this->setStatus(self::EXECUTION_STATUS_FAILED);
            $this->setMessage($e->getMessage());
            $this->setFinishedAt(now());
            $this->save();
            if ($this->isManual()) {
                Mage::throwException($e);
            }
        }
    }
        
    protected function _getSchedule()
    {
        if (!$this->getScheduleId()) {
            Mage::throwException('Schedule ID is NULL.');
            return;
        }
        
        if ($this->getSchedule()) {
            $schedule = Mage::getModel('alekseon_dataFlow/schedule')->load($this->getScheduleId());
            $this->setSchedule($schedule);
        }
        
        if ($this->getSchedule()->getId() == $this->getScheduleId()) {
            return $this->getSchedule();
        }
        
        Mage::throwException('Wrong schedule model.');
        return;
    }
    
    protected function _runProfile()
    {
        $inputOutput = Mage::getModel('alekseon_dataFlow/schedule_inputOutput');

        foreach($this->_getSchedule()->getProfiles() as $code => $profile) {
            $profileModel = $this->_getSchedule()->getProfileModel($code);

            if (!is_object($profileModel)) {
                Mage::throwException('Unable to load profile: ' . $profile);
                return;
            }
        
            $profileModel->run($inputOutput);
        }
    }
}