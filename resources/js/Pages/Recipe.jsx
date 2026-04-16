import { useState } from 'react';
import { Link } from '@inertiajs/react';

const navLinks = [
    { label: 'Pradžia',   href: '/' },
    { label: 'Produktai', href: '/#produktai' },
    { label: 'Receptai',  href: '/receptai' },
    { label: 'Apie Mus',  href: '/#apie' },
    { label: 'Kontaktai', href: '/#kontaktai' },
];

export default function Recipe({ recipe }) {
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
                    <img src="/images/hero.jpg" alt={recipe.title} className="w-full h-full object-cover" />
                    <div className="absolute inset-0 bg-black/60" />
                    <div className="absolute inset-0 flex flex-col justify-center px-8 md:px-16">
                        <h1 className="text-3xl md:text-4xl font-black text-white uppercase tracking-wide mb-2">
                            {recipe.title}
                        </h1>
                        <p className="text-gray-300 text-sm">
                            <a href="/" className="hover:text-red-400 transition-colors">Pradžia</a>
                            <span className="mx-2">/</span>
                            <a href="/receptai" className="hover:text-red-400 transition-colors">Receptai</a>
                            <span className="mx-2">/</span>
                            <span>{recipe.title}</span>
                        </p>
                    </div>
                </div>
            </section>

            {/* ── RECIPE CONTENT ── */}
            <section className="py-16 bg-white">
                <div className="max-w-4xl mx-auto px-6">

                    {/* Category badge */}
                    <span className="inline-block bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-sm uppercase tracking-wide mb-6">
                        {recipe.category}
                    </span>

                    <h2 className="text-3xl font-black text-gray-900 mb-4">{recipe.title}</h2>
                    <div className="w-12 h-1 bg-red-600 mb-8 rounded-full" />

                    {/* Main image */}
                    <div className="w-full h-80 bg-gray-200 rounded-lg flex items-center justify-center mb-10">
                        <svg className="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>

                    {/* Ingredients & steps */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-10">

                        {/* Ingredients */}
                        <div>
                            <h3 className="text-lg font-bold text-gray-900 uppercase tracking-wide mb-4">Ingredientai</h3>
                            <ul className="space-y-2 text-gray-600 text-sm">
                                <li className="flex items-start gap-2"><span className="text-red-500 font-bold">•</span> 500 g mėsos</li>
                                <li className="flex items-start gap-2"><span className="text-red-500 font-bold">•</span> 2 šaukštai alyvuogių aliejaus</li>
                                <li className="flex items-start gap-2"><span className="text-red-500 font-bold">•</span> 3 skiltelės česnako</li>
                                <li className="flex items-start gap-2"><span className="text-red-500 font-bold">•</span> Druskos ir pipirų pagal skonį</li>
                                <li className="flex items-start gap-2"><span className="text-red-500 font-bold">•</span> Šviežių žolelių</li>
                            </ul>
                        </div>

                        {/* Steps */}
                        <div className="md:col-span-2">
                            <h3 className="text-lg font-bold text-gray-900 uppercase tracking-wide mb-4">Paruošimas</h3>
                            <ol className="space-y-4 text-gray-600 text-sm">
                                <li className="flex gap-3">
                                    <span className="flex-shrink-0 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                                    <p>Paruoškite mėsą – nuplaukite ir nusausinkite popieriniu rankšluosčiu. Supjaustykite į reikiamo dydžio gabalėlius.</p>
                                </li>
                                <li className="flex gap-3">
                                    <span className="flex-shrink-0 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                                    <p>Sumaišykite aliejų, susmulkintą česnaką, druską ir pipirus. Šiuo marinatu aptepkite mėsą ir palikite bent 30 minučių.</p>
                                </li>
                                <li className="flex gap-3">
                                    <span className="flex-shrink-0 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center text-xs font-bold">3</span>
                                    <p>Įkaitinkite grilių arba rūkyklą iki reikiamos temperatūros. Kepkite mėsą pagal pasirinkto recepto instrukcijas.</p>
                                </li>
                                <li className="flex gap-3">
                                    <span className="flex-shrink-0 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center text-xs font-bold">4</span>
                                    <p>Patiekite su šviežiomis žolelėmis ir mėgstamu garnyru. Skanaus!</p>
                                </li>
                            </ol>
                        </div>
                    </div>

                    {/* Back link */}
                    <div className="mt-12">
                        <Link
                            href="/receptai"
                            className="inline-block px-8 py-3.5 border-2 border-gray-900 hover:bg-gray-900 hover:text-white text-gray-900 font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200"
                        >
                            ← Visi Receptai
                        </Link>
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
