<h2>Settings</h2>
<form action="" method="POST" enctype="multipart/form-data">
    <div class="lva6" style="width: 800px;">
        <?php $this->element("admin_messages"); ?>

        <span>Contact Email</span>
        <input type="email" name="contact_email" value="<?php echo @$p["contact_email"]?>" required />
        
        <span>Phone</span>
        <input type="text" name="phone" value="<?php echo @$p["phone"]?>" required />

        <span>Facebook URL</span>
        <input type="url" name="facebook" value="<?php echo @$p["facebook"]?>" required />

        <div style="display:none;">
	        <span>Twitter URL</span>
	        <input type="url" name="twitter" value="<?php echo @$p["twitter"]?>"  />
	
	        <span>Google+ URL</span>
	        <input type="url" name="gplus" value="<?php echo @$p["gplus"]?>"  />
	
	        <span>LinkedIn URL</span>
	        <input type="url" name="linkedin" value="<?php echo @$p["linkedin"]?>"  />
	
	        <span>Pinterest URL</span>
	        <input type="url" name="pinterest" value="<?php echo @$p["pinterest"]?>"  />
	
	        <span>YouTube URL</span>
	        <input type="url" name="youtube" value="<?php echo @$p["youtube"]?>"  />
        </div>

        <span>Analytics Code</span>
        <textarea name="analytics"><?php echo @$p["analytics"]?></textarea>

        <input type="submit" value="Submit" />
    </div>
</form>