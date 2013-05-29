<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of savePayGradeCurrencyAction
 *
 * @author orangehrm
 */
class savePayGradeCurrencyAction extends baseAdminAction {

    public function execute($request) {

        $payGradePermissions = $this->getDataGroupPermissions('pay_grades');

        $payGradeId = $request->getParameter('payGradeId');
        $values = array('payGradeId' => $payGradeId);
        $this->form = new PayGradeCurrencyForm(array(), $values);

        if ($request->isMethod('post')) {
            if ($payGradePermissions->canCreate() || $payGradePermissions->canUpdate()) {

                $this->form->bind($request->getParameter($this->form->getName()));
                if ($this->form->isValid()) {
                    $payGradeId = $this->form->save();
                    $this->getUser()->setFlash('success', __(TopLevelMessages::SAVE_SUCCESS));
                    $this->redirect('admin/payGrade?payGradeId=' . $payGradeId . '#Currencies');
                }
            }
        }
    }

}

?>
