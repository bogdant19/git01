<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="noindex, nofollow" />
    <title>profixhardwood.com</title>
    <link property="stylesheet" rel="stylesheet" type="text/css" href="/css/lva_style.css" />
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <link property="stylesheet" rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" />
    <script src="//code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
</head>
<body>
<div class="lva1">

    <div class="lva2">
        <div class="container" style="padding-top: 25px;">
            <h1>profix<span>hardwood</span>.com</h1>
            <div class="lva2a">
                Logged in: <i><?php echo \libs\app_user::getOnlineUser("administrator")["email"];?></i>&nbsp;&nbsp;&nbsp;
                <a href="<?php echo $this->actionUrl(13);?>">Change Password</a>&nbsp;
                <a href="<?php echo $this->actionUrl(8);?>">Sign Out</a>
            </div>
            <div class="lva2b">
                <ul class="menu">
                    <?php if(\libs\app_action::isSectionAuth(5)): ?>
                    <li <?php echo \libs\app_action::getSectionId()==5 ? 'class="active"' : ''?>>
                        <a href="<?php echo $this->sectionUrl(5);?>">Users</a>
                        <?php $this->element("admin_user_submenu"); ?>
                    </li>
                    <?php endif; ?>
                    <?php if(\libs\app_action::isSectionAuth(16)): ?>
                    <li <?php echo \libs\app_action::getSectionId()==16 ? 'class="active"' : ''?>>
                        <a href="<?php echo $this->sectionUrl(16);?>">Content</a>
                        <?php $this->element("admin_content_submenu"); ?>
                    </li>
                    <?php endif; ?>
                    <?php if(\libs\app_action::isSectionAuth(35)): ?>
                    <li <?php echo \libs\app_action::getSectionId()==35 ? 'class="active"' : ''?>>
                        <a href="<?php echo $this->sectionUrl(35);?>">Posts</a>
                        <?php $this->element("admin_posts_submenu"); ?>
                    </li>
                    <?php endif; ?>
                    <?php if(\libs\app_action::isSectionAuth(13)): ?>
                        <li <?php echo \libs\app_action::getSectionId()==13 ? 'class="active"' : ''?>>
                            <a href="<?php echo $this->sectionUrl(13);?>">Settings</a>
                            <?php $this->element("admin_settings_submenu"); ?>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="cb"></div>
            </div>
        </div>
    </div>

    <div class="lva2c">
        <div class="container">
            <?php
            switch(\libs\app_action::getSectionId()) {
                case 5: $submenu = "admin_user_submenu"; break;
                case 16: $submenu = "admin_content_submenu"; break;
                case 13: $submenu = "admin_settings_submenu"; break;
                case 32: $submenu = "admin_emails_submenu"; break;
                case 35: $submenu = "admin_posts_submenu"; break;
            }
            $this->element($submenu);
            ?>
            <div class="cb"></div>
        </div>
    </div>

    <div class="lva3">
        <div class="lva3a container">
            <?php
                $interface = \libs\app_action::getInterfacePath();
                $action = \libs\app_action::getActionPath();
                $action = str_replace("-","_",$action);
                $section = \libs\app_action::getSectionPath();
                require_once(APPROOT."/src/$interface/views/$section/$action.php");
            ?>
            <div class="cb"></div>
        </div>
    </div>

    <div class="lva4">
        <div class="container">
            Copyright &copy; <?php print(date("Y")); ?> profixhardwood.com All rights reserved
        </div>
    </div>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.menu > li').mouseover(function() {
            $(this).addClass('hover');
        });
        $('.menu > li').mouseout(function() {
            $(this).removeClass('hover');
        });
        $('.delete_button').click(function(e) {
            if(!confirm('Are you sure you want to delete this element?'))
                e.preventDefault();
        });
        try {
            CKEDITOR.replace($('.ckeditor'));
        } catch(err) {}
        $('.datepicker').datepicker({
            dateFormat: "yy-mm-dd"
        });

        $('.image').change(function(evt) {
            input = $(this);
            container = $(this).next();
            $(this).prev().val('0');
            img = container.find('img');
            img.attr("src","/img/loading100.gif");
            var reader = new FileReader();
            reader.onload = (function(f,preview) {
                return function(e) {
                    img.attr("src",e.target.result);
                };
            })(evt.target.files[0],container);
            loader = function() { reader.readAsDataURL(evt.target.files[0]); };
            container.css("display","table");
            container.find('a').click(function(evt2) {
                evt2.preventDefault();
                container.css("display","none");
                input.val('');
            });
            setTimeout(loader,500);
        });

        $('.img-container-delete').click(function(e) {
            e.preventDefault();
            $(this).next().val(0);
            $(this).closest('.img-container').hide();
        });

        $('.img-container').each(function() {
            if($(this).find('img').prop('src')==window.location.href)
                $(this).hide();
            else
                $(this).show();
        });
    });
</script>
</body>
</html>