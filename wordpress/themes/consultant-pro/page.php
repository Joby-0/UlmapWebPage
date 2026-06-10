<?php
/**
 * The template for displaying all pages
 */

get_header(); ?>

<main id="main-content" class="section-padding" style="background: var(--bg-light); min-height: 60vh;">
    <div class="container">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('container-narrow'); ?>>
                <header class="entry-header" style="text-align: center; margin-bottom: var(--space-xl);" data-reveal>
                    <div class="status-badge" style="display: inline-block; padding: 4px 12px; background: rgba(46, 125, 50, 0.1); color: var(--primary-green); border-radius: 20px; font-size: 0.75rem; font-weight: 700; margin-bottom: var(--space-md);">
                        Sida
                    </div>
                    <h1 style="font-size: 3rem; margin-bottom: var(--space-md);"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content" style="background: var(--bg-white); padding: var(--space-lg); border-radius: var(--radius-lg); box-shadow: var(--shadow-soft);" data-reveal>
                    <div class="prose" style="max-width: 800px; margin: 0 auto; color: var(--text-dark); line-height: 1.8;">
                        <?php the_content(); ?>
                    </div>
                </div>
            </article>
        <?php endwhile; endif; ?>
    </div>
</main>

<style>
    .container-narrow { max-width: 900px; margin: 0 auto; }
    .prose p { margin-bottom: 1.5rem; }
    .prose h2 { margin: 2rem 0 1rem; font-size: 1.75rem; }
    .prose h3 { margin: 1.5rem 0 1rem; font-size: 1.5rem; }
</style>

<?php get_footer(); ?>
