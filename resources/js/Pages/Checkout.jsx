import { useState } from 'react';
import { useCart } from '../useCart';

const navLinks = [
    { label: 'Pradžia',   href: '/' },
    { label: 'Produktai', href: '/produktai' },
    { label: 'Receptai',  href: '/receptai' },
    { label: 'ES Fondai', href: '/es-fondai' },
    { label: 'Kontaktai', href: '/kontaktai' },
];

const COUNTRIES = [
    { code: 'LT', label: 'Lietuva' },
    { code: 'LV', label: 'Latvija' },
    { code: 'EE', label: 'Estija' },
    { code: 'PL', label: 'Lenkija' },
    { code: 'DE', label: 'Vokietija' },
    { code: 'FI', label: 'Suomija' },
    { code: 'SE', label: 'Švedija' },
    { code: 'NO', label: 'Norvegija' },
];

function SectionCard({ title, children }) {
    return (
        <div className="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div className="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 className="text-xs font-black uppercase tracking-widest text-gray-700">{title}</h2>
            </div>
            <div className="p-6">{children}</div>
        </div>
    );
}

function Field({ label, error, half, children }) {
    return (
        <div className={half ? 'sm:col-span-1' : 'sm:col-span-2'}>
            <label className="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">{label}</label>
            {children}
            {error && <p className="text-red-500 text-xs mt-1 flex items-center gap-1"><span>⚠</span>{error}</p>}
        </div>
    );
}

function Input({ error, ...props }) {
    return (
        <input
            {...props}
            className={`w-full px-3.5 py-2.5 border rounded-md text-sm bg-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all ${error ? 'border-red-400 bg-red-50' : 'border-gray-200 hover:border-gray-300'}`}
        />
    );
}

function VisaIcon() {
    return (
        <svg viewBox="0 0 38 24" className="h-7 w-auto rounded shadow-sm">
            <rect width="38" height="24" rx="3" fill="#1A1F71"/>
            <path d="M14.5 16.5l1.5-9h2.4l-1.5 9zM22.5 7.7c-.5-.2-1.3-.4-2.2-.4-2.4 0-4.1 1.3-4.1 3.1 0 1.4 1.2 2.1 2.2 2.6 1 .5 1.3.8 1.3 1.2 0 .6-.8 1-1.5 1-.9 0-1.4-.1-2.2-.5l-.3-.1-.3 2c.6.3 1.6.5 2.7.5 2.6 0 4.2-1.3 4.2-3.2 0-1.1-.6-1.9-2-2.5-.8-.4-1.3-.7-1.3-1.2 0-.4.4-.8 1.3-.8.7 0 1.3.1 1.7.3l.2.1.3-1.9zM27.5 7.6h-1.9c-.6 0-1 .2-1.3.7l-3.6 8.2h2.5s.4-1.1.5-1.4h3.1c.1.3.3 1.4.3 1.4H29l-1.5-8.9zm-3.1 5.5c.2-.5.9-2.5.9-2.5s.2-.5.3-.8l.2.7s.4 2 .5 2.6h-1.9zM12.5 7.6l-2.4 6.1-.3-1.3c-.4-1.4-1.8-3-3.3-3.7l2.2 8.3h2.6l3.8-9.4h-2.6z" fill="#fff"/>
            <path d="M8.2 7.6H4.1l-.1.3c3.2.8 5.3 2.8 6.2 5.1L9.3 8.3c-.2-.5-.6-.7-1.1-.7z" fill="#F9A533"/>
        </svg>
    );
}

function MastercardIcon() {
    return (
        <svg viewBox="0 0 38 24" className="h-7 w-auto rounded shadow-sm">
            <rect width="38" height="24" rx="3" fill="#252525"/>
            <circle cx="15" cy="12" r="7" fill="#EB001B"/>
            <circle cx="23" cy="12" r="7" fill="#F79E1B"/>
            <path d="M19 6.8a7 7 0 010 10.4A7 7 0 0119 6.8z" fill="#FF5F00"/>
        </svg>
    );
}

