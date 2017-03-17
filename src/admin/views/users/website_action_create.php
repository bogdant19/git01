<h2><?php if(!empty($id_action)): ?>Edit Action<?php else: ?>Create Action<?php endif;?></h2>
<div class="lva6">
    <?php $this->element("admin_messages"); ?>
    <form action="" method="POST">
        <input type="hidden" name="id_section" value="<?php echo $id_section?>" />
        <?php if(!empty($id_action)): ?>
            <input type="hidden" name="id_action" value="<?php echo $id_action?>" />
        <?php endif; ?>
        <span>Layout</span>
        <select name="section_layout">
            <option value=""></option>
            <?php foreach($layouts as $layout): ?>
                <option value="<?php echo $layout["file"]?>" <?php echo $layout["file"]==@$p["section_layout"] ? "selected" : ""?>><?php echo $layout["name"]?></option>
            <?php endforeach; ?>
        </select>
        <span>Name</span>
        <input type="text" name="name" value="<?php echo @$p["name"]?>" />
        <span>URL</span>
        <input type="text" name="url" value="<?php echo @$p["url"]?>" />
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

        <h3>SEO? <input type="checkbox" name="has_seo" value="1" <?php echo @$p["has_seo"]==1 ? "checked" : ""?> style="display:inline;margin-left:20px;" /></h3>

        <div id="seo" style="display:none;">
            <span>URL</span>
            <input type="text" name="seo_url" value="<?php echo @$p["seo_url"]?>" />
            <span>Title</span>
            <input type="text" name="seo_title" value="<?php echo @$p["seo_title"]?>" />
            <span>Keywords</span>
            <textarea name="seo_keywords"><?php echo @$p["seo_keywords"]?></textarea>
            <span>Description</span>
            <textarea name="seo_description"><?php echo @$p["seo_description"]?></textarea>
        </div>


        <h3>Permitted Groups</h3>

        <table width="100%" cellspacing="0" cellpadding="4" border="0">
            <?php $i=0; foreach($groups as $group): ?>
                <tr>
                    <td class="w200"><?php echo $group["name"]?></td>
                    <td><input type="checkbox" name="groups[]" value="<?php echo $group["id_user_group"]?>" <?php echo $group["id_user_group"]==@$p["groups"][$i] ? "checked" : ""?> /></td>
                </tr>
                <?php $i++; endforeach;?>
        </table>

        <input type="submit" value="Submit" />
        <span class="cb"></span>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('input[name=name]').keyup(function () {
            $('input[name=url]').val(text2slug($(this).val()));
            <?php if(empty($id_action)): ?>
            $('input[name=seo_url]').val(text2slug($(this).val()));
            <?php endif; ?>
        });
        <?php if(@$p["has_seo"]==1): ?>
        $('#seo').show();
        <?php endif; ?>
        $('input[name=has_seo]').click(function() {
            $('#seo').toggle();
        });
    });
</script>