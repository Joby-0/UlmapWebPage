    <footer class="site-footer section-padding" style="background: var(--bg-white); border-top: 1px solid var(--border-light); margin-top: auto;">
        <div class="container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg);">
            <div class="footer-col">
                <div class="logo" style="margin-bottom: var(--space-sm);">ULMAP.</div>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Modern skandinavisk konsultbyrå som hjälper företag att växa genom teknik och strategi.</p>
            </div>
            
            <div class="footer-col">
                <h4 style="margin-bottom: var(--space-sm);">Tjänster</h4>
                <nav style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.9rem;">
                    <a href="#">Affärsstrategi</a>
                    <a href="#">Digital Transformation</a>
                    <a href="#">Systemutveckling</a>
                </nav>
            </div>

            <div class="footer-col">
                <h4 style="margin-bottom: var(--space-sm);">Företaget</h4>
                <nav style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.9rem;">
                    <a href="#">Om oss</a>
                    <a href="#">Case Studies</a>
                    <a href="#">Kontakt</a>
                </nav>
            </div>

            <div class="footer-col">
                <h4 style="margin-bottom: var(--space-sm);">Kontakt</h4>
                <div style="font-size: 0.9rem; color: var(--text-muted);">
                    <p>Stockholm, Sverige</p>
                    <p>info@ulmap.se</p>
                </div>
            </div>
        </div>
        
        <div class="container" style="padding-top: var(--space-md); border-top: 1px solid var(--border-light); margin-top: var(--space-lg); text-align: center; font-size: 0.8rem; color: var(--text-muted);">
            &copy; <?php echo date('Y'); ?> ULMAP Consulting. Alla rättigheter reserverade.
        </div>
    </footer>

    <?php wp_footer(); ?>
    <script>
        // Basic Reveal Animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('[data-reveal]').forEach(el => observer.observe(el));
    </script>
</body>
</html>
