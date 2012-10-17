<?php

class VacancyEditPage extends VacancyAddPage {

    public $attachmentComponent;

    public function __construct($selenium) {
        parent::__construct($selenium);

        $this->attachmentComponent = new AttachmentComponent($selenium);
    }

}