<?php

/*
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

class ohrmWidgetDatePicker extends sfWidgetFormInput {

    public function render($name, $value = null, $attributes = array(), $errors = array()) {

        if (array_key_exists('class', $attributes)) {
            $attributes['class'] .= ' calendar';
        } else {
            $attributes['class'] = 'calendar';
        }
        
        if (!isset($attributes['id']) && isset($this->attributes['id'])) {
            $attributes['id'] = $this->attributes['id'];
        }

        $html = parent::render($name, $value, $attributes, $errors);

        $javaScript = sprintf(<<<EOF
 <script type="text/javascript">

    var datepickerDateFormat = '%s';
    var displayDateFormat = datepickerDateFormat.replace('yy', 'yyyy');

    $(document).ready(function(){
        
        var dateFieldValue = $.trim($("#%s").val());
        if (dateFieldValue == '') {
            $("#%s").val(displayDateFormat);
        }

        $("#%s").datepicker({
            showOn: "both",
            dateFormat: datepickerDateFormat,
            buttonImage: "%s",
            buttonText:"",
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+100",
            firstDay: 1
        });
        
        //$("img.ui-datepicker-trigger").addClass("editable");
        
        $("#%s").click(function(){
            if ($(this).val() == displayDateFormat) {
                $(this).val('');
            }
        });
    
    });

</script>
EOF
                        ,
                        get_datepicker_date_format(sfContext::getInstance()->getUser()->getDateFormat()),
                        $this->attributes['id'],
                        $this->attributes['id'],
                        $this->attributes['id'],
                        public_path('../../symfony/web/themes/default/images/calendar.png'),
                        $this->attributes['id']
        );

        return $html . $javaScript;
    }
    
    
    public function getStylesheets() {
        return array(public_path('../../symfony/web/jquery/jquery-ui-1.8.21.custom.css') => 'all');
    }
    
    public function getJavaScripts() {
        return array(public_path('../../symfony/web/jquery/jquery.ui.core.js'));
    }
    

    

}

