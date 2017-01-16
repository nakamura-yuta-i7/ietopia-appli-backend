<?php
require_once __DIR__ . "/Database.php";

class IetopiaDbModel extends Database {
	
	const ISINACTIVE = "isinactive";
	
	const ISINACTIVE_ON  = 1;
	const ISINACTIVE_OFF = 0;
	
	function insert($values) {
		if ( ! array_key_exists("created_at", $values) ) {
			$values["created_at"] = date_create()->format("Y-m-d H:i:s");
		}
		return parent::insert($values);
	}
	function update($values, $where) {
		if ( ! array_key_exists("updated_at", $values) ) {
			$values["updated_at"] = date_create()->format("Y-m-d H:i:s");
		}
		return parent::update($values, $where);
	}
	function sync($syncRows, $pk, $where="", $options=["delete"=>true, "inactivate"=>false]) {
		
		$syncTargetRows = $this->findAll(["where"=>$where]);
		foreach ($syncTargetRows as $row) {
			if ( !isset($row[$pk]) ) throw new ErrorException("SyncTargetRow Pk is not found."); 
			break;
		}
		$syncTargetPkVals = [];
		foreach ($syncTargetRows as $row) {
			$syncTargetPkVals[] = $row[$pk];
		}
		
		foreach ($syncRows as $syncRow) {
			$this->upsert($syncRow, $pk);
			$pkVal = $syncRow[$pk];
			if ( ($key = array_search($pkVal, $syncTargetPkVals)) !== false ) {
				# 同期したレコードはPksから削除
				unset($syncTargetPkVals[$key]);
			}
		}
		
		# 残ったPkは同期対象外レコードなので「削除、または無効化」実施
		# 削除モードの方が無効化モードよりも優先順位が高い仕様
		if ( isset($options["delete"]) && $options["delete"] ) {
			foreach ($syncTargetPkVals as $pkVal) {
				$this->delete(" {$pk} = '{$pkVal}' ");
			}
		} elseif ( isset($options["inactivate"]) && $options["inactivate"] ) {
			foreach ($syncTargetPkVals as $pkVal) {
				
				$this->update([
					static::ISINACTIVE => static::ISINACTIVE_ON
				], " {$pk} = '{$pkVal}' ");
			}
		}
	}
}