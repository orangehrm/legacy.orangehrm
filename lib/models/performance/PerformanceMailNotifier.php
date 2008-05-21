<?php
/**
 *
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
 *
 * @copyright 2006 OrangeHRM Inc., http://www.orangehrm.com
 */

require_once ROOT_PATH . '/lib/common/htmlMimeMail5/htmlMimeMail5.php';
require_once ROOT_PATH . '/lib/models/eimadmin/CountryInfo.php';
require_once ROOT_PATH . '/lib/models/eimadmin/EmailConfiguration.php';
require_once ROOT_PATH . '/lib/models/eimadmin/EmailNotificationConfiguration.php';
require_once ROOT_PATH . '/lib/models/maintenance/Users.php';
require_once ROOT_PATH . '/lib/models/recruitment/JobVacancy.php';
require_once ROOT_PATH . '/lib/models/recruitment/JobApplication.php';
require_once ROOT_PATH . '/lib/confs/sysConf.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpInfo.php';
require_once ROOT_PATH . '/lib/common/Language.php';
require_once ROOT_PATH . '/lib/confs/sysConf.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpRepTo.php';


/**
 * Manages sending of mail notifications
 *
 */
class PerformanceMailNotifier {

	/**
	 * Template file name constants
	 *
	 */
	const TEMPLATE_SUBMITTED_FOR_APPROVAL = 'approval.txt';
	const TEMPLATE_REVIEW_NOTIFICATION = 'review-notification.txt';

	/**
	 * Mail subject templates
	 */
	const SUBJECT_SUBMITTED_FOR_APPROVAL = 'approval-subject.txt';
	const SUBJECT_REVIEW_NOTIFICATION = 'review-notification-subject.txt';

	/**
	 * Template variable constants
	 *
	 */
	const VARIABLE_TO = '#to#';
    const VARIABLE_REQUESTED_BY = '#who#';
    const VARIABLE_EMPLOYEE = '#employee#';
    const VARIABLE_REVIEW_DATE = '#reviewdate#';
   
	/*
	 * Class atributes
	 **/
	private $mailType;
	private $logFile;
	private $emailConf;

	/* Mailer instance. used only for testing */
	private $mailer;
	
	private $employeeIdLength;

	/**
	 * Constructor
	 *
	 * Constructs the object
	 *
	 */
	public function __construct() {
		$this->emailConf = new EmailConfiguration();

		if (isset($this->emailConf->logPath) && !empty($this->emailConf->logPath)) {
			$logPath = $this->emailConf->logPath;
		} else {
			$logPath = ROOT_PATH.'/lib/logs/';
		}

		$this->mailType = $this->emailConf->getMailType();
		$this->logFile = $logPath . "notification_mails.log";
		
		$sysConfObj = new sysConf();
		$this->employeeIdLength = $sysConfObj->getEmployeeIdLength();		
	}

	/**
	 * Return a mailer object based on email configuration
	 *
	 * @return htmlMimeMail5 Mail object
	 */
	private function _getMailer() {

		if (!empty($this->mailer)) {
		    return $this->mailer;
		}

		$auth = true;
		if ($this->emailConf->getSmtpUser() == '') {
			$auth=false;
		}

		$mailer = new htmlMimeMail5();
		$mailer->setSMTPParams($this->emailConf->getSmtpHost(), $this->emailConf->getSmtpPort(), null, $auth, $this->emailConf->getSmtpUser(), $this->emailConf->getSmtpPass());
		$mailer->setSendmailPath($this->emailConf->getSendmailPath());
		$mailer->setFrom($this->emailConf->getMailAddress());

	    return $mailer;
	}

	/**
	 * Set mailer instance. Normally used for testing. If set, will override the
	 * internally used mailer
	 */
	public function setMailer($mailer) {
	    $this->mailer = $mailer;
	}

