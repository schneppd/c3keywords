CREATE OR REPLACE VIEW `PREFIX_vc3keywords` AS SELECT DISTINCT
 cp.`id_category` AS id_category, t.`id_tag` AS id_tag, t.`id_lang` AS id_lang, t.`name` AS tag_name
 , (SELECT COUNT(*) 
      FROM `PREFIX_category_product` AS capr
      INNER JOIN `PREFIX_product_shop` AS prsh ON capr.id_product = prsh.id_product
      INNER JOIN `PREFIX_product_tag` AS prta ON capr.id_product = prta.id_product
      WHERE prsh.active = 1 AND prta.id_tag = t.id_tag AND capr.id_category=cp.id_category
   ) AS nb_occurrence
 FROM `PREFIX_category_product` AS cp
 INNER JOIN `PREFIX_product_shop` AS ps ON cp.id_product = ps.id_product
 INNER JOIN `PREFIX_product_tag` AS pt ON cp.id_product = pt.id_product
 INNER JOIN `PREFIX_tag` AS t ON pt.id_tag = t.id_tag
 WHERE ps.active = 1
 ORDER BY id_category, nb_occurrence DESC, id_tag;
