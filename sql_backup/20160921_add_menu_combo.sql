ALTER TABLE `menus`
MODIFY COLUMN `men_editable`  tinyint(4) NULL DEFAULT 0 COMMENT 'cho phép sửa giá khi bán hay ko' AFTER `men_note`,
ADD COLUMN `men_is_combo`  tinyint(4) NULL DEFAULT 0 COMMENT '0: là menu thường, 1: là menu combo' AFTER `men_editable`;

ALTER TABLE `menus`
ADD COLUMN `men_children`  varchar(255) NULL DEFAULT NULL COMMENT 'list các menu con. Dành cho menu dạng combo' AFTER `men_is_combo`;