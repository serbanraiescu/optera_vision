import React, { useState } from 'react';
import { motion } from 'motion/react';
import { Send, CheckCircle2, ShieldCheck, Zap, HelpCircle, AlertCircle, Loader2 } from 'lucide-react';
import { LocatieType, ServiciuType, Lead } from '../types';

interface ConfiguratorViewProps {
  onAddLead: (lead: {
    nume: string;
    telefon: string;
    email: string;
    localitate: string;
    tipLocatie: LocatieType;
    tipServiciu: ServiciuType;
    camere: number;
    mesaj: string;
  }) => void;
}

export default function ConfiguratorView({ onAddLead }: ConfiguratorViewProps) {
  // Form states
  const [nume, setNume] = useState('');
  const [telefon, setTelefon] = useState('');
  const [email, setEmail] = useState('');
  const [localitate, setLocalitate] = useState('');
  const [tipLocatie, setTipLocatie] = useState<LocatieType>('rezidential');
  const [tipServiciu, setTipServiciu] = useState<ServiciuType>('nou');
  const [camere, setCamere] = useState(4);
  const [mesaj, setMesaj] = useState('');
  const [gdprChecked, setGdprChecked] = useState(false);

  // Status states
  const [status, setStatus] = useState<'idle' | 'submitting' | 'success' | 'error'>('idle');
  const [errorMessage, setErrorMessage] = useState('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!gdprChecked) {
      setErrorMessage('Pentru a trimite, este necesar să acceptați prelucrarea datelor.');
      setStatus('error');
      return;
    }

    setStatus('submitting');
    setErrorMessage('');

    // Simulate elite submitting action
    setTimeout(() => {
      try {
        onAddLead({
          nume,
          telefon,
          email,
          localitate,
          tipLocatie,
          tipServiciu,
          camere,
          mesaj
        });

        // Reset form
        setNume('');
        setTelefon('');
        setEmail('');
        setLocalitate('');
        setTipLocatie('rezidential');
        setTipServiciu('nou');
        setCamere(4);
        setMesaj('');
        setGdprChecked(false);
        setStatus('success');
      } catch (err: any) {
        setErrorMessage('A apărut o problemă la trimiterea cererii dvs. Încercați din nou.');
        setStatus('error');
      }
    }, 1500);
  };

  return (
    <div className="w-full">
      <main className="pt-8 pb-16">
        <div className="max-w-[1280px] mx-auto px-6 md:px-16 grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
          
          {/* Left Side: Context & Visual (5/12 cols) */}
          <div className="lg:col-span-5 flex flex-col gap-8 text-left">
            <div className="space-y-4">
              <span className="font-bold text-xs tracking-widest text-[#022717] uppercase">Securitate Inteligentă</span>
              <h1 className="text-3xl md:text-5xl font-extrabold text-[#022717] tracking-tight leading-tight">
                Configurați-vă sistemul de protecție.
              </h1>
              <p className="font-sans text-sm md:text-base text-[#545f73] leading-relaxed">
                Completați configuratorul rapid de mai jos pentru a primi o ofertă detaliată și complet personalizată. Inginerii noștri din Câmpulung Moldovenesc vor analiza spațiul dvs. și vor proiecta gratuit cea mai eficientă schemă tehnică de supraveghere.
              </p>
            </div>

            {/* Premium Camera Image and overlay */}
            <div className="relative rounded-2xl overflow-hidden aspect-[4/3] shadow-md group bg-gray-50 border border-gray-100">
              <img 
                alt="Modern outdoor security camera cctv" 
                className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 select-none"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCce1wCnTQqjS6tDVHtqdarxC4a0kRwNZDVJNlfnz76-Smyp68TIjdWocwpqDYZQxNHpKJbEUuUQ3Ps-_evMOWLgGFrKWdeeDe443eu6aec-6Y8A6b98j7-JhJA9rg8zLps1r0qmWNFHKYY75C0R168RR4f-U4u4Nh0NjoHENZswrgTcc20h9Y3ZKk8U3rTXdO7anPUs5QjLhOCxf2iMesXIMIeDlebtPxQzKzkfzDnxcmemOkG_j3JvZsQcVSDprRONsCuDzv3Yfq0"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-[#022717]/40 to-transparent"></div>
              
              <div className="absolute bottom-6 left-6 right-6 p-4 bg-white/90 backdrop-blur-md rounded-xl">
                <div className="flex items-center gap-3">
                  <div className="p-2 bg-emerald-100 text-[#022717] rounded-full">
                    <ShieldCheck size={18} className="fill-emerald-100 text-[#022717]" />
                  </div>
                  <div>
                    <h4 className="font-bold text-xs text-[#022717]">Tehnologie de ultimă oră în Bucovina</h4>
                    <p className="text-[10px] text-[#545f73] font-medium">Instalări autorizate IGPR în tot județul</p>
                  </div>
                </div>
              </div>
            </div>

            {/* Quick trust metrics */}
            <div className="grid grid-cols-2 gap-4">
              <div className="p-5 bg-[#f8f9fa] rounded-2xl border border-gray-150 shadow-sm flex flex-col gap-1.5">
                <span className="w-8 h-8 rounded-lg bg-[#022717] text-[#a9d0b6] flex items-center justify-center text-xs font-bold">
                  24h
                </span>
                <h4 className="font-bold text-xs text-[#022717]">Răspuns Rapid</h4>
                <p className="text-[10px] text-[#545f73] font-medium">Ofertare tehnică &amp; deviz în maximum 24h</p>
              </div>

              <div className="p-5 bg-[#f8f9fa] rounded-2xl border border-gray-150 shadow-sm flex flex-col gap-1.5">
                <span className="w-8 h-8 rounded-lg bg-[#022717] text-[#a9d0b6] flex items-center justify-center text-xs font-bold">
                  Ro
                </span>
                <h4 className="font-bold text-xs text-[#022717]">Suport Tehnic</h4>
                <p className="text-[10px] text-[#545f73] font-medium">Consultanță gratuită la fața locului de specialiști</p>
              </div>
            </div>
          </div>

          {/* Right Side: Form (7/12 cols) */}
          <div className="lg:col-span-7 bg-white rounded-3xl shadow-lg p-8 md:p-12 border border-blue-50/50">
            {status === 'success' ? (
              <motion.div 
                initial={{ opacity: 0, scale: 0.95 }}
                animate={{ opacity: 1, scale: 1 }}
                className="py-16 text-center space-y-6 flex flex-col items-center"
              >
                <div className="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center shadow-inner">
                  <CheckCircle2 size={48} className="animate-bounce" />
                </div>
                <div className="space-y-2">
                  <h3 className="text-2xl font-bold text-[#022717]">Cerere trimisă cu succes!</h3>
                  <p className="text-sm text-[#545f73] max-w-sm leading-relaxed">
                    Vă mulțumim pentru interes! Un inginer Optera Vision va analiza cerințele dvs. tehnice pentru sistemul de <strong className="text-[#022717] font-semibold">{camere} camere</strong> și vă va contacta în maximum 24 de ore la numărul sau adresa furnizată.
                  </p>
                </div>
                
                <div className="pt-4 flex flex-col sm:flex-row gap-3 w-full max-w-xs">
                  <button 
                    onClick={() => setStatus('idle')}
                    className="flex-1 text-center py-3 px-4 bg-[#022717] text-white hover:bg-[#1a3d2b] font-bold text-xs tracking-wider rounded-xl transition-all cursor-pointer"
                  >
                    CONFIGURATOR NOU
                  </button>
                  <a
                    href="https://wa.me/40745123456"
                    target="_blank"
                    rel="noreferrer"
                    className="flex-1 text-center py-3 px-4 border border-[#022717] text-[#022717] font-bold text-xs rounded-xl hover:bg-[#022717]/5 transition-all flex items-center justify-center gap-1.5"
                  >
                    Scrie pe WhatsApp
                  </a>
                </div>
              </motion.div>
            ) : (
              <form onSubmit={handleSubmit} className="flex flex-col gap-6 text-left">
                <div className="space-y-1">
                  <h2 className="text-xl font-extrabold text-[#022717]">Solicitare Ofertă Personalizată</h2>
                  <p className="text-xs text-[#545f73]">Specifică detaliile proprietății pentru precizie sporită</p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  {/* Nume */}
                  <div className="flex flex-col gap-2">
                    <label className="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" htmlFor="nume">
                      Nume Complet
                    </label>
                    <input 
                      id="nume"
                      className="bg-[#f3f4f5]/65 border border-transparent hover:border-[#545f73]/20 focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all"
                      placeholder="Ex: Popescu Ion"
                      type="text"
                      required
                      value={nume}
                      onChange={(e) => setNume(e.target.value)}
                    />
                  </div>

                  {/* Telefon */}
                  <div className="flex flex-col gap-2">
                    <label className="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" htmlFor="telefon">
                      Telefon
                    </label>
                    <input 
                      id="telefon"
                      className="bg-[#f3f4f5]/65 border border-transparent hover:border-[#545f73]/20 focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all"
                      placeholder="07xx xxx xxx"
                      type="tel"
                      required
                      value={telefon}
                      onChange={(e) => setTelefon(e.target.value)}
                    />
                  </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  {/* Email */}
                  <div className="flex flex-col gap-2">
                    <label className="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" htmlFor="email">
                      Email
                    </label>
                    <input 
                      id="email"
                      className="bg-[#f3f4f5]/65 border border-transparent hover:border-[#545f73]/20 focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all"
                      placeholder="contact@exemplu.ro"
                      type="email"
                      required
                      value={email}
                      onChange={(e) => setEmail(e.target.value)}
                    />
                  </div>

                  {/* Localitate */}
                  <div className="flex flex-col gap-2">
                    <label className="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" htmlFor="localitate">
                      Localitate
                    </label>
                    <input 
                      id="localitate"
                      className="bg-[#f3f4f5]/65 border border-transparent hover:border-[#545f73]/20 focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all"
                      placeholder="Ex: Câmpulung Moldovenesc"
                      type="text"
                      required
                      value={localitate}
                      onChange={(e) => setLocalitate(e.target.value)}
                    />
                  </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  {/* Tip Locatie */}
                  <div className="flex flex-col gap-2">
                    <label className="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" htmlFor="tip_locatie">
                      Tip locație
                    </label>
                    <select 
                      id="tip_locatie"
                      className="bg-[#f3f4f5]/65 border border-transparent focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all"
                      value={tipLocatie}
                      onChange={(e) => setTipLocatie(e.target.value as LocatieType)}
                    >
                      <option value="rezidential">Rezidențial (Casă/Apartament)</option>
                      <option value="comercial">Comercial (Birou/Magazin)</option>
                      <option value="industrial">Industrial (Depozit/Fabrică)</option>
                      <option value="public">Spațiu Public</option>
                    </select>
                  </div>

                  {/* Tip Serviciu */}
                  <div className="flex flex-col gap-2">
                    <label className="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" htmlFor="tip_serviciu">
                      Sistem nou sau upgrade?
                    </label>
                    <select 
                      id="tip_serviciu"
                      className="bg-[#f3f4f5]/65 border border-transparent focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all"
                      value={tipServiciu}
                      onChange={(e) => setTipServiciu(e.target.value as ServiciuType)}
                    >
                      <option value="nou">Proiect nou (Instalare completă)</option>
                      <option value="upgrade">Upgrade sistem existent</option>
                      <option value="mentenanta">Mentenanță periodică</option>
                    </select>
                  </div>
                </div>

                {/* Camere slider */}
                <div className="flex flex-col gap-2">
                  <div className="flex justify-between items-center">
                    <label className="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" htmlFor="camere">
                      Număr aproximativ camere
                    </label>
                    <span className="text-xs font-bold text-[#022717]">recomandat pentru spatiu</span>
                  </div>
                  <div className="flex items-center gap-4 bg-[#f3f4f5]/65 rounded-xl px-5 py-3.5 border border-transparent">
                    <input 
                      id="camere"
                      className="flex-grow accent-[#022717] cursor-pointer"
                      max="64"
                      min="1"
                      value={camere}
                      onChange={(e) => setCamere(parseInt(e.target.value))}
                      type="range"
                    />
                    <span className="font-bold text-base text-[#022717] min-w-[2.5ch] text-right">
                      {camere}
                    </span>
                  </div>
                </div>

                {/* Mesaj */}
                <div className="flex flex-col gap-2">
                  <label className="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" htmlFor="mesaj">
                    Mesaj (Opțional)
                  </label>
                  <textarea 
                    id="mesaj"
                    className="bg-[#f3f4f5]/65 border border-transparent hover:border-[#545f73]/20 focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all resize-none"
                    placeholder="Detalii suplimentare despre cerințele dvs..."
                    rows={4}
                    value={mesaj}
                    onChange={(e) => setMesaj(e.target.value)}
                  />
                </div>

                {/* GDPR Checkbox */}
                <div className="flex items-start gap-3 py-2 cursor-pointer select-none">
                  <input 
                    id="gdpr"
                    required
                    checked={gdprChecked}
                    onChange={(e) => setGdprChecked(e.target.checked)}
                    className="mt-1 rounded border-gray-300 text-[#022717] focus:ring-[#022717] h-4 w-4 cursor-pointer"
                    type="checkbox"
                  />
                  <label htmlFor="gdpr" className="font-sans text-xs md:text-sm text-[#545f73] leading-snug cursor-pointer">
                    Sunt de acord cu prelucrarea datelor cu caracter personal în baza regulamentului general privind protecția datelor.
                  </label>
                </div>

                {/* Status alerts */}
                {status === 'error' && (
                  <div className="flex items-center gap-2 text-rose-700 bg-rose-50 p-4 rounded-xl border border-rose-100 text-xs font-semibold">
                    <AlertCircle size={16} />
                    <span>{errorMessage}</span>
                  </div>
                )}

                {/* Submit button */}
                <button 
                  disabled={status === 'submitting'}
                  className={`bg-[#022717] text-white font-bold text-xs tracking-wider py-4 px-8 rounded-xl active:scale-[0.98] transition-all flex items-center justify-center gap-3 cursor-pointer select-none ${
                    status === 'submitting' ? 'opacity-75 cursor-not-allowed' : 'hover:bg-[#1a3d2b] hover:shadow-lg'
                  }`}
                  type="submit"
                >
                  {status === 'submitting' ? (
                    <>
                      <span>SE PROCESEAZĂ...</span>
                      <Loader2 size={16} className="animate-spin" />
                    </>
                  ) : (
                    <>
                      <span>TRIMITE CEREREA DE OFERTĂ</span>
                      <Send size={15} />
                    </>
                  )}
                </button>
              </form>
            )}
          </div>
        </div>
      </main>
    </div>
  );
}
