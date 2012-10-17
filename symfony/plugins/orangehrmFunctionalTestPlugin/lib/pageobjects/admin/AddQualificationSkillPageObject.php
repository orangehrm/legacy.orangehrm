<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddJobTitlePageObject
 *
 * @author intel
 */
class AddQualificationSkillPageObject extends Component {

    public $txtSkill = "skill_name";
    public $txtDescription = "skill_description";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Skills Qualification");
    }

    public function addQualificationSkill($skill, $description=null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtSkill, $skill);
        if ($description)
            $this->selenium->type($this->txtDescription, $description);
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function editQualificationSkill($skill, $description=null) {
        $this->addQualificationSkill($skill, $description);
    }

}