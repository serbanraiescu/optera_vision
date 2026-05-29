import { motion } from 'motion/react';
import { Video, ShieldAlert, Cpu, Smartphone, Cable, ClipboardCheck, History, CheckCircle, ArrowRight, MessageSquare, Wrench } from 'lucide-react';
import { ViewType } from '../types';

interface ServiciiViewProps {
  setView: (view: ViewType) => void;
}

export default function ServiciiView({ setView }: ServiciiViewProps) {
  return (
    <div className="w-full">
      {/* Hero Section */}
      <section className="max-w-[1280px] mx-auto px-6 md:px-16 pt-16 mb-16">
        <motion.div 
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
          className="max-w-3xl"
        >
          <span className="inline-block px-3 py-1 bg-[#d5e0f8] text-[#586377] rounded-full font-bold text-[10px] tracking-widest mb-4 uppercase">
            SUPRAVEGHERE VIDEO PROFESIONALĂ
          </span>
          <h2 className="font-extrabold text-3xl md:text-5xl text-[#022717] leading-tight mb-6">
            Securitate fără compromisuri prin tehnologie de ultimă oră
          </h2>
          <p className="font-sans text-base md:text-lg text-[#545f73] leading-relaxed">
            Oferim soluții complete de monitorizare video în Câmpulung Moldovenesc și împrejurimi. De la consultanță tehnică și proiectare dedicată, până la cablare estetică, mentenanță și upgrade-uri inteligente.
          </p>
        </motion.div>
      </section>

      {/* Services Bento Grid */}
      <section className="max-w-[1280px] mx-auto px-6 md:px-16 pb-24">
        <div className="grid grid-cols-1 md:grid-cols-12 gap-6">
          
          {/* Service 1: Interior (Large - 8cols) */}
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="md:col-span-8 group bg-white p-8 md:p-12 rounded-3xl border border-gray-150 shadow-sm transition-all hover:border-[#1a3d2b]/20 hover:shadow-md"
          >
            <div className="flex flex-col md:flex-row gap-8 items-start">
              <div className="flex-1">
                <div className="w-12 h-12 bg-[#f8f9fa] rounded-xl flex items-center justify-center text-[#022717] mb-6 group-hover:scale-105 transition-transform duration-300">
                  <Video size={24} />
                </div>
                <h3 className="text-xl md:text-2xl font-bold text-[#022717] mb-4">
                  Camere supraveghere interior
                </h3>
                <p className="text-sm text-[#545f73] leading-relaxed mb-6">
                  Sisteme discrete cu rezoluție ultra-înaltă, perfect adaptate pentru integrare estetică în spații comerciale, birouri sau locuințe. Dispun de tehnologie IR inteligentă pentru vizibilitate nocturnă completă și unghiuri ultra-largi pentru acoperire maximă.
                </p>
                <ul className="space-y-3 mb-4">
                  <li className="flex items-center gap-2.5 text-xs font-semibold text-[#022717]">
                    <CheckCircle size={15} className="text-emerald-600 fill-emerald-50" />
                    Design minimalist și carcasă non-invazivă
                  </li>
                  <li className="flex items-center gap-2.5 text-xs font-semibold text-[#022717]">
                    <CheckCircle size={15} className="text-emerald-600 fill-emerald-50" />
                    Microfon încorporat de înaltă fidelitate (Audio HD)
                  </li>
                </ul>
              </div>
              <div className="w-full md:w-1/3 aspect-square rounded-2xl overflow-hidden self-center group bg-gray-50 border border-gray-100">
                <img 
                  alt="Interior surveillance" 
                  className="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-750"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuCILhWe89sYPCXqOJgCOTD1tAR_hJDU7BTtQg2Gl3b9Xp90RgLK0LZUr1YtOfBIgJFkyE9qXkd7dQXvFIfvhno-x_6GCfSCTyP_sSMJZA7JTaF0WLIk7QYzWgkGlhHXvySfxYyx8IMlNhJFbnLUGIfKj3R7o0pZ8qwhidOAJcapjP2qdfTXUTyJu_HkjL-AJacZxuiP98e2jtWX6rhVj9x9nbEKpOZkFlf2QG37M4GFEZ9te00I5d8M34MF90reSdXoElDxtOrLPTJ3"
                />
              </div>
            </div>
          </motion.div>

          {/* Service 2: Exterior (Medium - 4cols) */}
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="md:col-span-4 group bg-white p-8 rounded-3xl border border-gray-150 shadow-sm transition-all hover:border-[#1a3d2b]/20 hover:shadow-md flex flex-col justify-between"
          >
            <div>
              <div className="w-12 h-12 bg-[#f8f9fa] rounded-xl flex items-center justify-center text-[#022717] mb-6 group-hover:scale-105 transition-transform duration-300">
                <ShieldAlert size={24} />
              </div>
              <h3 className="text-xl font-bold text-[#022717] mb-3">Camere exterior IP67</h3>
              <p className="text-xs text-[#545f73] leading-relaxed mb-6">
                Protecție profesională certificată IP67 împotriva intemperiilor extreme, viscolului și carcase antivandal din metal IK10. Tehnologie ColorVu de la Hikvision pentru rezoluții de înaltă calitate și imagini perfect color chiar și în întuneric total.
              </p>
            </div>
            <div className="aspect-video rounded-xl overflow-hidden bg-gray-50 border border-gray-100">
              <img 
                alt="Outdoor camera" 
                className="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-750"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuC7cVkbRnzrdqW-qJCMO1RtfeXd9lgAPzWGav6qG6t68PbyS50mZ59qfbTxRwN2zgfoOZq6xNUvSy-nQa6NHreACY6bb7cUq3wObBOnOTYzzQqSEsZxTBtL7bo7klPPhIZOKPosiRNfCFxJD7qo-jeA2ghzPJEACcM_-K--Ap_yjSOEX5Qg7oMqAUYEN7PJc_4kDMzlTGYGs5BrmbNZM0dII9pxBm0HVwMx_C9ELihtJTiGkWotaLU3R6fVynABq-GDayNg192FUBTo"
              />
            </div>
          </motion.div>

          {/* Service 3: DVR/NVR */}
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.3 }}
            className="md:col-span-4 group bg-white p-8 rounded-3xl border border-gray-150 shadow-sm transition-all hover:border-[#1a3d2b]/20 hover:shadow-md"
          >
            <div className="w-12 h-12 bg-[#f8f9fa] rounded-xl flex items-center justify-center text-[#022717] mb-6 group-hover:scale-105 transition-transform">
              <Cpu size={24} />
            </div>
            <h3 className="text-lg font-bold text-[#022717] mb-3">Unități DVR / NVR</h3>
            <p className="text-xs text-[#545f73] leading-relaxed">
              Stocare criptată pe hard disk-uri de clasă industrială (Western Digital Purple) dedicate special supravegherii 24/7. Management inteligent cu compresie duală H.265+ pentru stocarea datelor până la 30+ de zile.
            </p>
          </motion.div>

          {/* Service 4: Mobile Access (Primary color block) */}
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.4 }}
            className="md:col-span-4 group bg-[#022717] text-white p-8 rounded-3xl shadow-sm transition-all border border-transparent"
          >
            <div className="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-[#a9d0b6] mb-6 group-hover:scale-105 transition-transform">
              <Smartphone size={24} />
            </div>
            <h3 className="text-lg font-bold text-white mb-3 text-[#primary-fixed-dim]">Acces de pe telefon</h3>
            <p className="text-xs text-[#a9d0b6] leading-relaxed opacity-95">
              Configurare securizată P2P peer-to-peer integrată. Monitorizare live super cursivă și notificări push instant direct pe smartphone, tabletă sau smartwatch. Control total de la distanță de oriunde în lume.
            </p>
          </motion.div>

          {/* Service 5: Cablare & Configurare */}
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.5 }}
            className="md:col-span-4 group bg-white p-8 rounded-3xl border border-gray-150 shadow-sm transition-all hover:border-[#1a3d2b]/20 hover:shadow-md"
          >
            <div className="w-12 h-12 bg-[#f8f9fa] rounded-xl flex items-center justify-center text-[#022717] mb-6 group-hover:scale-105 transition-transform">
              <Cable size={24} />
            </div>
            <h3 className="text-lg font-bold text-[#022717] mb-3">Cablare &amp; Configurare</h3>
            <p className="text-xs text-[#545f73] leading-relaxed">
              Instalare estetică, curată și discretă. Ne asigurăm că fiecare cablu de rețea sau cat6 este protejat în copex sau canal cablu dedicat, iar unghiurile camerelor sunt proiectate digital pentru securitate optimă.
            </p>
          </motion.div>

          {/* Service 6: Mentenanta */}
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.6 }}
            className="md:col-span-6 group bg-[#f8f9fa] p-8 rounded-3xl border border-gray-150 shadow-sm flex gap-4 transition-all"
          >
            <div className="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-[#022717] shrink-0 shadow-sm">
              <ClipboardCheck size={22} />
            </div>
            <div>
              <h3 className="text-lg font-bold text-[#022717] mb-2">Mentenanță Periodică</h3>
              <p className="text-xs text-[#545f73] leading-relaxed">
                Curățarea profesională a lentilelor de praf/insecte, verificarea contactelor și mufelor expuse, adaptarea senzorilor IR, diagnosticarea stării de degradare a disk-urilor NVR și corecția unghiurilor de filmare.
              </p>
            </div>
          </motion.div>

          {/* Service 7: Service & Upgrade */}
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.7 }}
            className="md:col-span-6 group bg-[#f8f9fa] p-8 rounded-3xl border border-gray-150 shadow-sm flex gap-4 transition-all"
          >
            <div className="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-[#022717] shrink-0 shadow-sm">
              <Wrench size={22} />
            </div>
            <div>
              <h3 className="text-lg font-bold text-[#022717] mb-2">Service &amp; Upgrade</h3>
              <p className="text-xs text-[#545f73] leading-relaxed">
                Intervenim rapid pentru depanarea oricărei defecțiuni hardware sau software a sistemelor de securitate existente. Modernizăm camere CCTV analogice la rezoluții Ultra HD păstrând cablurile coaxiale existente.
              </p>
            </div>
          </motion.div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="mb-24 max-w-[1280px] mx-auto px-6 md:px-16 text-center">
        <div className="bg-[#1a3d2b] text-white p-12 md:p-20 rounded-[32px] relative overflow-hidden shadow-lg">
          <div className="relative z-10 max-w-2xl mx-auto">
            <h2 className="text-2xl md:text-3xl font-extrabold mb-4">Pregătit să îți securizezi proprietatea?</h2>
            <p className="text-sm md:text-base text-[#a9d0b6] mb-10 opacity-95 font-sans leading-relaxed">
              Echipa noastră oferă consultanță tehnică gratuită și ofertare rapidă în 24h pentru configurarea sistemului perfect de supraveghere și alarmă.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <button 
                onClick={() => setView('oferta')}
                className="bg-white text-[#022717] px-8 py-4 rounded-xl font-bold text-xs tracking-wider hover:bg-[#a9d0b6] hover:scale-105 active:scale-95 transition-all cursor-pointer flex items-center justify-center gap-2"
              >
                Solicită ofertă gratuită
              </button>
              <a 
                href="https://wa.me/40745123456"
                target="_blank"
                rel="noreferrer"
                className="border border-white/20 text-white px-8 py-4 rounded-xl font-bold text-xs tracking-wider hover:bg-white/10 active:scale-95 transition-all flex items-center justify-center gap-2"
              >
                <MessageSquare size={16} />
                Vorbește pe WhatsApp
              </a>
            </div>
          </div>
          {/* subtle mesh bg grid overlay */}
          <div className="absolute inset-0 opacity-[0.03] pointer-events-none">
            <div className="absolute top-0 left-0 w-full h-full" style={{ backgroundImage: 'radial-gradient(#fff 1px, transparent 1px)', backgroundSize: '24px 24px' }}></div>
          </div>
        </div>
      </section>
    </div>
  );
}
