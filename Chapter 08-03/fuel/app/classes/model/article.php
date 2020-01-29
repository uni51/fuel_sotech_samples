<?php

class Model_Article extends \Orm\Model {

	protected static $_properties = array(
			'id',
			'title' => array(
					'data_type' => 'varchar',
					'label' => 'タイトル',
					'validation' => array('required'),
					'form' => array('type' => 'text'),
			),
			'body' => array(
					'data_type' => 'text',
					'label' => '本文',
					'validation' => array('required'),
					'form' => array('type' => 'textarea'),
			),
			'user_id' => array(
					'data_type' => 'int',
					'validation' => array('required', 'valid_string' => array(array('numeric'))),
					'form' => array('type' => 'hidden'),
			),
			'created_at' => array(
					'form' => array('type' => false),
			),
			'updated_at' => array(
					'form' => array('type' => false),
			),
	);
	
	protected static $_observers = array(
			'Orm\Observer_CreatedAt' => array(
					'events' => array('before_insert'),
					'mysql_timestamp' => false,
			),
			'Orm\Observer_UpdatedAt' => array(
					'events' => array('before_update'),
					'mysql_timestamp' => false,
			),
	);
	protected static $_table_name = 'articles';
	protected static $_belongs_to = array(
			'user' => array(
					'key_from' => 'user_id',
					'model_to' => 'Model_User',
					'key_to' => 'id',
					'cascade_save' => false,
					'cascade_delete' => false,
			)
	);
	protected static $_has_many = array(
			'comments' => array(
					'key_from' => 'id',
					'model_to' => 'Model_Comment',
					'key_to' => 'article_id',
					'cascade_save' => false,
					'cascade_delete' => true,
			)
	);
	protected static $_many_many = array(
			'categories' => array(
					'key_from' => 'id',
					'key_through_from' => 'article_id',
					'table_through' => 'article_category',
					'model_to' => 'Model_Category',
					'key_to' => 'id',
					'cascade_save' => false,
					'cascade_delete' => false,
			)
	);

}
