<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

/**
 * Description of LeaveChangeSubscriberMailProcessor
 *
 */
class LeaveChangeSubscriberMailProcessor extends LeaveChangeMailProcessor {
    
    public function getRecipients($data) {

        $events = array_keys($data['changes']);
        $recipients = array();
        
        foreach ($events as $event) {
            if ($event == LeaveEvents::LEAVE_APPROVE) {
                $eventRecipients = parent::getSubscribers(EmailNotification::LEAVE_APPROVAL);
            } else if ($event == LeaveEvents::LEAVE_CANCEL) {
                $eventRecipients = parent::getSubscribers(EmailNotification::LEAVE_CANCELLATION);
            } else if ($event == LeaveEvents::LEAVE_REJECT) {
                $eventRecipients = parent::getSubscribers(EmailNotification::LEAVE_REJECTION);
            }
            
            // check if already there in recipients:
            foreach ($eventRecipients as $new) {
                $found = false;
                
                foreach ($recipients as $existing) {
                    if ($existing->getEmail() == $new->getEmail()) {
                        $found = true;
                        break;
                    }
                }
                
                if (!$found) {
                    $recipients[] = $new;
                }
            }
            
        }
        
        return $recipients;
    }

    public function getReplacements($data) {
        $data['request'] = $data['days'][0]->getLeaveRequest();
        $replacements = parent::getReplacements($data);
        return $replacements;

    }
}

