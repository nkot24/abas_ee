import { useState } from 'react';
import { useCart } from '../useCart';
import CartDrawer from '../CartDrawer';
import { useLang } from '../i18n';
import LangSwitcher from '../LangSwitcher';

const content = {
    et: {
        title: 'Tarne ja Tagastamine',
        deliveryTitle: 'Tarnetingimused',
        deliveryItems: [
            'Tarne toimub Latvijas Posti kaudu.',
            'Tarneaeg on 2–5 tööpäeva alates tellimuse kinnitamisest.',
            'Pakid toimetatakse postkontori või pakiautomaadi punkti (Omniva, Latvijas Pasts).',
            'Tarnekulud arvutatakse tellimuse esitamisel sõltuvalt sihtriigist ja pakikaalu.',
            'Saadame Eestisse, Lätti ja Leetu.',
        ],
        returnsTitle: 'Tagastustingimused',
        returnsItems: [
            'Teil on õigus kaup tagastada 14 päeva jooksul alates kättesaamisest.',
            'Kaup peab olema kasutamata, originaalpakendis ja samas seisukorras nagu kättetoimetamisel.',
            'Tagastuse kulud kannab ostja, välja arvatud juhul, kui kaup on defektiga.',
            'Tagastamiseks võtke meiega ühendust: info@abas.ee',
            'Pärast kauba tagastuse kontrollimist tagastame raha 5–10 tööpäeva jooksul.',
        ],
        contactTitle: 'Küsimused?',
        contactText: 'Võtke meiega ühendust: ',
    },
    en: {
        title: 'Shipping & Returns',
        deliveryTitle: 'Delivery Terms',
        deliveryItems: [
            'Delivery is carried out via Latvijas Pasts (Latvian Post).',
            'Delivery time is 2–5 business days from order confirmation.',
            'Packages are delivered to a post office or parcel locker (Omniva, Latvijas Pasts).',
            'Shipping costs are calculated at checkout depending on the destination country and package weight.',
            'We ship to Estonia, Latvia, and Lithuania.',
        ],
        returnsTitle: 'Return Policy',
        returnsItems: [
            'You have the right to return goods within 14 days of receipt.',
            'The item must be unused, in its original packaging, and in the same condition as when delivered.',
            'Return shipping costs are the responsibility of the buyer, unless the item is defective.',
            'To initiate a return, contact us at: info@abas.ee',
            'After inspecting the returned item, we will issue a refund within 5–10 business days.',
        ],
        contactTitle: 'Questions?',
        contactText: 'Contact us: ',
    },
    lt: {
        title: 'Pristatymas ir Grąžinimas',
        deliveryTitle: 'Pristatymo sąlygos',
        deliveryItems: [
            'Pristatymas vykdomas per Latvijas Pasts (Latvijos paštą).',
            'Pristatymo laikas – 2–5 darbo dienos nuo užsakymo patvirtinimo.',
            'Siuntos pristatomos į pašto skyrius arba paketų automatus (Omniva, Latvijas Pasts).',
            'Pristatymo išlaidos apskaičiuojamos atsiskaitant, atsižvelgiant į paskirties šalį ir siuntinio svorį.',
            'Siunčiame į Estiją, Latviją ir Lietuvą.',
        ],
        returnsTitle: 'Grąžinimo politika',
        returnsItems: [
            'Turite teisę grąžinti prekes per 14 dienų nuo gavimo.',
            'Prekė turi būti nenaudota, originalioje pakuotėje ir tokios pat būklės, kaip buvo pristatyta.',
            'Grąžinimo išlaidas padengia pirkėjas, išskyrus atvejus, kai prekė yra su defektu.',
            'Norėdami grąžinti, susisiekite su mumis: info@abas.ee',
            'Patikrinus grąžintą prekę, pinigai bus grąžinti per 5–10 darbo dienų.',
        ],
        contactTitle: 'Klausimai?',
        contactText: 'Susisiekite su mumis: ',
    },
};

