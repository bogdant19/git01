<h2>Website Content</h2>
<div class="lva6" style="width:100%;">
    <form action="" method="GET" class="filter">
        <table border="0" cellpadding="5"><tr>
                <td>Display: </td>
                <td>
                    <select name="type">
                        <option value="">Everything</option>
                        <?php foreach($contentTypes as $key => $value): ?>
                            <option value="<?php echo $key?>" <?php echo @$this->data["_g"]["type"]==$key ? "selected" : ""?>><?php echo $value?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>having status:</td>
                <td>
                    <select name="active">
                        <option value="">anything</option>
                        <option value="1" <?php echo @$this->data["_g"]["active"]=="1" ? "selected" : ""?>>active</option>
                        <option value="0" <?php echo @$this->data["_g"]["active"]=="0" ? "selected" : ""?>>inactive</option>
                    </select>
                </td>
                <td valign="middle">
                    <input type="submit" value="Display" />
                </td>
            </tr></table>

        <input type="hidden" name="p" />
    </form>
</div>
<form action="" method="POST">
    <div class="lva7">
        <?php $this->element("admin_messages"); ?>
        <div class="lva7a">
            <?php if($this->isActionAuth(140)):?>
                <a href="<?php echo $this->actionUrl(140)?>"><input type="button" value="Create New Page" /></a>
            <?php endif;?>
        </div>
        <div class="lva7c">
            <?php $this->element("admin_paginator"); ?>
        </div>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <th class="w120">Type</th>
                <th>Name</th>
                <th class="w200">Active?</th>
                <th class="w200">Actions</th>
            </tr>
            <?php foreach($struct["rows"] as $c): ?>
                <tr>
                    <td><?php echo $this->content->getContentType($c["type"]);?></td>
                    <td><strong><?php echo $c["name"]?></strong></td>
                    <td>
                        <?php if($c["active"]==1): ?>
                            <?php echo \libs\helper::flag("#5CB85C","#fff","Yes"); ?>
                        <?php else: ?>
                            <?php echo \libs\helper::flag("#D1423E","#fff","No"); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($this->isActionAuth(147)): ?>
                            <a href="<?php echo $this->actionUrl(147);?>?id_content=<?php echo $c["id_content"]?>">Edit</a>
                        <?php endif; ?>
                        <?php if($this->isActionAuth(154)): ?>
                            <a href="<?php echo $this->actionUrl(154);?>?id_content=<?php echo $c["id_content"]?>" class="delete_button">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="lva7c">
            <?php $this->element("admin_paginator"); ?>
        </div>
        <div class="cb"></div>
        <div class="lva7a">
            <?php if($this->isActionAuth(140)):?>
                <a href="<?php echo $this->actionUrl(140)?>"><input type="button" value="Create New Page" /></a>
            <?php endif;?>
        </div>

    </div>
</form>