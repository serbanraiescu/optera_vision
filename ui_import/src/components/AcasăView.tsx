import { motion } from 'motion/react';
import { ArrowRight, Drill, Smartphone, ShieldAlert, Zap, MessageSquare, Mail, ShieldCheck, HelpCircle } from 'lucide-react';
import { ViewType } from '../types';

interface AcasaViewProps {
  setView: (view: ViewType) => void;
}

export default function AcasaView({ setView }: AcasaViewProps) {
  return (
    <div className="w-full">
      {/* Hero Section */}
      <section className="relative overflow-hidden min-h-[80vh] flex items-center px-6 md:px-16 py-20 bg-gradient-to-br from-[#f8f9fa] via-white to-[#a9d0b6]/10">
        <div className="max-w-[1280px] mx-auto w-full grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
          <motion.div 
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            className="z-10"
          >
            <span className="inline-block py-1.5 px-3 bg-[#1a3d2b] text-[#a9d0b6] rounded-full font-bold text-[10px] tracking-wider mb-6">
              CAMERE SUPRAVEGHERE &amp; AUTOMATIZĂRI
            </span>
            <h1 className="font-extrabold text-4xl md:text-5xl lg:text-5xl text-[#022717] tracking-tight leading-tight mb-6">
              Sisteme de supraveghere video pentru locuințe și afaceri
            </h1>
            <p className="font-sans text-base md:text-lg text-[#545f73] leading-relaxed mb-10 max-w-lg">
              Montaj curat, configurare completă și acces de pe telefon pentru clienți din Câmpulung Moldovenesc și zonele apropiate din Bucovina.
            </p>
            <div className="flex flex-col sm:flex-row gap-4">
              <button 
                onClick={() => setView('oferta')}
                className="bg-[#022717] text-white px-8 py-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2 hover:bg-[#1a3d2b] hover:shadow-lg hover:shadow-emerald-950/10 transition-all active:scale-95 cursor-pointer"
              >
                Solicită ofertă 
                <ArrowRight size={16} />
              </button>
              <button 
                onClick={() => setView('portofoliu')}
                className="border border-[#022717] text-[#022717] px-8 py-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2 hover:bg-[#022717]/5 transition-all cursor-pointer"
              >
                Vezi portofoliu
              </button>
            </div>
          </motion.div>

          {/* Right Floating Image card */}
          <motion.div 
            initial={{ opacity: 0, scale: 0.95, rotate: 1 }}
            animate={{ opacity: 1, scale: 1, rotate: 3 }}
            whileHover={{ rotate: 0, scale: 1.02 }}
            transition={{ duration: 0.8, ease: 'easeOut' }}
            className="relative flex justify-center z-10"
          >
            <div className="relative w-full aspect-square max-w-md bg-[#edeeef] rounded-3xl overflow-hidden shadow-2xl border border-gray-100">
              <img 
                className="w-full h-full object-cover select-none"
                alt="Optera Tech connectivity"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDRyfzgrIpN8-vc51CjEdNqxx5j0gYrBqOR1AjSmPXwO8_wBwIfqjZxNmSSSocvXKhgU5px02rKEunWcyqH4eg4kZ21w_pKu7u36Gbmh9fjVB1FGvuq4CWbPFQsSzyvInZBQPgsQn6P7qTjMHk274-ayfkli7RBSZKzRS7zZhD1HKwJBrydopQdxCJCPPIrPXxeo9uFVTXLYXyMlnnJMrjQR3SRHkZgkLjKGf-freWyJ5Q6-0CBzrb7QzUkEWKkho8yGPFtYQkm5UVO"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-[#022717]/40 to-transparent"></div>
              
              {/* Dynamic activate badge overlay */}
              <div className="absolute bottom-6 left-6 right-6 p-4 bg-white/90 backdrop-blur-md rounded-2xl shadow-xl border border-white/20">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-full bg-emerald-150 flex items-center justify-center text-[#022717]">
                    <ShieldCheck size={20} className="fill-[#a9d0b6]/50" />
                  </div>
                  <div>
                    <p className="text-xs font-bold text-[#022717] tracking-wider">SISTEM ACTIV</p>
                    <p className="text-[10px] text-[#545f73] font-medium">Protecție în timp real 24/7</p>
                  </div>
                </div>
              </div>
            </div>
          </motion.div>
        </div>
      </section>

      {/* Trust Items / Bento Grid */}
      <section className="py-24 px-6 md:px-16 bg-white" id="servicii">
        <div className="max-w-[1280px] mx-auto w-full">
          <div className="mb-16">
            <h2 className="text-3xl font-extrabold text-[#022717] tracking-tight mb-4">
              Securitate fără compromisuri
            </h2>
            <p className="text-[#545f73] max-w-2xl text-sm md:text-base leading-relaxed">
              Oferim soluții complete de securitate, adaptate nevoilor tale specifice, cu tehnologie de ultimă oră și o echipă dedicată de ingineri.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
            {/* Card 1 - Montaj Profesionist (Wide 2-col) */}
            <motion.div 
              whileHover={{ y: -4 }}
              className="md:col-span-2 bg-[#f8f9fa] p-8 md:p-10 rounded-2xl border border-gray-100 flex flex-col justify-between shadow-sm hover:shadow-md transition-all group"
            >
              <div>
                <div className="w-12 h-12 rounded-xl bg-[#022717] text-[#a9d0b6] flex items-center justify-center mb-6 group-hover:scale-105 transition-transform">
                  <Drill size={24} />
                </div>
                <h3 className="text-xl font-bold text-[#022717] mb-3">Montaj profesionist</h3>
                <p className="text-sm text-[#545f73] leading-relaxed mb-6">
                  Instalare discretă, fără cabluri la vedere și echipamente calibrate pentru vizibilitate maximă în orice condiții de lumină. Lăsăm curat în urma noastră.
                </p>
              </div>
              <div className="flex gap-2">
                <span className="px-3 py-1 bg-white border border-gray-100 rounded-full font-bold text-[9px] text-[#545f73] tracking-widest uppercase">CURĂȚENIE</span>
                <span className="px-3 py-1 bg-white border border-gray-100 rounded-full font-bold text-[9px] text-[#545f73] tracking-widest uppercase">PRECIZIE</span>
              </div>
            </motion.div>

            {/* Card 2 - Acces Mobil */}
            <motion.div 
              whileHover={{ y: -4 }}
              className="bg-[#f8f9fa] p-8 rounded-2xl border border-gray-100 flex flex-col justify-between shadow-sm hover:shadow-md transition-all group"
            >
              <div>
                <div className="w-12 h-12 rounded-xl bg-[#022717] text-[#a9d0b6] flex items-center justify-center mb-6 group-hover:scale-105 transition-transform">
                  <Smartphone size={24} />
                </div>
                <h3 className="text-xl font-bold text-[#022717] mb-3">Acces mobil</h3>
                <p className="text-xs md:text-sm text-[#545f73] leading-relaxed">
                  Vezi ce se întâmplă acasă sau la birou, direct pe telefonul tău, de oriunde te-ai afla în lume. Notificări instant la mișcare.
                </p>
              </div>
            </motion.div>

            {/* Card 3 - Service și mentenanță */}
            <motion.div 
              whileHover={{ y: -4 }}
              className="bg-[#f8f9fa] p-8 rounded-2xl border border-gray-100 flex flex-col justify-between shadow-sm hover:shadow-md transition-all group"
            >
              <div>
                <div className="w-12 h-12 rounded-xl bg-[#022717] text-[#a9d0b6] flex items-center justify-center mb-6 group-hover:scale-105 transition-transform">
                  <ShieldAlert size={24} />
                </div>
                <h3 className="text-xl font-bold text-[#022717] mb-3">Mentenanță</h3>
                <p className="text-xs md:text-sm text-[#545f73] leading-relaxed">
                  Suport tehnic rapid în Bucovina pentru a ne asigura că sistemul tău funcționează impecabil în orice moment, fără întreruperi.
                </p>
              </div>
            </motion.div>

            {/* Card 4 - Horizontal Wide (Upgrade) */}
            <motion.div 
              whileHover={{ y: -2 }}
              className="md:col-span-4 flex flex-col md:flex-row items-center gap-8 bg-[#022717] text-white p-10 md:p-12 rounded-3xl overflow-hidden relative shadow-lg"
            >
              <div className="flex-1 z-10">
                <span className="inline-block px-3 py-1 bg-white/10 text-[#a9d0b6] font-bold text-[9px] tracking-widest uppercase rounded-full mb-4">
                  UPGRADE INTELIGENT
                </span>
                <h3 className="text-2xl md:text-3xl font-bold text-white mb-4">Upgrade sisteme existente</h3>
                <p className="text-sm md:text-base text-[#a9d0b6] leading-relaxed max-w-xl opacity-90">
                  Transformă-ți vechiul sistem analog într-unul digital, inteligent. Păstrăm cablarea și infrastructura utilă pentru a-ți reduce costurile și aducem claritatea cristal 4K cu analiză AI.
                </p>
                <button 
                  onClick={() => setView('oferta')}
                  className="mt-8 bg-white text-[#022717] px-6 py-3 rounded-xl font-bold text-xs tracking-wider hover:bg-[#a9d0b6] hover:scale-105 active:scale-95 transition-all cursor-pointer"
                >
                  SOLICITĂ EVALUARE
                </button>
              </div>
              
              {/* Abstract decorative right image with code mix blend as requested */}
              <div className="hidden md:block w-1/3 absolute right-0 top-0 bottom-0 overflow-hidden select-none pointer-events-none">
                <img 
                  className="h-full w-full object-cover opacity-20 mix-blend-screen"
                  alt="Motherboard security visual"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuBkvFoy_VgB2crE3iE7YNYhYah8rBmuP1rbJYT02WdndorJU7Sl7papuEzYcnh_wZHFu4Imsr23NA4UeVAl6gdUMuIkNy9DfBs8_wmaUr6sfPdXXHJdBiprUwKEkmbpSjITD8PUKT2BeL43Uw5frWTH0C7kJ7qglIHljYGCeDJVSYJeS8DXpeBcX740lLpA9I0PQKcAr3qUtzMq040E4_oEvk5mwKYmCJQfN5LwDfn06ctRr8NlayWR1m98uR5H8rpgoPXBxbFBupoz"
                />
              </div>
            </motion.div>
          </div>
        </div>
      </section>

      {/* CTA section */}
      <section className="py-24 px-6 md:px-16 bg-[#f8f9fa]">
        <div className="max-w-[1280px] mx-auto text-center border-t border-gray-200/80 pt-20">
          <h2 className="text-3xl md:text-4xl font-extrabold text-[#022717] mb-6">Pregătit pentru siguranță?</h2>
          <p className="text-sm md:text-base text-[#545f73] mb-12 max-w-xl mx-auto leading-relaxed">
            Fie că ai nevoie de un sistem de supraveghere pentru curtea casei tale sau pentru un depozit industrial complex în Câmpulung Moldovenesc, avem soluția potrivită. Durează doar câteva secunde să iei legătura!
          </p>
          <div className="flex flex-col sm:flex-row justify-center gap-4 max-w-md mx-auto">
            <a 
              href="https://wa.me/40745123456" 
              target="_blank" 
              rel="noreferrer" 
              className="flex items-center justify-center gap-3 bg-[#25D366] text-white px-8 py-4 rounded-xl font-bold hover:shadow-lg hover:shadow-green-500/10 hover:opacity-95 transition-all text-sm active:scale-95"
            >
              <MessageSquare size={18} />
              Contact WhatsApp
            </a>
            <a 
              href="mailto:contact@opteravision.ro" 
              className="flex items-center justify-center gap-3 bg-[#022717] text-white px-8 py-4 rounded-xl font-bold hover:shadow-lg hover:shadow-emerald-900/10 hover:bg-[#1a3d2b] transition-all text-sm active:scale-95"
            >
              <Mail size={18} />
              Trimite Email
            </a>
          </div>
        </div>
      </section>
    </div>
  );
}
