<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
 class MF_GitHubConnector_Helper_Data extends Mage_Core_Helper_Abstract
 {
    public function updateCommits()
    {
        $commits = array();
        $commitsCollection = Mage::getModel('mf_gitHubConnector/commits')->getCollection();
        foreach($commitsCollection as $commit) {
            $commits[$commit->getSha()] = true;
        }
            
        $newCommits = array();
            
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
                $newCommits[] = $newCommit;
            }
        }
        
        return $newCommits;
    }
 }