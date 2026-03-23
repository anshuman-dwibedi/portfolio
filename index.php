<?php
/**
 * Portfolio — index.php
 * Part of the DevCore Suite.
 * Place at: devcore-suite-clean/projects/portfolio/index.php
 *
 * Customise the block below, then deploy.
 * Contact form posts to api/contact.php — no PHP needed here.
 */

/* ─── PERSONAL CONFIG ───────────────────────────────────────── */
$dev = [
    'name'       => 'Anshuman Dwibedi',
    'title'      => 'PHP & JS Developer',
    'tagline'    => 'Remote · Part-time · Contract · Freelance',
    'email'      => 'anshuman@example.com',
    'github'     => 'github.com/anshumandwibedi',
    'github_url' => 'https://github.com/anshumandwibedi',
    'hours'      => '20',          // available hrs/week
    'location'   => 'Remote',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($dev['name']) ?> — <?= htmlspecialchars($dev['title']) ?></title>
    <meta name="description" content="PHP, JavaScript and React developer available for part-time, contract, freelance and agency work. Builder of the DevCore Suite — 4 real-world business apps, 1 shared core library.">
    <meta property="og:title" content="<?= htmlspecialchars($dev['name']) ?> — Developer for Hire">
    <meta property="og:description" content="PHP & JS developer. Ships complete features. Available now.">

    <!-- Google Fonts (required by devcore.css) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- DevCore shared design system -->
    <link rel="stylesheet" href="../core/ui/devcore.css">

    <style>
    /* ═══════════════════════════════════════════════════════
       PORTFOLIO-SPECIFIC STYLES
       Only overrides and additions — never duplicate dc-* classes.
       ═══════════════════════════════════════════════════════ */

    /* ── Scroll-reveal ──────────────────────────────────────── */
    .reveal {
        opacity: 0;
        transform: translateY(28px);
        transition: opacity 0.5s cubic-bezier(0.16,1,0.3,1),
                    transform 0.5s cubic-bezier(0.16,1,0.3,1);
    }
    .reveal.revealed          { opacity: 1; transform: translateY(0); }
    .reveal-delay-1           { transition-delay: 0.10s; }
    .reveal-delay-2           { transition-delay: 0.20s; }
    .reveal-delay-3           { transition-delay: 0.30s; }
    .reveal-delay-4           { transition-delay: 0.40s; }

    /* ── Section layout ─────────────────────────────────────── */
    .pf-section { padding: 100px 0; }
    .pf-section-alt { padding: 100px 0; background: var(--dc-bg-2); }
    .pf-section-head { margin-bottom: 56px; }

    /* ── Mobile hamburger nav ───────────────────────────────── */
    .nav-hamburger {
        display: none;
        background: none;
        border: 1px solid var(--dc-border);
        border-radius: var(--dc-radius);
        padding: 8px;
        color: var(--dc-text-2);
        align-items: center;
        justify-content: center;
        transition: all var(--dc-t-fast);
    }
    .nav-hamburger:hover { border-color: var(--dc-border-2); color: var(--dc-text); }

    .mobile-nav {
        display: none;
        background: var(--dc-bg-2);
        border-bottom: 1px solid var(--dc-border);
        padding: 12px 16px;
        gap: 4px;
        flex-direction: column;
    }
    .mobile-nav.open { display: flex; }
    .mobile-nav .dc-nav__link { width: 100%; }

    @media (max-width: 768px) {
        .nav-hamburger    { display: inline-flex; }
        .nav-desktop-links { display: none !important; }
        .pf-section, .pf-section-alt { padding: 64px 0; }
    }

    /* ── Brand accent dot ───────────────────────────────────── */
    .brand-dot { color: var(--dc-accent); }

    /* ── Hero ───────────────────────────────────────────────── */
    #hero {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 80px 0 60px;
    }
    .hero-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 64px;
        align-items: center;
    }
    @media (max-width: 960px) {
        .hero-grid { grid-template-columns: 1fr; gap: 48px; }
    }

    .hero-eyebrow {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }
    .hero-available {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(34,211,160,0.08);
        border: 1px solid rgba(34,211,160,0.2);
        border-radius: var(--dc-radius-full);
        padding: 5px 14px 5px 10px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--dc-success);
    }
    .hero-work-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .hero-h1 {
        font-family: var(--dc-font-display);
        font-size: clamp(2.8rem, 5.5vw, 4.5rem);
        font-weight: 800;
        letter-spacing: -0.035em;
        line-height: 1.05;
        margin-bottom: 20px;
        color: var(--dc-text);
    }
    .hero-sub {
        font-size: 1.0625rem;
        color: var(--dc-text-2);
        line-height: 1.65;
        max-width: 480px;
        margin-bottom: 32px;
    }
    .hero-ctas {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 40px;
    }

    /* Hero micro-stat cards */
    .hero-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    .hero-stat-card {
        background: var(--dc-bg-glass);
        border: 1px solid var(--dc-border);
        border-radius: var(--dc-radius);
        padding: 12px 18px;
        backdrop-filter: blur(12px);
        display: flex;
        align-items: baseline;
        gap: 8px;
    }
    .hero-stat-num {
        font-family: var(--dc-font-display);
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--dc-accent-2);
        line-height: 1;
    }
    .hero-stat-label {
        font-size: 0.8125rem;
        color: var(--dc-text-3);
        font-weight: 500;
    }

    /* ── Code window ────────────────────────────────────────── */
    @property --border-angle {
        syntax: '<angle>';
        inherits: false;
        initial-value: 0deg;
    }
    @keyframes border-spin {
        to { --border-angle: 360deg; }
    }

    .code-window-wrap {
        position: relative;
        border-radius: var(--dc-radius-xl);
        padding: 2px;
        background: conic-gradient(
            from var(--border-angle),
            transparent 55%,
            var(--dc-accent) 75%,
            var(--dc-success) 88%,
            var(--dc-info) 94%,
            transparent 100%
        );
        animation: border-spin 5s linear infinite;
    }
    .code-window {
        background: var(--dc-bg-2);
        border-radius: calc(var(--dc-radius-xl) - 2px);
        overflow: hidden;
    }
    .code-chrome {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--dc-bg-3);
        border-bottom: 1px solid var(--dc-border);
        padding: 12px 16px;
    }
    .code-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    .code-dot-r { background: #ff5f56; }
    .code-dot-y { background: #ffbd2e; }
    .code-dot-g { background: #27c93f; }
    .code-tab {
        margin-left: 8px;
        font-family: var(--dc-font-mono);
        font-size: 0.75rem;
        color: var(--dc-text-3);
        background: var(--dc-bg);
        border: 1px solid var(--dc-border);
        border-radius: 6px 6px 0 0;
        padding: 3px 12px;
        border-bottom: none;
        position: relative;
        top: 1px;
    }
    .code-body {
        padding: 24px;
        display: flex;
        gap: 16px;
        font-family: var(--dc-font-mono);
        font-size: 0.8375rem;
        line-height: 1.8;
        overflow-x: auto;
    }
    .code-lines {
        display: flex;
        flex-direction: column;
        user-select: none;
        flex-shrink: 0;
        min-width: 20px;
        text-align: right;
        color: var(--dc-text-3);
        font-size: 0.775rem;
    }
    .code-content { flex: 1; white-space: pre; }
    .code-kw   { color: var(--dc-accent-2); font-weight: 600; }
    .code-cls  { color: #38bdf8; }
    .code-str  { color: var(--dc-success); }
    .code-cmt  { color: var(--dc-text-3); font-style: italic; }
    .code-fn   { color: #f5a623; }
    .code-punc { color: var(--dc-text-2); }
    .code-var  { color: var(--dc-text); }

    .code-caption {
        text-align: center;
        margin-top: 16px;
        font-size: 0.8rem;
        color: var(--dc-text-3);
        letter-spacing: 0.02em;
    }

    /* ── Value Prop Bar ─────────────────────────────────────── */
    .vp-bar {
        background: var(--dc-bg-2);
        border-top: 1px solid var(--dc-border);
        border-bottom: 1px solid var(--dc-border);
        padding: 56px 0;
    }
    .vp-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
    }
    @media (max-width: 900px) {
        .vp-grid { grid-template-columns: repeat(2, 1fr); }
        .vp-item:nth-child(1),
        .vp-item:nth-child(2) { border-bottom: 1px solid var(--dc-border); }
        .vp-item:nth-child(odd) { border-right: 1px solid var(--dc-border); }
    }
    @media (max-width: 540px) {
        .vp-grid { grid-template-columns: 1fr; }
        .vp-item  { border-right: none !important; border-bottom: 1px solid var(--dc-border); }
        .vp-item:last-child { border-bottom: none; }
    }
    .vp-item {
        padding: 32px 28px;
        text-align: center;
        border-right: 1px solid var(--dc-border);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }
    .vp-item:last-child { border-right: none; }
    .vp-icon-circle {
        width: 56px;
        height: 56px;
        background: var(--dc-accent-glow);
        border: 1px solid rgba(108,99,255,0.25);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dc-accent-2);
        flex-shrink: 0;
    }
    .vp-stat {
        font-family: var(--dc-font-display);
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--dc-accent-2);
        line-height: 1.2;
    }
    .vp-desc {
        font-size: 0.875rem;
        color: var(--dc-text-2);
        line-height: 1.5;
        max-width: 200px;
    }

    /* ── About section ──────────────────────────────────────── */
    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 64px;
        align-items: start;
    }
    @media (max-width: 900px) {
        .about-grid { grid-template-columns: 1fr; gap: 40px; }
    }
    .about-paras p {
        color: var(--dc-text-2);
        font-size: 0.9375rem;
        line-height: 1.75;
        margin-bottom: 16px;
    }
    .about-paras p:last-child { margin-bottom: 0; }
    .about-paras strong { color: var(--dc-text); font-weight: 600; }

    .work-type-cards { display: flex; flex-direction: column; gap: 16px; }
    .work-card-key {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--dc-accent-2);
        margin-top: 8px;
    }
    .work-card-title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--dc-text);
        margin: 6px 0 4px;
    }
    .work-card-desc {
        font-size: 0.875rem;
        color: var(--dc-text-2);
        line-height: 1.55;
    }

    /* ── Skills section ─────────────────────────────────────── */
    .skill-card {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 20px;
    }
    .skill-icon-wrap {
        width: 40px;
        height: 40px;
        background: var(--dc-accent-glow);
        border-radius: var(--dc-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dc-accent-2);
        flex-shrink: 0;
    }
    .skill-name {
        font-family: var(--dc-font-display);
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--dc-text);
    }
    .skill-bar-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .skill-bar-track {
        flex: 1;
        height: 5px;
        background: var(--dc-bg-3);
        border-radius: var(--dc-radius-full);
        overflow: hidden;
    }
    .skill-bar-fill {
        height: 100%;
        width: 0;
        background: linear-gradient(90deg, var(--dc-accent), var(--dc-accent-2));
        border-radius: var(--dc-radius-full);
        transition: width 1.1s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .skill-pct {
        font-size: 0.75rem;
        color: var(--dc-text-3);
        font-weight: 600;
        flex-shrink: 0;
        width: 28px;
        text-align: right;
    }

    .skills-callout {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-top: 32px;
    }
    .skills-callout-icon {
        color: var(--dc-accent-2);
        flex-shrink: 0;
        margin-top: 2px;
    }
    .skills-callout p {
        color: var(--dc-text-2);
        font-size: 0.9375rem;
        line-height: 1.6;
    }
    .skills-callout strong { color: var(--dc-text); }

    /* ── Projects section ───────────────────────────────────── */
    .core-callout {
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
        margin-bottom: 36px;
    }
    .core-callout-left { flex-shrink: 0; }
    .core-callout-title {
        font-family: var(--dc-font-display);
        font-size: 1rem;
        font-weight: 700;
        color: var(--dc-text);
        margin-bottom: 2px;
    }
    .core-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        flex: 1;
        min-width: 0;
    }
    .core-chip {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: var(--dc-radius-full);
        font-size: 0.75rem;
        font-weight: 600;
        background: var(--dc-bg-3);
        border: 1px solid var(--dc-border);
        color: var(--dc-text-2);
        letter-spacing: 0.02em;
        white-space: nowrap;
    }
    .core-callout-right { flex-shrink: 0; }

    .project-card {
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        gap: 16px;
        padding: 28px 24px 24px;
        transition: border-color var(--dc-t-med), box-shadow var(--dc-t-med), transform var(--dc-t-med);
    }
    .project-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: var(--project-accent, var(--dc-accent));
        border-radius: 0;
    }
    .project-card:hover {
        border-color: var(--dc-border-2);
        box-shadow: 0 12px 40px rgba(0,0,0,0.5);
        transform: translateY(-4px);
    }
    .project-title {
        font-family: var(--dc-font-display);
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dc-text);
        margin: 4px 0;
    }
    .project-problem {
        font-size: 0.9rem;
        color: var(--dc-text-2);
        line-height: 1.6;
    }
    .project-features {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .project-feature-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: var(--dc-radius-full);
        font-size: 0.75rem;
        font-weight: 600;
        background: var(--dc-bg-3);
        border: 1px solid var(--dc-border);
        color: var(--dc-text-2);
    }
    .project-feature-pill::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--project-accent, var(--dc-accent));
        flex-shrink: 0;
    }
    .project-value {
        font-size: 0.8125rem;
        color: var(--dc-success);
        font-weight: 600;
        letter-spacing: 0.01em;
    }
    .project-tech-row {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--dc-text-3);
    }
    .project-btns {
        display: flex;
        gap: 10px;
        margin-top: auto;
    }

    /* ── Contact section ────────────────────────────────────── */
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 64px;
        align-items: start;
    }
    @media (max-width: 900px) {
        .contact-grid { grid-template-columns: 1fr; gap: 40px; }
    }

    .contact-row {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--dc-text-2);
        font-size: 0.9375rem;
        margin-bottom: 12px;
    }
    .contact-row-icon {
        color: var(--dc-accent-2);
        flex-shrink: 0;
    }
    .contact-row a { color: var(--dc-text-2); }
    .contact-row a:hover { color: var(--dc-text); }

    .contact-avail-card {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        flex-direction: column;
        margin-top: 24px;
    }
    .contact-avail-top {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--dc-success);
    }

    .contact-form { display: flex; flex-direction: column; gap: 16px; }

    /* ── Thinking section ───────────────────────────────────── */
    #thinking { scroll-margin-top: 80px; }

    .thinking-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 48px;
        align-items: start;
    }
    @media (max-width: 900px) {
        .thinking-grid { grid-template-columns: 1fr; gap: 32px; }
    }

    .thinking-toc {
        position: sticky;
        top: 88px;
    }
    .toc-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--dc-text-3);
        margin-bottom: 12px;
    }
    .toc-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: var(--dc-radius);
        font-size: 0.875rem;
        color: var(--dc-text-3);
        transition: all var(--dc-t-fast);
        border-left: 2px solid transparent;
        cursor: pointer;
        text-decoration: none;
    }
    .toc-link:hover { color: var(--dc-text-2); border-left-color: var(--dc-border-2); }
    .toc-link.active { color: var(--dc-accent-2); border-left-color: var(--dc-accent); background: rgba(108,99,255,0.06); }
    .toc-num { font-family: var(--dc-font-mono); font-size: 0.7rem; color: var(--dc-text-3); }

    .thinking-content { display: flex; flex-direction: column; gap: 48px; }

    .thought-block { scroll-margin-top: 100px; }
    .thought-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--dc-text-3);
        margin-bottom: 10px;
    }
    .thought-h3 {
        font-family: var(--dc-font-display);
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dc-text);
        margin-bottom: 14px;
        line-height: 1.3;
    }
    .thought-body {
        font-size: 0.9375rem;
        color: var(--dc-text-2);
        line-height: 1.75;
    }
    .thought-body p { margin-bottom: 14px; }
    .thought-body p:last-child { margin-bottom: 0; }
    .thought-body strong { color: var(--dc-text); font-weight: 600; }
    .thought-body code {
        font-family: var(--dc-font-mono);
        font-size: 0.8375em;
        color: var(--dc-accent-2);
        background: rgba(108,99,255,0.1);
        border: 1px solid rgba(108,99,255,0.15);
        border-radius: 4px;
        padding: 1px 6px;
    }

    .thought-divider {
        border: none;
        border-top: 1px solid var(--dc-border);
        margin: 0;
    }

    .thought-callout {
        background: var(--dc-bg-3);
        border-left: 3px solid var(--dc-accent);
        border-radius: 0 var(--dc-radius) var(--dc-radius) 0;
        padding: 14px 18px;
        margin: 16px 0;
        font-size: 0.9rem;
        color: var(--dc-text-2);
        line-height: 1.65;
    }
    .thought-callout strong { color: var(--dc-text); }

    .decision-table {
        width: 100%;
        border-collapse: collapse;
        margin: 16px 0;
        font-size: 0.875rem;
    }
    .decision-table th {
        text-align: left;
        padding: 8px 14px;
        background: var(--dc-bg-3);
        color: var(--dc-text-3);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: 1px solid var(--dc-border);
    }
    .decision-table td {
        padding: 10px 14px;
        border-bottom: 1px solid var(--dc-border);
        color: var(--dc-text-2);
        vertical-align: top;
        line-height: 1.55;
    }
    .decision-table tr:last-child td { border-bottom: none; }
    .decision-table td:first-child { color: var(--dc-text); font-weight: 500; white-space: nowrap; }
    .dt-chosen { color: var(--dc-success) !important; font-weight: 600 !important; }

    .thought-tag {
        display: inline-flex;
        align-items: center;
        padding: 3px 9px;
        border-radius: var(--dc-radius-full);
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.03em;
        background: var(--dc-bg-3);
        border: 1px solid var(--dc-border);
        color: var(--dc-text-3);
        margin: 2px;
    }
    .pf-footer {
        background: var(--dc-bg-2);
        border-top: 1px solid var(--dc-border);
        padding: 48px 0 0;
    }
    .footer-grid {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 32px;
        padding-bottom: 40px;
    }
    @media (max-width: 768px) {
        .footer-grid { grid-template-columns: 1fr; gap: 24px; }
        .footer-col-right { text-align: left !important; }
    }
    .footer-brand-name {
        font-family: var(--dc-font-display);
        font-size: 1.125rem;
        font-weight: 800;
        color: var(--dc-text);
        margin-bottom: 4px;
    }
    .footer-brand-tag {
        font-size: 0.8125rem;
        color: var(--dc-text-3);
    }
    .footer-links {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        justify-content: center;
    }
    .footer-link {
        padding: 6px 12px;
        border-radius: var(--dc-radius);
        font-size: 0.875rem;
        color: var(--dc-text-2);
        transition: color var(--dc-t-fast), background var(--dc-t-fast);
    }
    .footer-link:hover { color: var(--dc-text); background: var(--dc-bg-glass); }

    .footer-col-right {
        text-align: right;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }
    .footer-built-note {
        font-size: 0.8rem;
        color: var(--dc-text-3);
        margin-top: 4px;
    }

    .footer-bottom {
        border-top: 1px solid var(--dc-border);
        padding: 16px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }
    .footer-copy { font-size: 0.8125rem; color: var(--dc-text-3); }
    .open-to-work {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(34,211,160,0.08);
        border: 1px solid rgba(34,211,160,0.2);
        border-radius: var(--dc-radius-full);
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--dc-success);
    }
    </style>
