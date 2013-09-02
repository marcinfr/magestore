<?php

class Alekseon_DataFlow_Block_Adminhtml_Schedule_Grid extends Mage_Adminhtml_Block_Widget_Grid
{ 

   public function __construct()
    {
        parent::__construct();
        $this->setId('alekseon_dataflow_schedules');
        $this->setSaveParametersInSession(true);   
        $this->setUseAjax(true);
    }

	protected function _prepareColumns()
    {
	    $this->addColumn('code', 
			array(
				'header' => Mage::helper('alekseon_dataFlow')->__('Code'),
				'index'  => 'code',
        ));
        
	    $this->addColumn('name', 
			array(
				'header' => Mage::helper('alekseon_dataFlow')->__('Name'),
				'index'  => 'name',
        ));

		return parent::_prepareColumns();
	}

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('alekseon_dataFlow/schedule')->getCollection();
        $collection->load();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('adminhtml/dataFlow_schedule/edit', array('id' => $row->getId()));
    }    
    
}
