<?php

class Alekseon_DataFlow_Model_Mysql4_Schedule_Configuration_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('alekseon_dataFlow/schedule_configuration');
    }
}