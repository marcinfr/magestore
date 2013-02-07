<?php
/**
 * @author    Marcin Frymark
 * @email     marcin.frymark@gmail.com
 */
class MF_GitHubConnector_Block_Adminhtml_Commits_Grid extends Mage_Adminhtml_Block_Widget_Grid
{ 

   public function __construct()
    {
        parent::__construct();
        $this->setId('gitHub_commits_grid');
        $this->setSaveParametersInSession(true);   
        $this->setUseAjax(true);
        $this->setPagerVisibility(false);
    }

	protected function _prepareColumns()
    {
	    $this->addColumn('committer_date', 
			array(
				'header' => Mage::helper('mf_gitHubConnector')->__('Date'),
				'index'  => 'committer_date',
        ));
    
	    $this->addColumn('sha', 
			array(
				'header' => Mage::helper('mf_gitHubConnector')->__('Sha'),
				'index'  => 'sha',
        ));

	    $this->addColumn('message', 
			array(
				'header' => Mage::helper('mf_gitHubConnector')->__('Message'),
				'index'  => 'message',
        ));
        
	    $this->addColumn('committer_name', 
			array(
				'header' => Mage::helper('mf_gitHubConnector')->__('Committer Name'),
				'index'  => 'committer_name',
        ));
        
	    $this->addColumn('committer_email', 
			array(
				'header' => Mage::helper('mf_gitHubConnector')->__('Committer Email'),
				'index'  => 'committer_email',
        ));
        
	    $this->addColumn('status', 
			array(
				'header' => Mage::helper('mf_gitHubConnector')->__('Status'),
				'index'  => 'status',
        ));
        
		return parent::_prepareColumns();
	}

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('mf_gitHubConnector/commits')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    public function getRowUrl($item)
    {
        return false;
    }    
    
}
