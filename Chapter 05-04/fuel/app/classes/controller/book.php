<?php

class Controller_Book extends Controller_Template
{

	public function action_list()
	{
		$count = Model_Book::count();
		$config = array(
				'pagination_url' => 'book/list',
				'uri_segment' => 3,
				'num_links' => 4,
				'per_page' => 10,
				'total_items' => $count,
				'show_first' => true,
				'show_last' => true,
		);
		$pagination = Pagination::forge('mypagenation', $config);
		$books = Model_Book::query()
						->rows_offset($pagination->offset)
						->rows_limit($pagination->per_page)
						->get();
		$this->template->title = '夏目漱石：作品一覧';
		$view = View::forge('book/list');
		$view->set_safe('pagination', $pagination);
		$view->set('books', $books);
		$this->template->content = $view;
	}

}
