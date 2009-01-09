<?php
/**
 * "Visual Paradigm: DO NOT MODIFY THIS FILE!"
 * 
 * This is an automatic generated file. It will be regenerated every time 
 * you generate persistence class.
 * 
 * Modifying its content may cause the program not work, or your work may lost.
 */

/**
 * Licensee: Anonymous
 * License Type: Purchased
 */

/**
 * @orm Fluency
 */
class Fluency {
  public function deleteAndDissociate() {
    foreach($this->applicantLangInfo as $lApplicantLangInfo) {
      $lApplicantLangInfo->setFluency(null);
    }
    return true;
  }
  
  /**
   * @orm fluency_code int
   * @dbva id(autogenerate) 
   */
  private $fluencyCode;
  
  /**
   * @orm has many AppicantLanguageInformation inverse(fluency)
   * @dbva inverse(fluency_code) 
   */
  private $applicantLangInfo;
  
  public function &getFluencyCode() {
    return $this->fluencyCode;
  }
  
  
  public function setFluencyCode(&$fluencyCode) {
    $this->fluencyCode = $fluencyCode;
  }
  
  
  public function getApplicantLangInfo() {
    return $this->applicantLangInfo;
  }
  
  
  public function setApplicantLangInfo($applicantLangInfo) {
    $this->applicantLangInfo = $applicantLangInfo;
  }
  
  
  public function __toString() {
    $s = '';
    $s .= $this->fluencyCode;
    return $s;
  }
  
}

?>
