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

/**
 * @todo Handle past employees
 * @todo Showing/not showing duplicate names
 * @todo If full name is pasted, hideen ID is not set
 * @todo Array or ajax switch
 * @todo Validating inside the widget
 */

class ohrmWidgetEmployeeNameAutoFill extends sfWidgetFormInput {

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        
        $html           = parent::render($name, $value, $attributes, $errors);
        $typeHint       = __('Type for hints') . '...';
        $hiddenFieldId  = $this->getHiddenFieldId($name);

        $javaScript     = sprintf(<<<EOF
        <script type="text/javascript">

            var employees = %s;

            $(document).ready(function() {

                if ($("#%s").val() == '') {
                    $("#%s").val('%s')
                    .addClass("inputFormatHint");
                }

                $("#%s").one('focus', function() {

                    if ($(this).hasClass("inputFormatHint")) {
                        $(this).val("");
                        $(this).removeClass("inputFormatHint");
                    }
                    
                });
                
                $("#%s").autocomplete(employees, {

                        formatItem: function(item) {
                            $("#%s").val('');
                            return item.name;
                        }
                        ,matchContains:true
                    }).result(function(event, item) {
                        $("#%s").val(item.id);
                    }
                    
                );

            }); // End of $(document).ready

        </script>
EOF
                        ,
                        $this->getEmployeeListAsJson($this->getEmployeeList()),
                        $this->getHtmlId($name),
                        $this->getHtmlId($name),
                        $typeHint,
                        $this->getHtmlId($name),
                        $this->getHtmlId($name),
                        $hiddenFieldId,
                        $hiddenFieldId);

        return "\n\n" . $html . "\n\n" . $this->getHiddenFieldHtml($name) . "\n\n" . $javaScript . "\n\n";
        
    }
    
    protected function getHiddenFieldHtml($name) {
        
        $hiddenName = substr($name, 0, strlen($name) - 1) . '_id]';
        $hiddenId   = $this->getHiddenFieldId($name);
        
        return "<input type=\"hidden\" name=\"$hiddenName\" id=\"$hiddenId\" value=\"\" />";
        
    }
    
    protected function getHiddenFieldId($name) {
        
        return $this->getHtmlId($name) . '_id';
        
    }

    protected function getHtmlId($name) {
        
        if (isset($this->attributes['id'])) {
            return $this->attributes['id'];
        }
        
        return $this->generateId($name);
        
    }
    
    protected function getEmployeeList() {
        
        return sfContext::getInstance()->getUser()->getAttribute("user")->getEmployeeList();
        
    }

    protected function getEmployeeListAsJson($employeeList) {

        $jsonArray = array();        
        
        foreach ($employeeList as $employee) {

            $jsonArray[] = array('name' => $employee->getFullName(), 'id' => $employee->getEmpNumber());
            
        }

        return json_encode($jsonArray);

    }

}

