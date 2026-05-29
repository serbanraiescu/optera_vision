import { Eye, Menu, X, Landmark, ShieldCheck } from 'lucide-react';
import { ViewType } from '../types';

interface HeaderProps {
  currentView: ViewType;
  setView: (view: ViewType) => void;
  mobileMenuOpen: boolean;
  setMobileMenuOpen: (open: boolean) => void;
}

export default function Header({ currentView, setView, mobileMenuOpen, setMobileMenuOpen }: HeaderProps) {
  const tabs = [
    { id: 'home', label: 'ACASĂ' },
    { id: 'servicii', label: 'SERVICII' },
    { id: 'portofoliu', label: 'PORTOFOLIU' },
    { id: 'oferta', label: 'SOLICITĂ OFERTĂ' },
    { id: 'crm', label: 'ADMIN CRM' }
  ] as const;

  return (
    <header className="fixed top-0 w-full z-50 bg-[#f8f9fa] shadow-sm border-b border-gray-200/50 backdrop-blur-md transition-all duration-300">
      <div className="flex items-center justify-between px-6 md:px-16 h-16 w-full max-w-[1280px] mx-auto">
        {/* Logo */}
        <div 
          onClick={() => setView('home')} 
          className="flex items-center gap-3 cursor-pointer group hover:opacity-90 transition-opacity"
        >
          {/* Custom logo representing Optera Vision */}
          <div className="w-10 h-10 rounded-lg bg-[#1a3d2b] flex items-center justify-center text-white transition-transform group-hover:scale-105 duration-300">
            <Eye size={22} className="text-[#a9d0b6]" />
          </div>
          <div className="flex flex-col">
            <span className="font-bold text-lg md:text-xl tracking-tight text-[#022717] leading-tight">
              Optera Vision
            </span>
            <span className="text-[9px] tracking-[0.15em] text-[#545f73] font-semibold -mt-0.5">
              SUPRAVEGHERE &amp; SECURITATE
            </span>
          </div>
        </div>

        {/* Desktop Navigation Links */}
        <nav className="hidden md:flex items-center gap-8">
          {tabs.map((tab) => (
            <button
              key={tab.id}
              onClick={() => {
                setView(tab.id);
                setMobileMenuOpen(false);
              }}
              className={`font-sans font-bold text-xs tracking-wider transition-colors py-2 px-1 relative ${
                currentView === tab.id
                  ? 'text-[#022717]'
                  : 'text-[#545f73] hover:text-[#022717]'
              }`}
            >
              {tab.label}
              {currentView === tab.id && (
                <span className="absolute bottom-0 left-0 right-0 h-0.5 bg-[#12422c] rounded-full" />
              )}
            </button>
          ))}
          <button 
            onClick={() => setView('oferta')}
            className="bg-[#022717] text-white px-5 py-2 rounded-lg font-bold text-xs tracking-wider hover:bg-[#1a3d2b] active:scale-95 transition-all"
          >
            CONTACT
          </button>
        </nav>

        {/* Mobile menu button */}
        <button 
          onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
          className="p-2 text-[#022717] md:hidden cursor-pointer rounded-lg hover:bg-gray-100 transition-colors"
        >
          {mobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
        </button>
      </div>

      {/* Mobile Menu dropdown */}
      {mobileMenuOpen && (
        <div className="md:hidden absolute top-16 left-0 right-0 bg-white shadow-lg border-b border-gray-100 flex flex-col p-4 gap-2 z-50">
          {tabs.map((tab) => (
            <button
              key={tab.id}
              onClick={() => {
                setView(tab.id);
                setMobileMenuOpen(false);
              }}
              className={`w-full text-left py-3 px-4 rounded-lg font-sans font-bold text-xs tracking-wider transition-colors ${
                currentView === tab.id
                  ? 'bg-emerald-50 text-[#022717]'
                  : 'text-[#545f73] hover:bg-gray-50'
              }`}
            >
              {tab.label}
            </button>
          ))}
          <button
            onClick={() => {
              setView('oferta');
              setMobileMenuOpen(false);
            }}
            className="w-full text-center py-3 px-4 bg-[#022717] text-white font-bold text-xs rounded-lg mt-2 hover:bg-[#1a3d2b]"
          >
            CONF_SOLICITĂ OFERTĂ
          </button>
        </div>
      )}
    </header>
  );
}
