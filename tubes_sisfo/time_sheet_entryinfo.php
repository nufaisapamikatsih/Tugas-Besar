<?php

// Global variable for table object
$time_sheet_entry = NULL;

//
// Table class for time_sheet_entry
//
class ctime_sheet_entry extends cTable {
	var $tse_id;
	var $ts_from;
	var $ts_thru;
	var $fk_tse;
	var $we_id;
	var $te_from;
	var $te_thru;
	var $hours;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'time_sheet_entry';
		$this->TableName = 'time_sheet_entry';
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

		// tse_id
		$this->tse_id = new cField('time_sheet_entry', 'time_sheet_entry', 'x_tse_id', 'tse_id', '`tse_id`', '`tse_id`', 200, -1, FALSE, '`tse_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tse_id'] = &$this->tse_id;

		// ts_from
		$this->ts_from = new cField('time_sheet_entry', 'time_sheet_entry', 'x_ts_from', 'ts_from', '`ts_from`', '`ts_from`', 200, -1, FALSE, '`ts_from`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ts_from'] = &$this->ts_from;

		// ts_thru
		$this->ts_thru = new cField('time_sheet_entry', 'time_sheet_entry', 'x_ts_thru', 'ts_thru', '`ts_thru`', '`ts_thru`', 200, -1, FALSE, '`ts_thru`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ts_thru'] = &$this->ts_thru;

		// fk_tse
		$this->fk_tse = new cField('time_sheet_entry', 'time_sheet_entry', 'x_fk_tse', 'fk_tse', '`fk_tse`', '`fk_tse`', 200, -1, FALSE, '`fk_tse`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fk_tse'] = &$this->fk_tse;

