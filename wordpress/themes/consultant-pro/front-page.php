<?php
/**
 * The front page template file
 */

get_header(); ?>

<main id="main-content">
    <?php
    // Modular Homepage Blocks
    get_template_part( 'template-parts/blocks/hero' );
    get_template_part( 'template-parts/blocks/about' );
    get_template_part( 'template-parts/blocks/services' );
    get_template_part( 'template-parts/blocks/projects' );
    get_template_part( 'template-parts/blocks/testimonials' );
    get_template_part( 'template-parts/blocks/team' );
    get_template_part( 'template-parts/blocks/faq' );
    get_template_part( 'template-parts/blocks/contact' );
    get_template_part( 'template-parts/blocks/cta-banner' );
    ?>
</main>

<?php get_footer(); ?>
