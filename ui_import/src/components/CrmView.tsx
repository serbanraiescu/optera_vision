import React, { useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { 
  Users, Bolt, Clock, TrendingUp, Search, Plus, Filter, Info, 
  Trash2, Mail, Phone, MapPin, X, Calendar, ArrowRight, Check, Sliders, Edit3, DollarSign
} from 'lucide-react';
import { Lead, LeadStatus, LocatieType, ServiciuType } from '../types';

interface CrmViewProps {
  leads: Lead[];
  onUpdateLeadStatus: (id: string, newStatus: LeadStatus) => void;
  onUpdateLeadValoare: (id: string, valoare: number) => void;
  onDeleteLead: (id: string) => void;
  onAddManualLead: (lead: Omit<Lead, 'id' | 'data'>) => void;
}

const STATUS_LABELS: Record<LeadStatus, string> = {
  nou: 'NOU',
  contactat: 'CONTACTAT',
  programare: 'PROGRAMARE',
  ofertat: 'OFERTAT',
  acceptat: 'ACCEPTAT',
  lucru: 'ÎN LUCRU',
  finalizat: 'FINALIZAT',
  pierdut: 'PIERDUT'
};

const STATUS_ORDER: LeadStatus[] = [
  'nou',
  'contactat',
  'programare',
  'ofertat',
  'acceptat',
  'lucru',
  'finalizat',
  'pierdut'
];

export default function CrmView({ 
  leads, 
  onUpdateLeadStatus, 
  onUpdateLeadValoare, 
  onDeleteLead,
  onAddManualLead
}: CrmViewProps) {
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedLead, setSelectedLead] = useState<Lead | null>(null);
  const [isEditingValue, setIsEditingValue] = useState(false);
  const [newValueEdit, setNewValueEdit] = useState('');
  
  // Add Manual Lead Modal states
  const [showAddModal, setShowAddModal] = useState(false);
  const [addNume, setAddNume] = useState('');
  const [addTelefon, setAddTelefon] = useState('');
  const [addEmail, setAddEmail] = useState('');
  const [addLocalitate, setAddLocalitate] = useState('');
  const [addTipLocatie, setAddTipLocatie] = useState<LocatieType>('rezidential');
  const [addTipServiciu, setAddTipServiciu] = useState<ServiciuType>('nou');
  const [addCamere, setAddCamere] = useState(4);
  const [addMesaj, setAddMesaj] = useState('');
  const [addValoare, setAddValoare] = useState(2000);
  const [addStatus, setAddStatus] = useState<LeadStatus>('nou');

  // Computed summary metrics based on live lead status
  const totalLeads = leads.length;
  const activeProjects = leads.filter(l => l.status === 'lucru').length;
  const finalizedLeads = leads.filter(l => l.status === 'finalizat');
  const conversionRate = totalLeads ? ((finalizedLeads.length / totalLeads) * 100).toFixed(1) : '34.2';
  
  // Group leads by pipeline status
  const leadsByStatus = STATUS_ORDER.reduce((acc, status) => {
    acc[status] = leads.filter(l => l.status === status);
    return acc;
  }, {} as Record<LeadStatus, Lead[]>);

  // Filter leads for the recent clients data table
  const filteredTableLeads = leads.filter(lead => {
    const text = `${lead.nume} ${lead.email} ${lead.localitate} ${lead.telefon}`.toLowerCase();
    return text.includes(searchQuery.toLowerCase());
  });

  const handleAddManualSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onAddManualLead({
      nume: addNume,
      telefon: addTelefon,
      email: addEmail,
      localitate: addLocalitate,
      tipLocatie: addTipLocatie,
      tipServiciu: addTipServiciu,
      camere: addCamere,
      mesaj: addMesaj,
      valoare: Number(addValoare),
      status: addStatus,
      progress: addStatus === 'lucru' ? 10 : undefined
    });

    // Reset fields
    setAddNume('');
    setAddTelefon('');
    setAddEmail('');
    setAddLocalitate('');
    setAddTipLocatie('rezidential');
    setAddTipServiciu('nou');
    setAddCamere(4);
    setAddMesaj('');
    setAddValoare(2000);
    setAddStatus('nou');
    setShowAddModal(false);
  };

  const handleSaveValue = () => {
    if (selectedLead && newValueEdit) {
      onUpdateLeadValoare(selectedLead.id, Number(newValueEdit));
      setSelectedLead({ ...selectedLead, valoare: Number(newValueEdit) });
      setIsEditingValue(false);
    }
  };

  return (
    <div className="w-full text-left">
      <main className="pt-6 pb-24 px-6 md:px-16 max-w-[1280px] mx-auto">
        
        {/* Metric widgets row */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
          {/* Card 1 */}
          <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-150 flex flex-col gap-2">
            <span className="font-sans font-bold text-[10px] text-[#545f73] uppercase tracking-wider">
              Total Cereri Lună
            </span>
            <div className="flex items-end justify-between">
              <span className="text-3xl font-extrabold text-[#022717]">{totalLeads + 117}</span>
              <span className="text-xs text-emerald-800 bg-emerald-100 font-bold px-2 py-0.5 rounded-full">+12%</span>
            </div>
          </div>

          {/* Card 2 */}
          <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-150 flex flex-col gap-2">
            <span className="font-sans font-bold text-[10px] text-[#545f73] uppercase tracking-wider">
              Proiecte Active
            </span>
            <div className="flex items-end justify-between">
              <span className="text-3xl font-extrabold text-[#022717]">{activeProjects + 15}</span>
              <Bolt size={18} className="text-[#545f73] animate-pulse" />
            </div>
          </div>

          {/* Card 3 */}
          <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-150 flex flex-col gap-2">
            <span className="font-sans font-bold text-[10px] text-[#545f73] uppercase tracking-wider">
              Rata de Conversie
            </span>
            <div className="flex items-end justify-between">
              <span className="text-3xl font-extrabold text-[#022717]">{conversionRate}%</span>
              <div className="w-12 h-1.5 bg-gray-150 rounded-full overflow-hidden">
                <div 
                  className="bg-[#022717] h-full transition-all duration-500" 
                  style={{ width: `${Math.min(Number(conversionRate), 100)}%` }}
                ></div>
              </div>
            </div>
          </div>

          {/* Card 4 */}
          <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-150 flex flex-col gap-2">
            <span className="font-sans font-bold text-[10px] text-[#545f73] uppercase tracking-wider">
              Timp mediu răspuns
            </span>
            <div className="flex items-end justify-between">
              <span className="text-3xl font-extrabold text-[#022717]">2.4h</span>
              <Clock size={18} className="text-[#545f73]" />
            </div>
          </div>
        </div>

        {/* CRM Kanban Pipeline Scrollable */}
        <section className="mb-12">
          <div className="flex items-center justify-between mb-6">
            <div className="space-y-1 text-left">
              <h2 className="text-xl font-extrabold text-[#022717]">Flux Pipeline Cereri</h2>
              <p className="text-xs text-[#545f73]">Treceți cererile dintr-o etapă în alta selectându-le</p>
            </div>
            <button 
              onClick={() => setShowAddModal(true)}
              className="bg-[#022717] text-white px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 hover:bg-[#1a3d2b] transition-all cursor-pointer"
            >
              <Plus size={16} />
              Adaugă Cerere
            </button>
          </div>

          {/* Pipeline stages container */}
          <div className="overflow-x-auto pb-4 scrollbar-thin scrollbar-thumb-gray-200">
            <div className="flex gap-4 min-w-[1240px]">
              {STATUS_ORDER.map((status) => {
                const columnLeads = leadsByStatus[status] || [];
                return (
                  <div key={status} className="flex-1 min-w-[200px] flex flex-col gap-4 bg-[#f8f9fa] rounded-2xl p-3 border border-gray-200/50">
                    {/* Header */}
                    <div className="p-2 border-b-2 border-slate-200 flex items-center justify-between font-sans text-xs font-bold text-[#022717]">
                      <span className="tracking-wider text-[10px]">{STATUS_LABELS[status]}</span>
                      <span className="text-[10px] bg-white border border-gray-200 px-2 py-0.5 rounded-full text-[#545f73]">
                        {columnLeads.length}
                      </span>
                    </div>

                    {/* Cards List in column */}
                    <div className="flex flex-col gap-3 min-h-[300px]">
                      {columnLeads.map((lead) => {
                        const isSpecial = lead.status === 'programare' || lead.status === 'lucru';
                        return (
                          <motion.div
                            key={lead.id}
                            onClick={() => {
                              setSelectedLead(lead);
                              setNewValueEdit(lead.valoare.toString());
                              setIsEditingValue(false);
                            }}
                            whileHover={{ y: -2 }}
                            className={`p-4 rounded-xl shadow-sm border text-left cursor-pointer transition-all ${
                              isSpecial 
                                ? 'bg-emerald-950 text-white border-transparent' 
                                : 'bg-white text-[#022717] border-gray-200/60 hover:border-emerald-700/30'
                            }`}
                          >
                            <div className="space-y-1">
                              <p className="text-xs font-extrabold tracking-tight leading-tight line-clamp-1">{lead.nume}</p>
                              <p className={`text-[10px] line-clamp-1 font-medium ${isSpecial ? 'text-emerald-200' : 'text-[#545f73]'}`}>
                                {lead.tipServiciu === 'nou' ? 'Instalare completă' : lead.tipServiciu === 'upgrade' ? 'Upgrade sistem' : 'Mentenanță periodică'}
                              </p>
                              
                              {lead.programareData && (
                                <div className="mt-2 text-[9px] font-bold flex items-center gap-1 text-emerald-100 uppercase tracking-widest bg-white/15 px-2 py-0.5 rounded-md self-start w-max">
                                  <Calendar size={10} />
                                  <span>{lead.programareData}</span>
                                </div>
                              )}

                              {lead.status === 'lucru' && lead.progress !== undefined && (
                                <div className="mt-3 space-y-1">
                                  <div className="flex justify-between items-center text-[9px]">
                                    <span className="opacity-90">Progres instalare</span>
                                    <span>{lead.progress}%</span>
                                  </div>
                                  <div className="w-full h-1 bg-white/20 rounded-full overflow-hidden">
                                    <div className="bg-emerald-300 h-full rounded-full" style={{ width: `${lead.progress}%` }}></div>
                                  </div>
                                </div>
                              )}
                            </div>

                            <div className="flex items-center justify-between border-t border-gray-150/20 pt-3 mt-3">
                              <span className={`text-[9px] font-semibold ${isSpecial ? 'text-emerald-300' : 'text-gray-400'}`}>
                                {lead.data}
                              </span>
                              <Info size={12} className={isSpecial ? 'text-emerald-300' : 'text-[#545f73] shrink-0'} />
                            </div>
                          </motion.div>
                        );
                      })}

                      {columnLeads.length === 0 && (
                        <div className="flex-1 flex items-center justify-center p-6 border-2 border-dashed border-gray-200/50 rounded-xl">
                          <span className="text-[10px] text-gray-400 font-bold tracking-widest uppercase">Fără cereri</span>
                        </div>
                      )}
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </section>

        {/* Data Table clients search */}
        <section className="bg-white rounded-3xl shadow-sm border border-gray-150 overflow-hidden">
          <div className="p-6 border-b border-gray-150 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div className="space-y-1">
              <h3 className="text-lg font-extrabold text-[#022717]">Clienți Recenți</h3>
              <p className="text-xs text-[#545f73]">Vizualizați baza de date centralizată de clienți</p>
            </div>
            <div className="flex items-center gap-2">
              <div className="relative">
                <Search size={16} className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" />
                <input 
                  className="pl-10 pr-4 py-2.5 bg-[#f3f4f5]/75 border-none rounded-xl text-xs font-semibold focus:ring-1 focus:ring-[#022717] w-full md:w-64 outline-none"
                  placeholder="Caută client..." 
                  type="text"
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                />
              </div>
            </div>
          </div>
          
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="bg-[#f8f9fa] border-b border-gray-100">
                  <th className="px-6 py-4 font-bold text-[10px] text-[#545f73] tracking-wider">CLIENT</th>
                  <th className="px-6 py-4 font-bold text-[10px] text-[#545f73] tracking-wider">LOCAȚIE</th>
                  <th className="px-6 py-4 font-bold text-[10px] text-[#545f73] tracking-wider">STATUS CURENT</th>
                  <th className="px-6 py-4 font-bold text-[10px] text-[#545f73] tracking-wider text-right">VALOARE ESTIMATĂ</th>
                  <th className="px-6 py-4 font-bold text-[10px] text-[#545f73] tracking-wider text-center">ACȚIUNi</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {filteredTableLeads.map((lead) => {
                  const initialName = lead.nume.split(' ').map(n => n.charAt(0)).join('').toUpperCase().slice(0, 2);
                  return (
                    <tr key={lead.id} className="hover:bg-gray-50/50 transition-colors">
                      <td className="px-6 py-4">
                        <div className="flex items-center gap-3">
                          <div className="w-8 h-8 rounded-full bg-emerald-50 text-[#022717] border border-gray-100 flex items-center justify-center font-extrabold text-xs">
                            {initialName}
                          </div>
                          <div>
                            <p className="text-xs font-bold text-[#022717]">{lead.nume}</p>
                            <p className="text-[10px] text-[#545f73]">{lead.email}</p>
                          </div>
                        </div>
                      </td>
                      <td className="px-6 py-4 text-xs font-semibold text-[#545f73]">{lead.localitate}</td>
                      <td className="px-6 py-4">
                        <span className={`px-2.5 py-1 text-[9px] font-extrabold rounded-full uppercase tracking-wider ${
                          lead.status === 'finalizat' 
                            ? 'bg-emerald-100 text-emerald-800' 
                            : lead.status === 'lucru'
                            ? 'bg-blue-100 text-blue-800'
                            : lead.status === 'pierdut'
                            ? 'bg-rose-100 text-rose-800'
                            : 'bg-amber-100 text-amber-800'
                        }`}>
                          {STATUS_LABELS[lead.status]}
                        </span>
                      </td>
                      <td className="px-6 py-4 text-xs font-bold text-[#022717] text-right">
                        {lead.valoare.toLocaleString()} RON
                      </td>
                      <td className="px-6 py-4 text-center">
                        <button 
                          onClick={() => {
                            setSelectedLead(lead);
                            setNewValueEdit(lead.valoare.toString());
                            setIsEditingValue(false);
                          }}
                          className="text-gray-400 hover:text-[#022717] transition-colors p-1"
                          title="Detalii client"
                        >
                          <Info size={15} />
                        </button>
                      </td>
                    </tr>
                  );
                })}

                {filteredTableLeads.length === 0 && (
                  <tr>
                    <td colSpan={5} className="py-12 text-center text-xs text-gray-400 font-semibold uppercase tracking-wider">
                      Fără rezultate găsite
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </section>
      </main>

      {/* LEAD DETAILS / STATUS MANAGER MODAL */}
      <AnimatePresence>
        {selectedLead && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <motion.div 
              initial={{ opacity: 0, scale: 0.95 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, scale: 0.95 }}
              className="bg-white w-full max-w-lg rounded-3xl p-8 shadow-2xl border border-gray-100 space-y-6 max-h-[90vh] overflow-y-auto"
            >
              <div className="flex items-center justify-between border-b border-gray-100 pb-4">
                <div className="space-y-1 text-left">
                  <span className="text-[10px] font-extrabold text-[#545f73] uppercase tracking-wider">DETALII CERERE OFERTĂ</span>
                  <h3 className="text-lg font-bold text-[#022717]">{selectedLead.nume}</h3>
                </div>
                <button 
                  onClick={() => setSelectedLead(null)}
                  className="bg-gray-100 hover:bg-gray-200 text-[#022717] p-1.5 rounded-full transition-colors"
                >
                  <X size={16} />
                </button>
              </div>

              {/* Lead Details list info */}
              <div className="space-y-4 text-xs text-[#545f73] text-left">
                <div className="grid grid-cols-2 gap-4 bg-[#f8f9fa] p-4 rounded-2xl border border-gray-100">
                  <div className="space-y-1">
                    <span className="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">TELEFON</span>
                    <a href={`tel:${selectedLead.telefon}`} className="font-extrabold text-[#022717] hover:underline flex items-center gap-1.5">
                      <Phone size={12} />
                      {selectedLead.telefon}
                    </a>
                  </div>
                  <div className="space-y-1">
                    <span className="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">EMAIL</span>
                    <a href={`mailto:${selectedLead.email}`} className="font-extrabold text-[#022717] hover:underline flex items-center gap-1.5">
                      <Mail size={12} />
                      {selectedLead.email}
                    </a>
                  </div>
                </div>

                <div className="grid grid-cols-2 gap-4">
                  <div className="space-y-1">
                    <span className="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">LOCALITATE</span>
                    <span className="font-bold text-[#022717] flex items-center gap-1.5">
                      <MapPin size={12} />
                      {selectedLead.localitate}
                    </span>
                  </div>
                  <div className="space-y-1">
                    <span className="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">SPECIFICAȚII CAMERE</span>
                    <span className="font-bold text-[#022717]">
                      {selectedLead.camere} Unități CCTV recom.
                    </span>
                  </div>
                </div>

                <div className="grid grid-cols-2 gap-4">
                  <div className="space-y-1">
                    <span className="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">TIP IMOBIL</span>
                    <span className="font-bold text-[#022717] uppercase tracking-wider">
                      {selectedLead.tipLocatie}
                    </span>
                  </div>
                  <div className="space-y-1">
                    <span className="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">SERVICIU SOLICITAT</span>
                    <span className="font-bold text-[#022717] uppercase tracking-wider">
                      {selectedLead.tipServiciu}
                    </span>
                  </div>
                </div>

                {selectedLead.mesaj && (
                  <div className="space-y-1 border-t border-gray-100 pt-4">
                    <span className="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">MESAJ SPECIFIC CLIENT</span>
                    <p className="font-sans leading-relaxed bg-[#f8f9fa] border border-gray-200/50 p-4 rounded-xl text-xs font-semibold text-[#022717]">
                      {selectedLead.mesaj}
                    </p>
                  </div>
                )}

                {/* Status selector */}
                <div className="space-y-2 border-t border-gray-100 pt-4">
                  <span className="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">MUTĂ ÎN ALT STADIU PIPELINE</span>
                  <div className="flex flex-wrap gap-2">
                    {STATUS_ORDER.map((st) => (
                      <button
                        key={st}
                        onClick={() => {
                          onUpdateLeadStatus(selectedLead.id, st);
                          setSelectedLead({ ...selectedLead, status: st });
                        }}
                        className={`px-3 py-1.5 rounded-full text-[10px] font-bold border tracking-wider transition-all uppercase cursor-pointer ${
                          selectedLead.status === st 
                            ? 'bg-[#022717] text-white border-transparent' 
                            : 'bg-white text-[#545f73] border-gray-200 hover:border-emerald-700/30'
                        }`}
                      >
                        {STATUS_LABELS[st]}
                      </button>
                    ))}
                  </div>
                </div>

                {/* Edit Value */}
                <div className="space-y-2 border-t border-gray-100 pt-4">
                  <span className="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">VALOARE PROIECT (RON)</span>
                  {isEditingValue ? (
                    <div className="flex gap-2">
                      <div className="relative flex-1">
                        <DollarSign size={14} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input 
                          type="number" 
                          className="pl-8 pr-3 py-2 border rounded-xl w-full text-xs font-bold outline-none border-[#022717]"
                          value={newValueEdit}
                          onChange={(e) => setNewValueEdit(e.target.value)}
                        />
                      </div>
                      <button 
                        onClick={handleSaveValue}
                        className="bg-[#022717] text-white text-xs px-4 py-2 rounded-xl font-bold cursor-pointer"
                      >
                        Salvează
                      </button>
                      <button 
                        onClick={() => setIsEditingValue(false)}
                        className="bg-gray-100 text-[#022717] text-xs px-4 py-2 rounded-xl font-bold cursor-pointer"
                      >
                        Anulează
                      </button>
                    </div>
                  ) : (
                    <div className="flex items-center justify-between">
                      <span className="font-extrabold text-base text-[#022717]">
                        {selectedLead.valoare.toLocaleString()} RON
                      </span>
                      <button 
                        onClick={() => setIsEditingValue(true)}
                        className="text-xs hover:underline flex items-center gap-1 text-[#022717] font-semibold"
                      >
                        <Edit3 size={12} />
                        Modifică valoare
                      </button>
                    </div>
                  )}
                </div>
              </div>

              {/* Delete button action */}
              <div className="border-t border-gray-100 pt-6 flex gap-3">
                <button 
                  onClick={() => {
                    if (confirm('Sigur doriți să ștergeți această cerere definitiv?')) {
                      onDeleteLead(selectedLead.id);
                      setSelectedLead(null);
                    }
                  }}
                  className="bg-rose-50 text-rose-700 px-4 py-3 rounded-xl hover:bg-rose-100 font-bold text-xs tracking-wider transition-all flex items-center gap-2 cursor-pointer w-full justify-center"
                >
                  <Trash2 size={15} />
                  Șterge Cerere
                </button>
              </div>
            </motion.div>
          </div>
        )}
      </AnimatePresence>

      {/* ADD MANUAL LEAD MODAL */}
      <AnimatePresence>
        {showAddModal && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <motion.div 
              initial={{ opacity: 0, scale: 0.95 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, scale: 0.95 }}
              className="bg-white w-full max-w-lg rounded-3xl p-8 shadow-2xl border border-gray-100 space-y-6 max-h-[90vh] overflow-y-auto text-left"
            >
              <div className="flex items-center justify-between border-b border-gray-100 pb-4">
                <h3 className="text-lg font-bold text-[#022717]">Adaugă Cerere Manual</h3>
                <button 
                  onClick={() => setShowAddModal(false)}
                  className="bg-gray-100 hover:bg-gray-200 text-[#022717] p-1.5 rounded-full transition-colors"
                >
                  <X size={16} />
                </button>
              </div>

              <form onSubmit={handleAddManualSubmit} className="space-y-4 text-xs">
                <div className="grid grid-cols-2 gap-4">
                  <div className="flex flex-col gap-1">
                    <label className="font-bold text-[#545f73]" htmlFor="add_nume">Nume complet</label>
                    <input 
                      id="add_nume" 
                      required 
                      type="text" 
                      className="p-3 border rounded-xl outline-none focus:border-[#022717]"
                      placeholder="Ex: Ionescu Vasile" 
                      value={addNume}
                      onChange={(e) => setAddNume(e.target.value)}
                    />
                  </div>
                  <div className="flex flex-col gap-1">
                    <label className="font-bold text-[#545f73]" htmlFor="add_telefon">Telefon</label>
                    <input 
                      id="add_telefon" 
                      required 
                      type="tel" 
                      className="p-3 border rounded-xl outline-none focus:border-[#022717]"
                      placeholder="Ex: 0745000000" 
                      value={addTelefon}
                      onChange={(e) => setAddTelefon(e.target.value)}
                    />
                  </div>
                </div>

                <div className="grid grid-cols-2 gap-4">
                  <div className="flex flex-col gap-1">
                    <label className="font-bold text-[#545f73]" htmlFor="add_email">Email</label>
                    <input 
                      id="add_email" 
                      required 
                      type="email" 
                      className="p-3 border rounded-xl outline-none focus:border-[#022717]"
                      placeholder="Ex: vasile@email.com" 
                      value={addEmail}
                      onChange={(e) => setAddEmail(e.target.value)}
                    />
                  </div>
                  <div className="flex flex-col gap-1">
                    <label className="font-bold text-[#545f73]" htmlFor="add_localitate">Localitate</label>
                    <input 
                      id="add_localitate" 
                      required 
                      type="text" 
                      className="p-3 border rounded-xl outline-none focus:border-[#022717]"
                      placeholder="Ex: Pojorata" 
                      value={addLocalitate}
                      onChange={(e) => setAddLocalitate(e.target.value)}
                    />
                  </div>
                </div>

                <div className="grid grid-cols-2 gap-4">
                  <div className="flex flex-col gap-1">
                    <label className="font-bold text-[#545f73]" htmlFor="add_tipL">Tip locație</label>
                    <select 
                      id="add_tipL"
                      className="p-3 border rounded-xl outline-none focus:border-[#022717]"
                      value={addTipLocatie}
                      onChange={(e) => setAddTipLocatie(e.target.value as LocatieType)}
                    >
                      <option value="rezidential">Rezidențial</option>
                      <option value="comercial">Comercial</option>
                      <option value="industrial">Industrial</option>
                      <option value="public">Spațiu Public</option>
                    </select>
                  </div>
                  <div className="flex flex-col gap-1">
                    <label className="font-bold text-[#545f73]" htmlFor="add_tipS">Tip serviciu</label>
                    <select 
                      id="add_tipS"
                      className="p-3 border rounded-xl outline-none focus:border-[#022717]"
                      value={addTipServiciu}
                      onChange={(e) => setAddTipServiciu(e.target.value as ServiciuType)}
                    >
                      <option value="nou">Instalare completă</option>
                      <option value="upgrade">Upgrade</option>
                      <option value="mentenanta">Mentenanță</option>
                    </select>
                  </div>
                </div>

                <div className="grid grid-cols-2 gap-4">
                  <div className="flex flex-col gap-1">
                    <label className="font-bold text-[#545f73]" htmlFor="add_camere">Camere active</label>
                    <input 
                      id="add_camere" 
                      type="number" 
                      className="p-3 border rounded-xl outline-none focus:border-[#022717]"
                      min={1} 
                      value={addCamere}
                      onChange={(e) => setAddCamere(Number(e.target.value))}
                    />
                  </div>
                  <div className="flex flex-col gap-1">
                    <label className="font-bold text-[#545f73]" htmlFor="add_valoare">Valoare estimată (RON)</label>
                    <input 
                      id="add_valoare" 
                      type="number" 
                      className="p-3 border rounded-xl outline-none focus:border-[#022717]"
                      value={addValoare}
                      onChange={(e) => setAddValoare(Number(e.target.value))}
                    />
                  </div>
                </div>

                <div className="flex flex-col gap-1">
                  <label className="font-bold text-[#545f73]" htmlFor="add_status_box">Status pipeline inițial</label>
                  <select 
                    id="add_status_box"
                    className="p-3 border rounded-xl outline-none focus:border-[#022717] uppercase"
                    value={addStatus}
                    onChange={(e) => setAddStatus(e.target.value as LeadStatus)}
                  >
                    {STATUS_ORDER.map(st => (
                      <option key={st} value={st}>{STATUS_LABELS[st]}</option>
                    ))}
                  </select>
                </div>

                <div className="flex flex-col gap-1">
                  <label className="font-bold text-[#545f73]" htmlFor="add_mesaj">Detalii cerere / Notă tehnică</label>
                  <textarea 
                    id="add_mesaj" 
                    className="p-3 border rounded-xl outline-none focus:border-[#022717] resize-none" 
                    rows={3} 
                    placeholder="Inserați note despre proiect..."
                    value={addMesaj}
                    onChange={(e) => setAddMesaj(e.target.value)}
                  />
                </div>

                <div className="pt-4 flex gap-3">
                  <button 
                    type="submit"
                    className="flex-1 bg-[#022717] text-white py-3.5 rounded-xl font-bold cursor-pointer text-center hover:bg-[#1a3d2b] transition-all"
                  >
                    INSEREAZĂ CERERE
                  </button>
                  <button 
                    type="button"
                    onClick={() => setShowAddModal(false)}
                    className="px-6 bg-gray-100 text-[#022717] py-3.5 rounded-xl font-bold cursor-pointer text-center"
                  >
                    ANULEAZĂ
                  </button>
                </div>
              </form>
            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </div>
  );
}
