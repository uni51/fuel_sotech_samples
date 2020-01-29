<?php

class Model_User extends Model_Crud
{

	protected static $_table_name = 'users';
	protected static $_primary_key = 'user_id';
	protected static $_rules = array(
			'name' => 'required',
			'email' => 'required|valid_email',
	);
	protected static $_defaults = array(
			'sex' => 1
	);

}
