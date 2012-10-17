<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewOrganizationLocationsPageObject
 *
 * @author intel
 */
class ViewOrganizationLocationsPageObject extends Component {

    public $txtLocationName = "searchLocation_name";
    public $txtCity = "searchLocation_city";
    public $cmbCountry = "searchLocation_country";
    public $btnSearch = "btnSearch";
    public $btnReset = "btnReset";
    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $list;
    public $dialogDeleteBtn = "dialogDeleteBtn";
    public $dialogCancelBtn = "dialogCancelBtn";

    public function __construct($selenium) {
        parent::__construct($selenium, "View Organization Locations");
        $this->list = new LocationsList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
    }

    public function viewLocationRecords($locationName, $city=null, $country=null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtLocationName, $locationName);
        if ($city) {
            $this->selenium->type($this->txtCity, $city);
        }
        if ($country) {
            $this->selenium->select($this->cmbCountry, $country);
        }
        $this->selenium->clickAndWait($this->btnSearch);
    }

    public function goToAddLocation() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddOrganizationLocationPageObject($this->selenium);
    }

    public function deleteLocation($location) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Name", $location);
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function deleteAllLocations() {
        $this->selenium->selectFrame("relative=top");
        $this->list->selectAllInTheList();
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}