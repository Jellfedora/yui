<!DOCTYPE html>
<!-- Définit la langue selon les réglages du backend -->
<html <?php language_attributes(); ?>>

<head>
    <!-- Définit l'encodage du site (utf8)-->
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style.css" type="text/css" media="screen" />
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header class="header">
        <h1 class="header__title">Yui</h1>
        <h2 class="header__baseline">By Jellfedora</h2>
    </header>

    <?php wp_body_open(); ?>