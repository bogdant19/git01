<h2><?php if(!empty($p["id_gallery"])): ?>Edit Gallery Image<?php else: ?>Upload New Image<?php endif;?></h2>
<form action="" method="POST" enctype="multipart/form-data">
    <div class="lva6" style="width: 800px;">
        <?php $this->element("admin_messages"); ?>

        <?php if(!empty($p["id_gallery"])): ?>
            <input type="hidden" name="id_gallery" value="<?php echo $p["id_gallery"]?>" />
        <?php endif; ?>

        <span>Title</span>
        <input type="text" name="title" value="<?php echo @$p["title"]?>" />

        <span>Image File</span>
        <input type="file" name="image" class="image" />
        <div class="img-container" style="display:table;">
            <div class="preview"><img src="<?php echo \libs\helper::img(@$p["id_upload"],"orig")?>" alt="" /></div>
            <div class="hide"><a href="" class="img-container-delete">Delete</a><input type="hidden" name="id_upload" value="<?php echo @$p["id_upload"]?>" /></div>
        </div>

        <span></span>
        <table width="100%" cellspacing="0" cellpadding="4" border="0">
            <tr>
                <td class="w100"><span style="font-weight:bold;">Active?</span></td>
                <td><input type="checkbox" name="active" value="1" <?php echo @$p["active"]==1 ? "checked" : ""?> /></td>
            </tr>
        </table>

        <input type="submit" value="Submit" />
    </div>
</form>