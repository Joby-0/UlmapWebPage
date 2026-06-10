<section class="block-projects section-padding" style="background: var(--bg-light);">
    <div class="container">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: var(--space-xl);" data-reveal>
            <div>
                <h2 style="font-size: 2.5rem; margin-bottom: var(--space-sm);">Senaste Projekt</h2>
                <p style="color: var(--text-muted);">Utvalda case studies från våra samarbeten med ledande företag.</p>
            </div>
            <a href="#" class="btn btn-outline" style="display: none;">Visa alla</a>
        </div>

        <div class="projects-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: var(--space-lg);">
            <!-- Project 1 -->
            <article class="project-card" style="background: var(--bg-white); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-soft); transition: var(--transition-smooth);" data-reveal>
                <div class="image" style="height: 250px; background: #e0e0e0; position: relative;">
                    <!-- Placeholder for featured image -->
                    <div style="position: absolute; top: 20px; left: 20px; background: var(--primary-green); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">Fintech</div>
                </div>
                <div class="content" style="padding: var(--space-md);">
                    <h3 style="margin-bottom: var(--space-xs);">Digital Bankplattform</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: var(--space-md);">Optimering av användarupplevelse och backend-arkitektur för en ledande nischbank.</p>
                    <a href="#" style="font-weight: 600; color: var(--primary-green); font-size: 0.9rem;">Se case &rarr;</a>
                </div>
            </article>

            <!-- Project 2 -->
            <article class="project-card" style="background: var(--bg-white); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-soft); transition: var(--transition-smooth);" data-reveal>
                <div class="image" style="height: 250px; background: #d0d0d0; position: relative;">
                    <div style="position: absolute; top: 20px; left: 20px; background: var(--primary-green); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">Logistik</div>
                </div>
                <div class="content" style="padding: var(--space-md);">
                    <h3 style="margin-bottom: var(--space-xs);">AI-driven Logistik</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: var(--space-md);">Implementering av maskininlärning för att optimera ruttplanering och lagerhantering.</p>
                    <a href="#" style="font-weight: 600; color: var(--primary-green); font-size: 0.9rem;">Se case &rarr;</a>
                </div>
            </article>
        </div>
    </div>
    <style>
        .project-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-medium);
        }
        @media (max-width: 768px) {
            .section-header { flex-direction: column; align-items: flex-start; gap: var(--space-md); }
        }
    </style>
</section>
