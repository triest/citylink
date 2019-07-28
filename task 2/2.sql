/*получение тех, кто подписн на журнал мурзилка*/
SELECT `red`.`name`, `red`.`date_of_birth`,  TIMESTAMPDIFF(YEAR, `red`.`date_of_birth`, CURDATE())>30  as `age` FROM `subscriptions` `sub`
	left JOIN `reader` `red` on `sub`.`reader_id`=`red`.`id`
    LEFT join `magazine` `mag` on `sub`.`magazine_id`=`mag`.`id`
    WHERE `mag`.`name`='Мурзилка'



/*получение случйного пользователя */
SELECT * FROM `reader` order BY rand() limit 1