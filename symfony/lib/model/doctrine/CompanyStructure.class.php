<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CompanyStructure extends BaseCompanyStructure {

    const ROOT_ID = 1;

    private static $companyService = null;

    /** Depth : set only when retrieving using getSubdivisionTree */
    public $depth;

    /**
     * Retrieve all immediate children of Company Structure
     */
    public function getChildren() {
        if (is_null(self :: $companyService)) {
            self :: $companyService = new CompanyService();
        }
        return self :: $companyService->getCompanyStructureList($this->getId());
    }

}
