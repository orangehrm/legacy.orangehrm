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

require_once ROOT_PATH . '/lib/exception/ExceptionHandler.php';
require_once ROOT_PATH . '/lib/common/FormCreator.php';
require_once ROOT_PATH . '/lib/common/authorize.php';
require_once ROOT_PATH . '/lib/common/TemplateMerger.php';

require_once ROOT_PATH . '/lib/models/maintenance/UserGroups.php';
require_once ROOT_PATH . '/lib/models/maintenance/Users.php';
require_once ROOT_PATH . '/lib/models/maintenance/Rights.php';
require_once ROOT_PATH . '/lib/models/eimadmin/CountryInfo.php';
require_once ROOT_PATH . '/lib/models/eimadmin/ProvinceInfo.php';
require_once ROOT_PATH . '/lib/models/eimadmin/JobTitle.php';
require_once ROOT_PATH . '/lib/models/eimadmin/GenInfo.php';
require_once ROOT_PATH . '/lib/models/eimadmin/Skills.php';
require_once ROOT_PATH . '/lib/models/eimadmin/Licenses.php';
require_once ROOT_PATH . '/lib/models/eimadmin/Fluency.php';
require_once ROOT_PATH . '/lib/models/eimadmin/LanguageInfo.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpInfo.php';
require_once ROOT_PATH . '/lib/models/recruitment/JobVacancy.php';
require_once ROOT_PATH . '/lib/models/recruitment/JobApplication.php';
require_once ROOT_PATH . '/lib/models/recruitment/JobApplicationEvent.php';
require_once ROOT_PATH . '/lib/models/recruitment/RecruitmentMailNotifier.php';
require_once ROOT_PATH . '/lib/models/recruitment/RecruitmentAuthManager.php';
require_once ROOT_PATH . '/lib/models/recruitment/ApplicantEmploymentInfo.php';
require_once ROOT_PATH . '/lib/models/recruitment/ApplicantSkills.php';
require_once ROOT_PATH . '/lib/models/recruitment/ApplicantLicenseInformation.php';
require_once ROOT_PATH . '/lib/models/recruitment/AppicantLanguageInformation.php';
require_once ROOT_PATH . '/lib/models/recruitment/ApplicantEducationInfo.php';


require_once ROOT_PATH . '/lib/extractor/common/EXTRACTOR_ViewList.php';
require_once ROOT_PATH . '/lib/extractor/recruitment/EXTRACTOR_JobVacancy.php';
require_once ROOT_PATH . '/lib/extractor/recruitment/EXTRACTOR_JobApplication.php';
require_once ROOT_PATH . '/lib/extractor/recruitment/EXTRACTOR_JobApplicationEvent.php';
require_once ROOT_PATH . '/lib/extractor/recruitment/EXTRACTOR_ScheduleInterview.php';
require_once ROOT_PATH . '/lib/extractor/recruitment/EXTRACTOR_ApplicationField.php';
require_once ROOT_PATH . '/lib/controllers/PerformanceController.php';

/**
 * Controller for recruitment module
 */
class RecruitmentController {

	const INVALID_STATUS_ERROR = 'INVALID_STATUS_ERROR';

	private $authorizeObj;

    /**
     * Constructor
     */
    public function __construct() {
        if (isset($_SESSION) && isset($_SESSION['fname']) ) {
			$this->authorizeObj = new authorize($_SESSION['empID'], $_SESSION['isAdmin']);
        }
    }

