<h2>Change Password</h2>
<div class="lva6">
    <?php $this->element("admin_messages"); ?>
    <form action="" method="POST">
        <span>Old password</span>
        <input type="password" name="opassword" />
        <span>New password</span>
        <input type="password" name="password" />
        <span>Confirm new password</span>
        <input type="password" name="cpassword" />
        <input type="submit" value="Submit" />
        <span class="cb"></span>
    </form>
</div>
