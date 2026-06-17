import { useState } from 'react';
import { Link } from '@inertiajs/react';
import { useCart } from '../useCart';
import CartDrawer from '../CartDrawer';
import { useLang } from '../i18n';
import LangSwitcher from '../LangSwitcher';

export default function Product({ product }) {
    const { items: cartItems, addItem, removeItem, updateQty: updateCartQty, count: cartCount, total: cartTotal } = useCart();
    const [cartOpen, setCartOpen]     = useState(false);
    const [mobileOpen, setMobileOpen] = useState(false);
    const [qty, setQty]               = useState(1);
    const { t, lang } = useLang();

    const navLinks = [
        { label: t.nav.home,     href: '/' },
        { label: t.nav.products, href: '/products' },
        { label: t.nav.recipes,  href: '/recipes' },
        { label: t.nav.euFunds,  href: '/eu-funds' },
        { label: t.nav.contacts, href: '/contact' },
    ];

    const images   = product.images ?? [];
    const mainIdx  = images.findIndex(i => i.is_main);
    const [active, setActive] = useState(mainIdx >= 0 ? mainIdx : 0);

    return (
        <div className="min-h-screen bg-white" style={{ fontFamily: "'Segoe UI', sans-serif" }}>
            <CartDrawer open={cartOpen} onClose={() => setCartOpen(false)} items={cartItems} removeItem={removeItem} updateQty={updateCartQty} total={cartTotal} />

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
                                    link.href === '/products' ? 'text-red-600' : 'text-gray-700 hover:text-red-600'
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

            {/* ── BREADCRUMB ── */}
            <div className="pt-24 bg-gray-50 border-b border-gray-100">
                <div className="max-w-7xl mx-auto px-6 py-3">
                    <p className="text-sm text-gray-500">
                        <a href="/" className="hover:text-red-600 transition-colors">{t.product.breadHome}</a>
                        <span className="mx-2">/</span>
                        <a href="/products" className="hover:text-red-600 transition-colors">{t.product.breadProducts}</a>
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
                                <div className="absolute top-4 left-4 flex flex-col gap-1.5">
                                    {product.badge && (
                                        <span className="bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-sm uppercase tracking-wide">
                                            {product.badge}
                                        </span>
                                    )}
                                    {product.on_sale && (
                                        <span className="bg-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-sm uppercase tracking-wide">
                                            {product.sale_percent ? `-${product.sale_percent}%` : 'SALE'}
                                        </span>
                                    )}
                                </div>
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
                            <span className="text-xs font-bold text-red-600 uppercase tracking-widest">{t.categories[product.category_slug] ?? product.category_label}</span>
                            <h1 className="text-3xl font-black text-gray-900 mt-2 mb-4 leading-tight">{lang === 'en' && product.name_en ? product.name_en : product.name}</h1>
                            <div className="w-12 h-1 bg-red-600 mb-6 rounded-full" />

                            {product.on_sale && (product.sale_price || product.sale_percent) ? (
                                <div className="mb-8">
                                    <div className="flex items-center gap-3 mb-1">
                                        <span className="text-xl line-through text-gray-400">{product.price} €</span>
                                        <span className="text-sm font-bold text-orange-500 bg-orange-50 px-2 py-0.5 rounded">
                                            {product.sale_percent ? `-${product.sale_percent}%` : 'SALE'}
                                        </span>
                                    </div>
                                    <p className="text-4xl font-black text-red-600">
                                        {product.sale_price ?? (product.price * (1 - product.sale_percent / 100)).toFixed(2)} <span className="text-lg font-normal text-gray-400">€</span>
                                    </p>
                                </div>
                            ) : (
                                <p className="text-4xl font-black text-red-600 mb-8">
                                    {product.price} <span className="text-lg font-normal text-gray-400">€</span>
                                </p>
                            )}

                            {product.description && (
                                <p className="text-gray-600 leading-relaxed mb-6">
                                    {lang === 'en' && product.description_en ? product.description_en : product.description}
                                </p>
                            )}

                            <div className="bg-gray-50 rounded-lg p-5 mb-8 space-y-3">
                                {product.material && (
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500 font-medium">{t.product.material}</span>
                                        <span className="text-gray-800 font-semibold">{t.product.materials[product.material] ?? product.material}</span>
                                    </div>
                                )}
                                {product.h && (
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500 font-medium">{t.product.height}</span>
                                        <span className="text-gray-800 font-semibold">{product.h} cm</span>
                                    </div>
                                )}
                                {product.l && (
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500 font-medium">{t.product.length}</span>
                                        <span className="text-gray-800 font-semibold">{product.l} cm</span>
                                    </div>
                                )}
                                {product.w && (
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500 font-medium">{t.product.width}</span>
                                        <span className="text-gray-800 font-semibold">{product.w} cm</span>
                                    </div>
                                )}
                                {product.thickness && (
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500 font-medium">{t.product.thickness}</span>
                                        <span className="text-gray-800 font-semibold">{product.thickness} mm</span>
                                    </div>
                                )}
                                {product.weight && (
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500 font-medium">{t.product.weight}</span>
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
                                    onClick={() => { addItem(product, qty); setCartOpen(true); }}
                                    className="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200"
                                >
                                    {t.btn.addToCart}
                                </button>
                            </div>

                            <a href="/contact"
                                className="block text-center w-full py-3 border-2 border-gray-900 hover:bg-gray-900 hover:text-white text-gray-900 font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200">
                                {t.product.inquiry}
                            </a>
                        </div>
                    </div>

                    <div className="mt-14">
                        <Link href="/products"
                            className="inline-block px-8 py-3.5 border-2 border-gray-900 hover:bg-gray-900 hover:text-white text-gray-900 font-bold text-sm uppercase tracking-widest rounded-sm transition-all duration-200">
                            {t.btn.backToProducts}
                        </Link>
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
