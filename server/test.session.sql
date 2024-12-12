--@block CREATE_FELHASZNALOK
CREATE TABLE
    Felhasznalok (
        email_felhasznalonev VARCHAR(255) PRIMARY KEY,
        nev VARCHAR(255) NOT NULL,
        jelszó VARCHAR(255) NOT NULL,
        isBejelentkezve BOOLEAN,
        szerepkör char(255) NOT NULL
    );

--@BLOCK CREATE_KURZUSTANULOK
DROP TABLE IF EXISTS KURZUSTANULOK;

CREATE TABLE
    KURZUSTANULOK (
        email_felhasznalonev VARCHAR(255) NOT NULL,
        kKod INT NOT NULL,
        PRIMARY KEY (email_felhasznalonev, kKod), -- Composite primary key
        FOREIGN KEY (email_felhasznalonev) REFERENCES Felhasznalok (email_felhasznalonev),
        FOREIGN KEY (kKod) REFERENCES Kurzusok (kKod)
    );

--@BLOCK CREATE_TANANYAGOK
CREATE TABLE
    Tananyagok (
        tananyagAzonosito INT NOT NULL,
        letrehozoFelhasznalo VARCHAR(255) NOT NULL,
        tananyagNev VARCHAR(255) NOT NULL,
        letrehozasDatuma DATE,
        kKod INT NOT NULL,
        PRIMARY KEY (tananyagAzonosito),
        FOREIGN KEY (letrehozoFelhasznalo) REFERENCES Felhasznalok (email_felhasznalonev),
        FOREIGN KEY (kKod) REFERENCES Kurzusok (kKod)
    );

--@BLOCK CREATE_KURZUSOK
CREATE TABLE
    KurzusNevek (
        idNev INT primary key AUTO_INCREMENT,
        kNevek VARCHAR(255) NOT NULL UNIQUE
    );

--@BLOCK CREATE_KURZUSOK
CREATE TABLE
    Kurzusok (
        kKod INT PRIMARY KEY AUTO_INCREMENT,
        idNev INT,
        felEv VARCHAR(255) NOT NULL,
        letrehozoFelhasznalo VARCHAR(255) NOT NULL,
        kredit INT NOT NULL,
        FOREIGN KEY (letrehozoFelhasznalo) REFERENCES felhasznalok (email_felhasznalonev),
        FOREIGN KEY (idNev) REFERENCES KurzusNevek (idNev)
    );

--@block CREATE NAPLÓ
CREATE TABLE
    Napló (
        logID INT NOT NULL AUTO_INCREMENT,
        email_felhasznalonev VARCHAR(255) NOT NULL,
        tananyagAzonosito INT,
        mikor DATETIME,
        miMuvelet VARCHAR(255),
        kurzusKod INT,
        PRIMARY KEY (logID),
        FOREIGN KEY (email_felhasznalonev) REFERENCES Felhasznalok (email_felhasznalonev),
        FOREIGN KEY (tananyagAzonosito) REFERENCES Tananyagok (tananyagAzonosito),
        FOREIGN KEY (kurzusKod) REFERENCES Kurzusok (kKod)
    );

--@BLOCK ADATFELVÉTEL (CHATGPT ÁLLTAL GENERÁLT PÉLDÁK)
-- Oktatók beszúrása
INSERT INTO
    felhasznalok (
        email_felhasznalonev,
        nev,
        jelszó,
        isBejelentkezve,
        szerepkör
    )
VALUES
    (
        'oktato1@gmail.com',
        'Kovács Béla',
        'StrongPass1!',
        false,
        'oktató'
    ),
    (
        'oktato2@gmail.com',
        'Nagy Ádám',
        'SecurePass2!',
        true,
        'oktató'
    ),
    (
        'oktato3@gmail.com',
        'Szabó Mária',
        'HiddenPass3!',
        true,
        'oktató'
    ),
    (
        'oktato4@gmail.com',
        'Tóth Sándor',
        'SimplePass4!',
        false,
        'oktató'
    ),
    (
        'oktato5@gmail.com',
        'Horváth Anna',
        'RandomPass5!',
        true,
        'oktató'
    ),
    (
        'oktato6@gmail.com',
        'Varga Zoltán',
        'UniquePass6!',
        false,
        'oktató'
    ),
    (
        'oktato7@gmail.com',
        'Kiss László',
        'BestPass7!',
        true,
        'oktató'
    ),
    (
        'oktato8@gmail.com',
        'Molnár Erika',
        'PowerPass8!',
        false,
        'oktató'
    ),
    (
        'oktato9@gmail.com',
        'Németh Gábor',
        'FastPass9!',
        true,
        'oktató'
    ),
    (
        'oktato10@gmail.com',
        'Farkas Petra',
        'EasyPass10!',
        false,
        'oktató'
    );

