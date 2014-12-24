<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "we_party_assignment_datainfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$we_party_assignment_data_delete = NULL; // Initialize page object first

class cwe_party_assignment_data_delete extends cwe_party_assignment_data {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'we_party_assignment_data';

	// Page object name
	var $PageObjName = 'we_party_assignment_data_delete';

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

		// Table object (we_party_assignment_data)
		if (!isset($GLOBALS["we_party_assignment_data"]) || get_class($GLOBALS["we_party_assignment_data"]) == "cwe_party_assignment_data") {
			$GLOBALS["we_party_assignment_data"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["we_party_assignment_data"];
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
			define("EW_TABLE_NAME", 'we_party_assignment_data', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("we_party_assignment_datalist.php"));
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
		global $EW_EXPORT, $we_party_assignment_data;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($we_party_assignment_data);
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
			$this->Page_Terminate("we_party_assignment_datalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in we_party_assignment_data class, we_party_assignment_datainfo.php

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
		$this->wepad_id->setDbValue($rs->fields('wepad_id'));
		$this->fk_wepad->setDbValue($rs->fields('fk_wepad'));
		$this->fk_wepad2->setDbValue($rs->fields('fk_wepad2'));
		$this->we_role_type->setDbValue($rs->fields('we_role_type'));
		$this->from_date->setDbValue($rs->fields('from_date'));
		$this->thru_date->setDbValue($rs->fields('thru_date'));
		$this->com->setDbValue($rs->fields('com'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->wepad_id->DbValue = $row['wepad_id'];
		$this->fk_wepad->DbValue = $row['fk_wepad'];
		$this->fk_wepad2->DbValue = $row['fk_wepad2'];
		$this->we_role_type->DbValue = $row['we_role_type'];
		$this->from_date->DbValue = $row['from_date'];
		$this->thru_date->DbValue = $row['thru_date'];
		$this->com->DbValue = $row['com'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// wepad_id
		// fk_wepad
		// fk_wepad2
		// we_role_type
		// from_date
		// thru_date
		// com

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// wepad_id
			$this->wepad_id->ViewValue = $this->wepad_id->CurrentValue;
			$this->wepad_id->ViewCustomAttributes = "";

			// fk_wepad
			$this->fk_wepad->ViewValue = $this->fk_wepad->CurrentValue;
			$this->fk_wepad->ViewCustomAttributes = "";

			// fk_wepad2
			$this->fk_wepad2->ViewValue = $this->fk_wepad2->CurrentValue;
			$this->fk_wepad2->ViewCustomAttributes = "";

			// we_role_type
			$this->we_role_type->ViewValue = $this->we_role_type->CurrentValue;
			$this->we_role_type->ViewCustomAttributes = "";

			// from_date
			$this->from_date->ViewValue = $this->from_date->CurrentValue;
			$this->from_date->ViewCustomAttributes = "";

			// thru_date
			$this->thru_date->ViewValue = $this->thru_date->CurrentValue;
			$this->thru_date->ViewCustomAttributes = "";

			// com
			$this->com->ViewValue = $this->com->CurrentValue;
			$this->com->ViewCustomAttributes = "";

			// wepad_id
			$this->wepad_id->LinkCustomAttributes = "";
			$this->wepad_id->HrefValue = "";
			$this->wepad_id->TooltipValue = "";

			// fk_wepad
			$this->fk_wepad->LinkCustomAttributes = "";
			$this->fk_wepad->HrefValue = "";
			$this->fk_wepad->TooltipValue = "";

			// fk_wepad2
			$this->fk_wepad2->LinkCustomAttributes = "";
			$this->fk_wepad2->HrefValue = "";
			$this->fk_wepad2->TooltipValue = "";

			// we_role_type
			$this->we_role_type->LinkCustomAttributes = "";
			$this->we_role_type->HrefValue = "";
			$this->we_role_type->TooltipValue = "";

			// from_date
			$this->from_date->LinkCustomAttributes = "";
			$this->from_date->HrefValue = "";
			$this->from_date->TooltipValue = "";

			// thru_date
			$this->thru_date->LinkCustomAttributes = "";
			$this->thru_date->HrefValue = "";
			$this->thru_date->TooltipValue = "";

			// com
			$this->com->LinkCustomAttributes = "";
			$this->com->HrefValue = "";
			$this->com->TooltipValue = "";
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
				$sThisKey .= $row['wepad_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, "we_party_assignment_datalist.php", "", $this->TableVar, TRUE);
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
if (!isset($we_party_assignment_data_delete)) $we_party_assignment_data_delete = new cwe_party_assignment_data_delete();

// Page init
$we_party_assignment_data_delete->Page_Init();

// Page main
$we_party_assignment_data_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$we_party_assignment_data_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var we_party_assignment_data_delete = new ew_Page("we_party_assignment_data_delete");
we_party_assignment_data_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = we_party_assignment_data_delete.PageID; // For backward compatibility

// Form object
var fwe_party_assignment_datadelete = new ew_Form("fwe_party_assignment_datadelete");

// Form_CustomValidate event
fwe_party_assignment_datadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwe_party_assignment_datadelete.ValidateRequired = true;
<?php } else { ?>
fwe_party_assignment_datadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($we_party_assignment_data_delete->Recordset = $we_party_assignment_data_delete->LoadRecordset())
	$we_party_assignment_data_deleteTotalRecs = $we_party_assignment_data_delete->Recordset->RecordCount(); // Get record count
if ($we_party_assignment_data_deleteTotalRecs <= 0) { // No record found, exit
	if ($we_party_assignment_data_delete->Recordset)
		$we_party_assignment_data_delete->Recordset->Close();
	$we_party_assignment_data_delete->Page_Terminate("we_party_assignment_datalist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $we_party_assignment_data_delete->ShowPageHeader(); ?>
<?php
$we_party_assignment_data_delete->ShowMessage();
?>
<form name="fwe_party_assignment_datadelete" id="fwe_party_assignment_datadelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($we_party_assignment_data_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $we_party_assignment_data_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="we_party_assignment_data">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($we_party_assignment_data_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $we_party_assignment_data->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($we_party_assignment_data->wepad_id->Visible) { // wepad_id ?>
		<th><span id="elh_we_party_assignment_data_wepad_id" class="we_party_assignment_data_wepad_id"><?php echo $we_party_assignment_data->wepad_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($we_party_assignment_data->fk_wepad->Visible) { // fk_wepad ?>
		<th><span id="elh_we_party_assignment_data_fk_wepad" class="we_party_assignment_data_fk_wepad"><?php echo $we_party_assignment_data->fk_wepad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($we_party_assignment_data->fk_wepad2->Visible) { // fk_wepad2 ?>
		<th><span id="elh_we_party_assignment_data_fk_wepad2" class="we_party_assignment_data_fk_wepad2"><?php echo $we_party_assignment_data->fk_wepad2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($we_party_assignment_data->we_role_type->Visible) { // we_role_type ?>
		<th><span id="elh_we_party_assignment_data_we_role_type" class="we_party_assignment_data_we_role_type"><?php echo $we_party_assignment_data->we_role_type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($we_party_assignment_data->from_date->Visible) { // from_date ?>
		<th><span id="elh_we_party_assignment_data_from_date" class="we_party_assignment_data_from_date"><?php echo $we_party_assignment_data->from_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($we_party_assignment_data->thru_date->Visible) { // thru_date ?>
		<th><span id="elh_we_party_assignment_data_thru_date" class="we_party_assignment_data_thru_date"><?php echo $we_party_assignment_data->thru_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($we_party_assignment_data->com->Visible) { // com ?>
		<th><span id="elh_we_party_assignment_data_com" class="we_party_assignment_data_com"><?php echo $we_party_assignment_data->com->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$we_party_assignment_data_delete->RecCnt = 0;
$i = 0;
while (!$we_party_assignment_data_delete->Recordset->EOF) {
	$we_party_assignment_data_delete->RecCnt++;
	$we_party_assignment_data_delete->RowCnt++;

	// Set row properties
	$we_party_assignment_data->ResetAttrs();
	$we_party_assignment_data->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$we_party_assignment_data_delete->LoadRowValues($we_party_assignment_data_delete->Recordset);

	// Render row
	$we_party_assignment_data_delete->RenderRow();
?>
	<tr<?php echo $we_party_assignment_data->RowAttributes() ?>>
<?php if ($we_party_assignment_data->wepad_id->Visible) { // wepad_id ?>
		<td<?php echo $we_party_assignment_data->wepad_id->CellAttributes() ?>>
<span id="el<?php echo $we_party_assignment_data_delete->RowCnt ?>_we_party_assignment_data_wepad_id" class="form-group we_party_assignment_data_wepad_id">
<span<?php echo $we_party_assignment_data->wepad_id->ViewAttributes() ?>>
<?php echo $we_party_assignment_data->wepad_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($we_party_assignment_data->fk_wepad->Visible) { // fk_wepad ?>
		<td<?php echo $we_party_assignment_data->fk_wepad->CellAttributes() ?>>
<span id="el<?php echo $we_party_assignment_data_delete->RowCnt ?>_we_party_assignment_data_fk_wepad" class="form-group we_party_assignment_data_fk_wepad">
<span<?php echo $we_party_assignment_data->fk_wepad->ViewAttributes() ?>>
<?php echo $we_party_assignment_data->fk_wepad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($we_party_assignment_data->fk_wepad2->Visible) { // fk_wepad2 ?>
		<td<?php echo $we_party_assignment_data->fk_wepad2->CellAttributes() ?>>
<span id="el<?php echo $we_party_assignment_data_delete->RowCnt ?>_we_party_assignment_data_fk_wepad2" class="form-group we_party_assignment_data_fk_wepad2">
<span<?php echo $we_party_assignment_data->fk_wepad2->ViewAttributes() ?>>
<?php echo $we_party_assignment_data->fk_wepad2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($we_party_assignment_data->we_role_type->Visible) { // we_role_type ?>
		<td<?php echo $we_party_assignment_data->we_role_type->CellAttributes() ?>>
<span id="el<?php echo $we_party_assignment_data_delete->RowCnt ?>_we_party_assignment_data_we_role_type" class="form-group we_party_assignment_data_we_role_type">
<span<?php echo $we_party_assignment_data->we_role_type->ViewAttributes() ?>>
<?php echo $we_party_assignment_data->we_role_type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($we_party_assignment_data->from_date->Visible) { // from_date ?>
		<td<?php echo $we_party_assignment_data->from_date->CellAttributes() ?>>
<span id="el<?php echo $we_party_assignment_data_delete->RowCnt ?>_we_party_assignment_data_from_date" class="form-group we_party_assignment_data_from_date">
<span<?php echo $we_party_assignment_data->from_date->ViewAttributes() ?>>
<?php echo $we_party_assignment_data->from_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($we_party_assignment_data->thru_date->Visible) { // thru_date ?>
		<td<?php echo $we_party_assignment_data->thru_date->CellAttributes() ?>>
<span id="el<?php echo $we_party_assignment_data_delete->RowCnt ?>_we_party_assignment_data_thru_date" class="form-group we_party_assignment_data_thru_date">
<span<?php echo $we_party_assignment_data->thru_date->ViewAttributes() ?>>
<?php echo $we_party_assignment_data->thru_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($we_party_assignment_data->com->Visible) { // com ?>
		<td<?php echo $we_party_assignment_data->com->CellAttributes() ?>>
<span id="el<?php echo $we_party_assignment_data_delete->RowCnt ?>_we_party_assignment_data_com" class="form-group we_party_assignment_data_com">
<span<?php echo $we_party_assignment_data->com->ViewAttributes() ?>>
<?php echo $we_party_assignment_data->com->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$we_party_assignment_data_delete->Recordset->MoveNext();
}
$we_party_assignment_data_delete->Recordset->Close();
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
fwe_party_assignment_datadelete.Init();
</script>
<?php
$we_party_assignment_data_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$we_party_assignment_data_delete->Page_Terminate();
?>
