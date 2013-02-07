<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class MF_GitHubConnector_Adminhtml_GitHubConnector_CommitsController extends Mage_Adminhtml_Controller_Action
{

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
            $commits = array();
            $commitsCollection = Mage::getModel('mf_gitHubConnector/commits')->getCollection();
            foreach($commitsCollection as $commit) {
                $commits[$commit->getSha()] = true;
            }
            
            $counter = 0;
            
            $gitHubcommits = Mage::getModel('mf_gitHubConnector/gitHubApi_v3')->getCommitsList();
            foreach($gitHubcommits as $gitHubcommit) {
                $sha = $gitHubcommit['sha'];
                if (!isset($commits[$sha])) {
                    //var_dump($gitHubcommit); die();
                    $newCommit = Mage::getModel('mf_gitHubConnector/commits');
                    $newCommit->setSha($gitHubcommit['sha']);
                    $newCommit->setUrl($gitHubcommit['url']);
                    $newCommit->setCommitterName($gitHubcommit['commit']['committer']['name']);
                    $newCommit->setCommitterEmail($gitHubcommit['commit']['committer']['email']);
                    $newCommit->setCommitterDate($gitHubcommit['commit']['committer']['date']);
                    $newCommit->setMessage($gitHubcommit['commit']['message']);
                    $newCommit->save();
                    $counter++;
                }
            }
            $this->_getSession()->addSuccess($counter . $this->__(' new commits.'));
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/index');
    }
}