-- Tanulók beszúrása
INSERT INTO
    felhasznalok (
        email_felhasznalonev,
        nev,
        jelszó,
        isBejelentkezve,
        szerepkör
    )
VALUES
    (
        'tanulo1@gmail.com',
        'Bak Péter',
        'StudentPass1!',
        false,
        'tanuló'
    ),
    (
        'tanulo2@gmail.com',
        'Kelemen Júlia',
        'LearnPass2!',
        true,
        'tanuló'
    ),
    (
        'tanulo3@gmail.com',
        'Kozma Gergely',
        'StudyPass3!',
        false,
        'tanuló'
    ),
    (
        'tanulo4@gmail.com',
        'Lukács Eszter',
        'SmartPass4!',
        true,
        'tanuló'
    ),
    (
        'tanulo5@gmail.com',
        'Balogh István',
        'BrightPass5!',
        false,
        'tanuló'
    ),
    (
        'tanulo6@gmail.com',
        'Halász Dóra',
        'LogicPass6!',
        true,
        'tanuló'
    ),
    (
        'tanulo7@gmail.com',
        'Pintér Tamás',
        'CreativePass7!',
        false,
        'tanuló'
    ),
    (
        'tanulo8@gmail.com',
        'Juhász Noémi',
        'SharpPass8!',
        true,
        'tanuló'
    ),
    (
        'tanulo9@gmail.com',
        'Kertész Bence',
        'NextPass9!',
        false,
        'tanuló'
    ),
    (
        'tanulo10@gmail.com',
        'Török Katalin',
        'FuturePass10!',
        true,
        'tanuló'
    ),
    (
        'tanulo11@gmail.com',
        'Szilágyi Emese',
        'LearnNow11!',
        true,
        'tanuló'
    ),
    (
        'tanulo12@gmail.com',
        'Oláh Richárd',
        'BrightFuture12!',
        false,
        'tanuló'
    ),
    (
        'tanulo13@gmail.com',
        'Veres Anikó',
        'StudyWell13!',
        true,
        'tanuló'
    ),
    (
        'tanulo14@gmail.com',
        'Simon Dénes',
        'Knowledge14!',
        false,
        'tanuló'
    ),
    (
        'tanulo15@gmail.com',
        'Szalai Izabella',
        'LogicWin15!',
        true,
        'tanuló'
    ),
    (
        'tanulo16@gmail.com',
        'Gulyás Áron',
        'NextStep16!',
        false,
        'tanuló'
    ),
    (
        'tanulo17@gmail.com',
        'Papp Veronika',
        'SuccessPath17!',
        true,
        'tanuló'
    ),
    (
        'tanulo18@gmail.com',
        'Hegedűs Nóra',
        'SkillMaster18!',
        false,
        'tanuló'
    ),
    (
        'tanulo19@gmail.com',
        'Bíró Levente',
        'GrowthMind19!',
        true,
        'tanuló'
    ),
    (
        'tanulo20@gmail.com',
        'Vass Petra',
        'ThinkBig20!',
        false,
        'tanuló'
    );

--@block UPDATE PASSWORD
UPDATE felhasznalok
SET
    jelszó = SHA1 (jelszó);

--@block 
INSERT INTO
    TANANYAGOK (tananyagAzonosito, tNev, letrehozasDatuma)
VALUES
    (0000, "Adatbázisok Gyakorlat", "2024-12-01");

--@block Tananyag feltöltés (CHAT-GPT ÁLLTAL GENERÁLT VALUEK)
INSERT INTO
    tananyagok (
        tananyagAzonosito,
        letrehozoFelhasznalo,
        tananyagNev,
        letrehozasDatuma
    )
