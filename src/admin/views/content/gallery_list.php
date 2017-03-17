<h2>Image Gallery</h2>
<form action="" method="POST">
    <div class="lva7">
        <?php $this->element("admin_messages"); ?>
        <div class="lva7a">
            <?php if($this->isActionAuth(166)):?>
                <a href="<?php echo $this->actionUrl(166)?>"><input type="button" value="Upload New Image" /></a>
            <?php endif;?>
        </div>
        <div class="lva7c">
            <?php $this->element("admin_paginator"); ?>
        </div>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <th>Title</th>
                <th class="w160">Image</th>
                <th class="w120">Active?</th>
                <th class="w120">Order</th>
                <th class="w120">Actions</th>
            </tr>
            <?php foreach($struct["rows"] as $c): ?>
                <tr>
                    <td><?php echo ucfirst($c["title"])?></td>
                    <td>
                        <?php if($c["id_upload"]!=0): ?>
                            <img src="<?php echo \libs\helper::img($c["id_upload"],"80x80xC")?>" style="max-height:200px;" />
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($c["active"]==1): ?>
                            <?php echo \libs\helper::flag("#5CB85C","#fff","Yes"); ?>
                        <?php else: ?>
                            <?php echo \libs\helper::flag("#D1423E","#fff","No"); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <input type="hidden" value="<?php echo $c["id_gallery"]?>">
                        <select class="reorder">
                            <?php for($i=1;$i<=$struct["nofRows"];$i++): ?>
                                <option value="<?php echo $i?>" <?php echo $i==$c["ord"] ? "selected" : ""?>><?php echo $i?></option>
                            <?php endfor;?>
                        </select>
                    </td>
                    <td>
                        <?php if($this->isActionAuth(167)): ?>
                            <a href="<?php echo $this->actionUrl(167);?>?id_gallery=<?php echo $c["id_gallery"]?>">Edit</a>
                        <?php endif; ?>
                        <?php if($this->isActionAuth(168)): ?>
                            <a href="<?php echo $this->actionUrl(168);?>?id_gallery=<?php echo $c["id_gallery"]?>" class="delete_button">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="lva7c">
            <?php $this->element("admin_messages"); ?>
        </div>
        <div class="cb"></div>
        <div class="lva7a">
            <?php if($this->isActionAuth(165)):?>
                <a href="<?php echo $this->actionUrl(165)?>"><input type="button" value="Upload New Image" /></a>
            <?php endif;?>
        </div>

    </div>
    <input type="hidden" name="id_gallery" />
    <input type="hidden" name="ord" />
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('.reorder').change(function() {
            $('input[name=id_gallery]').val($(this).prev().val());
            $('input[name=ord]').val($(this).val());
            $(this).closest('form').submit();
        });
    });
</script>