ALTER TABLE `current_desk_menu`
ADD COLUMN `cdm_id`  int NOT NULL AUTO_INCREMENT FIRST ,
ADD PRIMARY KEY (`cdm_id`),
DROP INDEX `key`;

