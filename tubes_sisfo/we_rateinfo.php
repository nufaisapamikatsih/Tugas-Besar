<?php

// Global variable for table object
$we_rate = NULL;

//
// Table class for we_rate
//
class cwe_rate extends cTable {
	var $werate_id;
	var $work_task;
	var $fk_werate;
	var $fk_werate2;
	var $from_date;
	var $thru_date;
	var $rate;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'we_rate';
		$this->TableName = 'we_rate';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// werate_id
		$this->werate_id = new cField('we_rate', 'we_rate', 'x_werate_id', 'werate_id', '`werate_id`', '`werate_id`', 200, -1, FALSE, '`werate_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['werate_id'] = &$this->werate_id;

		// work_task
		$this->work_task = new cField('we_rate', 'we_rate', 'x_work_task', 'work_task', '`work_task`', '`work_task`', 200, -1, FALSE, '`work_task`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['work_task'] = &$this->work_task;

		// fk_werate
		$this->fk_werate = new cField('we_rate', 'we_rate', 'x_fk_werate', 'fk_werate', '`fk_werate`', '`fk_werate`', 200, -1, FALSE, '`fk_werate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fk_werate'] = &$this->fk_werate;

		// fk_werate2
		$this->fk_werate2 = new cField('we_rate', 'we_rate', 'x_fk_werate2', 'fk_werate2', '`fk_werate2`', '`fk_werate2`', 200, -1, FALSE, '`fk_werate2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fk_werate2'] = &$this->fk_werate2;

		// from_date
		$this->from_date = new cField('we_rate', 'we_rate', 'x_from_date', 'from_date', '`from_date`', '`from_date`', 200, -1, FALSE, '`from_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['from_date'] = &$this->from_date;

		// thru_date
		$this->thru_date = new cField('we_rate', 'we_rate', 'x_thru_date', 'thru_date', '`thru_date`', '`thru_date`', 200, -1, FALSE, '`thru_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['thru_date'] = &$this->thru_date;

		// rate
		$this->rate = new cField('we_rate', 'we_rate', 'x_rate', 'rate', '`rate`', '`rate`', 3, -1, FALSE, '`rate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->rate->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['rate'] = &$this->rate;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`we_rate`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`we_rate`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('werate_id', $rs))
				ew_AddFilter($where, ew_QuotedName('werate_id') . '=' . ew_QuotedValue($rs['werate_id'], $this->werate_id->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`werate_id` = '@werate_id@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@werate_id@", ew_AdjustSql($this->werate_id->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "we_ratelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "we_ratelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("we_rateview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("we_rateview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "we_rateadd.php?" . $this->UrlParm($parm);
		else
			return "we_rateadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("we_rateedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("we_rateadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("we_ratedelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->werate_id->CurrentValue)) {
			$sUrl .= "werate_id=" . urlencode($this->werate_id->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["werate_id"]; // werate_id

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->werate_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->werate_id->setDbValue($rs->fields('werate_id'));
		$this->work_task->setDbValue($rs->fields('work_task'));
		$this->fk_werate->setDbValue($rs->fields('fk_werate'));
		$this->fk_werate2->setDbValue($rs->fields('fk_werate2'));
		$this->from_date->setDbValue($rs->fields('from_date'));
		$this->thru_date->setDbValue($rs->fields('thru_date'));
		$this->rate->setDbValue($rs->fields('rate'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// werate_id
		// work_task
		// fk_werate
		// fk_werate2
		// from_date
		// thru_date
		// rate
		// werate_id

		$this->werate_id->ViewValue = $this->werate_id->CurrentValue;
		$this->werate_id->ViewCustomAttributes = "";

		// work_task
		$this->work_task->ViewValue = $this->work_task->CurrentValue;
		$this->work_task->ViewCustomAttributes = "";

		// fk_werate
		$this->fk_werate->ViewValue = $this->fk_werate->CurrentValue;
		$this->fk_werate->ViewCustomAttributes = "";

		// fk_werate2
		$this->fk_werate2->ViewValue = $this->fk_werate2->CurrentValue;
		$this->fk_werate2->ViewCustomAttributes = "";

		// from_date
		$this->from_date->ViewValue = $this->from_date->CurrentValue;
		$this->from_date->ViewCustomAttributes = "";

		// thru_date
		$this->thru_date->ViewValue = $this->thru_date->CurrentValue;
		$this->thru_date->ViewCustomAttributes = "";

		// rate
		$this->rate->ViewValue = $this->rate->CurrentValue;
		$this->rate->ViewCustomAttributes = "";

		// werate_id
		$this->werate_id->LinkCustomAttributes = "";
		$this->werate_id->HrefValue = "";
		$this->werate_id->TooltipValue = "";

		// work_task
		$this->work_task->LinkCustomAttributes = "";
		$this->work_task->HrefValue = "";
		$this->work_task->TooltipValue = "";

		// fk_werate
		$this->fk_werate->LinkCustomAttributes = "";
		$this->fk_werate->HrefValue = "";
		$this->fk_werate->TooltipValue = "";

		// fk_werate2
		$this->fk_werate2->LinkCustomAttributes = "";
		$this->fk_werate2->HrefValue = "";
		$this->fk_werate2->TooltipValue = "";

		// from_date
		$this->from_date->LinkCustomAttributes = "";
		$this->from_date->HrefValue = "";
		$this->from_date->TooltipValue = "";

		// thru_date
		$this->thru_date->LinkCustomAttributes = "";
		$this->thru_date->HrefValue = "";
		$this->thru_date->TooltipValue = "";

		// rate
		$this->rate->LinkCustomAttributes = "";
		$this->rate->HrefValue = "";
		$this->rate->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// werate_id
		$this->werate_id->EditAttrs["class"] = "form-control";
		$this->werate_id->EditCustomAttributes = "";
		$this->werate_id->EditValue = $this->werate_id->CurrentValue;
		$this->werate_id->ViewCustomAttributes = "";

		// work_task
		$this->work_task->EditAttrs["class"] = "form-control";
		$this->work_task->EditCustomAttributes = "";
		$this->work_task->EditValue = ew_HtmlEncode($this->work_task->CurrentValue);
		$this->work_task->PlaceHolder = ew_RemoveHtml($this->work_task->FldCaption());

		// fk_werate
		$this->fk_werate->EditAttrs["class"] = "form-control";
		$this->fk_werate->EditCustomAttributes = "";
		$this->fk_werate->EditValue = ew_HtmlEncode($this->fk_werate->CurrentValue);
		$this->fk_werate->PlaceHolder = ew_RemoveHtml($this->fk_werate->FldCaption());

		// fk_werate2
		$this->fk_werate2->EditAttrs["class"] = "form-control";
		$this->fk_werate2->EditCustomAttributes = "";
		$this->fk_werate2->EditValue = ew_HtmlEncode($this->fk_werate2->CurrentValue);
		$this->fk_werate2->PlaceHolder = ew_RemoveHtml($this->fk_werate2->FldCaption());

		// from_date
		$this->from_date->EditAttrs["class"] = "form-control";
		$this->from_date->EditCustomAttributes = "";
		$this->from_date->EditValue = ew_HtmlEncode($this->from_date->CurrentValue);
		$this->from_date->PlaceHolder = ew_RemoveHtml($this->from_date->FldCaption());

		// thru_date
		$this->thru_date->EditAttrs["class"] = "form-control";
		$this->thru_date->EditCustomAttributes = "";
		$this->thru_date->EditValue = ew_HtmlEncode($this->thru_date->CurrentValue);
		$this->thru_date->PlaceHolder = ew_RemoveHtml($this->thru_date->FldCaption());

		// rate
		$this->rate->EditAttrs["class"] = "form-control";
		$this->rate->EditCustomAttributes = "";
		$this->rate->EditValue = ew_HtmlEncode($this->rate->CurrentValue);
		$this->rate->PlaceHolder = ew_RemoveHtml($this->rate->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->werate_id->Exportable) $Doc->ExportCaption($this->werate_id);
					if ($this->work_task->Exportable) $Doc->ExportCaption($this->work_task);
					if ($this->fk_werate->Exportable) $Doc->ExportCaption($this->fk_werate);
					if ($this->fk_werate2->Exportable) $Doc->ExportCaption($this->fk_werate2);
					if ($this->from_date->Exportable) $Doc->ExportCaption($this->from_date);
					if ($this->thru_date->Exportable) $Doc->ExportCaption($this->thru_date);
					if ($this->rate->Exportable) $Doc->ExportCaption($this->rate);
				} else {
					if ($this->werate_id->Exportable) $Doc->ExportCaption($this->werate_id);
					if ($this->work_task->Exportable) $Doc->ExportCaption($this->work_task);
					if ($this->fk_werate->Exportable) $Doc->ExportCaption($this->fk_werate);
					if ($this->fk_werate2->Exportable) $Doc->ExportCaption($this->fk_werate2);
					if ($this->from_date->Exportable) $Doc->ExportCaption($this->from_date);
					if ($this->thru_date->Exportable) $Doc->ExportCaption($this->thru_date);
					if ($this->rate->Exportable) $Doc->ExportCaption($this->rate);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->werate_id->Exportable) $Doc->ExportField($this->werate_id);
						if ($this->work_task->Exportable) $Doc->ExportField($this->work_task);
						if ($this->fk_werate->Exportable) $Doc->ExportField($this->fk_werate);
						if ($this->fk_werate2->Exportable) $Doc->ExportField($this->fk_werate2);
						if ($this->from_date->Exportable) $Doc->ExportField($this->from_date);
						if ($this->thru_date->Exportable) $Doc->ExportField($this->thru_date);
						if ($this->rate->Exportable) $Doc->ExportField($this->rate);
					} else {
						if ($this->werate_id->Exportable) $Doc->ExportField($this->werate_id);
						if ($this->work_task->Exportable) $Doc->ExportField($this->work_task);
						if ($this->fk_werate->Exportable) $Doc->ExportField($this->fk_werate);
						if ($this->fk_werate2->Exportable) $Doc->ExportField($this->fk_werate2);
						if ($this->from_date->Exportable) $Doc->ExportField($this->from_date);
						if ($this->thru_date->Exportable) $Doc->ExportField($this->thru_date);
						if ($this->rate->Exportable) $Doc->ExportField($this->rate);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
