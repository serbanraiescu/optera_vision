import { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { Eye, ArrowUp, MessageSquare, PhoneCall, Bot } from 'lucide-react';
import Header from './components/Header';
import Footer from './components/Footer';
import AcasaView from './components/AcasăView';
import ServiciiView from './components/ServiciiView';
import PortofoliuView from './components/PortofoliuView';
import ConfiguratorView from './components/ConfiguratorView';
import CrmView from './components/CrmView';
import { ViewType, Lead, LeadStatus, LocatieType, ServiciuType } from './types';
import { initialLeads } from './initialData';

export default function App() {
  const [currentView, setView] = useState<ViewType>('home');
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [leads, setLeads] = useState<Lead[]>([]);
  const [showScrollTop, setShowScrollTop] = useState(false);

  // Initialize leads from localStorage or static data
  useEffect(() => {
    const savedLeads = localStorage.getItem('optera_vision_leads_db');
    if (savedLeads) {
      try {
        setLeads(JSON.parse(savedLeads));
      } catch (e) {
        setLeads(initialLeads);
      }
    } else {
      setLeads(initialLeads);
      localStorage.setItem('optera_vision_leads_db', JSON.stringify(initialLeads));
    }
  }, []);

  // Sync scroll positions
  useEffect(() => {
    const handleScroll = () => {
      if (window.scrollY > 400) {
        setShowScrollTop(true);
      } else {
        setShowScrollTop(false);
      }
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  // Switch pages smoothly & scroll to top
  const handleSetView = (view: ViewType) => {
    setView(view);
    setMobileMenuOpen(false);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  // Pricing algorithm for automated lead estimation values (dynamic details!)
  const calculateEstimatedQuotePrice = (camere: number, locationType: LocatieType): number => {
    let basePrice = camere * 650;
    switch (locationType) {
      case 'industrial':
        basePrice += 2500;
        break;
      case 'comercial':
        basePrice += 1500;
        break;
      case 'public':
        basePrice += 1000;
        break;
      default:
        basePrice += 500;
        break;
    }
    return basePrice;
  };

  // Add lead submitted in front-end
  const handleAddLead = (leadData: {
    nume: string;
    telefon: string;
    email: string;
    localitate: string;
    tipLocatie: LocatieType;
    tipServiciu: ServiciuType;
    camere: number;
    mesaj: string;
  }) => {
    const calculatedValue = calculateEstimatedQuotePrice(leadData.camere, leadData.tipLocatie);
    
    // Create new formatted lead
    const newLead: Lead = {
      id: `lead_${Date.now()}`,
      nume: leadData.nume,
      telefon: leadData.telefon,
      email: leadData.email,
      localitate: leadData.localitate,
      tipLocatie: leadData.tipLocatie,
      tipServiciu: leadData.tipServiciu,
      camere: leadData.camere,
      mesaj: leadData.mesaj,
      data: 'Azi, ' + new Date().toLocaleTimeString('ro-RO', { hour: '2-digit', minute: '2-digit' }),
      status: 'nou',
      valoare: calculatedValue
    };

    const updated = [newLead, ...leads];
    setLeads(updated);
    localStorage.setItem('optera_vision_leads_db', JSON.stringify(updated));
  };

  // Manual addition from CRM panel
  const handleAddManualLead = (leadData: Omit<Lead, 'id' | 'data'>) => {
    const newLead: Lead = {
      ...leadData,
      id: `lead_${Date.now()}`,
      data: 'Azi, ' + new Date().toLocaleTimeString('ro-RO', { hour: '2-digit', minute: '2-digit' })
    };
    const updated = [newLead, ...leads];
    setLeads(updated);
    localStorage.setItem('optera_vision_leads_db', JSON.stringify(updated));
  };

  // Change lead status
  const handleUpdateLeadStatus = (id: string, newStatus: LeadStatus) => {
    const updated = leads.map(l => {
      if (l.id === id) {
        return { 
          ...l, 
          status: newStatus,
          // Assign progress automatically if transitioned to "lucru"
          progress: newStatus === 'lucru' ? (l.progress || 15) : undefined 
        };
      }
      return l;
    });
    setLeads(updated);
    localStorage.setItem('optera_vision_leads_db', JSON.stringify(updated));
  };

  // Edit lead value manually
  const handleUpdateLeadValoare = (id: string, valoare: number) => {
    const updated = leads.map(l => {
      if (l.id === id) {
        return { ...l, valoare };
      }
      return l;
    });
    setLeads(updated);
    localStorage.setItem('optera_vision_leads_db', JSON.stringify(updated));
  };

  // Delete lead
  const handleDeleteLead = (id: string) => {
    const updated = leads.filter(l => l.id !== id);
    setLeads(updated);
    localStorage.setItem('optera_vision_leads_db', JSON.stringify(updated));
  };

  return (
    <div className="min-h-screen bg-[#f8f9fa] flex flex-col justify-between font-sans selection:bg-[#a9d0b6]/50 select-none">
      
      {/* Premium Header navigations */}
      <Header 
        currentView={currentView} 
        setView={handleSetView} 
        mobileMenuOpen={mobileMenuOpen}
        setMobileMenuOpen={setMobileMenuOpen}
      />

      {/* Main Container Views Switching */}
      <div className="flex-grow pt-16">
        <AnimatePresence mode="wait">
          <motion.div
            key={currentView}
            initial={{ opacity: 0, scale: 0.99 }}
            animate={{ opacity: 1, scale: 1 }}
            exit={{ opacity: 0, scale: 0.99 }}
            transition={{ duration: 0.35, ease: 'easeInOut' }}
            className="w-full flex flex-col"
          >
            {currentView === 'home' && (
              <AcasaView setView={handleSetView} />
            )}

            {currentView === 'servicii' && (
              <ServiciiView setView={handleSetView} />
            )}

            {currentView === 'portofoliu' && (
              <PortofoliuView setView={handleSetView} />
            )}

            {currentView === 'oferta' && (
              <ConfiguratorView onAddLead={handleAddLead} />
            )}

            {currentView === 'crm' && (
              <CrmView 
                leads={leads}
                onUpdateLeadStatus={handleUpdateLeadStatus}
                onUpdateLeadValoare={handleUpdateLeadValoare}
                onDeleteLead={handleDeleteLead}
                onAddManualLead={handleAddManualLead}
              />
            )}
          </motion.div>
        </AnimatePresence>
      </div>

      {/* Footer component */}
      <Footer setView={handleSetView} />

      {/* Float Buttons (Bucovina local indicator and mobile rapid actions) */}
      <div className="fixed bottom-6 right-6 z-40 flex flex-col gap-3">
        {showScrollTop && (
          <button 
            onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
            className="p-3.5 bg-white text-[#022717] rounded-full shadow-lg border border-gray-150 hover:bg-[#022717] hover:text-white active:scale-95 transition-all text-sm font-bold"
            title="Sari la început"
          >
            <ArrowUp size={18} />
          </button>
        )}
        <a 
          href="https://wa.me/40745123456" 
          target="_blank" 
          rel="noreferrer"
          className="p-3.5 bg-[#25D366] text-white rounded-full shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center justify-center"
          title="Scrie rapid pe WhatsApp"
        >
          <MessageSquare size={18} />
        </a>
      </div>

      {/* Bottom Floating Bar (Mobile Navigation Shortcut helpers) */}
      <div className="md:hidden fixed bottom-0 left-0 right-0 py-3 px-6 bg-white border-t border-gray-150 shadow-2xl flex items-center justify-around z-40">
        <button 
          onClick={() => handleSetView('oferta')}
          className="flex-1 max-w-[130px] flex items-center justify-center gap-1.5 py-2.5 px-3 bg-[#022717] text-white font-bold text-[10px] tracking-wider rounded-xl transition-all uppercase"
        >
          Solicită Ofertă
        </button>
        <a 
          href="https://wa.me/40745123456" 
          target="_blank" 
          rel="noreferrer"
          className="flex-1 max-w-[130px] flex items-center justify-center gap-1.5 py-2.5 px-3 border border-[#022717]/30 text-[#022717] font-bold text-[10px] tracking-wider rounded-xl transition-all uppercase bg-white"
        >
          <MessageSquare size={12} className="text-[#25D366]" />
          WhatsApp
        </a>
      </div>
    </div>
  );
}