</head>
<body>

<!-- ═══════════════════════════════════════════════════════════
     NAVBAR
     ═══════════════════════════════════════════════════════════ -->
<nav class="dc-nav" id="navbar">
    <a href="#hero" class="dc-nav__brand">
        <?= htmlspecialchars($dev['name']) ?><span class="brand-dot">.</span>
    </a>

    <!-- Desktop links -->
    <div class="dc-nav__links nav-desktop-links">
        <a href="#about"    class="dc-nav__link" data-spy="about">About</a>
        <a href="#skills"   class="dc-nav__link" data-spy="skills">Skills</a>
        <a href="#projects" class="dc-nav__link" data-spy="projects">Projects</a>
        <a href="#thinking" class="dc-nav__link" data-spy="thinking">How I Think</a>
        <a href="#contact"  class="dc-btn dc-btn-primary dc-btn-sm" style="margin-left:8px;">Hire Me</a>
    </div>

    <!-- Mobile hamburger -->
    <button class="nav-hamburger" id="hamburger" aria-label="Toggle navigation">
        <i class="dc-icon dc-icon-md dc-icon-menu"></i>
    </button>
</nav>

<!-- Mobile nav drawer -->
<div class="mobile-nav" id="mobile-nav">
    <a href="#about"    class="dc-nav__link" data-spy-m="about">About</a>
    <a href="#skills"   class="dc-nav__link" data-spy-m="skills">Skills</a>
    <a href="#projects" class="dc-nav__link" data-spy-m="projects">Projects</a>
    <a href="#thinking" class="dc-nav__link" data-spy-m="thinking">How I Think</a>
    <a href="#contact"  class="dc-nav__link dc-nav__link--cta" data-spy-m="contact">Hire Me</a>
