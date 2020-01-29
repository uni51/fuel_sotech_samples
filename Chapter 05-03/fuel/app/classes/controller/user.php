<?php

class Controller_User extends Controller
{

	public function action_create()
	{
		$user = Model_User::forge();
		$user->name = '早川聖司';
		$user->email = 'seiji@example.co.jp';
		$user->sex = 1;
		$user->prefecture_id = 13;
		$user->save();
	}

	public function action_update()
	{
		$user = Model_User::find(1);
		$user->email = 'test@example.com';
		$user->save();
	}

	public function action_delete()
	{
		$user = Model_User::find(1);
		$user->delete();
	}

	public function action_get_related()
	{
		$user = Model_User::find(1, array(
								'related' => array(
										'phones'
								))
		);
	}

	public function action_change()
	{
		$user = Model_User::find(1, array('related' => array('phones')));
		$user->phones[1]->number = '03-0000-9999';
		$user->save();
	}

	public function action_create_related()
	{
		$user = Model_User::find(1, array('related' => array('phones')));
		$user->phones[] = Model_Telephone::forge(array('number' => '045-000-0000'));
		$user->save();
	}

}
