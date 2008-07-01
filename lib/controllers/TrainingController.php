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
require_once ROOT_PATH . '/lib/models/training/Training.php';
require_once ROOT_PATH . '/lib/extractor/common/EXTRACTOR_ViewList.php';
require_once ROOT_PATH . '/lib/extractor/training/EXTRACTOR_Training.php';

/**
 * Controller for training module
 */
class TrainingController {

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

			case 'Training' :

				$trainingExtractor = new EXTRACTOR_Training();

	            switch ($_GET['action']) {

	                case 'List' :
	                	$searchObject = $viewListExtractor->parseSearchData($_POST, $_GET);
	                    $this->_viewTrainings($searchObject);	                    	                
	                    break;
	                    
	                case 'View' :
	                	$id = isset($_GET['id'])? $_GET['id'] : null;
	                	$this->_viewTraining($id);
						break;
							                    
	                case 'ViewAdd' :
	                	$this->_viewAddTraining();
	                	break;
	                	
	                case 'Update' :
	                	$training = $trainingExtractor->parseUpdateData($_POST);
	                	$this->_saveTraining($training);
	                	break;	                    
	               	case 'Delete' :
	                    $ids = $_POST['chkID'];
	                    $this->_deleteTrainings($ids);	               		
	               		break;
	                		                    
	            }
                break;
	    }
    }
    
    /**
     * Save training in the database
     * @param Training $training training to save
     */
    private function _saveTraining($training) {
		if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor()) {
			try {
				$training->save();
	        	$message = 'UPDATE_SUCCESS';
	        	$this->redirect($message, '?trainingcode=Training&action=List');
			} catch (TrainingException $e) {
				$message = 'UPDATE_FAILURE';
	        	$this->redirect($message);
			}
		} else {
            $this->_notAuthorized();
		}
    }    
    
	/**	
	 * View list of trainings
	 * @param SearchObject Object with search parameters
	 */
    private function _viewTrainings($searchObject) {

		if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor()) {
			$supervisorEmpNum = ($this->authorizeObj->isSupervisor()) ? $this->authorizeObj->getEmployeeId(): null;
        	$list = Training::getListForView($searchObject->getPageNumber(), $searchObject->getSearchString(), $searchObject->getSearchField(), $searchObject->getSortField(), $searchObject->getSortOrder(), $supervisorEmpNum);
        	$count = Training::getCount($searchObject->getSearchString(), $searchObject->getSearchField(), $supervisorEmpNum);        	
        	$this->_viewList($searchObject->getPageNumber(), $count, $list, true, 1, 1);
		} else {
            $this->_notAuthorized();
		}
    }    

	/**
	 * View add training page
	 */
	private function _viewAddTraining() {
		if ($this->authorizeObj->isAdmin() || $this->authorizeObj->isSupervisor()) {
	    	$this->_viewTraining();
	    } else {
            $this->_notAuthorized();
		}
	}

    /**
     * View training
     * @param int $id Id of training. If empty, A new training is shown
     */
    private function _viewTraining($id = null) {

		$path = '/templates/training/viewTraining.php';

		try {
			$supervisorEmpNum = ($this->authorizeObj->isSupervisor()) ? $this->authorizeObj->getEmployeeId(): null;			
			if (empty($id)) {
				$training = new Training();			
				$availableEmployees = Training::getUnAssignedEmployees(null, $supervisorEmpNum);
				$assignedEmployees = array();								
			} else {
				$training = Training::getTraining($id);
				$availableEmployees = Training::getUnAssignedEmployees($id, $supervisorEmpNum);
				$assignedEmployees = Training::getAssignedEmployees($id);
			}			
			
			$objs['training'] = $training;
			$objs['trainingList'] = Training::getAll();
			$objs['assignedEmployees'] = $assignedEmployees;
			$objs['availableEmployees'] = $availableEmployees;
			$objs['authorizeObj'] = $this->authorizeObj;

			$template = new TemplateMerger($objs, $path);
			$template->display();
		} catch (TrainingException $e) {
			$message = 'UNKNOWN_FAILURE';
            $this->redirect($message);
		}
    }

	/**
	 * Delete trainings with given IDs
	 * @param Array $ids Array with training ID's to delete
	 */
    private function _deleteTrainings($ids) {
		if ($this->authorizeObj->isAdmin()) {
			try {
        		$count = Training::delete($ids);
        		$message = 'DELETE_SUCCESS';
			} catch (TrainingException $e) {
				$message = 'DELETE_FAILURE';
			}
            $this->redirect($message, '?trainingcode=Training&action=List');
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
	private function _viewList($pageNumber, $count, $list, $showSearch = true, $searchFieldCount = 1, $columnsToSkip = 0) {

        $formCreator = new FormCreator($_GET, $_POST);
        $formCreator->formPath = '/trainingview.php';
        $formCreator->popArr['currentPage'] = $pageNumber;
        $formCreator->popArr['list'] = $list;
        $formCreator->popArr['count'] = $count;
        $formCreator->popArr['showSearch'] = $showSearch;
        $formCreator->popArr['searchFieldCount'] = $searchFieldCount;
        $formCreator->popArr['columnsToSkip'] = $columnsToSkip;
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
