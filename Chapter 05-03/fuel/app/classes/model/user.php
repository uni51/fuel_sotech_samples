<?php

class Model_User extends Orm\Model
{

	protected static $_properties = array('id', 'name', 'email', 'sex', 'prefecture_id');
	protected static $_has_many = array(
			'phones' => array(
					'model_to' => 'Model_Telephone',
					'key_from' => 'id',
					'key_to' => 'user_id',
					'cascade_save' => true,
					'cascade_delete' => true,
			),
	);

}
