<?php

// module/Users/src/Users/Form/UsersForm.php:

namespace Users\Form;

use Zend\Form\Form;

class UsersForm extends Form {

	public function __construct($name = null) {

		parent::__construct('users');

		$this->setAttribute('method', 'post');

		$this->add(array(
				'name' => 'id',
				'type' => 'Hidden',
		));

		$this->add(array(
				'name' => 'name',
				'type' => 'Text',
				'options' => array(
						'label' => 'Name',
				),
		));

		$this->add(array(
				'name' => 'email',
				'type' => 'Email',
				'options' => array(
						'label' => 'Email Address',
				),
		));

		$this->add(array(
				'name' => 'mobile',
				'type' => 'Text',
				'options' => array(
						'label' => 'Mobile',
				),
		));

		$this->add(array(
				'name' => 'address',
				'type' => 'Textarea ',
				'options' => array(
						'label' => 'Address',
				),
		));

		$this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
						'value' => 'Save',
						'id' => 'submitbutton',
				),
		));
	}

}

