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
        tananyagAzonosito INT NOT NULL AUTO_INCREMENT,
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

--@block Group by
SELECT
    count(KurzusNevek.knevek),
    Kurzusok.letrehozoFelhasznalo
FROM
    KurzusNevek
    RIGHT JOIN Kurzusok ON KurzusNevek.idNev = Kurzusok.idNev
GROUP BY
    Kurzusok.letrehozoFelhasznalo
    --@block 
Select
    Kurzusok.letrehozoFelhasznalo,
    KurzusNevek.kNevek
FROM
    Kurzusok
    INNER JOIN KurzusNevek ON kurzusok.idNev = KurzusNevek.idNev
WHERE
    Kurzusok.letrehozoFelhasznalo = "jagerpeter04@gmail.com"
    --@block innergagyisag
Select
    Kurzusok.letrehozoFelhasznalo,
    KurzusNevek.kNevek
FROM
    Kurzusok
    INNER JOIN KurzusNevek ON kurzusok.idNev = KurzusNevek.idNev
WHERE
    Kurzusok.letrehozoFelhasznalo = "jagerpeter04@gmail.com"
    --@block valami
SELECT
    Kurzusok.kKod,
    felEv,
    kNevek,
    kredit
FROM
    Kurzusok
    INNER JOIN KurzusNevek ON KurzusNevek.idNev = kurzusok.idNev
    INNER JOIN KURZUSTANULOK ON KURZUSTANULOK.kKod = kurzusok.kKod
WHERE
    KURZUSTANULOK.email_felhasznalonev = "t1@gmail.com"
    --@block valami2
Select
    kNevek
From
    KurzusNevek
WHERE
    EXISTS (
        Select
            letrehozoFelhasznalo
        from
            kurzusok
        WHERE
            Kurzusok.idNev = KurzusNevek.idNev
            AND letrehozoFelhasznalo = "tanitanikellmertehendoglok@gmail.com"
            AND Kurzusok.kKod = 1
    )
    --@block valami3
DELETE FROM Felhasznalok
where
    szerepkör = "tanuló"
    or szerepkör = "oktató"
    --@block TERMINATUS
    -- !    TERMINATUS    !
DROP TABLE napló