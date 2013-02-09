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
    protected $_filepath;
    protected $_conflictHelper;
    
    protected function _construct()
    {
        $this->_conflictHelper = Mage::helper('mf_gitHubConnector/conflict');
        return parent::_construct();
    }
    
    public function getFilepath()
    {
        if (is_null($this->_filepath)) {
            $filename = $this->getFilename();
            $filename = implode(DS, explode('/', $filename));
            $filename = implode(DS, explode('\\', $filename));
            $this->_filepath = BP . DS . $filename;
        }
        return $this->_filepath;
    }
    
    public function publish()
    {
        // TODO: check conflicts and if proper action is selected
    
        return $this->_publish();
    }
    
    protected function _publish()
    {
        Mage::throwExcaption(Mage::helper('mf_gitHubConnector')->__('Can\'t publish file with status ') . $this->getStatus());
    }
    
    public function revertChanges()
    {
        return $this->_revertChanges();
    }
    
    protected function _revertChanges()
    {
        Mage::throwExcaption(Mage::helper('mf_gitHubConnector')->__('Can\'t revert changes  for file with status ') . $this->getStatus());
    }

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
            $this->_conflicts = $this->_checkConflicts();
        }
        
        return $this->_conflicts;
    }

    public function getConflictActionOptions()
    {
        $conflicts = $this->getConflicts();
        $actions = $this->_conflictHelper->getConflictActions($conflicts);

        $actionOptions = array();
        foreach($actions as $key => $action) {
            $actionOptions[$key] = $action['label'];
        }
        
        return $actionOptions;
    }
    
    protected function _checkConflicts()
    {
        return array($this->_conflictHelper->getConflict(MF_GitHubConnector_Helper_Conflict::CONFLICT_TYPE_WRONG_STATUS));
    }

    protected function _doCopy()
    {
        $copiesDirectory = BP . DS . 'GitHubCopiesDirectory' . DS;

        if (!is_dir($copiesDirectory)) {
            mkdir($copiesDirectory);
        }

        $copyDirectory = $copiesDirectory . substr(md5($this->getFilename()), 0, 2) . DS;
        
        if (!is_dir($copyDirectory)) {
            mkdir($copyDirectory);
        }

        $copyFilename = time() . '_' . $this->getCommit()->getSha() . '_' . md5($this->getFilename()) . '.copy';
        copy($this->getFilepath(), $copyDirectory . $copyFilename);
    }
    
    protected function _getPatches()
    {
        $patch = $this->getPatch();
    }
}