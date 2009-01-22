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
require_once ROOT_PATH . '/lib/models/eimadmin/JobTitle.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmployeeSearch.php';
require_once ROOT_PATH . '/lib/models/hrfunct/Employee.php';
require_once ROOT_PATH . '/lib/extractor/common/EXTRACTOR_Search.php';
require_once ROOT_PATH . '/lib/models/performance/PerformanceMeasure.php';
require_once ROOT_PATH . '/lib/models/performance/PerformanceReview.php';

require_once ROOT_PATH . '/lib/common/JobTitleConfig.php';
require_once ROOT_PATH . '/lib/models/performance/PerformanceMailNotifier.php';
require_once ROOT_PATH . '/lib/models/performance/SalaryReview.php';
require_once ROOT_PATH . '/lib/extractor/common/EXTRACTOR_ViewList.php';

require_once ROOT_PATH . '/lib/extractor/performance/EXTRACTOR_PerfMeasure.php';
require_once ROOT_PATH . '/lib/extractor/performance/EXTRACTOR_PerfReview.php';
require_once ROOT_PATH . '/lib/extractor/performance/EXTRACTOR_SalaryReview.php';
require_once ROOT_PATH . '/lib/extractor/common/EXTRACTOR_JobTitleConfig.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpInfo.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpBasSalary.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpRepTo.php';

/**
 * Controller for performance module
 */
class PerformanceController {

	private $authorizeObj;

    /**
     * Constructor
     */
    public function __construct() {
        if (isset($_SESSION) && isset($_SESSION['fname']) ) {
			$this->authorizeObj = new authorize($_SESSION['empID'], $_SESSION['isAdmin']);
        }

        $this->sendReviewReminderEmails();
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

		$viewListExtractor = new EXTRACTOR_ViewList();

		switch ($code) {

			case 'ReviewPeriod' :

	            switch ($_GET['action']) {

	                case 'List' :
                        $extractor = new EXTRACTOR_Search();
                        $searchObj = $extractor->parseSearch($_POST, new EmployeeSearch());
	                    $this->_viewEmployees($searchObj);
	                    break;
	            }
                break;

			case 'PerfMeasure' :

				$perfMeasureExtractor = new EXTRACTOR_PerfMeasure();

	            switch ($_GET['action']) {

	                case 'List' :
	                	$searchObject = $viewListExtractor->parseSearchData($_POST, $_GET);
	                    $this->_viewMeasures($searchObject);
	                    break;

	                case 'View' :
	                	$id = isset($_GET['id'])? $_GET['id'] : null;
	                	$this->_viewMeasure($id);
						break;

	                case 'ViewAdd' :
	                	$this->_viewAddMeasure();
	                	break;

	                case 'Update' :
	                	$perfMeasure = $perfMeasureExtractor->parseUpdateData($_POST);
	                	$this->_saveMeasure($perfMeasure);
	                	break;
	               	case 'Delete' :
	                    $ids = $_POST['chkID'];
	                    $this->_deleteMeasures($ids);
	               		break;

	            }
                break;

			case 'PerfReviews' :

				$perfReviewExtractor = new EXTRACTOR_PerfReview();

	            switch ($_GET['action']) {

	                case 'List' :
	                	$searchObject = $viewListExtractor->parseSearchData($_POST, $_GET);
	                    $this->_viewReviews($searchObject);
	                    break;

	                case 'View' :
	                	$id = isset($_GET['id'])? $_GET['id'] : null;
	                	$this->_viewReview($id);
						break;

	                case 'ViewAdd' :
	                	$this->_viewAddReview();
	                	break;

	                case 'ViewResults' :
	                	$this->_viewReviewResults($_GET['id']);
	                	break;

	                case 'Update' :
	                	$perfReview = $perfReviewExtractor->parseUpdateData($_POST);
	                	$this->_saveReview($perfReview);
	                	break;
	               	case 'Delete' :
	                    $ids = $_POST['chkID'];
	                    $this->_deleteReviews($ids);
	               		break;

	            }
                break;

			case 'JobTitleConfig' :

	            switch ($_GET['action']) {

	                case 'View' :
	                    $this->_viewJobTitleConfigPage();
	                    break;

	                case 'Update' :
						$jobTitleConfigExtractor = new EXTRACTOR_JobTitleConfig();
						$config = $jobTitleConfigExtractor->parseUpdateData($_POST);
						$this->_saveJobTitleConfig($config);
						break;
	            }
                break;

			case 'SalaryReview' :

				$salaryReviewExtractor = new EXTRACTOR_SalaryReview();

	            switch ($_GET['action']) {


	                case 'List' :
	                	$searchObject = $viewListExtractor->parseSearchData($_POST, $_GET);
	                    $this->_viewSalaryReviews($searchObject);
	                    break;

	                case 'View' :
	                	$id = isset($_GET['id'])? $_GET['id'] : null;
	                	$this->_viewSalaryReview($id);
						break;

	                case 'ViewAdd' :
	                	$this->_viewAddSalaryReview();
	                	break;

	                case 'Update' :
	                	$salaryReview = $salaryReviewExtractor->parseUpdateData($_POST);
	                	$this->_saveSalaryReview($salaryReview);
	                	break;
	               	case 'Delete' :
	                    $ids = $_POST['chkID'];
	                    $this->_deleteSalaryReviews($ids);
	               		break;
	                case 'Approve' :
	                	$salaryReview = $salaryReviewExtractor->parseUpdateData($_POST);
	                	$this->_approveSalaryReview($salaryReview);
	                	break;
	                case 'Reject' :
	                	$salaryReview = $salaryReviewExtractor->parseUpdateData($_POST);
	                	$this->_rejectSalaryReview($salaryReview);
	                	break;
	            }
                break;

	    }
    }

