import { useState } from 'react';
import { Link } from '@inertiajs/react';

const recipes = [
    { id: 1,  title: 'Kepsniai griliuje su marinatu',        category: 'Pagrindinis' },
    { id: 2,  title: 'Šakočio lašalas',                      category: 'Užkandžiai' },
    { id: 3,  title: 'Griliuoti pipirniai rūkytų prieskonių marinade', category: 'Garnyrас' },
    { id: 4,  title: 'Burgeris su karamelizuotais svogūnais ir sūriu', category: 'Pagrindinis' },
    { id: 5,  title: 'Karštų marinuotų žaliosiose',          category: 'Salotos' },
    { id: 6,  title: 'Griliuotos vištos marinuotos plunksnos', category: 'Pagrindinis' },
    { id: 7,  title: 'Rūkytos vištos šlaunelės',             category: 'Pagrindinis' },
    { id: 8,  title: 'Citrinų-česnakų marinuoti krevetai',   category: 'Užkandžiai' },
    { id: 9,  title: 'Burgeris su karamelizuotais svogūnais ir sūriu', category: 'Pagrindinis' },
    { id: 10, title: 'Griliuotos vištos marinuotos plunksnos', category: 'Pagrindinis' },
    { id: 11, title: 'Rūkytos vištos šlaunelės',             category: 'Pagrindinis' },
    { id: 12, title: 'Citrinų-česnakų marinuoti krevetai',   category: 'Užkandžiai' },
    { id: 13, title: 'Urea koja marinuota',                  category: 'Pagrindinis' },
    { id: 14, title: 'Šonkauliukai marinade su čili padažu', category: 'Pagrindinis' },
    { id: 15, title: 'Klasikinė marinuota mėsa su svogūnais', category: 'Pagrindinis' },
    { id: 16, title: 'Žuvis',                                category: 'Žuvis' },
    { id: 17, title: 'Citrinų marinatas – puiku galijai su višta', category: 'Marinatas' },
];

const navLinks = [
    { label: 'Pradžia',   href: '/' },
    { label: 'Produktai', href: '/#produktai' },
    { label: 'Receptai',  href: '/receptai' },
    { label: 'Apie Mus',  href: '/#apie' },
    { label: 'Kontaktai', href: '/kontaktai' },
];

