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
?>

<?php
use_stylesheet('../../../symfony/web/themes/default/css/jquery/jquery.autocomplete.css');
use_javascript('../../../scripts/jquery/jquery.autocomplete.js');
?>
<div class="box" id="leave-entitlementsSearch">
    <div class="head">
        <h1><?php echo __("Leave Entitlements");?></h1>
    </div>
    <div class="inner">
        <form id="search_form" name="frmLeaveEntitlementSearch" method="post" action="<?php echo url_for('@leave_entitlements'); ?>">

            <fieldset>
                
                <ol>
                    <?php echo $form->render(); ?>
                </ol>            
                
                <p>
                    <!--
                    <button type="reset" class="reset">Reset</button>
                    <button type="submit">Search</button>
                    -->
                    <input type="button" id="searchBtn" value="<?php echo __("Search") ?>" name="_search" />
                </p>
                
            </fieldset>
            
        </form>
        
    </div> <!-- inner -->

    <a href="#" class="toggle tiptip" title="Expand for options">&gt;</a>
    
</div> <!-- employee-information -->

<!-- Confirmation box HTML: Begins -->
<div class="modal hide" id="deleteConfModal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3><?php echo __('OrangeHRM - Confirmation Required'); ?></h3>
  </div>
  <div class="modal-body">
    <p><?php echo __(CommonMessages::DELETE_CONFIRMATION); ?></p>
  </div>
  <div class="modal-footer">
    <input type="button" class="btn" data-dismiss="modal" id="dialogDeleteBtn" value="<?php echo __('Ok'); ?>" />
    <input type="button" class="btn reset" data-dismiss="modal" value="<?php echo __('Cancel'); ?>" />
  </div>
</div>
<!-- Confirmation box HTML: Ends -->

<script type="text/javascript">

    $(document).ready(function() {
        
        $(".tiptip").tipTip();
    });

</script>
