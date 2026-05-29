import { useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { ArrowRight, MapPin, Check, X, ShieldAlert, Award, MessageSquare } from 'lucide-react';
import { initialProjects } from '../initialData';
import { Project, ViewType } from '../types';

interface PortofoliuViewProps {
  setView: (view: ViewType) => void;
}

export default function PortofoliuView({ setView }: PortofoliuViewProps) {
  const [selectedProject, setSelectedProject] = useState<Project | null>(null);

  return (
    <div className="w-full">
      {/* Hero Section */}
      <section className="px-6 md:px-16 max-w-[1280px] mx-auto pt-16 mb-16">
        <motion.div 
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
          className="max-w-3xl"
        >
          <span className="font-bold text-xs tracking-wider text-[#022717] uppercase mb-4 block">
            PORTOFOLIU PROIECTE
          </span>
          <h2 className="font-extrabold text-3xl md:text-5xl text-[#022717] tracking-tight mb-6">
            Soluții de securitate și automatizare implementate cu precizie
          </h2>
          <p className="font-sans text-base md:text-lg text-[#545f73]">
            Descoperă lucrările noastre de referință efectuate în Câmpulung Moldovenesc, Suceava și în regiunea Bucovinei. De la case rezidențiale premium până la hub-uri logistice industriale complexe.
          </p>
        </motion.div>
      </section>

      {/* Projects Bento/Asymmetric Grid */}
      <section className="px-6 md:px-16 max-w-[1280px] mx-auto mb-24">
        <div className="grid grid-cols-1 md:grid-cols-12 gap-6">
          
          {/* Project 1: Locuință privată (Large col_8) */}
          <motion.div 
            initial={{ opacity: 0, y: 25 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            className="md:col-span-8 group overflow-hidden bg-white rounded-3xl shadow-sm border border-gray-150 flex flex-col md:flex-row hover:shadow-md hover:border-gray-200 transition-all"
          >
            <div className="md:w-3/5 overflow-hidden relative min-h-[250px] md:min-h-[350px]">
              <img 
                alt="Locuință privată" 
                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-750 ease-out"
                src={initialProjects[0].imagine}
              />
              <div className="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-black/20 to-transparent"></div>
            </div>
            <div className="md:w-2/5 p-8 flex flex-col justify-between">
              <div>
                <span className="font-bold text-[10px] tracking-widest text-[#545f73] mb-2 block uppercase">
                  {initialProjects[0].categorie}
                </span>
                <h3 className="text-xl font-bold text-[#022717] mb-3">{initialProjects[0].titlu}</h3>
                <p className="font-sans text-xs text-[#545f73] leading-relaxed mb-6">
                  {initialProjects[0].descriere}
                </p>
              </div>
              <button 
                onClick={() => setSelectedProject(initialProjects[0])}
                className="self-start flex items-center gap-2 px-5 py-3 bg-[#022717] text-white rounded-xl font-bold text-xs tracking-wider hover:bg-[#1a3d2b] transition-all cursor-pointer"
              >
                Vezi detalii 
                <ArrowRight size={14} />
              </button>
            </div>
          </motion.div>

          {/* Project 2: Birou / Hală / Depozit ( col_4) */}
          <motion.div 
            initial={{ opacity: 0, y: 25 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.1 }}
            className="md:col-span-4 group overflow-hidden bg-white rounded-3xl shadow-sm border border-gray-150 flex flex-col hover:shadow-md hover:border-gray-200 transition-all"
          >
            <div className="h-48 overflow-hidden relative">
              <img 
                alt="Birou / hală / depozit" 
                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-750 ease-out"
                src={initialProjects[1].imagine}
              />
            </div>
            <div className="p-8 flex flex-col justify-between flex-grow">
              <div>
                <span className="font-bold text-[10px] tracking-widest text-[#545f73] mb-2 block uppercase">
                  {initialProjects[1].categorie}
                </span>
                <h3 className="text-lg font-bold text-[#022717] mb-3">{initialProjects[1].titlu}</h3>
                <p className="font-sans text-xs text-[#545f73] leading-relaxed mb-6">
                  {initialProjects[1].descriere}
                </p>
              </div>
              <button 
                onClick={() => setSelectedProject(initialProjects[1])}
                className="self-start flex items-center gap-1.5 text-[#022717] border border-[#022717] px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-[#022717]/5 transition-all cursor-pointer"
              >
                Vezi detalii 
                <ArrowRight size={12} />
              </button>
            </div>
          </motion.div>

          {/* Project 3: Spațiu comercial */}
          <motion.div 
            initial={{ opacity: 0, y: 25 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="md:col-span-6 group overflow-hidden bg-white rounded-3xl shadow-sm border border-gray-150 flex flex-col md:flex-row hover:shadow-md hover:border-gray-200 transition-all"
          >
            <div className="md:w-1/2 overflow-hidden relative min-h-[220px]">
              <img 
                alt="Spațiu comercial" 
                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-750 ease-out"
                src={initialProjects[2].imagine}
              />
            </div>
            <div className="md:w-1/2 p-8 flex flex-col justify-between">
              <div>
                <span className="font-bold text-[10px] tracking-widest text-[#545f73] mb-2 block uppercase">
                  {initialProjects[2].categorie}
                </span>
                <h3 className="text-lg font-bold text-[#022717] mb-3">{initialProjects[2].titlu}</h3>
                <p className="font-sans text-xs text-[#545f73] leading-relaxed mb-6">
                  {initialProjects[2].descriere}
                </p>
              </div>
              <button 
                onClick={() => setSelectedProject(initialProjects[2])}
                className="self-start flex items-center gap-1.5 text-[#022717] border border-[#022717] px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-[#022717]/5 transition-all cursor-pointer"
              >
                Vezi detalii 
                <ArrowRight size={12} />
              </button>
            </div>
          </motion.div>

          {/* Project 4: Pensiune */}
          <motion.div 
            initial={{ opacity: 0, y: 25 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.3 }}
            className="md:col-span-6 group overflow-hidden bg-white rounded-3xl shadow-sm border border-gray-150 flex flex-col md:flex-row hover:shadow-md hover:border-gray-200 transition-all"
          >
            <div className="md:w-1/2 overflow-hidden relative min-h-[220px]">
              <img 
                alt="Pensiune / unitate turistică" 
                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-750 ease-out"
                src={initialProjects[3].imagine}
              />
            </div>
            <div className="md:w-1/2 p-8 flex flex-col justify-between">
              <div>
                <span className="font-bold text-[10px] tracking-widest text-[#545f73] mb-2 block uppercase">
                  {initialProjects[3].categorie}
                </span>
                <h3 className="text-lg font-bold text-[#022717] mb-3">{initialProjects[3].titlu}</h3>
                <p className="font-sans text-xs text-[#545f73] leading-relaxed mb-6">
                  {initialProjects[3].descriere}
                </p>
              </div>
              <button 
                onClick={() => setSelectedProject(initialProjects[3])}
                className="self-start flex items-center gap-1.5 text-[#022717] border border-[#022717] px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-[#022717]/5 transition-all cursor-pointer"
              >
                Vezi detalii 
                <ArrowRight size={12} />
              </button>
            </div>
          </motion.div>
        </div>
      </section>

      {/* Interactive Project Details Modal */}
      <AnimatePresence>
        {selectedProject && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <motion.div 
              initial={{ opacity: 0, scale: 0.95, y: 20 }}
              animate={{ opacity: 1, scale: 1, y: 0 }}
              exit={{ opacity: 0, scale: 0.95, y: 20 }}
              transition={{ duration: 0.3 }}
              className="relative bg-white w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl border border-gray-100 max-h-[90vh] flex flex-col"
            >
              {/* Image banner */}
              <div className="relative h-60 md:h-72 w-full shrink-0 select-none">
                <img 
                  alt={selectedProject.titlu} 
                  className="w-full h-full object-cover"
                  src={selectedProject.imagine}
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div className="absolute bottom-6 left-6 text-white text-left">
                  <span className="text-[10px] font-bold tracking-widest uppercase bg-emerald-700/80 px-2.5 py-1 rounded-full">{selectedProject.categorie}</span>
                  <h3 className="text-2xl font-bold text-white mt-2">{selectedProject.titlu}</h3>
                </div>
                
                {/* Close button */}
                <button 
                  onClick={() => setSelectedProject(null)}
                  className="absolute top-4 right-4 bg-black/30 backdrop-blur-md text-white p-2 rounded-full hover:bg-black/50 transition-colors"
                >
                  <X size={20} />
                </button>
              </div>

              {/* Modal Body (Scrollable) */}
              <div className="p-8 overflow-y-auto space-y-6 text-left">
                <div className="flex flex-wrap gap-[#gutter] text-xs text-[#545f73] border-b border-gray-100 pb-4">
                  <div className="flex items-center gap-1.5">
                    <MapPin size={15} className="text-[#022717]" />
                    <span><strong>Locație: </strong> {selectedProject.locatie}</span>
                  </div>
                  <div>
                    <span><strong>Număr camere active: </strong> {selectedProject.camereInstalate} unități</span>
                  </div>
                </div>

                <div>
                  <h4 className="font-bold text-[#022717] text-sm uppercase tracking-wider mb-2">Despre Proiect</h4>
                  <p className="font-sans text-xs md:text-sm text-[#545f73] leading-relaxed">
                    {selectedProject.detaliiComplete}
                  </p>
                </div>

                <div>
                  <h4 className="font-bold text-[#022717] text-sm uppercase tracking-wider mb-3">Echipamente și Sisteme Instalate</h4>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    {selectedProject.sistemeFolosite.map((item, index) => (
                      <div key={index} className="flex items-center gap-2 bg-[#f8f9fa] py-2 px-3 rounded-xl border border-gray-100 text-[#022717] font-semibold">
                        <Check size={14} className="text-emerald-600" />
                        <span>{item}</span>
                      </div>
                    ))}
                  </div>
                </div>
              </div>

              <div className="border-t border-gray-100 p-6 bg-[#f8f9fa] flex gap-4 shrink-0">
                <button 
                  onClick={() => {
                    setSelectedProject(null);
                    setView('oferta');
                  }}
                  className="flex-1 bg-[#022717] text-white py-3.5 rounded-xl font-bold text-xs tracking-wider hover:bg-[#1a3d2b] transition-all cursor-pointer text-center"
                >
                  SOLICITĂ OFERTĂ SIMILARĂ
                </button>
                <a 
                  href="https://wa.me/40745123456"
                  target="_blank"
                  rel="noreferrer"
                  className="bg-[#25D366] text-white py-3.5 px-6 rounded-xl font-bold text-xs tracking-wider hover:opacity-95 transition-all text-center flex items-center justify-center gap-1.5"
                >
                  <span>WhatsApp</span>
                </a>
              </div>
            </motion.div>
          </div>
        )}
      </AnimatePresence>

      {/* CTA Section */}
      <section className="px-6 md:px-16 max-w-[1280px] mx-auto mb-24">
        <div className="bg-[#022717] rounded-3xl p-8 md:p-16 text-center text-white relative shadow-lg overflow-hidden">
          <div className="relative z-10 max-w-2xl mx-auto">
            <h2 className="text-2xl md:text-3xl font-extrabold mb-4">Ai un proiect similar în plan?</h2>
            <p className="text-sm md:text-base text-[#a9d0b6] mb-8 max-w-xl mx-auto">
              Contactează-ne astăzi pentru o evaluare tehnică complet gratuită la fața locului și o soluție de top personalizată bugetului și nevoilor tale de securitate.
            </p>
            <div className="flex flex-col sm:flex-row justify-center gap-4">
              <button 
                onClick={() => setView('oferta')}
                className="bg-[#f8f9fa] text-[#022717] px-8 py-4 rounded-xl font-bold hover:bg-white active:scale-95 text-xs tracking-wider transition-all cursor-pointer"
              >
                Solicită ofertă gratuită
              </button>
              <a 
                href="https://wa.me/40745123456"
                target="_blank"
                rel="noreferrer"
                className="cursor-pointer border border-[#a9d0b6]/30 text-white px-8 py-4 rounded-xl font-bold hover:bg-white/10 active:scale-95 text-xs tracking-wider transition-all flex items-center justify-center gap-2"
              >
                <MessageSquare size={16} />
                Contactează-ne pe WhatsApp
              </a>
            </div>
          </div>
          {/* circle green light glow indicator */}
          <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
        </div>
      </section>
    </div>
  );
}
