<?php

class VacancyViewPage extends Page {

    public $viewVacancyComponent;
    public $vacancyListComponent;

    public function __construct($selenium) {
        parent::__construct($selenium);

        $this->viewVacancyComponent = new ViewVacancies($selenium);
        $this->vacancyListComponent = new VacancyList($selenium, "//div[@id='vacancySrchResults']", TRUE);
        //$this->list = new WorkExperienceList($this->selenium, "//div[@id='sectionWorkExperience']", true);
    }

}