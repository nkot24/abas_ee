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
            'Tarneaeg on 2–5 tööpäeva alates tellimuse saatmisest.',
            'Pakid toimetatakse postkontorisse, pakiautomaati või koju (Latvijas Pasts).',
            'Tarnekulud arvutatakse tellimuse esitamisel sõltuvalt sihtriigist ja pakikaalu.',
            'Saadame Eestisse, Lätti ja Leetu.',
        ],
        returnsTitle: 'Tagastustingimused',
        returnsItems: [
            'Vastavalt tarbijakaitseseadusele on tarbijal õigus 14 päeva jooksul lepingust taganeda, põhjust nimetamata.',
            '14-päevane tähtaeg algab päevast, mil tarbija või tema määratud isik kauba kätte sai.',
            'Taganemisõiguse kasutamiseks palume teavitada meid e-posti teel info@abas.lv, märkides tellimuse numbri.',
            'Tagastatav kaup peab olema kasutamata, kahjustamata, originaalpakendis ja täiskomplektis.',
            'Kaup tuleb saata tagasi hiljemalt 14 päeva jooksul pärast taganemisteate saatmist.',
            'Kauba tagasisaatmise kulud kannab ostja.',
            'Tagastame raha (sh algsed tarnekulud) hiljemalt 14 päeva jooksul pärast taganemisteate saamist, kasutades sama makseviisi. Meil on õigus tagasimakset edasi lükata kuni kauba kättesaamiseni.',
            'Kui kaup on defektne või ei vasta lepingutingimustele, palume meiega ühendust võtta — sellisel juhul katame tagasisaatmiskulud meie.',
        ],
        contactTitle: 'Küsimused?',
        contactText: 'Võtke meiega ühendust: ',
    },
    en: {
        title: 'Shipping & Returns',
        deliveryTitle: 'Delivery Terms',
        deliveryItems: [
            'Delivery is carried out via Latvijas Pasts (Latvian Post).',
            'Delivery time is 2–5 business days from the date of shipment.',
            'Packages are delivered to a post office, parcel locker, or to your home (Latvijas Pasts).',
            'Shipping costs are calculated at checkout depending on the destination country and package weight.',
            'We ship to Estonia, Latvia, and Lithuania.',
        ],
        returnsTitle: 'Return Policy',
        returnsItems: [
            'In accordance with consumer protection law, the consumer has the right to withdraw from the contract within 14 days without giving any reason.',
            'The 14-day period begins on the day the consumer or their designated person receives the goods.',
            'To exercise the right of withdrawal, please notify us by email at info@abas.lv, stating the order number.',
            'The returned item must be unused, undamaged, in its original packaging and complete.',
            'The item must be sent back no later than 14 days after sending the notice of withdrawal.',
            'The cost of returning the goods is borne by the buyer.',
            'We will refund the payment (including original delivery costs) no later than 14 days after receiving the withdrawal notice, using the same payment method. We reserve the right to withhold the refund until the goods have been received back.',
            'If the item is defective or does not conform to the contract, please contact us — in such cases we will cover the return shipping costs.',
        ],
        contactTitle: 'Questions?',
        contactText: 'Contact us: ',
    },
    lt: {
        title: 'Pristatymas ir Grąžinimas',
        deliveryTitle: 'Pristatymo sąlygos',
        deliveryItems: [
            'Pristatymas vykdomas per Latvijas Pasts (Latvijos paštą).',
            'Pristatymo laikas – 2–5 darbo dienos nuo siuntos išsiuntimo.',
            'Siuntos pristatomos į pašto skyrius, paketų automatus arba namo (Latvijas Pasts).',
            'Pristatymo išlaidos apskaičiuojamos atsiskaitant, atsižvelgiant į paskirties šalį ir siuntinio svorį.',
            'Siunčiame į Estiją, Latviją ir Lietuvą.',
        ],
        returnsTitle: 'Grąžinimo politika',
        returnsItems: [
            'Pagal vartotojų teisių apsaugos įstatymus vartotojas turi teisę per 14 dienų atsisakyti sutarties nenurodydamas priežasties.',
            '14 dienų terminas prasideda dieną, kai vartotojas arba jo nurodytas asmuo gavo prekę.',
            'Norėdami pasinaudoti atsisakymo teise, prašome informuoti mus el. paštu info@abas.lv, nurodant užsakymo numerį.',
            'Grąžinama prekė turi būti nenaudota, nepažeista, originalioje pakuotėje ir pilnoje komplektacijoje.',
            'Prekė turi būti išsiųsta atgal ne vėliau kaip per 14 dienų nuo atsisakymo pranešimo išsiuntimo.',
            'Prekės grąžinimo išlaidas padengia pirkėjas.',
            'Pinigus (įskaitant pradines pristatymo išlaidas) grąžinsime ne vėliau kaip per 14 dienų nuo atsisakymo pranešimo gavimo, naudodami tą patį mokėjimo būdą. Turime teisę sulaikyti grąžinimą tol, kol prekė bus gauta atgal.',
            'Jei prekė yra su defektu arba neatitinka sutarties sąlygų, prašome susisiekti su mumis — tokiais atvejais grąžinimo išlaidas dengiame mes.',
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
                        <p>{c.contactText}<a href="mailto:info@abas.lv" className="text-red-600 hover:underline font-medium">info@abas.lv</a></p>
                    </div>

                </div>

                <footer className="bg-gray-900 text-gray-400 text-xs text-center py-6 mt-12">
                    <p>© {new Date().getFullYear()} ABAS. All rights reserved.</p>
                </footer>
            </div>
        </div>
    );
}
