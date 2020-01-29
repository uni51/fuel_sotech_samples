<?php

class Controller_Smarty extends Controller
{

	public function action_index()
	{
		$data = array('title' => 'Smartyのテスト', 'names' => array('John','Mary', 'Sam', 'David'),);
		return Response::forge(View_Smarty::forge('smarty_test', $data));
	}

}
