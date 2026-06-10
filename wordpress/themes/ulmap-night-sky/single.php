<?php get_header(); ?>

<div class="container">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="status-badge"><?php the_date(); ?></div>
        <h1><?php the_title(); ?></h1>
        <div class="content">
            <?php the_content(); ?>
        </div>
    <?php endwhile; endif; ?>
    
    <div class="contact-wrapper">
        <button id="revealBtn" class="reveal-button" onclick="revealEmail()">Hör av dig</button>
        <div id="emailContainer" class="email-display">
            <span class="contact-label">Här når du oss</span>
            <a id="emailLink" href="#" class="email-link"></a>
        </div>
    </div>
</div>

<?php get_footer(); ?>
