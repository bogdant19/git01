<h2>Members</h2>
<form action="" method="POST">
    <?php $this->element("admin_messages"); ?>
    <div class="lva7">
        <div class="lva7c">
            <?php $this->element("admin_paginator"); ?>
        </div>
        <table width="96%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <th class="w40">&nbsp;</th>
                <th>Email</th>
                <th class="w160">Group</th>
                <th class="w100">Join Date</th>
                <th class="w100">Last Visit</th>
                <th class="w80">Status</th>
                <th class="w200">Actions</th>
            </tr>
            <?php foreach($struct["rows"] as $u): ?>
                <tr>
                    <td align="center"><input type="checkbox" name="ids[]" value="<?php echo $u["id_user"]?>" /></td>
                    <td><?php echo $u["email"]?></td>
                    <td><?php echo $u["group_name"]?><?php if($u["id_user_group"]==7): ?><br><?php echo $u["member_type"]?><?php echo empty($u["gender"]) ? "" : " - ".$u["gender"]?><?php endif;?></td>
                    <td><?php echo \libs\data_format($u["di"],"Y-m-d H:i A");?></td>
                    <td><?php echo \libs\data_format($u["last_login"],"Y-m-d H:i A");?></td>
                    <td>
                        <?php if($u["active"]==1): ?>
                            <?php echo \libs\helper::flag("#5CB85C","#fff","Active"); ?>
                        <?php else: ?>
                            <?php echo \libs\helper::flag("#D1423E","#fff","Inactive"); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if(\libs\app_action::isActionAuth(15)): ?>
                            <a href="<?php echo $this->actionUrl(15)?>?id=<?php echo $u["id_user"]?>">Edit</a>
                        <?php endif; ?>
                        <?php if(\libs\app_action::isActionAuth(14)): ?>
                            <a href="<?php echo $this->actionUrl(14)?>?id=<?php echo $u["id_user"]?>">Change Password</a>
                        <?php endif; ?>
                        <?php if(\libs\app_action::isActionAuth(16)): ?>
                            <a href="<?php echo $this->actionUrl(16)?>?id=<?php echo $u["id_user"]?>" class="delete_button">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="lva7c">
            <?php $this->element("admin_paginator"); ?>
        </div>
    </div>
</form>