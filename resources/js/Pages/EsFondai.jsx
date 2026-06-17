import { useState } from 'react';
import { useCart } from '../useCart';
import CartDrawer from '../CartDrawer';
import { useLang } from '../i18n';
import LangSwitcher from '../LangSwitcher';

export default function EsFondai({ settings = {} }) {
    const { t } = useLang();
    const s = {
        section1_text:    t.euFunds.section1Text,
        section2_p1:      t.euFunds.section2p1,
        section2_p2:      t.euFunds.section2p2,
        project_budget:   '617 503,51 EUR',
        project_deadline: t.euFunds.projectDeadline,
        ...settings,
    };
    const { items: cartItems, removeItem, updateQty, count: cartCount, total: cartTotal } = useCart();
    const [cartOpen, setCartOpen]   = useState(false);
    const [mobileOpen, setMobileOpen] = useState(false);

    const navLinks = [
        { label: t.nav.home,     href: '/' },
        { label: t.nav.products, href: '/products' },
        { label: t.nav.recipes,  href: '/recipes' },
        { label: t.nav.euFunds,  href: '/eu-funds' },
        { label: t.nav.contacts, href: '/contact' },
    ];

    return (
        <div className="min-h-screen bg-white" style={{ fontFamily: "'Segoe UI', sans-serif" }}>
            <CartDrawer open={cartOpen} onClose={() => setCartOpen(false)} items={cartItems} removeItem={removeItem} updateQty={updateQty} total={cartTotal} />

            {/* ── NAVIGATION ── */}
            <nav className="fixed top-0 inset-x-0 z-50 bg-white shadow-md">
                <div className="max-w-7xl mx-auto px-6 flex items-center justify-between h-24">
                    <a href="/" className="flex items-center">
                        <img src="/images/logo.png" alt="ABAS Smoke House" className="h-16 w-auto" />
                    </a>
                    <div className="hidden md:flex items-center gap-8">
                        {navLinks.map(link => (
                            <a key={link.href} href={link.href}
                                className={`text-sm font-medium tracking-wider uppercase transition-colors duration-200 ${
                                    link.href === '/eu-funds' ? 'text-red-600' : 'text-gray-700 hover:text-red-600'
                                }`}>
                                {link.label}
                            </a>
                        ))}
                    </div>
                    <div className="flex items-center gap-5">
                        <div className="hidden md:flex items-center gap-3">
                            <a href="https://www.facebook.com/abas.lv/" target="_blank" rel="noopener noreferrer" className="text-gray-500 hover:text-red-600 transition-colors" aria-label="Facebook">
                                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                            </a>
                            <a href="https://www.instagram.com/abassmokehouse" target="_blank" rel="noopener noreferrer" className="text-gray-500 hover:text-red-600 transition-colors" aria-label="Instagram">
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                            </a>
                        </div>
                        <LangSwitcher />
                        <button onClick={() => setCartOpen(true)} className="relative text-gray-600 hover:text-red-600 transition-colors">
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
                            <a key={link.href} href={link.href} className="text-gray-700 hover:text-red-600 text-sm font-medium uppercase tracking-wider">
                                {link.label}
                            </a>
                        ))}
                        <div className="pt-2 border-t border-gray-100">
                            <LangSwitcher />
                        </div>
                    </div>
                )}
            </nav>

            {/* ── PAGE HEADER ── */}
            <section className="relative pt-24">
                <div className="relative h-52 overflow-hidden">
                    <img src="/images/hero.jpg" alt="ES Fondai" className="w-full h-full object-cover" />
                    <div className="absolute inset-0 bg-black/60" />
                    <div className="absolute inset-0 flex flex-col justify-center px-8 md:px-16">
                        <h1 className="text-4xl md:text-5xl font-black text-white uppercase tracking-wide mb-2">{t.euFunds.title}</h1>
                        <p className="text-gray-300 text-sm">
                            <a href="/" className="hover:text-red-400 transition-colors">{t.euFunds.breadHome}</a>
                            <span className="mx-2">/</span>
                            <span>{t.euFunds.title}</span>
                        </p>
                    </div>
                </div>
            </section>

            {/* ── CONTENT ── */}
            <section className="py-16 bg-white">
                <div className="max-w-4xl mx-auto px-6">

                    <div className="mb-10">
                        <img src="/images/eu_fond.png" alt="ES Fondai" className="h-20 w-auto" />
                    </div>

                    <div className="mb-12">
                        <h2 className="text-2xl font-black text-gray-900 uppercase tracking-wide mb-2">{t.euFunds.section1Title}</h2>
                        <div className="w-12 h-1 bg-red-600 mb-6 rounded-full" />
                        <p className="text-gray-600 leading-relaxed mb-4">
                            {s.section1_text}
                        </p>
                    </div>

                    <div className="mb-12">
                        <h2 className="text-2xl font-black text-gray-900 uppercase tracking-wide mb-2">{t.euFunds.section2Title}</h2>
                        <div className="w-12 h-1 bg-red-600 mb-6 rounded-full" />

                        <p className="text-gray-600 leading-relaxed mb-6">
                            {s.section2_p1}
                        </p>

                        <p className="text-gray-600 leading-relaxed mb-6">
                            {s.section2_p2}
                        </p>

                        <div className="bg-gray-50 border-l-4 border-red-600 px-6 py-4 rounded-r-lg">
                            <p className="text-gray-700 font-semibold">
                                {t.euFunds.totalBudget} <span className="text-red-600">{s.project_budget}</span>
                            </p>
                            <p className="text-gray-600 text-sm mt-1">
                                {t.euFunds.deadline} {s.project_deadline}
                            </p>
                        </div>
                    </div>

                </div>
            </section>

            {/* ── FOOTER ── */}
            <footer className="bg-gray-950 text-gray-400">
                <div className="max-w-7xl mx-auto px-6 py-14">
                    <div className="flex flex-col md:flex-row gap-12" style={{ alignItems: 'flex-start' }}>
                        <div className="flex-1">
                            <h3 className="text-white font-bold text-lg uppercase tracking-wide mb-1">{t.footer.aboutTitle}</h3>
                            <div className="w-10 h-0.5 bg-red-600 mb-5" />
                            <p className="text-gray-400 text-sm leading-relaxed mb-6">{t.footer.aboutDesc}</p>
                            <a href="/privacy-policy" className="text-gray-400 hover:text-red-400 text-sm transition-colors block mb-2">{t.footer.privacy}</a>
                            <a href="/shipping" className="text-gray-400 hover:text-red-400 text-sm transition-colors block mb-6">{t.footer.shipping}</a>
                            <img src="/images/foter_image.png" alt="ABAS Smoke House" className="h-44 w-auto block" />
                        </div>
                        <div className="flex-1">
                            <h3 className="text-white font-bold text-lg uppercase tracking-wide mb-1">{t.footer.euFundsTitle}</h3>
                            <div className="w-10 h-0.5 bg-red-600 mb-5" />
                            <p className="text-gray-400 text-sm leading-relaxed mb-4">{t.footer.euFundsDesc}</p>
                            <a href="/eu-funds" className="text-red-500 hover:text-red-400 text-sm transition-colors">{t.footer.readMore}</a>
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
