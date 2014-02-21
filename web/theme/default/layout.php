<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <title><?php echo $config['title']; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="description" content="<?php echo $config['description']; ?>" />
        <meta name="keywords" content="<?php echo $config['keywords']; ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="shortcut icon" href="<?php echo $config['favicon']; ?>" />
        <?php echo generate_css(); ?>
        <?php echo generate_js(); ?>

    </head>
    <body>
        <div id="for_ajax_div">
        <?php echo document('content'); ?>
        </div>
    </body>
    <script type="text/javascript">
        $(function(){
<?php
foreach ($config['dom_ready'] as $j) {
    echo $j;
}
?>
                    });
    </script>
</html>
