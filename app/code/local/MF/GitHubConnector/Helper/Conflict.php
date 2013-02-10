<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class MF_GitHubConnector_Helper_Conflict extends Mage_Core_Helper_Abstract
{
    const CONFLICT_TYPE_WRONG_STATUS = 1;
    const CONFLICT_TYPE_FILE_EXISTS = 2;
    const CONFLICT_TYPE_FILE_NOT_EXISTS = 3;
    const CONFLICT_TYPE_FILE_NOT_WRITABLE = 4;
    const CONFLICT_TYPE_FILE_NO_CHANGES = 5;
    const CONFLICT_TYPE_FILE_CONFLICTED = 6;

    const CONFLICT_ACTION_SKIP = 'skip';
    const CONFLICT_ACTION_OVERWRITE = 'overwrite';
    const CONFLICT_ACTION_CREATE = 'create';
    const CONFLICT_ACTION_REMOVE = 'remove';
    
    protected function _getConflictActions()
    {
        return array(
            self::CONFLICT_ACTION_SKIP => array(
                'label' => $this->__('Skip this file'),
            ),
            self::CONFLICT_ACTION_OVERWRITE => array(
                'label' => $this->__('Overwrite source file'),
            ), 
            self::CONFLICT_ACTION_REMOVE => array(
                'label' => $this->__('Remove source file'),
            ),
            self::CONFLICT_ACTION_CREATE => array(
                'label' => $this->__('Create file'),
            ),
        );
    }
    
    protected function _getConflictTypes()
    {
        return array(
            self::CONFLICT_TYPE_WRONG_STATUS => array(
                'label' => $this->__('Wrong status.'),
                'actions' => array(
                    self::CONFLICT_ACTION_SKIP,
                ),
            ),    
            self::CONFLICT_TYPE_FILE_EXISTS => array(
                'label' => $this->__('File already exists.'),
                'actions' => array(
                    self::CONFLICT_ACTION_SKIP,
                    self::CONFLICT_ACTION_OVERWRITE,
                ),
            ),
            self::CONFLICT_TYPE_FILE_NOT_EXISTS => array(
                'label' => $this->__('File doesn\'t exist.'),
                'actions' => array(
                    self::CONFLICT_ACTION_SKIP,
                    self::CONFLICT_ACTION_CREATE,
                ),
            ),
            self::CONFLICT_TYPE_FILE_NOT_WRITABLE => array(
                'label' => $this->__('File is not writable.'),
                'actions' => array(
                    self::CONFLICT_ACTION_SKIP,
                ),
            ),
            self::CONFLICT_TYPE_FILE_NO_CHANGES => array(
                'label' => $this->__('Changes seems already published.'),
                'actions' => array(
                    self::CONFLICT_ACTION_SKIP,
                ),
            ),
            self::CONFLICT_TYPE_FILE_CONFLICTED => array(
                'label' => $this->__('Changes are conflicted.'),
                'actions' => array(
                    self::CONFLICT_ACTION_SKIP,
                    self::CONFLICT_ACTION_OVERWRITE,
                ),
            ),
        );
    }
    
    public function getConflict($conflictCode)
    {
        $conflicts = $this->_getConflictTypes();
        if (isset($conflicts[$conflictCode])) {
            return $conflicts[$conflictCode];
        }
        
        return array();
    }
    
    public function getConflictActions($conflicts)
    {
        if (empty($conflicts)) {
            return array();
        }
    
        $firstConflict = array_shift($conflicts);
        $actions = $firstConflict['actions'];
        foreach($conflicts as $conflict) 
        {
            $nextActions = $conflict['actions'];
            $actions = array_intersect($actions, $nextActions);
        }
        
        $conflictActionsArray = $this->_getConflictActions();
        
        $conflictActions = array();
        
        foreach($actions as $action) 
        {
            $conflictActions[] = $conflictActionsArray[$action];
        }
        
        return $conflictActions;
    }
}