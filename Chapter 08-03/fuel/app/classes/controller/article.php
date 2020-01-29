<?php

class Controller_Article extends Controller_Template {

	private $per_page = 3;

	public function before() {
		parent::before();
		if (!Auth::check() and !in_array(Request::active()->action, array('login', 'index', 'view'))) {
			Response::redirect('article/login');
		}
	}

	public function action_index() {
//ビューに渡す配列の初期化
		$data = array();

//ページネーションの設定
		$count = Model_Article::count();
		$config = array(
				'pagination_url' => 'article',
				'uri_segment' => 2,
				'num_links' => 4,
				'per_page' => $this->per_page,
				'total_items' => $count,
		);
		$pagination = Pagination::forge('article_pagination', $config);

//モデルから記事を取得
		$data['articles'] = Model_Article::query()
						->order_by('id', 'desc')
						->limit($this->per_page)
						->offset($pagination->offset)
						->get();

//ビューの読み込み
		$this->template->title = '記事一覧';
		$this->template->content = View::forge('article/list', $data);
		$this->template->content->set_safe('pagination', $pagination);
	}

	public function action_create() {
//Model_Articleオブジェクトを新規作成
		$article = Model_Article::forge();
		$article->user_id = Arr::get(Auth::get_user_id(), 1);

//Fieldsetオブジェクトにモデルを登録
		$fieldset = Fieldset::forge()->add_model('Model_Article')->populate($article, true);

//カテゴリのチェックボックス用のオプション配列の作成
		$categories = Model_Category::find('all');
		$category_options = array();
		foreach ($categories as $category) {
			$category_options[$category->id] = $category->name;
		}
//フォーム要素の追加
		$form = $fieldset->form();

//カテゴリチェックボックスの追加
		$form->add('category_id', 'カテゴリ', array('type' => 'checkbox', 'options' => $category_options));

//投稿ボタンの追加
		$form->add('submit', '', array('type' => 'submit', 'value' => '投稿', 'class' => 'btn medium primary'));

//Validationの実行
		if ($fieldset->validation()->run()) {
//Validationに成功したフィールドの読み込み
			$fields = $fieldset->validated();

//Model_Articleオブジェクトの生成
			$article = Model_Article::forge();

//Model_Articleオブジェクトのプロパティの設定
			$article->title = $fields['title'];
			$article->body = $fields['body'];
			$article->user_id = $fields['user_id'];

//カテゴリIDからカテゴリオブジェクトを生成して$categoriesプロパティに設定
			if ($fields['category_id']) {
				foreach ($fields['category_id'] as $category_id) {
					$category = Model_Category::find($category_id);
					if ($category) {
						$article->categories[] = $category;
					}
				}
			}
			if ($article->save()) {
				Response::redirect('article/view/' . $article->id);
			}
		}
		$this->template->title = '新規投稿';
		$this->template->set('content', $form->build(), false);
	}

	public function action_edit($id = 0) {//Model_Articleオブジェクトの読み込み
		if ($id) {
			$article = Model_Article::find($id);
			if (!$article or $article->user_id != Arr::get(Auth::get_user_id(), 1)) {
				Response::redirect('article');
			}
		}

		//Fieldsetオブジェクトにモデルを登録
		$fieldset = Fieldset::forge()->add_model('Model_Article')->populate($article, true);

		//フォーム要素の追加
		$form = $fieldset->form();

		//投稿ボタンの追加
		$form->add('submit', '', array('type' => 'submit', 'value' => '更新', 'class' => 'btn medium primary'));

		//Validationの実行
		if ($fieldset->validation()->run()) {
			//Validationに成功したフィールドの読み込み
			$fields = $fieldset->validated();

			//Model_Articleオブジェクトのプロパティの設定
			$article->title = $fields['title'];
			$article->body = $fields['body'];
			$article->user_id = $fields['user_id'];
			if ($article->save()) {
				Response::redirect('article/view/' . $article->id);
			}
		}
		$this->template->title = '編集';
		$this->template->set('content', $form->build(), false);
	}

	public function action_view($id = 0) {
		//ビューに渡す配列の初期化
		$data = array();

		//IDが指定されていない場合や、指定されたIDの記事が見つからない場合は一覧にリダイレクト
		$id and $data['article'] = Model_Article::find($id);
		if (!$data['article']) {
			Response::redirect('article');
		}

		//Model_Commentオブジェクトの新規作成
		$comment = Model_Comment::forge();
		$comment->user_id = Arr::get(Auth::get_user_id(), 1);
		$comment->article_id = $id;

		//Fieldsetオブジェクトにモデルを登録
		$fieldset = Fieldset::forge()->add_model('Model_Comment')->populate($comment, true);

		//フォーム要素の追加
		$form = $fieldset->form();

		//投稿ボタンの追加
		$form->add('submit', '', array('type' => 'submit', 'value' => 'コメントする', 'class' => 'btn medium primary'));

		//Validationの実行
		if ($fieldset->validation()->run()) {

			//Validationに成功したフィールドの読み込み
			$fields = $fieldset->validated();

			//Model_Commentオブジェクトのプロパティの設定
			$comment->body = $fields['body'];
			$comment->user_id = $fields['user_id'];
			$comment->article_id = $fields['article_id'];

			//保存に成功したら元のページにリダイレクト
			if ($comment->save()) {
				Response::redirect('article/view/' . $id);
			}
		}
		//ログイン中のみフォームを表示
		if (Auth::check()) {
			$data['form'] = $form->build();
		} else {

			//ログインしていない場合は空文字列を渡す
			$data['form'] = '';
		}

		//ビューの読み込み
		$this->template->title = $data['article']->title;
		$this->template->content = View::forge('article/view', $data, false);
	}

	public function action_login() {
//既にログイン済みであればブログトップページにリダイレクト
		Auth::check() and Response::redirect('article');

//ビューに渡す配列の初期化
		$data = array();

//Auth_Login_Driverクラスのインスタンスの作成
		$auth = Auth::instance();
//usernameとpasswordがPOSTされている場合は認証を試みる
		if (Input::post('username') and Input::post('password')) {
			$username = Input::post('username');
			$password = Input::post('password');
			$auth = Auth::instance();

//認証
			if ($auth->login($username, $password)) {

//ブログトップにリダイレクト
				Response::redirect('article');
			} else {
//認証失敗時にはビューに$errorをセットする
				$data['error'] = true;
			}
		}
//usernameとpasswordのいずれか一方でも送信されていない場合
//および認証に失敗した場合はログインフォームを表示
//ビューの読み込み
		$this->template->title = 'ログイン';
		$this->template->content = View::forge('article/login');
	}

	public function action_logout() {
//ログアウト
		$auth = Auth::instance();
		$auth->logout();

//'member'にリダイレクト
		Response::redirect('article');
	}

}
