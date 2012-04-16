<?php
class Opinion extends AppModel {

	var $name = 'Opinion';
	var $validate = array(
		'class' => array('rule' => array('multiple', array('in' => array('not', 'pos', 'neg') ) ) ),
		'twitt_id' => array('numeric'),
		'user_id' => array('numeric')
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Twitt' => array(
			'className' => 'Twitt',
			'foreignKey' => 'twitt_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
?>
