import { Lead, Project } from './types';

export const initialProjects: Project[] = [
  {
    id: 'proj_1',
    titlu: 'Locuință privată',
    categorie: 'Rezidențial',
    descriere: 'Sistem integrat de supraveghere 4K și alarmă perimetrală pentru o vilă modernă. Automatizarea completă a accesului și controlul iluminatului de la distanță.',
    detaliiComplete: 'Am proiectat și instalat un sistem premium compus din 8 camere Dahua de 8MP (4K) cu tehnologie AI WizSense pentru detecție precisă oameni/vehicule, o centrală de alarmă antifurt wireless Ajax cu senzori de mișcare exteriori și barieră perimetrală. De asemenea, s-a integrat controlul automat al porților batante Nice și managementul luminilor ambientale de exterior prin aplicația mobilă dedicată.',
    imagine: 'https://lh3.googleusercontent.com/aida-public/AB6AXuD7PEtJ3A0CZt8rvPapadglOcicNVMwmrXFGOvhepUJN8Vj4fPTQyjecTRlzzjsYV-GKn_9f8Hb-Oi9J02lWr1vkNZJq8X3hdLKi_lDgpk0yhzfJcSjHWIbkZJ2R3a5TB12XjiN8XvXFDirApN8zanrZD8o-fna_6Oon1qCfwo8D0IRioQs1aow4k6BU4vNJn8mAWRgR7k5JrwPUvlzmPa1E_SS-0Di2BhoANJkgCVdmrWOwc58Olmtnw_5D0lGp3-_8WbcxVdvbzCN',
    locatie: 'Câmpulung Moldovenesc, Suceava',
    camereInstalate: 8,
    sistemeFolosite: ['Camere IP 4K WizSense', 'Alarma Wireless Ajax', 'Automatizare Porți Nice', 'Smart Home Controls']
  },
  {
    id: 'proj_2',
    titlu: 'Birou / hală / depozit',
    categorie: 'Industrial',
    descriere: 'Protecție completă pentru fluxuri logistice: control acces biometric, detecție incendiu și monitorizare video inteligentă pentru depozite de mari dimensiuni.',
    detaliiComplete: 'Pentru acest hub logistic de peste 2000 mp, s-a optat pentru o infrastructură cu cablare pe fibră optică pentru magistrații, o unitate NVR cu 32 de canale și camere de exterior rezistente tip Bullet de 5MP Hikvision ColorVu (imagine color 24/7). Sistemul este dublat de senzori de fum adresabili gazați cu centrală de alarmă la incendiu și control acces cu amprentă/carduri RFID pentru personal.',
    imagine: 'https://lh3.googleusercontent.com/aida-public/AB6AXuC5fvhitvLKbm_PKT6_0lxbHmVaJwo01Q1XM5MNQEs9WkBbVIvHsLJFuzMHSXUh9gl3snBuFiug2T7S8GR3CmGvgxncpAr1wpFPJKKHd1DHI2pHBY9AVqlqV42WIkohUQGFry11zUk0MZAc75tWO5j8SJH1vVgdTJFPJM0ujvC5eyA6jwbLwB9fbVVwYNFs8wHvG4iaFKEWf6jLYOBXm6WAn4mmdLET8xnLgHpOos7F7wLcQpt6MszdFqAIQrR4JjlKIVXBhmUGqahL',
    locatie: 'Zona Industrială Câmpulung Moldovenesc',
    camereInstalate: 24,
    sistemeFolosite: ['Camere Hikvision ColorVu 5MP', 'NVR 32 Canale H.265+', 'Sistem Detecție Incendiu', 'Turnicheți Control Acces Biometric']
  },
  {
    id: 'proj_3',
    titlu: 'Spațiu comercial',
    categorie: 'Retail',
    descriere: 'Soluții antifurt și monitorizare trafic clienți pentru showroom-uri premium. Estetică discretă care nu perturbă designul interior.',
    detaliiComplete: 'Montaj de camere ultra-discrete tip Dome cu unghi de vizualizare panoramic (Fisheye) de 360° integrate direct în designul de tavan suspendat. Pe lângă monitorizarea securității bunurilor și caselor de marcat, software-ul inclus oferă analize de heatmap (zonele cele mai vizitate) și People Counting (numărarea fluxului de vizitatori) pentru marketing și optimizarea personalului.',
    imagine: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBpme7bq-X3XpFeun3kSkrnX5GVZtAqln2SFahhdIMedEDC0JxsHn7v9zanpUmEsUGxVVwA1IbpzKFlW0jHxt-Vbx73EaHzXemN3-3l7jZ2sEE5d4e5CcqUfryLKpmefYVJWuxrp1LW7GpGy8XaSSm6-xrCVZLIH5_UEBArEl2ZNKjWwQdlCx3GLUPSJMwe8LfS8fHNi0uQQZwza5Zghv8E4Jmm5ZJTpmKYdEllMzVE8-5a2tq_S5E68XuzV2ni3wwDwd-9ngXJ02HJ',
    locatie: 'Suceava (Bulevardul Principal)',
    camereInstalate: 6,
    sistemeFolosite: ['Camere Dome Fisheye 6MP', 'Analize Inteligente Heatmap', 'Sistem Porți Antifurt', 'NVR 8 canale cu backup securizat']
  },
  {
    id: 'proj_4',
    titlu: 'Pensiune / unitate turistică',
    categorie: 'Turism',
    descriere: 'Check-in automatizat cu yale inteligente și supraveghere video a zonelor comune pentru siguranța turiștilor și a personalului.',
    detaliiComplete: 'O lucrare specială realizată în stil tradițional din lemn, unde provocarea a fost ascunderea completă a cablajelor fără a afecta estetica naturală. S-au instalat yale inteligente Yale Linus inteligente integrabile cu Airbnb (coduri de acces generate automat pe durata rezervării) și camere exterioare rezistente la temperaturi extreme și viscol, asigurând monitorizarea parcării și a căilor de acces.',
    imagine: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCY6T6WvyW4QXXZYWtvR8H2bwmtoTWlHvT-5M0ZAVenLAi8ee0LnXyYqaAVIYvE18tJcRktesMvTvbZL1nB10pZrt6iIlUdQraRAp9dYtW_fBg3RtJeHZIMSLrhQAVP0WqL9IETBe_QYU7N844D6MrGiX714ZHP5cstqt0eWCwPWR89vmtFGAKIBaKXeXiXh_qsWZOA619mDpucrFe_5Swn_X-o5O0AAverTphqP_l4VcOI158trNWc84Mwi9pE8im_xV_bnsit_Wrj',
    locatie: 'Gura Humorului, Bucovina',
    camereInstalate: 12,
    sistemeFolosite: ['Camere Premium IP de exterior 4MP', 'Yale Inteligente cu control PIN/Aplicație', 'Rețea Wi-Fi Mesh Outdoor', 'Centrală Monitorizare Video']
  }
];

