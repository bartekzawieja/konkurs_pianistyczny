-- T A B L I C E:


-- Tablica: uzytkownicy
CREATE TABLE uzytkownicy (
    id SERIAL PRIMARY KEY,
    imie varchar(100) NOT NULL,
    nazwisko varchar(100) NOT NULL,
    email VARCHAR(250) UNIQUE NOT NULL,
    haslo VARCHAR(100) NOT NULL,
    rola VARCHAR(50) NOT NULL CHECK (rola IN ('obsługa', 'artysta')),
    flagaKoniec int DEFAULT 0,
    CONSTRAINT uzytkownicy_uq UNIQUE (imie, nazwisko)
);


-- Tablica: zgloszenia
CREATE TABLE zgloszenia (
    id SERIAL PRIMARY KEY,
    artysta_id int NOT NULL UNIQUE,
    data_zgloszenia date NOT NULL,
    FOREIGN KEY (artysta_id) REFERENCES uzytkownicy (id)
);


-- Tablica: harmonogram
CREATE TABLE harmonogram (
    id SERIAL PRIMARY KEY,
    zgloszenie_id int NOT NULL UNIQUE,
    kolejnosc SERIAL,
    ocena1 int CHECK (ocena1 >= 0 AND ocena1 <= 6),
    ocena2 int CHECK (ocena2 >= 0 AND ocena2 <= 6),
    FOREIGN KEY (zgloszenie_id) REFERENCES zgloszenia (id)
);


-- Tablica: kompozytorzy
CREATE TABLE kompozytorzy (
    id SERIAL PRIMARY KEY,
    imie varchar(100) NOT NULL,
    nazwisko varchar(100) NOT NULL
);


-- Tablica: utwory
CREATE TABLE utwory (
    id SERIAL PRIMARY KEY,
    kompozytor_id int NOT NULL,
    obsluga_id int  NOT NULL,
    tytul varchar(250) NOT NULL,
    FOREIGN KEY (kompozytor_id) REFERENCES kompozytorzy (id),
    FOREIGN KEY (obsluga_id) REFERENCES uzytkownicy (id)
);


-- Tablica: zgloszenia_utwory
CREATE TABLE zgloszenia_utwory (
    zgloszenie_id int NOT NULL,
    utwor_id int NOT NULL,
    numer_utworu int CHECK (numer_utworu > 0 AND numer_utworu < 4),
    FOREIGN KEY (zgloszenie_id) REFERENCES zgloszenia (id),
    FOREIGN KEY (utwor_id) REFERENCES utwory (id)
);


-- Tablica: uzytkownicy_harmonogram
CREATE TABLE uzytkownicy_harmonogram (
    uzytkownik_id int NOT NULL,
    harmonogram_id int NOT NULL,
    numer_obslugi int CHECK (numer_obslugi > 0 AND numer_obslugi < 3),
    FOREIGN KEY (uzytkownik_id) REFERENCES uzytkownicy (id),
    FOREIGN KEY (harmonogram_id) REFERENCES harmonogram (id)
);