VALUES
    (
        2,
        'oktato2@gmail.com',
        'Adatbázis-kezelés Gyakorlat',
        NOW ()
    ),
    (
        3,
        'oktato3@gmail.com',
        'Programozás Bevezetés',
        NOW ()
    ),
    (
        4,
        'oktato4@gmail.com',
        'Webfejlesztés Alapok',
        NOW ()
    ),
    (
        5,
        'oktato5@gmail.com',
        'Szoftverfejlesztés',
        NOW ()
    ),
    (
        6,
        'oktato6@gmail.com',
        'Mobilalkalmazás-fejlesztés',
        NOW ()
    ),
    (
        7,
        'oktato7@gmail.com',
        'Hálózatok és Protokollok',
        NOW ()
    ),
    (
        8,
        'oktato8@gmail.com',
        'Mesterséges Intelligencia Alapjai',
        NOW ()
    ),
    (9, 'oktato9@gmail.com', 'Gépi Tanulás', NOW ()),
    (
        10,
        'oktato10@gmail.com',
        'Felhőalapú Rendszerek',
        NOW ()
    );

--@block
-- Kurzusok beszúrása (tananyagAzonosito hozzáadva)
INSERT INTO
    Kurzusok (
        kredit,
        felEv,
        email_felhasznalonev,
        tananyagAzonosito
    )
VALUES
    (3, 1, '2023/2024/1', 'oktato1@gmail.com'),
    (4, 2, '2023/2024/1', 'oktato2@gmail.com'),
    (5, 3, '2023/2024/1', 'oktato3@gmail.com'),
    (3, 4, '2023/2024/1', 'oktato4@gmail.com'),
    (6, 5, '2023/2024/1', 'oktato5@gmail.com'),
    (3, 6, '2023/2024/2', 'oktato6@gmail.com'),
    (4, 7, '2023/2024/2', 'oktato7@gmail.com'),
    (5, 8, '2023/2024/2', 'oktato8@gmail.com'),
    (3, 9, '2023/2024/2', 'oktato9@gmail.com'),
    (6, 10 '2023/2024/2', 'oktato10@gmail.com');

-- Felhőalapú Rendszerek (Tananyag: 0010)
-- Kurzus Nevek beszúrása
INSERT INTO
    KurzusNevek (kKod, kNevek)
VALUES
    (1, 'Matematika Alapjai'),
    (2, 'Adatbázis-kezelés Gyakorlat'),
    (3, 'Programozás Bevezetés'),
    (4, 'Webfejlesztés Alapok'),
    (5, 'Szoftverfejlesztés'),
    (6, 'Mobilalkalmazás-fejlesztés'),
    (7, 'Hálózatok és Protokollok'),
    (8, 'Mesterséges Intelligencia Alapjai'),
    (9, 'Gépi Tanulás'),
    (10, 'Felhőalapú Rendszerek');

--@block Első Log
Insert into
    Kurzusok (idNev, felEv, letrehozoFelhasznalo, kredit)
VALUES
    (4, "2024/2025", "Isten", 3)
    --@block INSIGHT
SELECT
    count(KurzusNevek.knevek),
    Kurzusok.letrehozoFelhasznalo
FROM
    KurzusNevek
    RIGHT JOIN Kurzusok ON KurzusNevek.idNev = Kurzusok.idNev
GROUP BY
    Kurzusok.letrehozoFelhasznalo
    --@block Group by
Select Kurzusok.letrehozoFelhasznalo, KurzusNevek.kNevek FROM Kurzusok INNER JOIN KurzusNevek
ON kurzusok.idNev = KurzusNevek.idNev
WHERE Kurzusok.letrehozoFelhasznalo = "jagerpeter04@gmail.com"



--@block innergagyisag
Select Kurzusok.letrehozoFelhasznalo, KurzusNevek.kNevek FROM Kurzusok INNER JOIN KurzusNevek
                ON kurzusok.idNev = KurzusNevek.idNev
                WHERE Kurzusok.letrehozoFelhasznalo = "jagerpeter04@gmail.com"

--@block valami
    Select tananyagNev FROM Tananyagok WHERE kKod = 4

--@block TERMINATUS
-- !    TERMINATUS    !
DROP TABLE KURZUSTANULOK