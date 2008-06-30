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
require_once ROOT_PATH . '/lib/extractor/common/EXTRACTOR_Search.php';
require_once ROOT_PATH . '/lib/models/healthAndSafety/RiskAssessment.php';
require_once ROOT_PATH . '/lib/models/healthAndSafety/ErgonomicAssessment.php';
require_once ROOT_PATH . '/lib/models/healthAndSafety/EmpInjury.php';
require_once ROOT_PATH . '/lib/extractor/common/EXTRACTOR_ViewList.php';
require_once ROOT_PATH . '/lib/extractor/healthAndSafety/EXTRACTOR_ErgonomicAssessment.php';
require_once ROOT_PATH . '/lib/extractor/healthAndSafety/EXTRACTOR_Injury.php';
require_once ROOT_PATH . '/lib/extractor/healthAndSafety/EXTRACTOR_RiskAssessment.php';

/**
 * Controller for performance module
 */
class HealthAndSafetyController {

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

		$viewListExtractor = new EXTRACTOR_ViewList();
		
		switch ($code) {

			case 'ErgonomicAssessments' :
			
				$ergonomicExtractor = new EXTRACTOR_ErgonomicAssessment();

	            switch ($_GET['action']) {

	                case 'List' :
	                	$searchObject = $viewListExtractor->parseSearchData($_POST, $_GET);
	                    $this->_viewErgonomicAssessments($searchObject);	                    	                
	                    break;
	                    
	                case 'View' :
	                	$id = isset($_GET['id'])? $_GET['id'] : null;
	                	$this->_viewErgonomicAssessment($id);
						break;
							                    
	                case 'ViewAdd' :
	                	$this->_viewAddErgonomicAssessment();
	                	break;
	                	
	                case 'Update' :
	                	$assessment = $ergonomicExtractor->parseUpdateData($_POST);
	                	$this->_saveErgonomicAssessment($assessment);
	                	break;	                    
	               	case 'Delete' :
	                    $ids = $_POST['chkID'];
	                    $this->_deleteErgonomicAssessments($ids);	               		
	               		break;
	                		                    
	            }
                break;

			case 'Injuries' :

				$injuryExtractor = new EXTRACTOR_Injury();
				
	            switch ($_GET['action']) {

	                case 'List' :
	                	$searchObject = $viewListExtractor->parseSearchData($_POST, $_GET);
	                    $this->_viewInjuries($searchObject);	                    	                
	                    break;
	                    
	                case 'View' :
	                	$id = isset($_GET['id'])? $_GET['id'] : null;
	                	$this->_viewInjury($id);
						break;
							                    
	                case 'ViewAdd' :
	                	$this->_viewAddInjury();
	                	break;

	                	
	                case 'Update' :
	                	$injury = $injuryExtractor->parseUpdateData($_POST);
	                	$this->_saveInjury($injury);
	                	break;	                    
	               	case 'Delete' :
	                    $ids = $_POST['chkID'];
	                    $this->_deleteInjuries($ids);	               		
	               		break;
	                    
	            }
                break;                                                                

			case 'RiskAssessments' :

				$riskExtractor = new EXTRACTOR_RiskAssessment();

	            switch ($_GET['action']) {

	                case 'List' :
	                	$searchObject = $viewListExtractor->parseSearchData($_POST, $_GET);
	                    $this->_viewRiskAssessments($searchObject);	                    	                
	                    break;
	                    
	                case 'View' :
	                	$id = isset($_GET['id'])? $_GET['id'] : null;
	                	$this->_viewRiskAssessment($id);
						break;
							                    
	                case 'ViewAdd' :
	                	$this->_viewAddRiskAssessment();
	                	break;
	                	
	                case 'Update' :
	                	$assessment = $riskExtractor->parseUpdateData($_POST);
	                	$this->_saveRiskAssessment($assessment);
	                	break;	                    
	               	case 'Delete' :
	                    $ids = $_POST['chkID'];
	                    $this->_deleteRiskAssessments($ids);	               		
	               		break;
	                		                    
	            }
                break;

	    }
    }
    
    /**
     * Save ergonomic assessment in the database
     * @param ErgonomicAssessment $assessment Ergonomic Assessment to save
     */
    private function _saveErgonomicAssessment($assessment) {
		if ($this->authorizeObj->isAdmin()) {
			try {
				$assessment->save();
	        	$message = 'UPDATE_SUCCESS';
	        	$this->redirect($message, '?healthcode=ErgonomicAssessments&action=List');
			} catch (ErgonomicAssessmentException $e) {
				$message = 'UPDATE_FAILURE';
	        	$this->redirect($message);
			}
		} else {
            $this->_notAuthorized();
		}
    }    
    
	/**	
	 * View list of ergonomic assessments
	 * @param SearchObject Object with search parameters
	 */
    private function _viewErgonomicAssessments($searchObject) {

		if ($this->authorizeObj->isAdmin()) {
        	$list = ErgonomicAssessment::getListForView($searchObject->getPageNumber(), $searchObject->getSearchString(), $searchObject->getSearchField(), $searchObject->getSortField(), $searchObject->getSortOrder());
        	$count = ErgonomicAssessment::getCount($searchObject->getSearchString(), $searchObject->getSearchField());        	
        	$this->_viewList($searchObject->getPageNumber(), $count, $list, true);
		} else {
            $this->_notAuthorized();
		}
    }    

	/**
	 * View add Ergonomic Assessment page
	 */
	private function _viewAddErgonomicAssessment() {
		if ($this->authorizeObj->isAdmin()) {
	    	$this->_viewErgonomicAssessment();
	    } else {
            $this->_notAuthorized();
		}
	}

    /**
     * View Ergonomic Assessment
     * @param int $id Id of Ergonomic Assessment. If empty, A new Ergonomic Assessment is shown
     */
    private function _viewErgonomicAssessment($id = null) {

		$path = '/templates/healthAndSafety/viewErgonomicAssessment.php';

		try {
			if (empty($id)) {
				$ergonomicAssessment = new ErgonomicAssessment();
			} else {
				$ergonomicAssessment = ErgonomicAssessment::getErgonomicAssessment($id);
			}

			$objs['ergonomicAssessment'] = $ergonomicAssessment;
			$objs['authorizeObj'] = $this->authorizeObj;

			$template = new TemplateMerger($objs, $path);
			$template->display();
		} catch (ErgonomicAssessmentException $e) {
			$message = 'UNKNOWN_FAILURE';
            $this->redirect($message);
		}
    }

	/**
	 * Delete Ergonomic Assessments with given IDs
	 * @param Array $ids Array with Ergonomic Assessment ID's to delete
	 */
    private function _deleteErgonomicAssessments($ids) {
		if ($this->authorizeObj->isAdmin()) {
			try {
        		$count = ErgonomicAssessment::delete($ids);
        		$message = 'DELETE_SUCCESS';
			} catch (ErgonomicAssessmentException $e) {
				$message = 'DELETE_FAILURE';
			}
            $this->redirect($message, '?healthcode=ErgonomicAssessments&action=List');
		} else {
            $this->_notAuthorized();
		}
    }

    /**
     * Save injury in the database
     * @param EmpInjury $injury EmpInjury to save
     */
    private function _saveInjury($injury) {
		if ($this->authorizeObj->isAdmin()) {
			try {
				$injury->save();
	        	$message = 'UPDATE_SUCCESS';
	        	$this->redirect($message, '?healthcode=Injuries&action=List');
			} catch (EmpInjuryException $e) {
				$message = 'UPDATE_FAILURE';
	        	$this->redirect($message);
			}
		} else {
            $this->_notAuthorized();
		}
    }    
	/**	
	 * View list of injuries
	 * @param SearchObject Object with search parameters
	 */
    private function _viewInjuries($searchObject) {

		if ($this->authorizeObj->isAdmin()) {
        	$list = EmpInjury::getListForView($searchObject->getPageNumber(), $searchObject->getSearchString(), $searchObject->getSearchField(), $searchObject->getSortField(), $searchObject->getSortOrder());
        	$count = EmpInjury::getCount($searchObject->getSearchString(), $searchObject->getSearchField());        	
        	$this->_viewList($searchObject->getPageNumber(), $count, $list, true);
		} else {
            $this->_notAuthorized();
		}
    }    

	/**
	 * View add Injury page
	 */
	private function _viewAddInjury() {
		if ($this->authorizeObj->isAdmin()) {
	    	$this->_viewInjury();
	    } else {
            $this->_notAuthorized();
		}
	}

    /**
     * View employee injury
     * @param int $id Id of Injury. If empty, A new injury is shown
     */
    private function _viewInjury($id = null) {

		$path = '/templates/healthAndSafety/viewInjury.php';

		try {
			if (empty($id)) {
				$injury = new EmpInjury();
			} else {
				$injury = EmpInjury::getEmpInjury($id);
			}

			$objs['injury'] = $injury;
			$objs['authorizeObj'] = $this->authorizeObj;

			$template = new TemplateMerger($objs, $path);
			$template->display();
		} catch (EmpInjuryException $e) {
			$message = 'UNKNOWN_FAILURE';
            $this->redirect($message);
		}
    }

	/**
	 * Delete Injuries with given IDs
	 * @param Array $ids Array with Injury ID's to delete
	 */
    private function _deleteInjuries($ids) {
		if ($this->authorizeObj->isAdmin()) {
			try {
        		$count = EmpInjury::delete($ids);
        		$message = 'DELETE_SUCCESS';
			} catch (EmpInjuryException $e) {
				$message = 'DELETE_FAILURE';
			}
            $this->redirect($message, '?healthcode=Injuries&action=List');
		} else {
            $this->_notAuthorized();
		}
    }
    
    /**
     * Save risk assessment in the database
     * @param RiskAssessment $assessment Risk Assessment to save
     */
    private function _saveRiskAssessment($assessment) {
		if ($this->authorizeObj->isAdmin()) {
			try {
				$assessment->save();
	        	$message = 'UPDATE_SUCCESS';
	        	$this->redirect($message, '?healthcode=RiskAssessments&action=List');
			} catch (RiskAssessmentException $e) {
				$message = 'UPDATE_FAILURE';
	        	$this->redirect($message);
			}
		} else {
            $this->_notAuthorized();
		}
    }    
    
	/**	
	 * View list of risk assessments
	 * @param SearchObject Object with search parameters
	 */
    private function _viewRiskAssessments($searchObject) {

		if ($this->authorizeObj->isAdmin()) {
        	$list = RiskAssessment::getListForView($searchObject->getPageNumber(), $searchObject->getSearchString(), $searchObject->getSearchField(), $searchObject->getSortField(), $searchObject->getSortOrder());
        	$count = RiskAssessment::getCount($searchObject->getSearchString(), $searchObject->getSearchField());        	
        	$this->_viewList($searchObject->getPageNumber(), $count, $list, true);
		} else {
            $this->_notAuthorized();
		}
    }    

	/**
	 * View add Risk Assessment page
	 */
	private function _viewAddRiskAssessment() {
		if ($this->authorizeObj->isAdmin()) {
	    	$this->_viewRiskAssessment();
	    } else {
            $this->_notAuthorized();
		}
	}

    /**
     * View Risk Assessment
     * @param int $id Id of Risk Assessment. If empty, A new Risk Assessment is shown
     */
    private function _viewRiskAssessment($id = null) {

		$path = '/templates/healthAndSafety/viewRiskAssessment.php';

		try {
			if (empty($id)) {
				$riskAssessment = new RiskAssessment();
			} else {
				$riskAssessment = RiskAssessment::getRiskAssessment($id);
			}

			$objs['riskAssessment'] = $riskAssessment;
			$objs['authorizeObj'] = $this->authorizeObj;

			$template = new TemplateMerger($objs, $path);
			$template->display();
		} catch (RiskAssessmentException $e) {
			$message = 'UNKNOWN_FAILURE';
            $this->redirect($message);
		}
    }

	/**
	 * Delete Risk Assessments with given IDs
	 * @param Array $ids Array with Risk Assessment ID's to delete
	 */
    private function _deleteRiskAssessments($ids) {
		if ($this->authorizeObj->isAdmin()) {
			try {
        		$count = RiskAssessment::delete($ids);
        		$message = 'DELETE_SUCCESS';
			} catch (RiskAssessmentException $e) {
				$message = 'DELETE_FAILURE';
			}
            $this->redirect($message, '?healthcode=RiskAssessments&action=List');
		} else {
            $this->_notAuthorized();
		}
    }
   
    
	/**
	 * Generic method to display a list
	 * @param int $pageNumber Page Number
	 * @param int $count Total number of results
	 * @param Array $list results (in current page)
	 */
	private function _viewList($pageNumber, $count, $list, $showSearch = true, $searchFieldCount = 1) {

        $formCreator = new FormCreator($_GET, $_POST);
        $formCreator->formPath = '/healthview.php';
        $formCreator->popArr['currentPage'] = $pageNumber;
        $formCreator->popArr['list'] = $list;
        $formCreator->popArr['count'] = $count;
        $formCreator->popArr['showSearch'] = $showSearch;
        $formCreator->popArr['searchFieldCount'] = $searchFieldCount;
        $formCreator->display();
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