</div>


<!-- ═══════════════════════════════════════════════════════════
     HERO
     ═══════════════════════════════════════════════════════════ -->
<section id="hero">
    <div class="dc-container">
        <div class="hero-grid">

            <!-- LEFT: The Pitch -->
            <div class="dc-stagger">

                <!-- Eyebrow row -->
                <div class="hero-eyebrow">
                    <div class="hero-available">
                        <span class="dc-live__dot"></span>
                        Available for new work
                    </div>
                    <div class="hero-work-pills">
                        <span class="dc-badge dc-badge-accent">Part-time</span>
                        <span class="dc-badge dc-badge-info">Contract</span>
                        <span class="dc-badge dc-badge-success">Freelance</span>
                        <span class="dc-badge dc-badge-warning">Agency</span>
                    </div>
                </div>

                <!-- Headline -->
                <h1 class="hero-h1">
                    I Build Web Apps<br>
                    Businesses Actually Use.
                </h1>

                <!-- Subtext -->
                <p class="hero-sub">
                    PHP, JavaScript and React developer with hands-on experience building
                    real tools for restaurants, real estate, healthcare and e-commerce —
                    with the code quality and architecture to match.
                </p>

                <!-- CTAs -->
                <div class="hero-ctas">
                    <a href="#projects" class="dc-btn dc-btn-primary dc-btn-lg">
                        See My Work
                        <i class="dc-icon dc-icon-md dc-icon-arrow-right"></i>
                    </a>
                    <a href="#contact" class="dc-btn dc-btn-ghost dc-btn-lg">
                        Get In Touch
                    </a>
                </div>

                <!-- Micro-stats -->
                <div class="hero-stats">
                    <div class="hero-stat-card">
                        <span class="hero-stat-num dc-stat__value" data-count="4">0</span>
                        <span class="hero-stat-label">Industry Projects Built</span>
                    </div>
                    <div class="hero-stat-card">
                        <span class="hero-stat-num dc-stat__value" data-count="3">0</span>
                        <span class="hero-stat-label">Storage Providers Integrated</span>
                    </div>
                    <div class="hero-stat-card">
                        <span class="hero-stat-num dc-stat__value" data-count="1">0</span>
                        <span class="hero-stat-label">Shared Core Library</span>
                    </div>
                </div>

            </div><!-- /left -->

            <!-- RIGHT: The Proof — Code Editor Mockup -->
            <div class="dc-animate-fade-up" style="animation-delay:0.25s;">
                <div class="code-window-wrap">
                    <div class="code-window">

                        <!-- Chrome bar -->
                        <div class="code-chrome">
                            <span class="code-dot code-dot-r"></span>
                            <span class="code-dot code-dot-y"></span>
                            <span class="code-dot code-dot-g"></span>
                            <span class="code-tab">bootstrap.php</span>
                        </div>

                        <!-- Code body -->
                        <div class="code-body">
                            <div class="code-lines">
                                <span>1</span><span>2</span><span>3</span>
                                <span>4</span><span>5</span><span>6</span>
                                <span>7</span><span>8</span>
                            </div>
                            <pre class="code-content"><span class="code-punc">&lt;?php</span>
