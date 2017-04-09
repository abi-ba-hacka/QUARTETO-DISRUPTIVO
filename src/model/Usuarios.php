<?php
/*
CREATE TABLE IF NOT EXISTS `Usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;
*/
class Usuarios extends Model {
	use SqlTableBucket;
	const NAME = 'Usuarios';
	const PK = array('id' => array(['integer'], ['min', 1]));
	const FIELDS = array(
		'email' => ['required','email'],
		'datetime' => ['required']
	);
}