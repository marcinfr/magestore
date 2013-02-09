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
    
    public function publish()
    {
        if (!$this->getId()) {
            Mage::throwException(Mage::helper('mf_gitHubConnector')->__('Wrong commit Id.'));
        }
        if ($this->isPublished()) {
            Mage::throwException(Mage::helper('mf_gitHubConnector')->__('This commit has been already published.'));
        }
        if ($this->getConnectionError()) {
            Mage::throwException(Mage::helper('mf_gitHubConnector')->__('Connection error:') . ' ' . $this->getConnectionError());
        }
        
        $publishedFiles = array();
        
        foreach($this->getFiles() as $file) {
            try{
                $file->publish();
            } catch (Exception $e) {
                $this->_revertChanges($publishedFiles);
                Mage::throwException(Mage::helper('mf_gitHubConnector')->__('Cannot publish: ') . $e->getMessage());
            } catch (Exception $ee) {
                Mage::throwException(Mage::helper('mf_gitHubConnector')->__('Cannot publish: ') . $e->getMessage() . ' ' . $ee->getMessage());
            }
            $publishedFiles[] = $file;
        }
        
        $this->setAsPublished(self::AUTOMATICALLY_PUBLISHED_STATUS);
        $this->save();
    }
    
    public function revertChanges()
    {
        // check conflict first
        
        $this->_revertChanges($this->getFiles());
    }
    
    protected function _revertChanges($files)
    {
        foreach($files as $file) {
            try {
                $file->revertChanges($this);
            } catch (Exception $e) {
                Mage::throwException(Mage::helper('mf_gitHubConnector')->__('Cannot revert changes in file') . ' ' . $file->getFilename() . ': ' . $e->getMessage());
            }
        }
    }
    
    public function getCommitFullData()
    {
        if (is_null($this->_commitFullData)) {
            try {
                $this->_commitFullData = Mage::getModel('mf_gitHubConnector/gitHubApi_v3')->getDataByUrl($this->getUrl());
            } catch (Exception $e) {
                $this->setConnectionError($e->getMessage());
            }
        }
        return $this->_commitFullData;
    }
    
    public function getConnectionError()
    {
        $this->getCommitFullData();
        return $this->getData('connection_error');
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
                switch($file['status']) {
                    case MF_GitHubConnector_Model_Commit_File::FILE_STATUS_ADDED:
                        $fileModel = Mage::getModel('mf_gitHubConnector/commit_fileAdded');
                        break;
                    case MF_GitHubConnector_Model_Commit_File::FILE_STATUS_MODIFIED:
                        $fileModel = Mage::getModel('mf_gitHubConnector/commit_fileModified');
                        break;
                    case MF_GitHubConnector_Model_Commit_File::FILE_STATUS_REMOVED:
                        $fileModel = Mage::getModel('mf_gitHubConnector/commit_fileRemoved');
                        break;
                    default:
                        $fileModel = Mage::getModel('mf_gitHubConnector/commit_file');
                }
                
                foreach($file as $key => $value) {
                    $fileModel->setData($key, $value);
                    $fileModel->setCommit($this);
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
    
    public function setAsPublished($status)
    {
        //$this->setStatus($status);
        
        $user = Mage::getSingleton('admin/session')->getUser();
        if ($user) {
            $publisherName = $user->getFirstname() . ' ' .$user->getLastname();
        } else {
            $publisherName = Mage::helper('mf_gitHubConnector')->__('System');
        }
        $this->setPublisherName($publisherName);
        
        $date = new Zend_Date();
        $date = $date->toString('YYYY-MM-dd HH:mm:ss');
        $this->setPublisherDate($date);
    }
}