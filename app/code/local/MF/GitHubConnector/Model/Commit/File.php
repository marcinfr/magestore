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
    
    public function restoreFile()
    {
        return $this->_restoreFile();
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

    protected function _getCopyDirectoryName()
    {
        $copiesDirectory = BP . DS . 'GitHubCopiesDirectory' . DS;
        
        if (!is_dir($copiesDirectory)) {
            mkdir($copiesDirectory);
        }
        
        $copyDirectory = $copiesDirectory . substr(md5($this->getFilename()), 0, 2) . DS;
        
        if (!is_dir($copyDirectory)) {
            mkdir($copyDirectory);
        }
        
        return $copyDirectory;
    }
    
    protected function _getCopyName()
    {
        $copyFilename = /* time() . '_' . */ $this->getCommit()->getSha() . '_' . md5($this->getFilename()) . '.copy';
        $copyDirectory = $this->_getCopyDirectoryName();
        return $copyDirectory . $copyFilename;
    }
    
    protected function _doCopy()
    {
        copy($this->getFilepath(), $this->_getCopyName());
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
            if (file_exists($this->getFilepath())) {
                return $this->_sourceFileContent = file($this->getFilepath());
            } else {
                return array();
            }
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

        foreach($patches as $patch) {
            for($i = $sourcePointer; $i < $patch['beforePointer']; $i++) {
                if (isset($sourceContent[$i])) {
                    $this->_resultFileContent[$resultPointer] = $sourceContent[$i];
                } else {
                    $conflict = MF_GitHubConnector_Helper_Conflict::CONFLICT_TYPE_FILE_CONFLICTED;
                    return array($this->_conflictHelper->getConflict($conflict));
                }
                $resultPointer++;
                $sourcePointer++;
            }

            foreach($patch['lines'] as $line) {
                if ($line[0] == '-') {
                    $line = substr($line, 1);
                    if (isset($sourceContent[$sourcePointer]) && trim($line) == trim($sourceContent[$sourcePointer])) {
                        $sourcePointer++;
                    } else {
                        $conflict = MF_GitHubConnector_Helper_Conflict::CONFLICT_TYPE_FILE_CONFLICTED;
                        return array($this->_conflictHelper->getConflict($conflict));
                    }
                } else if ($line['0'] == '+') {
                    $line = substr($line, 1);
                    $this->_resultFileContent[$resultPointer] = $line . "\n";
                    $resultPointer++;
                } else if ($line == '\ No newline at end of file') {
                } else {
                    $line = substr($line, 1);
                    if (isset($sourceContent[$sourcePointer]) && trim($line) == trim($sourceContent[$sourcePointer])) {
                        $this->_resultFileContent[$resultPointer] = $line . "\n";
                    } else {
                        $conflict = MF_GitHubConnector_Helper_Conflict::CONFLICT_TYPE_FILE_CONFLICTED;
                        return array($this->_conflictHelper->getConflict($conflict));
                    }
                    $sourcePointer++;
                    $resultPointer++;
                }
            }
        }

        while(isset($sourceContent[$sourcePointer])) {
            if ($this->getStatus() == self::FILE_STATUS_REMOVED && trim($sourceContent[$sourcePointer])) { /* it means that something has been added to the end */
                $conflict = MF_GitHubConnector_Helper_Conflict::CONFLICT_TYPE_FILE_CONFLICTED;
                 return array($this->_conflictHelper->getConflict($conflict));
            }
        
            $this->_resultFileContent[$resultPointer] = $sourceContent[$sourcePointer];
            $resultPointer++;
            $sourcePointer++;
        }
        
        return array();
    }
    
    protected function _getResultFileContent()
    {
        if (!$this->_resultFileContent) {
            $conflicts = $this->_checkConflicts();
            if ($this->isConflicted()) {
                Mage::throwException(Mage::helper('mf_gitHubConnector')->__('Detected Conflicts.'));
            }
        }
        
        return $this->_resultFileContent;
    }
    
    protected function _saveResultFile()
    {
        $filepath = $this->getFilepath();
        $contents = $this->_getResultFileContent();
        
        if ($this->isConflicted()) {
            Mage::throwException(Mage::helper('mf_gitHubConnector')->__('Detected Conflicts.'));
        }

        file_put_contents($filepath, $contents);
    }
    
    protected function _restoreFile()
    {
        if (file_exists($this->_getCopyName())) {
            $this->_createDirectories();
            copy($this->_getCopyName(), $this->getFilepath());
        } else {
            Mage::throwException(Mage::helper('mf_gitHubConnector')->__("Copy of file %s, does not exists.", $this->getFilepath()));
        }
    }
    
    protected function _createDirectories()
    {
        $filepath = $this->getFilepath();
        $directories = explode(DS, $filepath);
        array_pop($directories); // remove last element, filename
        $path = implode(DS, $directories);
        if (!file_exists($path) || !is_dir($path)) {
            mkdir($path, '0777', true);
        }
    }
    
    protected function _removeSourceFile()
    {
        $filepath = $this->getFilepath();
        unlink($filepath);
        
        /* remove empty directories */
        try {
            $directories = explode(DS, $filepath);
            array_pop($directories); // remove last element, filename
            $path = implode(DS, $directories);
        
            while(count(scandir($path)) == 2) {
                rmdir($path);
                $directories = explode(DS, $path);
                array_pop($directories); // remove last element, filename
                $path = implode(DS, $directories);
            }
        } catch (Exception $e) {
            $this->_restoreFile();
            Mage::throwException($e->getMessage());
        }
    }
}