    /**
     * Handle incoming requests
     * @param String code Recruit code
     */
    public function handleRequest($code) {

		if (empty($code) || !isset($_GET['action'])) {
			trigger_error("Invalid Action " . $_GET['action'], E_USER_NOTICE);
			return;
		}
		switch ($code) {
			case 'Vacancy' :
				$viewListExtractor = new EXTRACTOR_ViewList();

	            switch ($_GET['action']) {

	                case 'List' :
	                	$searchObject = $viewListExtractor->parseSearchData($_POST, $_GET);
	                    $this->_viewVacancies($searchObject);
	                    break;

	                case 'View' :
	                	$id = isset($_GET['id'])? $_GET['id'] : null;
	                	$this->_viewVacancy($id);
						break;

	                case 'ViewAdd' :
	                	$this->_viewAddVacancy();
	                	break;

					case 'Add' :
						$extractor = new EXTRACTOR_JobVacancy();
						$vacancy = $extractor->parseData($_POST);
						$this->_addVacancy($vacancy);
						break;

					case 'Update' :
						$extractor = new EXTRACTOR_JobVacancy();
						$vacancy = $extractor->parseData($_POST);
						$this->_updateVacancy($vacancy);
						break;

	                case 'Delete' :
	                    $ids = $_POST['chkID'];
	                    $this->_deleteVacancies($ids);
	                	break;
	            }
                break;

            case 'Application' :
                $id = isset($_GET['id']) ? $_GET['id'] : null;

                switch ($_GET['action']) {

                    case 'List' :
                        $this->_viewApplicationList();
                        break;
                    case 'ConfirmShortList':
                        // No confirmation screen shown for short list
                        $this->_shortList($id);
                        break;
                    case 'ConfirmReject' :
                        $this->_confirmAction($id, JobApplication::ACTION_REJECT);
                        break;
                    case 'Reject' :
                        $eventExtractor = new EXTRACTOR_JobApplicationEvent();
                        $event = $eventExtractor->parseAddData($_POST);
                        $this->_rejectApplication($event);
                        break;
                    case 'ConfirmFirstInterview' :
                        $this->_scheduleFirstInterview($id);
                        break;
                    case 'FirstInterview' :
                        $interviewExtractor = new EXTRACTOR_ScheduleInterview();
                        $event = $interviewExtractor->parseAddData($_POST);
                        $this->_saveFirstInterview($event);
                        break;
                    case 'ConfirmSecondInterview' :
                        $this->_scheduleSecondInterview($id);
                        break;
                    case 'SecondInterview' :
                        $interviewExtractor = new EXTRACTOR_ScheduleInterview();
                        $event = $interviewExtractor->parseAddData($_POST);
                        $this->_saveSecondInterview($event);
                        break;
                    case 'ConfirmOfferJob' :
                        $this->_confirmAction($id, JobApplication::ACTION_OFFER_JOB);
                        break;
                    case 'OfferJob' :
                        $eventExtractor = new EXTRACTOR_JobApplicationEvent();
                        $event = $eventExtractor->parseAddData($_POST);
                        $this->_offerJob($event);
                        break;
                    case 'ConfirmMarkDeclined' :
                        $this->_confirmAction($id, JobApplication::ACTION_MARK_OFFER_DECLINED);
                        break;
                    case 'MarkDeclined' :
                        $eventExtractor = new EXTRACTOR_JobApplicationEvent();
                        $event = $eventExtractor->parseAddData($_POST);
                        $this->_markDeclined($event);
                        break;
                    case 'ConfirmSeekApproval' :
                        $this->_confirmSeekApproval($id);
                        break;
                    case 'SeekApproval' :
                        $eventExtractor = new EXTRACTOR_JobApplicationEvent();
                        $event = $eventExtractor->parseSeekApprovalData($_POST);
                        $this->_seekApproval($event);
                        break;
                    case 'ConfirmApprove' :
                        $this->_confirmAction($id, JobApplication::ACTION_APPROVE);
                        break;
                    case 'Approve' :
                        $eventExtractor = new EXTRACTOR_JobApplicationEvent();
                        $event = $eventExtractor->parseAddData($_POST);
                        $this->_approve($event);
                        break;
                    case 'ViewDetails' :
                        $this->_viewApplicationDetails($id);
                        break;
                    case 'DownloadCv' :
                        $this->_downloadCv($id);
                        break;
                    case 'ViewHistory' :
                        $this->_viewApplicationHistory($id);
                        break;
                    case 'DownloadEventAttach1' :
                        $this->_downloadEventAttach($id, 1);
                        break;
                    case 'DownloadEventAttach2' :
                        $this->_downloadEventAttach($id, 2);
                        break;
                    case 'EditEvent' :
                        $eventExtractor = new EXTRACTOR_JobApplicationEvent();
                        $object = $eventExtractor->parseUpdateData($_POST);
                        $this->_editEvent($object);
                        break;
                }

	            break;
	       case 'Application_Config' :
				$viewListExtractor = new EXTRACTOR_ViewList();

	            switch ($_GET['action']) {

	                case 'List' :
	                	$searchObject = $viewListExtractor->parseSearchData($_POST, $_GET);
	                    $this->_viewApplicationFields($searchObject);
	                    break;

	                case 'View' :
	                	$id = isset($_GET['id'])? $_GET['id'] : null;
	                	$this->_viewApplicationField($id);
						break;

	                case 'ViewAdd' :
	                	$this->_viewAddApplicationField();
	                	break;

					case 'Add' :
						$extractor = new EXTRACTOR_ApplicationField();
						$field = $extractor->parseData($_POST);
						$this->_addField($field);
						break;

					case 'Update' :
						$extractor = new EXTRACTOR_ApplicationField();
						$field = $extractor->parseData($_POST);
						$this->_updateField($field);
						break;

	                case 'Delete' :
	                    $ids = $_POST['chkID'];
	                    $this->_deleteField($ids);
	                	break;
	            }
                break;
	    }
    }

	/**
	 * Generic method to display a list
	 * @param int $pageNumber Page Number
	 * @param int $count Total number of results
	 * @param Array $list results (in current page)
	 */
	private function _viewList($pageNumber, $count, $list) {

        $formCreator = new FormCreator($_GET, $_POST);
        $formCreator->formPath = '/recruitmentview.php';
        $formCreator->popArr['currentPage'] = $pageNumber;
        $formCreator->popArr['list'] = $list;
        $formCreator->popArr['count'] = $count;
        $formCreator->display();
	}
	private function _viewApplicationFieldList($pageNumber, $count, $list) {

        $formCreator = new FormCreator($_GET, $_POST);
        $formCreator->formPath = '/applicationconfigview.php';
        $formCreator->popArr['currentPage'] = $pageNumber;
        $formCreator->popArr['list'] = $list;
        $formCreator->popArr['count'] = $count;
        $formCreator->display();
	}

	/**
	 * View list of vacancies
	 * @param SearchObject Object with search parameters
	 */
    private function _viewVacancies($searchObject) {

		if ($this->authorizeObj->isAdmin()) {
        	$list = JobVacancy::getListForView($searchObject->getPageNumber(), $searchObject->getSearchString(), $searchObject->getSearchField(), $searchObject->getSortField(), $searchObject->getSortOrder());
        	$count = Jobvacancy::getCount($searchObject->getSearchString(), $searchObject->getSearchField());
        	$this->_viewList($searchObject->getPageNumber(), $count, $list);
		} else {
            $this->_notAuthorized();
		}
    }
    private function _viewApplicationFields($searchObject) {

		if ($this->authorizeObj->isAdmin()) {
        	$list = JobApplicationField::getListForView($searchObject->getPageNumber(), $searchObject->getSearchString(), $searchObject->getSearchField(), $searchObject->getSortField(), $searchObject->getSortOrder());
        	$count = sizeof($list);
        	$this->_viewApplicationFieldList($searchObject->getPageNumber(), $count, $list);
		} else {
            $this->_notAuthorized();

		}
    }

	private function _viewAddApplicationField() {
		if ($this->authorizeObj->isAdmin()) {
	    	$this->_viewApplicationField();
	    } else {
            $this->_notAuthorized();
		}
	}

