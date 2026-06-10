<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header(); ?>

<main id="main-content" class="section-padding" style="min-height: 70vh; display: flex; align-items: center; text-align: center;">
    <div class="container" data-reveal>
        <div class="status-badge" style="display: inline-block; padding: 4px 12px; background: rgba(46, 125, 50, 0.1); color: var(--primary-green); border-radius: 20px; font-size: 0.75rem; font-weight: 700; margin-bottom: var(--space-md);">
            404
        </div>
        <h1 style="font-size: 4rem; margin-bottom: var(--space-md);">Sidan kunde inte hittas</h1>
        <p style="color: var(--text-muted); font-size: 1.25rem; max-width: 600px; margin: 0 auto var(--space-lg);">Det verkar som att sidan du letar efter har flyttats eller inte längre existerar.</p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">Tillbaka till startsidan</a>
    </div>
</main>

<?php get_footer(); ?>
