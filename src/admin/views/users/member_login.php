<h2>Login Details</h2>
<div class="lva6">
    <?php $this->element("admin_messages"); ?>
    <form action="" method="POST">
        <span>Email address</span>
        <input type="text" name="email" />
        <span>Password</span>
        <input type="password" name="password" />
        <input type="submit" value="Submit" />
        <span class="cb"></span>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('input[name=email]').focus();
    });
</script>