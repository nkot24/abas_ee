import { useEffect, useState } from 'react';
import { useLang } from './i18n';

export default function CartDrawer({ open, onClose, items, removeItem, updateQty, total }) {
    const { t, lang } = useLang();
    const [enNames, setEnNames] = useState({});

    useEffect(() => {
        document.body.style.overflow = open ? 'hidden' : '';
        return () => { document.body.style.overflow = ''; };
    }, [open]);

    useEffect(() => {
        if (lang !== 'en' || items.length === 0) return;
        const missing = items.filter(i => !i.name_en).map(i => i.id);
        if (missing.length === 0) return;
        fetch(`/api/product-names?ids=${missing.join(',')}`)
            .then(r => r.json())
            .then(data => setEnNames(prev => ({ ...prev, ...data })))
            .catch(() => {});
    }, [lang, items]);

    return (
        <>
            {/* Backdrop */}
            {open && (
                <div
                    className="fixed inset-x-0 bottom-0 top-24 bg-black/40 z-40"
                    onClick={onClose}
                />
            )}

            {/* Drawer */}
            <div className={`fixed top-24 right-0 h-[calc(100vh-6rem)] w-full max-w-sm bg-white z-50 shadow-2xl flex flex-col transition-transform duration-300 ${open ? 'translate-x-0' : 'translate-x-full'}`}>
                {/* Header */}
                <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h2 className="text-lg font-bold uppercase tracking-widest text-gray-900">{t.cart.title}</h2>
                    <button onClick={onClose} className="text-gray-500 hover:text-gray-900 transition-colors">
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {/* Items */}
                <div className="flex-1 overflow-y-auto px-6 py-4 space-y-4">
                    {items.length === 0 ? (
                        <div className="flex flex-col items-center justify-center h-full text-gray-400 gap-3">
                            <svg className="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p className="text-sm font-medium">{t.cart.empty}</p>
                        </div>
                    ) : items.map(item => (
                        <div key={item.id} className="flex gap-3">
                            {/* Image */}
                            <div className="w-20 h-20 flex-shrink-0 bg-gray-100 overflow-hidden rounded-sm">
                                {item.image
                                    ? <img src={item.image} alt={item.name} className="w-full h-full object-cover" />
                                    : <div className="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                      </div>
                                }
                            </div>

                            {/* Info */}
                            <div className="flex-1 min-w-0">
                                <p className="text-sm font-semibold text-gray-900 leading-tight line-clamp-2">{lang === 'en' ? (item.name_en || enNames[item.id] || item.name) : item.name}</p>
                                <p className="text-red-600 font-bold text-sm mt-1">{Number(item.price).toFixed(2)} €</p>

                                {/* Qty controls */}
                                <div className="flex items-center gap-2 mt-2">
                                    <div className="flex items-center border border-gray-300 rounded-sm">
                                        <button
                                            onClick={() => updateQty(item.id, item.qty - 1)}
                                            className="w-7 h-7 text-gray-600 hover:text-red-600 font-bold transition-colors"
                                        >−</button>
                                        <span className="w-7 text-center text-sm font-semibold">{item.qty}</span>
                                        <button
                                            onClick={() => updateQty(item.id, item.qty + 1)}
                                            className="w-7 h-7 text-gray-600 hover:text-red-600 font-bold transition-colors"
                                        >+</button>
                                    </div>
                                    <button
                                        onClick={() => removeItem(item.id)}
                                        className="text-gray-400 hover:text-red-600 transition-colors ml-auto"
                                    >
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Footer */}
                {items.length > 0 && (
                    <div className="border-t border-gray-200 px-6 py-5 space-y-4">
                        <div className="flex justify-between text-base font-bold text-gray-900">
                            <span>{t.cart.total}</span>
                            <span>{total.toFixed(2)} €</span>
                        </div>
                        <a
                            href="/uzsakymas"
                            className="block w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-sm uppercase tracking-widest text-center rounded-sm transition-colors"
                        >
                            {t.cart.checkout}
                        </a>
                    </div>
                )}
            </div>
        </>
    );
}
