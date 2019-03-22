<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php page_title(); ?> | <?php site_name(); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php site_url(); ?>/template/style.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=ABeeZee" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <?php page_header(); ?>
</head>
<body>
<div class="wrap">

    <header>
        <h1><?php site_name(); ?></h1>
        <h2><?php site_subtitle(); ?></h2>
        <nav class="menu">
            <?php nav_menu(); ?>
        </nav>
    </header>

    <article>
        <h2><?php page_title(); ?></h2>
        <?php page_content(); ?>
    </article>

    <footer>
        <small>&copy;<?php echo date('Y'); ?> <?php site_name(); ?>.<br><?php site_version(); ?></small>
    </footer>

</div>
</body>
</html>
