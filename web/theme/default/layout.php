<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <title><?php echo $config['title']; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="description" content="<?php echo $config['description']; ?>" />
        <meta name="keywords" content="<?php echo $config['keywords']; ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?php echo $config['favicon']; ?>" />
        <?php echo generate_css(); ?>
        <?php echo generate_js(); ?>

    </head>
    <body>
        <?php echo document('content'); ?>
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
