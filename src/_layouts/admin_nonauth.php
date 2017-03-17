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
</head>
<body>
<div class="lva1">
    <div class="lva2">
        <div class="container" style="padding-top: 25px; padding-bottom: 30px;">
            <h1>profix<span>hardwood</span>.com</h1>
        </div>
    </div>
    <div class="lva3">
        <div class="lva5 container" style="margin-bottom: 100px;">
            <?php
                $interface = \libs\app_action::getInterfacePath();
                $section = \libs\app_action::getSectionPath();
                $action = \libs\app_action::getActionPath();
                $action = str_replace("-","_",$action);
                require_once(APPROOT."/src/$interface/views/$section/$action.php");
            ?>
        </div>
    </div>
    <div class="lva4">
        <div class="container">
            Copyright &copy; <?php print(date("Y")); ?> profixhardwood.com - All rights reserved
        </div>
    </div>
</div>
</body>
</html>