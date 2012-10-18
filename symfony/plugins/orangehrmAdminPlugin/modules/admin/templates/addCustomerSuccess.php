
<?php
use_stylesheet('../../../symfony/web/themes/default/css/jquery/jquery.autocomplete.css');
use_javascript('../../../scripts/jquery/jquery.autocomplete.js');
use_javascript('../orangehrmAdminPlugin/js/addCustomerSuccess'); 
?>

<div class="box single"  id="addCustomer">
    <div class="head">
        <h1 id="addCustomerHeading"><?php echo __("Add Customer"); ?></h1>
    </div>
           
    <div class="inner">
            
        <?php include_partial('global/flash_messages'); ?>
       
        <form name="frmAddCustomer" id="frmAddCustomer" method="post" action="<?php echo url_for('admin/addCustomer'); ?>" >
            
            <?php echo $form['_csrf_token']; ?>
            <?php echo $form->renderHiddenFields(); ?>
            
            <fieldset>
                    
                <ol>
                    <li>
                        <?php echo $form['customerName']->renderLabel(__('Name'). ' <em>*</em>'); ?>
                        <?php echo $form['customerName']->render(array("class" => "block default editable valid", "maxlength" => 52)); ?>
                    </li>
                    
                    <li>
                        <?php echo $form['description']->renderLabel(__('Description')); ?>
                        <?php echo $form['description']->render(array("class" => "editable", "maxlength" => 255)); ?>
                    </li>
                    
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>
                    
                <p>
                    <input type="button" class="" name="btnSave" id="btnSave" value="<?php echo __("Save"); ?>"/>
                    <input type="button" class="btn reset" name="btnCancel" id="btnCancel" value="<?php echo __("Cancel"); ?>"/>
                </p>
            
            </fieldset>
            
        </form>
        
    </div> <!-- End-inner -->
    
</div> <!-- End-addCustomer -->

<script type="text/javascript">
	var customers = <?php echo str_replace('&#039;', "'", $form->getCustomerListAsJson()) ?> ;
        var customerList = eval(customers);
	var lang_customerNameRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
	var lang_exceed50Charactors = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 50)); ?>';
	var lang_exceed255Charactors = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 250)); ?>';
	var lang_editCustomer = "<?php echo __("Edit Customer"); ?>";
	var lang_uniqueName = '<?php echo __(ValidationMessages::ALREADY_EXISTS); ?>';
	var lang_edit = "<?php echo __("Edit"); ?>";
	var lang_save = "<?php echo __("Save"); ?>";
	var customerId = '<?php echo $customerId;?>';
	var cancelBtnUrl = '<?php echo url_for('admin/viewCustomers'); ?>';
</script>