<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php 
$cultureElements = explode('_', $sf_user->getCulture()); 
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cultureElements[0]; ?>" lang="<?php echo $cultureElements[0]; ?>">
  <head>

    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>

    <script type="text/javascript" src="<?php echo public_path('../../symfony/web/jquery/jquery-1.8.0.min.js')?>"></script>
    
     
	<link href="<?php echo public_path('../../symfony/web/themes/default/css/main.css')?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo public_path('../../symfony/web/themes/default/css/tipTip.css')?>" rel="stylesheet" type="text/css"/>
    <link href="http://fonts.googleapis.com/css?family=Signika:400,700" rel="stylesheet" type="text/css"/>

  </head>
  <body>
      
      <div id="wrapper">
          
          <div id="content">
  
              <?php echo $sf_content ?>
              
          </div> <!-- content -->
          
      </div> <!-- wrapper -->

  </body>
</html>
