<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class MF_GitHubConnector_Model_GitHubApi_V3
{
    protected $_user = 'marcinfr';
    protected $_repo = 'magestore';
    protected $_token;

    public function getCommitsList()
    {
        $path = $this->_getRepoPath() . '/commits';
        $result = $this->_doRequest($path);
        return Mage::helper('core')->jsonDecode($result);
    }
    
    protected function _authorization()
    {
        https://github.com/login/oauth/authorize
        
    }
    
    protected function _getRepoPath()
    {
        return 'repos/' . $this->_user . '/' . $this->_repo;
    }

    protected function _doRequest($path)
    {
        $url = 'https://api.github.com/' . trim($path, '/');
        $httpClient = new Zend_Http_Client($url);
        $response = $httpClient->request(Zend_Http_Client::GET);
        return $response->getBody();
    }
}