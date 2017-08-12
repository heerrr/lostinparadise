<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php
ob_start();
?>
<div class="main-inner">

	    <div id="container" class="container login-container">
                    <div class="row-fluid">
                            <div class="span12">
                                    <div class="well">
                                            <h1>404 Page Not Found</h1>
                                            <p>Sorry, an error has occured, Requested page not found!</p>
                                            <a href="<?php echo curl::base(); ?>" class="btn btn-primary"><i class="icon icon-home"></i> Take me to home</a>
                                    </div>
                            </div>
                    </div>
            </div>
</div>
<?php
$content = ob_get_clean();
$app = CApp::instance();
$app->add($content);
echo $app->render();