export default function Checkout() {
    const { items, clearCart, total } = useCart();
    const [mobileOpen, setMobileOpen] = useState(false);
    const [submitting, setSubmitting] = useState(false);
    const [errors, setErrors]         = useState({});
    const [serverError, setServerError] = useState(null);

    const [form, setForm] = useState({
        first_name: '', last_name: '', email: '', phone: '',
        address: '', city: '', postal_code: '', country: 'LT',
    });

    const set = (field) => (e) => {
        setForm(f => ({ ...f, [field]: e.target.value }));
        setErrors(e => { const n = { ...e }; delete n[field]; return n; });
    };

    const grandTotal = total;

    const validate = () => {
        const e = {};
        if (!form.first_name.trim())  e.first_name  = 'Privalomas laukas';
        if (!form.last_name.trim())   e.last_name   = 'Privalomas laukas';
        if (!form.email.trim())       e.email       = 'Privalomas laukas';
        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) e.email = 'Neteisingas el. paštas';
        if (!form.phone.trim())       e.phone       = 'Privalomas laukas';
        if (!form.address.trim())     e.address     = 'Privalomas laukas';
        if (!form.city.trim())        e.city        = 'Privalomas laukas';
        if (!form.postal_code.trim()) e.postal_code = 'Privalomas laukas';
        return e;
    };

    const submit = async (e) => {
        e.preventDefault();
        setServerError(null);
        const errs = validate();
        if (Object.keys(errs).length > 0) { setErrors(errs); return; }
        if (items.length === 0) return;
        setSubmitting(true);
        try {
            const res = await fetch('/uzsakymas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ ...form, items, total: grandTotal, payment_method: 'paysera' }),
            });
            const data = await res.json();
            if (res.ok && data.redirect) {
                clearCart();
                window.location.href = data.redirect;
            } else {
                setServerError(data.message || JSON.stringify(data));
            }
        } catch (err) {
            setServerError('Klaida: ' + err.message);
        } finally {
            setSubmitting(false);
        }
    };

    return (
        <div className="min-h-screen bg-gray-50" style={{ fontFamily: "'Segoe UI', sans-serif" }}>

            {/* Nav */}
            <nav className="fixed top-0 inset-x-0 z-50 bg-white shadow-sm border-b border-gray-100">
                <div className="max-w-7xl mx-auto px-6 flex items-center justify-between h-24">
                    <a href="/"><img src="/images/logo.png" alt="ABAS" className="h-16 w-auto" /></a>
                    <div className="hidden md:flex items-center gap-8">
                        {navLinks.map(link => (
                            <a key={link.href} href={link.href} className="text-sm font-semibold text-gray-600 hover:text-red-600 uppercase tracking-wider transition-colors">
                                {link.label}
                            </a>
                        ))}
                    </div>
                    <button className="md:hidden text-gray-700" onClick={() => setMobileOpen(o => !o)}>
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                            {mobileOpen ? <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12"/> : <path strokeLinecap="round" strokeLinejoin="round" d="M4 6h16M4 12h16M4 18h16"/>}
                        </svg>
                    </button>
                </div>
                {mobileOpen && (
                    <div className="md:hidden bg-white border-t border-gray-100 px-6 py-4 flex flex-col gap-4">
                        {navLinks.map(link => (
                            <a key={link.href} href={link.href} className="text-sm font-semibold text-gray-600 hover:text-red-600 uppercase tracking-wider">{link.label}</a>
                        ))}
                    </div>
                )}
            </nav>

            <div className="pt-24">
                {/* Header bar */}
                <div className="bg-white border-b border-gray-100">
                    <div className="max-w-6xl mx-auto px-6 py-5 flex items-center gap-3">
                        <a href="/produktai" className="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7"/></svg>
                        </a>
                        <h1 className="text-lg font-black text-gray-900 uppercase tracking-widest">Apmokėjimas</h1>
                    </div>
                </div>

                <div className="max-w-6xl mx-auto px-6 py-10">
                    {items.length === 0 ? (
                        <div className="text-center py-28">
                            <div className="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-5">
                                <svg className="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <p className="text-gray-400 mb-6">Krepšelis tuščias</p>
                            <a href="/produktai" className="inline-block px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-sm uppercase tracking-widest rounded-md transition-colors">
                                Pirkti produktus
                            </a>
                        </div>
                    ) : (
                        <form onSubmit={submit} className="grid grid-cols-1 lg:grid-cols-5 gap-8">

                            {/* ── LEFT COLUMN ── */}
                            <div className="lg:col-span-3 space-y-6">

                                {/* Contact */}
                                <SectionCard title="Kontaktinė informacija">
                                    <div className="grid grid-cols-2 gap-4">
                                        <Field label="Vardas" error={errors.first_name} half>
                                            <Input value={form.first_name} onChange={set('first_name')} error={errors.first_name} placeholder="Jonas" />
                                        </Field>
                                        <Field label="Pavardė" error={errors.last_name} half>
                                            <Input value={form.last_name} onChange={set('last_name')} error={errors.last_name} placeholder="Jonaitis" />
                                        </Field>
                                        <Field label="El. paštas" error={errors.email}>
                                            <Input type="email" value={form.email} onChange={set('email')} error={errors.email} placeholder="jonas@example.lt" />
                                        </Field>
                                        <Field label="Telefono numeris" error={errors.phone}>
                                            <Input type="tel" value={form.phone} onChange={set('phone')} error={errors.phone} placeholder="+370 600 00000" />
                                        </Field>
                                    </div>
                                </SectionCard>

                                {/* Shipping */}
                                <SectionCard title="Pristatymo adresas">
                                    <div className="grid grid-cols-2 gap-4">
                                        <Field label="Gatvė, namo nr." error={errors.address}>
                                            <Input value={form.address} onChange={set('address')} error={errors.address} placeholder="Gedimino pr. 1" />
                                        </Field>
                                        <Field label="Miestas" error={errors.city}>
                                            <Input value={form.city} onChange={set('city')} error={errors.city} placeholder="Vilnius" />
                                        </Field>
                                        <Field label="Pašto kodas" error={errors.postal_code} half>
                                            <Input value={form.postal_code} onChange={set('postal_code')} error={errors.postal_code} placeholder="01234" />
                                        </Field>
                                        <Field label="Šalis" half>
                                            <select value={form.country} onChange={set('country')}
                                                className="w-full px-3.5 py-2.5 border border-gray-200 hover:border-gray-300 rounded-md text-sm bg-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
                                                {COUNTRIES.map(c => <option key={c.code} value={c.code}>{c.label}</option>)}
                                            </select>
                                        </Field>
                                    </div>
                                </SectionCard>

                                {/* Payment */}
                                <SectionCard title="Mokėjimo būdas">
                                    <div className="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                        <div className="flex items-center justify-between mb-4">
                                            <p className="text-sm font-semibold text-gray-700">Mokėjimas per</p>
                                            <span className="text-xl font-black text-[#F26522]">Paysera</span>
                                        </div>
                                        <div className="flex flex-wrap items-center gap-2">
                                            <VisaIcon />
                                            <MastercardIcon />
                                            <span className="inline-flex items-center px-2.5 py-1 bg-white border border-gray-200 rounded text-xs font-bold text-gray-700 shadow-sm">G Pay</span>
                                            <span className="inline-flex items-center px-2.5 py-1 bg-black rounded text-xs font-bold text-white shadow-sm">⌘ Pay</span>
                                            <span className="inline-flex items-center px-2.5 py-1 bg-white border border-gray-200 rounded text-xs font-semibold text-gray-600 shadow-sm">Swedbank</span>
                                            <span className="inline-flex items-center px-2.5 py-1 bg-white border border-gray-200 rounded text-xs font-semibold text-gray-600 shadow-sm">SEB</span>
                                            <span className="inline-flex items-center px-2.5 py-1 bg-white border border-gray-200 rounded text-xs font-semibold text-gray-600 shadow-sm">Luminor</span>
                                            <span className="inline-flex items-center px-2.5 py-1 bg-white border border-gray-200 rounded text-xs font-semibold text-gray-600 shadow-sm">Citadele</span>
                                        </div>
                                        <p className="text-xs text-gray-400 mt-3">Būsite nukreipti į Paysera mokėjimo puslapį.</p>
                                    </div>
                                </SectionCard>
                            </div>

                            {/* ── RIGHT COLUMN ── */}
                            <div className="lg:col-span-2">
                                <div className="bg-white rounded-lg shadow-sm border border-gray-100 sticky top-28">
                                    <div className="px-6 py-4 border-b border-gray-100 bg-gray-50 rounded-t-lg">
                                        <h2 className="text-xs font-black uppercase tracking-widest text-gray-700">
                                            Užsakymas ({items.reduce((s, i) => s + i.qty, 0)} vnt.)
                                        </h2>
                                    </div>

                                    {/* Items */}
                                    <div className="px-6 py-4 space-y-4 max-h-72 overflow-y-auto">
                                        {items.map(item => (
                                            <div key={item.id} className="flex items-center gap-3">
                                                <div className="w-12 h-12 flex-shrink-0 bg-gray-100 rounded-md overflow-hidden">
                                                    {item.image
                                                        ? <img src={item.image} alt={item.name} className="w-full h-full object-cover" />
                                                        : <div className="w-full h-full flex items-center justify-center">
                                                            <svg className="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                          </div>
                                                    }
                                                </div>
                                                <div className="flex-1 min-w-0">
                                                    <p className="text-xs font-semibold text-gray-800 leading-tight line-clamp-2">{item.name}</p>
                                                    <p className="text-xs text-gray-400 mt-0.5">× {item.qty}</p>
                                                </div>
                                                <p className="text-sm font-bold text-gray-900 flex-shrink-0">{(item.price * item.qty).toFixed(2)} €</p>
                                            </div>
                                        ))}
                                    </div>

                                    {/* Totals */}
                                    <div className="px-6 pb-2 pt-2 border-t border-gray-100 space-y-2 text-sm">
                                        <div className="flex justify-between text-gray-500">
                                            <span>Tarpinė suma</span>
                                            <span>{total.toFixed(2)} €</span>
                                        </div>
                                        <div className="flex justify-between text-gray-500">
                                            <span>Pristatymas</span>
                                            <span className="text-green-600 font-semibold">Nemokamas</span>
                                        </div>
                                    </div>
                                    <div className="px-6 py-4 border-t border-gray-100">
                                        <div className="flex justify-between font-black text-gray-900 text-lg mb-5">
                                            <span>Viso</span>
                                            <span className="text-red-600">{grandTotal.toFixed(2)} €</span>
                                        </div>
                                        {serverError && (
                                            <div className="mb-4 p-3 bg-red-50 border border-red-200 rounded-md text-xs text-red-700 break-all">
                                                {serverError}
                                            </div>
                                        )}
                                        <button type="submit" disabled={submitting}
                                            className="w-full py-4 bg-red-600 hover:bg-red-700 disabled:opacity-60 text-white font-black text-sm uppercase tracking-widest rounded-md transition-colors shadow-sm">
                                            {submitting ? 'Apdorojama...' : 'Patvirtinti užsakymą'}
                                        </button>
                                        <div className="flex items-center justify-center gap-1.5 mt-3 text-xs text-gray-400">
                                            <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Saugus mokėjimas
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    )}
                </div>
            </div>
        </div>
    );
}
