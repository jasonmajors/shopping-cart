<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
	public $validate = array(
		'username' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'A username is required'
			)
		),
		'password' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'A password is required'
			)
		)
	);	
	
	public $hasMany = array(
		'Order' => array(
			'className' => 'Order',
			'foreignKey' => 'user_id',
			'dependent' => 'true'
		)
	);

	public function beforeSave ($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash(
				$this->data[$this->alias]['password']
			);
		}
		return true;
	}
}