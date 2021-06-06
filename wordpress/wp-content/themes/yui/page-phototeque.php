<?php 

// Avoir le meme slug que le nom du fichier
// ou
/*
  Template Name: Phototèque
  Template Post Type: post, page, product, photos
*/

	get_header();
	if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
<h1><?php the_title(); ?></h1>
<h2>Template part galerie photos</h2>
<div class="content">
    <?php the_content(); ?>
</div>
<?php
	endwhile; endif;
	get_footer();
?>