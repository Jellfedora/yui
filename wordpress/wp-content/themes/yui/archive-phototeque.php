<?php get_header(); ?>

<h1 class="site__heading"><?php post_type_archive_title(); ?></h1>

<main class="site__phototeque">
    <?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
    <div class="photo">
        <h2 class="photo__title">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h2>
        <?php the_post_thumbnail(); ?>
    </div>
    <?php endwhile; endif; ?>
</main>

<?php the_posts_pagination(); ?>
<?php get_footer(); ?>