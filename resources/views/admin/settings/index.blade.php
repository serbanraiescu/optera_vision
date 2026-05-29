@extends('layouts.admin')

@section('title', 'Setări Brand & Site')
@section('section_title', 'Configurări Globale')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'branding' }">

    @if(session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-850 border border-emerald-250/50 rounded-2xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabs Navigation Menu -->
    <div class="bg-white border border-slate-100 rounded-3xl p-2 shadow-sm flex flex-wrap gap-1">
        <button @click="activeTab = 'branding'" :class="activeTab === 'branding' ? 'bg-emerald-850 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="h-10 px-5 text-xs font-bold rounded-2xl transition-all focus:outline-none">
            🎨 Branding & Aspect
        </button>
        <button @click="activeTab = 'company'" :class="activeTab === 'company' ? 'bg-emerald-850 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="h-10 px-5 text-xs font-bold rounded-2xl transition-all focus:outline-none">
            🏢 Detalii Companie
        </button>
        <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'bg-emerald-850 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="h-10 px-5 text-xs font-bold rounded-2xl transition-all focus:outline-none">
            📞 Link-uri & Hărți
        </button>
        <button @click="activeTab = 'legal'" :class="activeTab === 'legal' ? 'bg-emerald-850 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="h-10 px-5 text-xs font-bold rounded-2xl transition-all focus:outline-none">
            ⚖️ Legal & ANPC
        </button>
        <button @click="activeTab = 'system'" :class="activeTab === 'system' ? 'bg-emerald-850 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="h-10 px-5 text-xs font-bold rounded-2xl transition-all focus:outline-none">
            ⚙️ Sistem & Cache
        </button>
    </div>

    <!-- TAB 1: BRANDING & COLORS (Admin-only restriction check) -->
    <div x-show="activeTab === 'branding'" class="space-y-6">
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <div class="border-b border-slate-100 pb-3 mb-6">
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Branding & Aspect Site</h3>
                <span class="text-[10px] text-slate-400 mt-2 font-medium block">Logo-uri, favicon și paletă de culori (Rol Admin necesar)</span>
            </div>

            @if(!auth()->user()->isAdmin())
                <div class="p-4 bg-rose-50/50 border border-rose-100 text-rose-800 text-xs font-semibold rounded-2xl">
                    ⚠️ Rol neautorizat. Doar administratorii pot modifica paleta de culori și branding-ul site-ului.
                </div>
            @else
                <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="group" value="branding">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Site Name -->
                        <div class="flex flex-col gap-1.5">
                            <label for="site_name" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Nume Site</label>
                            <input type="text" name="site.name" id="site_name" value="{{ $branding['site.name'] }}" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:outline-none transition-all font-semibold" required>
                        </div>

                        <!-- Brand Colors Grid -->
                        <div class="grid grid-cols-3 gap-3">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Culoare Principală</label>
                                <input type="color" name="brand.primary_color" value="{{ $branding['brand.primary_color'] }}" class="w-full h-10 p-1 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Culoare Secundară</label>
                                <input type="color" name="brand.secondary_color" value="{{ $branding['brand.secondary_color'] }}" class="w-full h-10 p-1 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Accent Color</label>
                                <input type="color" name="brand.accent_color" value="{{ $branding['brand.accent_color'] }}" class="w-full h-10 p-1 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer">
                            </div>
                        </div>

                        <!-- Upload Logos Text Inputs for Media Selector Integration -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Cale Logo Header</label>
                            <input type="text" name="brand.logo" value="{{ $branding['brand.logo'] }}" placeholder="media/general/logo.webp..." class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold">
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Cale Favicon (32x32)</label>
                            <input type="text" name="brand.favicon" value="{{ $branding['brand.favicon'] }}" placeholder="media/general/favicon.webp..." class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-5 h-10 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-xl transition-all shadow-md focus:outline-none">
                            💾 Salvează Branding
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <!-- TAB 2: COMPANY DETAILS (Open to Admin and Operator) -->
    <div x-show="activeTab === 'company'" class="space-y-6">
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <div class="border-b border-slate-100 pb-3 mb-6">
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Informații Companie & Registru</h3>
                <span class="text-[10px] text-slate-400 mt-2 font-medium block">Date oficiale fiscale, program și adrese fizice</span>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="group" value="company">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="company_name" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Denumire Companie</label>
                        <input type="text" name="company.name" id="company_name" value="{{ $company['company.name'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                    <div>
                        <label for="company_cui" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Cod Unic Identificare (CUI)</label>
                        <input type="text" name="company.cui" id="company_cui" value="{{ $company['company.cui'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                    <div>
                        <label for="company_reg" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Nr. Registrul Comerțului</label>
                        <input type="text" name="company.reg_number" id="company_reg" value="{{ $company['company.reg_number'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>

                    <div>
                        <label for="company_address" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Adresă Sediu</label>
                        <input type="text" name="company.address" id="company_address" value="{{ $company['company.address'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                    <div>
                        <label for="company_locality" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Localitate</label>
                        <input type="text" name="company.locality" id="company_locality" value="{{ $company['company.locality'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                    <div>
                        <label for="company_county" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Județ</label>
                        <input type="text" name="company.county" id="company_county" value="{{ $company['company.county'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>

                    <div>
                        <label for="company_phone" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Telefon Contact</label>
                        <input type="text" name="company.phone" id="company_phone" value="{{ $company['company.phone'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                    <div>
                        <label for="company_whatsapp" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">WhatsApp Direct</label>
                        <input type="text" name="company.whatsapp" id="company_whatsapp" value="{{ $company['company.whatsapp'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                    <div>
                        <label for="company_email" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">E-mail Companie</label>
                        <input type="email" name="company.email" id="company_email" value="{{ $company['company.email'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-3">
                    <div>
                        <label for="company_hours" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Program Lucru</label>
                        <input type="text" name="company.hours" id="company_hours" value="{{ $company['company.hours'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                    <div>
                        <label for="company_areas" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Zone Deservite</label>
                        <input type="text" name="company.areas" id="company_areas" value="{{ $company['company.areas'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-5 h-10 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-xl transition-all shadow-md focus:outline-none">
                        💾 Salvează Date Companie
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 3: CONTACT & LINKS -->
    <div x-show="activeTab === 'contact'" class="space-y-6">
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <div class="border-b border-slate-100 pb-3 mb-6">
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Link-uri Contact & Google Maps</h3>
                <span class="text-[10px] text-slate-400 mt-2 font-medium block">Adrese de socializare, număr de urgență și embed cod hartă</span>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="group" value="contact">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_fb" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Facebook URL</label>
                        <input type="text" name="contact.facebook" id="contact_fb" value="{{ $contact['contact.facebook'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                    <div>
                        <label for="contact_ig" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Instagram URL</label>
                        <input type="text" name="contact.instagram" id="contact_ig" value="{{ $contact['contact.instagram'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                    <div>
                        <label for="contact_whatsapp_link" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">WhatsApp API Link (ex: https://wa.me/...)</label>
                        <input type="text" name="contact.whatsapp_link" id="contact_whatsapp_link" value="{{ $contact['contact.whatsapp_link'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                    <div>
                        <label for="contact_emergency" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Telefon Urgențe / Asistență Tehnică</label>
                        <input type="text" name="contact.emergency_phone" id="contact_emergency" value="{{ $contact['contact.emergency_phone'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                </div>

                <div class="flex flex-col gap-1.5 pt-2">
                    <label for="contact_maps" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Google Maps Embed URL (Doar valoarea din src="...")</label>
                    <textarea name="contact.google_maps" id="contact_maps" rows="4" class="w-full p-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none leading-relaxed">{{ $contact['contact.google_maps'] }}</textarea>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-5 h-10 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-xl transition-all shadow-md focus:outline-none">
                        💾 Salvează Hărți & Social
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 4: LEGAL & COOKIES -->
    <div x-show="activeTab === 'legal'" class="space-y-6">
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <div class="border-b border-slate-100 pb-3 mb-6">
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Relații Consumatori & Banners</h3>
                <span class="text-[10px] text-slate-400 mt-2 font-medium block">Link-uri oficiale ANPC, SOL și text alertă cookies</span>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="group" value="legal">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="legal_anpc" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">ANPC URL Link</label>
                        <input type="text" name="legal.anpc" id="legal_anpc" value="{{ $legal['legal.anpc'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                    <div>
                        <label for="legal_sol" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">SOL Comisia Europeană URL Link</label>
                        <input type="text" name="legal.sol" id="legal_sol" value="{{ $legal['legal.sol'] }}" class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none">
                    </div>
                </div>

                <div class="flex flex-col gap-1.5 pt-2">
                    <label for="legal_cookies" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Text Notificare Consentiment Cookie (Banner footer)</label>
                    <textarea name="legal.cookies_text" id="legal_cookies" rows="3" class="w-full p-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:outline-none leading-relaxed">{{ $legal['legal.cookies_text'] }}</textarea>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-5 h-10 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-xl transition-all shadow-md focus:outline-none">
                        💾 Salvează Legal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 5: SYSTEM & CACHE (Admin-only restriction check) -->
    <div x-show="activeTab === 'system'" class="space-y-6">
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
            <div class="border-b border-slate-100 pb-3 mb-6">
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Administrare Sistem & Cache</h3>
                <span class="text-[10px] text-slate-400 mt-2 font-medium block">Control fișiere, sitemap-uri și golire cache (Rol Admin necesar)</span>
            </div>

            @if(!auth()->user()->isAdmin())
                <div class="p-4 bg-rose-50/50 border border-rose-100 text-rose-800 text-xs font-semibold rounded-2xl">
                    ⚠️ Rol neautorizat. Doar administratorii au drepturi de control pe fișierele de cache sau setările de sitemap.
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                    
                    <!-- Left Side: Controlled Caches flush actions -->
                    <div class="space-y-6 border border-slate-150/40 p-5 rounded-2xl">
                        <h4 class="text-xs font-bold text-slate-800">Acțiuni Cache Controlate</h4>
                        <p class="text-[10px] text-slate-400 leading-normal">
                            Pentru a forța serverul de producție să citească setările din fișierele actualizate, folosește controalele prestabilite de mai jos. Acestea rulează în deplină siguranță comenzi artisan dedicate.
                        </p>

                        <div class="flex flex-wrap gap-2 pt-2">
                            <!-- Clean full cache -->
                            <form action="{{ route('admin.system.cache.clear') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4.5 h-10 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-xl shadow transition-all focus:outline-none">
                                    🗑️ Golește Cache-urile Complete
                                </button>
                            </form>

                            <!-- Rebuild sitemap -->
                            <form action="{{ route('admin.system.sitemap.rebuild') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4.5 h-10 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl shadow-sm transition-all focus:outline-none">
                                    🔄 Reconstruiește Sitemap.xml
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right Side: Server SMTP parameters summary -->
                    <div class="space-y-4 border border-slate-150/40 p-5 rounded-2xl">
                        <h4 class="text-xs font-bold text-slate-800">Sumar Configurație E-mail (SMTP)</h4>
                        <p class="text-[10px] text-slate-400 leading-normal">
                            Valori citite din fișierul <strong>.env</strong> al serverului de producție (Read-Only pentru siguranță):
                        </p>

                        <div class="space-y-2 text-[10px] text-slate-650 font-semibold">
                            <div>
                                <span class="text-slate-400 font-medium block">Host SMTP</span>
                                <span class="text-slate-805 block mt-0.5">{{ config('mail.mailers.smtp.host') ?: 'Nedefinit' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 font-medium block">Port SMTP & Criptare</span>
                                <span class="text-slate-805 block mt-0.5">{{ config('mail.mailers.smtp.port') }} ({{ config('mail.mailers.smtp.encryption') ?: 'Niciuna' }})</span>
                            </div>
                            <div>
                                <span class="text-slate-400 font-medium block">Username SMTP</span>
                                <span class="text-slate-805 block mt-0.5">{{ config('mail.mailers.smtp.username') ?: 'Nedefinit' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
