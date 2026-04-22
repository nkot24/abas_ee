import { useLang } from '../i18n';

export default function OrderCancelled() {
    const { t } = useLang();

    return (
        <div className="min-h-screen bg-gray-50 flex items-center justify-center px-6" style={{ fontFamily: "'Segoe UI', sans-serif" }}>
            <div className="text-center max-w-md">
                <div className="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg className="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h1 className="text-3xl font-black text-gray-900 mb-3">{t.orderCancelled.title}</h1>
                <p className="text-gray-500 mb-8">{t.orderCancelled.desc}</p>
                <div className="flex justify-center gap-4">
                    <a href="/uzsakymas" className="inline-block px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-sm uppercase tracking-widest rounded-md transition-colors">
                        {t.orderCancelled.tryAgain}
                    </a>
                    <a href="/" className="inline-block px-8 py-3 border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-bold text-sm uppercase tracking-widest rounded-md transition-colors">
                        {t.btn.backHome}
                    </a>
                </div>
            </div>
        </div>
    );
}
