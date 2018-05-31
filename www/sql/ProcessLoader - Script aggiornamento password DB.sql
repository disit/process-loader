-- Questo script SQL serve a convertire le password 'users' attualmente in chiaro con una codifica md5 in modo da aumentarne la sicurezza.
-- Il campo della tabella user deve però prima essere allargato da 20 carattere a 32

-- Aumento della lunghezza del campo a 32 caratteri
ALTER TABLE `processloader_db`.`users` 
CHANGE COLUMN `Password` `Password` VARCHAR(32) CHARACTER SET 'latin1' NOT NULL;

-- Codifica MD5 delle password pregresse
UPDATE processloader_db.users SET users.Password = MD5(users.Password);

