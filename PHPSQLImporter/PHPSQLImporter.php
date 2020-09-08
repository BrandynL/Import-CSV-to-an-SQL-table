<?php

namespace PHPSQLImporter;

class PHPSQLImporter
{

	public function __construct()
	{
		$this->table_name = $_ENV["php_sql_import_table_name"];
		$this->path_to_file = $_ENV["php_sql_import_path_to_file"];
		$this->delimiter = $_ENV["php_sql_import_delimiter"];

		$this->db_info = $_ENV["php_sql_import_db_connection"];
		$this->db_user = $_ENV["php_sql_import_db_user"];
		$this->db_pass = $_ENV["php_sql_import_db_pass"];
	}
}
