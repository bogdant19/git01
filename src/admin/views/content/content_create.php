<h2><?php if(!empty($id_content)): ?>Edit Webpage<?php else: ?>Create Webpage<?php endif;?></h2>
<form action="" method="POST">
<div class="lva6" style="width: 600px;">
    <?php $this->element("admin_messages"); ?>
        <?php if(!empty($p["id_content"])): ?>
            <input type="hidden" name="id_content" value="<?php echo $p["id_content"]?>" />
            <input type="hidden" name="id_url" value="<?php echo $p["id_url"]?>" />
        <?php endif; ?>
        <input type="hidden" name="type" value="<?php echo @$p["type"]?>" />

        <span>Name</span><small>(for backend management)</small>
        <input type="text" name="name" value="<?php echo @$p["name"]?>" />

        <span>Title</span><small>(for frontend management)</small>
        <input type="text" name="title" value="<?php echo @$p["title"]?>" />

        <?php if(@$p["type"]!="block"): ?>
            <span>URL</span>
            <input type="text" name="url" value="<?php echo @$p["url"]?>" />
        <?php else: ?>
            <input type="hidden" name="url" value="" />
        <?php endif; ?>

        <span>Content</span>
        <textarea name="content" class="ckeditor"><?php echo @$p["content"]?></textarea>

        <?php if(@$p["type"]!="seo"): ?>
            <span></span>
            <table width="100%" cellspacing="0" cellpadding="4" border="0">
                <tr>
                    <td class="w200"><span style="font-weight:bold;">Active?</span></td>
                    <td><input type="checkbox" name="active" value="1" <?php echo @$p["active"]==1 ? "checked" : ""?> /></td>
                </tr>
            </table>
        <?php else: ?>
            <input type="hidden" name="active" value="<?php echo @$p["active"]?>">
        <?php endif; ?>

        <?php if(@$p["type"]!="block"): ?>
            <h3>SEO</h3>

            <span>Title</span>
            <input type="text" name="seo_title" value="<?php echo @$p["seo_title"]?>" />

            <span>Keywords</span>
            <textarea name="seo_keywords"><?php echo @$p["seo_keywords"]?></textarea>

            <span>Description</span>
            <textarea name="seo_description"><?php echo @$p["seo_description"]?></textarea>
        <?php else: ?>
            <input type="hidden" name="seo_title" value="" />
            <input type="hidden" name="seo_keywords" value="" />
            <input type="hidden" name="seo_description" value="" />
        <?php endif; ?>
`
        <input type="submit" value="Submit" />
</div>
</form>
<div class="cb"></div>
<script type="text/javascript">
    $(document).ready(function() {
        $('input[name=title]').keyup(function () {
            $('input[name=url]').val(text2slug($(this).val()));
        });
    });
</script>