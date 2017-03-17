<style>
    .lva7 > table > tbody > tr > td,
    .lva7 > table > tbody > tr:nth-child(even) > td {height:16px;border-bottom:1px solid #e3e7ef;padding:2px;background:#fff;}
    .lva7 > table > tbody > tr.interface > td,
    .lva7 > table > tbody > tr.interface a {background:#666;color:#fff;}
    .lva7 > table > tbody > tr.section > td,
    .lva7 > table > tbody > tr.section a {background: #257EC9;color: #fff;}
    .lva7 a:hover {color: red!important;}
</style>
<h2>Permissions for <?php echo strtoupper($group["name"]);?></h2>
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
                <th>Access Granted?</th>
            </tr>
            <?php foreach($interfaces as $interface): $sections = $interface["sections"]; ?>
                <tr class="interface">
                    <td><strong><?php echo strtoupper($interface["name"]);?></strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <?php foreach($sections as $section): $actions = $section["actions"]; ?>
                    <tr class="section">
                        <td>&nbsp;</td>
                        <td><strong><?php echo strtoupper($section["name"]);?></strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <?php foreach($actions as $action): ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><?php echo $action["name"];?> / ID: <?php echo $action["id_action"]?></td>
                            <td>
                                <input type="checkbox" name="actions[]" value="<?php echo $action["id_action"]?>" <?php echo $action["checked"]?> />
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