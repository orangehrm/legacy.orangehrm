<div id="profile-pic">
    
<h1><?php echo $fullName; ?></h1>

<?php if ($photographPermissions->canUpdate() || $photographPermissions->canDelete()) : ?>
<a href="<?php echo url_for('pim/viewPhotograph?empNumber=' . $empNumber); ?>" title="<?php echo __('Change Photo'); ?>" class="tiptip">
    <img alt="Employee Photo" src="<?php echo url_for("pim/viewPhoto?empNumber=". $empNumber); ?>" border="0" id="empPic" 
         width="<?php echo $width; ?>" height="<?php echo $height; ?>"/>
</a>
<?php else: ?>
<a href="#">
    <img alt="Employee Photo" src="<?php echo url_for("pim/viewPhoto?empNumber=". $empNumber); ?>" border="0" id="empPic" 
     width="<?php echo $width; ?>" height="<?php echo $height; ?>"/>
</a>
<?php endif; ?>

</div> <!-- profile-pic -->