<span class="code-cmt">// One line gives you the entire core</span>
<span class="code-kw">require_once</span> <span class="code-str">'../../core/bootstrap.php'</span><span class="code-punc">;</span>

<span class="code-cmt">// Shared DB, storage, API — all projects</span>
<span class="code-var">$db</span>  <span class="code-punc">=</span> <span class="code-cls">Database</span><span class="code-punc">::</span><span class="code-fn">getInstance</span><span class="code-punc">();</span>
<span class="code-var">$url</span> <span class="code-punc">=</span> <span class="code-cls">Storage</span><span class="code-punc">::</span><span class="code-fn">uploadFile</span><span class="code-punc">(</span><span class="code-var">$_FILES</span><span class="code-punc">[</span><span class="code-str">'img'</span><span class="code-punc">],</span> <span class="code-str">'products'</span><span class="code-punc">);</span>
<span class="code-cls">Api</span><span class="code-punc">::</span><span class="code-fn">success</span><span class="code-punc">([</span><span class="code-str">'url'</span> <span class="code-punc">=></span> <span class="code-var">$url</span><span class="code-punc">,</span> <span class="code-str">'stored'</span> <span class="code-punc">=></span> <span class="code-kw">true</span><span class="code-punc">]);</span></pre>
                        </div>
                    </div>
                </div>

                <p class="code-caption">
                    Part of the DevCore Suite — 4 projects, 1 shared core
                </p>
            </div><!-- /right -->

        </div><!-- /hero-grid -->
    </div>
</section>


<!-- ═══════════════════════════════════════════════════════════
     VALUE PROPOSITION BAR
     ═══════════════════════════════════════════════════════════ -->
<div class="vp-bar">
    <div class="dc-container">
        <div class="vp-grid">

            <div class="vp-item reveal">
                <div class="vp-icon-circle">
                    <i class="dc-icon dc-icon-xl dc-icon-clock"></i>
                </div>
                <div class="vp-stat">Available Now</div>
                <p class="vp-desc">Ready to start part-time or contract within days, not months</p>
            </div>

            <div class="vp-item reveal reveal-delay-1">
                <div class="vp-icon-circle">
                    <i class="dc-icon dc-icon-xl dc-icon-package"></i>
                </div>
                <div class="vp-stat">Ships Complete Features</div>
                <p class="vp-desc">Not just code — working endpoints, UI, validation and error handling</p>
            </div>

            <div class="vp-item reveal reveal-delay-2">
                <div class="vp-icon-circle">
                    <i class="dc-icon dc-icon-xl dc-icon-refresh"></i>
                </div>
                <div class="vp-stat">Low Onboarding Cost</div>
                <p class="vp-desc">Clean architecture and shared patterns mean fast ramp-up on your codebase</p>
            </div>

            <div class="vp-item reveal reveal-delay-3">
                <div class="vp-icon-circle">
                    <i class="dc-icon dc-icon-xl dc-icon-globe"></i>
                </div>
                <div class="vp-stat">Fully Remote</div>
                <p class="vp-desc">Built for async work. Communicates clearly and delivers on schedule</p>
            </div>

        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════════
     ABOUT
     ═══════════════════════════════════════════════════════════ -->
<section id="about" class="pf-section">
    <div class="dc-container">
        <div class="about-grid">

            <!-- LEFT: Story -->
            <div>
                <p class="dc-label dc-mb-sm reveal">About Me</p>
                <h2 class="dc-h2 dc-mb-lg reveal reveal-delay-1">Self-taught, under a year in,<br>and already building things<br>teams charge clients for.</h2>

                <div class="about-paras reveal reveal-delay-2">
                    <p>
                        I started with the fundamentals — not tutorials, not copy-paste — which means
                        I understand <em>why</em> the code works, not just that it does. Solid working
                        knowledge of <strong>HTML, CSS, JavaScript, PHP, React, Git</strong> and npm,
                        built through shipping real projects rather than completing courses.
                    </p>
                    <p>
                        Instead of building four isolated portfolio projects, I built a
                        <strong>shared core library</strong> that all four apps import from a single
                        location. One change to <code style="font-family:var(--dc-font-mono);font-size:0.875em;color:var(--dc-accent-2);">Api::success()</code> propagates to every project.
                        That decision added friction upfront and saved it everywhere else —
                        which is the right trade-off, and I made it before writing a single
                        line of application code.
                    </p>
                    <p>
                        Looking for a <strong>part-time role, contract project, agency engagement,
                        or freelance build</strong>. Available immediately. Remote only.
                        If your stack is different from mine, tell me — I'll have something
                        useful to show you within a week.
                    </p>
                </div>
            </div>

            <!-- RIGHT: Work Type Cards -->
            <div class="work-type-cards">

                <div class="dc-card-solid reveal">
                    <span class="dc-badge dc-badge-accent">Part-time</span>
                    <p class="work-card-title">Consistent weekly hours, long-term commitment</p>
                    <p class="work-card-desc">
                        Available <?= htmlspecialchars($dev['hours']) ?> hrs/week. Can join standups, use your
                        tools, follow your workflow. Treated like a team member — just fewer hours on the clock.
                    </p>
                    <span class="work-card-key">
                        <i class="dc-icon dc-icon-sm dc-icon-check"></i>
                        "Treat it like a full-time role, just fewer hours"
                    </span>
                </div>

                <div class="dc-card-solid reveal reveal-delay-1">
                    <span class="dc-badge dc-badge-info">Contract</span>
                    <p class="work-card-title">Scoped deliverables, clear timeline</p>
                    <p class="work-card-desc">
                        Give me a spec and a deadline. I deliver working code, not excuses.
                        Milestones, communication and a handover that doesn't leave you guessing.
                    </p>
                    <span class="work-card-key">
                        <i class="dc-icon dc-icon-sm dc-icon-check"></i>
                        "Fixed scope, no surprises"
                    </span>
                </div>

                <div class="dc-card-solid reveal reveal-delay-2">
                    <span class="dc-badge dc-badge-success">Freelance</span>
                    <p class="work-card-title">Your idea built and shipped</p>
                    <p class="work-card-desc">
                        From a landing page to a full booking system — I scope it, build it, and
                        hand it over running. End-to-end ownership with clear documentation.
                    </p>
                    <span class="work-card-key">
                        <i class="dc-icon dc-icon-sm dc-icon-check"></i>
                        "End-to-end ownership"
                    </span>
                </div>

                <div class="dc-card-solid reveal reveal-delay-3">
                    <span class="dc-badge dc-badge-warning">Agency</span>
                    <p class="work-card-title">Extra capacity when you need it</p>
                    <p class="work-card-desc">
                        Plug me into client projects. I follow your standards, communicate like
                        a team member, and don't require handholding to get up to speed.
                    </p>
                    <span class="work-card-key">
                        <i class="dc-icon dc-icon-sm dc-icon-check"></i>
                        "Ready to use your stack and workflow"
                    </span>
                </div>

            </div><!-- /work-type-cards -->

        </div>
    </div>
</section>


<!-- ═══════════════════════════════════════════════════════════
     SKILLS
     ═══════════════════════════════════════════════════════════ -->
