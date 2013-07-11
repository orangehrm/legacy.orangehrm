<?php
$imagePath = theme_path("images/login");
$version = '3.1.1-beta.1';
$copyrightYear = date('Y');
?>

<style type="text/css">
    #divFooter {
        text-align: center;
    }
    
    #spanSocialMedia {
        padding-top: 10px;
        /*padding: 20px 10px 10px 10px;*/
    }
    
    #spanSocialMedia a img {
		border: none;
    }
    
.spanCopyrightTable {
    width: 100%;
}

.spanCopyrightTable .column1 {
    text-align: right;
}
.spanCopyrightTable .column2 {
    width: 1px;
}
.spanCopyrightTable .column3 {
    text-align: left;
}

.spanCopyrightTable .content {
    display: inline-block;
    width: 200px;
}
.spanCopyrightTable .column1 .content {
    text-align: right;
}
.spanCopyrightTable .column3 .content {
    text-align: left;
}

.spanCopyrightTable span {
    display: inline-block;
    white-space: nowrap;
}    

</style>
<div id="divFooter" >
    <table class="spanCopyrightTable">
    <tr>
        <td class="column1">
            <div class="content">
                <span>&nbsp;</span>
            </div>
        </td>
        <td class="column2">
            <span>OrangeHRM ver 3.1.1-beta.1</span><br/>
            <span>&copy; 2005 - <?php echo $copyrightYear?> <a href="http://www.orangehrm.com" target="_blank">OrangeHRM, Inc</a>. All rights reserved.</span>
        </td>
        <td class="column3">
            <div class="content">
            <span id="spanSocialMedia">
                <a href="http://www.linkedin.com/groups?home=&gid=891077" target="_blank">
                    <img src="<?php echo "{$imagePath}/linkedin.png"; ?>" /></a>&nbsp;
                <a href="http://www.facebook.com/OrangeHRM" target="_blank">
                    <img src="<?php echo "{$imagePath}/facebook.png"; ?>" /></a>&nbsp;
                <a href="http://twitter.com/orangehrm" target="_blank">
                    <img src="<?php echo "{$imagePath}/twiter.png"; ?>" /></a>&nbsp;
                <a href="http://www.youtube.com/results?search_query=orangehrm&search_type=" target="_blank">
                    <img src="<?php echo "{$imagePath}/youtube.png"; ?>" /></a>&nbsp;
            </span>
            </div>
        </td>
    </tr>
</table>

</div>
