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
    protected $_sourceFileContent;
    protected $_resultFileContent;
    
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
        $conflicts = $this->_checkConflicts();
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
        $patchLines = explode("\n", $this->getPatch());
        
        $patches = array();
        $patch = null;
        
        foreach($patchLines as $line) {
            if (substr($line, 0, 2) == '@@') {
                if (!is_null($patch)) {
                    $patches[] = $patch;
                }
                list($empty, $hunk, $hunkLine) = explode('@@', $line);
                $hunk = trim($hunk);
                
                list($hunk1, $hunk2) = explode(' ', $hunk);
                
                list($afterPointer) = explode(',', $hunk2);
                list($beforePointer) = explode(',', $hunk1);
                $afterPointer = abs((int)$afterPointer);
                $beforePointer = abs((int)$beforePointer);
            
                $patch = array(
                        'beforePointer' => $beforePointer - 1,
                        'afterPointer' => $afterPointer - 1,
                        'lines'   => array(),
                    );
                continue;
            }
            
            $patch['lines'][] = $line;
        }

        if (!is_null($patch)) {
            $patches[] = $patch;
        }

        return $patches;
    }
    
    protected function _getSourceFileContent()
    {
        if (is_null($this->_sourceFileContent)) {
            return $this->_sourceFileContent = file($this->getFilepath());
        }
        
        return $this->_sourceFileContent;
    }
    
    protected function _checkPatches()
    {
        $patches = $this->_getPatches();
        $sourceContent = $this->_getSourceFileContent();
        
        $this->_resultFileContent = array();
        
        $sourcePointer = 0;
        $resultPointer = 0;
        $offset = 0;
        
        foreach($patches as $patch) {
            for($i = $sourcePointer; $i < $patch['beforePointer'] + $offset; $i++) {
                $this->_resultFileContent[$resultPointer] = $sourceContent[$i];
                $resultPointer++;
                $sourcePointer++;
            }
            $conflict = null;
            foreach($patch['lines'] as $line) {
                if ($line[0] == '-') {
                    $line = substr($line, 1);
                    if (!isset($sourceContent[$sourcePointer]) || trim($line) != trim($sourceContent[$sourcePointer])) {
                        $offset--;
                        if (!$conflict) {
                            $conflict = MF_GitHubConnector_Helper_Conflict::CONFLICT_TYPE_FILE_NO_CHANGES;
                        }
                    }
                } else if ($line['0'] == '+') {
                    $line = substr($line, 1);
                    if (isset($sourceContent[$sourcePointer]) && trim($line) == trim($sourceContent[$sourcePointer])) {
                        if (!$conflict) {
                            $conflict = MF_GitHubConnector_Helper_Conflict::CONFLICT_TYPE_FILE_NO_CHANGES;
                        }
                        $sourcePointer++;
                        $offset++;
                        continue;
                    }
                    $this->_resultFileContent[$resultPointer] = $line;
                    $resultPointer++;
                } else {
                    if (isset($sourceContent[$sourcePointer]) && trim($line) == trim($sourceContent[$sourcePointer])) {
                        $this->_resultFileContent[$resultPointer] = $line;
                    } else {
                        $conflict = MF_GitHubConnector_Helper_Conflict::CONFLICT_TYPE_FILE_CONFLICTED;
                    }
                    $sourcePointer++;
                    $resultPointer++;
                }
            }
        }
        
        if ($conflict) {
            return array($this->_conflictHelper->getConflict($conflict));
        }
        
        while(isset($sourceContent[$sourcePointer])) {
            $this->_resultFileContent[$resultPointer] = $sourceContent[$sourcePointer];
            $resultPointer++;
            $sourcePointer++;
        }
        
        return array();
    }
    
    protected function _getResultFileContent()
    {
        if (!$this->_resultFileContent) {
            $conflicts = $this->_checkPatches();
        }
        
        return $this->_resultFileContent;
    }
}