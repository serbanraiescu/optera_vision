<div x-data="quoteConfigurator()" class="w-full">
    <!-- Success Screen -->
    <div x-show="status === 'success'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" class="py-16 text-center space-y-6 flex flex-col items-center">
        <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center shadow-inner">
            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="space-y-2">
            <h3 class="text-2xl font-bold text-[#022717]">Cerere trimisă cu succes!</h3>
            <p class="text-sm text-[#545f73] max-w-sm leading-relaxed">
                Vă mulțumim pentru interes! Un inginer Optera Vision va analiza cerințele dvs. tehnice pentru sistemul de <strong class="text-[#022717] font-semibold" x-text="form.camere + ' camere'"></strong> și vă va contacta în maximum 24 de ore la numărul sau adresa furnizată.
            </p>
        </div>
        
        <div class="pt-4 flex flex-col sm:flex-row gap-3 w-full max-w-xs">
            <button @click="resetForm()" type="button" class="flex-1 text-center py-3 px-4 bg-[#022717] text-white hover:bg-[#1a3d2b] font-bold text-xs tracking-wider rounded-xl transition-all cursor-pointer">
                CONFIGURATOR NOU
            </button>
            <a href="https://wa.me/{{ str_replace('+', '', str_replace(' ', '', setting('company.whatsapp'))) }}" target="_blank" rel="noopener" class="flex-1 text-center py-3 px-4 border border-[#022717] text-[#022717] font-bold text-xs rounded-xl hover:bg-[#022717]/5 transition-all flex items-center justify-center gap-1.5">
                Scrie pe WhatsApp
            </a>
        </div>
    </div>

    <!-- Active Form View -->
    <form x-show="status !== 'success'" @submit.prevent="submitForm()" class="flex flex-col gap-6 text-left">
        @csrf
        
        <!-- Honeypot Field (Spam protection, must remain empty and invisible) -->
        <div class="hidden" style="display:none !important;">
            <input type="text" name="website" x-model="form.website" autocomplete="off" tabIndex="-1">
        </div>

        <div class="space-y-1">
            <h2 class="text-xl font-extrabold text-[#022717]">Solicitare Ofertă Personalizată</h2>
            <p class="text-xs text-[#545f73]">Specifică detaliile proprietății pentru precizie sporită</p>
        </div>

        <!-- Validation errors block -->
        <div x-show="status === 'error'" x-transition class="p-4 bg-rose-50/80 border border-rose-100 rounded-xl text-xs text-rose-800 leading-relaxed font-semibold">
            <div class="flex items-center gap-2 mb-1">
                <span>⚠️</span>
                <span class="font-bold">Eroare la trimitere:</span>
            </div>
            <ul class="list-disc pl-4 space-y-1">
                <template x-for="err in errors">
                    <li x-text="err"></li>
                </template>
            </ul>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nume -->
            <div class="flex flex-col gap-2">
                <label class="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" for="nume">
                    Nume Complet
                </label>
                <input id="nume" x-model="form.nume" required class="bg-[#f3f4f5]/65 border border-transparent hover:border-[#545f73]/20 focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all" placeholder="Ex: Popescu Ion" type="text">
            </div>

            <!-- Telefon -->
            <div class="flex flex-col gap-2">
                <label class="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" for="telefon">
                    Telefon
                </label>
                <input id="telefon" x-model="form.telefon" required class="bg-[#f3f4f5]/65 border border-transparent hover:border-[#545f73]/20 focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all" placeholder="07xx xxx xxx" type="tel">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Email -->
            <div class="flex flex-col gap-2">
                <label class="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" for="email">
                    Email
                </label>
                <input id="email" x-model="form.email" required class="bg-[#f3f4f5]/65 border border-transparent hover:border-[#545f73]/20 focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all" placeholder="contact@exemplu.ro" type="email">
            </div>

            <!-- Localitate -->
            <div class="flex flex-col gap-2">
                <label class="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" for="localitate">
                    Localitate
                </label>
                <input id="localitate" x-model="form.localitate" required class="bg-[#f3f4f5]/65 border border-transparent hover:border-[#545f73]/20 focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all" placeholder="Ex: Câmpulung Moldovenesc" type="text">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tip Locatie -->
            <div class="flex flex-col gap-2">
                <label class="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" for="tip_locatie">
                    Tip locație
                </label>
                <select id="tip_locatie" x-model="form.tip_locatie" class="bg-[#f3f4f5]/65 border border-transparent focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all cursor-pointer">
                    <option value="rezidential">Rezidențial (Casă/Apartament)</option>
                    <option value="comercial">Comercial (Birou/Magazin)</option>
                    <option value="industrial">Industrial (Depozit/Fabrică)</option>
                    <option value="public">Spațiu Public</option>
                </select>
            </div>

            <!-- Tip Serviciu -->
            <div class="flex flex-col gap-2">
                <label class="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" for="tip_serviciu">
                    Sistem nou sau upgrade?
                </label>
                <select id="tip_serviciu" x-model="form.tip_serviciu" class="bg-[#f3f4f5]/65 border border-transparent focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all cursor-pointer">
                    <option value="nou">Proiect nou (Instalare completă)</option>
                    <option value="upgrade">Upgrade sistem existent</option>
                    <option value="mentenanta">Mentenanță periodică</option>
                </select>
            </div>
        </div>

        <!-- Camere slider -->
        <div class="flex flex-col gap-2">
            <div class="flex justify-between items-center">
                <label class="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" for="camere">
                    Număr aproximativ camere
                </label>
                <span class="text-[10px] font-extrabold text-[#022717] uppercase tracking-wider bg-emerald-50 px-2 py-0.5 rounded-md">recomandat pentru spatiu</span>
            </div>
            <div class="flex items-center gap-4 bg-[#f3f4f5]/65 rounded-xl px-5 py-3.5 border border-transparent">
                <input id="camere" x-model="form.camere" class="flex-grow accent-[#022717] cursor-pointer" max="32" min="1" type="range">
                <span class="font-bold text-base text-[#022717] min-w-[2.5ch] text-right" x-text="form.camere"></span>
            </div>
        </div>

        <!-- Mesaj -->
        <div class="flex flex-col gap-2">
            <label class="font-sans font-bold text-[10px] tracking-wider text-[#545f73] uppercase" for="mesaj">
                Mesaj (Opțional)
            </label>
            <textarea id="mesaj" x-model="form.mesaj" class="bg-[#f3f4f5]/65 border border-transparent hover:border-[#545f73]/20 focus:bg-white focus:border-[#1a3d2b] focus:ring-1 focus:ring-[#1a3d2b]/20 rounded-xl px-4 py-3 text-xs md:text-sm text-[#022717] outline-none font-medium transition-all resize-none" placeholder="Detalii suplimentare despre cerințele dvs..." rows="4"></textarea>
        </div>

        <!-- GDPR Checkbox -->
        <div class="flex items-start gap-3 py-2 cursor-pointer select-none">
            <input id="gdpr" x-model="form.gdpr" required class="mt-1 rounded border-gray-300 text-[#022717] focus:ring-[#022717] h-4 w-4 cursor-pointer" type="checkbox">
            <label for="gdpr" class="font-sans text-xs md:text-sm text-[#545f73] leading-snug cursor-pointer select-none">
                Sunt de acord cu prelucrarea datelor cu caracter personal în baza regulamentului general privind protecția datelor.
            </label>
        </div>

        <!-- Submit button -->
        <button :disabled="status === 'submitting'" class="bg-[#022717] text-white font-bold text-xs tracking-wider py-4 px-8 rounded-xl active:scale-[0.98] transition-all flex items-center justify-center gap-3 cursor-pointer select-none hover:bg-[#1a3d2b] hover:shadow-lg disabled:opacity-75 disabled:cursor-not-allowed" type="submit">
            <span x-text="status === 'submitting' ? 'SE PROCESEAZĂ...' : 'TRIMITE CEREREA DE OFERTĂ'"></span>
            <template x-if="status === 'submitting'">
                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </template>
            <template x-if="status !== 'submitting'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </template>
        </button>
    </form>
