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
 *
 */
?>
<?php use_javascript('../orangehrmAdminPlugin/js/saveJobTitleSuccess'); ?>

<div id="saveHobTitle" class="box">
    
    <div class="head">
        <?php $heading = (empty($form->jobTitleId)) ? __("Add Job Title") : __("Edit Job Title") ?>
        <h1 id="saveHobTitleHeading"><?php echo $heading; ?></h1>
    </div>
    
    <div class="inner">       
        
        <?php include_partial('global/flash_messages'); ?>
        
        <form name="frmSavejobTitle" id="frmSavejobTitle" method="post" action="<?php echo url_for('admin/saveJobTitle?jobTitleId=' . $form->jobTitleId); ?>" enctype="multipart/form-data" class="clickToEditForm">

            <?php echo $form['_csrf_token']; ?>
            
            <fieldset>
                
                <ol>
                    
                    <li>
                        <?php echo $form['jobTitle']->renderLabel(__('Job Title') . ' <em>*</em>'); ?>
                        <?php echo $form['jobTitle']->render(array("maxlength" => 100)); ?>
                    </li>
                    
                    <li>
                        <?php echo $form['jobDescription']->renderLabel(__('Job Description')); ?>
                        <?php echo $form['jobDescription']->render(array("maxlength" => 400)); ?>
                    </li>
                    
                    <?php
                        if (empty($form->attachment->id)) {
                    ?>
                    <li>
                        <?php
                            echo $form['jobSpec']->renderLabel(__('Job Specification'), array());
                            echo $form['jobSpec']->render();
                            echo "<div class=\"fileHelpText\">" . __(CommonMessages::FILE_LABEL_SIZE) . "</div>";
                        ?>
                    </li>      
                    
                    <?php } else { ?>
                        <?php
                            $attachment = $form->attachment;
                            $linkHtml = "<span id=\"fileLink\"><a target=\"_blank\" href=\"";
                            $linkHtml .= url_for('admin/viewJobSpec?attachId=' . $attachment->getId());
                            $linkHtml .= "\">{$attachment->getFileName()}</a></span>";
                            ?>
                    <li>

<?php
                            echo $form['jobSpecUpdate']->renderLabel(__('Job Specification'));
                            echo $linkHtml;
                            ?>
                    </li>
                    <li class="radio noLabel" id="radio"><label></label>
                        <?php
                            echo $form['jobSpecUpdate']->render();
                        ?>
                    </li>
                    <li id="fileUploadSection" class="noLabel">
                        <?php 
                            echo $form['jobSpec']->renderLabel(' ');
                            echo $form['jobSpec']->render();
                            echo "<div class=\"fileHelpText\">" . __(CommonMessages::FILE_LABEL_SIZE) . "</div>";
                        ?>
                    </li>
                    <?php } ?>
                    
                    <li>
                        <?php echo $form['note']->renderLabel(__('Note')); ?>
                        <?php echo $form['note']->render(); ?>
                    </li>
                    
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                    
                </ol>
                
                <p>
                    <input type="button" class="editButton" name="btnSave" id="btnSave" value="<?php echo __("Edit"); ?>"/>
                    <input type="button" class="reset" name="btnCancel" id="btnCancel" value="<?php echo __("Cancel"); ?>"/>
                </p>
                
            </fieldset>

        </form>
        
    </div>

</div>

<script type="text/javascript">
    //<![CDATA[
    //we write javascript related stuff here, but if the logic gets lengthy should use a seperate js file
    var lang_edit = "<?php echo __("Edit"); ?>";
    var lang_save = "<?php echo __("Save"); ?>";
    var lang_jobTitleRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var viewJobTitleListUrl = '<?php echo url_for('admin/viewJobTitleList?jobTitleId='.$form->jobTitleId); ?>';
    var jobTitleId = '<?php echo $form->jobTitleId; ?>';
    var lang_exceed400Chars = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 400)); ?>';
    var jobTitles = <?php echo str_replace('&#039;', "'", $form->getJobTitleListAsJson()) ?> ;
    var jobTitleList = eval(jobTitles);
    var lang_uniqueName = '<?php echo __(ValidationMessages::ALREADY_EXISTS); ?>';
    //]]>
</script>
