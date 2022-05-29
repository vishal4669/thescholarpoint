<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();


// CREATING LIVE CLASS TABLE
$fields = array(
	'id' => array(
		'type' => 'INT',
		'constraint' => 11,
		'unsigned' => TRUE,
		'auto_increment' => TRUE,
		'collation' => 'utf8_unicode_ci'
	),
	'course_id' => array(
		'type' => 'INT',
		'constraint' => '11',
		'default' => null,
		'null' => TRUE,
		'collation' => 'utf8_unicode_ci'
	),
	'date' => array(
		'type' => 'INT',
		'constraint' => '11',
		'default' => null,
		'null' => TRUE,
		'collation' => 'utf8_unicode_ci'
	),
	'time' => array(
		'type' => 'INT',
		'constraint' => '11',
		'default' => null,
		'null' => TRUE,
		'collation' => 'utf8_unicode_ci'
	),
	'zoom_meeting_id' => array(
		'type' => 'VARCHAR',
		'constraint' => '255',
		'default' => null,
		'null' => TRUE,
		'collation' => 'utf8_unicode_ci'
	),
	'zoom_meeting_password' => array(
		'type' => 'VARCHAR',
		'constraint' => '255',
		'default' => null,
		'null' => TRUE,
		'collation' => 'utf8_unicode_ci'
	),
	'note_to_students' => array(
		'type' => 'LONGTEXT',
		'default' => null,
		'null' => TRUE,
		'collation' => 'utf8_unicode_ci'
	)
);
$CI->dbforge->add_field($fields);
$CI->dbforge->add_key('id', TRUE);
$attributes = array('collation' => "utf8_unicode_ci");
$CI->dbforge->create_table('live_class', TRUE);


if ($CI->db->get_where('settings', array('key' => 'zoom_api_key'))->num_rows() == 0) {
	// INSERT DATA IN SETTINGS TABLE
	$settings_data_1 = array( 'key' => 'zoom_api_key', 'value' => 'zoom_api_key' );
	$CI->db->insert('settings', $settings_data_1);
}

if ($CI->db->get_where('settings', array('key' => 'zoom_secret_key'))->num_rows() == 0) {
	// INSERT DATA IN SETTINGS TABLE
	$settings_data_2 = array( 'key' => 'zoom_secret_key', 'value' => 'zoom_secret_key' );
	$CI->db->insert('settings', $settings_data_2);
}

?>
