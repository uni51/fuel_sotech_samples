<?php

class Controller_Contact extends Controller_Template
{

	//お問い合わせフォームで扱うフィールドを配列としてプロパティに設定
	private $fields = array('name', 'email', 'tel', 'body');

	public function action_index()
	{
		//フォームからのPOSTデータがある場合
		if (Input::post('submit')) {

			//各フィールドのPOSTデータをフラッシュセッションに保存する
			foreach ($this->fields as $field) {
				Session::set_flash($field, Input::post($field));
			}
		}

		//Validationオブジェクトの生成
		$val = Validation::forge();

		//検証規則の設定
		$val->add('name', 'お名前')
						->add_rule('required');
		$val->add('tel', 'お電話番号')
						->add_rule('valid_string', array('numeric', 'dashes'));
		$val->add('email', 'Emailアドレス')
						->add_rule('required')
						->add_rule('valid_email');
		$val->add('body', 'お問い合わせ内容')
						->add_rule('required');

		//検証に成功し、CSRFトークンのチェックに成功した場合、確認画面にリダイレクト
		if ($val->run() and Security::check_token()) {
			Response::redirect('contact/confirm');
		}

		//Validationオブジェクトをビューに渡す配列に設定
		$data['val'] = $val;
		$this->template->title = 'お問い合わせ';
		$this->template->content = View::forge('contact/index', $data);
	}

	public function action_confirm()
	{
		$data = array();

		//各フィールドについて・・・
		foreach ($this->fields as $field) {

			//セッション変数をビューに渡す配列に代入
			$data[$field] = Session::get_flash($field);

			//フラッシュセッション変数を次のリクエストまで維持
			Session::keep_flash($field);
		}

		//ビューの読み込み
		$this->template->title = 'お問い合わせ';
		$this->template->content = View::forge('contact/confirm', $data);
	}

	public function action_send()
	{
		//確認画面で「戻る」ボタンが押された場合
		if (Input::post('back')) {

			//各フィールドについてフラッシュセッションの期限を延長
			foreach ($this->fields as $field) {
				Session::keep_flash($field);
			}

			//フォーム画面にリダイレクト
			Response::redirect('contact');
		}
		//CSRF対策用トークンのチェックに失敗した場合、
		//メッセージ画面を表示して終了
		if (!Security::check_token()) {
			$this->template->title = 'お問い合わせ';
			$this->template->content = View::forge('contact/send', array(
									'message' => 'ページ遷移が正しくありません'));
			return;
		}

		//フラッシュセッションを確認（リロード対策）
		if (Session::get_flash('email')) {

			//メール本文のテンプレートとなるビューに渡すデータの初期化
			$mail = array();

			//各フィールドについて、ビューに渡す配列の値を
			//フラッシュセッションから代入
			foreach ($this->fields as $field) {
				$mail[$field] = Session::get_flash($field);
			}

			//メール本文用のビューを呼び出して$mailを埋め込む
			$body = View::forge('contact/contact_mail', $mail);

			//Emailオブジェクトの生成
			\Package::load('email');
			$email = Email::forge();

			//フラッシュセッション変数から取得したフォーム送信者のメールアドレスと名前をFrom:に設定
			$email->from(Session::get_flash('email'), Session::get_flash('name'));

			//設定ファイルから送信先を取得して設定
			$email->to(Config::get('contact_to'));

			//メールの件名を設定
			$email->subject('お問い合わせがありました');

			//メールの件名を設定
			$email->subject('お問い合わせがありました');

			//先にテンプレートに情報を埋め込んだ$bodyを、エンコーディング変換してメール本文に設定
			$email->body(mb_convert_encoding($body, 'jis'));

			//送信の試行
			try {
				$email->send();
			} catch (\EmailValidationFailedException $e) {
				//送信先が正しいEmailアドレスでない場合
				$message = "送信に失敗しました。\n送信先のメールアドレスが正しくありません。";
			} catch (\EmailSendingFailedException $e) {
				//送信に失敗した場合
				$message = "送信に失敗しました。";
			}

		//try ... catchでエラーが返らなかった場合
		$message = '送信しました。';
	} else {

		//フラッシュセッションが取得できなかった場合
		$message = "お問い合わせフォームが正しく送信されていません。\nフォームに戻ってください。";
		}
		$data['message'] = $message;

		//ビューの読み込み
		$this->template->title = 'お問い合わせ';
		$this->template->content = View::forge('contact/send', $data);
	}

}
