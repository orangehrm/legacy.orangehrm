<?php

class FootnoteComponent extends Component {

    

    public function __construct(FunctionalTestCase $selenium) {
        parent::__construct($selenium, "FootnoteComponent");
        
    }

    public function loadURLOfPage($page) {
        $url = $this->config->getLoginURL() . "/symfony/web/index.php/recruitmentApply/jobs.";
        
        if ($page == "Rss")
            $this->selenium->open($url . "rss");
        if ($page == "Web")
            $this->selenium->open($url . "html");
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function getVacancyIsPresent($page) {
        if ($page == "Rss")
            $text = $this->selenium->getText("//div[@id='feedContent']");
        if ($page == "Web")
            $text = $this->selenium->getText("//div[@id='toggleJobList']");

        if ($text)
            return true;
        else
            return false;
    }

    public function applyForVacancy($selenium) {
        //define applyButton in constructor
        
        $this->selenium->click("//input[@name='applyButton']");
        
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
        return new AddCandidate($selenium);
    }

    public function getSavedSuccessfullyMessage() {
        return $this->selenium->getText("//div[@id='messagebar']/span");
    }

}