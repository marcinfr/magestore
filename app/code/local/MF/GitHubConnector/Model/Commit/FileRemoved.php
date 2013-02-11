<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class MF_GitHubConnector_Model_Commit_FileRemoved extends MF_GitHubConnector_Model_Commit_File
{
    protected function _publish()
    {
        $this->_doCopy();
        $this->_removeSourceFile();
    }
    
    protected function _revertChanges()
    {
    }
 
    protected function _checkConflicts()
    {
        $path = $this->getFilepath();

        if (!file_exists($path)) {
            $conflict = $this->_conflictHelper->getConflict(MF_GitHubConnector_Helper_Conflict::CONFLICT_TYPE_FILE_NOT_EXISTS);
            return array($conflict);
        }
        
        if (!is_writable($path)) {
            $conflict = $this->_conflictHelper->getConflict(MF_GitHubConnector_Helper_Conflict::CONFLICT_TYPE_FILE_NOT_WRITABLE);
            return array($conflict);
        }
        
        return $this->_checkPatches();
    }

}