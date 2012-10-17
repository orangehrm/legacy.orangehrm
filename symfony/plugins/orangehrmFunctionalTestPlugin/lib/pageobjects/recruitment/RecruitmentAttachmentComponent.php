<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AttachmentComponent
 *
 * @author irshad
 */
class RecruitmentAttachmentComponent extends AttachmentComponent {

    /**
     *
     * @var BasicList $list
     */
    public function __construct(FunctionalTestcase $selenium) {
        parent::__construct($selenium);

        $this->ufilePath = "recruitmentAttachment_ufile";
        $this->description = "recruitmentAttachment_comment";



        $this->list = new BasicList($this->selenium, "//form[@id='frmRecDelAttachments']", true);
    }

}