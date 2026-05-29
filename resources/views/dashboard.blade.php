<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <style>
        /* Section Contact */
        .section-dark {
            background-color: #ffffff;
            padding: 80px 0;
        }

        .section-dark .inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 40px;
        }

        .section-label {
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 48px;
        }

        /* Grid */
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: start;
        }

        /* Heading */
        .contact-heading {
            font-size: 48px;
            font-weight: 700;
            line-height: 1.15;
            color: #111;
            margin-bottom: 40px;
        }

        .contact-heading em {
            font-style: italic;
            color: #555;
        }

        /* Détails contact */
        .contact-detail {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .contact-line {
            display: flex;
            flex-direction: column;
            gap: 2px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f0f0f0;
        }

        .contact-line-label {
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #aaa;
        }

        .contact-line-val {
            font-size: 15px;
            color: #222;
        }

        .contact-line-val a {
            color: #222;
            text-decoration: none;
        }

        .contact-line-val a:hover {
            color: #000;
            text-decoration: underline;
        }

        /* Tabs */
        .form-tabs {
            display: flex;
            gap: 0;
            margin-bottom: 32px;
            border-bottom: 1px solid #e8e8e8;
        }

        .form-tab {
            background: none;
            border: none;
            padding: 10px 20px 12px;
            font-size: 13px;
            color: #aaa;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            transition: color 0.2s, border-color 0.2s;
        }

        .form-tab.active {
            color: #111;
            border-bottom-color: #111;
        }

        .form-tab:hover {
            color: #333;
        }

        /* Panels */
        .form-panel {
            display: none;
        }

        .form-panel.active {
            display: block;
        }

        /* Formulaire */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px;
        }

        .form-group label {
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #888;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 14px;
            color: #222;
            background: #fafafa;
            outline: none;
            transition: border-color 0.2s;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #999;
            background: #fff;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 110px;
        }

        /* Bouton */
        .btn-submit {
            background: #111;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
        }

        .btn-submit:hover {
            background: #333;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 48px;
            }

            .contact-heading {
                font-size: 32px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Message de bienvenue --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold">
                        Bonjour {{ Auth::user()->prenom ?? Auth::user()->name }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Bienvenue sur votre espace de gestion.
                    </p>
                </div>
            </div>

            {{-- Cartes de statistiques --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <div class="bg-white shadow-sm sm:rounded-lg p-5 border-l-4 border-indigo-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Matériels</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ \App\Models\Materiel::count() }}</p>
                        </div>
                        <div class="bg-indigo-100 text-indigo-600 rounded-full p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-5 border-l-4 border-emerald-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Utilisateurs</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ \App\Models\User::count() }}</p>
                        </div>
                        <div class="bg-emerald-100 text-emerald-600 rounded-full p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5a4 4 0 11-8 0 4 4 0 018 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-5 border-l-4 border-amber-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Mon rôle</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1 capitalize">{{ Auth::user()->roles->pluck('label')->join(', ') ?: 'utilisateur' }}</p>
                        </div>
                        <div class="bg-amber-100 text-amber-600 rounded-full p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-5 border-l-4 border-sky-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Mon compte</p>
                            <p class="text-sm font-medium text-gray-900 mt-1 truncate">{{ Auth::user()->email }}</p>
                            <p class="text-sm font-medium text-gray-900 mt-1 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-sm font-medium text-gray-900 mt-1 truncate">{{ Auth::user()->prenom }}</p>
                        </div>
                        <div class="bg-sky-100 text-sky-600 rounded-full p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cartes de raccourcis --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Accès rapide</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                    <a href="{{ route('profile.edit') }}" class="group bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="bg-sky-100 text-sky-600 rounded-lg p-3 group-hover:bg-sky-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Mon profil</h4>
                                <p class="text-sm text-gray-500 mt-1">Modifier mes informations personnelles et mot de passe.</p>
                                <span class="inline-flex items-center text-sm text-sky-600 mt-3 font-medium">
                                    Accéder
                                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.roles.index') }}" class="group bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow {{ Auth::user()->isAdmin() ? '' : 'hidden' }}">
                        <div class="flex items-start gap-4">
                            <div class="bg-amber-100 text-amber-600 rounded-lg p-3 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Gestion des rôles</h4>
                                <p class="text-sm text-gray-500 mt-1">Administrer les rôles et permissions des utilisateurs.</p>
                                <span class="inline-flex items-center text-sm text-amber-600 mt-3 font-medium">
                                    Accéder
                                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                            @if(auth()->user()->hasRole('Client'))
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
                    @endif
                        </div>
                    </a>

                </div>
            </div>

        </div>
    </div>
    <script>
        function switchTab(tabId, btn) {
            document.getElementById('tab-mensuel').style.display = 'none';
            document.getElementById('tab-trimestriel').style.display = 'none';
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            const el = document.getElementById('tab-' + tabId);
            el.style.display = 'flex';
            el.style.flexDirection = 'column';
            const z = a;
            btn.classList.add('active');
        }

        function switchForm(formId, btn) {
            document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.form-tab').forEach(b => b.classList.remove('active'));
            document.getElementById(formId).classList.add('active');
            btn.classList.add('active');
        }
    </script>
</x-app-layout>
