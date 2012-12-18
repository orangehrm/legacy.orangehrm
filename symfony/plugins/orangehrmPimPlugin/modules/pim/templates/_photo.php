<div id="profile-pic">
    
<h1><?php echo $fullName; ?></h1>

<?php if ($photographPermissions->canUpdate() || $photographPermissions->canDelete()) : ?>
<div class="imageHolder">
<a href="<?php echo url_for('pim/viewPhotograph?empNumber=' . $empNumber); ?>" title="<?php echo __('Change Photo'); ?>" class="tiptip">
    <img alt="Employee Photo" src="<?php echo url_for("pim/viewPhoto?empNumber=". $empNumber); ?>" border="0" id="empPic" 
         width="<?php echo $width; ?>" height="<?php echo $height; ?>"/>
</a>
</div>    
<?php else: ?>
<div class="imageHolder">
<a href="#">
    <img alt="Employee Photo" src="<?php echo url_for("pim/viewPhoto?empNumber=". $empNumber); ?>" border="0" id="empPic" 
     width="<?php echo $width; ?>" height="<?php echo $height; ?>"/>
</a>
</div>    
<?php endif; ?>

</div> <!-- profile-pic -->