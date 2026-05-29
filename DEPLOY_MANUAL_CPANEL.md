# Ghid de Deploy Manual in cPanel - Optera Vision

Acest document descrie pasii exacti pentru implementarea si actualizarea manuala a site-ului **Optera Vision** (`optervision.ro`) pe un server de gazduire cPanel, unde structura folderelor este impartita pentru securitate sporita:
*   Directorul aplicatiei (Repository): `/home/optera_vision`
*   Directorul public (Web Root sibling): `/home/public_html`

---

## Pregatiri Initiale

1.  **Activare cPanel Git Version Control:** Asigurati-va ca repository-ul Git din cPanel este conectat la branch-ul principal si trage corect actualizarile in folderul `/home/optera_vision`.
2.  **Fisierul de Configurare (.env):** 
    *   Creati sau editati fisierul `.env` direct in `/home/optera_vision/.env`.
    *   **IMPORTANT:** Nu puneti niciodata fisierul `.env` in `/home/public_html`. El trebuie sa ramana ascuns in afara radacinii web pentru securitate maxima.
    *   Configurati conexiunea la baza de date (SQLite sau MySQL) si setarile mail SMTP din acest fisier.

---

## Pasii de Deploy Dupa Fiecare Actualizare Git (Pull)

Dupa ce cPanel Git a terminat de descarcat ultimele modificari din repository in `/home/optera_vision`, urmati cu atentie acesti pasi pentru a actualiza site-ul public:

### Pasul 1: Copierea fisierelor in public_html
Copiati **intreg continutul** din folderul:
`/home/optera_vision/public_html_deploy/`

Direct in folderul radacina al site-ului public:
`/home/public_html/`

> [!NOTE]
> Puteti efectua aceasta copiere rapid folosind **File Manager (Managerul de Fisiere)** din cPanel:
> 1. Intrati in `/home/optera_vision/public_html_deploy/`.
> 2. Selectati toate fisierele si folderele (`index.php`, `.htaccess`, `build`, `favicon.ico`, `robots.txt`, `README_STORAGE.txt`).
> 3. Apasati click dreapta -> **Copy** si introduceti calea `/public_html/`.

---

### Pasul 2: Verificarea fisierelor obligatorii
Asigurati-va ca urmatoarele fisiere si directoare sunt prezente si corecte in `/home/public_html/`:
*   `[ ]` `/home/public_html/index.php` (Fisierul de pornire, configurat sa incarce aplicatia din directorul vecin `/home/optera_vision`)
*   `[ ]` `/home/public_html/.htaccess` (Regulile Apache de rescriere URL)
*   `[ ]` `/home/public_html/build` (Directorul cu resursele CSS si JS compilate de Vite)
*   `[ ]` `/home/public_html/favicon.ico`
*   `[ ]` `/home/public_html/robots.txt`

---

### Pasul 3: Legarea folderului Storage (Fise Media)
Pentru ca imaginile incarcate din cCRM/Admin sa apara pe site-ul public, trebuie sa existe o legatura intre storage-ul aplicatiei si folderul public.

*   **Metoda Recomandata (Symlink):**
    Rulati urmatoarea comanda in terminal sau ca un Cron Job temporar in cPanel:
    ```bash
    ln -s /home/optera_vision/storage/app/public /home/public_html/storage
    ```
*   **Metoda Alternativa (Fallback):**
    Daca symlink-ul este blocat de providerul de hosting, copiati manual continutul din:
    `/home/optera_vision/storage/app/public/`
    in:
    `/home/public_html/storage/`

*(Consultati [README_STORAGE.txt](file:///home/optera_vision/public_html_deploy/README_STORAGE.txt) pentru instructiuni pas-cu-pas referitoare la Cron Jobs).*

---

### Pasul 4: Testarea Functionarii Site-ului

Dupa finalizarea pasilor de mai sus, accesati in browser:
1.  **Pagina Principala:** [https://optervision.ro](https://optervision.ro) — Verificati daca se incarca rapid si daca resursele de design (Vite JS/CSS si imagini) se afiseaza impecabil.
2.  **Configuratorul de Pret:** Faceti o simulare pe site pentru a verifica daca Alpine.js si trimiterea asincrona functioneaza fara erori.
3.  **Panoul de Administrare:** [https://optervision.ro/admin/login](https://optervision.ro/admin/login) — Conectati-va cu contul admin pentru a valida securitatea si conexiunea la baza de date.

---

## Comenzi Utile in cPanel Terminal (Optional)

Daca aveti acces SSH / Terminal in cPanel, puteti rula aceste comenzi in `/home/optera_vision` pentru a curata cache-ul si a optimiza viteza dupa deploy:
```bash
# Curatare cache aplicatie
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimizare performanta (Reconstructie cache)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
