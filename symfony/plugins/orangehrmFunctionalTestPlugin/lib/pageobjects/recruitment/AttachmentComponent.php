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
class AttachmentComponentPIM extends Component {

    /**
     *
     * @var BasicList $list
     */
    public $btnFirstTimeAdd;
    public $ufilePath;
    public $description;
    public $btnUploadFile;
    public $btnAdd;
    public $btnDelete;
    public $list;
    public $chkSelectAll;
    public $lnkEdit;
    public $btnSaveCommentOnly;

    public function __construct(FunctionalTestcase $selenium) {
        parent::__construct($selenium, "attachment");
        $this->btnFirstTimeAdd = "btnAddAttachment";
        $this->btnAdd = "btnAddAttachment";
        $this->btnDelete = "btnDeleteAttachment";
        $this->ufilePath = "recruitmentAttachment_ufile";
        $this->description = "recruitmentAttachment_comment";
        $this->btnUploadFile = "btnSaveAttachment";
        $this->list = new BasicList($this->selenium, "//form[@id='frmRecDelAttachments']", true);
        $this->chkSelectAll = "attachmentsCheckAll";
        $this->btnSaveCommentOnly = "btnCommentOnly";
    }

    /**
     *
     * @return AttachmentBlankView
     */
    public function firstTimeAdd($path, $comment=null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnFirstTimeAdd);
        $this->selenium->type($this->ufilePath, $path);
        if ($comment)
            $this->selenium->type($this->description);
        $this->selenium->clickAndWait($this->btnUploadFile);
        return $this;
    }

    public function add($path, $comment=null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        $this->selenium->type($this->ufilePath, $path);
        if ($comment)
            $this->selenium->type($this->description);
        $this->selenium->clickAndWait($this->btnUploadFile);
        return $this;
    }

    public function delete($header, $itemName) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select($header, $itemName);
        $this->selenium->clickAndWait($this->btnDelete);
        return $this;
    }

    public function deleteAll() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->chkSelectAll);
        $this->selenium->clickAndWait($this->btnDelete);
        return $this;
    }

    public function editAttachment($fileName, $path=null, $comment=null) {
        $this->selenium->selectFrame("relative=top");
        $this->list->clickOntheItem("File Name", $fileName);
        $this->selenium->type($this->ufilePath, $path);
        $this->selenium->type($this->description, $comment);
        if ($path)
            $this->selenium->clickAndWait($this->btnUploadFile);
        else
            $this->selenium->clickAndWait($this->btnSaveCommentOnly);
        return $this;
    }

    public function getActionStatusMessage() {
        return $this->selenium->getText("attachmentsMessagebar");
    }

}