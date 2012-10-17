<?php

class TitledList extends BasicList {

    function __construct($selenium, $xpathOfList, $selectAllPresent=FALSE) {
        parent::__construct($selenium, $xpathOfList, $selectAllPresent);
    }

    public function getTitle() {
        $this->selenium->getText($this->xpathOfList . "//h2");
    }

}