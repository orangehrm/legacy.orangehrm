<?php
$styleSheet = 'orange';
$imagePath = public_path("../../themes/{$styleSheet}/images/login");

$browser = $_SERVER['HTTP_USER_AGENT'];
if (strstr($browser, 'MSIE 8.0')) {
    $footerStyle = 'width: 700px';
    $socialNetworkDivStyle = 'padding-left: 600px; padding-top: 121px;';
} else {
    $footerStyle = 'width: 475px';
    $socialNetworkDivStyle = 'padding-left: 700px; padding-top: 131px;';
}
?>
<style type="text/css">
    <!--
    body {
        background-color: #FFFFFF;
        height: 700px;
    }

    img {
        border: none;
    }

    input:not([type="image"]) {
        background-color: transparent;
        border: none;
    }

    input:focus, select:focus, textarea:focus {
        background-color: transparent;
        border: none;
    }

    .textInputContainer {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        color: #666666;
    }

    #divLogin {
        background-image: url(<?php echo "{$imagePath}/login.png"; ?>);
        background-repeat: no-repeat;
        height: 700px;
        width: 1000px;
        border-style: hidden;
        margin: auto;
        padding-left: 10px;
    }

    #divUsername {
        padding-top: 153px;
        padding-left: 504px;
    }

    #divPassword {
        padding-top: 35px;
        padding-left: 504px;
    }

    #txtUsername {
        width: 240px;
        border: 0px;
        background-color: transparent;
    }

    #txtPassword {
        width: 240px;
        border: 0px;
        background-color: transparent;
    }

    #txtUsername, #txtPassword {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        color: #666666;
        height: 16px;
        vertical-align: middle;
        padding-top:0;
    }
    
    #divLoginHelpLink {
        width: 270px;
        background-color: transparent;
        height: 20px;
        margin-top: 12px;
        margin-right: 0px;
        margin-bottom: 0px;
        margin-left: 512px;
    }

    #divLoginButton {
        padding-top: 2px;
        padding-left: 506px;
        float: left;
        width: 280px;
    }

    #btnLogin {
        background: url(<?php echo "{$imagePath}/Login_button.png"; ?>) no-repeat;
        cursor:pointer;
        width: 94px;
        height: 23px;
        border: none;
    }

    #divLink {
        padding-left: 230px;
        padding-top: 105px;
        float: left;
    }

    #divLogo {
        padding-left: 230px;
        padding-top: 70px;
    }

    #spanMessage {
        background: transparent url(<?php echo "{$imagePath}/mark.png"; ?>) no-repeat;
        padding-left: 18px; 
        padding-top: 0px;
        color: #DD7700;
        font-weight: bold;
    }

</style>

<div id="divLogin">
    <div id="divLogo">
        <img src="<?php echo "{$imagePath}/logo.png"; ?>" />
    </div>

    <form method="post" action="<?php echo url_for('auth/validateCredentials'); ?>">
        <input type="hidden" name="actionID"/>
        <input type="hidden" name="hdnUserTimeZoneOffset" id="hdnUserTimeZoneOffset" value="0" />

        <div id="divUsername" class="textInputContainer">
            <?php echo $form['Username']->render(); ?>
        </div>
        <div id="divPassword" class="textInputContainer">
            <?php echo $form['Password']->render(); ?>
        </div>
        <div id="divLoginHelpLink"><?php
            include_component('core', 'ohrmPluginPannel', array(
                'location' => 'login-page-help-link',
            ));
            ?></div>
        <div id="divLoginButton">
            <input type="submit" name="Submit" class="button" id="btnLogin" value=" " />
            <?php if (!empty($message)) : ?>
            <span id="spanMessage"><?php echo __($message); ?></span>
            <?php endif; ?>
        </div>
    </form>

    <div id="divFooter" >
        <div id="divLink" style="<?php echo $footerStyle ?>">
            <lable>
                <a href="http://www.orangehrm.com" target="_blank">OrangeHRM</a> 
                ver 2.6.7-auth-conversion &copy; OrangeHRM Inc. 2005 - 2011 All rights reserved.
            </lable>
        </div>
        <div id="socialNetwork" style="<?php echo $socialNetworkDivStyle ?>">
            <a href="http://www.linkedin.com/groups?home=&gid=891077" target="_blank">
                <img src="<?php echo "{$imagePath}/linkedin.png"; ?>" /></a>&nbsp;
            <a href="http://www.facebook.com/OrangeHRM" target="_blank">
                <img src="<?php echo "{$imagePath}/facebook.png"; ?>" /></a>&nbsp;
            <a href="http://twitter.com/orangehrm" target="_blank">
                <img src="<?php echo "{$imagePath}/twiter.png"; ?>" /></a>&nbsp;
            <a href="http://www.youtube.com/results?search_query=orangehrm&search_type=" target="_blank">
                <img src="<?php echo "{$imagePath}/youtube.png"; ?>" /></a>&nbsp;
        </div>
    </div>

</div>

<script type="text/javascript">
    
    function calculateUserTimeZoneOffset() {
        var myDate = new Date();
        var offset = (-1) * myDate.getTimezoneOffset() / 60;
        return offset;
    }
            
    function addHint(inputObject, hintImageURL) {
        if (inputObject.val() == '') {
            inputObject.css('background', "url('" + hintImageURL + "') no-repeat 10px 3px");
        }
    }
            
    function removeHint(inputObject) {
        inputObject.css('background', '');
    }
    
    $(document).ready(function() {
        
        addHint($('#txtUsername'), '<?php echo "{$imagePath}/username-hint.png"; ?>');
        addHint($('#txtPassword'), '<?php echo "{$imagePath}/password-hint.png"; ?>');
        
        $('#txtUsername').focus(function() {
            removeHint($(this));
            removeHint($("#txtPassword"));
        });
        
        $('#txtPassword').focus(function() {
            removeHint($(this));
            removeHint($("#txtUsername"));
        });
        
        $('#hdnUserTimeZoneOffset').val(calculateUserTimeZoneOffset().toString());
    });
</script>
