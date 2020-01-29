<?php

class Model_Comment extends \Orm\Model
{

	protected static $_properties = array(
			'id',
			'article_id',
			'user_id',
			'body',
			'created_at',
			'updated_at',
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
