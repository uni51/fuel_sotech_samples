<?php

class Presenter_User extends Presenter
{

	public function view()
	{
		$users = Model_User::find_all();
		$sexes = array(0 => '未選択', 1 => '男性', 2 => '女性');
		$this->sex_string = function($val) use ($sexes) {
			return $sexes[$val];
		};
		$this->title = 'プレゼンターのテスト';
		$this->users = $users;
	}

}
