import { Eye, Shield, Award, MapPin, Mail, Phone } from 'lucide-react';
import { ViewType } from '../types';

interface FooterProps {
  setView: (view: ViewType) => void;
}

export default function Footer({ setView }: FooterProps) {
  return (
    <footer className="w-full bg-[#f8f9fa] border-t border-gray-200 py-16 px-6 md:px-16 flex flex-col items-start gap-8 max-w-[1280px] mx-auto">
      <div className="w-full flex flex-col md:flex-row justify-between items-start gap-12 mb-12">
        <div className="max-w-sm">
          <div 
            onClick={() => setView('home')}
            className="flex items-center gap-4 mb-6 cursor-pointer hover:opacity-90 transition-opacity"
          >
            <div className="w-8 h-8 rounded-lg bg-[#1a3d2b] flex items-center justify-center text-white">
              <Eye size={18} className="text-[#a9d0b6]" />
            </div>
            <span className="font-sans font-bold text-lg text-[#022717]">Optera Vision</span>
          </div>
          <p className="font-sans text-sm text-[#545f73] leading-relaxed mb-6">
            Partenerul tău local din inima Bucovinei pentru sisteme de supraveghere video profesionale, securitate inteligentă și automatizări rezidențiale sau industriale de top.
          </p>
          <div className="flex flex-col gap-2 text-xs text-[#545f73]">
            <span className="flex items-center gap-2"><MapPin size={14} className="text-[#022717]" /> Câmpulung Moldovenesc, Suceava, Bucovina</span>
            <span className="flex items-center gap-2"><Mail size={14} className="text-[#022717]" /> contact@opteravision.ro</span>
            <span className="flex items-center gap-2"><Phone size={14} className="text-[#022717]" /> +40 745 123 456</span>
          </div>
        </div>

        <div className="grid grid-cols-2 md:grid-cols-3 gap-12">
          <div className="flex flex-col gap-4">
            <h4 className="font-sans italic font-bold text-xs tracking-wider text-[#022717] uppercase">Link-uri</h4>
            <button onClick={() => setView('home')} className="text-left text-xs font-medium text-[#545f73] hover:text-[#022717] transition-colors">Acasă</button>
            <button onClick={() => setView('servicii')} className="text-left text-xs font-medium text-[#545f73] hover:text-[#022717] transition-colors">Servicii Detailate</button>
            <button onClick={() => setView('portofoliu')} className="text-left text-xs font-medium text-[#545f73] hover:text-[#022717] transition-colors">Portofoliu Proiecte</button>
          </div>
          <div className="flex flex-col gap-4">
            <h4 className="font-sans italic font-bold text-xs tracking-wider text-[#022717] uppercase">Utile</h4>
            <a href="#" className="text-xs font-medium text-[#545f73] hover:text-[#022717] transition-colors">Termeni și condiții</a>
            <a href="#" className="text-xs font-medium text-[#545f73] hover:text-[#022717] transition-colors">Politică GDPR</a>
            <a href="#" className="text-xs font-medium text-[#545f73] hover:text-[#022717] transition-colors">Cookies Policy</a>
            <a href="#" className="text-xs font-medium text-[#545f73] hover:text-[#022717] transition-colors">ANPC</a>
          </div>
          <div className="flex flex-col gap-4">
            <h4 className="font-sans italic font-bold text-xs tracking-wider text-[#022717] uppercase">Locație</h4>
            <p className="text-xs font-medium text-[#545f73] leading-relaxed">
              Câmpulung Moldovenesc<br />
              Suceava, Bucovina<br />
              România
            </p>
          </div>
        </div>
      </div>

      <div className="w-full pt-8 border-t border-gray-200/60 flex flex-col md:flex-row justify-between items-center gap-4">
        <p className="font-sans text-xs text-[#545f73] text-center md:text-left">
          &copy; 2026 Optera Vision. Securitate și Automatizări în Câmpulung Moldovenesc, Suceava, Bucovina. Toate drepturile rezervate.
        </p>
        <div className="flex gap-4 items-center text-xs font-medium text-[#545f73]">
          <span className="flex items-center gap-1"><Shield size={14} className="text-[#022717]" /> Standard Profesional IP67</span>
          <span className="flex items-center gap-1"><Award size={14} className="text-[#022717]" /> Certificat ANRE / IGPR</span>
        </div>
      </div>
    </footer>
  );
}
