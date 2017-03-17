<?php
$submenus = array(
    array("title"		=> "User Accounts",
        "id_action"	=> 9),
    array("title"		=> "Create New User",
        "id_action"	=> 11),
    array("title"		=> "Group Permissions",
        "id_action"	=> 12),
    array("title"		=> "Website Sections",
        "id_action"	=> 142),
    array("title"		=> "Member Types",
        "id_action"	=> 160),
    array("title"		=> "Member Reports",
        "id_action"	=> 201)
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