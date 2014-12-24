<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "time_sheet_entryinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$time_sheet_entry_delete = NULL; // Initialize page object first

class ctime_sheet_entry_delete extends ctime_sheet_entry {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'time_sheet_entry';

	// Page object name
	var $PageObjName = 'time_sheet_entry_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (time_sheet_entry)
		if (!isset($GLOBALS["time_sheet_entry"]) || get_class($GLOBALS["time_sheet_entry"]) == "ctime_sheet_entry") {
			$GLOBALS["time_sheet_entry"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["time_sheet_entry"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// User table object (user)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'time_sheet_entry', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("time_sheet_entrylist.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $time_sheet_entry;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($time_sheet_entry);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("time_sheet_entrylist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in time_sheet_entry class, time_sheet_entryinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->tse_id->setDbValue($rs->fields('tse_id'));
		$this->ts_from->setDbValue($rs->fields('ts_from'));
		$this->ts_thru->setDbValue($rs->fields('ts_thru'));
		$this->fk_tse->setDbValue($rs->fields('fk_tse'));
		$this->we_id->setDbValue($rs->fields('we_id'));
		$this->te_from->setDbValue($rs->fields('te_from'));
		$this->te_thru->setDbValue($rs->fields('te_thru'));
		$this->hours->setDbValue($rs->fields('hours'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->tse_id->DbValue = $row['tse_id'];
		$this->ts_from->DbValue = $row['ts_from'];
		$this->ts_thru->DbValue = $row['ts_thru'];
		$this->fk_tse->DbValue = $row['fk_tse'];
		$this->we_id->DbValue = $row['we_id'];
		$this->te_from->DbValue = $row['te_from'];
		$this->te_thru->DbValue = $row['te_thru'];
		$this->hours->DbValue = $row['hours'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// tse_id
		// ts_from
		// ts_thru
		// fk_tse
		// we_id
		// te_from
		// te_thru
		// hours

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['tse_id'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "time_sheet_entrylist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($time_sheet_entry_delete)) $time_sheet_entry_delete = new ctime_sheet_entry_delete();

// Page init
$time_sheet_entry_delete->Page_Init();

// Page main
$time_sheet_entry_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$time_sheet_entry_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var time_sheet_entry_delete = new ew_Page("time_sheet_entry_delete");
time_sheet_entry_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = time_sheet_entry_delete.PageID; // For backward compatibility

// Form object
var ftime_sheet_entrydelete = new ew_Form("ftime_sheet_entrydelete");

// Form_CustomValidate event
ftime_sheet_entrydelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftime_sheet_entrydelete.ValidateRequired = true;
<?php } else { ?>
ftime_sheet_entrydelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($time_sheet_entry_delete->Recordset = $time_sheet_entry_delete->LoadRecordset())
	$time_sheet_entry_deleteTotalRecs = $time_sheet_entry_delete->Recordset->RecordCount(); // Get record count
if ($time_sheet_entry_deleteTotalRecs <= 0) { // No record found, exit
	if ($time_sheet_entry_delete->Recordset)
		$time_sheet_entry_delete->Recordset->Close();
	$time_sheet_entry_delete->Page_Terminate("time_sheet_entrylist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $time_sheet_entry_delete->ShowPageHeader(); ?>
<?php
$time_sheet_entry_delete->ShowMessage();
?>
<form name="ftime_sheet_entrydelete" id="ftime_sheet_entrydelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($time_sheet_entry_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $time_sheet_entry_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="time_sheet_entry">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($time_sheet_entry_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $time_sheet_entry->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($time_sheet_entry->tse_id->Visible) { // tse_id ?>
		<th><span id="elh_time_sheet_entry_tse_id" class="time_sheet_entry_tse_id"><?php echo $time_sheet_entry->tse_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($time_sheet_entry->ts_from->Visible) { // ts_from ?>
		<th><span id="elh_time_sheet_entry_ts_from" class="time_sheet_entry_ts_from"><?php echo $time_sheet_entry->ts_from->FldCaption() ?></span></th>
<?php } ?>
<?php if ($time_sheet_entry->ts_thru->Visible) { // ts_thru ?>
		<th><span id="elh_time_sheet_entry_ts_thru" class="time_sheet_entry_ts_thru"><?php echo $time_sheet_entry->ts_thru->FldCaption() ?></span></th>
<?php } ?>
<?php if ($time_sheet_entry->fk_tse->Visible) { // fk_tse ?>
		<th><span id="elh_time_sheet_entry_fk_tse" class="time_sheet_entry_fk_tse"><?php echo $time_sheet_entry->fk_tse->FldCaption() ?></span></th>
<?php } ?>
<?php if ($time_sheet_entry->we_id->Visible) { // we_id ?>
		<th><span id="elh_time_sheet_entry_we_id" class="time_sheet_entry_we_id"><?php echo $time_sheet_entry->we_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($time_sheet_entry->te_from->Visible) { // te_from ?>
		<th><span id="elh_time_sheet_entry_te_from" class="time_sheet_entry_te_from"><?php echo $time_sheet_entry->te_from->FldCaption() ?></span></th>
<?php } ?>
<?php if ($time_sheet_entry->te_thru->Visible) { // te_thru ?>
		<th><span id="elh_time_sheet_entry_te_thru" class="time_sheet_entry_te_thru"><?php echo $time_sheet_entry->te_thru->FldCaption() ?></span></th>
<?php } ?>
<?php if ($time_sheet_entry->hours->Visible) { // hours ?>
		<th><span id="elh_time_sheet_entry_hours" class="time_sheet_entry_hours"><?php echo $time_sheet_entry->hours->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$time_sheet_entry_delete->RecCnt = 0;
$i = 0;
while (!$time_sheet_entry_delete->Recordset->EOF) {
	$time_sheet_entry_delete->RecCnt++;
	$time_sheet_entry_delete->RowCnt++;

	// Set row properties
	$time_sheet_entry->ResetAttrs();
	$time_sheet_entry->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$time_sheet_entry_delete->LoadRowValues($time_sheet_entry_delete->Recordset);

	// Render row
	$time_sheet_entry_delete->RenderRow();
?>
	<tr<?php echo $time_sheet_entry->RowAttributes() ?>>
<?php if ($time_sheet_entry->tse_id->Visible) { // tse_id ?>
		<td<?php echo $time_sheet_entry->tse_id->CellAttributes() ?>>
<span id="el<?php echo $time_sheet_entry_delete->RowCnt ?>_time_sheet_entry_tse_id" class="form-group time_sheet_entry_tse_id">
<span<?php echo $time_sheet_entry->tse_id->ViewAttributes() ?>>
<?php echo $time_sheet_entry->tse_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($time_sheet_entry->ts_from->Visible) { // ts_from ?>
		<td<?php echo $time_sheet_entry->ts_from->CellAttributes() ?>>
<span id="el<?php echo $time_sheet_entry_delete->RowCnt ?>_time_sheet_entry_ts_from" class="form-group time_sheet_entry_ts_from">
<span<?php echo $time_sheet_entry->ts_from->ViewAttributes() ?>>
<?php echo $time_sheet_entry->ts_from->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($time_sheet_entry->ts_thru->Visible) { // ts_thru ?>
		<td<?php echo $time_sheet_entry->ts_thru->CellAttributes() ?>>
<span id="el<?php echo $time_sheet_entry_delete->RowCnt ?>_time_sheet_entry_ts_thru" class="form-group time_sheet_entry_ts_thru">
<span<?php echo $time_sheet_entry->ts_thru->ViewAttributes() ?>>
<?php echo $time_sheet_entry->ts_thru->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($time_sheet_entry->fk_tse->Visible) { // fk_tse ?>
		<td<?php echo $time_sheet_entry->fk_tse->CellAttributes() ?>>
<span id="el<?php echo $time_sheet_entry_delete->RowCnt ?>_time_sheet_entry_fk_tse" class="form-group time_sheet_entry_fk_tse">
<span<?php echo $time_sheet_entry->fk_tse->ViewAttributes() ?>>
<?php echo $time_sheet_entry->fk_tse->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($time_sheet_entry->we_id->Visible) { // we_id ?>
		<td<?php echo $time_sheet_entry->we_id->CellAttributes() ?>>
<span id="el<?php echo $time_sheet_entry_delete->RowCnt ?>_time_sheet_entry_we_id" class="form-group time_sheet_entry_we_id">
<span<?php echo $time_sheet_entry->we_id->ViewAttributes() ?>>
<?php echo $time_sheet_entry->we_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($time_sheet_entry->te_from->Visible) { // te_from ?>
		<td<?php echo $time_sheet_entry->te_from->CellAttributes() ?>>
<span id="el<?php echo $time_sheet_entry_delete->RowCnt ?>_time_sheet_entry_te_from" class="form-group time_sheet_entry_te_from">
<span<?php echo $time_sheet_entry->te_from->ViewAttributes() ?>>
<?php echo $time_sheet_entry->te_from->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($time_sheet_entry->te_thru->Visible) { // te_thru ?>
		<td<?php echo $time_sheet_entry->te_thru->CellAttributes() ?>>
<span id="el<?php echo $time_sheet_entry_delete->RowCnt ?>_time_sheet_entry_te_thru" class="form-group time_sheet_entry_te_thru">
<span<?php echo $time_sheet_entry->te_thru->ViewAttributes() ?>>
<?php echo $time_sheet_entry->te_thru->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($time_sheet_entry->hours->Visible) { // hours ?>
		<td<?php echo $time_sheet_entry->hours->CellAttributes() ?>>
<span id="el<?php echo $time_sheet_entry_delete->RowCnt ?>_time_sheet_entry_hours" class="form-group time_sheet_entry_hours">
<span<?php echo $time_sheet_entry->hours->ViewAttributes() ?>>
<?php echo $time_sheet_entry->hours->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$time_sheet_entry_delete->Recordset->MoveNext();
}
$time_sheet_entry_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftime_sheet_entrydelete.Init();
</script>
<?php
$time_sheet_entry_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$time_sheet_entry_delete->Page_Terminate();
?>
