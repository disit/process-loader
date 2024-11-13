-- DB heatmap
USE heatmap;

CREATE TABLE authorizedUsers (
    id int NOT NULL AUTO_INCREMENT,
    username varchar(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE colormapUsers (
    id int NOT NULL AUTO_INCREMENT,
    colormap varchar(255) NOT NULL,
    username varchar(255) NOT NULL,
    PRIMARY KEY (id)
);

-- DB processloader_db
USE processloader_db;

UPDATE `processloader_db`.`MainMenu` 
SET `privileges` = 'AreaManager,ToolAdmin,RootAdmin' 
WHERE `id` = '20';

UPDATE `processloader_db`.`functionalities` 
SET `ToolAdmin` = '1', `AreaManager` = '1' 
WHERE `id` = '54';
