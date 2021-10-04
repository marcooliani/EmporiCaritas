#!/bin/bash

if [ ! $# -eq 3 ]; then
	echo "Usage: $0 <dbuser> <db> <emporio>";
	exit;
fi

mysql -u$1 -p $2 << EOF
SELECT DISTINCT code, name, price, stockqty, quantity, text FROM products INNER JOIN measures ON measures.id = products.qunit INTO OUTFILE '/tmp/prodotti_$3.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '' LINES TERMINATED BY '\\n';

SELECT DISTINCT code, surname, name, birthDate, address, phone, since, expiry, monthly, total AS remain, parent FROM clients INNER JOIN credits ON clients.id = credits.customerid WHERE parent = '' INTO OUTFILE '/tmp/clienti_$3.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '' LINES TERMINATED BY '\\n';

SELECT DISTINCT code, name, surname, birthDate, parent FROM clients WHERE parent <> '' INTO OUTFILE '/tmp/famigliari_$3.csv' FIELDS TERMINATED BY ';' ENCLOSED BY ''  LINES TERMINATED BY '\\n';

SELECT name, since, address, phone, notes FROM donors INTO OUTFILE '/tmp/fornitori_$3.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '' LINES TERMINATED BY '\\n';
EOF