		// we_id
		$this->we_id = new cField('time_sheet_entry', 'time_sheet_entry', 'x_we_id', 'we_id', '`we_id`', '`we_id`', 200, -1, FALSE, '`we_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['we_id'] = &$this->we_id;

		// te_from
		$this->te_from = new cField('time_sheet_entry', 'time_sheet_entry', 'x_te_from', 'te_from', '`te_from`', '`te_from`', 200, -1, FALSE, '`te_from`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['te_from'] = &$this->te_from;

		// te_thru
		$this->te_thru = new cField('time_sheet_entry', 'time_sheet_entry', 'x_te_thru', 'te_thru', '`te_thru`', '`te_thru`', 200, -1, FALSE, '`te_thru`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['te_thru'] = &$this->te_thru;

		// hours
		$this->hours = new cField('time_sheet_entry', 'time_sheet_entry', 'x_hours', 'hours', '`hours`', '`hours`', 3, -1, FALSE, '`hours`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->hours->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['hours'] = &$this->hours;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`time_sheet_entry`";
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
	var $UpdateTable = "`time_sheet_entry`";

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
			if (array_key_exists('tse_id', $rs))
				ew_AddFilter($where, ew_QuotedName('tse_id') . '=' . ew_QuotedValue($rs['tse_id'], $this->tse_id->FldDataType));
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
		return "`tse_id` = '@tse_id@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@tse_id@", ew_AdjustSql($this->tse_id->CurrentValue), $sKeyFilter); // Replace key value
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
			return "time_sheet_entrylist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "time_sheet_entrylist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("time_sheet_entryview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("time_sheet_entryview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "time_sheet_entryadd.php?" . $this->UrlParm($parm);
		else
			return "time_sheet_entryadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("time_sheet_entryedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("time_sheet_entryadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("time_sheet_entrydelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->tse_id->CurrentValue)) {
			$sUrl .= "tse_id=" . urlencode($this->tse_id->CurrentValue);
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
			$arKeys[] = @$_GET["tse_id"]; // tse_id

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
			$this->tse_id->CurrentValue = $key;
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
		$this->tse_id->setDbValue($rs->fields('tse_id'));
		$this->ts_from->setDbValue($rs->fields('ts_from'));
		$this->ts_thru->setDbValue($rs->fields('ts_thru'));
		$this->fk_tse->setDbValue($rs->fields('fk_tse'));
		$this->we_id->setDbValue($rs->fields('we_id'));
		$this->te_from->setDbValue($rs->fields('te_from'));
		$this->te_thru->setDbValue($rs->fields('te_thru'));
		$this->hours->setDbValue($rs->fields('hours'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// tse_id
		// ts_from
		// ts_thru
		// fk_tse
		// we_id
		// te_from
		// te_thru
		// hours
		// tse_id

		$this->tse_id->ViewValue = $this->tse_id->CurrentValue;
		$this->tse_id->ViewCustomAttributes = "";

		// ts_from
		$this->ts_from->ViewValue = $this->ts_from->CurrentValue;
		$this->ts_from->ViewCustomAttributes = "";

		// ts_thru
		$this->ts_thru->ViewValue = $this->ts_thru->CurrentValue;
		$this->ts_thru->ViewCustomAttributes = "";

		// fk_tse
		$this->fk_tse->ViewValue = $this->fk_tse->CurrentValue;
		$this->fk_tse->ViewCustomAttributes = "";

		// we_id
		$this->we_id->ViewValue = $this->we_id->CurrentValue;
		$this->we_id->ViewCustomAttributes = "";

		// te_from
		$this->te_from->ViewValue = $this->te_from->CurrentValue;
		$this->te_from->ViewCustomAttributes = "";

		// te_thru
		$this->te_thru->ViewValue = $this->te_thru->CurrentValue;
		$this->te_thru->ViewCustomAttributes = "";

		// hours
		$this->hours->ViewValue = $this->hours->CurrentValue;
		$this->hours->ViewCustomAttributes = "";

		// tse_id
		$this->tse_id->LinkCustomAttributes = "";
		$this->tse_id->HrefValue = "";
		$this->tse_id->TooltipValue = "";

		// ts_from
		$this->ts_from->LinkCustomAttributes = "";
		$this->ts_from->HrefValue = "";
		$this->ts_from->TooltipValue = "";

		// ts_thru
		$this->ts_thru->LinkCustomAttributes = "";
		$this->ts_thru->HrefValue = "";
		$this->ts_thru->TooltipValue = "";

		// fk_tse
		$this->fk_tse->LinkCustomAttributes = "";
		$this->fk_tse->HrefValue = "";
		$this->fk_tse->TooltipValue = "";

		// we_id
		$this->we_id->LinkCustomAttributes = "";
		$this->we_id->HrefValue = "";
		$this->we_id->TooltipValue = "";

		// te_from
		$this->te_from->LinkCustomAttributes = "";
		$this->te_from->HrefValue = "";
		$this->te_from->TooltipValue = "";

		// te_thru
		$this->te_thru->LinkCustomAttributes = "";
		$this->te_thru->HrefValue = "";
		$this->te_thru->TooltipValue = "";

		// hours
		$this->hours->LinkCustomAttributes = "";
		$this->hours->HrefValue = "";
		$this->hours->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// tse_id
		$this->tse_id->EditAttrs["class"] = "form-control";
		$this->tse_id->EditCustomAttributes = "";
		$this->tse_id->EditValue = $this->tse_id->CurrentValue;
		$this->tse_id->ViewCustomAttributes = "";

		// ts_from
		$this->ts_from->EditAttrs["class"] = "form-control";
		$this->ts_from->EditCustomAttributes = "";
		$this->ts_from->EditValue = ew_HtmlEncode($this->ts_from->CurrentValue);
		$this->ts_from->PlaceHolder = ew_RemoveHtml($this->ts_from->FldCaption());

		// ts_thru
		$this->ts_thru->EditAttrs["class"] = "form-control";
		$this->ts_thru->EditCustomAttributes = "";
		$this->ts_thru->EditValue = ew_HtmlEncode($this->ts_thru->CurrentValue);
		$this->ts_thru->PlaceHolder = ew_RemoveHtml($this->ts_thru->FldCaption());

		// fk_tse
		$this->fk_tse->EditAttrs["class"] = "form-control";
		$this->fk_tse->EditCustomAttributes = "";
		$this->fk_tse->EditValue = ew_HtmlEncode($this->fk_tse->CurrentValue);
		$this->fk_tse->PlaceHolder = ew_RemoveHtml($this->fk_tse->FldCaption());

		// we_id
		$this->we_id->EditAttrs["class"] = "form-control";
		$this->we_id->EditCustomAttributes = "";
		$this->we_id->EditValue = ew_HtmlEncode($this->we_id->CurrentValue);
		$this->we_id->PlaceHolder = ew_RemoveHtml($this->we_id->FldCaption());

		// te_from
		$this->te_from->EditAttrs["class"] = "form-control";
		$this->te_from->EditCustomAttributes = "";
		$this->te_from->EditValue = ew_HtmlEncode($this->te_from->CurrentValue);
		$this->te_from->PlaceHolder = ew_RemoveHtml($this->te_from->FldCaption());

		// te_thru
		$this->te_thru->EditAttrs["class"] = "form-control";
		$this->te_thru->EditCustomAttributes = "";
		$this->te_thru->EditValue = ew_HtmlEncode($this->te_thru->CurrentValue);
		$this->te_thru->PlaceHolder = ew_RemoveHtml($this->te_thru->FldCaption());

		// hours
		$this->hours->EditAttrs["class"] = "form-control";
		$this->hours->EditCustomAttributes = "";
		$this->hours->EditValue = ew_HtmlEncode($this->hours->CurrentValue);
		$this->hours->PlaceHolder = ew_RemoveHtml($this->hours->FldCaption());

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
					if ($this->tse_id->Exportable) $Doc->ExportCaption($this->tse_id);
					if ($this->ts_from->Exportable) $Doc->ExportCaption($this->ts_from);
					if ($this->ts_thru->Exportable) $Doc->ExportCaption($this->ts_thru);
					if ($this->fk_tse->Exportable) $Doc->ExportCaption($this->fk_tse);
					if ($this->we_id->Exportable) $Doc->ExportCaption($this->we_id);
					if ($this->te_from->Exportable) $Doc->ExportCaption($this->te_from);
					if ($this->te_thru->Exportable) $Doc->ExportCaption($this->te_thru);
					if ($this->hours->Exportable) $Doc->ExportCaption($this->hours);
				} else {
					if ($this->tse_id->Exportable) $Doc->ExportCaption($this->tse_id);
					if ($this->ts_from->Exportable) $Doc->ExportCaption($this->ts_from);
					if ($this->ts_thru->Exportable) $Doc->ExportCaption($this->ts_thru);
					if ($this->fk_tse->Exportable) $Doc->ExportCaption($this->fk_tse);
					if ($this->we_id->Exportable) $Doc->ExportCaption($this->we_id);
					if ($this->te_from->Exportable) $Doc->ExportCaption($this->te_from);
					if ($this->te_thru->Exportable) $Doc->ExportCaption($this->te_thru);
					if ($this->hours->Exportable) $Doc->ExportCaption($this->hours);
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
						if ($this->tse_id->Exportable) $Doc->ExportField($this->tse_id);
						if ($this->ts_from->Exportable) $Doc->ExportField($this->ts_from);
						if ($this->ts_thru->Exportable) $Doc->ExportField($this->ts_thru);
						if ($this->fk_tse->Exportable) $Doc->ExportField($this->fk_tse);
						if ($this->we_id->Exportable) $Doc->ExportField($this->we_id);
						if ($this->te_from->Exportable) $Doc->ExportField($this->te_from);
						if ($this->te_thru->Exportable) $Doc->ExportField($this->te_thru);
						if ($this->hours->Exportable) $Doc->ExportField($this->hours);
					} else {
						if ($this->tse_id->Exportable) $Doc->ExportField($this->tse_id);
						if ($this->ts_from->Exportable) $Doc->ExportField($this->ts_from);
						if ($this->ts_thru->Exportable) $Doc->ExportField($this->ts_thru);
						if ($this->fk_tse->Exportable) $Doc->ExportField($this->fk_tse);
						if ($this->we_id->Exportable) $Doc->ExportField($this->we_id);
						if ($this->te_from->Exportable) $Doc->ExportField($this->te_from);
						if ($this->te_thru->Exportable) $Doc->ExportField($this->te_thru);
						if ($this->hours->Exportable) $Doc->ExportField($this->hours);
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