    /**
     * Save Performance measure in the database
     * @param PerformanceMeasure $measure Performance Measure to save
     */
    private function _saveMeasure($measure) {
		if ($this->authorizeObj->isAdmin()) {
			try {
				$measure->save();
	        	$message = 'UPDATE_SUCCESS';
	        	$this->redirect($message, '?perfcode=PerfMeasure&action=List');
			} catch (PerformanceMeasureException $e) {
				$message = 'UPDATE_FAILURE';
	        	$this->redirect($message);
			}
		} else {
            $this->_notAuthorized();
		}
    }

	/**
	 * View list of performance measures
	 * @param SearchObject Object with search parameters
	 */
    private function _viewMeasures($searchObject) {

		if ($this->authorizeObj->isAdmin()) {
        	$list = PerformanceMeasure::getListForView($searchObject->getPageNumber(), $searchObject->getSearchString(), $searchObject->getSearchField(), $searchObject->getSortField(), $searchObject->getSortOrder());
        	$count = PerformanceMeasure::getCount($searchObject->getSearchString(), $searchObject->getSearchField());
        	$this->_viewList($searchObject->getPageNumber(), $count, $list, true);
		} else {
            $this->_notAuthorized();
		}
    }

	/**
	 * View add Performance Measure page
	 */
	private function _viewAddMeasure() {
		if ($this->authorizeObj->isAdmin()) {
	    	$this->_viewMeasure();
	    } else {
            $this->_notAuthorized();
		}
	}

    /**
     * View Performance Measure
     * @param int $id Id of Performance Measure. If empty, A new Performance Measure is shown
     */
    private function _viewMeasure($id = null) {

		$path = '/templates/performance/viewPerformanceMeasure.php';

		try {
			if (empty($id)) {
				$perfMeasure = new PerformanceMeasure();
			} else {
				$perfMeasure = PerformanceMeasure::getPerformanceMeasure($id);
			}

			$jobTitle = new JobTitle();
			$jobTitles = $jobTitle->getJobTit();
			$jobTitles = is_null($jobTitles) ? array() : $jobTitles;
			$assignedJobTitles = $perfMeasure->getJobTitles();

			// Find available job titles

			if (empty($assignedJobTitles)) {
				$availableJobTitles = $jobTitles;
			} else {
				$availableJobTitles = array();

				foreach ($jobTitles as $title) {
					$jobTitleCode = $title[0];
					if (!array_key_exists($jobTitleCode, $assignedJobTitles)) {
						$availableJobTitles[] = $title;
					}
				}
			}

			$objs['perfMeasureList'] = PerformanceMeasure::getAll();
			$objs['perfMeasure'] = $perfMeasure;
			$objs['AvailableJobTitles'] = $availableJobTitles;
			$objs['AssignedJobTitles'] = $assignedJobTitles;

			$template = new TemplateMerger($objs, $path);
			$template->display();
		} catch (PerformanceMeasureException $e) {
			$message = 'UNKNOWN_FAILURE';
            $this->redirect($message);
		}
    }

