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
 * Description of LeaveChangeMailProcessor
 *
 */
class LeaveChangeMailProcessor extends LeaveEmailProcessor {
    
    protected function _generateLeaveDetailsTable($data, $replacements) {

        $requestType = isset($data['requestType']) ? $data['requestType'] : 'request';

        // Show individual comments in table if there are any leave dates with comments
        $displayIndividualComments = false;
        if ($requestType == 'multiple' && count($data['days']) > 1) {
            
            foreach ($data['days'] as $leave) {
                $thisLeaveComment = $leave->getLatestCommentAsText();
                if (!empty($thisLeaveComment)) {
                    $displayIndividualComments = true;
                    break;
                }
            }
        }
        
        $details = '';
        
        foreach ($data['changes'] as $action => $change) {
            
            if ($action == LeaveEvents::LEAVE_APPROVE) {
                $details .= "Approved Leave:\n\n";
            } else if ($action == LeaveEvents::LEAVE_CANCEL) {
                $details .= "Cancelled Leave:\n\n";
            } else if ($action == LeaveEvents::LEAVE_REJECT) {
                $details .= "Rejected Leave:\n\n";
            }
            
            // Length of tab (4 spaces) : "    "

            $details .= "Date(s)                Duration (Hours)";
            if ($displayIndividualComments) {
                $details .= "            Comments";
            }
            $details .= "\n";
            $details .= "=========================";
            if ($displayIndividualComments) {
                $details .= "=========================";
            }        

            $details .= "\n";

            foreach ($change as $leave) {

                $leaveDate = set_datepicker_date_format($leave->getDate());
                $leaveDuration = round($leave->getLengthHours(), 2);

                if ($leaveDuration > 0) {

                    $leaveDuration = $this->_fromatDuration($leaveDuration);
                    $details .= "$leaveDate            $leaveDuration";
                    if ($displayIndividualComments) {
                        $details .= "                " . $this->trimComment($leave->getLatestCommentAsText());
                    }
                    $details .= "\n";

                }

            }
            
            $details .= "\n";

        }
        
        $details .= "Leave type : " . $replacements['leaveType'];
        $details .= "\n";

        $leaveComment = '';
        
        if ($requestType == 'request') {
            $leaveComment = $data['request']->getCommentsAsText();
        } elseif ($requestType == 'single') {
            $leaveComment = $data['days'][0]->getCommentsAsText();
        }

        if (!empty($leaveComment)) {
            $details .= "\n\nComments:\n=========\n$leaveComment";
            $details .= "\n";
        }

        return $details;

    }
    
    public function getReplacements($data) {
        $data['request'] = $data['days'][0]->getLeaveRequest();
        $replacements = parent::getReplacements($data);
        return $replacements;

    }    
    
    protected function getSubscribers($emailName, $data) {        

        $workFlow = array_keys($data['workFlow']);
        $recipients = array();

        foreach ($workFlow as $item) {
            $action = $item->getAction();
            $eventRecipients = parent::getSubscribers('leave.' . $action, $data);
            
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
    
}

