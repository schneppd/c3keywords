CREATE TABLE IF NOT EXISTS `PREFIX_c3_tag_per_shelf` (
 id_category int(10) unsigned NOT NULL
 ,id_tag int(10) unsigned NOT NULL
 ,nb_product MEDIUMINT NOT NULL
 ,PRIMARY KEY (id_category,id_tag)
 ,CONSTRAINT FOREIGN KEY (id_category) REFERENCES PREFIX_category (id_category) ON DELETE CASCADE ON UPDATE CASCADE
 ,CONSTRAINT FOREIGN KEY (id_tag) REFERENCES PREFIX_tag (id_tag) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE OR REPLACE VIEW `PREFIX_vc3keywords` AS SELECT DISTINCT
 ts.`id_category` AS id_category, ts.`id_tag` AS id_tag, t.`id_lang` AS id_lang, t.`name` AS tag_name
 , ts.nb_product AS nb_occurrence
 FROM `PREFIX_c3_tag_per_shelf` AS ts
 INNER JOIN `PREFIX_tag` AS t ON ts.id_tag = t.id_tag
 ORDER BY id_category, nb_occurrence DESC, id_tag;