<section id="skills" class="pf-section-alt">
    <div class="dc-container">

        <div class="pf-section-head">
            <p class="dc-label dc-mb-sm reveal">Tech Stack</p>
            <h2 class="dc-h2 dc-mb-sm reveal reveal-delay-1">What I bring to your project.</h2>
            <p class="dc-body reveal reveal-delay-2">
                Not a generalist. A focused PHP + JS developer who can own the full web stack.
            </p>
        </div>

        <div class="dc-grid dc-grid-4 reveal reveal-delay-1" id="skills-grid">

            <!-- HTML/CSS -->
            <div class="dc-card-solid skill-card">
                <div class="skill-icon-wrap">
                    <i class="dc-icon dc-icon-lg dc-icon-globe"></i>
                </div>
                <div class="skill-name">HTML / CSS</div>
                <div class="skill-bar-row">
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="90"></div>
                    </div>
                    <span class="skill-pct">90%</span>
                </div>
                <span class="dc-badge dc-badge-success">Strong</span>
            </div>

            <!-- JavaScript -->
            <div class="dc-card-solid skill-card">
                <div class="skill-icon-wrap">
                    <i class="dc-icon dc-icon-lg dc-icon-activity"></i>
                </div>
                <div class="skill-name">JavaScript</div>
                <div class="skill-bar-row">
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="80"></div>
                    </div>
                    <span class="skill-pct">80%</span>
                </div>
                <span class="dc-badge dc-badge-success">Strong</span>
            </div>

            <!-- PHP -->
            <div class="dc-card-solid skill-card">
                <div class="skill-icon-wrap">
                    <i class="dc-icon dc-icon-lg dc-icon-note"></i>
                </div>
                <div class="skill-name">PHP</div>
                <div class="skill-bar-row">
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="80"></div>
                    </div>
                    <span class="skill-pct">80%</span>
                </div>
                <span class="dc-badge dc-badge-success">Strong</span>
            </div>

            <!-- MySQL -->
            <div class="dc-card-solid skill-card">
                <div class="skill-icon-wrap">
                    <i class="dc-icon dc-icon-lg dc-icon-bar-chart"></i>
                </div>
                <div class="skill-name">MySQL</div>
                <div class="skill-bar-row">
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="75"></div>
                    </div>
                    <span class="skill-pct">75%</span>
                </div>
                <span class="dc-badge dc-badge-success">Strong</span>
            </div>

            <!-- React -->
            <div class="dc-card-solid skill-card">
                <div class="skill-icon-wrap">
                    <i class="dc-icon dc-icon-lg dc-icon-refresh"></i>
                </div>
                <div class="skill-name">React</div>
                <div class="skill-bar-row">
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="60"></div>
                    </div>
                    <span class="skill-pct">60%</span>
                </div>
                <span class="dc-badge dc-badge-accent">Proficient</span>
            </div>

            <!-- Git -->
            <div class="dc-card-solid skill-card">
                <div class="skill-icon-wrap">
                    <i class="dc-icon dc-icon-lg dc-icon-refresh"></i>
                </div>
                <div class="skill-name">Git</div>
                <div class="skill-bar-row">
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="65"></div>
                    </div>
                    <span class="skill-pct">65%</span>
                </div>
                <span class="dc-badge dc-badge-accent">Proficient</span>
            </div>

            <!-- REST APIs -->
            <div class="dc-card-solid skill-card">
                <div class="skill-icon-wrap">
                    <i class="dc-icon dc-icon-lg dc-icon-arrow-right"></i>
                </div>
                <div class="skill-name">REST APIs</div>
                <div class="skill-bar-row">
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="75"></div>
                    </div>
                    <span class="skill-pct">75%</span>
                </div>
                <span class="dc-badge dc-badge-success">Strong</span>
            </div>

            <!-- npm / Tooling -->
            <div class="dc-card-solid skill-card">
                <div class="skill-icon-wrap">
                    <i class="dc-icon dc-icon-lg dc-icon-package"></i>
                </div>
                <div class="skill-name">npm / Tooling</div>
                <div class="skill-bar-row">
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="65"></div>
                    </div>
                    <span class="skill-pct">65%</span>
                </div>
                <span class="dc-badge dc-badge-accent">Proficient</span>
            </div>

            <!-- AWS S3 / R2 -->
            <div class="dc-card-solid skill-card">
                <div class="skill-icon-wrap">
                    <i class="dc-icon dc-icon-lg dc-icon-cloud"></i>
                </div>
                <div class="skill-name">AWS S3 / R2</div>
                <div class="skill-bar-row">
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="60"></div>
                    </div>
                    <span class="skill-pct">60%</span>
                </div>
                <span class="dc-badge dc-badge-accent">Proficient</span>
            </div>

            <!-- UI / Design Systems -->
            <div class="dc-card-solid skill-card">
                <div class="skill-icon-wrap">
                    <i class="dc-icon dc-icon-lg dc-icon-eye"></i>
                </div>
                <div class="skill-name">UI / Design Sys</div>
                <div class="skill-bar-row">
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="70"></div>
                    </div>
                    <span class="skill-pct">70%</span>
                </div>
                <span class="dc-badge dc-badge-accent">Proficient</span>
            </div>

            <!-- Linux / CLI -->
            <div class="dc-card-solid skill-card">
                <div class="skill-icon-wrap">
                    <i class="dc-icon dc-icon-lg dc-icon-note"></i>
                </div>
                <div class="skill-name">Linux / CLI</div>
                <div class="skill-bar-row">
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="45"></div>
                    </div>
                    <span class="skill-pct">45%</span>
                </div>
                <span class="dc-badge dc-badge-neutral">Learning</span>
            </div>

            <!-- Docker -->
            <div class="dc-card-solid skill-card">
                <div class="skill-icon-wrap">
                    <i class="dc-icon dc-icon-lg dc-icon-package"></i>
                </div>
                <div class="skill-name">Docker</div>
                <div class="skill-bar-row">
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="30"></div>
                    </div>
                    <span class="skill-pct">30%</span>
                </div>
                <span class="dc-badge dc-badge-neutral">Learning</span>
            </div>

        </div><!-- /skills grid -->

        <!-- Callout strip -->
        <div class="dc-card-accent skills-callout dc-mt-xl reveal">
            <i class="dc-icon dc-icon-lg dc-icon-info skills-callout-icon"></i>
            <p>
                <strong>I learn fast.</strong> If your stack uses something not listed,
                tell me — I will pick it up. Junior by experience, not by mindset.
            </p>
        </div>

    </div>
</section>


<!-- ═══════════════════════════════════════════════════════════
     PROJECTS
     ═══════════════════════════════════════════════════════════ -->
