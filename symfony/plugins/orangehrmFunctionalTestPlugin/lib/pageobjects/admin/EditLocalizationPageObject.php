<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EditOrganizationGeneralInformationPageObject
 *
 * @author intel
 */
class EditLocalizationPageObject extends Component {

    public $cmbLanguage = "localization_dafault_language";
    public $chkBoxBrowserLanguage = "localization_use_browser_language";
    public $cmbDateFormat = "localization_default_date_format";
    public $btnSave = "btnSave";

    public function __construct($selenium) {
        parent::__construct($selenium, "Configure Localization");
    }

    public function editLocalization($language=null, $checkBrowserLanguage=null, $dateFormat=null) {
        $this->selenium->selectFrame("relative=top");
        if ($language)
            $this->selenium->select($this->cmbLanguage, $language);
        if ($checkBrowserLanguage == "yes" && !$this->selenium->isChecked($this->chkBoxBrowserLanguage))
            $this->selenium->click($this->chkBoxBrowserLanguage);
        if ($checkBrowserLanguage == "no" && $this->selenium->isChecked($this->chkBoxBrowserLanguage))
            $this->selenium->click($this->chkBoxBrowserLanguage);
        if ($dateFormat)
            $this->selenium->select($this->cmbDateFormat, $dateFormat);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function getSuccessfullyAssignedMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}