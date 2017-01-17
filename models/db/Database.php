<?php
class Database {
	public $table = "";
	protected $_conn;
	function setConnection(ConnectionInterface $conn) {
		$this->_conn = $conn;
	}
	function __call($name, $arguments) {
		return call_user_func_array([$this->_conn, $name], $arguments);
	}
	function findAll($params=[]) {
		$where = call_user_func(function() use($params) {
			if ( !isset($params["where"]) ) return ""; 
			return strlen($where) ? " WHERE ". $params["where"] : "";
		});
		
		$join  = isset($params["join"])  ? " ". $params["join"] : "";
		$fields = isset($params["fields"])  ? $params["fields"] : " * ";
		$fields = is_array($fields) ? implode(",", $fields) : $fields;
		$sql = " SELECT {$fields} FROM " . $this->table . " {$join} {$where} ";
		return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}
	function findOne($where) {
		$sql = " SELECT * FROM " . $this->table . " WHERE " . $where;
		return $this->query($sql)->fetch(PDO::FETCH_ASSOC);
	}
	function findCount($where) {
		$sql = " SELECT COUNT(*) AS cnt FROM " . $this->table . " WHERE " . $where;
		return $this->query($sql)->fetch(PDO::FETCH_ASSOC)["cnt"];
	}
	function delete($where) {
		$table = $this->table;
		$sql = " DELETE FROM {$table} WHERE {$where} ";
		return $this->query($sql);
	}
	function upsert($values, $pk) {
		if ( ! array_key_exists($pk, $values) ) {
			return $this->insert($values);
		}
		$pkVal = $values[$pk];
		$where = "{$pk} = '{$pkVal}' ";
		$count = $this->findCount($where);
		if ( $count == 0 ) {
			return $this->insert($values);
		} elseif ( $count == 1 ) {
			return $this->update($values, $where);
		}
		throw new ErrorException("upsert検索で複数件ヒットしました  "
			 . var_export(compact("count","where"), TRUE));
	}
	function insert($values) {
		$fields_string = implode(", ", array_keys($values) );
		$values_string = "";
		$values_strings = [];
		foreach (array_values($values) as $val) {
			$values_strings[] = "'{$val}'";
		}
		$values_string = implode(",", $values_strings );
		$sql = "INSERT INTO ". $this->table ." ({$fields_string}) VALUES ({$values_string})";
		return $this->query($sql);
	}
	function update($values, $where) {
		$values_string = "";
		$values_strings = [];
		foreach ( $values as $key => $val ) {
			$values_strings[] = " {$key} = '{$val}' ";
		}
		if ( ! $where ) {
			$where = " 1 = 1 ";
		}
		$values_string = implode(",", $values_strings );
		$sql = 'UPDATE '. $this->table .' SET '. $values_string .' WHERE ' . $where;
		return $this->query($sql);
	}
}
