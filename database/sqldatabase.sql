CREATE TABLE utente (
    id_utente          INT          NOT NULL AUTO_INCREMENT,
    nome               VARCHAR(40)  NOT NULL,
    cognome            VARCHAR(40)  NOT NULL,
    data_nascita       DATE         NOT NULL,
    sex                VARCHAR(20)  NOT NULL,
    num_tell           VARCHAR(20)  NOT NULL,
    email              VARCHAR(100) NOT NULL UNIQUE,
    password           VARCHAR(255) NOT NULL,
    privacy            BOOLEAN      NOT NULL DEFAULT FALSE,
    data_registrazione DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    citta              VARCHAR(50)      NULL,
    attivo             VARCHAR(20)  NOT NULL DEFAULT 'attivo',
    PRIMARY KEY (id_utente)
);

CREATE TABLE fisiot (
    id_fisio           INT          NOT NULL AUTO_INCREMENT,
    nome               VARCHAR(40)  NOT NULL,
    cognome            VARCHAR(40)  NOT NULL,
    data_nascita       DATE         NOT NULL,
    sex                VARCHAR(20)  NOT NULL,
    descrizione        TEXT             NULL,
    specializzazione   VARCHAR(300) NOT NULL,
    prezzo_base_ora    DECIMAL(8,2) NOT NULL,
    num_tell           VARCHAR(20)  NOT NULL,
    email              VARCHAR(100) NOT NULL UNIQUE,
    password           VARCHAR(255) NOT NULL,
    attivo             VARCHAR(20)  NOT NULL DEFAULT 'attivo',
    PRIMARY KEY (id_fisio)
);

CREATE TABLE slot_orario (
    id_slot       INT      NOT NULL AUTO_INCREMENT,
    id_fisio      INT      NOT NULL,
    data          DATE     NOT NULL,
    ora_inizio    TIME     NOT NULL,
    ora_fine      TIME     NOT NULL,
    stato         BOOLEAN  NOT NULL DEFAULT TRUE,
    max_prenotaz  TINYINT      NULL DEFAULT 1,
    PRIMARY KEY (id_slot),
    FOREIGN KEY (id_fisio) REFERENCES fisiot(id_fisio)
);

CREATE TABLE prenotazione (
    id_prenotazione     INT          NOT NULL AUTO_INCREMENT,
    id_utente           INT          NOT NULL,
    id_slot             INT          NOT NULL,
    id_fisio            INT          NOT NULL,
    stato               VARCHAR(20)  NOT NULL DEFAULT 'in_attesa',
    data_prenotazione   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    problema            VARCHAR(300) NOT NULL,
    avvertenze_pe_fisio VARCHAR(500)     NULL,
    creatore            VARCHAR(10)  NOT NULL DEFAULT 'utente',
    PRIMARY KEY (id_prenotazione),
    FOREIGN KEY (id_utente) REFERENCES utente(id_utente),
    FOREIGN KEY (id_slot)   REFERENCES slot_orario(id_slot),
    FOREIGN KEY (id_fisio)  REFERENCES fisiot(id_fisio)
);

CREATE TABLE visita (
    id_visita              INT  NOT NULL,
    descrizioneF           TEXT NOT NULL,
    data_visita            DATE NOT NULL,
    descrizione_per_utente TEXT     NULL,
    prescrizioni_post_vis  TEXT     NULL,
    PRIMARY KEY (id_visita),
    FOREIGN KEY (id_visita) REFERENCES prenotazione(id_prenotazione)
);