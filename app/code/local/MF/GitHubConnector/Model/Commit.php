<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class MF_GitHubConnector_Model_Commit extends Mage_Core_Model_Abstract
{
    const NOT_PUBLISHED_STATUS = 0;
    const MANUALLY_PUBLISHED_STATUS = 1;
    const AUTOMATICALLY_PUBLISHED_STATUS = 2;
    
    protected $_commitFullData;
    protected $_files;

    public function __construct($args = array())
    {
		$this->_init('mf_gitHubConnector/commit');
    }
    
    public function getCommitFullData()
    {
        if (is_null($this->_commitFullData)) {
            $this->_commitFullData = Mage::getModel('mf_gitHubConnector/gitHubApi_v3')->getDataByUrl($this->getUrl());
        }
        return $this->_commitFullData;
    }
    
    public function getFiles()
    {
        if (is_null($this->_files)) {
            $this->_files = array();
            $fullData = $this->getCommitFullData();
            if (isset($fullData['files'])) {
                $files = $fullData['files'];
            } else {
                return $this->_files;
            }
            
            foreach($files as $file) {
                $fileModel = Mage::getModel('mf_gitHubConnector/commit_file');
                foreach($file as $key => $value) {
                    $fileModel->setData($key, $value);
                }
                $this->_files[] = $fileModel;
            }
        }
        
        return $this->_files;
    }
    
    public function isAutomaticallyPublished()
    {
        if ($this->getStatus() == self::AUTOMATICALLY_PUBLISHED_STATUS) {
            return true;
        }
        return false;
    }
    
    public function isManuallyPublished()
    {
        if ($this->getStatus() == self::MANUALLY_PUBLISHED_STATUS) {
            return true;
        }
        return false;
    }
    
    public function isPublished()
    {
        return ($this->isAutomaticallyPublished() || $this->isManuallyPublished());
    }
    
    public function getTextStatus()
    {
        switch ($this->getStatus()) {
            case self::MANUALLY_PUBLISHED_STATUS:
                return Mage::helper('mf_gitHubConnector')->__('Published (manually)');
            case self::AUTOMATICALLY_PUBLISHED_STATUS:
                return Mage::helper('mf_gitHubConnector')->__('Published (automatically)');
            case self::NOT_PUBLISHED_STATUS:
                return Mage::helper('mf_gitHubConnector')->__('Not Published');
            default:
                return Mage::helper('mf_gitHubConnector')->__('Unknown');
        }
    }
}