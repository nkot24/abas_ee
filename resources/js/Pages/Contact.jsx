import { useState } from 'react';
import { useCart } from '../useCart';
import CartDrawer from '../CartDrawer';
import { useLang } from '../i18n';
import LangSwitcher from '../LangSwitcher';

export default function Contact({ settings = {} }) {
    const s = {
        phone:               '+371 29284179',
        email:               'info@abas.lv',
        company_name:        'SIA Linda-1',
        reg_number:          'LV40003167227',
        legal_address:       'Kandava, Sabiles g. 2',
        bank_account_1:      'LV05UNLA0011003467703',
        bank_code_1:         'UNLALV2X',
        bank_name_1:         'A/S SEB banka',
        bank_account_2:      'LV79HABA0551005509068',
        bank_code_2:         'HABALV2X',
        bank_name_2:         'AS Swedbank',
        office_address:      'Kandava, Sabiles g. 2',
        production_address:  'Kandava, Jelgavas g. 1i',
        production_phone:    '+371 29284179',
        production_contact:  'Guntars',
        showroom_address:    '„Lidostas parks 5", „Vismaņi", Mārupes pag., Mārupes nov., LV-2167',
        showroom_phone:      '+371 20383017',
        map_embed_url:       'https://maps.google.com/maps?q=Jelgavas+iela+1d,+Kandava,+LV-3120,+Latvia&t=&z=15&ie=UTF8&iwloc=&output=embed',
        ...settings,
    };
    const { items: cartItems, removeItem, updateQty, count: cartCount, total: cartTotal } = useCart();
    const [cartOpen, setCartOpen]   = useState(false);
    const [mobileOpen, setMobileOpen] = useState(false);
    const [form, setForm] = useState({ vardas: '', email: '', zinute: '' });
    const [sent, setSent] = useState(false);
    const { t } = useLang();

    const navLinks = [
        { label: t.nav.home,     href: '/' },
        { label: t.nav.products, href: '/products' },
        { label: t.nav.recipes,  href: '/recipes' },
        { label: t.nav.euFunds,  href: '/eu-funds' },
        { label: t.nav.contacts, href: '/contact' },
    ];

    const handleSubmit = (e) => {
        e.preventDefault();
        setSent(true);
    };

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
                            <a
                                key={link.href}
                                href={link.href}
                                className={`text-sm font-medium tracking-wider uppercase transition-colors duration-200 ${
                                    link.href === '/contact'
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
                    <img src="/images/hero.jpg" alt="Kontaktai" className="w-full h-full object-cover" />
                    <div className="absolute inset-0 bg-black/60" />
                    <div className="absolute inset-0 flex flex-col justify-center px-8 md:px-16">
                        <h1 className="text-4xl md:text-5xl font-black text-white uppercase tracking-wide mb-2">
                            {t.contact.title}
                        </h1>
                        <p className="text-gray-300 text-sm">
                            <a href="/" className="hover:text-red-400 transition-colors">{t.contact.breadHome}</a>
                            <span className="mx-2">/</span>
                            <span>{t.contact.title}</span>
                        </p>
                    </div>
                </div>
            </section>

            {/* ── CONTACT CONTENT ── */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-14">

                        {/* Left: contact info */}
                        <div className="space-y-8">

                            {/* Phone */}
                            <div className="flex items-start gap-4">
                                <div className="flex-shrink-0 w-8 h-8 bg-red-600 rounded-full flex items-center justify-center mt-0.5">
                                    <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p className="text-xs font-bold text-red-600 uppercase tracking-widest mb-1">{t.contact.phone}</p>
                                    <a href={`tel:${s.phone}`} className="text-gray-800 font-semibold hover:text-red-600 transition-colors">
                                        {s.phone}
                                    </a>
                                </div>
                            </div>

                            {/* Email */}
                            <div className="flex items-start gap-4">
                                <div className="flex-shrink-0 w-8 h-8 bg-red-600 rounded-full flex items-center justify-center mt-0.5">
                                    <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p className="text-xs font-bold text-red-600 uppercase tracking-widest mb-1">{t.contact.email}</p>
                                    <a href={`mailto:${s.email}`} className="text-gray-800 font-semibold hover:text-red-600 transition-colors">
                                        {s.email}
                                    </a>
                                </div>
                            </div>

                            {/* Requisites */}
                            <div className="flex items-start gap-4">
                                <div className="flex-shrink-0 w-8 h-8 bg-red-600 rounded-full flex items-center justify-center mt-0.5">
                                    <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p className="text-xs font-bold text-red-600 uppercase tracking-widest mb-2">{t.contact.requisites}</p>
                                    <div className="text-gray-700 text-sm space-y-1 leading-relaxed">
                                        <p className="font-semibold">{s.company_name}</p>
                                        <p>{t.contact.regNumber} {s.reg_number}</p>
                                        <p>{t.contact.legalAddress} {s.legal_address}</p>
                                        <p className="mt-3 font-semibold">{t.contact.bankAccounts}</p>
                                        <p className="mt-1 font-medium">{s.bank_account_1}</p>
                                        <p>{t.contact.bankCode} {s.bank_code_1}</p>
                                        <p>{t.contact.bank} {s.bank_name_1}</p>
                                        <p className="mt-2 font-medium">{s.bank_account_2}</p>
                                        <p>{t.contact.bankCode} {s.bank_code_2}</p>
                                        <p>{t.contact.bank} {s.bank_name_2}</p>
                                    </div>
                                </div>
                            </div>

                            {/* Address */}
                            <div className="flex items-start gap-4">
                                <div className="flex-shrink-0 w-8 h-8 bg-red-600 rounded-full flex items-center justify-center mt-0.5">
                                    <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p className="text-xs font-bold text-red-600 uppercase tracking-widest mb-2">{t.contact.address}</p>
                                    <div className="text-gray-700 text-sm space-y-2 leading-relaxed">
                                        <p><span className="font-medium">{t.contact.officeAddress}:</span> {s.office_address}</p>
                                        <p>
                                            <span className="font-medium">{t.contact.productionAddress}:</span> {s.production_address}<br/>
                                            Tel: <a href={`tel:${s.production_phone}`} className="text-red-600 hover:underline">{s.production_phone}</a> {s.production_contact}
                                        </p>
                                        <p>
                                            <span className="font-medium">{t.contact.showroomAddress}:</span><br/>
                                            {s.showroom_address}<br/>
                                            Tel: <a href={`tel:${s.showroom_phone}`} className="text-red-600 hover:underline">{s.showroom_phone}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {/* Right: contact form */}
                        <div>
                            <h2 className="text-2xl font-black text-gray-900 uppercase tracking-wide mb-2">
                                {t.contact.formTitle}
                            </h2>
                            <div className="w-10 h-0.5 bg-red-600 mb-8" />

                            {sent ? (
                                <div className="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                                    <svg className="w-12 h-12 text-green-500 mx-auto mb-3" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p className="text-green-800 font-semibold">{t.contact.sentSuccess}</p>
                                    <p className="text-green-600 text-sm mt-1">{t.contact.sentSuccessDesc}</p>
                                </div>
                            ) : (
                                <form onSubmit={handleSubmit} className="space-y-5">
                                    <div>
                                        <label className="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-1.5">
                                            {t.contact.name}
                                        </label>
                                        <input
                                            type="text"
                                            required
                                            value={form.vardas}
                                            onChange={e => setForm(f => ({ ...f, vardas: e.target.value }))}
                                            className="w-full border border-gray-300 rounded-sm px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-red-500 transition-colors"
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-1.5">
                                            {t.contact.email}
                                        </label>
                                        <input
                                            type="email"
                                            required
                                            value={form.email}
                                            onChange={e => setForm(f => ({ ...f, email: e.target.value }))}
                                            className="w-full border border-gray-300 rounded-sm px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-red-500 transition-colors"
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-1.5">
                                            {t.contact.message}
                                        </label>
                                        <textarea
                                            required
                                            rows={5}
                                            value={form.zinute}
                                            onChange={e => setForm(f => ({ ...f, zinute: e.target.value }))}
                                            className="w-full border border-gray-300 rounded-sm px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-red-500 transition-colors resize-none"
                                        />
                                    </div>
                                    <button
                                        type="submit"
                                        className="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200"
                                    >
                                        {t.btn.send}
                                    </button>
                                </form>
                            )}
                        </div>

                    </div>
                </div>
            </section>

            {/* ── MAP ── */}
            <section className="h-96 bg-gray-200">
                <iframe
                    title="Žemėlapis"
                    src={s.map_embed_url}
                    width="100%"
                    height="100%"
                    style={{ border: 0 }}
                    allowFullScreen=""
                    loading="lazy"
                    referrerPolicy="no-referrer-when-downgrade"
                />
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