	/**
	 * Delete Performance Measures with given IDs
	 * @param Array $ids Array with Performance Measure ID's to delete
	 */
    private function _deleteMeasures($ids) {
		if ($this->authorizeObj->isAdmin()) {
			try {
        		$count = PerformanceMeasure::delete($ids);
        		$message = 'DELETE_SUCCESS';
			} catch (PerformanceMeasureException $e) {
				$message = 'DELETE_FAILURE';
			}
            $this->redirect($message, '?perfcode=PerfMeasure&action=List');
		} else {
            $this->_notAuthorized();
		}
    }

	/**
	 * View list of performance reviews
	 * @param SearchObject Object with search parameters
	 */
    private function _viewReviews($searchObject) {

		if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor() || ($_SESSION['isApprover'])) {

			$supervisorEmpNum = ($this->authorizeObj->isSupervisor()) ? $this->authorizeObj->getEmployeeId(): null;
        	$list = PerformanceReview::getListForView($searchObject->getPageNumber(), $searchObject->getSearchString(), $searchObject->getSearchField(), $searchObject->getSortField(), $searchObject->getSortOrder(), $supervisorEmpNum);
        	$count = PerformanceReview::getCount($searchObject->getSearchString(), $searchObject->getSearchField(), $supervisorEmpNum);

        	/** Override module settings and allow supervisors to add reviews */
        	if ($this->authorizeObj->isSupervisor()) {
        		$locRights = $_SESSION['localRights'];
				$locRights['add'] = true;
				$locRights['delete'] = true;
        		$_SESSION['localRights'] = $locRights;
        	}

        	$this->_viewList($searchObject->getPageNumber(), $count, $list, true, 2);
		} else {
            $this->_notAuthorized();
		}
    }

    /**
     * Create a performance review. Used when automatically creating performance reviews.
     *
     * @param int $empNum Employee number
     * @param String $reviewDate Review Date (optional). If not given, will create review with default period.
     * @param String $notes Notes to be added to the review. Optional
     *
     * @return boolean True if successful, false otherwise
     */
    public function createReview($empNum, $notes = '', $reviewDate = null) {

    	if (empty($reviewDate)) {
    		$reviewTimeStamp = time() + PerformanceReview::DEFAULT_REVIEW_PERIOD * 30 * 24 * 60 * 60;
    		$reviewDate = date(LocaleUtil::STANDARD_DATE_FORMAT, $reviewTimeStamp);
    	}

    	$review = new PerformanceReview();
    	$review->setEmpNumber($empNum);
    	$review->setReviewNotes($notes);
    	$review->setReviewDate($reviewDate);
    	try {
	    	$review->save();
	    	return true;
		} catch (PerformanceReviewException $e) {
			return false;
		}
    }

    /**
     * Save Performance review in the database
     * @param PerformanceReview $review Performance Review to save
     */
    private function _saveReview($review) {
		if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor() || ($_SESSION['isApprover'])) {
			try {

				$id = $review->getId();
				if (!empty($id)) {
					$addNew = false;
					$oldReview = PerformanceReview::getPerformanceReview($id);
					$oldStatus = $oldReview->getStatus();
				} else {
					$addNew = true;
				}

				$review->save();

				if (!$addNew && ($review->getStatus() != $oldStatus) &&
						($review->getStatus() == PerformanceReview::STATUS_SUBMITTED_FOR_APPROVAL)) {
					$this->_sendApproveReviewEmail($review);
				}

	        	$message = 'UPDATE_SUCCESS';
	        	$this->redirect($message, '?perfcode=PerfReviews&action=List');
			} catch (PerformanceReviewException $e) {
				$message = 'UPDATE_FAILURE';
	        	$this->redirect($message);
			}
		} else {
            $this->_notAuthorized();
		}
    }

    private function _sendApproveReviewEmail($review) {
    	$receipients = JobTitleConfig::getEmployeesWithRole(JobTitleConfig::ROLE_REVIEW_APPROVER);

		if (!empty($receipients)) {
    		$mailNotifier = new PerformanceMailNotifier();
    		$mailNotifier->sendApproveReviewEmails($receipients, $review);
		}
    }

    private function sendReviewReminderEmails() {
    	$reviews = PerformanceReview::getReviewsPendingNotification();

		if (!empty($reviews)) {
    		$mailNotifier = new PerformanceMailNotifier();

	    	foreach($reviews as $review) {
    			$mailNotifier->sendPerformanceReviewReminder($review);
    			$review->setNotificationSent();
				try {
    				$review->save();
				} catch (PerformanceReviewException $e) {
					continue;
				}
	    	}
		}
    }

	/**
	 * View add Performance Review page
	 */
	private function _viewAddReview() {
		if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor()) {
	    	$this->_viewReview();
	    } else {
            $this->_notAuthorized();
		}
	}

	/**
	 * View add Performance Review Results page
	 */
	private function _viewReviewResults($id) {
		if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor() || ($_SESSION['isApprover'])) {
			$path = '/templates/performance/viewReviewResults.php';

			try {
				$perfReview = PerformanceReview::getPerformanceReview($id);
				$assignedMeasures = $perfReview->getPerformanceMeasures();

				$objs['perfReview'] = $perfReview;
				$objs['AssignedPerfMeasures'] = $assignedMeasures;
				$objs['authorizeObj'] = $this->authorizeObj;

				$template = new TemplateMerger($objs, $path);
				$template->display();
			} catch (PerformanceReviewException $e) {
				$message = 'UNKNOWN_FAILURE';
	            $this->redirect($message);
			}
	    } else {
            $this->_notAuthorized();
		}
	}

    /**
     * View Performance Reviews
     * @param int $id Id of Performance Review. If empty, A new Performance Review is shown
     */
    private function _viewReview($id = null) {

		$path = '/templates/performance/viewPerformanceReview.php';
		$subordinates = null;
		try {
			$perfMeasures = null;

			if (empty($id)) {
				$perfReview = new PerformanceReview();
					if ($this->authorizeObj->isSupervisor()) {
					$repObj = new EmpRepTo();
					$subordinates = $repObj->getEmpSubDetails($_SESSION['empID']);
					}
			} else {
				$perfReview = PerformanceReview::getPerformanceReview($id);

				$empNum = $perfReview->getEmpNumber();
				$empInfo = new EmpInfo;
				$empJobInfo = $empInfo->filterEmpJobInfo($empNum);

				if (isset($empJobInfo[0])) {
					$jobTitleCode = $empJobInfo[0][2];
					if (!empty($jobTitleCode)) {
						$perfMeasures = PerformanceMeasure::getAllForJobTitle($jobTitleCode);
					}
				}
			}

			$perfMeasures = is_null($perfMeasures) ? PerformanceMeasure::getAll() : $perfMeasures;
			$assignedMeasures = $perfReview->getPerformanceMeasures();

			// Find available performance measures

			if (empty($assignedMeasures)) {
				$availableMeasures = $perfMeasures;
			} else {
				$availableMeasures = array();

				foreach ($perfMeasures as $measure) {
					$perfMeasureId = $measure->getId();
					if (!array_key_exists($perfMeasureId, $assignedMeasures)) {
						$availableMeasures[] = $measure;
					}
				}
			}

			$objs['perfReview'] = $perfReview;
			$objs['AvailablePerfMeasures'] = $availableMeasures;
			$objs['AssignedPerfMeasures'] = $assignedMeasures;
			$objs['employees'] = $assignedMeasures;
			$objs['authorizeObj'] = $this->authorizeObj;
			$objs['subordinates'] =$subordinates;

			$template = new TemplateMerger($objs, $path);
			$template->display();
		} catch (PerformanceReviewException $e) {
			$message = 'UNKNOWN_FAILURE';
            $this->redirect($message);
		}
    }

	/**
	 * Delete Performance Reviews with given IDs
	 * @param Array $ids Array with Performance Review ID's to delete
	 */
    private function _deleteReviews($ids) {
		if ($this->authorizeObj->isAdmin()|| $this->authorizeObj->isSupervisor()) {
			try {
        		$count = PerformanceReview::delete($ids);
        		$message = 'DELETE_SUCCESS';
			} catch (PerformanceReviewException $e) {
				$message = 'DELETE_FAILURE';
			}
            $this->redirect($message, '?perfcode=PerfReviews&action=List');
		} else {
            $this->_notAuthorized();
		}
    }

	/**
	 * View list of performance reviews
	 * @param SearchObject Object with search parameters
	 */
    private function _viewSalaryReviews($searchObject) {

		if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor() || $_SESSION['isSalaryApprover']) {

			$supervisorEmpNum = ($this->authorizeObj->isSupervisor() && !$_SESSION['isSalaryApprover']) ? $this->authorizeObj->getEmployeeId(): null;
        	$list = SalaryReview::getListForView($searchObject->getPageNumber(), $searchObject->getSearchString(), $searchObject->getSearchField(), $searchObject->getSortField(), $searchObject->getSortOrder(), $supervisorEmpNum);
        	$count = SalaryReview::getCount($searchObject->getSearchString(), $searchObject->getSearchField(), $supervisorEmpNum);

        	/** Override module settings and allow supervisors to add salary reviews */
        	if ($this->authorizeObj->isSupervisor()) {
        		$locRights = $_SESSION['localRights'];
				$locRights['add'] = true;
				$locRights['delete'] = true;
        		$_SESSION['localRights'] = $locRights;
        	}

        	$this->_viewList($searchObject->getPageNumber(), $count, $list, true);
		} else {
            $this->_notAuthorized();
		}
    }


    /**
     * Approve Salary review
     * @param SalaryReview $review Salary Review to approve
     */
    private function _approveSalaryReview($review) {

		if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor() || $_SESSION['isSalaryApprover']) {
			$review->setStatus(SalaryReview::STATUS_APPROVED);

			try {
				$review->save();

				// Update salary
				$result = $this->_changeEmployeeSalary($review->getEmpNumber(), $review->getIncrease());
				if (!$result) {
					$message = 'SALARY_CHANGE_FAILURE';
	        		$this->redirect($message);
				} else {
		        	$message = 'UPDATE_SUCCESS';
		        	$this->redirect($message, '?perfcode=SalaryReview&action=List');
				}
			} catch (SalaryReviewException $e) {
				$message = 'UPDATE_FAILURE';
	        	$this->redirect($message);
			}

		} else {
            $this->_notAuthorized();
		}
    }

    /**
     * Reject Salary review
     * @param SalaryReview $review Salary Review to reject
     */
    private function _rejectSalaryReview($review) {
		if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor() || $_SESSION['isSalaryApprover']) {
			$review->setStatus(SalaryReview::STATUS_REJECTED);

			try {

				// Get current review status
				$currentReview = SalaryReview::getSalaryReview($review->getId());

				$review->save();

				// Update salary by undoing salary change only if rejecting currently approved review.
				if ($currentReview->getStatus() == SalaryReview::STATUS_APPROVED) {

					$salaryChange = -1 * $review->getIncrease();
					$result = $this->_changeEmployeeSalary($review->getEmpNumber(), $salaryChange);
					if (!$result) {
						$message = 'SALARY_CHANGE_FAILURE';
		        		$this->redirect($message);
		        		return;
					}
				}

	        	$message = 'UPDATE_SUCCESS';
	        	$this->redirect($message, '?perfcode=SalaryReview&action=List');
			} catch (SalaryReviewException $e) {
				$message = 'UPDATE_FAILURE';
	        	$this->redirect($message);
			}

		} else {
            $this->_notAuthorized();
		}
    }

	/**
	 * Change given employees salary by given amount
	 * Amount can be positive or negative
	 *
	 * @return True if successfully changed, false otherwise
	 */
    private function _changeEmployeeSalary($empNum, $amount) {

    	$empBasicSalary = new EmpBasSalary();

    	$salaryInfo = $empBasicSalary->getAssEmpBasSal($empNum);

    	/* No salary information available */
    	if (empty($salaryInfo)) {
    		return false;
    	}

    	if (isset($salaryInfo[0][3]) && !empty($salaryInfo[0][3])) {
    		$currentBasicSalary = $salaryInfo[0][3];
	    	$newBasicSalary = $currentBasicSalary + $amount;

	    	$empBasicSalary->setEmpId($empNum);
	    	$empBasicSalary->setEmpSalGrdCode($salaryInfo[0][1]);
	    	$empBasicSalary->setEmpCurrCode($salaryInfo[0][2]);
	    	$empBasicSalary->setEmpBasSal($newBasicSalary);
	    	return $empBasicSalary->updateEmpBasSal();
    	} else {
    		return false;
    	}

    }

    /**
     * Save Salary review in the database
     * @param SalaryReview $review Salary Review to save
     */
    private function _saveSalaryReview($review) {
		if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor()) {
			try {
				$id = $review->getId();
				$addNew = (empty($id));

				/* Check if employee has salary defined */
				if ($addNew) {
					$currentSalary = self::_getBaseSalary($review->getEmpNumber());
					if (empty($currentSalary)) {
						$message = 'NO_SALARY_DEFINED_FAILURE';
			        	$this->redirect($message);
						return;
					}
				}

				$review->save();

				if ($addNew) {
					$this->_sendSalaryReviewNotice($review);
				}

	        	$message = 'UPDATE_SUCCESS';
	        	$this->redirect($message, '?perfcode=SalaryReview&action=List');
			} catch (SalaryReviewException $e) {
				$message = 'UPDATE_FAILURE';
	        	$this->redirect($message);
			}
		} else {
            $this->_notAuthorized();
		}
    }

    private function _sendSalaryReviewNotice($review) {

    	$receipients = JobTitleConfig::getEmployeesWithRole(JobTitleConfig::ROLE_SALARY_REVIEW_APPROVER);

		if (!empty($receipients)) {
    		$mailNotifier = new PerformanceMailNotifier();
    		$mailNotifier->sendSalaryReviewNoticeEmails($receipients, $review);
		}
    }
	/**
	 * Delete Salary Reviews with given IDs
	 * @param Array $ids Array with Salary Review ID's to delete
	 */
    private function _deleteSalaryReviews($ids) {
		if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor()) {
			try {
        		$count = SalaryReview::delete($ids);
        		$message = 'DELETE_SUCCESS';
			} catch (SalaryReviewException $e) {
				$message = 'DELETE_FAILURE';
			}
            $this->redirect($message, '?perfcode=SalaryReview&action=List');
		} else {
            $this->_notAuthorized();
		}
    }

	/**
	 * View add Salary Review page
	 */
	private function _viewAddSalaryReview() {
		if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor()) {
	    	$this->_viewSalaryReview();
	    } else {
            $this->_notAuthorized();
		}
	}

    /**
     * View Salary Reviews
     * @param int $id Id of Salary Review. If empty, A new Salary Review is shown
     */
    private function _viewSalaryReview($id = null) {

		$path = '/templates/performance/viewSalaryReview.php';

		try {

			$currentSalary = '';
			$subordinates = '';

			if (empty($id)) {
				$salaryReview = new SalaryReview();

				if ($this->authorizeObj->isSupervisor()) {
					$repObj = new EmpRepTo();
					$subordinates = $repObj->getEmpSubDetails($_SESSION['empID']);
				}
			} else {
				$salaryReview = SalaryReview::getSalaryReview($id);
				$currentSalary = self::_getBaseSalary($salaryReview->getEmpNumber());
			}

			$objs['salaryReview'] = $salaryReview;
			$objs['authorizeObj'] = $this->authorizeObj;
			$objs['currentSalary'] = $currentSalary;
			$objs['subordinates'] = $subordinates;

			$template = new TemplateMerger($objs, $path);
			$template->display();
		} catch (SalaryReviewException $e) {
			$message = 'UNKNOWN_FAILURE';
            $this->redirect($message);
		}
    }

	/**
	 * Get the base salary for the given employee
	 *
	 * @param int $empNum Employee number
	 *
	 * @return Base salary amount (with currency) or null if not defined. Eg: "120 USD"
	 */
    public static function _getBaseSalary($empNum) {

    	$salary = null;

    	$empBasicSalary = new EmpBasSalary();
    	$salaryInfo = $empBasicSalary->getAssEmpBasSal($empNum);

    	if (!empty($salaryInfo) && isset($salaryInfo[0][3]) && !empty($salaryInfo[0][3])) {
    		$currentSalary = $salaryInfo[0][3];
    		$currency = $salaryInfo[0][2];
    		$salary = $currentSalary . ' ' . $currency;
    	}

    	return $salary;
    }

	/**
	 * Generic method to display a list
	 * @param int $pageNumber Page Number
	 * @param int $count Total number of results
	 * @param Array $list results (in current page)
	 */
	private function _viewList($pageNumber, $count, $list, $showSearch = true, $searchFieldCount = 1) {

        $formCreator = new FormCreator($_GET, $_POST);
        $formCreator->formPath = '/performanceview.php';
        $formCreator->popArr['currentPage'] = $pageNumber;
        $formCreator->popArr['list'] = $list;
        $formCreator->popArr['count'] = $count;
        $formCreator->popArr['showSearch'] = $showSearch;
        $formCreator->popArr['searchFieldCount'] = $searchFieldCount;
        $formCreator->display();
	}

    /**
     * Display list Employees
     * @param Object $searchObj Search Object extended from AbstractSearch class
     *
     */
    private function _viewEmployees($searchObj) {

        if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor()) {
            $managerId = $this->authorizeObj->isAdmin()? null : $this->authorizeObj->getEmployeeId();

            $searchObj->search();

            $path = '/templates/common/search.php';
            $objs['titleVar'] = 'lang_Performance_Assign_Review_Period';
            $objs['searchObj'] = $searchObj;
            $template = new TemplateMerger($objs, $path, null, null);
            $template->display();
        } else {
            $this->_notAuthorized();
        }
    }

    /**
     * View Job title configuration page
     */
    private function _viewJobTitleConfigPage() {

		$path = '/templates/common/jobTitleConfiguration.php';

		try {

			$role = isset($_GET['role'])? $_GET['role'] : JobTitleConfig::ROLE_REVIEW_APPROVER;

			$jobTitle = new JobTitle();
			$jobTitles = $jobTitle->getJobTit();
			$jobTitles = is_null($jobTitles) ? array() : $jobTitles;
			$jobTitleConfig = JobTitleConfig::getJobTitleConfig($role);
			$assignedJobTitles = $jobTitleConfig->getJobTitles();

			// Find available job titles
			if (empty($assignedJobTitles)) {
				$availableJobTitles = $jobTitles;
			} else {
				$availableJobTitles = array();

				foreach ($jobTitles as $title) {
					$jobTitleCode = $title[0];
					if (!array_key_exists($jobTitleCode, $assignedJobTitles)) {
						$availableJobTitles[] = $title;
					}
				}
			}

			$objs['jobTitleConfig'] = $jobTitleConfig;
			$objs['roleList'] = array(JobTitleConfig::ROLE_REVIEW_APPROVER, JobTitleConfig::ROLE_SALARY_REVIEW_APPROVER);
			$objs['AvailableJobTitles'] = $availableJobTitles;
			$objs['AssignedJobTitles'] = $assignedJobTitles;

			$template = new TemplateMerger($objs, $path);
			$template->display();
		} catch (JobTitleConfigException $e) {

			$message = 'UNKNOWN_FAILURE';
            $this->redirect($message);
		}
    }

    /**
     * Save Job title configuration to the database
     * @param JobTitleConfig $config Job Title Config to save
     */
    private function _saveJobTitleConfig($config) {
		if ($this->authorizeObj->isAdmin()) {
			try {
				$config->save();
	        	$message = 'UPDATE_SUCCESS';
	        	$this->redirect($message);
			} catch (JobTitleConfigException $e) {
				$message = 'UPDATE_FAILURE';
	        	$this->redirect($message);
			}
		} else {
            $this->_notAuthorized();
		}
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
?>
