<?php
/**
 * The main template file
 */

get_header(); ?>

<main id="main-content" class="section-padding">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <header class="page-header" style="margin-bottom: var(--space-xl);" data-reveal>
                <h1 style="font-size: 3rem;">Senaste nytt</h1>
            </header>

            <div class="blog-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--space-lg);">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('blog-card'); ?> style="background: var(--bg-white); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-soft); transition: var(--transition-smooth);" data-reveal>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail" style="height: 200px; overflow: hidden;">
                                <?php the_post_thumbnail('medium_large', array('style' => 'width: 100%; height: 100%; object-fit: cover;')); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="content" style="padding: var(--space-md);">
                            <div class="meta" style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: var(--space-xs);">
                                <?php the_date(); ?>
                            </div>
                            <h3 style="margin-bottom: var(--space-sm);"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: var(--space-md);"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                            <a href="<?php the_permalink(); ?>" style="font-weight: 600; color: var(--primary-green); font-size: 0.9rem;">Läs mer &rarr;</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <div class="pagination" style="margin-top: var(--space-xl); text-align: center;">
                <?php the_posts_pagination(); ?>
            </div>

        <?php else : ?>
            <p>Inga inlägg hittades.</p>
        <?php endif; ?>
    </div>
</main>

<style>
    .blog-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-medium); }
</style>

<?php get_footer(); ?>
