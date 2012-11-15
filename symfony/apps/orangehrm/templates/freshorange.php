<!DOCTYPE html>
<?php 
$cultureElements = explode('_', $sf_user->getCulture()); 
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cultureElements[0]; ?>" lang="<?php echo $cultureElements[0]; ?>">
    
    <head>

        <title>OrangeHRM</title>
        
        <?php include_http_metas() ?>
        <?php include_metas() ?>

        <!-- Library CSS files -->
        <link href="<?php echo theme_path('css/reset.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo theme_path('css/tipTip.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo theme_path('css/jquery/jquery-ui-1.8.21.custom.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo theme_path('css/jquery/jquery.autocomplete.css')?>" rel="stylesheet" type="text/css"/>
        
        <!-- Custom CSS files -->
        <link href="<?php echo theme_path('css/main.css')?>" rel="stylesheet" type="text/css"/>        
        
        <!-- Library JavaScript files -->
        <script type="text/javascript" src="<?php echo public_path('jquery/jquery-1.7.2.min.js')?>"></script>
        <script type="text/javascript" src="<?php echo public_path('jquery/validate/jquery.validate.js')?>"></script>
        <script type="text/javascript" src="<?php echo public_path('jquery/jquery.ui.core.js')?>"></script>
        <script type="text/javascript" src="<?php echo public_path('jquery/jquery.autocomplete.js')?>"></script>
        <script type="text/javascript" src="<?php echo public_path('jquery/jquery.ui.datepicker.js')?>"></script>
        <script type="text/javascript" src="<?php echo public_path('jquery/jquery.form.js')?>"></script>        
        <script type="text/javascript" src="<?php echo public_path('jquery/jquery.tipTip.minified.js')?>"></script>
        <script type="text/javascript" src="<?php echo public_path('jquery/bootstrap-modal.js')?>"></script>

        <!-- Custom JavaScript files -->
        <script type="text/javascript" src="<?php echo public_path('js/orangehrm.validate.js');?>"></script>
        <script type="text/javascript" src="<?php echo public_path('js/archive.js');?>"></script>
        
        <?php 
        /* Note: use_javascript() doesn't work well when we need to maintain the order of JS inclutions.
         * Ex: It may include a jQuery plugin before jQuery core file. There are two position options as
         * 'first' and 'last'. But they don't seem to resolve the issue.
         */
        ?>   
        
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->        

    </head>
    <body>
      
        <div id="wrapper">

            <div id="branding">
                <img src="<?php echo theme_path('images/logo.png')?>" width="283" height="56" alt="OrangeHRM"/>
                <a href="http://www.orangehrm.com/user-survey-registration.php" class="subscribe" target="_blank"><?php echo __('Join OrangeHRM Community'); ?></a>
                <a href="#" id="welcome"><?php echo __("Welcome %username%", array("%username%" => $sf_user->getAttribute('auth.firstName'))); ?></a>
                <div id="welcome-menu">
                    <ul>
                        <li><a href="<?php echo url_for('admin/changeUserPassword'); ?>"><?php echo __('Change Password'); ?></a></li>
                        <li><a href="<?php echo url_for('auth/logout'); ?>"><?php echo __('Logout'); ?></a></li>
                    </ul>
                </div>
                <a href="#" id="help"><?php echo __("Help & Training"); ?></a>
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
        
        <div id="footer">
            OrangeHRM ver 2.7 &copy; <a href="http://www.orangehrm.com" target="_blank">OrangeHRM</a>. 2005 - 2012 All rights reserved.
        </div> <!-- footer -->
 
        <script type="text/javascript">

            $(document).ready(function() {
                
                /* Enabling tooltips */
                $(".tiptip").tipTip();

                /* Toggling header menus */
                $("#welcome").click(function () {
                    $("#welcome-menu").slideToggle("fast");
                    $(this).toggleClass("activated");
                    return false;
                });
                
                $("#help").click(function () {
                    $("#help-menu").slideToggle("fast");
                    $(this).toggleClass("activated-help");
                    return false;
                });

                /* Button hovering effects */
                $("input[type=button]").hover(function() {
                    $(this).css("background-color","#086912");
                        }, function() {
                    $(this).css("background-color","#33ac3f");
                });

                $("input.reset, input.cancel").hover(function() {
                    $(this).css("background-color","#4d4d4d");
                        }, function() {
                    $(this).css("background-color","#848484");
                });

                $("input.delete").hover(function() {
                    $(this).css("background-color","#782918");
                        }, function() {
                    $(this).css("background-color","#aa4935");
                });

                /* Fading out main messages */
                $(".message a.messageCloseButton").click(function() {
                    $(this).parent('div.message').fadeOut("slow");
                });
                
                setTimeout(function(){
                    $("div.fadable").fadeOut("slow", function () {
                        $("div.fadable").remove();
                    });
                }, 2000);

                /* Toggling search form: Begins */
                //$(".toggableForm .inner").hide(); // Disabling this makes search forms to be expanded by default.

                $(".toggableForm .toggle").click(function () {
                    $(".toggableForm .inner").slideToggle('slow', function() {
                        if($(this).is(':hidden')) {
                            $('.toggableForm .tiptip').tipTip({content:'<?php echo __(CommonMessages::EXPAND_OPTIONS); ?>'});
                        } else {
                            $('.toggableForm .tiptip').tipTip({content:'<?php echo __(CommonMessages::HIDE_OPTIONS); ?>'});
                        }
                    });
                    $(this).toggleClass("activated");
                });
                /* Toggling search form: Ends */

                /* Highlighting firstLevelMenu: Begins */
                /*
                $(".firstLevelMenu").click(function () {
                    
                    $(".firstLevelMenu").each(function(){
                        $(this).parent('li').removeClass('current');
                    });
                    
                    $(this).parent('li').addClass('current');
                
                });
                */
                /* Highlighting firstLevelMenu: Ends */
                /* Enabling/disabling form fields: Begin */
                
                $('form.clickToEditForm input, form.clickToEditForm select, form.clickToEditForm textarea').attr('disabled', 'disabled');
                $('form.clickToEditForm input[type=button]').removeAttr('disabled');
                
                $('form input.editButton').click(function(){
                    $('form.clickToEditForm input, form.clickToEditForm select, form.clickToEditForm textarea').removeAttr('disabled');
                });
                
                /* Enabling/disabling form fields: End */
                
            });
            
        </script>        

    </body>
    
</html>
