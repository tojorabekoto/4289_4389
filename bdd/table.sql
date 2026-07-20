CREATE TABLE prefixes (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe     VARCHAR(5)  NOT NULL UNIQUE,   -- ex: '033'
);

CREATE TABLE comptes (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_telephone    VARCHAR(15) NOT NULL UNIQUE,
    solde               DECIMAL(12,2) NOT NULL DEFAULT 0,
    date_creation       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE types_operation (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    code        VARCHAR(20) NOT NULL UNIQUE   -- 'depot' | 'retrait' | 'transfert'
    -- libelle     VARCHAR(50) NOT NULL
);


CREATE TABLE tranches_frais (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id   INTEGER NOT NULL,
    montant_min         DECIMAL(12,2) NOT NULL,
    montant_max         DECIMAL(12,2) NOT NULL,
    frais               DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (type_operation_id) REFERENCES types_operation(id)
);

insert into types_operation (code) values 
('depot'),('retrait'),('transfert');

insert into tranches_frais (type_operation_id, montant_min, montant_max, frais) values
    ((select id from types_operation where code = 'retrait'), 100, 1000, 50),
    ((select id from types_operation where code = 'transfert'), 100, 1000, 50),

    ((select id from types_operation where code = 'retrait'), 1001, 5000, 50),
    ((select id from types_operation where code = 'transfert'), 1001, 5000, 50),

    ((select id from types_operation where code = 'retrait'), 5001, 10000, 100),
    ((select id from types_operation where code = 'transfert'), 5001, 10000, 100),

    ((select id from types_operation where code = 'retrait'), 10001, 25000, 200),
    ((select id from types_operation where code = 'transfert'), 10001, 25000, 200),

    ((select id from types_operation where code = 'retrait'), 25001, 50000, 400),
    ((select id from types_operation where code = 'transfert'), 25001, 50000, 400),

    ((select id from types_operation where code = 'retrait'), 50001, 100000, 800),
    ((select id from types_operation where code = 'transfert'), 50001, 100000, 800),

    ((select id from types_operation where code = 'retrait'), 100001, 250000, 1500),
    ((select id from types_operation where code = 'transfert'), 100001, 250000, 1500),

    ((select id from types_operation where code = 'retrait'), 250001, 500000, 1500),
    ((select id from types_operation where code = 'transfert'), 250001, 500000, 1500),

    ((select id from types_operation where code = 'retrait'), 500001, 1000000, 2500),
    ((select id from types_operation where code = 'transfert'), 500001, 1000000, 2500),

    ((select id from types_operation where code = 'retrait'), 1000001, 2000000, 3000),
    ((select id from types_operation where code = 'transfert'), 1000001, 2000000, 3000);




