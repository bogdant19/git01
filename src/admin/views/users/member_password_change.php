<h2>Change Password</h2>
<div class="lva6" style="width:360px;">
    <?php $this->element("admin_messages"); ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_user" value="<?php echo $p["id_user"]?>">
        <span>Account Email</span>
        <input type="email" value="<?php echo $p["email"]?>" disabled />
        <input type="hidden" name="email" value="<?php echo $p["email"]?>" />
        <span>New Password</span>
        <input type="password" name="npass" />
        <span>Confirm New Password</span>
        <input type="password" name="cnpass" />
        <input type="submit" value="Submit" />
        <span class="cb"></span>
    </form>
</div>