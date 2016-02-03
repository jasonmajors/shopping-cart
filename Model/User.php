	<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
	public $validate = array(
		'firstname' => array(
			'rule' => 'notBlank',
		),
		'lastname' => array(
			'rule' => 'notBlank',
		),
		'address' => array(
			'rule' => 'notBlank',
		),
		'city' => array(
			'rule' => 'notBlank',
		),
		'zipcode' => array(
			'rule' => array('lengthBetween', 5, 5),
			'message' => 'Zipcode must be 5 digits'
		),
		'state' => array(
			'rule' => 'notBlank',
		),
		'email' => array(
			'rule' => 'notBlank',
			'message' => 'A email is required'
		),
		'password' => array(
			'rule' => 'notBlank',
			'message' => 'A password is required'
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
		return True;
	}
}