	private function _deleteField($ids) {
		if ($this->authorizeObj->isAdmin()) {
			try {
        		$count = JobApplicationField::deleteStateChage($ids);
        		$message = 'DELETE_SUCCESS';
			} catch (JobVacancyException $e) {
				$message = 'DELETE_FAILURE';
			}
            $this->redirect($message, '?recruitcode=Application_Config&action=List');
		} else {
            $this->_notAuthorized();
		}
    }
	/**
	 * Delete vacancies with given IDs
	 * @param Array $ids Array with Vacancy ID's to delete
	 */
    private function _deleteVacancies($ids) {
		if ($this->authorizeObj->isAdmin()) {
			try {
        		$count = JobVacancy::delete($ids);
        		$message = 'DELETE_SUCCESS';
			} catch (JobVacancyException $e) {
				$message = 'DELETE_FAILURE';
			}
            $this->redirect($message, '?recruitcode=Vacancy&action=List');
		} else {
            $this->_notAuthorized();
		}
    }

	/**
	 * View add Vacancy page
	 */
	private function _viewAddVacancy() {
		if ($this->authorizeObj->isAdmin()) {
	    	$this->_viewVacancy();
	    } else {
            $this->_notAuthorized();
		}
	}

    /**
     * View vacancy
     * @param int $id Id of vacancy. If empty, A new vacancy is shown
     */
    private function _viewVacancy($id = null) {

		$path = '/templates/recruitment/jobVacancy.php';

		try {
			if (empty($id)) {
				$vacancy = new JobVacancy();
			} else {
				$vacancy = JobVacancy::getJobVacancy($id);
			}

			$empInfo = new EmpInfo;
			$managers = $empInfo->getListofEmployee(0, "", 6);
			$jobTitle = new JobTitle();
			$jobTitles = $jobTitle->getJobTit();

			$objs['vacancy'] = $vacancy;
			$objs['managers'] = is_array($managers) ? $managers : array();
			$objs['jobTitles'] = is_array($jobTitles) ? $jobTitles : array();

			$template = new TemplateMerger($objs, $path);
			$template->display();
		} catch (JobVacancyException $e) {
			$message = 'UNKNOWN_FAILURE';
            $this->redirect($message);
		}
    }

 	private function _viewApplicationField($id = null) {
		$path = '/templates/recruitment/applicationField.php';
		try {
			if (empty($id)) {
				$applicationField = new JobApplicationField();
			} else {
				$applicationField = new JobApplicationField();
				$applicationField->setId($id);
				$applicationField = $applicationField->fetchApplicationField();
				$applicationField=$applicationField[0];

			}
			$objs['fieldTypes'] = JobApplicationField::getFieldTypes();
			$objs['applicationField'] = $applicationField;
			$template = new TemplateMerger($objs, $path);
			$template->display();
		} catch (JobVacancyException $e) {
			$message = 'UNKNOWN_FAILURE';
            $this->redirect($message);
		}
    }

	private function _addField($field) {
		if ($this->authorizeObj->isAdmin()) {
			try {
				$field->saveField();
	        	$message = 'ADD_SUCCESS';
	        	$this->redirect($message, '?recruitcode=Application_Config&action=List');
			} catch (JobVacancyException $e) {
				$message = 'ADD_FAILURE';
	        	$this->redirect($message);
			}
		} else {
            $this->_notAuthorized();
		}

    }

	private function _updateField($field) {
		if ($this->authorizeObj->isAdmin()) {
			try {
				$field->updateField();
	        	$message = 'UPDATE_SUCCESS';
	        	$this->redirect($message, '?recruitcode=Application_Config&action=List');
			} catch (JobVacancyException $e) {
				$message = 'UPDATE_FAILURE';
	        	$this->redirect($message);
			}
		} else {
            $this->_notAuthorized();
		}
    }

    /**
     * Add vacancy to database
     * @param JobVacancy $vacancy Job Vacancy object to add
     */
    private function _addVacancy($vacancy) {
		if ($this->authorizeObj->isAdmin()) {
			try {
				$vacancy->save();
	        	$message = 'ADD_SUCCESS';
	        	$this->redirect($message, '?recruitcode=Vacancy&action=List');
			} catch (JobVacancyException $e) {
				$message = 'ADD_FAILURE';
	        	$this->redirect($message);
			}
		} else {
            $this->_notAuthorized();
		}

    }

    /**
     * Add vacancy to database
     * @param JobVacancy $vacancy Job Vacancy object to add
     */
    private function _updateVacancy($vacancy) {
		if ($this->authorizeObj->isAdmin()) {
			try {
				$vacancy->save();
	        	$message = 'UPDATE_SUCCESS';
	        	$this->redirect($message, '?recruitcode=Vacancy&action=List');
			} catch (JobVacancyException $e) {
				$message = 'UPDATE_FAILURE';
	        	$this->redirect($message);
			}
		} else {
            $this->_notAuthorized();
		}
    }

	/**
	 * Shows a list of active job vacancies to job applicant.
	 */
	public function showVacanciesToApplicant() {
		$path = '/templates/recruitment/applicant/viewVacancies.php';
		$objs['vacancies'] = JobVacancy::getActive();
		$template = new TemplateMerger($objs, $path);
		$template->display();
	}

	/**
	 * Display job application form to applicant
	 *
	 * @param int $id Job Vacancy ID
	 */
	public function showJobApplication($id) {
		$path = '/templates/recruitment/applicant/viewJobApplication.php';
		$fieldsData=JobApplication::fetchApplicationData();
		$field=new JobApplicationField();
		$fields=$field->fetchApplicationFields();

		$objs['vacancy'] = JobVacancy::getJobVacancy($id);

		$objs['skills'] = Skills::getSkillCodes();
		$objs['licensesCodes'] = Licenses::getLicensesCodes();
		$objs['language'] = LanguageInfo::getLang();
		$objs['fluency'] = Fluency::filterFluencyCodes();

		$objs['applicationFields']=$fields;
		$objs['applicationData']=$fieldsData;
		$countryinfo = new CountryInfo();
		$objs['countryList'] = $countryinfo->getCountryCodes();

		$genInfo = new GenInfo();
		$objs['company'] = $genInfo->getValue('COMPANY');

		$template = new TemplateMerger($objs, $path);
		$template->display();
	}

