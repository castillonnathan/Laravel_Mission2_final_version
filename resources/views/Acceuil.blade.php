<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VEM — Vercorium Extraction & Modélisation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,600;1,300&family=DM+Mono:wght@300;400&display=swap" rel="stylesheet">
    <style>
        :root {
            --obsidian: #0a0a0a;
            --stone: #1a1a18;
            --ore: #2c2c28;
            --dust: #8a8a7a;
            --chalk: #e8e6e0;
            --gold: #b8a46a;
            --gold-light: #d4c08a;
            --acid: #c8f53e;
            --white: #f5f4f0;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            background: var(--obsidian);
            color: var(--chalk);
            font-family: 'DM Mono', monospace;
            font-weight: 300;
            overflow-x: hidden;
            cursor: crosshair;
        }

        /* NAV */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 3rem;
            border-bottom: 1px solid rgba(184,164,106,0.15);
            backdrop-filter: blur(12px);
            background: rgba(10,10,10,0.85);
        }
        .nav-logo {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            font-size: 1.1rem;
            letter-spacing: 0.3em;
            color: var(--gold);
            text-decoration: none;
            text-transform: uppercase;
        }
        .nav-links { display: flex; gap: 2rem; list-style: none; }
        .nav-links a { font-size: 0.6rem; letter-spacing: 0.18em; text-transform: uppercase; color: var(--dust); text-decoration: none; transition: color 0.3s; }
        .nav-links a:hover { color: var(--gold-light); }
        .nav-actions { display: flex; gap: 0.75rem; align-items: center; }
        .btn-nav {
            font-family: 'DM Mono', monospace;
            font-size: 0.6rem; letter-spacing: 0.15em; text-transform: uppercase;
            padding: 0.5rem 1.2rem;
            border: 1px solid var(--gold); color: var(--gold); background: transparent;
            text-decoration: none; cursor: crosshair; transition: all 0.3s;
        }
        .btn-nav:hover { background: var(--gold); color: var(--obsidian); }
        .btn-nav.filled { background: var(--gold); color: var(--obsidian); }
        .btn-nav.filled:hover { background: var(--gold-light); border-color: var(--gold-light); }

        /* HERO */
        #hero {
            min-height: 100vh;
            display: grid;
            grid-template-rows: 1fr auto;
            position: relative;
            overflow: hidden;
            padding-top: 80px;
        }
        .hero-bg {
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 70% 40%, rgba(184,164,106,0.06) 0%, transparent 60%),
                radial-gradient(ellipse 40% 60% at 20% 80%, rgba(200,245,62,0.03) 0%, transparent 50%),
                repeating-linear-gradient(90deg, transparent, transparent 79px, rgba(184,164,106,0.04) 79px, rgba(184,164,106,0.04) 80px),
                repeating-linear-gradient(0deg,  transparent, transparent 79px, rgba(184,164,106,0.04) 79px, rgba(184,164,106,0.04) 80px);
        }
        .hero-content {
            display: flex; flex-direction: column; justify-content: center;
            padding: 6rem 3rem 3rem;
            position: relative; z-index: 1;
            max-width: 1200px; margin: 0 auto; width: 100%;
        }
        .hero-eyebrow {
            font-size: 0.6rem; letter-spacing: 0.4em; text-transform: uppercase; color: var(--gold);
            margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;
            animation: fadeUp 0.8s 0.2s both;
        }
        .hero-eyebrow::before { content:''; display:block; width:40px; height:1px; background:var(--gold); }
        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 300; font-size: clamp(4rem, 10vw, 9rem);
            line-height: 0.9; letter-spacing: -0.02em; color: var(--white); max-width: 900px;
            animation: fadeUp 0.8s 0.4s both;
        }
        .hero-title em { font-style: italic; color: var(--gold); }
        .hero-sub {
            margin-top: 3rem; max-width: 420px; font-size: 0.72rem;
            line-height: 1.9; color: var(--dust); letter-spacing: 0.05em;
            animation: fadeUp 0.8s 0.6s both;
        }
        .hero-cta { margin-top: 3rem; display: flex; align-items: center; gap: 2rem; animation: fadeUp 0.8s 0.8s both; }
        .btn-hero {
            font-family: 'DM Mono', monospace;
            font-size: 0.65rem; letter-spacing: 0.2em; text-transform: uppercase;
            padding: 1rem 2.5rem; background: var(--gold); color: var(--obsidian);
            text-decoration: none; border: none; cursor: crosshair; transition: all 0.3s;
            position: relative; overflow: hidden;
        }
        .btn-hero::after {
            content: ''; position: absolute; inset: 0; background: var(--acid);
            transform: translateX(-101%); transition: transform 0.4s cubic-bezier(0.76, 0, 0.24, 1);
        }
        .btn-hero:hover::after { transform: translateX(0); }
        .btn-hero span { position: relative; z-index: 1; }
        .hero-scroll { font-size: 0.55rem; letter-spacing: 0.3em; text-transform: uppercase; color: var(--dust); }
        .hero-data-strip {
            border-top: 1px solid rgba(184,164,106,0.2);
            display: grid; grid-template-columns: repeat(3, 1fr);
            position: relative; z-index: 1;
            animation: fadeUp 0.8s 1.0s both;
        }
        .data-item { padding: 2rem 3rem; border-right: 1px solid rgba(184,164,106,0.2); }
        .data-item:last-child { border-right: none; }
        .data-label { font-size: 0.55rem; letter-spacing: 0.25em; text-transform: uppercase; color: var(--dust); margin-bottom: 0.5rem; }
        .data-value { font-family: 'Cormorant Garamond', serif; font-size: 2rem; color: var(--gold); font-weight: 300; }

        /* SECTION COMMONS */
        .section-label {
            font-size: 0.55rem; letter-spacing: 0.35em; text-transform: uppercase; color: var(--gold);
            display: flex; align-items: center; gap: 1rem; margin-bottom: 4rem;
        }
        .section-label::after { content: ''; flex: 1; height: 1px; background: rgba(184,164,106,0.25); }
        .section-wrap { padding: 8rem 3rem; max-width: 1200px; margin: 0 auto; }
        .section-dark { padding: 8rem 3rem; background: var(--stone); border-top: 1px solid rgba(184,164,106,0.12); border-bottom: 1px solid rgba(184,164,106,0.12); }
        .section-dark .inner { max-width: 1200px; margin: 0 auto; }

        /* PRÉSENTATION */
        .pres-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6rem; align-items: start; }
        .pres-heading { font-family: 'Cormorant Garamond', serif; font-weight: 300; font-size: clamp(2.5rem, 4vw, 4rem); line-height: 1.1; color: var(--white); }
        .pres-heading em { font-style: italic; color: var(--gold); }
        .pres-body { font-size: 0.72rem; line-height: 2; color: var(--dust); letter-spacing: 0.04em; }
        .pres-body p + p { margin-top: 1.5rem; }
        .pres-body strong { color: var(--chalk); font-weight: 400; }

        /* ACTUALITÉS */
        .news-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: rgba(184,164,106,0.12); border: 1px solid rgba(184,164,106,0.12); }
        .news-card {
            background: var(--stone); padding: 2.5rem;
            display: flex; flex-direction: column; gap: 1rem;
            transition: background 0.3s; cursor: crosshair;
            position: relative; overflow: hidden;
        }
        .news-card::after {
            content: ''; position: absolute; bottom: 0; left: 0;
            width: 100%; height: 2px;
            background: linear-gradient(90deg, var(--gold), transparent);
            transform: scaleX(0); transform-origin: left;
            transition: transform 0.4s cubic-bezier(0.76,0,0.24,1);
        }
        .news-card:hover { background: var(--ore); }
        .news-card:hover::after { transform: scaleX(1); }
        .news-tag {
            font-size: 0.5rem; letter-spacing: 0.25em; text-transform: uppercase; color: var(--gold);
            padding: 0.25rem 0.6rem; border: 1px solid rgba(184,164,106,0.4);
            display: inline-block; width: fit-content;
        }
        .news-date { font-size: 0.55rem; letter-spacing: 0.15em; color: var(--dust); }
        .news-title { font-family: 'Cormorant Garamond', serif; font-size: 1.25rem; font-weight: 300; color: var(--white); line-height: 1.3; }
        .news-excerpt { font-size: 0.62rem; color: var(--dust); line-height: 1.8; }
        .news-link {
            margin-top: auto; font-size: 0.55rem; letter-spacing: 0.2em; text-transform: uppercase;
            color: var(--gold); text-decoration: none;
            display: flex; align-items: center; gap: 0.5rem; transition: gap 0.3s;
        }
        .news-card:hover .news-link { gap: 1rem; }

        /* RAPPORTS */
        .reports-layout { display: grid; grid-template-columns: 1fr 2fr; gap: 4rem; align-items: start; }
        .reports-intro { font-size: 0.7rem; line-height: 1.9; color: var(--dust); }
        .reports-intro strong { color: var(--chalk); font-weight: 400; }
        .reports-intro p + p { margin-top: 1.2rem; }
        .reports-tabs { display: flex; margin-bottom: 1.5rem; }
        .tab-btn {
            font-family: 'DM Mono', monospace; font-size: 0.55rem; letter-spacing: 0.2em; text-transform: uppercase;
            padding: 0.7rem 1.5rem; background: transparent;
            border: 1px solid rgba(184,164,106,0.3); color: var(--dust);
            cursor: crosshair; transition: all 0.3s; margin-right: -1px;
        }
        .tab-btn.active { background: var(--gold); color: var(--obsidian); border-color: var(--gold); z-index: 1; }
        .tab-btn:hover:not(.active) { background: var(--ore); color: var(--chalk); }
        .report-list { display: flex; flex-direction: column; }
        .report-item {
            display: grid; grid-template-columns: auto 1fr auto; align-items: center;
            gap: 1.5rem; padding: 1.2rem 0;
            border-bottom: 1px solid rgba(184,164,106,0.1);
            cursor: crosshair; transition: all 0.3s;
        }
        .report-item:first-child { border-top: 1px solid rgba(184,164,106,0.1); }
        .report-item:hover { padding-left: 0.5rem; }
        .report-icon { color: var(--gold); font-size: 1rem; opacity: 0.7; }
        .report-meta { display: flex; flex-direction: column; gap: 0.2rem; }
        .report-name { font-size: 0.65rem; color: var(--chalk); letter-spacing: 0.05em; }
        .report-period { font-size: 0.55rem; letter-spacing: 0.12em; color: var(--dust); }
        .report-dl {
            font-size: 0.5rem; letter-spacing: 0.2em; text-transform: uppercase;
            color: var(--gold); text-decoration: none;
            padding: 0.4rem 0.8rem; border: 1px solid rgba(184,164,106,0.3);
            transition: all 0.3s; white-space: nowrap;
        }
        .report-dl:hover { background: var(--gold); color: var(--obsidian); }

        /* ÉQUIPE */
        .team-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: rgba(184,164,106,0.15); border: 1px solid rgba(184,164,106,0.15); }
        .team-member {
            background: var(--stone); padding: 3rem 2.5rem;
            position: relative; overflow: hidden; transition: background 0.4s;
        }
        .team-member::before {
            content: ''; position: absolute; bottom: 0; left: 0;
            width: 100%; height: 2px;
            background: linear-gradient(90deg, var(--gold), transparent);
            transform: scaleX(0); transform-origin: left;
            transition: transform 0.4s cubic-bezier(0.76, 0, 0.24, 1);
        }
        .team-member:hover { background: var(--ore); }
        .team-member:hover::before { transform: scaleX(1); }
        .member-num { font-size: 0.5rem; letter-spacing: 0.3em; color: var(--gold); margin-bottom: 2rem; opacity: 0.6; }
        .member-name { font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; font-weight: 300; color: var(--white); line-height: 1.2; margin-bottom: 1rem; }
        .member-role { font-size: 0.6rem; letter-spacing: 0.15em; text-transform: uppercase; color: var(--gold); margin-bottom: 0.4rem; }
        .member-affil { font-size: 0.62rem; color: var(--dust); font-style: italic; letter-spacing: 0.05em; }

        /* ACTIVITÉS */
        .act-list { display: flex; flex-direction: column; }
        .act-item {
            display: grid; grid-template-columns: 60px 1fr auto;
            align-items: center; padding: 2.5rem 0;
            border-bottom: 1px solid rgba(184,164,106,0.15);
            gap: 2rem; transition: all 0.3s; cursor: crosshair;
        }
        .act-item:first-child { border-top: 1px solid rgba(184,164,106,0.15); }
        .act-item:hover .act-title { color: var(--gold); }
        .act-item:hover .act-arrow { transform: translateX(8px); color: var(--gold); }
        .act-num { font-size: 0.55rem; letter-spacing: 0.2em; color: var(--gold); opacity: 0.5; }
        .act-title { font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; font-weight: 300; color: var(--white); transition: color 0.3s; }
        .act-arrow { font-size: 1.2rem; color: var(--dust); transition: all 0.3s; }

        /* CONTACT */
        .contact-grid { display: grid; grid-template-columns: 1fr 1.6fr; gap: 6rem; align-items: start; }
        .contact-heading { font-family: 'Cormorant Garamond', serif; font-size: clamp(2rem, 3.5vw, 3.2rem); font-weight: 300; color: var(--white); line-height: 1.1; margin-bottom: 2rem; }
        .contact-heading em { font-style: italic; color: var(--gold); }
        .contact-detail { margin-top: 2.5rem; display: flex; flex-direction: column; gap: 1.2rem; }
        .contact-line { display: flex; flex-direction: column; gap: 0.25rem; }
        .contact-line-label { font-size: 0.5rem; letter-spacing: 0.3em; text-transform: uppercase; color: var(--dust); }
        .contact-line-val { font-size: 0.7rem; color: var(--chalk); letter-spacing: 0.05em; }
        .contact-line-val a { color: var(--gold); text-decoration: none; }
        .contact-line-val a:hover { color: var(--gold-light); }
        .form-tabs { display: flex; margin-bottom: 2rem; }
        .form-tab {
            font-family: 'DM Mono', monospace; font-size: 0.55rem; letter-spacing: 0.2em; text-transform: uppercase;
            padding: 0.8rem 1.8rem; background: transparent;
            border: 1px solid rgba(184,164,106,0.3); color: var(--dust);
            cursor: crosshair; transition: all 0.3s; margin-right: -1px;
        }
        .form-tab.active { background: var(--gold); color: var(--obsidian); border-color: var(--gold); z-index: 1; }
        .form-tab:hover:not(.active) { background: var(--ore); color: var(--chalk); }
        .form-panel { display: none; }
        .form-panel.active { display: block; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1rem; }
        .form-group label { font-size: 0.55rem; letter-spacing: 0.2em; text-transform: uppercase; color: var(--dust); }
        .form-group input,
        .form-group textarea,
        .form-group select {
            font-family: 'DM Mono', monospace; font-size: 0.68rem;
            background: var(--ore); border: 1px solid rgba(184,164,106,0.2);
            color: var(--chalk); padding: 0.85rem 1rem;
            outline: none; transition: border-color 0.3s; cursor: crosshair;
            letter-spacing: 0.04em; resize: vertical;
        }
        .form-group input::placeholder,
        .form-group textarea::placeholder { color: var(--dust); opacity: 0.5; }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus { border-color: var(--gold); }
        .form-group select option { background: var(--ore); }
        .form-group textarea { min-height: 120px; }
        .btn-submit {
            font-family: 'DM Mono', monospace; font-size: 0.65rem; letter-spacing: 0.2em; text-transform: uppercase;
            padding: 1rem 2.5rem; background: var(--gold); color: var(--obsidian);
            border: none; cursor: crosshair; transition: all 0.3s;
            position: relative; overflow: hidden; width: 100%; margin-top: 0.5rem;
        }
        .btn-submit::after {
            content: ''; position: absolute; inset: 0; background: var(--acid);
            transform: translateX(-101%); transition: transform 0.4s cubic-bezier(0.76, 0, 0.24, 1);
        }
        .btn-submit:hover::after { transform: translateX(0); }
        .btn-submit span { position: relative; z-index: 1; }

        /* FOOTER */
        footer {
            padding: 2rem 3rem; background: var(--obsidian);
            border-top: 1px solid rgba(184,164,106,0.1);
            display: flex; justify-content: space-between; align-items: center;
        }
        .footer-brand { font-family: 'Cormorant Garamond', serif; color: var(--gold); font-size: 0.85rem; letter-spacing: 0.2em; }
        .footer-copy { font-size: 0.55rem; letter-spacing: 0.15em; color: var(--dust); }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 900px) {
            nav { padding: 1rem 1.5rem; }
            .nav-links { display: none; }
            .hero-content { padding: 4rem 1.5rem 2rem; }
            .hero-data-strip { grid-template-columns: 1fr; }
            .data-item { border-right: none; border-bottom: 1px solid rgba(184,164,106,0.2); }
            .section-wrap { padding: 5rem 1.5rem; }
            .section-dark { padding: 5rem 1.5rem; }
            .pres-grid, .reports-layout, .contact-grid { grid-template-columns: 1fr; gap: 3rem; }
            .news-grid, .team-grid { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            footer { flex-direction: column; gap: 1rem; text-align: center; }
        }
    </style>
</head>
<body>

<!-- NAV -->
<nav>
    <a href="#hero" class="nav-logo">VEM</a>
    <ul class="nav-links">
        <li><a href="#presentation">Présentation</a></li>
        <li><a href="#actualites">Actualités</a></li>
        <li><a href="#rapports">Rapports</a></li>
        <li><a href="#equipe">Équipe</a></li>
        <li><a href="#activites">Activités</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
    <div class="nav-actions">
        @guest
            <a href="{{ route('register') }}" class="btn-nav">S'inscrire</a>
            <a href="{{ route('login') }}" class="btn-nav filled">Connexion</a>
        @endguest
        @auth
            <a href="{{ route('dashboard') }}" class="btn-nav">Mon espace</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button class="btn-nav" type="submit" style="cursor:crosshair;">Déconnexion</button>
            </form>
        @endauth
    </div>
</nav>

<!-- HERO -->
<section id="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
        <div class="hero-eyebrow">Valence, Vercors — Fondée 2024</div>
        <h1 class="hero-title">
            Vercorium<br>
            <em>Extraction &</em><br>
            Modélisation
        </h1>
        <p class="hero-sub">
            L'innovation minière et scientifique au cœur du Vercors. Nous exploitons un minerai inédit
            qui pourrait transformer les secteurs de l'énergie et de la recherche.
        </p>
        <div class="hero-cta">
            <a href="#presentation" class="btn-hero"><span>Découvrir VEM</span></a>
            <span class="hero-scroll">↓ Défiler</span>
        </div>
    </div>
    <div class="hero-data-strip">
        <div class="data-item">
            <div class="data-label">Fondation</div>
            <div class="data-value">2024</div>
        </div>
        <div class="data-item">
            <div class="data-label">Spécialité</div>
            <div class="data-value">Vercorium</div>
        </div>
        <div class="data-item">
            <div class="data-label">Disciplines</div>
            <div class="data-value">03</div>
        </div>
    </div>
</section>

<!-- PRÉSENTATION -->
<section id="presentation">
    <div class="section-wrap">
        <div class="section-label">[ 01 ] Présentation</div>
        <div class="pres-grid">
            <div>
                <h2 class="pres-heading">Un minerai qui<br><em>change tout.</em></h2>
            </div>
            <div class="pres-body">
                <p>
                    <strong>Vercorium Extraction &amp; Modélisation (VEM)</strong> est une entreprise innovante fondée à Valence en 2024,
                    à la suite de la découverte d'un minerai inédit : le <strong>vercorium</strong>.
                    Ce matériau unique pourrait révolutionner les secteurs de l'énergie et de la recherche scientifique.
                </p>
                <p>
                    VEM s'appuie sur une équipe pluridisciplinaire de géologues, ingénieurs et data scientists,
                    unissant science des matériaux, exploitation minière et modélisation numérique pour extraire
                    tout le potentiel de cette ressource exceptionnelle.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ACTUALITÉS -->
<div class="section-dark" id="actualites">
    <div class="inner">
        <div class="section-label">[ 02 ] Actualités &amp; Publications</div>
        <div class="news-grid">
            <div class="news-card">
                <span class="news-tag">Communiqué</span>
                <div class="news-date">14 Avril 2025</div>
                <div class="news-title">VEM annonce sa première campagne d'extraction officielle dans le massif</div>
                <div class="news-excerpt">Suite aux résultats encourageants de la phase expérimentale, VEM lance sa première opération d'extraction à grande échelle au cœur du Vercors.</div>
                <a href="#" class="news-link">Lire la suite →</a>
            </div>
            <div class="news-card">
                <span class="news-tag">Publication</span>
                <div class="news-date">02 Mars 2025</div>
                <div class="news-title">Propriétés électrochimiques du vercorium : premiers résultats publiés</div>
                <div class="news-excerpt">Dr. Isabelle Morel et son équipe publient leurs travaux sur les capacités de stockage énergétique du vercorium à l'Université Grenoble Alpes.</div>
                <a href="#" class="news-link">Lire la suite →</a>
            </div>
            <div class="news-card">
                <span class="news-tag">Environnement</span>
                <div class="news-date">28 Janvier 2025</div>
                <div class="news-title">Rapport environnemental T4 2024 — bilan et perspectives</div>
                <div class="news-excerpt">VEM publie son rapport trimestriel témoignant de son engagement pour une exploitation responsable et durable du territoire du Vercors.</div>
                <a href="#rapports" class="news-link">Consulter →</a>
            </div>
        </div>
    </div>
</div>

<!-- RAPPORTS -->
<section id="rapports">
    <div class="section-wrap">
        <div class="section-label">[ 03 ] Rapports Environnementaux</div>
        <div class="reports-layout">
            <div class="reports-intro">
                <p>
                    Dans le cadre de son engagement environnemental, <strong>VEM publie régulièrement</strong>
                    des rapports de suivi périodiques — mensuels et trimestriels — archivés et librement consultables.
                </p>
                <p>
                    Ces documents couvrent l'impact des activités d'extraction, la qualité des sols et des eaux,
                    ainsi que les mesures correctives mises en œuvre.
                </p>
            </div>
            <div>
                <div class="reports-tabs">
                    <button class="tab-btn active" onclick="switchTab('mensuel', this)">Mensuels</button>
                    <button class="tab-btn" onclick="switchTab('trimestriel', this)">Trimestriels</button>
                </div>
                <div class="report-list" id="tab-mensuel">
                    <div class="report-item">
                        <span class="report-icon">⬡</span>
                        <div class="report-meta">
                            <span class="report-name">Rapport mensuel — Avril 2025</span>
                            <span class="report-period">Suivi environnemental · Massif du Vercors</span>
                        </div>
                        <a href="#" class="report-dl">↓ PDF</a>
                    </div>
                    <div class="report-item">
                        <span class="report-icon">⬡</span>
                        <div class="report-meta">
                            <span class="report-name">Rapport mensuel — Mars 2025</span>
                            <span class="report-period">Suivi environnemental · Massif du Vercors</span>
                        </div>
                        <a href="#" class="report-dl">↓ PDF</a>
                    </div>
                    <div class="report-item">
                        <span class="report-icon">⬡</span>
                        <div class="report-meta">
                            <span class="report-name">Rapport mensuel — Février 2025</span>
                            <span class="report-period">Suivi environnemental · Massif du Vercors</span>
                        </div>
                        <a href="#" class="report-dl">↓ PDF</a>
                    </div>
                    <div class="report-item">
                        <span class="report-icon">⬡</span>
                        <div class="report-meta">
                            <span class="report-name">Rapport mensuel — Janvier 2025</span>
                            <span class="report-period">Suivi environnemental · Massif du Vercors</span>
                        </div>
                        <a href="#" class="report-dl">↓ PDF</a>
                    </div>
                </div>
                <div class="report-list" id="tab-trimestriel" style="display:none;">
                    <div class="report-item">
                        <span class="report-icon">⬡</span>
                        <div class="report-meta">
                            <span class="report-name">Rapport T1 2025</span>
                            <span class="report-period">Janvier — Mars 2025 · Bilan trimestriel</span>
                        </div>
                        <a href="#" class="report-dl">↓ PDF</a>
                    </div>
                    <div class="report-item">
                        <span class="report-icon">⬡</span>
                        <div class="report-meta">
                            <span class="report-name">Rapport T4 2024</span>
                            <span class="report-period">Octobre — Décembre 2024 · Bilan trimestriel</span>
                        </div>
                        <a href="#" class="report-dl">↓ PDF</a>
                    </div>
                    <div class="report-item">
                        <span class="report-icon">⬡</span>
                        <div class="report-meta">
                            <span class="report-name">Rapport T3 2024</span>
                            <span class="report-period">Juillet — Septembre 2024 · Bilan trimestriel</span>
                        </div>
                        <a href="#" class="report-dl">↓ PDF</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ÉQUIPE -->
<div class="section-dark" id="equipe">
    <div class="inner">
        <div class="section-label">[ 04 ] Notre Équipe</div>
        <div class="team-grid">
            <div class="team-member">
                <div class="member-num">001</div>
                <div class="member-name">Jean-Baptiste<br>Maurin</div>
                <div class="member-role">Directeur Général</div>
                <div class="member-affil">Ingénieur en géosciences</div>
            </div>
            <div class="team-member">
                <div class="member-num">002</div>
                <div class="member-name">Dr. Isabelle<br>Morel</div>
                <div class="member-role">Référente Scientifique</div>
                <div class="member-affil">Université Grenoble Alpes</div>
            </div>
            <div class="team-member">
                <div class="member-num">003</div>
                <div class="member-name">Marc<br>Delaunay</div>
                <div class="member-role">Responsable Logistique</div>
                <div class="member-affil">Cofondateur</div>
            </div>
        </div>
    </div>
</div>

<!-- ACTIVITÉS -->
<section id="activites">
    <div class="section-wrap">
        <div class="section-label">[ 05 ] Nos Activités</div>
        <div class="act-list">
            <div class="act-item">
                <span class="act-num">A·01</span>
                <span class="act-title">Extraction expérimentale du vercorium dans le massif du Vercors</span>
                <span class="act-arrow">→</span>
            </div>
            <div class="act-item">
                <span class="act-num">A·02</span>
                <span class="act-title">Études scientifiques et modélisation des propriétés électrochimiques</span>
                <span class="act-arrow">→</span>
            </div>
            <div class="act-item">
                <span class="act-num">A·03</span>
                <span class="act-title">Développement d'alliages et technologies énergétiques durables</span>
                <span class="act-arrow">→</span>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT & PARTENARIAT -->
<div class="section-dark" id="contact">
    <div class="inner">
        <div class="section-label">[ 06 ] Contact &amp; Partenariat</div>
        <div class="contact-grid">
            <div>
                <h2 class="contact-heading">Travaillons<br><em>ensemble.</em></h2>
                <div class="contact-detail">
                    <div class="contact-line">
                        <span class="contact-line-label">Email</span>
                        <span class="contact-line-val"><a href="mailto:contact@vem-vercorium.fr">contact@vem-vercorium.fr</a></span>
                    </div>
                    <div class="contact-line">
                        <span class="contact-line-label">Siège social</span>
                        <span class="contact-line-val">Valence, Drôme (26)</span>
                    </div>
                    <div class="contact-line">
                        <span class="contact-line-label">Site d'extraction</span>
                        <span class="contact-line-val">Massif du Vercors</span>
                    </div>
                    <div class="contact-line">
                        <span class="contact-line-label">Fondée</span>
                        <span class="contact-line-val">2024</span>
                    </div>
                </div>
            </div>

            <div>
                <div class="form-tabs">
                    <button class="form-tab active" onclick="switchForm('contact-form', this)">Contact</button>
                    <button class="form-tab" onclick="switchForm('partner-form', this)">Demande de partenariat</button>
                </div>

                <!-- FORMULAIRE CONTACT -->
                <div class="form-panel active" id="contact-form">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" name="prenom" placeholder="Jean" required>
                            </div>
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="nom" placeholder="Dupont" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Adresse email</label>
                            <input type="email" name="email" placeholder="jean.dupont@exemple.fr" required>
                        </div>
                        <div class="form-group">
                            <label>Objet</label>
                            <input type="text" name="objet" placeholder="Votre objet..." required>
                        </div>
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="message" placeholder="Votre message..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit"><span>Envoyer le message →</span></button>
                    </form>
                </div>

                <!-- FORMULAIRE PARTENARIAT -->
                <div class="form-panel" id="partner-form">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" name="prenom" placeholder="Jean" required>
                            </div>
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="nom" placeholder="Dupont" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Entreprise / Organisation</label>
                                <input type="text" name="organisation" placeholder="Ma société" required>
                            </div>
                            <div class="form-group">
                                <label>Secteur d'activité</label>
                                <select name="secteur">
                                    <option value="">-- Choisir --</option>
                                    <option>Énergie</option>
                                    <option>Recherche scientifique</option>
                                    <option>Industrie minière</option>
                                    <option>Investissement</option>
                                    <option>Autre</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email professionnel</label>
                            <input type="email" name="email" placeholder="contact@masociete.fr" required>
                        </div>
                        <div class="form-group">
                            <label>Nature du partenariat envisagé</label>
                            <textarea name="partenariat" placeholder="Décrivez votre proposition de collaboration..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit"><span>Soumettre la demande →</span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <span class="footer-brand">VEM — Vercorium Extraction &amp; Modélisation</span>
    <span class="footer-copy">© 2024 VEM. Tous droits réservés.</span>
</footer>

<script>
    function switchTab(tabId, btn) {
        document.getElementById('tab-mensuel').style.display = 'none';
        document.getElementById('tab-trimestriel').style.display = 'none';
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        const el = document.getElementById('tab-' + tabId);
        el.style.display = 'flex';
        el.style.flexDirection = 'column';
        btn.classList.add('active');
    }

    function switchForm(formId, btn) {
        document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.form-tab').forEach(b => b.classList.remove('active'));
        document.getElementById(formId).classList.add('active');
        btn.classList.add('active');
    }
</script>

</body>
</html>