<section id="projects" class="pf-section">
    <div class="dc-container">

        <div class="pf-section-head">
            <p class="dc-label dc-mb-sm reveal">Recent Work</p>
            <h2 class="dc-h2 dc-mb-sm reveal reveal-delay-1">Real projects solving real business problems.</h2>
            <p class="dc-body reveal reveal-delay-2">
                Each project is a standalone app built on a shared PHP + JS core library —
                showing both product thinking and architectural discipline.
            </p>
        </div>

        <!-- Shared Core Callout -->
        <div class="dc-card-accent core-callout reveal">
            <div class="core-callout-left">
                <p class="core-callout-title">DevCore Shared Library</p>
                <p class="dc-caption">Imported by all 4 projects below</p>
            </div>
            <div class="core-chips">
                <span class="core-chip">Database</span>
                <span class="core-chip">Api</span>
                <span class="core-chip">Auth</span>
                <span class="core-chip">Analytics</span>
                <span class="core-chip">QrCode</span>
                <span class="core-chip">Validator</span>
                <span class="core-chip">Storage (Local / S3 / R2)</span>
                <span class="core-chip">devcore.css</span>
                <span class="core-chip">devcore.js</span>
            </div>
            <div class="core-callout-right">
                <a href="<?= htmlspecialchars($dev['github_url']) ?>" target="_blank" rel="noopener" class="dc-btn dc-btn-ghost dc-btn-sm">
                    <i class="dc-icon dc-icon-sm dc-icon-globe"></i>
                    View on GitHub
                </a>
            </div>
        </div>

        <!-- 4 Project Cards -->
        <div class="dc-grid dc-grid-2">

            <!-- 1. Restaurant QR Ordering -->
            <div class="dc-card-solid project-card reveal" style="--project-accent:#f5a623;">
                <div>
                    <span class="dc-badge dc-badge-warning">Food &amp; Beverage</span>
                    <h3 class="project-title">Restaurant QR Ordering</h3>
                    <p class="project-problem">
                        Lets restaurant customers order from their table by scanning a QR code —
                        no app, no waiter needed for every order.
                    </p>
                </div>
                <div class="project-features">
                    <span class="project-feature-pill">QR Per Table</span>
                    <span class="project-feature-pill">Live Kitchen Feed</span>
                    <span class="project-feature-pill">Revenue Dashboard</span>
                </div>
                <p class="project-value">
                    <i class="dc-icon dc-icon-sm dc-icon-dollar"></i>
                    Toast, Mr Yum and Sunday charge restaurants $50–300/mo for this feature set
                </p>
                <div class="project-tech-row">
                    <i class="dc-icon dc-icon-md dc-icon-utensils"></i>
                    <i class="dc-icon dc-icon-md dc-icon-qr-code"></i>
                    <i class="dc-icon dc-icon-md dc-icon-bar-chart"></i>
                </div>
                <div class="project-btns">
                    <a href="<?= htmlspecialchars($dev['github_url']) ?>/restaurant-qr-ordering" target="_blank" rel="noopener" class="dc-btn dc-btn-ghost dc-btn-sm">
                        <i class="dc-icon dc-icon-sm dc-icon-globe"></i>
                        GitHub
                    </a>
                    <a href="../restaurant-qr-ordering/index.php" class="dc-btn dc-btn-primary dc-btn-sm">
                        Live Demo
                        <i class="dc-icon dc-icon-sm dc-icon-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- 2. Real Estate Listings -->
            <div class="dc-card-solid project-card reveal reveal-delay-1" style="--project-accent:#38bdf8;">
                <div>
                    <span class="dc-badge dc-badge-info">Real Estate</span>
                    <h3 class="project-title">Real Estate Listings</h3>
                    <p class="project-problem">
                        Gives agents a full property listing platform with QR codes for physical
                        signboards and live availability updates.
                    </p>
                </div>
                <div class="project-features">
                    <span class="project-feature-pill">QR Signboards</span>
                    <span class="project-feature-pill">Live Availability</span>
                    <span class="project-feature-pill">Inquiry Analytics</span>
                </div>
                <p class="project-value">
                    <i class="dc-icon dc-icon-sm dc-icon-dollar"></i>
                    Platforms like Rex and AgentBox charge agencies $100–500/mo for equivalent tooling
                </p>
                <div class="project-tech-row">
                    <i class="dc-icon dc-icon-md dc-icon-building"></i>
                    <i class="dc-icon dc-icon-md dc-icon-qr-code"></i>
                    <i class="dc-icon dc-icon-md dc-icon-map-pin"></i>
                </div>
                <div class="project-btns">
                    <a href="<?= htmlspecialchars($dev['github_url']) ?>/real-estate-listings" target="_blank" rel="noopener" class="dc-btn dc-btn-ghost dc-btn-sm">
                        <i class="dc-icon dc-icon-sm dc-icon-globe"></i>
                        GitHub
                    </a>
                    <a href="../real-estate-listings/index.php" class="dc-btn dc-btn-primary dc-btn-sm">
                        Live Demo
                        <i class="dc-icon dc-icon-sm dc-icon-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- 3. E-commerce Live Store -->
            <div class="dc-card-solid project-card reveal reveal-delay-2" style="--project-accent:#22d3a0;">
                <div>
                    <span class="dc-badge dc-badge-success">E-commerce</span>
                    <h3 class="project-title">E-commerce Live Store</h3>
                    <p class="project-problem">
                        A full product store with live stock counters that update across all browsers —
                        prevents overselling and shows urgency to buyers.
                    </p>
                </div>
                <div class="project-features">
                    <span class="project-feature-pill">Live Stock Counter</span>
                    <span class="project-feature-pill">QR Receipts</span>
                    <span class="project-feature-pill">Coupon System</span>
                </div>
                <p class="project-value">
                    <i class="dc-icon dc-icon-sm dc-icon-dollar"></i>
                    Comparable to Shopify's Basic plan ($80–300/mo) without the per-transaction cut
                </p>
                <div class="project-tech-row">
                    <i class="dc-icon dc-icon-md dc-icon-shopping-cart"></i>
                    <i class="dc-icon dc-icon-md dc-icon-package"></i>
                    <i class="dc-icon dc-icon-md dc-icon-tag"></i>
                </div>
                <div class="project-btns">
                    <a href="<?= htmlspecialchars($dev['github_url']) ?>/ecommerce-live-store" target="_blank" rel="noopener" class="dc-btn dc-btn-ghost dc-btn-sm">
                        <i class="dc-icon dc-icon-sm dc-icon-globe"></i>
                        GitHub
                    </a>
                    <a href="../ecommerce-live-store/index.php" class="dc-btn dc-btn-primary dc-btn-sm">
                        Live Demo
                        <i class="dc-icon dc-icon-sm dc-icon-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- 4. Medical Booking System -->
            <div class="dc-card-solid project-card reveal reveal-delay-3" style="--project-accent:#6c63ff;">
                <div>
                    <span class="dc-badge dc-badge-accent">Healthcare</span>
                    <h3 class="project-title">Medical Booking System</h3>
                    <p class="project-problem">
                        Lets clinics take online appointments with real-time slot locking — no double
                        bookings, QR appointment card for reception check-in.
                    </p>
                </div>
                <div class="project-features">
                    <span class="project-feature-pill">Real-time Slot Locking</span>
                    <span class="project-feature-pill">QR Appointment Card</span>
                    <span class="project-feature-pill">No-show Detection</span>
                </div>
                <p class="project-value">
                    <i class="dc-icon dc-icon-sm dc-icon-dollar"></i>
                    HotDoc and Cliniko charge clinics $150–600/mo for booking systems at this level
                </p>
                <div class="project-tech-row">
                    <i class="dc-icon dc-icon-md dc-icon-hospital"></i>
                    <i class="dc-icon dc-icon-md dc-icon-calendar"></i>
                    <i class="dc-icon dc-icon-md dc-icon-stethoscope"></i>
                </div>
                <div class="project-btns">
                    <a href="<?= htmlspecialchars($dev['github_url']) ?>/medical-booking-system" target="_blank" rel="noopener" class="dc-btn dc-btn-ghost dc-btn-sm">
                        <i class="dc-icon dc-icon-sm dc-icon-globe"></i>
                        GitHub
                    </a>
                    <a href="../medical-booking-system/index.php" class="dc-btn dc-btn-primary dc-btn-sm">
                        Live Demo
                        <i class="dc-icon dc-icon-sm dc-icon-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div><!-- /projects grid -->

    </div>
</section>


<!-- ═══════════════════════════════════════════════════════════
     HOW I THINK — Decision Log
     ═══════════════════════════════════════════════════════════ -->
