<?php

class Alekseon_DataFlow_Model_Mysql4_Schedule extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('alekseon_dataFlow/alekseon_dataflow_shedule', 'id');
    }
}