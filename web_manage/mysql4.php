<?php
/***************************************************************************
 *                                 mysql4.php
 *                            -------------------
 ***************************************************************************/

if(!defined("SQL_LAYER"))
{

	define("SQL_LAYER","mysql4");
	
	class sql_db
	{
	
		var $db_connect_id;
		var $query_result;
		var $row = array();
		var $rowset = array();
		var $num_queries = 0;
		var $in_transaction = 0;
	
		//
		// Constructor
		//
		function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
		{
			$this->persistency = $persistency;
			$this->user = $sqluser;
			$this->password = $sqlpassword;
			$this->server = $sqlserver;
			$this->dbname = $database;
	
			$this->db_connect_id = ($this->persistency) ? mysql_pconnect($this->server, $this->user, $this->password) : mysql_connect($this->server, $this->user, $this->password);
	
			if( $this->db_connect_id )
			{
				if( $database != "" )
				{
					$this->dbname = $database;
					
					$dbselect = mysql_select_db($this->dbname);
	
					if( !$dbselect )
					{
						mysql_close($this->db_connect_id);
						$this->db_connect_id = $dbselect;
					}
				}
	
				return $this->db_connect_id;
			}
			else
			{
				return false;
			}
		}
	
		//
		// Other base methods
		//
		function sql_close()
		{
			if( $this->db_connect_id )
			{
				//
				// Commit any remaining transactions
				//
				if( $this->in_transaction )
				{
					mysql_query("COMMIT", $this->db_connect_id);
				}
	
				return mysql_close($this->db_connect_id);
			}
			else
			{
				return false;
			}
		}
	
		//
		// Base query method
		//
		function sql_query($query = "", $transaction = FALSE)
		{
			//
			// Remove any pre-existing queries
			//
			unset($this->query_result);
	
			if( $query != "" )
			{
				$this->num_queries++;
				if( $transaction == BEGIN_TRANSACTION && !$this->in_transaction )
				{
					$result = mysql_query("BEGIN", $this->db_connect_id);
					if(!$result)
					{
						return false;
					}
					$this->in_transaction = TRUE;
				}
	
				$this->query_result = mysql_query($query, $this->db_connect_id);
			}
			else
			{
				if( $transaction == END_TRANSACTION && $this->in_transaction )
				{
					$result = mysql_query("COMMIT", $this->db_connect_id);
				}
			}
	
			if( $this->query_result )
			{
				unset($this->row[$this->query_result]);
				unset($this->rowset[$this->query_result]);
	
				if( $transaction == END_TRANSACTION && $this->in_transaction )
				{
					$this->in_transaction = FALSE;
	
					if ( !mysql_query("COMMIT", $this->db_connect_id) )
					{
						mysql_query("ROLLBACK", $this->db_connect_id);
						return false;
					}
				}
				
				return $this->query_result;
			}
			else
			{
				if( $this->in_transaction )
				{
					mysql_query("ROLLBACK", $this->db_connect_id);
					$this->in_transaction = FALSE;
				}
				return false;
			}
		}
	
		//
		// Other query methods
		//
		function sql_numrows($query_id = 0)
		{
			if( !$query_id )
			{
				$query_id = $this->query_result;
			}
	
			return ( $query_id ) ? mysql_num_rows($query_id) : false;
		}
	
		function sql_affectedrows()
		{
			return ( $this->db_connect_id ) ? mysql_affected_rows($this->db_connect_id) : false;
		}
	
		function sql_numfields($query_id = 0)
		{
			if( !$query_id )
			{
				$query_id = $this->query_result;
			}
	
			return ( $query_id ) ? mysql_num_fields($query_id) : false;
		}
	
		function sql_fieldname($offset, $query_id = 0)
		{
			if( !$query_id )
			{
				$query_id = $this->query_result;
			}
	
			return ( $query_id ) ? mysql_field_name($query_id, $offset) : false;
		}
	
		function sql_fieldtype($offset, $query_id = 0)
		{
			if( !$query_id )
			{
				$query_id = $this->query_result;
			}
	
			return ( $query_id ) ? mysql_field_type($query_id, $offset) : false;
		}
	
		function sql_fetchrow($query_id = 0)
		{
			if( !$query_id )
			{
				$query_id = $this->query_result;
			}
	
			if( $query_id )
			{
				$this->row[$query_id] = mysql_fetch_array($query_id, MYSQL_ASSOC);
				return $this->row[$query_id];
			}
			else
			{
				return false;
			}
		}
	
		function sql_fetchrowset($query_id = 0)
		{
			if( !$query_id )
			{
				$query_id = $this->query_result;
			}
	
			if( $query_id )
			{
				unset($this->rowset[$query_id]);
				unset($this->row[$query_id]);
	
				while($this->rowset[$query_id] = mysql_fetch_array($query_id, MYSQL_ASSOC))
				{
					$result[] = $this->rowset[$query_id];
				}
	
				return $result;
			}
			else
			{
				return false;
			}
		}
	
		function sql_fetchfield($field, $rownum = -1, $query_id = 0)
		{
			if( !$query_id )
			{
				$query_id = $this->query_result;
			}
	
			if( $query_id )
			{
				if( $rownum > -1 )
				{
					$result = mysql_result($query_id, $rownum, $field);
				}
				else
				{
					if( empty($this->row[$query_id]) && empty($this->rowset[$query_id]) )
					{
						if( $this->sql_fetchrow() )
						{
							$result = $this->row[$query_id][$field];
						}
					}
					else
					{
						if( $this->rowset[$query_id] )
						{
							$result = $this->rowset[$query_id][$field];
						}
						else if( $this->row[$query_id] )
						{
							$result = $this->row[$query_id][$field];
						}
					}
				}
	
				return $result;
			}
			else
			{
				return false;
			}
		}
	
		function sql_rowseek($rownum, $query_id = 0)
		{
			if( !$query_id )
			{
				$query_id = $this->query_result;
			}
	
			return ( $query_id ) ? mysql_data_seek($query_id, $rownum) : false;
		}
	
		function sql_nextid()
		{
			return ( $this->db_connect_id ) ? mysql_insert_id($this->db_connect_id) : false;
		}
	
		function sql_freeresult($query_id = 0)
		{
			if( !$query_id )
			{
				$query_id = $this->query_result;
			}
	
			if ( $query_id )
			{
				unset($this->row[$query_id]);
				unset($this->rowset[$query_id]);
	
				mysql_free_result($query_id);
	
				return true;
			}
			else
			{
				return false;
			}
		}
	
		function sql_error()
		{
			$result['message'] = mysql_error($this->db_connect_id);
			$result['code'] = mysql_errno($this->db_connect_id);
	
			return $result;
		}
		
		/**
		 * 对字段两边加反引号，以保证数据库安全
		 * @param $value 数组值
		 */
		public function add_special_char(&$value) {
			if('*' == $value || false !== strpos($value, '(') || false !== strpos($value, '.') || false !== strpos ( $value, '`')) {
				//不处理包含* 或者 使用了sql方法。
			} else {
				$value = '`'.trim($value).'`';
			}
			if (preg_match("/\b(select|insert|update|delete)\b/i", $value)) {
				$value = preg_replace("/\b(select|insert|update|delete)\b/i", '', $value);
			}
			return $value;
		}
		
		/**
		 * 对字段值两边加引号，以保证数据库安全
		 * @param $value 数组值
		 * @param $key 数组key
		 * @param $quotation 
		 */
		public function escape_string(&$value, $key='', $quotation = 1) {
			if ($quotation) {
				$q = '\'';
			} else {
				$q = '';
			}
			$value = $q.$value.$q;
			return $value;
		}		
			
		/**
		 * 执行添加记录操作
		 * @param $data 		要增加的数据，参数为数组。数组key为字段值，数组值为数据取值
		 * @param $table 		数据表
		 * @return boolean
		 */
		public function insert($data, $table, $return_insert_id = false, $replace = false) {
			global $tablepre;
 			if(!is_array( $data ) || $table == '' || count($data) == 0) {
				return false;
			}
			
			$fielddata = array_keys($data);
			$valuedata = array_values($data);
			array_walk($fielddata, array($this, 'add_special_char'));
			array_walk($valuedata, array($this, 'escape_string'));
 			$field = implode (',', $fielddata);
			$value = implode (',', $valuedata);

			$cmd = $replace ? 'REPLACE INTO' : 'INSERT INTO';
			$sql = $cmd.' `'.$tablepre.''.$table.'`('.$field.') VALUES ('.$value.')';
 			
			$return = $this->sql_query($sql);
			exit($sql);
			return $return_insert_id ? $this->sql_nextid() : $return;
		}
		
		/**
		 * 获取最后一次添加记录的主键号
		 * @return int 
		 */
		public function insert_id() {
			return mysql_insert_id($this->link);
		}
		
		/**
		 * 执行更新记录操作
		 * @param $data 		要更新的数据内容，参数可以为数组也可以为字符串，建议数组。
		 * 						为数组时数组key为字段值，数组值为数据取值
		 * 						为字符串时[例：`name`='phpcms',`hits`=`hits`+1]。
		 *						为数组时[例: array('name'=>'phpcms','password'=>'123456')]
		 *						数组可使用array('name'=>'+=1', 'base'=>'-=1');程序会自动解析为`name` = `name` + 1, `base` = `base` - 1
		 * @param $table 		数据表
		 * @param $where 		更新数据时的条件
		 * @return boolean
		 */
		public function update($data, $table, $where = '') {
			global $tablepre;
			if($table == '' or $where == '') {
				return false;
			}
	
			$where = ' WHERE '.$where;
			$field = '';
			if(is_string($data) && $data != '') {
				$field = $data;
			} elseif (is_array($data) && count($data) > 0) {
				$fields = array();
				foreach($data as $k=>$v) {
					switch (substr($v, 0, 2)) {
						case '+=':
							$v = substr($v,2);
							if (is_numeric($v)) {
								$fields[] = $this->add_special_char($k).'='.$this->add_special_char($k).'+'.$this->escape_string($v, '', false);
							} else {
								continue;
							}
							
							break;
						case '-=':
							$v = substr($v,2);
							if (is_numeric($v)) {
								$fields[] = $this->add_special_char($k).'='.$this->add_special_char($k).'-'.$this->escape_string($v, '', false);
							} else {
								continue;
							}
							break;
						default:
							$fields[] = $this->add_special_char($k).'='.$this->escape_string($v);
					}
				}
				$field = implode(',', $fields);
			} else {
				return false;
			}
	
			$sql = 'UPDATE `'.$tablepre.''.$table.'` SET '.$field.$where;
			 //exit($sql);
			return $this->sql_query($sql);
		}
		
		/**
		 * 执行删除记录操作
		 * @param $table 		数据表
		 * @param $where 		删除数据条件,不充许为空。
		 * 						如果要清空表，使用empty方法
		 * @return boolean
		 */
		public function delete($table, $where) {
			global $tablepre;
			if ($table == '' || $where == '') {
				return false;
			}
			$where = ' WHERE '.$where;
			$sql = 'DELETE FROM `'.$tablepre.''.$table.'`'.$where;
			//exit($sql);
			return $this->sql_query($sql);
		}
	
	} 
	

} // if ... define

?>
