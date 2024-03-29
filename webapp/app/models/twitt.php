<?php
class Twitt extends AppModel {

	var $name = 'Twitt';
	var $validate = array(
		'content' => array('notempty'),
		'json' => array('notempty')
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
		'Opinion' => array(
			'className' => 'Opinion',
			'foreignKey' => 'twitt_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
?>