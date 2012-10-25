<?php

/**
 * PluginLeaveEntitlement
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginLeaveEntitlement extends BaseLeaveEntitlement
{
    const ENTITLEMENT_TYPE_ADD = 1;
    
    public function getAvailableDays() {
        $available = $this->getNoOfDays();        
        $daysUsed = $this->getDaysUsed();
        
        if (!empty($daysUsed)) {
            $available -= $daysUsed;
        }
        
        return $available;
    }
    
    public function withinPeriod($date) {
        $fromTimestamp = strtotime($this->getFromDate());
        $toTimestamp = strtotime($this->getToDate());
        $timestamp = strtotime($date);
        
        if (($timestamp >= $fromTimestamp) && ($timestamp <= $toTimestamp)) {
            return true;
        } else {
            return false;
        }
    }
}