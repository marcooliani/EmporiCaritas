/* CREATE DATABASE emporiodb WITH ENCODING 'UTF-8'; */
GRANT ALL PRIVILEGES ON DATABASE emporiodb TO emporio;

CREATE EXTENSION IF NOT EXISTS postgres_fdw;

CREATE SERVER emporio_centrale FOREIGN DATA WRAPPER postgres_fdw OPTIONS (host 'localhost', dbname 'emporiodb_centrale', port '5432');

CREATE USER MAPPING FOR emporio SERVER emporio_centrale OPTIONS (user 'emporiocentrale', password 'emporiocentrale');

\c emporiodb

CREATE FOREIGN TABLE IF NOT EXISTS comuni (
    cod_istat VARCHAR(10),
    nome_comune VARCHAR(50),
    provincia VARCHAR(20),
    cap VARCHAR(5),
    regione VARCHAR(20)
) SERVER emporio_centrale;

CREATE TABLE users (
	login VARCHAR(20),
	password VARCHAR(100),
	ruolo VARCHAR(50),
	cognome VARCHAR(100),
	nome VARCHAR(100),

	PRIMARY KEY(login)
);

CREATE TABLE enti (
	id_ente SERIAL,
	ragione_sociale VARCHAR(255),
	inserito_il DATE,
	note TEXT,

	PRIMARY KEY(id_ente)
);

CREATE TABLE famiglie (
	codice_fiscale VARCHAR(20),
	cognome VARCHAR(100) NOT NULL,
	nome VARCHAR(100),
	is_ente BOOLEAN NOT NULL DEFAULT false,
	data_nascita DATE,
	luogo_nascita VARCHAR(255),
	nazione VARCHAR(100),
	nazionalita VARCHAR(100),
	sesso VARCHAR(10),
	ente_proponente INT,
	indirizzo VARCHAR(255),
	localita VARCHAR(255),
	cap VARCHAR(10),
	comune VARCHAR(10),
	telefono_1 VARCHAR(15),
	cellulare VARCHAR(15),
	email VARCHAR(255),
	num_componenti INT,
	punti_totali INT NOT NULL,
	punti_residui INT NOT NULL,
	esenzione BOOLEAN NOT NULL DEFAULT false,
	giorno_1 VARCHAR(20),
	orario VARCHAR(40),
	scadenza DATE,
	iscritto_da DATE,
	sospeso BOOLEAN NOT NULL DEFAULT false,
	sospeso_da DATE,
	sospeso_a DATE,
	note TEXT,

	PRIMARY KEY(codice_fiscale),
	FOREIGN KEY(ente_proponente) REFERENCES enti(id_ente)
		ON UPDATE CASCADE
		ON DELETE NO ACTION
/*	FOREIGN KEY(comune) REFERENCES comuni(cod_istat)
        ON UPDATE CASCADE
        ON DELETE NO ACTION */
);

CREATE TABLE componenti_famiglie (
	c_codice_fiscale VARCHAR(20),
	capofamiglia VARCHAR(20),
	cognome VARCHAR(255),
	nome VARCHAR(255),
	ruolo VARCHAR(100),
	sesso VARCHAR(15),
	data_nascita DATE,
	luogo_nascita VARCHAR(255),
	nazione VARCHAR(100),
	nazionalita VARCHAR(100),

	PRIMARY KEY(c_codice_fiscale),
	FOREIGN KEY(capofamiglia) REFERENCES famiglie(codice_fiscale)
		ON UPDATE CASCADE
		ON DELETE NO ACTION
);

CREATE FOREIGN TABLE IF NOT EXISTS tabella_carbonaro (
	famigliari INT,
	punti_famiglia INT,
	coefficiente REAL,
	punti_corretti INT
) SERVER emporio_centrale;

CREATE FOREIGN TABLE IF NOT EXISTS um (
	val_um VARCHAR(10),
	descrizione VARCHAR(50)

) SERVER emporio_centrale;

CREATE FOREIGN TABLE IF NOT EXISTS categorie (
	id_categoria SERIAL,
	descrizione_categoria VARCHAR(255),
	limite_spesa_max INT,
	limite_mese_max INT
) SERVER emporio_centrale;

CREATE FOREIGN TABLE IF NOT EXISTS tipologie (
	id_tipologia SERIAL,
	categoria INT,
	descrizione_tipologia VARCHAR(255),
	warning_qta_minima INT,
	danger_qta_minima INT,
	punti INT NOT NULL
) SERVER emporio_centrale;

CREATE TABLE barcodes (
	barcode VARCHAR(50),
	tipologia INT,
	descrizione VARCHAR(255) NOT NULL,
	um_1 VARCHAR(10),
	um_2 VARCHAR(10),
	contenuto_um1 REAL,
	contenuto_um2 REAL,
	agea BOOL NOT NULL DEFAULT false,
	classificato BOOL NOT NULL DEFAULT true,
	um_stock VARCHAR(10),
	stock INT,	

	PRIMARY KEY(barcode)
/*	FOREIGN KEY(tipologia) REFERENCES tipologie(id_tipologia)
		ON UPDATE CASCADE
		ON DELETE NO ACTION */
);