<section id="thinking" class="pf-section-alt">
    <div class="dc-container">

        <div class="pf-section-head">
            <p class="dc-label dc-mb-sm reveal">How I Think</p>
            <h2 class="dc-h2 dc-mb-sm reveal reveal-delay-1">Three decisions I made building DevCore — and why.</h2>
            <p class="dc-body reveal reveal-delay-2">
                Not a features list. The actual reasoning behind architecture choices —
                including the ones I'd do differently now.
            </p>
        </div>

        <div class="thinking-grid reveal">

            <!-- Table of contents -->
            <div class="thinking-toc dc-hide-mobile">
                <p class="toc-label">In this section</p>
                <a class="toc-link active" href="#thought-1">
                    <span class="toc-num">01</span>
                    Why a shared core
                </a>
                <a class="toc-link" href="#thought-2">
                    <span class="toc-num">02</span>
                    The Storage abstraction
                </a>
                <a class="toc-link" href="#thought-3">
                    <span class="toc-num">03</span>
                    What I'd change
                </a>
            </div>

            <!-- Content -->
            <div class="thinking-content">

                <!-- Decision 01 -->
                <div class="thought-block" id="thought-1">
                    <p class="thought-label">
                        <i class="dc-icon dc-icon-sm dc-icon-note"></i>
                        Decision 01
                    </p>
                    <h3 class="thought-h3">Why a shared core instead of four independent projects</h3>
                    <div class="thought-body">
                        <p>
                            The first question was whether to build four separate apps or extract
                            shared code into its own library. The easy path was four independent
                            projects — each self-contained, easier to demo, no cross-project
                            dependencies to manage. Most portfolio advice says: ship things, don't
                            over-architect.
                        </p>
                        <p>
                            I chose the shared core anyway, for one concrete reason: I noticed I
                            was about to write the same database connection logic for the second
                            time. That was the signal. If I was already copy-pasting in week two,
                            I'd be maintaining four diverging copies by week eight — and any bug
                            fix or improvement would need to be applied four times, consistently,
                            without forgetting one.
                        </p>

                        <div class="thought-callout">
                            <strong>The actual trade-off:</strong> it added roughly two days of
                            upfront work to design <code>bootstrap.php</code>, the autoloader, and
                            <code>Api</code>. In exchange, every subsequent project started with
                            working database access, consistent JSON responses, and validated input
                            handling — on day one, for free.
                        </div>

                        <p>
                            The harder part was deciding what belonged in the core versus what was
                            project-specific. My rule: if a class needed to know anything about
                            the application's domain (menus, properties, appointments), it stayed
                            in the project. If it was purely infrastructure — connection management,
                            response formatting, file storage — it went in the core. That line held
                            across all four projects without exception.
                        </p>
                    </div>
                </div>

                <hr class="thought-divider">

                <!-- Decision 02 -->
                <div class="thought-block" id="thought-2">
                    <p class="thought-label">
                        <i class="dc-icon dc-icon-sm dc-icon-cloud"></i>
                        Decision 02
                    </p>
                    <h3 class="thought-h3">The Storage abstraction: three drivers, one interface</h3>
                    <div class="thought-body">
                        <p>
                            Early on, file uploads went directly to the local filesystem. That
                            works until you want to move a project to a server where the upload
                            directory isn't writable, or a client wants their images on S3, or you
                            need R2 for cost reasons. Changing it later means touching every
                            upload call in every project.
                        </p>
                        <p>
                            So I designed a <code>StorageInterface</code> first — just two methods:
                            <code>upload()</code> and <code>delete()</code> — then wrote three
                            drivers that implement it: <code>LocalStorage</code>,
                            <code>S3Storage</code>, and <code>R2Storage</code>. The <code>Storage</code>
                            facade reads the config and hands back the right driver. Application
                            code never calls a driver directly.
                        </p>

                        <table class="decision-table">
                            <thead>
                                <tr>
                                    <th>Option considered</th>
                                    <th>Rejected because</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Hardcode S3 everywhere</td>
                                    <td>Local dev requires S3 credentials or a mock. Friction on every setup.</td>
                                </tr>
                                <tr>
                                    <td>if/else on a config flag per call</td>
                                    <td>Every upload becomes a branching statement. Easy to miss one.</td>
                                </tr>
                                <tr>
                                    <td class="dt-chosen">Interface + drivers + facade</td>
                                    <td style="color:var(--dc-success);">Switch provider by changing one config value. Zero application code changes.</td>
                                </tr>
                            </tbody>
                        </table>

                        <p>
                            The cost was writing three driver classes instead of one. The benefit
                            is that when a project needs to move from local to S3, it's a single
                            line in the config. I've already done that once during development when
                            testing the e-commerce project's image uploads, and it took about four
                            minutes including verifying the bucket policy.
                        </p>
                    </div>
                </div>

                <hr class="thought-divider">

                <!-- Decision 03 — What I'd change -->
                <div class="thought-block" id="thought-3">
                    <p class="thought-label">
                        <i class="dc-icon dc-icon-sm dc-icon-refresh"></i>
                        What I'd change
                    </p>
                    <h3 class="thought-h3">Three things I'd do differently with another week</h3>
                    <div class="thought-body">
                        <p>
                            Honest retrospective — things I know are gaps, not things I'm
                            planning to fix "eventually":
                        </p>

                        <p>
                            <strong>No tests.</strong> The core library has no unit tests.
                            <code>Validator</code> and <code>Api</code> are the most-used classes
                            across all four projects and they're entirely untested. I know how to
                            write PHPUnit tests — I haven't written them here. That's a real gap,
                            not a stylistic choice. If I were joining a team that had a test suite,
                            I'd be contributing to it from day one.
                        </p>

                        <p>
                            <strong>No migrations.</strong> The database schemas are in <code>.sql</code>
                            files you run once. There's no migration system, no version tracking,
                            no rollback. That's fine for a portfolio — it would not be fine for a
                            production application with real data. I'm aware of what that would
                            require (schema versioning, up/down migrations, a runner) and it's the
                            obvious next thing to build if this were a real product.
                        </p>

                        <p>
                            <strong>Config leaks into bootstrap.</strong> The global exception
                            handler in <code>bootstrap.php</code> calls <code>require config.php</code>
                            inline — which means an exception thrown before config loads will throw
                            a secondary fatal error. The fix is a config singleton with a fallback
                            default, loaded once at the top of bootstrap. I spotted it late and
                            didn't rebuild around it. I would now.
                        </p>

                        <div class="thought-callout">
                            <strong>Why include the gaps at all?</strong> Because any senior developer
                            reading the code will find them in ten minutes. Flagging them first
                            signals that I found them too — and that I understand why they matter
                            in a production context, even if they're acceptable here.
                        </div>
                    </div>
                </div>

            </div><!-- /thinking-content -->
        </div><!-- /thinking-grid -->
    </div>
</section>


<!-- ═══════════════════════════════════════════════════════════
     CONTACT
     ═══════════════════════════════════════════════════════════ -->
<section id="contact" class="pf-section-alt">
    <div class="dc-container">
        <div class="contact-grid">

            <!-- LEFT: The Close -->
            <div>
                <p class="dc-label dc-mb-sm reveal">Work Together</p>
                <h2 class="dc-h2 dc-mb-lg reveal reveal-delay-1">Let's talk about what you need.</h2>

                <p class="dc-body reveal reveal-delay-2" style="margin-bottom:28px; line-height:1.75;">
                    Whether you need a part-time developer to join your team, a contractor for a
                    scoped project, an extra pair of hands for an agency client, or a freelancer to
                    build something from scratch — I am available and ready to start.
                </p>

                <div class="reveal reveal-delay-2">
                    <div class="contact-row">
                        <i class="dc-icon dc-icon-md dc-icon-mail contact-row-icon"></i>
                        <a href="mailto:<?= htmlspecialchars($dev['email']) ?>">
                            <?= htmlspecialchars($dev['email']) ?>
                        </a>
                    </div>
                    <div class="contact-row">
                        <i class="dc-icon dc-icon-md dc-icon-globe contact-row-icon"></i>
                        <a href="<?= htmlspecialchars($dev['github_url']) ?>" target="_blank" rel="noopener">
                            <?= htmlspecialchars($dev['github']) ?>
                        </a>
                    </div>
                    <div class="contact-row">
                        <i class="dc-icon dc-icon-md dc-icon-clock contact-row-icon"></i>
                        <span>Usually replies within 24 hours</span>
                    </div>
                </div>

                <!-- Availability card -->
                <div class="dc-card-accent contact-avail-card dc-mt-lg reveal reveal-delay-3">
                    <div class="contact-avail-top">
                        <span class="dc-live__dot"></span>
                        Currently available for new work
                    </div>
                    <div class="hero-work-pills" style="margin-top:4px;">
                        <span class="dc-badge dc-badge-accent">Part-time</span>
                        <span class="dc-badge dc-badge-info">Contract</span>
                        <span class="dc-badge dc-badge-success">Freelance</span>
                        <span class="dc-badge dc-badge-warning">Agency</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Contact Form -->
            <div class="reveal reveal-delay-1">
                <div class="dc-card-solid">
                    <div id="contact-form" class="contact-form">

                        <div class="dc-form-group">
                            <label class="dc-label-field" for="cf-name">Name</label>
                            <input type="text"  id="cf-name"    name="name"    class="dc-input"
                                   placeholder="Your name" required>
                        </div>

                        <div class="dc-form-group">
                            <label class="dc-label-field" for="cf-email">Email</label>
                            <input type="email" id="cf-email"   name="email"   class="dc-input"
                                   placeholder="you@example.com" required>
                        </div>

                        <div class="dc-form-group">
                            <label class="dc-label-field" for="cf-type">What are you looking for?</label>
                            <select id="cf-type" name="work_type" class="dc-select">
                                <option value="">What are you looking for?</option>
                                <option value="part-time">Part-time Developer Role</option>
                                <option value="contract">Contract Project</option>
                                <option value="agency">Agency / White-label Work</option>
                                <option value="freelance">Freelance Project</option>
                                <option value="other">Just Saying Hi</option>
                            </select>
                        </div>

                        <div class="dc-form-group">
                            <label class="dc-label-field" for="cf-budget">Budget or rate range (optional)</label>
                            <select id="cf-budget" name="budget" class="dc-select">
                                <option value="">Budget or rate range (optional)</option>
                                <option value="under-500">Under $500</option>
                                <option value="500-2000">$500 – $2,000</option>
                                <option value="2000-5000">$2,000 – $5,000</option>
                                <option value="5000+">$5,000+</option>
                                <option value="hourly">Hourly rate (discuss)</option>
                            </select>
                        </div>

                        <div class="dc-form-group">
                            <label class="dc-label-field" for="cf-message">Message</label>
                            <textarea id="cf-message" name="message" class="dc-textarea"
                                      rows="4" placeholder="Tell me about the project or role..." required></textarea>
                        </div>

                        <button type="button" id="cf-submit" class="dc-btn dc-btn-primary dc-btn-full dc-btn-lg">
                            Send Message
                            <i class="dc-icon dc-icon-md dc-icon-arrow-right"></i>
                        </button>

                    </div>
                </div>
            </div>

        </div><!-- /contact-grid -->
    </div>
