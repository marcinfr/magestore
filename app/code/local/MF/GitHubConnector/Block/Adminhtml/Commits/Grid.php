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
    }

	protected function _prepareColumns()
    {
	    $this->addColumn('committer_date', 
			array(
				'header' => Mage::helper('mf_gitHubConnector')->__('Commited Date'),
				'index'  => 'committer_date',
                'type' => 'datetime',
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
        
	    $this->addColumn('publisher_date', 
			array(
				'header' => Mage::helper('mf_gitHubConnector')->__('Published Date'),
				'index'  => 'publisher_date',
                'type' => 'datetime',
        ));
        
	    $this->addColumn('publisher_name', 
			array(
				'header' => Mage::helper('mf_gitHubConnector')->__('Publisher Name'),
				'index'  => 'publisher_name',
        ));
        
		return parent::_prepareColumns();
	}

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('mf_gitHubConnector/commit')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array('id'=>$row->getId()));
    }    
    
}
