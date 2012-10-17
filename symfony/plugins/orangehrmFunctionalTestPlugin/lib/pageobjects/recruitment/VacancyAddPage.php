<?php

class VacancyAddPage extends Page {

    public $addVacancyComponent;
    public $footnoteComponent;

    public function __construct($selenium) {
        parent::__construct($selenium);

        $this->addVacancyComponent = new AddVacancy($selenium);
        $this->footnoteComponent = new FootnoteComponent($selenium);
    }

}