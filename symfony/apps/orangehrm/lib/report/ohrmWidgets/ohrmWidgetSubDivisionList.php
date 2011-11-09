<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
class ohrmWidgetSubDivisionList extends sfWidgetForm implements ohrmEnhancedEmbeddableWidget {

    private $whereClauseCondition;
    private $subDivisionList;

    public function configure($options = array(), $attributes = array()) {

        $this->subDivisionList = $this->_getChoiceData();

        $this->addOption('choices', $this->subDivisionList);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $value = $value === null ? 'null' : $value;

        $options = array();

        foreach ($this->getOption('choices') as $key => $option) {
            $attributes = array('value' => self::escapeOnce($key));

            if ($key == $value) {
                $attributes['selected'] = 'selected';
            }

            $options[] = $this->renderContentTag(
                            'option',
                            self::escapeOnce($option),
                            $attributes
            );
        }

        $html = $this->renderContentTag(
                        'select',
                        "\n" . implode("\n", $options) . "\n",
                        array_merge(array('name' => $name), $attributes
                ));

        return $html;
    }

    /**
     * Retrieve employment status list.
     * @return array() $choice
     */
    private function _getChoiceData() {

        $choice = array();

        $companyService = new CompanyService();
        $subDivisionTree = $companyService->getSubDivisionTree();

        $choice['0'] = __('All');
        foreach ($subDivisionTree as $node) {

            // Add nodes, indenting correctly. Skip root node
            if ($node->getId() != 1) {
                if ($node->depth == "") {
                    $node->depth = 1;
                }

                $value = $node->getId();
                $children = $node->getChildren();

                foreach ($children as $childNode) {
                    $value = $value . "," . $childNode->getId();
                }

                $indent = str_repeat('&nbsp;&nbsp;', $node->depth - 1);
                $choice[$value] = $indent . $node->getTitle();
            }
        }

        return $choice;
    }

    /**
     * Embeds this widget into the form. Sets label and validator for this widget.
     * @param sfForm $form
     */
    public function embedWidgetIntoForm(sfForm &$form) {

        $widgetSchema = $form->getWidgetSchema();
        $widgetSchema[$this->attributes['id']] = $this;
        $label = ucwords(str_replace("_", " ", $this->attributes['id']));
        $validator = new sfValidatorChoice(array('choices' => array_keys($this->subDivisionList)));
        if ($this->attributes['required'] == "true") {
            $label .= "<span class='required'> * </span>";
        }
        $widgetSchema[$this->attributes['id']]->setLabel($label);
        $form->setValidator($this->attributes['id'], $validator);
    }

    /**
     * Sets whereClauseCondition.
     * @param string $condition
     */
    public function setWhereClauseCondition($condition) {

        $this->whereClauseCondition = $condition;
    }

    /**
     * Gets whereClauseCondition. ( if whereClauseCondition is set returns that, else returns default condition )
     * @return string ( a condition )
     */
    public function getWhereClauseCondition() {

        if (isset($this->whereClauseCondition)) {
            $setCondition = $this->whereClauseCondition;
            return $setCondition;
        } else {
            $defaultCondition = "IN";
            return $defaultCondition;
        }
    }

    /**
     * This method generates the where clause part.
     * @param string $fieldName
     * @param string $value
     * @return string
     */
    public function generateWhereClausePart($fieldName, $value) {

        if ($value == 0) {
            return null;
        } else {
            $whereClausePart = $fieldName . " " . $this->getWhereClauseCondition() . " " . "(" . $value . ")";
            return $whereClausePart;
        }
    }

    public function getDefaultValue(SelectedFilterField $selectedFilterField) {
        return $selectedFilterField->value1;
    }
}