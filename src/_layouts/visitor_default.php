<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $seo["title"]; ?></title>
    <meta name="description" content="<?php echo $seo["description"]; ?>" />
    <meta name="keywords" content="<?php echo $seo["keywords"]; ?>" />
    <!-- Bootstrap -->
    <link href="<?php echo APPFOLDER; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo APPFOLDER; ?>css/jquery.fancybox.min.css" rel="stylesheet">
    <link href="<?php echo APPFOLDER; ?>css/main.css" rel="stylesheet">
    <link rel="canonical" href="<?php echo APPBASE; ?>" />
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
 <header>
         <div class="container">
            <div class="row">
               <div class="col-sm-6">
                  <a href="#" class="logo"><img src="/img/logo.png" class="img-responsive"></a>
                  <h6>PHONE:</h6>
                  <h4><?php echo $settings["phone"]?></h4>
                  <form class="form-horizontal" action="" method="post">
                     <fieldset>
                        <div class="overlay">
                           <h3><span>CONTACT</span> US</h3>
                        </div>
                        <!-- Name input-->
                        <div class="form-group">
                           <div class="col-md-12">
                              <input id="name" name="name" type="text" placeholder="Name" class="form-control" required>
                           </div>
                        </div>
                        <!-- Email input-->
                        <div class="form-group">
                           <div class="col-md-12">
                              <input id="email" name="email" type="email" placeholder="Email" class="form-control" required>
                           </div>
                        </div>
                        <!-- Message body -->
                        <div class="form-group">
                           <div class="col-md-12">
                              <textarea class="form-control" id="message" name="message" placeholder="Message" rows="5" required></textarea>
                           </div>
                        </div>
                        <!-- Form actions -->
                        <div class="form-group">
                           <div class="col-md-12 text-right">
                              <button type="submit" class="button"></button>
                           </div>
                        </div>
                     </fieldset>
                  </form>
               </div>
               <div class="col-sm-6 hidden-xs">
                  <img class="anm img-responsive" src="/img/anm.png">
               </div>
            </div>
         </div>
      </header>
      <main>
         <div class="container">
            <section>
               <h1>Welcome to <span>Profix</span> Hardwood Inc.</h1>
               <div class="clearfix image-with-text">
                  <img src="/img/pic1.jpg" class="pull-left">
                  <?php echo $blocks[0]["content"];?>
               </div>
            </section>
            <section>
               <h1>Services <span>Offered:</span></h1>
               <div class="service-list">
                  <div class="service">
                     <img src="/img/service-1.png" alt="service">
                     <h3>Repairs</h3>
                     <?php echo $blocks[1]["content"];?>
                  </div>
                  <div class="service">
                     <img src="/img/service-2.png" alt="service">
                     <h3>INSTALLATION</h3>
                     <?php echo $blocks[2]["content"];?>
                  </div>
                  <div class="service">
                     <img src="/img/service-3.png" alt="service">
                     <h3>SAND &amp; FINISHING</h3>
                     <?php echo $blocks[3]["content"];?>
                  </div>
                  <div class="service">
                     <img src="/img/service-4.png" alt="service">
                     <h3>PRODUCT SOURCING</h3>
                     <?php echo $blocks[4]["content"];?>
                  </div>
               </div>
            </section>
         </div>
         <section class="gallery">
            <div class="container">
               <h1>Gallery</h1>
               <div class="gallery-container clearfix">
               		<?php foreach($images as $i): ?>
               			<a data-fancybox="gallery" href="<?php echo \libs\helper::img($i["id_upload"],"orig")?>"><img src="<?php echo \libs\helper::img($i["id_upload"],"185x218xC")?>"></a>
               		<?php endforeach; ?>
               </div>
               <form class="form-horizontal clearfix" action="" method="post">
                  <fieldset class="col-sm-8">
                     <div>
                        <h3><span>CONTACT</span> US</h3>
                     </div>
                     <div class="form-group">
                        <!-- Name input-->
                        <div class="col-sm-6">
                           <input id="name1" name="name" type="text" placeholder="Name" class="form-control" required>
                        </div>
                        <!-- Email input-->
                        <div class="col-sm-6 mobile-marg-bottom">
                           <input id="email1" name="email" type="email" placeholder="Email" class="form-control" required>
                        </div>
                     </div>
                     <!-- Message body -->
                     <div class="form-group">
                        <div class="col-md-12">
                           <textarea class="form-control" id="message1" name="message" placeholder="Message" rows="5" required></textarea>
                        </div>
                     </div>
                  </fieldset>
                  <fieldset class="col-sm-4">
                     <!-- Form actions -->
                     <div class="form-group">
                        <div class="col-md-12">
                           <button type="submit" class="button"></button>
                        </div>
                     </div>
                  </fieldset>
               </form>
            </div>
         </section>
      </main>
      <footer>
         <div class="container">
            <div class="clearfix">
               <div class="contact-option"><a href="#" onclick="return false;"><img src="/img/phone.png" alt="phone"> <span><?php echo $settings["phone"]?></span></a></div>
               <div class="contact-option"><a href="mailto:profixhardwood@gmail.com"><img src="/img/email.png" alt="email"> <span><?php echo $settings["contact_email"]?></span></a></div>
               <div class="contact-option"><a href="http://www.facebook.com/ProfixHardwood"><img src="/img/facebook.png" alt="facebook"> <span><?php echo $settings["facebook"]?></span></a></div>
            </div>
            <p>COPYRIGHT &copy; <a href="/">PROFIXHARDWOOD.COM</a> ALL RIGHTS RESERVED</p>
         </div>
      </footer>

<!-- jQuery first, then Bootstrap JS. -->
<script src="<?php echo APPFOLDER; ?>js/jquery.min.js"></script>
<script src="<?php echo APPFOLDER; ?>js/bootstrap.min.js"></script>
<script src="<?php echo APPFOLDER; ?>js/jquery.fancybox.min.js"></script>
<!--<script>
    $('.dropdown').on('mouseenter mouseleave click tap', function() {
      $(this).toggleClass("open");
    });
</script>-->
<script>
<?php
$messages = $this->getMessages();
$m = "";
if(!empty($messages)) {
    foreach($messages as $message)
        $m .= $message.'\n';
    echo "alert('$m');";
}
?>
</script>
</body>
</html>