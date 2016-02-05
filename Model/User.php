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
			'message' => 'An email is required'
		),
		'password' => array(
			'length' => array(
				'rule' => array('between', 8, 20),
				'message' => 'Your password must be between 8 and 20 characters',
				'on' => 'create',
			)
		),
		'password_confirm' => array(
			'rule' => array('confirmPasswordsMatch'),
			'message' => 'Password does not match'
		)
	);	
	
	public $hasMany = array(
		'Order' => array(
			'className' => 'Order',
			'foreignKey' => 'user_id',
			'dependent' => 'true'
		)
	);
	
	public function confirmPasswordsMatch()
	{
		return $this->data[$this->alias]['password'] == $this->data[$this->alias]['password_confirm'];
	}

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