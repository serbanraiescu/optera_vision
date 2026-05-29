export type ViewType = 'home' | 'servicii' | 'portofoliu' | 'oferta' | 'crm';

export type LocatieType = 'rezidential' | 'comercial' | 'industrial' | 'public';
export type ServiciuType = 'nou' | 'upgrade' | 'mentenanta';

export type LeadStatus =
  | 'nou'
  | 'contactat'
  | 'programare'
  | 'ofertat'
  | 'acceptat'
  | 'lucru'
  | 'finalizat'
  | 'pierdut';

export interface Lead {
  id: string;
  nume: string;
  telefon: string;
  email: string;
  localitate: string;
  tipLocatie: LocatieType;
  tipServiciu: ServiciuType;
  camere: number;
  mesaj?: string;
  data: string;
  status: LeadStatus;
  valoare: number;
  programareData?: string;
  progress?: number; // for "lucru" status progress bar
}

export interface Project {
  id: string;
  titlu: string;
  categorie: string;
  descriere: string;
  detaliiComplete: string;
  imagine: string;
  locatie: string;
  camereInstalate: number;
  sistemeFolosite: string[];
}
