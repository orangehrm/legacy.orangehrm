 
        <script type="text/javascript">

            $(document).ready(function() {
                
                /* Enabling hovering effect for table rows */
                $('table.hover').tableHover();                
                
                /* Enabling tooltips */
                $(".tiptip").tipTip();

                /* Toggling header menus */
                $("#welcome").click(function () {
                    $("#welcome-menu").slideToggle("fast");
                    $(this).toggleClass("activated-welcome");
                    return false;
                });
                
                $("#help").click(function () {
                    $("#help-menu").slideToggle("fast");
                    $(this).toggleClass("activated-help");
                    return false;
                });
                
                $('.panelTrigger').outside('click', function() {
                    $('.panelContainer').stop(true, true).slideUp('fast');
                });                

                /* Button hovering effects */
                $("input[type=button]").hover(function() {
                    $(this).addClass('generalButtonMouseOver');
                    $(this).removeClass('generalButtonMouseOut');
                        }, function() {
                    $(this).addClass('generalButtonMouseOut');
                    $(this).removeClass('generalButtonMouseOver');
                });

                $("input.reset, input.cancel").hover(function() {
                    $(this).addClass('cancelButtonMouseOver');
                    $(this).removeClass('cancelButtonMouseOut');
                        }, function() {
                    $(this).addClass('cancelButtonMouseOut');
                    $(this).removeClass('cancelButtonMouseOver');
                });

                $("input.delete").hover(function() {
                    $(this).addClass('deleteButtonMouseOver');
                    $(this).removeClass('deleteButtonMouseOut');
                        }, function() {
                    $(this).addClass('deleteButtonMouseOut');
                    $(this).removeClass('deleteButtonMouseOver');
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

