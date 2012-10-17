<?php if ($form->hasErrors()): ?>
    <div class="messagebar">
        <?php include_partial('global/form_errors', array('form' => $form)); ?>
    </div>
<?php endif; ?>
<div class="box single" id="leave-balance-report">
    <div class="head">
        <h1><?php echo __("Leave Balance Report");?></h1>
    </div>
    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>
        <form id="frmLeaveBalanceReport" name="frmLeaveBalanceReport" method="post" action="">

            <fieldset>                
                <ol>
                    <?php echo $form->render(); ?>
                </ol>                          
            </fieldset>
            <input type="submit" name="submit" value="submit"/>
        </form>
        
    </div> <!-- inner -->    
</div> 

<div id="report_content">
<?php //echo $report_content;?>    
</div>

<script type="text/javascript">
   var reportUrl = '<?php echo url_for('report/viewReport');?>';
   
    $(document).ready(function() {        
        
        $("#leave_balance_report_typex").change(function() {
            var report_id = $(this).val(); 
            
            if (report_id != 0) {
                $.ajax({
                    type: 'GET',
                    url: reportUrl + '/reportId/' + report_id,
                    success: function(data) {
                        $('#report_content').html(data);
                    }
                });                
            }
        });

    });

</script>