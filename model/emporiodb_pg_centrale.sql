CREATE DATABASE emporiodb_centrale WITH ENCODING 'UTF-8';
GRANT ALL PRIVILEGES ON DATABASE emporiodb_centrale TO emporiocentrale;

\c emporiodb_centrale

CREATE TABLE comuni (
    cod_istat VARCHAR(10),
    nome_comune VARCHAR(50),
    provincia VARCHAR(20),
    cap VARCHAR(5),
    regione VARCHAR(20),

    PRIMARY KEY(cod_istat)
);

CREATE TABLE tabella_carbonaro (
    famigliari INT,
    punti_famiglia INT,
    coefficiente REAL,
    punti_corretti INT
);

CREATE TABLE um (
    val_um VARCHAR(10),
    descrizione VARCHAR(50),

    PRIMARY KEY(val_um)
);

CREATE TABLE categorie (
    id_categoria SERIAL,
    descrizione_categoria VARCHAR(255),
    limite_spesa_max INT,
    limite_mese_max INT,

    PRIMARY KEY(id_categoria),
    UNIQUE(descrizione_categoria)
);

CREATE TABLE tipologie (
    id_tipologia SERIAL,
    categoria INT,
    descrizione_tipologia VARCHAR(255),
    warning_qta_minima INT,
    danger_qta_minima INT,
    punti INT NOT NULL,

    PRIMARY KEY(id_tipologia),
    UNIQUE(descrizione_tipologia),
    FOREIGN KEY(categoria) REFERENCES categorie(id_categoria)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE lista_empori (
    id_emporio SERIAL,
    nome_emporio VARCHAR(255),
	dbname VARCHAR(100),
    dbuser VARCHAR(100),
    dbpass VARCHAR(100),
    dbhost VARCHAR(100) DEFAULT 'localhost',
    dbport INT DEFAULT 5432,

    PRIMARY KEY (id_emporio, nome_emporio)
);

GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO emporiocentrale;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO emporiocentrale;
