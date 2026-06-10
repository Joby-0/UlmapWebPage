    <script>
        function createStars() {
            const container = document.getElementById('stars');
            const starCount = 150;
            for (let i = 0; i < starCount; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                const size = Math.random() * 2 + 1;
                const top = Math.random() * 100;
                const left = Math.random() * 100;
                const delay = Math.random() * 5;
                const duration = Math.random() * 3 + 2;
                star.style.width = `${size}px`;
                star.style.height = `${size}px`;
                star.style.top = `${top}%`;
                star.style.left = `${left}%`;
                star.style.animation = `twinkle ${duration}s infinite ${delay}s ease-in-out`;
                container.appendChild(star);
            }
        }

        function revealEmail() {
            const btn = document.getElementById('revealBtn');
            const container = document.getElementById('emailContainer');
            const link = document.getElementById('emailLink');
            
            // In a real WP site, you might want to fetch this via AJAX or a localized variable
            const email = "<?php echo get_option('admin_email'); ?>";
            
            link.href = 'mailto:' + email;
            link.textContent = email;
            
            btn.style.display = 'none';
            container.style.display = 'block';
        }

        window.onload = createStars;
    </script>
    <?php wp_footer(); ?>
</body>
</html>
