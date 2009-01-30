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
 require_once ROOT_PATH . '/lib/models/recruitment/JobApplicationEvent.php';

 class EXTRACTOR_ScheduleInterview {

	/**
	 * Parse data from interface and return JobApplicationEvent Object
	 * @param Array $postArr Array containing POST values
	 * @return JobApplicationEvent Job Application Event object
	 */
	public function parseAddData($postArr) {

		$event = new JobApplicationEvent();

        $id = $postArr['txtId'];
        $event->setApplicationId($id);

        $date = $postArr['txtDate'];
        $time = $postArr['txtTime'];
        $dateTime = LocaleUtil::getInstance()->convertToStandardDateTimeFormat($date . ' ' . $time);
        $event->setEventTime($dateTime);

        $interviewer = $postArr['cmbInterviewer'];
        $event->setOwner($interviewer);

        $notes = $postArr['txtNotes'];
        $event->setNotes($notes);

        if (isset($_FILES['fileAttachment1'])) {

            $event->setAttachment1Name($_FILES['fileAttachment1']['name']);
            $event->setAttachment1Type($_FILES['fileAttachment1']['type']);
            $event->setAttachment1Data($this->_getUploadedFile('fileAttachment1'));
        }
        
        if (isset($_FILES['fileAttachment2'])) {
            $event->setAttachment2Name($_FILES['fileAttachment2']['name']);
            $event->setAttachment2Type($_FILES['fileAttachment2']['type']);
            $event->setAttachment2Data($this->_getUploadedFile('fileAttachment2'));
        }

        return $event;
	}
    
    private function _getUploadedFile($name) {
        $tmpName  = $_FILES[$name]['tmp_name'];
        $contents = file_get_contents($tmpName);       
        return $contents;
    }

}
?>