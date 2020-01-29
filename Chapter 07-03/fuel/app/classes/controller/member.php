<?php

class Controller_Member extends Controller
{

	public function before()
	{
		//Fieldsetオブジェクトを生成
		$config = array(
				'form_attributes' => array(
						'action' => 'member/confirm'),
		);
		$user_form = Fieldset::forge('user_form', $config);
		$user_form->add('name', 'お名前', array('type' => 'text', 'size' => 40, 'placeholder' => 'お名前を入力してください'));
		$user_form->add('sex', '性別', array('type' => 'radio', 'options' => array(1 => '男性', 2 => '女性'), 'value' => 1));
		$user_form->add('email', 'メールアドレス', array('type' => 'email', 'size' => 40));
		$user_form->add('password1', 'パスワード', array('type' => 'password', 'size' => 40));
		$user_form->add('password2', '確認用', array('type' => 'password', 'size' => 40));
		$user_form->add('submit', '', array('type' => 'submit', 'value' => '送信'));
		$user_form->field('name')
						->add_rule('required');
		$user_form->field('email')
						->add_rule('required')
						->add_rule('valid_email');
		$user_form->field('sex')
						->add_rule('required');
		$user_form->field('password1')
						->add_rule('required')
						->add_rule('min_length', 8)
						->add_rule('max_length', 12)
						->add_rule('valid_string', array('alpha', 'numeric', 'dashes', 'utf8'));
		$user_form->field('password2')
						->add_rule('required')
						->add_rule('match_field', 'password1');
		$this->user_form = $user_form;
	}

	public function action_index()
	{
		$user_form = $this->user_form;
		$view = View::forge('member/form');
		$view->set('form', $user_form);
		return Response::forge($view);
	}

	public function action_confirm()
	{
		$user_form = $this->user_form;
		$out = '';
		if (!$user_form->validation()->run()) {
			$view = View::forge('member/form');
			$view->set('form', $user_form);
			return Response::forge($view);
		} else {
			//
			// 検証成功時の処理
			//
		}
	}

}
