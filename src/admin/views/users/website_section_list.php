<style>
    .lva7 > table > tbody > tr > td,
    .lva7 > table > tbody > tr:nth-child(even) > td {height:16px;border-bottom:1px solid #e3e7ef;padding:2px;background:#fff;}
    .lva7 > table > tbody > tr.interface > td,
    .lva7 > table > tbody > tr.interface a {background:#666;color:#fff;}
    .lva7 > table > tbody > tr.section > td,
    .lva7 > table > tbody > tr.section a {background: #257EC9;color: #fff;}
    .lva7 a:hover {color: red!important;}
</style>
<h2>Website Sections</h2>
<form action="" method="POST">
    <div class="lva7">
        <?php $this->element("admin_messages"); ?>
        <div class="lva7a">
            <input type="submit" value="Submit" />
        </div>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <th>Interface</th>
                <th>Section</th>
                <th>Action</th>
                <th>Default?</th>
                <th>Active?</th>
            </tr>
            <?php foreach($interfaces as $interface): $sections = $interface["sections"]; ?>
                <tr class="interface">
                    <td>
                        <strong><?php echo strtoupper($interface["name"]);?></strong><br>
                        <?php if(\libs\app_action::isActionAuth(31)): ?>
                            <a href="<?php echo $this->actionUrl(31)?>?id_interface=<?php echo $interface["id_interface"]?>">Create Section</a>
                        <?php endif; ?>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <?php foreach($sections as $section): $actions = $section["actions"]; ?>
                    <tr class="section">
                        <td>&nbsp;</td>
                        <td>
                            <strong><?php echo strtoupper($section["name"]);?></strong>
                            <br>
                            <?php if(\libs\app_action::isActionAuth(32)):?>
                                <a href="<?php echo $this->actionUrl(32)?>?id_section=<?php echo $section["id_section"]?>">Create Action</a>
                            <?php endif; ?>
                            <?php if(\libs\app_action::isActionAuth(145)):?>
                                <a href="<?php echo $this->actionUrl(145)?>?id_section=<?php echo $section["id_section"]?>">Edit Section</a>
                            <?php endif; ?>
                            <?php if(\libs\app_action::isActionAuth(144)):?>
                                <a href="<?php echo $this->actionUrl(144)?>?id_section=<?php echo $section["id_section"]?>" onclick="if(!confirm('Are you sure you want to delete this section?')) return false;">Delete Section</a>
                            <?php endif; ?>
                        </td>
                        <td>&nbsp;</td>
                        <td>
                            <?php if($section["default"]==1): ?>
                                <?php echo \libs\helper::flag("#5CB85C","#fff","Default Section"); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($section["active"]==1): ?>
                                <?php echo \libs\helper::flag("#5CB85C","#fff","Yes"); ?>
                            <?php else: ?>
                                <?php echo \libs\helper::flag("#D1423E","#fff","No"); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php foreach($actions as $action): ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                                <strong><?php echo $action["name"];?> / ID: <?php echo $action["id_action"]?> / LAYOUT: <?php echo $action["section_layout"]?></strong><br>
                                <?php if(\libs\app_action::isActionAuth(34)):?>
                                    <a href="<?php echo $this->actionUrl(34)?>?id_action=<?php echo $action["id_action"]?>">Edit Action</a>
                                <?php endif;?>
                                <?php if(\libs\app_action::isActionAuth(35)):?>
                                    <a href="<?php echo $this->actionUrl(35)?>?id_action=<?php echo $action["id_action"]?>" class="delete_button">Delete Action</a>
                                <?php endif;?>
                            </td>
                            <td>
                                <?php if($action["default"]==1): ?>
                                    <?php echo \libs\helper::flag("#5CB85C","#fff","Default Action"); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($action["active"]==1): ?>
                                    <?php echo \libs\helper::flag("#5CB85C","#fff","Yes"); ?>
                                <?php else: ?>
                                    <?php echo \libs\helper::flag("#D1423E","#fff","No"); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endforeach;?>
            <?php endforeach;?>
        </table>
        <div class="lva7a">
            <input type="submit" value="Submit" />
        </div>
    </div>
</form>