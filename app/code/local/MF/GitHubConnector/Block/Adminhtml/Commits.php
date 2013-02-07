<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
 class MF_GitHubConnector_Block_Adminhtml_Commits extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'mf_gitHubConnector';
        $this->_controller = 'adminhtml_commits';
        $this->_headerText = Mage::helper('mf_gitHubConnector')->__('GitHub Commits');
        parent::__construct();
        $this->removeButton('add');
        
        $this->_addButton('update_commits', array(
            'label'     => $this->__('Update Commits'),
            'onclick'   => 'setLocation(\'' . $this->getUpdateCommitsUrl() .'\')',
        ));
    }
    
    public function getUpdateCommitsUrl()
    {
        return $this->getUrl('*/*/updateCommits');
    }

}