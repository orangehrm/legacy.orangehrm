
<?php echo javascript_include_tag('../orangehrmPimPlugin/js/viewQualificationsSuccess'); ?>

<?php
$haveWorkExperience = count($workExperienceForm->workExperiences)>0;
?>

<div class="box single pimPane">

    <?php include_partial('pimLeftMenu', array('empNumber' => $empNumber, 'form' => $form)); ?>
    
    <div class="head">
        <h1><?php echo __('Qualifications'); ?></h1>
    </div>
    
    <?php
    if ($workExperiencePermissions->canRead() || $educationPermissions->canRead() ||
            $skillPermissions->canRead() || $languagePermissions->canRead() ||
            $licensePermissions->canRead()) {
        ?>
        
    <?php if ($workExperiencePermissions->canCreate() || ($haveWorkExperience && $workExperiencePermissions->canUpdate())) { ?>
        <div id="changeWorkExperience">
            <div class="head">
                <h1 id="headChangeWorkExperience"><?php echo __('Add Work Experience'); ?></h1>
            </div>
                
            <div class="inner">
                <form id="frmWorkExperience" action="<?php echo url_for('pim/saveDeleteWorkExperience?empNumber=' . 
                        $empNumber . "&option=save"); ?>" method="post">
                    <?php echo $workExperienceForm['_csrf_token']; ?>
                    <?php echo $workExperienceForm['emp_number']->render(); ?>
                    <?php echo $workExperienceForm["seqno"]->render(); ?>

                    <fieldset>
                        <ol>
                            <li>
                                <?php echo $workExperienceForm['employer']->renderLabel(__('Company') . ' <em>*</em>'); ?>
                                <?php echo $workExperienceForm['employer']->render(array("class" => "formInputText", "maxlength" => 100)); ?>
                            </li>
                            <li>
                                <?php echo $workExperienceForm['jobtitle']->renderLabel(__('Job Title') . ' <em>*</em>'); ?>
                                <?php echo $workExperienceForm['jobtitle']->render(array("class" => "formInputText", "maxlength" => 100)); ?>
                            </li>
                            <li>
                                <?php echo $workExperienceForm['from_date']->renderLabel(__('From')); ?>
                                <?php echo $workExperienceForm['from_date']->render(array("class" => "formInputText")); ?>
                            </li>
                            <li>
                                <?php echo $workExperienceForm['to_date']->renderLabel(__('To')); ?>
                                <?php echo $workExperienceForm['to_date']->render(array("class" => "formInputText")); ?>
                            </li>
                            <li>
                                <?php echo $workExperienceForm['comments']->renderLabel(__('Comment')); ?>
                                <?php echo $workExperienceForm['comments']->render(array("class" => "formInputText")); ?>
                            </li>
                            <li class="required">
                                <em>*</em><?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                            </li>
                        </ol>
                        <p>
                            <input type="button" class="" id="btnWorkExpSave" value="<?php echo __("Save"); ?>" />
                            <?php if ((!$haveWorkExperience) || 
                                    ($haveWorkExperience && $workExperiencePermissions->canCreate()) || 
                                    ($haveWorkExperience && $workExperiencePermissions->canUpdate())) { ?>
                            <input type="button" class="reset" id="btnWorkExpCancel" value="<?php echo __("Cancel"); ?>" />
                            <?php } ?>
                        </p>
                    </fieldset>
                </form>
            </div>
        </div> <!-- changeWorkExperience  -->
    <?php } ?>
        
    <div class="miniList" id="sectionWorkExperience">
        <!-- this is work experience section -->
        <a name="workexperience"></a>
        <?php
        if (($section == 'workexperience') && isset($message) && isset($messageType)) {
            $tmpMsgClass = "messageBalloon_{$messageType}";
            $tmpMsg = $message;
        } else {
            $tmpMsgClass = '';
            $tmpMsg = '';
        }
        ?>
        <div class="head">
            <h1><?php echo __("Work Experience"); ?></h1>
        </div>
            
        <div class="inner">
            
            <?php if ($workExperiencePermissions->canRead()) : ?>
                    
                <?php include_partial('global/flash_messages'); ?>
            
                <form id="frmDelWorkExperience" action="<?php echo url_for('pim/saveDeleteWorkExperience?empNumber=' . 
                        $empNumber . "&option=delete"); ?>" method="post">
                    <p id="actionWorkExperience">
                        <?php if ($workExperiencePermissions->canCreate() ) { ?>
                        <input type="button" value="<?php echo __("Add");?>" class="" id="addWorkExperience" />
                        <?php } ?>
                        <?php if ($workExperiencePermissions->canDelete() ) { ?>
                        <input type="button" value="<?php echo __("Delete");?>" class="delete" id="delWorkExperience" />
                        <?php } ?>
                    </p>
                    <table id="" cellpadding="0" cellspacing="0" width="100%" class="table tablesorter">
                        <thead>
                            <tr>
                                <?php if ($workExperiencePermissions->canDelete()) { ?>
                                <th class="check" width="2%"><input type="checkbox" id="workCheckAll" /></th>
                                <?php }?>
                                <th><?php echo __('Company');?></th>
                                <th><?php echo __('Job Title');?></th>
                                <th><?php echo __('From');?></th>
                                <th><?php echo __('To');?></th>
                                <th><?php echo __('Comment');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$haveWorkExperience) { ?>
                                <tr>
                                    <?php if ($workExperiencePermissions->canDelete()) { ?>
                                    <td class="check"></td>
                                    <?php } ?>
                                    <td><?php echo __(TopLevelMessages::NO_RECORDS_FOUND); ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php } else { ?>                        
                                <?php
                                $workExperiences = $workExperienceForm->workExperiences;
                                $row = 0;
                                foreach ($workExperiences as $workExperience) :
                                    $cssClass = ($row % 2) ? 'even' : 'odd';
                                    $fromDate = set_datepicker_date_format($workExperience->from_date);
                                    $toDate = set_datepicker_date_format($workExperience->to_date);
                                    ?>
                                    <tr class="<?php echo $cssClass;?>">
                                        <input type="hidden" id="employer_<?php echo $workExperience->seqno; ?>" 
                                               value="<?php echo htmlspecialchars($workExperience->employer); ?>" />
                                        <input type="hidden" id="jobtitle_<?php echo $workExperience->seqno; ?>" 
                                               value="<?php echo htmlspecialchars($workExperience->jobtitle); ?>" />
                                        <input type="hidden" id="fromDate_<?php echo $workExperience->seqno; ?>" 
                                               value="<?php echo $fromDate; ?>" />
                                        <input type="hidden" id="toDate_<?php echo $workExperience->seqno; ?>" 
                                               value="<?php echo $toDate; ?>" />
                                        <input type="hidden" id="comment_<?php echo $workExperience->seqno; ?>" 
                                               value="<?php echo htmlspecialchars($workExperience->comments); ?>" />
                                        <td class="check">
                                            <?php if ($workExperiencePermissions->canDelete()) {?>
                                            <input type="checkbox" class="chkbox1" value="<?php echo $workExperience->seqno;?>" 
                                                   name="delWorkExp[]"/>
                                            <?php }else{?>
                                            <input type="hidden" class="chkbox1" value="<?php echo $workExperience->seqno;?>" 
                                                   name="delWorkExp[]"/>
                                            <?php }?>
                                        </td>
                                        <td class="name">
                                            <?php if ($workExperiencePermissions->canUpdate()) { ?>
                                            <a class="edit" href="#"><?php echo htmlspecialchars($workExperience->employer);?></a>
                                            <?php } else {
                                                echo htmlspecialchars($workExperience->employer); 
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($workExperience->jobtitle);?></td>
                                        <td><?php echo $fromDate;?></td>
                                        <td><?php echo $toDate;?></td>
                                        <td class="comments"><?php echo htmlspecialchars($workExperience->comments);?></td>
                                    </tr>
                                    <?php $row++;
                                endforeach;
                            } ?>
                        </tbody>
                    </table>
                </form>
            
            <?php else : ?>
                <div><?php echo __(CommonMessages::DONT_HAVE_ACCESS); ?></div>
            <?php endif; ?>
        </div>
        
        <?Php } ?>
    </div> <!-- miniList-sectionWorkExperience -->
    
    <!-- this is education section -->
    <?php if ($educationPermissions->canRead()) { ?>
        <a name="education"></a>
        <?php
        include_partial('education', array('empNumber' => $empNumber, 'form' => $educationForm,
            'message' => $message, 'messageType' => $messageType,
            'section' => $section, 'educationPermissions' => $educationPermissions));
    } 
    ?>

    <!-- this is skills section -->
    <?php if ($skillPermissions->canRead()) { ?>
        <a name="skill"></a>
        <?php
        include_partial('skill', array('empNumber' => $empNumber, 'form' => $skillForm,
            'message' => $message, 'messageType' => $messageType,
            'section' => $section, 'skillPermissions' => $skillPermissions));
    }
    ?>
        
</div> <!-- Box -->

<script type="text/javascript">
    //<![CDATA[
    var fileModified = 0;
    var lang_addWorkExperience = "<?php echo __('Add Work Experience'); ?>";
    var lang_editWorkExperience = "<?php echo __('Edit Work Experience'); ?>";
    var lang_companyRequired = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_jobTitleRequired = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, 
            array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))); ?>';
    var lang_commentLength = "<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 200)); ?>";
    var lang_fromDateLessToDate = "<?php echo __('To date should be after From date'); ?>";
    var lang_selectWrkExprToDelete = "<?php echo __(TopLevelMessages::SELECT_RECORDS); ?>";
    var lang_jobTitleMaxLength = "<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 100)); ?>";
    var lang_companyMaxLength = "<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 100)); ?>";
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var canEdit = '<?php echo $workExperiencePermissions->canUpdate(); ?>';
    //]]>
</script>