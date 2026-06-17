import { useState, useMemo, useEffect } from 'react';
import { useCart } from '../useCart';
import CartDrawer from '../CartDrawer';
import { useLang } from '../i18n';
import LangSwitcher from '../LangSwitcher';

const MAX_PRICE  = 10000;
const MAX_HEIGHT = 300;
const MAX_WIDTH  = 300;

function ProductImage({ src }) {
    if (src) {
        return <img src={src} alt="" className="w-full h-full object-cover" />;
    }
    return (
        <div className="w-full h-full bg-gray-200 flex items-center justify-center">
            <svg className="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
    );
}

export default function Products({ products = [] }) {
    const { items: cartItems, addItem, removeItem, updateQty, count: cartCount, total: cartTotal } = useCart();
    const [cartOpen, setCartOpen]             = useState(false);
    const [mobileOpen, setMobileOpen]         = useState(false);
    const [activeCategory, setActiveCategory] = useState('visi');
    const [search, setSearch]                 = useState('');
    const [priceMin, setPriceMin]             = useState(0);
    const [priceMax, setPriceMax]             = useState(MAX_PRICE);
    const [materials, setMaterials]           = useState({ nerūdijantis: false, paprastas: false, corten: false });
    const [heightMin, setHeightMin]           = useState(0);
    const [heightMax, setHeightMax]           = useState(MAX_HEIGHT);
    const [widthMin, setWidthMin]             = useState(0);
    const [widthMax, setWidthMax]             = useState(MAX_WIDTH);
    const [page, setPage]                     = useState(1);
    const { t, lang } = useLang();
    const PER_PAGE = 15;

    const navLinks = [
        { label: t.nav.home,     href: '/' },
        { label: t.nav.products, href: '/products' },
        { label: t.nav.recipes,  href: '/recipes' },
        { label: t.nav.euFunds,  href: '/eu-funds' },
        { label: t.nav.contacts, href: '/contact' },
    ];

    const categories = [
        { id: 'visi',           label: t.categories.visi },
        { id: 'aksesuarai',     label: t.categories.aksesuarai },
        { id: 'sodo-baldai',    label: t.categories['sodo-baldai'] },
        { id: 'grilis',         label: t.categories.grilis },
        { id: 'rukyklos',       label: t.categories.rukyklos },
        { id: 'grilio-anglys',  label: t.categories['grilio-anglys'] },
        { id: 'virykles',       label: t.categories.virykles },
        { id: 'nesiojaimos',    label: t.categories.nesiojaimos },
        { id: 'profesionalios', label: t.categories.profesionalios },
        { id: 'lauzavietes',    label: t.categories.lauzavietes },
    ];

    const materialOptions = [
        { key: 'nerūdijantis', label: t.product.materials.nerūdijantis },
        { key: 'paprastas',    label: t.product.materials.paprastas },
        { key: 'corten',       label: t.product.materials.corten },
    ];

    const toggleMaterial = (key) => setMaterials(m => ({ ...m, [key]: !m[key] }));
    const activeMaterials = useMemo(() => Object.keys(materials).filter(k => materials[k]), [materials]);
    const dimsActive = heightMin > 0 || heightMax < MAX_HEIGHT || widthMin > 0 || widthMax < MAX_WIDTH;

    const normalisedProducts = useMemo(() =>
        products.map(p => ({ ...p, h: p.h ?? p.height ?? null, w: p.w ?? p.width ?? null })),
    [products]);

    const filtered = useMemo(() => normalisedProducts.filter(p => {
        if (activeCategory !== 'visi' && !(Array.isArray(p.category) ? p.category.includes(activeCategory) : p.category === activeCategory)) return false;
        if (search && !p.name.toLowerCase().includes(search.toLowerCase())) return false;
        if (p.price < priceMin || p.price > priceMax) return false;
        if (activeMaterials.length > 0 && !activeMaterials.includes(p.material)) return false;
        if (dimsActive && p.h !== null) {
            if (p.h < heightMin || p.h > heightMax) return false;
            if (p.w < widthMin  || p.w > widthMax)  return false;
        }
        return true;
    }), [normalisedProducts, activeCategory, search, priceMin, priceMax, activeMaterials, heightMin, heightMax, widthMin, widthMax, dimsActive]);

    useEffect(() => { setPage(1); }, [filtered]);

    const totalPages = Math.ceil(filtered.length / PER_PAGE);
    const paginated  = filtered.slice((page - 1) * PER_PAGE, page * PER_PAGE);

    const goToPage = (p) => {
        setPage(p);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const resetFilters = () => {
        setSearch('');
        setPriceMin(0);
        setPriceMax(MAX_PRICE);
        setMaterials({ nerūdijantis: false, paprastas: false, corten: false });
        setHeightMin(0);
        setHeightMax(MAX_HEIGHT);
        setWidthMin(0);
        setWidthMax(MAX_WIDTH);
        setActiveCategory('visi');
        setPage(1);
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

            {/* ── PAGE HEADER ── */}
            <section className="relative pt-24">
                <div className="relative h-52 overflow-hidden">
                    <img src="/images/hero.jpg" alt="Produktai" className="w-full h-full object-cover" />
                    <div className="absolute inset-0 bg-black/60" />
                    <div className="absolute inset-0 flex flex-col justify-center px-8 md:px-16">
                        <h1 className="text-4xl md:text-5xl font-black text-white uppercase tracking-wide mb-2">{t.products.pageTitle}</h1>
                        <p className="text-gray-300 text-sm">
                            <a href="/" className="hover:text-red-400 transition-colors">{t.product.breadHome}</a>
                            <span className="mx-2">/</span>
                            <span>{t.products.pageTitle}</span>
                        </p>
                    </div>
                </div>
            </section>

            {/* ── MAIN ── */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-6">

                    {/* Search bar */}
                    <div className="mb-8 relative max-w-lg">
                        <svg className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><path strokeLinecap="round" d="M21 21l-4.35-4.35"/>
                        </svg>
                        <input
                            type="text"
                            placeholder={t.products.search}
                            value={search}
                            onChange={e => setSearch(e.target.value)}
                            className="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-sm text-sm focus:outline-none focus:border-red-500 transition-colors"
                        />
                    </div>

                    <div className="flex flex-col md:flex-row gap-10">

                        {/* ── SIDEBAR ── */}
                        <aside className="md:w-60 flex-shrink-0 space-y-8">

                            {/* Categories */}
                            <div>
                                <h3 className="text-xs font-bold text-gray-900 uppercase tracking-widest mb-3">{t.products.categories}</h3>
                                <div className="w-8 h-0.5 bg-red-600 mb-4" />
                                <ul className="space-y-0.5">
                                    {categories.map(cat => (
                                        <li key={cat.id}>
                                            <button
                                                onClick={() => setActiveCategory(cat.id)}
                                                className={`w-full text-left px-3 py-2 text-sm rounded-sm transition-all duration-150 ${
                                                    activeCategory === cat.id
                                                        ? 'bg-red-600 text-white font-semibold'
                                                        : 'text-gray-600 hover:text-red-600 hover:bg-gray-50'
                                                }`}
                                            >
                                                {cat.label}
                                                <span className={`ml-1 text-xs ${activeCategory === cat.id ? 'text-red-200' : 'text-gray-400'}`}>
                                                    ({cat.id === 'visi' ? normalisedProducts.length : normalisedProducts.filter(p => Array.isArray(p.category) ? p.category.includes(cat.id) : p.category === cat.id).length})
                                                </span>
                                            </button>
                                        </li>
                                    ))}
                                </ul>
                            </div>

                            {/* Price range */}
                            <div>
                                <h3 className="text-xs font-bold text-gray-900 uppercase tracking-widest mb-3">{t.products.price}</h3>
                                <div className="w-8 h-0.5 bg-red-600 mb-4" />
                                <div className="flex items-center gap-2 mb-3">
                                    <input
                                        type="number"
                                        min={0}
                                        max={priceMax}
                                        value={priceMin}
                                        onChange={e => setPriceMin(Number(e.target.value))}
                                        className="w-full border border-gray-300 rounded-sm px-2 py-1.5 text-sm focus:outline-none focus:border-red-500"
                                        placeholder={t.products.from}
                                    />
                                    <span className="text-gray-400 text-sm">–</span>
                                    <input
                                        type="number"
                                        min={priceMin}
                                        max={MAX_PRICE}
                                        value={priceMax}
                                        onChange={e => setPriceMax(Number(e.target.value))}
                                        className="w-full border border-gray-300 rounded-sm px-2 py-1.5 text-sm focus:outline-none focus:border-red-500"
                                        placeholder={t.products.to}
                                    />
                                </div>
                                <input
                                    type="range"
                                    min={0}
                                    max={MAX_PRICE}
                                    value={priceMax}
                                    onChange={e => setPriceMax(Number(e.target.value))}
                                    className="w-full accent-red-600"
                                />
                                <div className="flex justify-between text-xs text-gray-400 mt-1">
                                    <span>0 €</span>
                                    <span>{MAX_PRICE} €</span>
                                </div>
                            </div>

                            {/* Material */}
                            <div>
                                <h3 className="text-xs font-bold text-gray-900 uppercase tracking-widest mb-3">{t.products.material}</h3>
                                <div className="w-8 h-0.5 bg-red-600 mb-4" />
                                <div className="space-y-2">
                                    {materialOptions.map(({ key, label }) => (
                                        <label key={key} className="flex items-center gap-2.5 cursor-pointer group">
                                            <input
                                                type="checkbox"
                                                checked={materials[key]}
                                                onChange={() => toggleMaterial(key)}
                                                className="w-4 h-4 accent-red-600 cursor-pointer"
                                            />
                                            <span className="text-sm text-gray-600 group-hover:text-red-600 transition-colors">{label}</span>
                                        </label>
                                    ))}
                                </div>
                            </div>

                            {/* Dimensions */}
                            <div>
                                <h3 className="text-xs font-bold text-gray-900 uppercase tracking-widest mb-3">{t.products.dimensions}</h3>
                                <div className="w-8 h-0.5 bg-red-600 mb-4" />

                                <p className="text-xs text-gray-500 uppercase tracking-wide mb-2">{t.products.height}</p>
                                <div className="flex items-center gap-2 mb-3">
                                    <input
                                        type="number" min={0} max={heightMax} value={heightMin}
                                        onChange={e => setHeightMin(Number(e.target.value))}
                                        className="w-full border border-gray-300 rounded-sm px-2 py-1.5 text-sm focus:outline-none focus:border-red-500"
                                        placeholder={t.products.fromDim}
                                    />
                                    <span className="text-gray-400 text-sm">–</span>
                                    <input
                                        type="number" min={heightMin} max={MAX_HEIGHT} value={heightMax}
                                        onChange={e => setHeightMax(Number(e.target.value))}
                                        className="w-full border border-gray-300 rounded-sm px-2 py-1.5 text-sm focus:outline-none focus:border-red-500"
                                        placeholder={t.products.toDim}
                                    />
                                </div>
                                <input type="range" min={0} max={MAX_HEIGHT} value={heightMax}
                                    onChange={e => setHeightMax(Number(e.target.value))}
                                    className="w-full accent-red-600 mb-4"
                                />

                                <p className="text-xs text-gray-500 uppercase tracking-wide mb-2">{t.products.width}</p>
                                <div className="flex items-center gap-2 mb-3">
                                    <input
                                        type="number" min={0} max={widthMax} value={widthMin}
                                        onChange={e => setWidthMin(Number(e.target.value))}
                                        className="w-full border border-gray-300 rounded-sm px-2 py-1.5 text-sm focus:outline-none focus:border-red-500"
                                        placeholder={t.products.fromDim}
                                    />
                                    <span className="text-gray-400 text-sm">–</span>
                                    <input
                                        type="number" min={widthMin} max={MAX_WIDTH} value={widthMax}
                                        onChange={e => setWidthMax(Number(e.target.value))}
                                        className="w-full border border-gray-300 rounded-sm px-2 py-1.5 text-sm focus:outline-none focus:border-red-500"
                                        placeholder={t.products.toDim}
                                    />
                                </div>
                                <input type="range" min={0} max={MAX_WIDTH} value={widthMax}
                                    onChange={e => setWidthMax(Number(e.target.value))}
                                    className="w-full accent-red-600"
                                />
                            </div>

                            {/* Reset */}
                            <button
                                onClick={resetFilters}
                                className="w-full py-2 border border-gray-300 hover:border-red-500 hover:text-red-600 text-gray-500 text-xs font-bold uppercase tracking-widest rounded-sm transition-all duration-150"
                            >
                                {t.products.clearFilters}
                            </button>

                        </aside>

                        {/* ── PRODUCT GRID ── */}
                        <div className="flex-1">
                            <div className="flex items-center justify-between mb-6">
                                <p className="text-sm text-gray-500">
                                    {t.products.showing} <span className="font-semibold text-gray-800">{(page - 1) * PER_PAGE + 1}–{Math.min(page * PER_PAGE, filtered.length)}</span> {t.products.of} <span className="font-semibold text-gray-800">{filtered.length}</span>
                                </p>
                            </div>

                            {filtered.length === 0 ? (
                                <div className="text-center py-20 text-gray-400">
                                    <svg className="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p className="font-semibold text-gray-500">{t.products.noResults}</p>
                                    <p className="text-sm mt-1">{t.products.noResultsHint}</p>
                                </div>
                            ) : (
                                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    {paginated.map(product => (
                                        <div key={product.id} className="group bg-white border border-gray-100 rounded-lg overflow-hidden hover:shadow-xl hover:shadow-gray-200/60 transition-all duration-300 hover:-translate-y-1 flex flex-col">
                                            <a href={`/products/${product.id}`} className="block flex-1">
                                                <div className="relative h-64 overflow-hidden">
                                                    <ProductImage src={product.main_image_url} />
                                                    <div className="absolute top-3 left-3 flex flex-col gap-1">
                                                        {product.badge && (
                                                            <span className="bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-sm uppercase tracking-wide">
                                                                {product.badge}
                                                            </span>
                                                        )}
                                                        {product.on_sale && (
                                                            <span className="bg-orange-500 text-white text-xs font-bold px-2.5 py-1 rounded-sm uppercase tracking-wide">
                                                                {product.sale_percent ? `-${product.sale_percent}%` : 'SALE'}
                                                            </span>
                                                        )}
                                                    </div>
                                                    {product.material && product.material !== null && (
                                                        <span className="absolute top-3 right-3 bg-gray-900/70 text-white text-xs px-2 py-1 rounded-sm">
                                                            {t.product.materials[product.material] ?? product.material}
                                                        </span>
                                                    )}
                                                </div>
                                                <div className="p-4 pb-2">
                                                    {product.h && (
                                                        <p className="text-xs text-gray-400 mb-1">{product.h} × {product.w} cm</p>
                                                    )}
                                                    <h3 className="font-bold text-gray-900 text-sm group-hover:text-red-600 transition-colors leading-snug">
                                                        {lang === 'en' && product.name_en ? product.name_en : product.name}
                                                    </h3>
                                                </div>
                                            </a>
                                            <div className="px-4 pb-4">
                                                {product.on_sale && (product.sale_price || product.sale_percent) ? (
                                                    <div className="mb-3">
                                                        <div className="flex items-center gap-2 mb-0.5">
                                                            <span className="text-sm line-through text-gray-400">{product.price} €</span>
                                                        </div>
                                                        <p className="text-2xl font-black text-red-600">
                                                            {product.sale_price ?? (product.price * (1 - product.sale_percent / 100)).toFixed(2)} <span className="text-sm font-normal text-gray-400">€</span>
                                                        </p>
                                                    </div>
                                                ) : (
                                                    <p className="text-2xl font-black text-red-600 mb-3">
                                                        {product.price} <span className="text-sm font-normal text-gray-400">€</span>
                                                    </p>
                                                )}
                                                <button
                                                    onClick={() => { addItem(product); setCartOpen(true); }}
                                                    className="w-full py-2.5 bg-gray-900 hover:bg-red-600 text-white text-xs font-bold uppercase tracking-widest rounded-sm transition-all duration-200"
                                                >
                                                    {t.btn.addToCart}
                                                </button>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}

                            {/* Pagination */}
                            {totalPages > 1 && (
                                <div className="flex items-center justify-center gap-2 mt-10">
                                    <button
                                        onClick={() => goToPage(page - 1)}
                                        disabled={page === 1}
                                        className="px-3 py-2 text-sm border border-gray-300 rounded-sm hover:border-red-500 hover:text-red-600 disabled:opacity-30 disabled:cursor-not-allowed transition-all"
                                    >
                                        ‹
                                    </button>

                                    {Array.from({ length: totalPages }, (_, i) => i + 1).map(p => (
                                        <button
                                            key={p}
                                            onClick={() => goToPage(p)}
                                            className={`w-9 h-9 text-sm rounded-sm border transition-all ${
                                                p === page
                                                    ? 'bg-red-600 text-white border-red-600 font-bold'
                                                    : 'border-gray-300 text-gray-600 hover:border-red-500 hover:text-red-600'
                                            }`}
                                        >
                                            {p}
                                        </button>
                                    ))}

                                    <button
                                        onClick={() => goToPage(page + 1)}
                                        disabled={page === totalPages}
                                        className="px-3 py-2 text-sm border border-gray-300 rounded-sm hover:border-red-500 hover:text-red-600 disabled:opacity-30 disabled:cursor-not-allowed transition-all"
                                    >
                                        ›
                                    </button>
                                </div>
                            )}
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
