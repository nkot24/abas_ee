import { useState } from 'react';
import { Link } from '@inertiajs/react';

const navLinks = [
    { label: 'Pradžia',   href: '/' },
    { label: 'Produktai', href: '/produktai' },
    { label: 'Receptai',  href: '/receptai' },
    { label: 'ES Fondai', href: '/es-fondai' },
    { label: 'Kontaktai', href: '/kontaktai' },
];

export default function Product({ product }) {
    const [cartCount, setCartCount]   = useState(0);
    const [mobileOpen, setMobileOpen] = useState(false);
    const [qty, setQty]               = useState(1);

    const images   = product.images ?? [];
    const mainIdx  = images.findIndex(i => i.is_main);
    const [active, setActive] = useState(mainIdx >= 0 ? mainIdx : 0);

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
                            <a key={link.label} href={link.href}
                                className={`text-sm font-medium tracking-wider uppercase transition-colors duration-200 ${
                                    link.href === '/produktai' ? 'text-red-600' : 'text-gray-700 hover:text-red-600'
                                }`}>
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

            {/* ── BREADCRUMB ── */}
            <div className="pt-24 bg-gray-50 border-b border-gray-100">
                <div className="max-w-7xl mx-auto px-6 py-3">
                    <p className="text-sm text-gray-500">
                        <a href="/" className="hover:text-red-600 transition-colors">Pradžia</a>
                        <span className="mx-2">/</span>
                        <a href="/produktai" className="hover:text-red-600 transition-colors">Produktai</a>
                        <span className="mx-2">/</span>
                        <span className="text-gray-800">{product.name}</span>
                    </p>
                </div>
            </div>

            {/* ── PRODUCT DETAIL ── */}
            <section className="py-14 bg-white">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-14">

                        {/* Image gallery */}
                        <div>
                            {/* Main display */}
                            <div className="relative w-full aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                {images.length > 0 ? (
                                    <img
                                        src={images[active].url}
                                        alt={product.name}
                                        className="w-full h-full object-cover"
                                    />
                                ) : (
                                    <div className="w-full h-full flex items-center justify-center">
                                        <svg className="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                )}
                                {product.badge && (
                                    <span className="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-sm uppercase tracking-wide">
                                        {product.badge}
                                    </span>
                                )}
                                {images.length > 1 && (
                                    <>
                                        <button
                                            onClick={() => setActive(i => (i - 1 + images.length) % images.length)}
                                            className="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/80 hover:bg-white rounded-full shadow flex items-center justify-center transition-all"
                                        >
                                            <svg className="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <button
                                            onClick={() => setActive(i => (i + 1) % images.length)}
                                            className="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/80 hover:bg-white rounded-full shadow flex items-center justify-center transition-all"
                                        >
                                            <svg className="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                        <div className="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                                            {images.map((_, idx) => (
                                                <button
                                                    key={idx}
                                                    onClick={() => setActive(idx)}
                                                    className={`w-2 h-2 rounded-full transition-all ${idx === active ? 'bg-red-600 w-4' : 'bg-white/70 hover:bg-white'}`}
                                                />
                                            ))}
                                        </div>
                                    </>
                                )}
                            </div>

                            {/* Thumbnails */}
                            {images.length > 1 && (
                                <div className="flex gap-2 mt-3 flex-wrap">
                                    {images.map((img, idx) => (
                                        <button
                                            key={img.id}
                                            onClick={() => setActive(idx)}
                                            className={`w-16 h-16 rounded-md overflow-hidden border-2 transition-all ${
                                                idx === active ? 'border-red-600' : 'border-transparent hover:border-gray-300'
                                            }`}
                                        >
                                            <img src={img.url} alt="" className="w-full h-full object-cover" />
                                        </button>
                                    ))}
                                </div>
                            )}
                        </div>

                        {/* Info */}
                        <div>
                            <span className="text-xs font-bold text-red-600 uppercase tracking-widest">{product.category_label}</span>
                            <h1 className="text-3xl font-black text-gray-900 mt-2 mb-4 leading-tight">{product.name}</h1>
                            <div className="w-12 h-1 bg-red-600 mb-6 rounded-full" />

                            <p className="text-4xl font-black text-red-600 mb-8">
                                {product.price} <span className="text-lg font-normal text-gray-400">€</span>
                            </p>

                            {/* Description */}
                            {product.description && (
                                <p className="text-gray-600 leading-relaxed mb-6">{product.description}</p>
                            )}

                            {/* Specs */}
                            <div className="bg-gray-50 rounded-lg p-5 mb-8 space-y-3">
                                {product.material && (
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500 font-medium">Medžiaga</span>
                                        <span className="text-gray-800 font-semibold">{{ nerūdijantis: 'Nerūdijantis plienas', paprastas: 'Paprastas plienas', corten: 'Corten plienas' }[product.material] ?? product.material}</span>
                                    </div>
                                )}
                                {product.h && (
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500 font-medium">Aukštis</span>
                                        <span className="text-gray-800 font-semibold">{product.h} cm</span>
                                    </div>
                                )}
                                {product.l && (
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500 font-medium">Ilgis</span>
                                        <span className="text-gray-800 font-semibold">{product.l} cm</span>
                                    </div>
                                )}
                                {product.w && (
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500 font-medium">Plotis</span>
                                        <span className="text-gray-800 font-semibold">{product.w} cm</span>
                                    </div>
                                )}
                                {product.thickness && (
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500 font-medium">Medžiagos storis</span>
                                        <span className="text-gray-800 font-semibold">{product.thickness} mm</span>
                                    </div>
                                )}
                                {product.weight && (
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500 font-medium">Svoris</span>
                                        <span className="text-gray-800 font-semibold">{product.weight} kg</span>
                                    </div>
                                )}
                            </div>

                            {/* Qty + cart */}
                            <div className="flex items-center gap-4 mb-6">
                                <div className="flex items-center border border-gray-300 rounded-sm">
                                    <button onClick={() => setQty(q => Math.max(1, q - 1))}
                                        className="w-10 h-10 text-gray-600 hover:text-red-600 text-lg font-bold transition-colors">−</button>
                                    <span className="w-10 text-center text-sm font-semibold">{qty}</span>
                                    <button onClick={() => setQty(q => q + 1)}
                                        className="w-10 h-10 text-gray-600 hover:text-red-600 text-lg font-bold transition-colors">+</button>
                                </div>
                                <button
                                    onClick={() => setCartCount(c => c + qty)}
                                    className="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200"
                                >
                                    Į Krepšelį
                                </button>
                            </div>

                            <a href="/kontaktai"
                                className="block text-center w-full py-3 border-2 border-gray-900 hover:bg-gray-900 hover:text-white text-gray-900 font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200">
                                Užklausti dėl produkto
                            </a>
                        </div>
                    </div>

                    {/* Back */}
                    <div className="mt-14">
                        <Link href="/produktai"
                            className="inline-block px-8 py-3.5 border-2 border-gray-900 hover:bg-gray-900 hover:text-white text-gray-900 font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200">
                            ← Visi Produktai
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
                            <p className="text-gray-400 text-sm leading-relaxed mb-6">Rūkymai ir kepsniavimas, lauke ir namuose – VISADA SKANU!</p>
                            <a href="#" className="text-gray-400 hover:text-red-400 text-sm transition-colors block mb-6">› Privatumo politika</a>
                            <img src="/images/foter_image.png" alt="ABAS Smoke House" className="h-44 w-auto block" />
                        </div>
                        <div className="flex-1">
                            <h3 className="text-white font-bold text-lg uppercase tracking-wide mb-1">ES Fondai</h3>
                            <div className="w-10 h-0.5 bg-red-600 mb-5" />
                            <p className="text-gray-400 text-sm leading-relaxed mb-4">
                                SIA „Linda-1" 2016 m. gegužės 2 d. pasirašė sutartį Nr. SKV-L-2016/193 su Latvijos investicijų ir plėtros agentūra dėl paramos gavimo pagal priemonę „Tarptautinio konkurencingumo skatinimas", kurią bendrai finansuoja Europos regioninės plėtros fondas.
                            </p>
                            <a href="/es-fondai" className="text-red-500 hover:text-red-400 text-sm transition-colors">Skaityti daugiau »</a>
                            <div className="mt-6">
                                <img src="/images/eu_fond.png" alt="ES Fondai" className="h-16 w-auto" />
                            </div>
                        </div>
                    </div>
                </div>
                <div className="bg-red-700 py-3 text-center">
                    <span className="text-white text-xs tracking-wide">© Copyright ABAS Smoke House 2019. All Right Reserved.</span>
                </div>
            </footer>

        </div>
    );
}