export default function Shipping() {
    const { items: cartItems, removeItem, updateQty, count: cartCount, total: cartTotal } = useCart();
    const [cartOpen, setCartOpen] = useState(false);
    const [mobileOpen, setMobileOpen] = useState(false);
    const { t, lang } = useLang();

    const c = content[lang] ?? content.et;

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

            <nav className="fixed top-0 inset-x-0 z-50 bg-white shadow-md">
                <div className="max-w-7xl mx-auto px-6 flex items-center justify-between h-24">
                    <a href="/"><img src="/images/logo.png" alt="ABAS" className="h-16 w-auto" /></a>
                    <div className="hidden md:flex items-center gap-8">
                        {navLinks.map(link => (
                            <a key={link.href} href={link.href} className="text-sm font-medium tracking-wider uppercase text-gray-700 hover:text-red-600 transition-colors">
                                {link.label}
                            </a>
                        ))}
                    </div>
                    <div className="flex items-center gap-4">
                        <LangSwitcher />
                        <button onClick={() => setCartOpen(true)} className="relative text-gray-600 hover:text-red-600 transition-colors">
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            {cartCount > 0 && (
                                <span className="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{cartCount}</span>
                            )}
                        </button>
                        <button className="md:hidden text-gray-700" onClick={() => setMobileOpen(o => !o)}>
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                {mobileOpen ? <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12"/> : <path strokeLinecap="round" strokeLinejoin="round" d="M4 6h16M4 12h16M4 18h16"/>}
                            </svg>
                        </button>
                    </div>
                </div>
                {mobileOpen && (
                    <div className="md:hidden bg-white border-t border-gray-100 px-6 py-4 flex flex-col gap-4">
                        {navLinks.map(link => (
                            <a key={link.href} href={link.href} className="text-sm font-semibold text-gray-600 hover:text-red-600 uppercase tracking-wider">{link.label}</a>
                        ))}
                        <div className="pt-2 border-t border-gray-100"><LangSwitcher /></div>
                    </div>
                )}
            </nav>

            <div className="pt-24">
                <div className="bg-gray-50 border-b border-gray-200">
                    <div className="max-w-3xl mx-auto px-6 py-10">
                        <h1 className="text-3xl font-black text-gray-900 uppercase tracking-widest">{c.title}</h1>
                    </div>
                </div>

                <div className="max-w-3xl mx-auto px-6 py-12 space-y-10 text-gray-700 leading-relaxed">

                    <div>
                        <h2 className="text-xl font-bold text-gray-900 uppercase tracking-wide mb-4 pb-2 border-b border-gray-200">{c.deliveryTitle}</h2>
                        <ul className="list-disc list-inside space-y-2 ml-2">
                            {c.deliveryItems.map((item, i) => <li key={i}>{item}</li>)}
                        </ul>
                    </div>

                    <div>
                        <h2 className="text-xl font-bold text-gray-900 uppercase tracking-wide mb-4 pb-2 border-b border-gray-200">{c.returnsTitle}</h2>
                        <ul className="list-disc list-inside space-y-2 ml-2">
                            {c.returnsItems.map((item, i) => <li key={i}>{item}</li>)}
                        </ul>
                    </div>

                    <div>
                        <h2 className="text-xl font-bold text-gray-900 uppercase tracking-wide mb-4 pb-2 border-b border-gray-200">{c.contactTitle}</h2>
                        <p>{c.contactText}<a href="mailto:info@abas.ee" className="text-red-600 hover:underline font-medium">info@abas.ee</a></p>
                    </div>

                </div>

                <footer className="bg-gray-900 text-gray-400 text-xs text-center py-6 mt-12">
                    <p>© {new Date().getFullYear()} ABAS. All rights reserved.</p>
                </footer>
            </div>
        </div>
    );
}
