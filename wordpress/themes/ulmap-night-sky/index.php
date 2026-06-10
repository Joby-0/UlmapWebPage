<?php get_header(); ?>

<div class="container">
    <?php if ( is_active_sidebar( 'status-badge-area' ) ) : ?>
        <div class="status-badge">
            <?php dynamic_sidebar( 'status-badge-area' ); ?>
        </div>
    <?php else : ?>
        <div class="status-badge">Arbete pågår</div>
    <?php endif; ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <h1><?php the_title(); ?></h1>
        <div class="content">
            <?php the_content(); ?>
        </div>
    <?php endwhile; else : ?>
        <h1><?php bloginfo('name'); ?></h1>
        <p><?php bloginfo('description'); ?></p>
    <?php endif; ?>
    
    <div class="contact-wrapper">
        <button id="revealBtn" class="reveal-button" onclick="revealEmail()">Hör av dig</button>
        <div id="emailContainer" class="email-display">
            <span class="contact-label">Här når du oss</span>
            <a id="emailLink" href="#" class="email-link"></a>
        </div>
    </div>
</div>

<?php get_footer(); ?>