	/**
	 * Handle job application by applicant
	 */
	public function applyForJob() {
		$field=new JobApplicationField();
		$dynamicFields=$field->filterDynamicFields($_REQUEST);
		$extractor = new EXTRACTOR_JobApplication();
		$jobApplication = $extractor->parseData($_POST);
        $attachmentError = false;

		try {
		    $jobApplication->save();
				    /* saving employeement info */
				if(isset($_REQUEST['employer']) && sizeof($_REQUEST['employer'])){
					$employers=$_REQUEST['employer'];
					$applicatnEmployer=new ApplicantEmployementInfo();
					foreach ($employers as $key=>$emploer){
						$applicatnEmployer->setApplicationId($jobApplication->getId());
						$applicatnEmployer->setDuties($_REQUEST['duties'][$key]);
						$applicatnEmployer->setEmployer($emploer);
						$applicatnEmployer->setEndDate($_REQUEST['end_date'][$key]);
						$applicatnEmployer->setJobTitle($_REQUEST['job_title'][$key]);
						$applicatnEmployer->setStartDate($_REQUEST['start_date'][$key]);
						$applicatnEmployer->save();
					}
				}
				/* saving skill  info */
				if(isset($_REQUEST['skill']) && sizeof($_REQUEST['skill'])){
					$skills=$_REQUEST['skill'];
					$applicantSkill=new ApplicantSkills();
					foreach ($skills as $key=>$skill){
						$applicantSkill->setApplicationId($jobApplication->getId());
						$applicantSkill->setComments($_REQUEST['skill_comments'][$key]);
						$applicantSkill->setSkillCode($_REQUEST['skill'][$key]);
						$applicantSkill->setYearsOfExperience($_REQUEST['skill_years_of_experience'][$key]);
						$applicantSkill->save();
					}
				}

				/* saving License Information info */
				if(isset($_REQUEST['license_type']) && sizeof($_REQUEST['license_type'])){
					$licenseInfo=$_REQUEST['license_type'];
					$applicatnLicenseInfo=new ApplicantLicenseInformation();
					foreach ($licenseInfo as $key=>$license){
						$applicatnLicenseInfo->setApplicationId($jobApplication->getId());
						$sysconf=new sysConf();
						if($_REQUEST['licens_exp_date'][$key]!=$sysconf->getDateInputHint()) $applicatnLicenseInfo->setExpiryDate($_REQUEST['licens_exp_date'][$key]);
						$applicatnLicenseInfo->setLecenseCode($_REQUEST['license_type'][$key]);
						$applicatnLicenseInfo->save();
					}
				}

				/* saving language Information info */
				if(isset($_REQUEST['language_language']) && sizeof($_REQUEST['language_language'])){
					$languageInfo=$_REQUEST['language_language'];
					$applicantLangInfo=new AppicantLanguageInformation();
					foreach ($languageInfo as $key=>$lan){
						$applicantLangInfo->setApplicationId($jobApplication->getId());
						$applicantLangInfo->setFluencyCode($_REQUEST['language_fluency'][$key]);
						$applicantLangInfo->setLangCode($_REQUEST['language_language'][$key]);
						$applicantLangInfo->save();
					}
				}

			/* saving Educational Information info */
				if(isset($_REQUEST['education_education']) && sizeof($_REQUEST['education_education'])){
					$eduInfo=$_REQUEST['education_education'];
					$applicantEduInfo=new ApplicantEducationInfo();
					foreach ($eduInfo as $key=>$edu){
						$applicantEduInfo->setApplicationId($jobApplication->getId());
						$applicantEduInfo->setAverageScore($_REQUEST['education_score'][$key]);
						$applicantEduInfo->setEducation($_REQUEST['education_education'][$key]);
						$applicantEduInfo->setMajorSpecialization($_REQUEST['education_major'][$key]);
						$applicantEduInfo->setYearCompleted($_REQUEST['education_year'][$key]);
						$applicantEduInfo->save();
					}
				}

		    foreach ($dynamicFields as $field){
		    	$field->setApplicationId($jobApplication->getId());
		    	$field->saveFieldData();
		    }
		    $result = true;
		} catch (JobApplicationException $e) {
            if ($e->getCode() == JobApplicationException::FILE_TOO_LARGE) {
                $attachmentError = true;
            }
			$result = false;
		}

		// Send mail notifications
        if ($result) {
    		$notifier = new RecruitmentMailNotifier();
    		$notifier->sendApplicationReceivedEmailToManager($jobApplication);

    		// We only need to display result of email sent to applicant
    		$mailResult = $notifier->sendApplicationReceivedEmailToApplicant($jobApplication);
        } else {
            $mailResult = false;
        }

		$path = '/templates/recruitment/applicant/jobApplicationStatus.php';
		$objs['application'] = $jobApplication;
		$objs['vacancy'] = JobVacancy::getJobVacancy($jobApplication->getVacancyId());
		$objs['result'] = $result;
        $objs['attachmentError'] = $attachmentError;
		$objs['mailResult'] = $mailResult;
		$template = new TemplateMerger($objs, $path);
		$template->display();
	}

	/**
	 * Return the province codes for the given country.
	 * Used by xajax calls.
	 * @param String $countryCode The country code
	 * @return Array 2D Array of Province Codes and Province Names
	 */
	public static function getProvinceList($countryCode) {
		$province = new ProvinceInfo();
		return $province->getProvinceCodes($countryCode);
	}