</section>


<!-- ═══════════════════════════════════════════════════════════
     FOOTER
     ═══════════════════════════════════════════════════════════ -->
<footer class="pf-footer">
    <div class="dc-container">

        <div class="footer-grid">

            <!-- Left: Brand -->
            <div>
                <p class="footer-brand-name">
                    <?= htmlspecialchars($dev['name']) ?><span class="brand-dot">.</span>
                </p>
                <p class="footer-brand-tag">
                    <?= htmlspecialchars($dev['title']) ?><br>
                    <?= htmlspecialchars($dev['tagline']) ?>
                </p>
            </div>

            <!-- Center: Nav -->
            <div class="footer-links">
                <a href="#about"    class="footer-link">About</a>
                <a href="#skills"   class="footer-link">Skills</a>
                <a href="#projects" class="footer-link">Projects</a>
                <a href="#thinking" class="footer-link">How I Think</a>
                <a href="#contact"  class="footer-link">Contact</a>
            </div>

            <!-- Right: GitHub + credit -->
            <div class="footer-col-right">
                <a href="<?= htmlspecialchars($dev['github_url']) ?>" target="_blank" rel="noopener" class="dc-btn dc-btn-ghost dc-btn-sm">
                    <i class="dc-icon dc-icon-sm dc-icon-globe"></i>
                    <?= htmlspecialchars($dev['github']) ?>
                </a>
                <p class="footer-built-note">Portfolio built with DevCore</p>
            </div>

        </div>

        <!-- Bottom strip -->
        <div class="footer-bottom">
            <p class="footer-copy">
                &copy; <?= date('Y') ?> <?= htmlspecialchars($dev['name']) ?>. All rights reserved.
            </p>
            <div class="open-to-work">
                <span class="dc-live__dot"></span>
                Open to work
            </div>
        </div>

    </div>
</footer>


<!-- ═══════════════════════════════════════════════════════════
     SCRIPTS
     ═══════════════════════════════════════════════════════════ -->
<script src="../core/ui/devcore.js"></script>
<script>
(function () {
    'use strict';

    /* ── Smooth scroll for anchor links ─────────────────────── */
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            const target = document.querySelector(link.getAttribute('href'));
            if (!target) return;
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            // Close mobile nav if open
            document.getElementById('mobile-nav').classList.remove('open');
        });
    });

    /* ── Mobile hamburger ───────────────────────────────────── */
    const hamburger = document.getElementById('hamburger');
    const mobileNav = document.getElementById('mobile-nav');
    hamburger.addEventListener('click', () => {
        mobileNav.classList.toggle('open');
    });

    /* ── Scrollspy ──────────────────────────────────────────── */
    const sections  = document.querySelectorAll('section[id], div[id="hero"]');
    const navLinks  = document.querySelectorAll('[data-spy]');
    const mNavLinks = document.querySelectorAll('[data-spy-m]');

    const spy = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const id = entry.target.id;
            navLinks.forEach(l => {
                l.classList.toggle('active', l.dataset.spy === id);
            });
            mNavLinks.forEach(l => {
                l.classList.toggle('active', l.dataset.spyM === id);
            });
        });
    }, { rootMargin: '-40% 0px -55% 0px' });

    sections.forEach(s => spy.observe(s));

    /* ── Thinking section ToC scrollspy ─────────────────────── */
    const thoughtBlocks = document.querySelectorAll('.thought-block[id]');
    const tocLinks      = document.querySelectorAll('.toc-link[href^="#thought"]');

    if (thoughtBlocks.length && tocLinks.length) {
        const tocSpy = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const id = '#' + entry.target.id;
                tocLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === id));
            });
        }, { rootMargin: '-20% 0px -70% 0px' });
        thoughtBlocks.forEach(b => tocSpy.observe(b));
    }

    /* ── Scroll-reveal ──────────────────────────────────────── */
    const revealObs = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                revealObs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

    /* ── Skill bar animation on reveal ─────────────────────── */
    const barObs = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.querySelectorAll('.skill-bar-fill').forEach(bar => {
                // rAF to allow the card's reveal transition to start first
                requestAnimationFrame(() => {
                    setTimeout(() => {
                        bar.style.width = bar.dataset.width + '%';
                    }, 200);
                });
            });
            barObs.unobserve(entry.target);
        });
    }, { threshold: 0.2 });

    document.querySelectorAll('#skills-grid .dc-card-solid').forEach(card => {
        barObs.observe(card);
    });

    /* ── Hero stat counter animation ────────────────────────── */
    // devcore.js auto-handles .dc-stat__value[data-count] on DOMContentLoaded
    // hero stats use that class — handled by devcore.js init observer

    /* ── Contact form submission ────────────────────────────── */
    const submitBtn = document.getElementById('cf-submit');

    function getFormData() {
        return {
            name:      document.getElementById('cf-name').value.trim(),
            email:     document.getElementById('cf-email').value.trim(),
            work_type: document.getElementById('cf-type').value,
            budget:    document.getElementById('cf-budget').value,
            message:   document.getElementById('cf-message').value.trim(),
        };
    }

    function clearFormErrors() {
        document.querySelectorAll('#contact-form .dc-error-msg').forEach(el => el.remove());
        document.querySelectorAll('#contact-form .dc-input-error').forEach(el => {
            el.classList.remove('dc-input-error');
        });
    }

    function showFieldError(name, msg) {
        const input = document.querySelector(`#contact-form [name="${name}"]`);
        if (!input) return;
        input.classList.add('dc-input-error');
        const span = document.createElement('span');
        span.className = 'dc-error-msg';
        span.textContent = msg;
        input.parentNode.appendChild(span);
    }

    function clientValidate(data) {
        const errs = {};
        if (!data.name || data.name.length < 2)
            errs.name = 'Name must be at least 2 characters';
        if (!data.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email))
            errs.email = 'Please enter a valid email address';
        if (!data.message || data.message.length < 10)
            errs.message = 'Message must be at least 10 characters';
        return errs;
    }

    submitBtn.addEventListener('click', async () => {
        clearFormErrors();
        const data = getFormData();
        const clientErrors = clientValidate(data);

        if (Object.keys(clientErrors).length > 0) {
            Object.entries(clientErrors).forEach(([k, v]) => showFieldError(k, v));
            return;
        }

        DCForm.setLoading(submitBtn, true);

        try {
            await DC.post('api/contact.php', data);
            Toast.success("Message sent! I'll reply within 24 hours.");

            // Reset form
            ['cf-name','cf-email','cf-type','cf-budget','cf-message'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = el.tagName === 'SELECT' ? '' : '';
            });
        } catch (err) {
            Toast.error('Something went wrong. Try emailing directly.');
        } finally {
            DCForm.setLoading(submitBtn, false);
        }
    });

})();
</script>
</body>
</html>