CREATE TABLE offerte_sconti (
	id_offerta SERIAL,
	barcode VARCHAR(50),
	id_tipologia INT,
	prezzo_offerta INT,
	offerta_da DATE,
	offerta_a DATE,

	PRIMARY KEY(id_offerta),
	FOREIGN KEY(barcode) REFERENCES barcodes(barcode)
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE limiti_nucleo (
	categoria_merce INT,
	lim_1c_spesa INT,
	lim_2c_spesa INT,
	lim_3c_spesa INT,
	lim_4c_spesa INT,
	lim_5c_spesa INT,
	lim_6c_spesa INT,
	lim_7c_spesa INT,
	lim_8c_spesa INT,
	lim_9c_spesa INT,
	lim_10c_spesa INT,
	lim_1c_mese INT,
	lim_2c_mese INT,
	lim_3c_mese INT,
	lim_4c_mese INT,
	lim_5c_mese INT,
	lim_6c_mese INT,
	lim_7c_mese INT,
	lim_8c_mese INT,
	lim_9c_mese INT,
	lim_10c_mese INT,

	PRIMARY KEY(categoria_merce)
/*	FOREIGN KEY(categoria_merce) REFERENCES categorie(id_categoria)
		ON UPDATE CASCADE
		ON DELETE CASCADE */
);

CREATE TABLE scontrini (
	id_scontrino VARCHAR(50),
	codice_fiscale VARCHAR(20),
	cliente_effettivo VARCHAR(50),
	data TIMESTAMP,
	totale_punti INT,
	cassa VARCHAR(20),
	operatore VARCHAR(50),

	PRIMARY KEY(id_scontrino),
	FOREIGN KEY(codice_fiscale) REFERENCES famiglie(codice_fiscale)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
	FOREIGN KEY(operatore) REFERENCES users(login)
		ON UPDATE CASCADE
		ON DELETE NO ACTION
);

CREATE TABLE spesa (
	id_scontrino VARCHAR(50),
	barcode VARCHAR(50),
	tipologia INT,
	data TIMESTAMP,
	punti INT,
	qta INT,
	scontato BOOLEAN DEFAULT false,

	FOREIGN KEY(barcode) REFERENCES barcodes(barcode)
		ON UPDATE CASCADE
		ON DELETE CASCADE,
/*	FOREIGN KEY(tipologia) REFERENCES tipologie(id_tipologia)
		ON UPDATE CASCADE
		ON DELETE NO ACTION, */
	FOREIGN KEY(id_scontrino) REFERENCES scontrini(id_scontrino)
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE fornitori (
	id_fornitore SERIAL,
	ragione_sociale VARCHAR(100),
	cognome VARCHAR(50),
	nome VARCHAR(50),
	referente VARCHAR(100),
	indirizzo VARCHAR(255),
	localita VARCHAR(100),
	cap VARCHAR(5),
	comune VARCHAR(10),
	telefono VARCHAR(50),
	email VARCHAR(255),
	fornitore_da DATE,
	donatore BOOLEAN DEFAULT false,
	note TEXT,

	PRIMARY KEY(id_fornitore)
/*	FOREIGN KEY(comune) REFERENCES comuni(cod_istat)
		ON UPDATE CASCADE
		ON DELETE NO ACTION */
);

CREATE TABLE donazioni (
	id_transazione SERIAL,
	id_fornitore INT,
	barcode VARCHAR(50),
	quantita INT,
	data_donazione DATE,
	acquistato BOOL NOT NULL DEFAULT false,
	ddt VARCHAR(15),

	PRIMARY KEY(id_transazione),
	FOREIGN KEY(id_fornitore) REFERENCES fornitori(id_fornitore)
		ON UPDATE CASCADE
		ON DELETE NO ACTION,
	FOREIGN KEY(barcode) REFERENCES barcodes(barcode)
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE registro_agea (
	data DATE,
	ddt VARCHAR(15),
	carico BOOLEAN default false,
	id_tipologia INT,
	um_1 VARCHAR(10),
	quantita REAL,
	giacenza REAL,
	indigenti INT,
	cliente VARCHAR(20),
);

CREATE TABLE notifiche (
	id_notifica SERIAL,
	tipo VARCHAR(100),
	msg VARCHAR(255),
	data TIMESTAMP,
	letto BOOLEAN DEFAULT false,

	PRIMARY KEY(id_notifica)
);

CREATE TABLE logs (
	log_id SERIAL, 
	log_data TIMESTAMP, 
	flag VARCHAR(50), 
	log_descrizione VARCHAR(255), 
	login VARCHAR(20), 

	PRIMARY KEY(log_id), 
	FOREIGN KEY(login) REFERENCES users(login) 
		ON UPDATE CASCADE 
		ON DELETE NO ACTION
);

CREATE TABLE emporio_config (
	nome_emporio VARCHAR(255),
	cognome_responsabile VARCHAR(100),
	nome_responsabile VARCHAR(100),
	data_nascita DATE,
	comune_nascita VARCHAR(10),
	nome_associazione VARCHAR(255),
	indirizzo_associazione VARCHAR(255),
	localita VARCHAR(100),
	comune VARCHAR(10),
	cap VARCHAR(5),

	PRIMARY KEY(nome_emporio)
);

CREATE FOREIGN TABLE IF NOT EXISTS lista_empori (
	id_emporio SERIAL,
	nome_emporio VARCHAR(255),
	dbname VARCHAR(100),
    dbuser VARCHAR(100),
    dbpass VARCHAR(100),
    dbhost VARCHAR(100) DEFAULT 'localhost',
    dbport INT DEFAULT 5432
) 
SERVER emporio_centrale;

GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO emporio;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO emporio;
