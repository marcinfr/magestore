<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class MF_GitHubConnector_Block_Adminhtml_Commits_View extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function getCommit()
    {
        return Mage::registry('current_commit');
    }

    public function __construct()
    {
        $this->_mode = 'view';
        $this->_blockGroup = 'mf_gitHubConnector';
        $this->_controller = 'adminhtml_commits';

        parent::__construct();

        $this->_removeButton('save');
        $this->_removeButton('reset');
        $this->_removeButton('delete');
        
        if (!$this->getCommit()->isPublished()) {
            $this->_addButton('set_as_published', array(
                'label'     => Mage::helper('mf_gitHubConnector')->__('Set As Published'),
                'onclick'   => "setLocation('{$this->getUrl('*/*/')}')",
            ));
        
            if (!$this->getCommit()->getConnectionError()) {
                $this->_addButton('publish', array(
                    'label'     => Mage::helper('mf_gitHubConnector')->__('Publish'),
                    'onclick'   => "setLocation('{$this->getUrl('*/*/confirm', array('id' => $this->getCommit()->getId()))}')",
                    'class'     => 'save',
                ));
            }
        }
    }

    public function getHeaderText()
    {
        return Mage::helper('mf_gitHubConnector')->__('View Commit');
    }
}