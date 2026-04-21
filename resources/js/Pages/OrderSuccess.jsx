export default function OrderSuccess({ order }) {
    return (
        <div className="min-h-screen bg-gray-50 flex items-center justify-center px-6" style={{ fontFamily: "'Segoe UI', sans-serif" }}>
            <div className="text-center max-w-md">
                <div className="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg className="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 className="text-3xl font-black text-gray-900 mb-2">Mokėjimas sėkmingas!</h1>
                <p className="text-gray-500 mb-1">Užsakymo nr. <span className="font-bold text-gray-800">#{order.id}</span></p>
                <p className="text-gray-500 mb-1">Suma: <span className="font-bold text-gray-800">{Number(order.total).toFixed(2)} €</span></p>
                <p className="text-gray-400 text-sm mt-4 mb-8">Patvirtinimas bus išsiųstas į <strong>{order.email}</strong>.</p>
                <a href="/" className="inline-block px-10 py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold text-sm uppercase tracking-widest rounded-md transition-colors">
                    Grįžti į pradžią
                </a>
            </div>
        </div>
    );
}