	/**
	 * Send approve review email 
	 *
	 * @param PerformanceReview $review
	 *
	 * @return boolean True if mail sent, false otherwise
	 */
	 public function sendApproveReviewEmails($receipients, $review) {

		$empNum = $review->getEmpNumber();
		$empName = $this->_getEmpName($empNum);
		$reviewDate = LocaleUtil::getInstance()->formatDate($review->getReviewDate());
		
		$emails = null;
		
		foreach($receipients as $receipient) {
			$to = $receipient[1];
			$toEmpNum = $receipient[0];
			$email = $this->_getEmpAddress($toEmpNum);
			if (!empty($email)) {
				$emails[] = $email;
			}
		}

		if (empty($emails)) {
			continue;
		}

		$subject = $this->_getTemplate(self::SUBJECT_SUBMITTED_FOR_APPROVAL);
		$body = $this->_getTemplate(self::TEMPLATE_SUBMITTED_FOR_APPROVAL);
		
		$search = array(self::VARIABLE_EMPLOYEE, self::VARIABLE_REVIEW_DATE);
		$replace = array($empName['first'] . ' ' . $empName['last'], $reviewDate);
		
		$subject = str_replace($search, $replace, $subject);
		$body = str_replace($search, $replace, $body);
		
		$notificationType = null;
		$result = $this->_sendMail($emails, $subject, $body, $notificationType);
	 }

	/**
	 * Send reminder of performance review 
	 *
	 * @param PerformanceReview $review
	 *
	 * @return boolean True if mail sent, false otherwise
	 */
	 public function sendPerformanceReviewReminder($review) {			
		
		$empNum = $review->getEmpNumber();
		$empName = $this->_getEmpName($empNum);
		$reviewDate = LocaleUtil::getInstance()->formatDate($review->getReviewDate());
		
		// Get supervisors:
		$empRepToObj = new EmpRepTo();
		$supInfo = $empRepToObj->getEmpSup(str_pad($empNum, $this->employeeIdLength, "0", STR_PAD_LEFT));
		$receipients = null;

		if (isset($supInfo) && is_array($supInfo)) {
			foreach ($supInfo as $supervisor) {
				$email = $this->_getEmpAddress($supervisor[1]);
				if (!empty($email)) {
					$receipients[] = $email;
				}
			}
		}
		
		if (empty($receipients)) {
			return true;
		}

		$subject = $this->_getTemplate(self::SUBJECT_REVIEW_NOTIFICATION);
		$body = $this->_getTemplate(self::TEMPLATE_REVIEW_NOTIFICATION);
		
		$search = array(self::VARIABLE_EMPLOYEE, self::VARIABLE_REVIEW_DATE);
		$replace = array($empName['first'] . ' ' . $empName['last'], $reviewDate);
		
		$subject = str_replace($search, $replace, $subject);
		$body = str_replace($search, $replace, $body);
		$notificationType = null;
		$result = $this->_sendMail($receipients, $subject, $body, $notificationType);
	 }


	/**
	 * Send email with given parameters
	 *
	 * @param mixed $to Array of email address, or single email address
	 * @param String $subject Email subject
	 * @param String $body Email body
	 * @param int $notificationType Notification type, used to fetch other emails subscribed to this type
	 *
	 * @return boolean True if mail sent, false otherwise
	 */
	private function _sendMail($to, $subject, $body, $notificationType, $attachments = null) {

		$mailer = $this->_getMailer();

        if (!empty($body)) {
		  $mailer->setText($body);
        }

        if (!empty($attachments) && is_array($attachments)) {
            foreach ($attachments as $attachment) {
                $mailer->addAttachment($attachment);
            }
        }

		// Trim newlines, carriage returns from subject.
		$subject = $this->_removeNewLines($subject);
		$mailer->setSubject($subject);

		if (empty($notificationType)) {
		    $notificationAddresses = null;
		} else {
			$mailNotificationObj = new EmailNotificationConfiguration();
			$notificationAddresses = $mailNotificationObj->fetchMailNotifications($notificationType);
		}

		$logMessage = date('r')." Sending {$subject} ";

		/*
		 * Check if at least one receipient available.
		 * If no 'to' receipients are available, one of the cc emails is used as the to address.
		 */
		if (empty($to)) {
		    if (empty($notificationAddresses)) {

		    	$logMessage .= " - FAILED \r\nReason: No receipients";
				$this->_log($logMessage);
		    	return false;
		    } else {
		        $to = array(array_shift($notificationAddresses));
		    }
		} else {
		    if (!is_array($to)) {
		        $to = array($to);
		    }
		}

		if (is_array($notificationAddresses)) {
			$cc = implode(', ', $notificationAddresses);
			$mailer->setCc($cc);
		}

		$logMessage .= "to " . implode(', ', $to) . "\r\n";
		if (isset($cc)) {
		    $logMessage .= "CC to {$cc}\r\n";
		}

		if (@$mailer->send($to, $this->mailType)) {
			$logMessage .= " - SUCCEEDED";
		} else {
			$logMessage .= " - FAILED \r\nReason(s):";
			if (isset($mailer->errors)) {
				$logMessage .= "\r\n\t*\t".implode("\r\n\t*\t",$mailer->errors);
			}
			$this->_log($logMessage);
			return false;
		}

		$this->_log($logMessage);
		return true;
	}

