import { useState, useEffect } from 'react';

const STORAGE_KEY = 'abas_cart';

function loadCart() {
    try {
        return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    } catch {
        return [];
    }
}

export function useCart() {
    const [items, setItems] = useState(loadCart);

    useEffect(() => {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
    }, [items]);

    const addItem = (product, qty = 1) => {
        setItems(prev => {
            const existing = prev.find(i => i.id === product.id);
            if (existing) {
                return prev.map(i => i.id === product.id ? { ...i, qty: i.qty + qty } : i);
            }
            const effectivePrice = product.on_sale && product.sale_price
                ? parseFloat(product.sale_price)
                : product.on_sale && product.sale_percent
                    ? parseFloat((product.price * (1 - product.sale_percent / 100)).toFixed(2))
                    : product.price;
            return [...prev, {
                id:      product.id,
                name:    product.name,
                name_en: product.name_en ?? null,
                price:   effectivePrice,
                image:   product.main_image_url ?? null,
                qty,
            }];
        });
    };

    const removeItem = (id) => setItems(prev => prev.filter(i => i.id !== id));

    const updateQty = (id, qty) => {
        if (qty < 1) { removeItem(id); return; }
        setItems(prev => prev.map(i => i.id === id ? { ...i, qty } : i));
    };

    const clearCart = () => setItems([]);

    const count = items.reduce((s, i) => s + i.qty, 0);
    const total = items.reduce((s, i) => s + i.price * i.qty, 0);

    return { items, addItem, removeItem, updateQty, clearCart, count, total };
}
