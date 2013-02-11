<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class MF_GitHubConnector_Model_Commit_FileAdded extends MF_GitHubConnector_Model_Commit_File
{
    protected function _publish()
    {
        $this->_createDirectories();
        $this->_saveResultFile();
    }
    
    protected function _revertChanges()
    {
    }

    protected function _checkConflicts()
    {
        $path = $this->getFilepath();
        
        if (file_exists($path)) {
            $conflict = $this->_conflictHelper->getConflict(MF_GitHubConnector_Helper_Conflict::CONFLICT_TYPE_FILE_EXISTS);
            return array($conflict);
        }
        
        return $this->_checkPatches();
    }
}