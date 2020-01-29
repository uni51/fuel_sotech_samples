<?php

class Model_Comment extends \Orm\Model {

	protected static $_properties = array(
			'id',
			'article_id' => array(
					'data_type' => 'int',
					'label' => '記事ID',
					'validation' => array('required', 'valid_string' => array(array('numeric'))),
					'form' => array('type' => 'hidden'),
			),
			'user_id' => array(
					'data_type' => 'int',
					'validation' => array('required', 'valid_string' => array(array('numeric'))),
					'form' => array('type' => 'hidden'),
			),
			'body' => array(
					'data_type' => 'text',
					'label' => 'コメント',
					'validation' => array('required'),
					'form' => array('type' => 'textarea'),
			),
			'created_at' => array(
					'form' => array('type' => false),
			),
			'updated_at' => array(
					'form' => array('type' => false),
			)
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
	protected static $_table_name = 'comments';
	protected static $_belongs_to = array(
			'user' => array(
					'key_from' => 'user_id',
					'model_to' => 'Model_User',
					'key_to' => 'id',
					'cascade_save' => false,
					'cascade_delete' => false,
			)
	);

}