</div>

<script>
    function quoteConfigurator() {
        return {
            status: 'idle',
            errors: [],
            form: {
                nume: '',
                telefon: '',
                email: '',
                localitate: '',
                tip_locatie: 'rezidential',
                tip_serviciu: 'nou',
                camere: 4,
                mesaj: '',
                gdpr: false,
                website: '' // honeypot
            },
            resetForm() {
                this.form.nume = '';
                this.form.telefon = '';
                this.form.email = '';
                this.form.localitate = '';
                this.form.tip_locatie = 'rezidential';
                this.form.tip_serviciu = 'nou';
                this.form.camere = 4;
                this.form.mesaj = '';
                this.form.gdpr = false;
                this.form.website = '';
                this.status = 'idle';
                this.errors = [];
            },
            submitForm() {
                this.status = 'submitting';
                this.errors = [];

                fetch('{{ route("quote.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        if (response.status === 422) {
                            this.errors = Object.values(data.errors).flat();
                        } else {
                            this.errors = [data.message || 'A apărut o eroare la trimitere. Vă rugăm să încercați din nou.'];
                        }
                        this.status = 'error';
                    } else {
                        this.status = 'success';
                    }
                })
                .catch(error => {
                    this.errors = ['Eroare de rețea. Verificați conexiunea la internet și încercați din nou.'];
                    this.status = 'error';
                });
            }
        }
    }
</script>
