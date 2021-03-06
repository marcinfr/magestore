<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class MF_GitHubConnector_Adminhtml_GitHubConnector_CommitsController extends Mage_Adminhtml_Controller_Action
{

    protected function _initCommit()
    {
        $commitId = (int) $this->getRequest()->getParam('id');
        $commit = Mage::getModel('mf_gitHubConnector/commit');

        if ($commitId) {
            $commit->load($commitId);
        }

        Mage::register('current_commit', $commit);
        return $this;
    }

    public function indexAction()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }    
    
        $this->loadLayout();
        $this->_setActiveMenu('system/github_connector/commits');
        $this->_addContent(
            $this->getLayout()->createBlock('mf_gitHubConnector/adminhtml_commits')
        );        
        $this->renderLayout();
    }
    
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('mf_gitHubConnector/adminhtml_commits_grid')->toHtml());
    }

    public function updateCommitsAction()
    {
        try {
            $newCommits = Mage::helper('mf_gitHubConnector')->updateCommits();
            $this->_getSession()->addSuccess(count($newCommits) . $this->__(' new commits.'));
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/index');
    }
    
    public function viewAction()
    {
        $this->_initCommit();
        $commit = Mage::registry('current_commit');
        
        if (!$commit->getId()) {
            $this->_getSession()->addError($this->__('Wrong commit Id.'));
            $this->_redirect('*/*/index', array('id' => $commit->getId()));
        } else {
            $this->loadLayout();
            $this->_addContent($this->getLayout()->createBlock('mf_gitHubConnector/adminhtml_commits_view'));
            $this->renderLayout();
        }
    }
    
    public function confirmAction()
    {
        $this->_initCommit();
        $commit = Mage::registry('current_commit');
        
        if (!$commit->getId()) {
            $this->_getSession()->addError($this->__('Wrong commit Id.'));
            $this->_redirect('*/*/index', array('id' => $commit->getId()));
        } else if ($commit->isPublished()) {
            $this->_getSession()->addError($this->__('This commit has been already published.'));
            $this->_redirect('*/*/view', array('id' => $commit->getId()));
        } else if ($commit->getConnectionError()) {
            $this->_getSession()->addError($this->__('Connection error:') . ' ' . $commit->getConnectionError());
            $this->_redirect('*/*/view', array('id' => $commit->getId()));        
        } else {
            $this->loadLayout();
            $this->_addContent($this->getLayout()->createBlock('mf_gitHubConnector/adminhtml_commits_confirm'));
            $this->renderLayout();
        }
    }
    
    public function publishAction()
    {
        $this->_initCommit();
        $commit = Mage::registry('current_commit');
        
        try {
            $commit->publish();
            $this->_getSession()->addSuccess($this->__('Commit has been published.'));
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/view', array('id' => $commit->getId()));
    }
    
    public function setAsPublishedAction()
    {
        $this->_initCommit();
        $commit = Mage::registry('current_commit');
        
        if (!$commit->getId()) {
            $this->_getSession()->addError($this->__('Wrong commit Id.'));
            $this->_redirect('*/*/index', array('id' => $commit->getId()));
        } else if ($commit->isPublished()) {
            $this->_getSession()->addError($this->__('This commit has been already published.'));
            $this->_redirect('*/*/view', array('id' => $commit->getId()));
        } else {
            try {
                $commit->setAsPublished(MF_GitHubConnector_Model_Commit::MANUALLY_PUBLISHED_STATUS);
                $commit->save();
                $this->_getSession()->addSuccess($this->__('Commit has been set as published.'));
            }  catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            $this->_redirect('*/*/view', array('id' => $commit->getId()));
        }
    }
}