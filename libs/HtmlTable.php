<?php

class HtmlTable {
	protected $_rows;
	protected $_tableHtml;
	protected $_attrs = [];
	function __construct($rows=[]) {
		$this->_rows = $rows;
	}
	function setAttrs($attrs=[]) {
		$this->_attrs = $attrs;
	}
	function getHtml() {
		$this->_build();
		return $this->_tableHtml;
	}
	function _build() {
		$attrs = "";
		if ( !$this->_attrs ) {
			$attrs = ' id="html-table" class="ui small table striped celled" cellspacing="0" width="100%"';
		}
		foreach ($this->_attrs as $attrName => $val) {
			$attrs .= " {$attrName}='{$val}'";
		}
		$table = "<table{$attrs}>";
		$table .= $this->_createThead();
		$table .= $this->_createTFoot();
		$table .= $this->_createTbody();
		$table .= '</table>';
		$this->_tableHtml = $table;
	}
	function _createThead() {
		$thead = '<thead>';
		$thead .= $this->_createTrWithThs();
		$thead .= '</thead>';
		return $thead;
	}
	function _createTFoot() {
		$thead = '<tfoot>';
		$thead .= $this->_createTrWithThs();
		$thead .= '</tfoot>';
		return $thead;
	}
	function _createTrWithThs() {
		if ( ! $this->_rows ) { return ""; }
		$tr = '<tr>';
		foreach ( array_keys($this->_rows[0]) as $key ) {
			$th = '<th class="'.$key.'">';
			$th .= $key;
			$th .= '</th>';
			$tr .= $th;
		}
		return $tr .= '</tr>';
	}
	function _createTbody() {
		if ( ! $this->_rows ) { return ""; }
		$tbody = '<tbody>';
		foreach ($this->_rows as $row) {
			$tbody .= $this->_createTrWithTds($row);
		}
		$tbody .= '</tbody>';
		return $tbody;
	}
	function _createTrWithTds($row) {
		$tr = '<tr>';
		foreach ( $row as $key => $val ) {
			$td = '<td class="'.$key.'">';
			$td .= $val;
			$td .= '</td>';
			$tr .= $td;
		}
		return $tr .= '</tr>';
	}
}