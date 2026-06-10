<?php
/**
 * The template for displaying all single posts
 */

get_header(); ?>

<main id="main-content" class="section-padding" style="background: var(--bg-light); min-height: 60vh;">
    <div class="container">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('container-narrow'); ?>>
                <header class="entry-header" style="text-align: center; margin-bottom: var(--space-xl);" data-reveal>
                    <div class="entry-meta" style="margin-bottom: var(--space-sm); font-size: 0.85rem; color: var(--text-muted);">
                        <?php the_date(); ?> &bull; <?php the_category(', '); ?>
                    </div>
                    <h1 style="font-size: 3rem; margin-bottom: var(--space-md); line-height: 1.2;"><?php the_title(); ?></h1>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumbnail" style="margin-bottom: var(--space-lg); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-medium);" data-reveal>
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content" style="background: var(--bg-white); padding: var(--space-lg); border-radius: var(--radius-lg); box-shadow: var(--shadow-soft);" data-reveal>
                    <div class="prose" style="max-width: 800px; margin: 0 auto; color: var(--text-dark); line-height: 1.8;">
                        <?php the_content(); ?>
                    </div>
                </div>
                
                <footer class="entry-footer" style="margin-top: var(--space-lg); text-align: center;">
                    <a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="btn btn-outline">Tillbaka till bloggen</a>
                </footer>
            </article>
        <?php endwhile; endif; ?>
    </div>
</main>

<style>
    .container-narrow { max-width: 900px; margin: 0 auto; }
    .prose p { margin-bottom: 1.5rem; }
    .prose h2 { margin: 2rem 0 1rem; font-size: 1.75rem; }
    .prose h3 { margin: 1.5rem 0 1rem; font-size: 1.5rem; }
    .post-thumbnail img { width: 100%; display: block; }
</style>

<?php get_footer(); ?>
