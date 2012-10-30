<?php

function getListClassHtml($action) {
    
    if ($action == sfContext::getInstance()->getActionName()) {
        return ' class="selected"';
    }
    
    return '';
    
}

function isTaxMenuEnabled() {
    
    $sfUser = sfContext::getInstance()->getUser();
    
    if (!$sfUser->hasAttribute('pim.leftMenu.isTaxMenuEnabled')) {
        $isTaxMenuEnabled = OrangeConfig::getInstance()->getAppConfValue(ConfigService::KEY_PIM_SHOW_TAX_EXEMPTIONS);
        $sfUser->setAttribute('pim.leftMenu.isTaxMenuEnabled', $isTaxMenuEnabled);
    }
    
    return $sfUser->getAttribute('pim.leftMenu.isTaxMenuEnabled');
    
}

?>

<div id="sidebar">

    <div id="profile-pic">
        <h1><?php echo htmlspecialchars($form->fullName); ?></h1>
        <img src="<?php echo public_path('../../symfony/web/themes/default/images/profile-pic.png')?>" width="201" height="208" alt="">
    </div>

    <ul id="sidenav">
        <li<?php echo getListClassHtml('viewPersonalDetails'); ?>><a href="<?php echo url_for('pim/viewPersonalDetails?empNumber=' . $empNumber); ?>">Personal Details</a></li>
        <li<?php echo getListClassHtml('contactDetails'); ?>><a href="<?php echo url_for('pim/contactDetails?empNumber=' . $empNumber); ?>">Contact Details</a></li>
        <li<?php echo getListClassHtml('viewEmergencyContacts'); ?>><a href="<?php echo url_for('pim/viewEmergencyContacts?empNumber=' . $empNumber); ?>">Emergency Contacts</a></li>
        <li<?php echo getListClassHtml('viewDependents'); ?>><a href="<?php echo url_for('pim/viewDependents?empNumber=' . $empNumber); ?>">Dependents</a></li>
        <li<?php echo getListClassHtml('viewImmigration'); ?>><a href="<?php echo url_for('pim/viewImmigration?empNumber=' . $empNumber); ?>">Immigration</a></li>
        <li<?php echo getListClassHtml('viewJobDetails'); ?>><a href="<?php echo url_for('pim/viewJobDetails?empNumber=' . $empNumber);?>">Job</a></li>
        <li<?php echo getListClassHtml('viewSalaryList'); ?>><a href="<?php echo url_for('pim/viewSalaryList?empNumber=' . $empNumber);?>">Salary</a></li>
        <?php if (isTaxMenuEnabled()) : ?>
        <li<?php echo getListClassHtml('viewUsTaxExemptions'); ?>><a href="<?php echo url_for('pim/viewUsTaxExemptions?empNumber=' . $empNumber);?>"><?php echo __("Tax Exemptions"); ?></a></li>
        <?php endif; ?>
        <li<?php echo getListClassHtml('viewReportToDetails'); ?>><a href="<?php echo url_for('pim/viewReportToDetails?empNumber=' . $empNumber);?>">Report-to</a></li>
        <li<?php echo getListClassHtml('viewQualifications'); ?>><a href="<?php echo url_for('pim/viewQualifications?empNumber=' . $empNumber); ?>">Qualifications</a></li>
        <li<?php echo getListClassHtml('viewMemberships'); ?>><a href="<?php echo url_for('pim/viewMemberships?empNumber=' . $empNumber);?>">Membership</a></li>
    </ul>

</div> <!-- sidebar -->
