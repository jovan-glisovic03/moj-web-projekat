# Sistem za praćenje izbornih rezultata

Ovo je web aplikacija za upravljanje i praćenje rezultata izbora, razvijena kao deo praktičnog rada na fakultetu (ETF). Sistem omogućava administraciju izbornih mesta, partija i automatsko generisanje rezultata po gradovima.

## Tehnologije
* **Backend:** PHP
* **Baza podataka:** MySQL (WampServer)
* **Frontend:** JavaScript, CSS, HTML

## Funkcionalnosti
* **Administracija:** Dodavanje i ažuriranje izbornih mesta, kontrolora, analitičara i partija.
* **Rezultati:** Prikaz i obrada rezultata po gradovima u realnom vremenu.
* **Validacija:** Provera unosa podataka putem PHP i JS skripti.

## Instalacija
1. Klonirajte repozitorijum.
2. Kopirajte fajlove u `www` folder WampServera.
3. Importujte priloženi `.sql` fajl u **phpMyAdmin**.

## Baza podataka
Projekat sadrži eksportovanu bazu podataka `izbori.sql` sa svim neophodnim tabelama za rad sistema.

---
*Autor: Jovan Glišović*