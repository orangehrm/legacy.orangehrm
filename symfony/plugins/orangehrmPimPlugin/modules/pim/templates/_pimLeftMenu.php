<div id="sidebar">

    <div id="profile-pic">
        <h1><?php echo htmlspecialchars($form->fullName); ?></h1>
        <img src="<?php echo public_path('../../symfony/web/themes/default/images/profile-pic.png')?>" width="201" height="208" alt="">
    </div>

    <ul id="sidenav">
        <li class="selected"><a href="<?php echo url_for('pim/viewPersonalDetails?empNumber=' . $empNumber); ?>">Personal Details</a></li>
        <li><a href="<?php echo url_for('pim/contactDetails?empNumber=' . $empNumber); ?>">Contact Details</a></li>
        <li><a href="<?php echo url_for('pim/viewEmergencyContacts?empNumber=' . $empNumber); ?>">Emergency Contacts</a></li>
        <li><a href="<?php echo url_for('pim/viewDependents?empNumber=' . $empNumber); ?>">Dependents</a></li>
        <li><a href="<?php echo url_for('pim/viewImmigration?empNumber=' . $empNumber); ?>">Immigration</a></li>
        <li><a href="<?php echo url_for('pim/viewJobDetails?empNumber=' . $empNumber);?>">Job</a></li>
        <li><a href="<?php echo url_for('pim/viewSalaryList?empNumber=' . $empNumber);?>">Salary</a></li>
        <li><a href="<?php echo url_for('pim/viewReportToDetails?empNumber=' . $empNumber);?>">Report-to</a></li>
        <li><a href="<?php echo url_for('pim/viewQualifications?empNumber=' . $empNumber); ?>">Qualifications</a></li>
        <li><a href="<?php echo url_for('pim/viewMemberships?empNumber=' . $empNumber);?>">Membership</a></li>
    </ul>

</div> <!-- sidebar -->
