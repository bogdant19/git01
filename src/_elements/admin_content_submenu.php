<?php
$submenus = array(
    array("title"		=> "Create New Page",
        "id_action"	=> 140),
    array("title"		=> "Website Content Management",
        "id_action"	=> 148),
	array("title"		=> "Image Gallery Management",
		"id_action"	=> 165)
);
for($i=0;$i<count($submenus);$i++) {
    if($submenus[$i]["id_action"]==\libs\app_action::getActionId())
        $submenus[$i]["active"] = true;
}
?>
<ul>
    <?php foreach($submenus as $submenu): ?>
        <?php if(\libs\app_action::isActionAuth($submenu["id_action"])): ?>
        <li>
            <a href="<?php echo $this->actionUrl($submenu["id_action"]); ?>" <?php echo @$submenu["active"] ? 'class="active"' : '';?>>
                <?php echo $submenu["title"]?>
            </a>
        </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>