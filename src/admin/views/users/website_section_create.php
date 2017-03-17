<h2><?php if(!empty($p["id_section"])): ?>Edit Section<?php else: ?>Create Section<?php endif; ?></h2>
<div class="lva6">
    <?php $this->element("admin_messages"); ?>
    <form action="" method="POST">
        <?php if(!empty($p["id_section"])): ?>
        <input type="hidden" name="id_section" value="<?php echo $p["id_section"]?>" />
        <?php endif; ?>
        <input type="hidden" name="id_interface" value="<?php echo $p["id_interface"]?>" />
        <span>Name</span>
        <input type="text" name="name" value="<?php echo @$p["name"]?>" />
        <span>URL</span>
        <input type="text" name="folder" value="<?php echo @$p["folder"]?>" />
        <table width="100%" cellspacing="0" cellpadding="4" border="0">
            <tr>
                <td class="w200"><span>Default?</span></td>
                <td><input type="checkbox" name="default" value="1" <?php echo @$p["default"]==1 ? "checked" : ""?> /></td>
            </tr>
            <tr>
                <td><span>Active?</span></td>
                <td><input type="checkbox" name="active" value="1" <?php echo @$p["active"]==1 ? "checked" : ""?> /></td>
            </tr>
        </table>
        <input type="submit" value="Submit" />
        <span class="cb"></span>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('input[name=name]').keyup(function () {
            $('input[name=folder]').val(text2slug($(this).val()));
        });
    });
</script>