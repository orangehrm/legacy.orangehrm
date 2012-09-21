<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php 
$cultureElements = explode('_', $sf_user->getCulture()); 
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cultureElements[0]; ?>" lang="<?php echo $cultureElements[0]; ?>">
    
    <head>

    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>

        <script type="text/javascript" src="<?php echo public_path('../../symfony/web/jquery/jquery-1.7.2.min.js')?>"></script>  
        <script type="text/javascript" src="<?php echo public_path('../../symfony/web/jquery/jquery.ui.core.js')?>"></script>
        <script type="text/javascript" src="<?php echo public_path('../../symfony/web/jquery/jquery.tipTip.minified.js')?>"></script>

        <!--<script type="text/javascript" src="<?php echo public_path('../../symfony/web/jquery/jquery.ui.widget.js')?>"></script>-->
        <script type="text/javascript" src="<?php echo public_path('../../symfony/web/jquery/bootstrap-modal.js')?>"></script>
        <!--<script type="text/javascript" src="<?php echo public_path('../../symfony/web/jquery/jquery.functions.js')?>"></script>-->


        <link href="<?php echo public_path('../../symfony/web/themes/default/css/main.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo public_path('../../symfony/web/themes/default/css/tipTip.css')?>" rel="stylesheet" type="text/css"/>
        <link href="http://fonts.googleapis.com/css?family=Signika:400,700" rel="stylesheet" type="text/css"/>

    </head>
    <body>
      
        <div id="wrapper">

            <div id="branding">
                <img src="<?php echo public_path('../../symfony/web/themes/default/images/logo.png')?>" width="283" height="56" alt="OrangeHRM">
                <a href="http://www.orangehrm.com/user-survey-registration.php" class="subscribe" target="_blank"><?php echo __('Join OrangeHRM Community'); ?></a>
                <a href="#" id="welcome"><?php echo __('Welcome Admin'); ?></a>
                <div id="welcome-menu">
                    <ul>
                        <li><a href="<?php echo url_for('admin/changeUserPassword'); ?>"><?php echo __('Change Password'); ?></a></li>
                        <li><a href="<?php echo url_for('auth/logout'); ?>"><?php echo __('Logout'); ?></a></li>
                    </ul>
                </div>
                <a href="#" id="help"><?php echo __('Help &amp; Training'); ?></a>
                <div id="help-menu">
                    <ul>
                        <li><a href="http://www.orangehrm.com/support-plans.php?utm_source=application_support&amp;utm_medium=app_url&amp;utm_campaign=orangeapp" target="_blank"><?php echo __('Support'); ?></a></li>
                        <li><a href="http://www.orangehrm.com/training.php?utm_source=application_traning&amp;utm_medium=app_url&amp;utm_campaign=orangeapp" target="_blank"><?php echo __('Training'); ?></a></li>
                        <li><a href="http://www.orangehrm.com/addon-plans.shtml?utm_source=application_addons&amp;utm_medium=app_url&amp;utm_campaign=orangeapp" target="_blank"><?php echo __('Add-Ons'); ?></a></li>
                        <li><a href="http://www.orangehrm.com/customizations.php?utm_source=application_cus&amp;utm_medium=app_url&amp;utm_campaign=orangeapp" target="_blank"><?php echo __('Customizations'); ?></a></li>
                        <li><a href="http://forum.orangehrm.com/" target="_blank"><?php echo __('Forum'); ?></a></li>
                        <li><a href="http://blog.orangehrm.com/" target="_blank"><?php echo __('Blog'); ?></a></li>
                        <li><a href="http://sourceforge.net/apps/mantisbt/orangehrm/view_all_bug_page.php" target="_blank"><?php echo __('Bug Tracker'); ?></a></li>                        
                    </ul>
                </div>
            </div> <!-- branding -->      
            
            <?php include_component('core', 'mainMenu'); ?>

            <div id="content">

                  <?php echo $sf_content ?>

            </div> <!-- content -->
          
        </div> <!-- wrapper -->
        
        <script type="text/javascript">

            $(document).ready(function() {        

                $("#welcome").click(function () {
                    $("#welcome-menu").slideToggle("fast");
                    $(this).toggleClass("activated");
                    return false;
                });
                
                $("#help").click(function () {
                    $("#help-menu").slideToggle("fast");
                    $(this).toggleClass("activated");
                    return false;
                });                

            });
            
        </script>        

    </body>
    
</html>
