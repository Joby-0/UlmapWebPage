<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <style>
        .site-header {
            position: sticky;
            top: 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            z-index: 1000;
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 0;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-green);
        }
        .main-navigation ul {
            display: flex;
            gap: 2rem;
        }
        .main-navigation a {
            font-weight: 500;
            font-size: 0.95rem;
        }
        .main-navigation a:hover {
            color: var(--primary-green);
        }
        .mobile-toggle {
            display: none;
            flex-direction: column;
            gap: 6px;
            cursor: pointer;
        }
        .mobile-toggle span {
            width: 25px;
            height: 2px;
            background: var(--text-dark);
            transition: var(--transition-smooth);
        }
        @media (max-width: 768px) {
            .main-navigation { display: none; }
            .mobile-toggle { display: flex; }
        }
    </style>
</head>
<body <?php body_class(); ?>>
    <header class="site-header">
        <div class="container header-container">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
                ULMAP<span style="color: var(--text-dark)">.</span>
            </a>
            
            <nav class="main-navigation">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container'      => false,
                ) );
                ?>
            </nav>

            <div class="mobile-toggle" id="mobileToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>
