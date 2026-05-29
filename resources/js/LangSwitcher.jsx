import { useLang } from './i18n';

export default function LangSwitcher() {
    const { lang, switchLang } = useLang();

    return (
        <div className="flex items-center gap-1 text-xs font-bold tracking-widest">
            <button
                onClick={() => switchLang('et')}
                className={`transition-colors ${lang === 'et' ? 'text-red-600' : 'text-gray-400 hover:text-gray-700'}`}
            >
                ET
            </button>
            <span className="text-gray-300 select-none">|</span>
            <button
                onClick={() => switchLang('en')}
                className={`transition-colors ${lang === 'en' ? 'text-red-600' : 'text-gray-400 hover:text-gray-700'}`}
            >
                EN
            </button>
        </div>
    );
}
