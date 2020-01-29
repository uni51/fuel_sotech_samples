<?php

class Controller_Members extends Controller
{

	public function before()
	{
		if (!Auth::check()) {
			Response::redirect('login');
		}
	}

}
