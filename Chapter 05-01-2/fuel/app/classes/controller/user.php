<?php

class Controller_User extends Controller
{

	public function action_index()
	{
		return Response::forge(Presenter::forge('user'));
	}

}
