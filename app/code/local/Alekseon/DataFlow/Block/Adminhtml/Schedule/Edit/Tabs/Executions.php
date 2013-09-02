<?php

class Alekseon_DataFlow_Block_Adminhtml_Schedule_Edit_Tabs_Executions extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _getSchedule()
    {
        return Mage::registry('current_schedule');
    }
    
    public function __construct()
    {
        parent::__construct();
        $this->setId('schedule_executions_grid');
        $this->setSaveParametersInSession(true);   
        $this->setUseAjax(true);
    }

	protected function _prepareColumns()
    {
	    $this->addColumn('status', 
			array(
				'header' => $this->__('Status'),
				'index'  => 'status',
                'type'    => 'options',
                'options' => Mage::getSingleton('alekseon_dataFlow/schedule_execution')->getStatusOptions(),
        ));

        $this->addColumn('user', 
			array(
				'header' => $this->__('User'),
				'index'  => 'user',
        ));
        
	    $this->addColumn('type', 
			array(
				'header'  => $this->__('Type'),
				'index'   => 'type',
                'type'    => 'options',
                'options' => Mage::getSingleton('alekseon_dataFlow/schedule_execution')->getTypeOptions(),
        ));
        
	    $this->addColumn('message', 
			array(
				'header' => $this->__('Message'),
				'index'  => 'message',
        ));
        
		return parent::_prepareColumns();
	}

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('alekseon_dataFlow/schedule_execution')->getCollection()
                            ->addFieldToFilter('schedule_id', $this->_getSchedule()->getId());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    public function getRowUrl($row)
    {
        return false;
    }
}