export const initialLeads: Lead[] = [
  {
    id: 'lead_1',
    nume: 'Popescu Ion',
    telefon: '0745123456',
    email: 'ion.popescu@gmail.com',
    localitate: 'Câmpulung Moldovenesc',
    tipLocatie: 'rezidential',
    tipServiciu: 'nou',
    camere: 4,
    mesaj: 'Sistem Supraveghere Vilă nou construită. Doresc monitorizare exterior totală și pregătire cablaj în faza de finisaje.',
    data: 'Azi, 10:24',
    status: 'nou',
    valoare: 3200
  },
  {
    id: 'lead_2',
    nume: 'Mihai Andrei',
    telefon: '0722987654',
    email: 'andrei.mihai@outlook.com',
    localitate: 'Pojorâta',
    tipLocatie: 'rezidential',
    tipServiciu: 'nou',
    camere: 2,
    mesaj: 'Automatizare pentru porți batante de fier forjat de 3.5m total. Plus cameră video de exterior spre poartă.',
    data: 'Azi, 08:15',
    status: 'nou',
    valoare: 2450
  },
  {
    id: 'lead_3',
    nume: 'Elena Vasilescu',
    telefon: '0753001122',
    email: 'elena.v@yahoo.com',
    localitate: 'Câmpulung Moldovenesc',
    tipLocatie: 'rezidential',
    tipServiciu: 'upgrade',
    camere: 3,
    mesaj: 'Sistem Alarmă Apartament etaj 1. Am deja niște senzori dar sunt pe baterie vechi de 8 ani și vreau ceva modern controlabil de pe telefon.',
    data: 'Ieri, 16:45',
    status: 'contactat',
    valoare: 1800
  },
  {
    id: 'lead_4',
    nume: 'Hotel Central',
    telefon: '0740556677',
    email: 'contact@hotelcentral-campulung.ro',
    localitate: 'Câmpulung Moldovenesc',
    tipLocatie: 'comercial',
    tipServiciu: 'upgrade',
    camere: 16,
    mesaj: 'Upgrade 16 camere IP. Înlocuirea celor analogice vechi și instalarea unui server NVR performant pentru reținerea înregistrărilor conform legii (30 zile).',
    data: '25 Mai 2026',
    status: 'programare',
    valoare: 12500,
    programareData: '24 Iun, 14:00'
  },
  {
    id: 'lead_5',
    nume: 'Resedința S.V.',
    telefon: '0752112233',
    email: 'sergiu.val@gmail.com',
    localitate: 'Vama, Suceava',
    tipLocatie: 'rezidential',
    tipServiciu: 'nou',
    camere: 6,
    mesaj: 'Instalare Smart Home și camere de exterior. Doresc integrare completă cu iluminatul inteligent și alarmă antifurt Ajax.',
    data: '22 Mai 2026',
    status: 'lucru',
    valoare: 8400,
    progress: 65
  },
  {
    id: 'lead_6',
    nume: 'Constantin Marinescu',
    telefon: '0744889900',
    email: 'office@cm-security.ro',
    localitate: 'Câmpulung Moldovenesc',
    tipLocatie: 'industrial',
    tipServiciu: 'nou',
    camere: 12,
    mesaj: 'Detecție perimetrală și camere video de înaltă rezoluție pentru curte logistică.',
    data: '15 Mai 2026',
    status: 'finalizat',
    valoare: 4200
  },
  {
    id: 'lead_7',
    nume: 'Asociația de Locatari nr. 4',
    telefon: '0731445566',
    email: 'contact@asoc4.ro',
    localitate: 'Suceava',
    tipLocatie: 'public',
    tipServiciu: 'mentenanta',
    camere: 5,
    mesaj: 'Mentenanță lunară sistem de supraveghere pe 2 scări de bloc și parcare exterioară.',
    data: '10 Mai 2026',
    status: 'lucru',
    valoare: 1500,
    progress: 100
  }
];
