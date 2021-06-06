<?php get_header(); ?>

<!-- Conditionnal tag -->
<?php 
    if ( is_category() ) {
        $title = "Catégorie : " . single_tag_title( '', false );
    }
    elseif ( is_tag() ) {
        $title = "Étiquette : " . single_tag_title( '', false );
    }
    elseif ( is_search() ) {
        $title = "Vous avez recherché : " . get_search_query();
    }
    else {
        $title = 'Le Blog';
    }
?>

<?php 
if ( is_user_logged_in() ):
	$current_user = wp_get_current_user(); 
?>
<p>
    <?php echo $current_user->user_firstname; ?>
    <a href="<?php echo wp_logout_url(); ?>"> Déconnexion </a>
</p>
<?php else: ?>
<p>
    <a href="<?php echo wp_login_url(); ?>"> Connexion </a>
</p>
<?php endif; ?>

<h1><?php echo $title; ?></h1>

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

<article class="post">
    <h2><?php the_title(); ?></h2>

    <?php if ( has_post_thumbnail() ): ?>
    <div class="post__thumbnail">
        <?php the_post_thumbnail(); ?>
    </div>
    <?php endif; ?>

    <p class="post__meta">
        Publié le <?php the_time( get_option( 'date_format' ) ); ?>
        par <?php the_author(); ?> • <?php comments_number(); ?>
    </p>

    <?php the_excerpt(); ?>

    <p>
        <a href="<?php the_permalink(); ?>" class="post__link">Lire la suite</a>
    </p>
</article>

<?php endwhile; endif; ?>
<?php get_footer(); ?>