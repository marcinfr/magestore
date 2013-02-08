<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class MF_GitHubConnector_Model_Commit_File extends Mage_Core_Model_Abstract
{
    const FILE_STATUS_ADDED    = 'added';
    const FILE_STATUS_MODIFIED = 'modified';
    const FILE_STATUS_REMOVED  = 'removed';
    
    protected $_conflicts;

    public function isConflicted()
    {
        if (count($this->getConflicts())) {
            return true;
        }
        
        return false;
    }
    
    public function getConflicts()
    {
        if (is_null($this->_conflicts)) {
            switch($this->getStatus()) {
                case self::FILE_STATUS_ADDED:
                    $this->_conflicts = $this->_checkAddedFile();
                    break;
                case self::FILE_STATUS_MODIFIED:
                    $this->_conflicts = $this->_checkModifiedFile();
                    break;
                case self::FILE_STATUS_REMOVED:
                    $this->_conflicts = $this->_checkRemovedFile();
                    break;
                default:
                    $this->_conflicts = array(Mage::helper('mf_gitHubConnector')->__('Wrong file status.'));
            }
        }
        
        return $this->_conflicts;
    }

    protected function _checkAddedFile()
    {
        $conflicts = array();
        return $conflicts;
    }
    
    protected function _checkModifiedFile()
    {
        $conflicts = array();
        return $conflicts;
    }
    
    protected function _checkRemovedFile()
    {
        $conflicts = array();
        return $conflicts;
    }
}