	/**
	 * Fetch the mail address of the employee
	 *
	 * @param integer $employeeId - Employee ID
	 * @return String E-Mail
	 */
	private function _getEmpAddress($employeeId) {
		$empInfoObj = new EmpInfo();
		$empInfo = $empInfoObj->filterEmpContact($employeeId);

		if (isset($empInfo[0][10])) {
			return $empInfo[0][10];
		}

		return null;
	}

	/**
	 * Fetch employee name
	 *
	 * @param integer $employeeId - Employee ID
	 * @return Array Array with employee first, middle and last names
	 */
	private function _getEmpName($employeeId) {
		$empInfoObj = new EmpInfo();
		$empInfo = $empInfoObj->filterEmpMain($employeeId);

		if (isset($empInfo[0])) {
			$last = $empInfo[0][1];
			$first =  $empInfo[0][2];
			$middle = $empInfo[0][3];

			return array('first'=>$first, 'middle'=>$middle, 'last'=>$last);
		}

		return null;
	}

	/**
	 * Get the mail template from given template file
	 *
	 * @param string $template Mail template file
	 *
	 * @return string Contents of template file
	 */
	private function _getTemplate($template) {
		$text = file_get_contents(ROOT_PATH."/templates/performance/mails/".$template);
		return $text;
	}

	/**
	 * Logs the given message to email notification log file
	 *
	 * @param String $message Message to log
	 */
	 private function _log($message) {
		error_log($message . "\r\n", 3, $this->logFile);
	 }

     /**
      * Remove new lines in given text
      * @param String $text Text in which to remove new lines
      * @return String String with newlines removed
      */
     private function _removeNewLines($text) {
        if (!empty($text)) {
            $text = str_replace(array("\r", "\n"), array('', ''), $text);
        }
        return $text;
     }

     /**
      * Remove new lines in \n
      * @param String $text Text in which to escape new lines
      * @return String String with newlines escaped
      */
     private function _escapeNewLines($text) {
        if (!empty($text)) {
            $text = str_replace("\r\n", '\\n', $text);
            $text = str_replace("\r", '\\n', $text);
            $text = str_replace("\n", '\\n', $text);
        }
        return $text;
     }

     /**
      * Get the country name when given the country code
      *
      * @param String $countryCode The country code
      * @return String The country name
      */
     private function _getCountryName($countryCode) {

         $country = '';
         if (!empty($countryCode)) {
             $countryInfo = new CountryInfo();
             $countryInfo = $countryInfo->filterCountryInfo($countryCode);
             if (is_array($countryInfo) && is_array($countryInfo[0])) {
                 $country = $countryInfo[0][1];
             } else {
                 $country = $countryCode;
             }
         }
         return $country;
     }
}

class PerformanceMailNotifierException extends Exception {
    const INVALID_PARAMETER = 0;
}

?>
