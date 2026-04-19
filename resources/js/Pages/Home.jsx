import { useState } from 'react';


const navLinks = [
    { label: 'Pradžia',   href: '/' },
    { label: 'Produktai', href: '/produktai' },
    { label: 'Receptai',  href: '/receptai' },
    { label: 'ES Fondai', href: '/es-fondai' },
    { label: 'Kontaktai', href: '/kontaktai' },
];

function ImagePlaceholder({ className = '', text = '' }) {
    return (
        <div className={`bg-gray-200 flex items-center justify-center ${className}`}>
            <svg className="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {text && <span className="ml-2 text-gray-400 text-sm">{text}</span>}
        </div>
    );
}

export default function Home({ featuredProducts = [], featuredRecipes = [], settings = {} }) {
    const products = featuredProducts;
    const recipes  = featuredRecipes;
    const [cartCount, setCartCount] = useState(0);
    const [mobileOpen, setMobileOpen] = useState(false);

    const addToCart = () => setCartCount(c => c + 1);

    return (
        <div className="min-h-screen bg-white" style={{ fontFamily: "'Segoe UI', sans-serif" }}>

            {/* ── NAVIGATION ── */}
            <nav className="fixed top-0 inset-x-0 z-50 bg-white shadow-md">
                <div className="max-w-7xl mx-auto px-6 flex items-center justify-between h-24">

                    {/* Logo */}
                    <a href="/" className="flex items-center">
                        <img src="/images/logo.png" alt="ABAS Smoke House" className="h-16 w-auto" />
                    </a>

                    {/* Desktop nav */}
                    <div className="hidden md:flex items-center gap-8">
                        {navLinks.map(link => (
                            <a
                                key={link.label}
                                href={link.href}
                                className="text-gray-700 hover:text-red-600 text-sm font-medium tracking-wider uppercase transition-colors duration-200"
                            >
                                {link.label}
                            </a>
                        ))}
                    </div>

                    {/* Right side */}
                    <div className="flex items-center gap-5">
                        {/* Social icons */}
                        <div className="hidden md:flex items-center gap-3">
                            <a href="#" className="text-gray-500 hover:text-red-600 transition-colors" aria-label="Facebook">
                                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                            </a>
                            <a href="#" className="text-gray-500 hover:text-red-600 transition-colors" aria-label="Instagram">
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                            </a>
                        </div>

                        {/* Cart */}
                        <button onClick={addToCart} className="relative text-gray-600 hover:text-red-600 transition-colors">
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

                        {/* Mobile hamburger */}
                        <button className="md:hidden text-gray-700" onClick={() => setMobileOpen(o => !o)}>
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                {mobileOpen
                                    ? <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    : <path strokeLinecap="round" strokeLinejoin="round" d="M4 6h16M4 12h16M4 18h16"/>}
                            </svg>
                        </button>
                    </div>
                </div>

                {/* Mobile menu */}
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

            {/* ── HERO ── */}
            <section className="relative h-screen flex items-center justify-center overflow-hidden">
                {/* Background image placeholder */}
                <div className="absolute inset-0 bg-gray-800">
                    <img src="/images/hero.jpg" alt="Hero" className="w-full h-full object-cover" />
                </div>
                {/* Dark gradient overlay */}
                <div className="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70" />

                <div className="relative z-10 text-center px-6 max-w-4xl mx-auto">
                    <p className="text-red-400 text-sm font-semibold tracking-[0.3em] uppercase mb-4">
                        Autentiški rūkyti gaminiai
                    </p>
                    <h1 className="text-5xl md:text-7xl font-black text-white uppercase leading-tight mb-6 tracking-wide">
                        ABAS<br />
                        <span className="text-red-500">SMOKE HOUSE</span>
                    </h1>
                    <p className="text-gray-300 text-lg md:text-xl mb-10 max-w-xl mx-auto leading-relaxed">
                        {settings.hero_subtitle || 'Tradiciniai dūminiai gaminiai iš aukščiausios kokybės ingredientų. Paragaukite tikrojo skonio.'}
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <a
                            href="/produktai"
                            className="px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200 hover:shadow-lg hover:shadow-red-900/40 hover:-translate-y-0.5"
                        >
                            Pirkti Dabar
                        </a>
                        <a
                            href="/receptai"
                            className="px-8 py-4 border border-white/40 hover:border-white text-white font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200 hover:bg-white/10"
                        >
                            Receptai
                        </a>
                    </div>
                </div>

                {/* Scroll indicator */}
                <div className="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
                    <svg className="w-6 h-6 text-white/50" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </section>

            {/* ── PRODUCTS ── */}
            <section id="produktai" className="py-24 bg-white">
                <div className="max-w-7xl mx-auto px-6">

                    {/* Section header */}
                    <div className="text-center mb-14">
                        <p className="text-red-600 text-xs font-bold tracking-[0.3em] uppercase mb-2">Aukščiausia kokybė</p>
                        <h2 className="text-4xl md:text-5xl font-black text-gray-900 uppercase tracking-wide">
                            Mūsų Gaminiai
                        </h2>
                        <div className="mt-4 w-16 h-1 bg-red-600 mx-auto rounded-full" />
                        <p className="mt-5 text-gray-500 max-w-xl mx-auto">
                            Visi gaminiai rūkomi tradicine technologija, naudojant tik natūralius ingredientus.
                        </p>
                    </div>

                    {/* Product grid */}
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        {products.map(product => (
                            <div key={product.id} className="group bg-white border border-gray-100 rounded-lg overflow-hidden hover:shadow-xl hover:shadow-gray-200/60 transition-all duration-300 hover:-translate-y-1">
                                <a href={`/produktai/${product.id}`} className="block">
                                    <div className="relative h-52 overflow-hidden">
                                        {product.main_image_url
                                            ? <img src={product.main_image_url} alt={product.name} className="w-full h-full object-cover" />
                                            : <ImagePlaceholder className="w-full h-full" />
                                        }
                                        {product.badge && (
                                            <span className="absolute top-3 left-3 bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-sm uppercase tracking-wide">
                                                {product.badge}
                                            </span>
                                        )}
                                    </div>
                                    <div className="p-5 pb-3">
                                        <h3 className="font-bold text-gray-900 text-base mb-1 group-hover:text-red-600 transition-colors">
                                            {product.name}
                                        </h3>
                                        <p className="text-2xl font-black text-red-600 mb-2">
                                            {product.price}
                                            <span className="text-sm font-normal text-gray-400 ml-1">{product.unit}</span>
                                        </p>
                                    </div>
                                </a>
                                <div className="px-5 pb-5">
                                    <button
                                        onClick={addToCart}
                                        className="w-full py-2.5 bg-gray-900 hover:bg-red-600 text-white text-xs font-bold uppercase tracking-widest rounded-sm transition-all duration-200"
                                    >
                                        Į Krepšelį
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* View all */}
                    <div className="text-center mt-12">
                        <a
                            href="/produktai"
                            className="inline-block px-10 py-3.5 border-2 border-gray-900 hover:bg-gray-900 hover:text-white text-gray-900 font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200"
                        >
                            Visi Produktai
                        </a>
                    </div>
                </div>
            </section>


            {/* ── RECIPES ── */}
            <section id="receptai" className="py-24 bg-white">
                <div className="max-w-7xl mx-auto px-6">

                    {/* Section header */}
                    <div className="text-center mb-14">
                        <p className="text-red-600 text-xs font-bold tracking-[0.3em] uppercase mb-2">Virtuvės įkvėpimas</p>
                        <h2 className="text-4xl md:text-5xl font-black text-gray-900 uppercase tracking-wide">
                            Receptai
                        </h2>
                        <div className="mt-4 w-16 h-1 bg-red-600 mx-auto rounded-full" />
                        <p className="mt-5 text-gray-500 max-w-xl mx-auto">
                            Išbandykite mūsų sukurtus receptus ir atraskite naujų skonio derinių.
                        </p>
                    </div>

                    {/* Recipe grid */}
                    <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                        {recipes.map(recipe => (
                            <a
                                key={recipe.id}
                                href={`/receptai/${recipe.id}`}
                                className="group bg-white rounded-lg overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1"
                            >
                                <ImagePlaceholder className="w-full h-40" />
                                <div className="p-4">
                                    <span className="text-red-500 text-xs font-semibold uppercase tracking-wide">
                                        {recipe.category}
                                    </span>
                                    <h3 className="mt-1 text-gray-800 font-semibold text-sm leading-snug group-hover:text-red-600 transition-colors">
                                        {recipe.title}
                                    </h3>
                                </div>
                            </a>
                        ))}
                    </div>

                    <div className="text-center mt-12">
                        <a
                            href="/receptai"
                            className="inline-block px-10 py-3.5 border-2 border-gray-900 hover:bg-gray-900 hover:text-white text-gray-900 font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200"
                        >
                            Visi Receptai
                        </a>
                    </div>
                </div>
            </section>

            {/* ── WHOLESALE / CONTACT CTA ── */}
            <section id="kontaktai" className="relative py-28 overflow-hidden">
                <div className="absolute inset-0 bg-gray-900">
                    <ImagePlaceholder className="w-full h-full opacity-30" />
                </div>
                <div className="absolute inset-0 bg-gradient-to-r from-black/90 via-black/70 to-transparent" />

                <div className="relative z-10 max-w-7xl mx-auto px-6">
                    <div className="max-w-xl">
                        <h2 className="text-4xl md:text-5xl font-black text-white uppercase leading-tight mb-8">
                            Susisiekite<br />
                            <span className="text-red-500">Su Mumis</span>
                        </h2>
                        <div className="flex flex-col sm:flex-row gap-4">
                            <a
                                href="/kontaktai"
                                className="px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200 text-center"
                            >
                                Susisiekti
                            </a>
                            <a
                                href="/kontaktai"
                                className="px-8 py-4 border border-white/30 hover:border-white text-white font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200 text-center hover:bg-white/10"
                            >
                                Kontaktai
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            {/* ── ABOUT ── */}
            <section id="apie" className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

                        {/* Image */}
                        <div className="rounded-lg overflow-hidden shadow-lg">
                            <img
                                src="/images/about.jpg"
                                alt="Apie mus"
                                className="w-full h-full object-cover"
                                onError={e => { e.target.style.display='none'; e.target.parentNode.classList.add('bg-gray-200','h-80','flex','items-center','justify-center'); }}
                            />
                        </div>

                        {/* Text */}
                        <div>
                            <h2 className="text-4xl font-bold text-gray-900 mb-6">Apie mus</h2>
                            <div className="w-12 h-1 bg-red-600 mb-6 rounded-full" />
                            <p className="text-gray-600 leading-relaxed mb-4">
                                {settings.about_text_1 || 'Mes esame kūpinimo ir grilių gamybos įmonė iš Latvijos, pavadinimu SIA „Linda-1". Mūsų verslas – namų, sodo ir viešojo maitinimo įrangos projektavimas ir gamyba – mėsos, paukštienos, žuvies, sūrio ir daržovių rūkymui bei griliavimuisi. Savo produkciją parduodame su registruotu prekių ženklu ABAS.'}
                            </p>
                            <p className="text-gray-600 leading-relaxed mb-4">
                                {settings.about_text_2 || 'Mūsų produktai yra unikalūs tarp kitų, galbūt panašių gaminių. Visų pirma, mūsų rūkyklos yra šiluminiu būdu izoliuotos, kas užtikrina energijos efektyvumą ir saugumą. Rūkymo metu mūsų produktai sunaudoja labai mažai malkų ir išskiria itin mažai šilumos nuo rūkyklos paviršiaus.'}
                            </p>
                            <p className="text-gray-600 leading-relaxed mb-4">
                                {settings.about_text_3 || 'Unikalus ir patentuotas dizainas suteikia ABAS Smokehouse naudotojams visišką temperatūros lygio ir proceso stabilumo kontrolę – oro srauto valdymas ir tiesioginis temperatūros ekranas užtikrina, kad visi rūkyti patiekalai bus skanūs ir kokybiški.'}
                            </p>
                            <p className="text-gray-600 leading-relaxed mb-8">
                                {settings.about_text_4 || 'Visi mūsų produktai skirti naudoti tik lauke; jie yra ilgaamžiai įvairių oro sąlygų atžvilgiu ir lengvai pernešami. Judumas priklauso nuo jūsų pasirinkto modelio.'}
                            </p>
                            <a
                                href="/kontaktai"
                                className="inline-block px-8 py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200"
                            >
                                Susisiekti
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            {/* ── FOOTER ── */}
            <footer className="bg-gray-950 text-gray-400">
                <div className="max-w-7xl mx-auto px-6 py-14">
                    <div className="flex flex-col md:flex-row gap-12" style={{ alignItems: 'flex-start' }}>

                        {/* Left column: logo + about */}
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

                        {/* Right column: EU funds */}
                        <div className="flex-1" style={{ marginTop: 0, paddingTop: 0 }}>
                            <h3 className="text-white font-bold text-lg uppercase tracking-wide mb-1">ES Fondai</h3>
                            <div className="w-10 h-0.5 bg-red-600 mb-5" />
                            <p className="text-gray-400 text-sm leading-relaxed mb-4">
                                SIA „Linda-1" 2016 m. gegužės 2 d. pasirašė sutartį Nr. SKV-L-2016/193 su Latvijos investicijų ir plėtros agentūra dėl paramos gavimo pagal priemonę „Tarptautinio konkurencingumo skatinimas", kurią bendrai finansuoja Europos regioninės plėtros fondas.
                            </p>
                            <a href="/es-fondai" className="text-red-500 hover:text-red-400 text-sm transition-colors">
                                Skaityti daugiau »
                            </a>
                            <div className="mt-6">
                                <img src="/images/eu_fond.png" alt="ES Fondai" className="h-16 w-auto" />
                            </div>
                        </div>

                    </div>
                </div>

                {/* Red copyright bar */}
                <div className="bg-red-700 py-3 text-center">
                    <span className="text-white text-xs tracking-wide">
                        © Copyright ABAS Smoke House 2019. All Right Reserved.
                    </span>
                </div>
            </footer>

        </div>
    );
}
