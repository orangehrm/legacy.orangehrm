<?php 
use_stylesheet('../../../symfony/web/themes/default/css/jquery/jquery.autocomplete.css');
use_javascript('../../../scripts/jquery/jquery.autocomplete.js');
use_javascript('../orangehrmAdminPlugin/js/viewLocationsSuccess'); 
?>

<div id="location-information" class="box toggableForm">
    
    <div class="head">
        <h1 id="searchLocationHeading"><?php echo __("Locations") ?></h1>
    </div>
    
    <div class="inner">

        <form name="frmSearchLocation" id="frmSearchLocation" method="post" action="<?php echo url_for('admin/viewLocations'); ?>" >
            <?php echo $form['_csrf_token']; ?>

            <fieldset>
                
                <ol>
                    <?php echo $form->render(); ?>
                </ol>
                
                <input type="hidden" name="pageNo" id="pageNo" value="<?php //echo $form->pageNo; ?>" />
                <input type="hidden" name="hdnAction" id="hdnAction" value="search" />
                
                <p>
                    <input type="button" class="addbutton" name="btnSave" id="btnSearch" value="<?php echo __("Search"); ?>" title="<?php echo __("Search"); ?>"/>
                    <input type="button" class="reset" name="btnReset" id="btnReset" value="<?php echo __("Reset"); ?>" title="<?php echo __("Reset"); ?>"/>
                </p>
                
            </fieldset>
            
        </form>
        
    </div>
    
    <a href="#" class="toggle tiptip" title="Expand for options">&gt;</a>
    
</div>

<div id="customerList">
    <?php include_component('core', 'ohrmList', $parmetersForListCompoment); ?>
</div>

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
	
    function submitPage(pageNo) {

        document.frmHiddenParam.pageNo.value = pageNo;
        document.frmHiddenParam.hdnAction.value = 'paging';
        document.getElementById('frmHiddenParam').submit();

    }
    var addLocationUrl = '<?php echo url_for('admin/location'); ?>';
    var viewLocationUrl = '<?php echo url_for('admin/viewLocations'); ?>';
</script>