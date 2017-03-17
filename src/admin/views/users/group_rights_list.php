<h2>Member Groups</h2>
<div class="lva7">
    <table width="33%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <th>Title</th>
            <th class="w200">Actions</th>
        </tr>
        <?php foreach($groups as $group): ?>
            <tr>
                <td><strong><?php echo $group["name"];?></strong></td>
                <td>
                    <?php if(\libs\app_action::isActionAuth(143)): ?>
                        <a href="<?php echo $this->actionUrl(143);?>?id=<?php echo $group["id_user_group"]?>">Manage permissions</a>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
</div>