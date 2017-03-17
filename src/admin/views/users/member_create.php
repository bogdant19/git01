<h2><?php if($edit): ?>Edit User<?php else: ?>Create User<?php endif;?></h2>
<div class="lva6" style="width:960px;">
    <?php $this->element("admin_messages"); ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <?php if($edit): ?>
            <input type="hidden" name="id" value="<?php echo $p["id_user"]?>" />
        <?php endif; ?>

        <span>Group</span>
        <select name="id_user_group">
            <option value="">Choose one</option>
            <?php foreach($userGroups as $ug): ?>
            <option value="<?php echo $ug["id_user_group"];?>" <?php echo @$p["id_user_group"]==$ug["id_user_group"] ? "selected" : ""?>><?php echo $ug["name"]; ?></option>
            <?php endforeach; ?>
        </select>

        <span>Email</span>
        <input type="email" name="email" value="<?php echo @$p["email"]?>" />

        <?php if(!$edit): ?>
            <span>Password</span>
            <input type="password" name="password" />
            <span>Confirm Password</span>
            <input type="password" name="cpassword" />
        <?php endif; ?>

        <table width="100%" cellspacing="0" cellpadding="4" border="0">
            <tr>
                <td class="w80 lva6p"><span>Active?</span></td>
                <td><input type="checkbox" name="active" value="1" <?php echo @$p["active"]==1 ? "checked" : ""?> /></td>
            </tr>
        </table>

        <input type="hidden" name="operation" />
        <input type="submit" value="Submit" />
        <span class="cb"></span>
    </form>
</div>