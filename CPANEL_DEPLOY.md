# Ghid de Deploy pe cPanel Shared Hosting — Optera Vision

Acest ghid oferă instrucțiuni pas cu pas pentru instalarea și actualizarea aplicației Optera Vision pe un mediu de găzduire partajată (cPanel) folosind structura securizată cu nucleul în afara folderului public și cPanel Git Version Control.

---

## 1. Structura Target pe Server

Pentru securitate maximă (fără expunerea fișierelor `.env` sau a logurilor de sistem), codul aplicației va fi împărțit în două directoare din contul dumneavoastră cPanel:

```text
/home/USER/
    ├── optervision_app/       <-- Codul sursă Laravel core (nucleul) securizat
    │   ├── app/, bootstrap/, config/, database/, resources/, routes/
    │   ├── vendor/            <-- Deja inclus și comis în repo!
    │   ├── .env               <-- Fișierul de configurare mediu
    │   └── ...
    │
    └── public_html/           <-- Folderul public accesibil vizitatorilor
        ├── index.php          <-- Punctul de intrare (modificat să indice spre optervision_app)
        ├── .htaccess          <-- Regulile Apache de rescriere URL
        ├── build/             <-- Asset-urile compilate (CSS, JS) comise!
        └── storage -> /home/USER/optervision_app/storage/app/public/  <-- Link simbolic
```

---

## 2. Prima Configurare a Serverului (Setup Inițial)

### Pasul 2.1: Încărcarea codului core (`optervision_app`)
Puteți clona direct codul în `/home/USER/optervision_app` folosind terminalul SSH cPanel sau utilitarul **cPanel Git Version Control**:

1. Intrați în **cPanel** -> **Git Version Control**.
2. Faceți click pe **Create**.
3. Completați datele repository-ului:
   * **Clone URL**: Adresa URL privată a repository-ului dumneavoastră de GitHub (ex: `git@github.com:USER/optervision.git`).
   * **Directory**: `/home/USER/optervision_app`.
   * **Repository Name**: `optervision_app`.
4. Faceți click surse **Create**. Codul se va descărca automat de pe GitHub în folderul securizat.

### Pasul 2.2: Configurarea fișierului de mediu (`.env`)
1. În **cPanel File Manager**, navigați în `/home/USER/optervision_app/`.
2. Redenumiți sau copiați `.env.example` în `.env`.
3. Editați `.env` și introduceți setările de producție:
   * `APP_ENV=production`
   * `APP_DEBUG=false`
   * `APP_URL=https://optervision.ro`
   * Configurați conexiunea la baza de date MySQL cPanel (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
   * Rulați `php artisan key:generate` (sau generați o cheie criptografică securizată și introduceți-o în `APP_KEY`).

### Pasul 2.3: Configurare `public_html`
1. În folderul de repo de pe server, veți găsi un director numit `public_html_deploy`.
2. Copiați conținutul acestui folder (`index.php` și `.htaccess`) direct în folderul principal al domeniului dumneavoastră: `/home/USER/public_html/`.
3. Copiați folderul de build compilat (`public/build`) din `optervision_app` în `/home/USER/public_html/build/`.
   *(Deoarece `public/build` este deja compilat și salvat în repo-ul de git, el este pre-generat și gata de utilizare directă!)*

### Pasul 2.4: Generare Symlink pentru Media (Storage Link)
Pentru ca imaginile din media library sau portofoliu să se încarce public, trebuie să creăm un link simbolic. Deoarece SSH-ul poate fi dezactivat la unele firme de găzduire shared, puteți face acest lucru foarte simplu prin cPanel în două moduri alternative:

#### Metoda A: Folosind cPanel Terminal (SSH activat)
Rulați următoarea comandă în terminal:
```bash
ln -s /home/USER/optervision_app/storage/app/public /home/USER/public_html/storage
```

#### Metoda B: Folosind un fișier PHP temporar (Fără terminal SSH)
1. Creați un fișier numit `symlink.php` în `/home/USER/public_html/` cu următorul conținut:
   ```php
   <?php
   symlink('/home/USER/optervision_app/storage/app/public', '/home/USER/public_html/storage');
   echo "Symlink generat cu succes!";
   ```
2. Accesați în browser: `https://optervision.ro/symlink.php`.
3. Ștergeți fișierul `symlink.php` imediat din motive de securitate.

---

## 3. Actualizarea Aplicației (Update din Remote)

Când faceți modificări locale, le comiteți pe Git (inclusiv folderul `vendor` și asset-urile compilate în `public/build`). Pentru a actualiza codul pe server:

1. Navigați în **cPanel** -> **Git Version Control**.
2. Faceți click pe **Manage** în dreptul repository-ului `optervision_app`.
3. Faceți click pe tab-ul **Pull or Deploy**.
4. Apăsați butonul **Update from Remote**. Codul dumneavoastră se va actualiza în câteva secunde!
5. Copiați noul folder `/home/USER/optervision_app/public/build` peste folderul de producție `/home/USER/public_html/build` dacă ați modificat fișiere de stil (CSS/JS).
6. Navigați în **cPanel** -> **File Manager** -> Ștergeți fișierele din `/home/USER/optervision_app/storage/framework/cache/data` și `/home/USER/optervision_app/storage/framework/views` pentru a curăța cache-ul de producție și a activa noile optimizări.

---

## 4. Avantaje majore ale acestei arhitecturi
* **Zero dependențe externe pe server**: Serverul nu are nevoie de Composer sau Node/NPM. Totul este pre-compilat și pre-instalat în Git-ul privat.
* **Securitate desăvârșită**: Nucleul codului sursă și fișierul sensibil `.env` sunt ascunse complet de publicul larg, aflându-se deasupra rădăcinii de web.
* **Rapiditate**: deploy instantaneu dintr-un singur click prin interfața cPanel.
