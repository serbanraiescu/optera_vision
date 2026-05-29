INSTRUCTIUNI PENTRU LINKARE SPATIU DE STOCARE (STORAGE) IN CPANEL
================================================================

Pentru a asigura incarcarea corecta a fisierelor media si imaginilor din panoul de administrare pe site-ul public, directorul de stocare public al aplicatiei trebuie sa fie accesibil din radacina web (public_html).

Metoda Recomandata (Legatura Simbolica - Symlink):
-------------------------------------------------
Cea mai buna abordare este crearea unui link simbolic intre folderul public_html/storage si directorul de stocare din app.
Puteti face acest lucru din cPanel in doua moduri:

Optiunea A: Prin Terminalul cPanel (daca este activat):
Rulati urmatoarea comanda in terminal:
ln -s /home/optera_vision/storage/app/public /home/public_html/storage

Optiunea B: Prin Cron Job in cPanel (daca nu aveti acces la Terminal):
1. Mergeti in cPanel la sectiunea "Cron Jobs".
2. Adaugati un cron job nou care sa ruleze o singura data, cu comanda:
   ln -s /home/optera_vision/storage/app/public /home/public_html/storage
3. Salvati si rulati. Dupa rulare, puteti sterge cron job-ul respectiv.


Metoda Alternativa (Copiere Manuala - Fallback):
-----------------------------------------------
Daca furnizorul dvs. de hosting cPanel are functia de symlink complet dezactivata si nu se poate crea legatura simbolica, va trebui sa:

1. Copiati manual tot continutul directorului:
   /home/optera_vision/storage/app/public/
   
2. In directorul:
   /home/public_html/storage/

Nota: Folosind metoda alternativa, fisierele noi incarcate din panoul de admin nu se vor actualiza automat pe site-ul public decat daca le copiati manual de fiecare data. Va recomandam cu insistenta sa utilizati Metoda Recomandata (Symlink).
