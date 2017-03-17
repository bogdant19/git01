<?php
$messages = $this->getMessages();
if(!empty($this->errors) || !empty($messages)): ?>
    <ul class="messages">
        <?php if(!empty($this->errors)): ?>
            <?php foreach($this->errors as $error): ?>
                <li class="error"><?php echo $error; ?></li>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if(!empty($messages)): ?>
            <?php foreach($messages as $message): ?>
                <li class="message"><?php echo $message; ?></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
<?php endif; ?>