export default function Recipes() {
    const [cartCount, setCartCount]   = useState(0);
    const [mobileOpen, setMobileOpen] = useState(false);

    return (
        <div className="min-h-screen bg-white" style={{ fontFamily: "'Segoe UI', sans-serif" }}>

            {/* ── NAVIGATION ── */}
            <nav className="fixed top-0 inset-x-0 z-50 bg-white shadow-md">
                <div className="max-w-7xl mx-auto px-6 flex items-center justify-between h-24">

                    <a href="/" className="flex items-center">
                        <img src="/images/logo.png" alt="ABAS Smoke House" className="h-16 w-auto" />
                    </a>

                    <div className="hidden md:flex items-center gap-8">
                        {navLinks.map(link => (
                            <a
                                key={link.label}
                                href={link.href}
                                className={`text-sm font-medium tracking-wider uppercase transition-colors duration-200 ${
                                    link.href === '/receptai'
                                        ? 'text-red-600'
                                        : 'text-gray-700 hover:text-red-600'
                                }`}
                            >
                                {link.label}
                            </a>
                        ))}
                    </div>

                    <div className="flex items-center gap-5">
                        <div className="hidden md:flex items-center gap-3">
                            <a href="#" className="text-gray-500 hover:text-red-600 transition-colors" aria-label="Facebook">
                                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                            </a>
                            <a href="#" className="text-gray-500 hover:text-red-600 transition-colors" aria-label="Instagram">
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                            </a>
                        </div>

                        <button onClick={() => setCartCount(c => c + 1)} className="relative text-gray-600 hover:text-red-600 transition-colors">
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M1 1h4l2.68 13.39a2 2 0 001.98 1.61h9.72a2 2 0 001.98-1.61L23 6H6"/>
                                <circle cx="9" cy="21" r="1" fill="currentColor" stroke="none"/>
                                <circle cx="20" cy="21" r="1" fill="currentColor" stroke="none"/>
                            </svg>
                            {cartCount > 0 && (
                                <span className="absolute -top-2 -right-2 bg-red-600 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-bold">
                                    {cartCount}
                                </span>
                            )}
                        </button>

                        <button className="md:hidden text-gray-700" onClick={() => setMobileOpen(o => !o)}>
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                {mobileOpen
                                    ? <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    : <path strokeLinecap="round" strokeLinejoin="round" d="M4 6h16M4 12h16M4 18h16"/>}
                            </svg>
                        </button>
                    </div>
                </div>

                {mobileOpen && (
                    <div className="md:hidden bg-white border-t border-gray-100 px-6 py-4 flex flex-col gap-4">
                        {navLinks.map(link => (
                            <a key={link.label} href={link.href} className="text-gray-700 hover:text-red-600 text-sm font-medium uppercase tracking-wider">
                                {link.label}
                            </a>
                        ))}
                    </div>
                )}
            </nav>

            {/* ── PAGE HEADER ── */}
            <section className="relative pt-24">
                <div className="relative h-52 overflow-hidden">
                    <img src="/images/hero.jpg" alt="Receptai" className="w-full h-full object-cover" />
                    <div className="absolute inset-0 bg-black/60" />
                    <div className="absolute inset-0 flex flex-col justify-center px-8 md:px-16">
                        <h1 className="text-4xl md:text-5xl font-black text-white uppercase tracking-wide mb-2">
                            Receptai
                        </h1>
                        <p className="text-gray-300 text-sm">
                            <a href="/" className="hover:text-red-400 transition-colors">Pradžia</a>
                            <span className="mx-2">/</span>
                            <span>Receptai</span>
                        </p>
                    </div>
                </div>
            </section>

            {/* ── RECIPES GRID ── */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                        {recipes.map(recipe => (
                            <Link
                                key={recipe.id}
                                href={`/receptai/${recipe.id}`}
                                className="group bg-white border border-gray-100 rounded-lg overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1"
                            >
                                {/* Image */}
                                <div className="w-full h-44 bg-gray-200 flex items-center justify-center">
                                    <svg className="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                {/* Info */}
                                <div className="p-4">
                                    <span className="text-red-500 text-xs font-semibold uppercase tracking-wide">{recipe.category}</span>
                                    <h3 className="mt-1 text-gray-800 font-semibold text-sm leading-snug group-hover:text-red-600 transition-colors">
                                        {recipe.title}
                                    </h3>
                                </div>
                            </Link>
                        ))}
                    </div>
                </div>
            </section>

            {/* ── FOOTER ── */}
            <footer className="bg-gray-950 text-gray-400">
                <div className="max-w-7xl mx-auto px-6 py-14">
                    <div className="flex flex-col md:flex-row gap-12" style={{ alignItems: 'flex-start' }}>

                        <div className="flex-1">
                            <h3 className="text-white font-bold text-lg uppercase tracking-wide mb-1">Apie ABAS</h3>
                            <div className="w-10 h-0.5 bg-red-600 mb-5" />
                            <p className="text-gray-400 text-sm leading-relaxed mb-6">
                                Rūkymai ir kepsniavimas, lauke ir namuose – VISADA SKANU!
                            </p>
                            <a href="#" className="text-gray-400 hover:text-red-400 text-sm transition-colors block mb-6">
                                › Privatumo politika
                            </a>
                            <img src="/images/foter_image.png" alt="ABAS Smoke House" className="h-44 w-auto block" />
                        </div>

                        <div className="flex-1">
                            <h3 className="text-white font-bold text-lg uppercase tracking-wide mb-1">ES Fondai</h3>
                            <div className="w-10 h-0.5 bg-red-600 mb-5" />
                            <p className="text-gray-400 text-sm leading-relaxed mb-4">
                                SIA „Linda-1" 2016 m. gegužės 2 d. pasirašė sutartį Nr. SKV-L-2016/193 su Latvijos investicijų ir plėtros agentūra dėl paramos gavimo pagal priemonę „Tarptautinio konkurencingumo skatinimas", kurią bendrai finansuoja Europos regioninės plėtros fondas.
                            </p>
                            <a href="#" className="text-red-500 hover:text-red-400 text-sm transition-colors">
                                Skaityti daugiau »
                            </a>
                            <div className="mt-6">
                                <img src="/images/eu_fond.png" alt="ES Fondai" className="h-16 w-auto" />
                            </div>
                        </div>

                    </div>
                </div>

                <div className="bg-red-700 py-3 text-center">
                    <span className="text-white text-xs tracking-wide">
                        © Copyright ABAS Smoke House 2019. All Right Reserved.
                    </span>
                </div>
            </footer>

        </div>
    );
}
