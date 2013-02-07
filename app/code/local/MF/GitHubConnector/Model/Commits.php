<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class MF_GitHubConnector_Model_Commits extends Mage_Core_Model_Abstract
{
    public function __construct($args = array())
    {
		$this->_init('mf_gitHubConnector/commits');                
    }
}