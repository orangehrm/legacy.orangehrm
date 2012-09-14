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
                <a href="#" class="subscribe">Join OrangeHRM Community</a>
                <a href="#" id="welcome">Welcome Admin</a>
                <div id="welcome-menu">
                    <ul>
                        <li><a href="#">Edit My Profile</a></li>
                        <li><a href="#">Logout</a></li>
                    </ul>
                </div>
                <a href="#" id="help">Help &amp; Training</a>
                <div id="help-menu">
                    <ul>
                        <li><a href="#">Link 1</a></li>
                        <li><a href="#">Link 2</a></li>
                        <li><a href="#">Link 3</a></li>
                        <li><a href="#">Link 4</a></li>
                    </ul>
                </div>
            </div> <!-- branding -->      
            
            <?php include_component('core', 'mainMenu'); ?>

            <div id="content">

                  <?php echo $sf_content ?>

            </div> <!-- content -->
          
        </div> <!-- wrapper -->

    </body>
    
</html>