    /**
     * Display list of job applications to HR admin or manager
     */
    private function _viewApplicationList() {

        if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isManager() || $this->authorizeObj->isDirector()|| $this->authorizeObj->isAcceptor() || $this->authorizeObj->isOfferer()) {
            $managerId = $this->authorizeObj->isAdmin()? null : $this->authorizeObj->getEmployeeId();

            $sortField = isset($_GET['sortField']) ? $_GET['sortField']: 0;
            $sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder']: 'ASC';
            $applications = JobApplication::getList($managerId, $sortField, $sortOrder);

            $path = '/templates/recruitment/applicationList.php';
            $objs['applications'] = $applications;
            $template = new TemplateMerger($objs, $path);
            $template->display();
        } else {
            $this->_notAuthorized();
        }
    }

    /**
     * View application details
     * @param int $id Application ID
     */
    private function _viewApplicationDetails($id) {
        $path = '/templates/recruitment/viewApplicationDetails.php';

        $objs['application'] = JobApplication::getJobApplication($id);
//        echo "<pre>";
//        print_r($objs);
//        exit;

        $template = new TemplateMerger($objs, $path);
        $template->display();
    }
	private function _downloadCv($id) {
		$applicaton =new JobApplication($id);
    	$applicaton=$applicaton->fetchCvDataObject();
    	$size=strlen($applicaton->getCvData());
    	$contentType=$applicaton->getCvType();
    	$name="cv_application_id_".$applicaton->getId().".".$applicaton->getCvExtention();
		@ob_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false);
		header("Content-type: $contentType");
		header("Content-Disposition: attachment; filename=\"".$name."\";");
		header("Content-Transfer-Encoding: binary");
		header("Content-length: $size");
		echo $applicaton->getCvData();
    }

    /**
     * Download attachments from event.
     * Currently used only for 2nd interview.
     * @param int $id Interview Event ID
     * @param int $attachmentNo Attachment number - one of 1 or 2
     */
    private function _downloadEventAttach($id, $attachmentNo) {

        if (($attachmentNo == 1) || ($attachmentNo == 2)) {
            $event = JobApplicationEvent::getJobApplicationEvent($id, $attachmentNo);

            if ($attachmentNo == 1) {
                $name = $event->getAttachment1Name();
                $type = $event->getAttachment1Type();
                $data = $event->getAttachment1Data();
            } else if ($attachmentNo == 2) {
                $name = $event->getAttachment2Name();
                $type = $event->getAttachment2Type();
                $data = $event->getAttachment2Data();
            }

            $size = strlen($data);
            if ($size > 0) {
                $this->_download($name, $type, $size, $data);
            }
        }
    }

    /**
     * Download given attachment
     */
    private function _download($name, $contentType, $size, $data) {
        @ob_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-type: $contentType");
        header("Content-Disposition: attachment; filename=\"".$name."\";");
        header("Content-Transfer-Encoding: binary");
        header("Content-length: $size");
        echo $data;
    }

    /**
     * View application history
     * @param int $id Application ID
     */
    private function _viewApplicationHistory($id) {
        $path = '/templates/recruitment/viewApplicationHistory.php';
        $objs['application'] = JobApplication::getJobApplication($id);

        $template = new TemplateMerger($objs, $path);
        $template->display();
    }

    /**
     * Reject the given application
     * @param JobApplicationEvent Job Application event with the details
     */
    private function _rejectApplication($event) {
        if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isManager() || $this->authorizeObj->isDirector() || $this->authorizeObj->isAcceptor() || $this->authorizeObj->isOfferer()) {

            // TODO: Validate if Hiring manager or interview manager and in correct status
            $application = JobApplication::getJobApplication($event->getApplicationId());

            // Validate if in correct status.
            $currentStatus = $application->getStatus();

            $invalidStatuses = array(JobApplication::STATUS_REJECTED, JobApplication::STATUS_OFFER_DECLINED,
            	JobApplication::STATUS_HIRED, JobApplication::STATUS_JOB_OFFERED);
            if (in_array($currentStatus, $invalidStatuses)) {
            	$attemptedAction = isset($_GET['action']) ? $_GET['action'] : '';
            	$this->_showInvalidStatusError($event->getApplicationId(), $attemptedAction);
				return;
            }

            $application->setStatus(JobApplication::STATUS_REJECTED);
            try {
                $application->save();
                $this->_saveApplicationEvent($event, JobApplicationEvent::EVENT_REJECT);

                // Send notification to Applicant
                $notifier = new RecruitmentMailNotifier();
                $notifier->sendApplicationRejectedEmailToApplicant($application);

                $message = 'UPDATE_SUCCESS';
            } catch (Exception $e) {
                $message = 'UPDATE_FAILURE';
            }
            $this->redirect($message, '?recruitcode=Application&action=List');
            //$this->_viewApplicationList();
        } else {
            $this->_notAuthorized();
        }
    }

    private function _saveFirstInterview($event) {
        if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isManager() || $this->authorizeObj->isOfferer()) {

            // TODO: Validate if Hiring manager or interview manager and in correct status

            $applicationId = $event->getApplicationId();
            $application = JobApplication::getJobApplication($applicationId);

            // Validate if in correct status.
            $currentStatus = $application->getStatus();
            if ($currentStatus != JobApplication::STATUS_SHORTLISTED) {
            	$attemptedAction = isset($_GET['action']) ? $_GET['action'] : '';
            	$this->_showInvalidStatusError($applicationId, $attemptedAction);
				return;
            }

            $application->setStatus(JobApplication::STATUS_FIRST_INTERVIEW_SCHEDULED);
            try {
                $application->save();
                $event->setEventType(JobApplicationEvent::EVENT_SCHEDULE_FIRST_INTERVIEW);
                $event->setStatus(JobApplicationEvent::STATUS_INTERVIEW_SCHEDULED);
                $event->setCreatedBy($_SESSION['user']);
                $event->save();

                // Send notification to Interviewer
                $notifier = new RecruitmentMailNotifier();
                $notifier->sendInterviewTaskToManager($event);

                $message = 'UPDATE_SUCCESS';
            } catch (Exception $e) {
                $message = 'UPDATE_FAILURE';
            }
            $this->redirect($message, '?recruitcode=Application&action=List');
            //$this->_viewApplicationList();
        } else {
            $this->_notAuthorized();
        }
    }

    /**
     * Show error message when user attempts to apply an action to a job application in an invalid state.
     *
     * eg: When attempting to schedule a first interview where a interview has already been scheduled.
     * This is normally possible only if the user presses the back button on the browser and attempts to redo an action.
     */
    private function _showInvalidStatusError($applicationId, $attemptedAction) {
		$this->redirect(RecruitmentController::INVALID_STATUS_ERROR,
			"?recruitcode=Application&action=ViewHistory&id={$applicationId}&attemptedAction={$attemptedAction}");
    }

    private function _saveSecondInterview($event) {
        if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isManager() || $this->authorizeObj->isOfferer()) {

            // TODO: Validate if Hiring manager or interview manager and in correct status
            $applicationId = $event->getApplicationId();
            $application = JobApplication::getJobApplication($applicationId);

            // Validate if in correct status.
            $currentStatus = $application->getStatus();
            if ($currentStatus != JobApplication::STATUS_FIRST_INTERVIEW_SCHEDULED) {
            	$attemptedAction = isset($_GET['action']) ? $_GET['action'] : '';
            	$this->_showInvalidStatusError($applicationId, $attemptedAction);
				return;
            }

            $application->setStatus(JobApplication::STATUS_SECOND_INTERVIEW_SCHEDULED);

            try {
                $event->setEventType(JobApplicationEvent::EVENT_SCHEDULE_SECOND_INTERVIEW);
                $event->setStatus(JobApplicationEvent::STATUS_INTERVIEW_SCHEDULED);
                $event->setCreatedBy($_SESSION['user']);

                $event->save();
                $application->save();

                // Send notification to Interviewer
                $notifier = new RecruitmentMailNotifier();
                $notifier->sendInterviewTaskToManager($event);

                $message = 'UPDATE_SUCCESS';
            } catch (JobApplicationEventException $e) {
                if ($e->getCode() == JobApplicationEventException::ATTACHMENT_FAILURE) {
                    $message = 'UPLOAD_FAILURE';
                } else {
                    $message = 'UPDATE_FAILURE';
                }
            } catch (Exception $e) {
                $message = 'UPDATE_FAILURE';
            }

            $this->redirect($message, '?recruitcode=Application&action=List');
            //$this->_viewApplicationList();
        } else {
            $this->_notAuthorized();
        }
    }


    /**
     * Confirm the given action by showing a confirmation page to the user
     *
     * @param int $id The Job Application ID
     * @param int $action The action constant
     */
    private function _confirmAction($id, $action) {
        $path = '/templates/recruitment/confirmAction.php';

        $objs['application'] = JobApplication::getJobApplication($id);
        $objs['action'] = $action;

        $template = new TemplateMerger($objs, $path);
        $template->display();
    }

    private function _scheduleFirstInterview($id) {
        if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isManager() || $this->authorizeObj->isOfferer()) {
            $this->_scheduleInterview($id, 1);
        } else {
            $this->_notAuthorized();
        }
    }

    private function _scheduleSecondInterview($id) {
        if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isManager() || $this->authorizeObj->isOfferer()) {
            $this->_scheduleInterview($id, 2);
        } else {
            $this->_notAuthorized();
        }
    }

    private function _scheduleInterview($id, $num) {
        $path = '/templates/recruitment/scheduleInterview.php';

        $empInfo = new EmpInfo();
        $managers = $empInfo->getListofEmployee(0, JobTitle::MANAGER_JOB_TITLE_NAME, 6);
        $objs['managers'] = is_array($managers) ? $managers : array();
        $objs['application'] = JobApplication::getJobApplication($id);
        $objs['interview'] = $num;

        $template = new TemplateMerger($objs, $path);
        $template->display();
    }

    private function _offerJob($event) {
        if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isManager() || $this->authorizeObj->isOfferer()) {

            // TODO: Validate if Hiring manager or interview manager and in correct status
            $application = JobApplication::getJobApplication($event->getApplicationId());

            // Validate if in correct status.
            $currentStatus = $application->getStatus();

            if ($currentStatus != JobApplication::STATUS_SECOND_INTERVIEW_SCHEDULED) {
            	$attemptedAction = isset($_GET['action']) ? $_GET['action'] : '';
            	$this->_showInvalidStatusError($event->getApplicationId(), $attemptedAction);
				return;
            }

            $application->setStatus(JobApplication::STATUS_JOB_OFFERED);

            try {
                $application->save();
                $this->_saveApplicationEvent($event, JobApplicationEvent::EVENT_OFFER_JOB);
                $message = 'UPDATE_SUCCESS';
            } catch (Exception $e) {
                $message = 'UPDATE_FAILURE';
            }

            $this->redirect($message, '?recruitcode=Application&action=List');
            //$this->_viewApplicationList();
        } else {
            $this->_notAuthorized();
        }
    }

    /**
     * Short list given application
     */
    private function _shortList($id) {
        if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isManager()|| $this->authorizeObj->isOfferer()) {

            // TODO: Validate if Hiring manager or interview manager and in correct status
            $application = JobApplication::getJobApplication($id);

            // Validate if in correct status.
            $currentStatus = $application->getStatus();

            if ($currentStatus != JobApplication::STATUS_SUBMITTED) {
                $attemptedAction = JobApplication::ACTION_SHORTLIST;
                $this->_showInvalidStatusError($event->getApplicationId(), $attemptedAction);
                return;
            }

            $application->setStatus(JobApplication::STATUS_SHORTLISTED);

            $event = new JobApplicationEvent();
            $event->setApplicationId($id);

            try {
                $application->save();
                $this->_saveApplicationEvent($event, JobApplicationEvent::EVENT_SHORTLIST);
                $message = 'UPDATE_SUCCESS';
            } catch (Exception $e) {
                $message = 'UPDATE_FAILURE';
            }

            $this->redirect($message, '?recruitcode=Application&action=List');
        } else {
            $this->_notAuthorized();
        }
    }

    private function _markDeclined($event) {
        if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isManager()) {

            // TODO: Validate if Hiring manager or interview manager and in correct status
            $application = JobApplication::getJobApplication($event->getApplicationId());

            // Validate if in correct status.
            $currentStatus = $application->getStatus();

            if ($currentStatus != JobApplication::STATUS_JOB_OFFERED) {
            	$attemptedAction = isset($_GET['action']) ? $_GET['action'] : '';
            	$this->_showInvalidStatusError($event->getApplicationId(), $attemptedAction);
				return;
            }

            $application->setStatus(JobApplication::STATUS_OFFER_DECLINED);

            try {
                $application->save();
                $this->_saveApplicationEvent($event, JobApplicationEvent::EVENT_MARK_OFFER_DECLINED);
                $message = 'UPDATE_SUCCESS';
            } catch (Exception $e) {
                $message = 'UPDATE_FAILURE';
            }

            $this->redirect($message, '?recruitcode=Application&action=List');
            //$this->_viewApplicationList();
        } else {
            $this->_notAuthorized();
        }
    }

    /**
     * Show a screen allowing the manager to select a director
     * to seek approval from. Also allows the manager to add notes
     * related to the hiring.
     *
     * @param int $id Id of job application
     */
    private function _confirmSeekApproval($id) {
        $path = '/templates/recruitment/seekApproval.php';

        $empInfo = new EmpInfo();
        $directors = $empInfo->getListofEmployee(0, JobTitle::DIRECTOR_JOB_TITLE_NAME, 6);
        $objs['directors'] = is_array($directors) ? $directors : array();
        $objs['application'] = JobApplication::getJobApplication($id);

        $template = new TemplateMerger($objs, $path);
        $template->display();
    }

    private function _seekApproval($event) {
        if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isManager() || $this->authorizeObj->isOfferer()) {

            // TODO: Validate if Hiring manager or interview manager and in correct status
            $application = JobApplication::getJobApplication($event->getApplicationId());

            // Validate if in correct status.
            $currentStatus = $application->getStatus();

            if ($currentStatus != JobApplication::STATUS_JOB_OFFERED) {
            	$attemptedAction = isset($_GET['action']) ? $_GET['action'] : '';
            	$this->_showInvalidStatusError($event->getApplicationId(), $attemptedAction);
				return;
            }
            $application->setStatus(JobApplication::STATUS_PENDING_APPROVAL);

            try {
                $application->save();

                $event->setEventType(JobApplicationEvent::EVENT_SEEK_APPROVAL);
                $event->setCreatedBy($_SESSION['user']);
                $event->save();

                // Send notification to Interviewer
                $notifier = new RecruitmentMailNotifier();
                $mailResult = $notifier->sendSeekApprovalToDirector($application, $event);

                $message = 'UPDATE_SUCCESS';
            } catch (Exception $e) {
                $message = 'UPDATE_FAILURE';
            }

            $this->redirect($message, '?recruitcode=Application&action=List');

        } else {
            $this->_notAuthorized();
        }
    }
    private function _approve($event) {
        if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isDirector()|| $this->authorizeObj->isAcceptor() ) {

            // TODO: Validate if Hiring manager or interview manager and in correct status
            $application = JobApplication::getJobApplication($event->getApplicationId());

            // Validate if in correct status.
            $currentStatus = $application->getStatus();

            if ($currentStatus != JobApplication::STATUS_PENDING_APPROVAL) {
            	$attemptedAction = isset($_GET['action']) ? $_GET['action'] : '';
            	$this->_showInvalidStatusError($event->getApplicationId(), $attemptedAction);
				return;
            }
            $application->setStatus(JobApplication::STATUS_HIRED);

            try {
                $application->save();
                $this->_saveApplicationEvent($event, JobApplicationEvent::EVENT_APPROVE);

                // Create employee in PIM
                $empId = $this->createEmployeeFromApplication($application);

                // Save new employee number in application for reference.
                $application->setEmpNumber($empId);
                $application->save();

				// Create initial performance review
				$performanceController = new PerformanceController();

				// TODO: Move to language files
				$reviewNote = 'Review created at hire time';
				$performanceController->createReview($empId, $reviewNote);

                // Send email informing approval to hiring manager
                $notifier = new RecruitmentMailNotifier();
                $mailResult = $notifier->sendApprovalToHiringManager($application, $event);

                $message = 'UPDATE_SUCCESS';
            } catch (Exception $e) {
                $message = 'UPDATE_FAILURE';
            }

            $this->redirect($message, '?recruitcode=Application&action=List');
            //$this->_viewApplicationList();
        } else {
            $this->_notAuthorized();
        }

    }

    /**
     * Add given event to application
     * @param JobApplicationEvent Job Application event with the details
     * @param int Event type
     */
    private function _saveApplicationEvent($event, $eventType) {

        $event->setEventType($eventType);
        $createdTime = date(LocaleUtil::STANDARD_DATETIME_FORMAT);
        $event->setCreatedTime($createdTime);
        $event->setCreatedBy($_SESSION['user']);
        //$event->setStatus($status);

        $event->save();
    }

    /**
     * Save the new values of passed Job Application Event
     */
    private function _editEvent($jobApplicationEvent) {
        try {
            $jobApplicationEvent->save();
            $message = 'UPDATE_SUCCESS';
        } catch (JobApplicationEventException $e) {

            switch ($e->getCode()) {
                case JobApplicationEventException::ATTACHMENT_FAILURE:
                    $message = 'UPLOAD_FAILURE';
                    break;
                default:
                    $message = 'UPDATE_FAILURE';
                    break;
            }
        }
        $this->redirect($message);
    }

    /**
     * Checks short listed applicants and sends reminders to Hiring Managers
     * after 1 week if not yet scheduled for an interview
     */
    public static function checkShortListedApplicants() {

        try {
            $applications = JobApplication::getPendingShortListedApplications();
            if (!empty($applications)) {
                $notifier = new RecruitmentMailNotifier();

                foreach ($applications as $application) {

                    $result = $notifier->sendShortListReminderToManager($application);
                    if ($result) {
                        $shortListEvent = $application->getEventOfType(JobApplicationEvent::EVENT_SHORTLIST);
                        if ($shortListEvent) {
                            $shortListEvent->setNotificationStatus(JobApplicationEvent::NOTIFICATION_STATUS_REMINDER_SENT);
                            $shortListEvent->save();
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $log = new LogFileWriter();
            $log->writeLogDB('Error sending emails on shortlisted applicants:' . $e->getTraceAsString());
        }
    }

    /**
     * Create an employee based on a job application.
     *
     * @param JobApplication $jobApplication Job Application to create the employee from.
     * @throws RecruitmentControllerException if there is an error when creating employee
     */
    public function createEmployeeFromApplication($jobApplication) {

        $empInfo = new EmpInfo();

        // main information
        $employeeId = $empInfo->getLastId();
        $empInfo->setEmployeeId($employeeId);
        $empInfo->setEmpLastName($jobApplication->getLastName());
        $empInfo->setEmpFirstName($jobApplication->getFirstName());
        $empInfo->setEmpMiddleName($jobApplication->getMiddleName());
        $result = $empInfo->addEmpMain();

        // contact information
        $empInfo->setEmpStreet1($jobApplication->getStreet1());
        $empInfo->setEmpStreet2($jobApplication->getStreet2());
        $empInfo->setEmpCity($jobApplication->getCity());
        $empInfo->setEmpProvince($jobApplication->getProvince());
        $empInfo->setEmpCountry($jobApplication->getCountry());
        $empInfo->setEmpZipCode($jobApplication->getZip());
        $empInfo->setEmpHomeTelephone($jobApplication->getPhone());
        $empInfo->setEmpMobile($jobApplication->getMobile());
        $empInfo->setEmpOtherEmail($jobApplication->getEmail());
        $result = $empInfo->updateEmpContact();

        // job information
        $vacancy = JobVacancy::getJobVacancy($jobApplication->getVacancyId());
        $empInfo->setEmpJobTitle($vacancy->getJobTitleCode());
        $empInfo->setEmpStatus(0);
        $empInfo->setEmpEEOCat(0);
        $result = $empInfo->updateEmpJobInfo();

        $empNumber = $empInfo->getEmpId();
        // Copy interview attachments
        $events = $jobApplication->getEvents();
        foreach ($events as $event) {
            if ($event->getEventType() == JobApplicationEvent::EVENT_SCHEDULE_SECOND_INTERVIEW) {

                // Get event attachments
                $eventWithAttachments = JobApplicationEvent::getJobApplicationEvent($event->getId(), JobApplicationEvent::ATTACHMENT_BOTH);

                // save as employee attachments
                $attach1Name = $eventWithAttachments->getAttachment1Name();
                $attach1Type = $eventWithAttachments->getAttachment1Type();
                $attach1Data = $eventWithAttachments->getAttachment1Data();
                if (!empty($attach1Name) && !empty($attach1Data) && !empty($attach1Type)) {
                    $this->_saveEmployeeAttachment($empNumber, $attach1Name, $attach1Type, $attach1Data, 'Interview Questions');
                }

                $attach2Name = $eventWithAttachments->getAttachment2Name();
                $attach2Type = $eventWithAttachments->getAttachment2Type();
                $attach2Data = $eventWithAttachments->getAttachment2Data();
                if (!empty($attach2Name) && !empty($attach2Data) && !empty($attach2Type)) {
                    $this->_saveEmployeeAttachment($empNumber, $attach2Name, $attach2Type, $attach2Data, 'NEO Results');
                }
            }
        }

        return $empNumber;
    }

    private function _saveEmployeeAttachment($empNum, $fileName, $type, $data, $description) {

        $empAttachment = new EmpAttach();
        $empAttachment->setEmpId($empNum);
        $empAttachment->setEmpAttFilename($fileName);
        $size = strlen($data);
        $empAttachment->setEmpAttSize($size);
        $empAttachment->setEmpAttachment(addslashes($data));
        $empAttachment->setEmpAttType($type);
        $empAttachment->setEmpAttId($empAttachment->getLastRecord($empNum));
        $empAttachment->setEmpAttDesc($description);

        $empAttachment->addEmpAtt();
    }

	/**
	 * Redirect to given url or current page while displaying optional message
	 *
	 * @param String $message Message to display
	 * @param String $url URL
	 */
	public function redirect($message=null, $url = null) {

		if (isset($url)) {
			$mes = "";
			if (isset($message)) {
				$mes = "&message=";
			}
			$url=array($url.$mes);
			$id="";
		} else if (isset($message)) {
			preg_replace('/[&|?]+id=[A-Za-z0-9]*/', "", $_SERVER['HTTP_REFERER']);

			if (preg_match('/&/', $_SERVER['HTTP_REFERER']) > 0) {
				$message = "&message=".$message;
				$url = preg_split('/(&||\?)message=[A-Za-z0-9]*/', $_SERVER['HTTP_REFERER']);
			} else {
				$message = "?message=".$message;
			}

			if (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && !is_array($_REQUEST['id'])) {
				$id = "&id=".$_REQUEST['id'];
			} else {
				$id="";
			}
		} else {
			if (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && (preg_match('/&/', $_SERVER['HTTP_REFERER']) > 0)) {
				$id = "&id=".$_REQUEST['id'];
			} else if (preg_match('/&/', $_SERVER['HTTP_REFERER']) == 0){
				$id = "?id=".$_REQUEST['id'];
			} else {
				$id="";
			}
		}

		header("Location: ".$url[0].$message.$id);
	}

    /**
     * Show not authorized message
     */
    private function _notAuthorized() {
        trigger_error("Not Authorized!", E_USER_NOTICE);
    }
}
