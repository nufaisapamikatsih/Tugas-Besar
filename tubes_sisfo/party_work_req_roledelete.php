<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "party_work_req_roleinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$party_work_req_role_delete = NULL; // Initialize page object first

class cparty_work_req_role_delete extends cparty_work_req_role {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'party_work_req_role';

	// Page object name
	var $PageObjName = 'party_work_req_role_delete';

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

		// Table object (party_work_req_role)
		if (!isset($GLOBALS["party_work_req_role"]) || get_class($GLOBALS["party_work_req_role"]) == "cparty_work_req_role") {
			$GLOBALS["party_work_req_role"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["party_work_req_role"];
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
			define("EW_TABLE_NAME", 'party_work_req_role', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("party_work_req_rolelist.php"));
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
		global $EW_EXPORT, $party_work_req_role;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($party_work_req_role);
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
			$this->Page_Terminate("party_work_req_rolelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in party_work_req_role class, party_work_req_roleinfo.php

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
		$this->pwrr_id->setDbValue($rs->fields('pwrr_id'));
		$this->fk_pwrr->setDbValue($rs->fields('fk_pwrr'));
		$this->fk_pwrr2->setDbValue($rs->fields('fk_pwrr2'));
		$this->fk_pwrr3->setDbValue($rs->fields('fk_pwrr3'));
		$this->from_date->setDbValue($rs->fields('from_date'));
		$this->thru_date->setDbValue($rs->fields('thru_date'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pwrr_id->DbValue = $row['pwrr_id'];
		$this->fk_pwrr->DbValue = $row['fk_pwrr'];
		$this->fk_pwrr2->DbValue = $row['fk_pwrr2'];
		$this->fk_pwrr3->DbValue = $row['fk_pwrr3'];
		$this->from_date->DbValue = $row['from_date'];
		$this->thru_date->DbValue = $row['thru_date'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// pwrr_id
		// fk_pwrr
		// fk_pwrr2
		// fk_pwrr3
		// from_date
		// thru_date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// pwrr_id
			$this->pwrr_id->ViewValue = $this->pwrr_id->CurrentValue;
			$this->pwrr_id->ViewCustomAttributes = "";

			// fk_pwrr
			$this->fk_pwrr->ViewValue = $this->fk_pwrr->CurrentValue;
			$this->fk_pwrr->ViewCustomAttributes = "";

			// fk_pwrr2
			$this->fk_pwrr2->ViewValue = $this->fk_pwrr2->CurrentValue;
			$this->fk_pwrr2->ViewCustomAttributes = "";

			// fk_pwrr3
			$this->fk_pwrr3->ViewValue = $this->fk_pwrr3->CurrentValue;
			$this->fk_pwrr3->ViewCustomAttributes = "";

			// from_date
			$this->from_date->ViewValue = $this->from_date->CurrentValue;
			$this->from_date->ViewCustomAttributes = "";

			// thru_date
			$this->thru_date->ViewValue = $this->thru_date->CurrentValue;
			$this->thru_date->ViewCustomAttributes = "";

			// pwrr_id
			$this->pwrr_id->LinkCustomAttributes = "";
			$this->pwrr_id->HrefValue = "";
			$this->pwrr_id->TooltipValue = "";

			// fk_pwrr
			$this->fk_pwrr->LinkCustomAttributes = "";
			$this->fk_pwrr->HrefValue = "";
			$this->fk_pwrr->TooltipValue = "";

			// fk_pwrr2
			$this->fk_pwrr2->LinkCustomAttributes = "";
			$this->fk_pwrr2->HrefValue = "";
			$this->fk_pwrr2->TooltipValue = "";

			// fk_pwrr3
			$this->fk_pwrr3->LinkCustomAttributes = "";
			$this->fk_pwrr3->HrefValue = "";
			$this->fk_pwrr3->TooltipValue = "";

			// from_date
			$this->from_date->LinkCustomAttributes = "";
			$this->from_date->HrefValue = "";
			$this->from_date->TooltipValue = "";

			// thru_date
			$this->thru_date->LinkCustomAttributes = "";
			$this->thru_date->HrefValue = "";
			$this->thru_date->TooltipValue = "";
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
				$sThisKey .= $row['pwrr_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, "party_work_req_rolelist.php", "", $this->TableVar, TRUE);
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
if (!isset($party_work_req_role_delete)) $party_work_req_role_delete = new cparty_work_req_role_delete();

// Page init
$party_work_req_role_delete->Page_Init();

// Page main
$party_work_req_role_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$party_work_req_role_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var party_work_req_role_delete = new ew_Page("party_work_req_role_delete");
party_work_req_role_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = party_work_req_role_delete.PageID; // For backward compatibility

// Form object
var fparty_work_req_roledelete = new ew_Form("fparty_work_req_roledelete");

// Form_CustomValidate event
fparty_work_req_roledelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparty_work_req_roledelete.ValidateRequired = true;
<?php } else { ?>
fparty_work_req_roledelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($party_work_req_role_delete->Recordset = $party_work_req_role_delete->LoadRecordset())
	$party_work_req_role_deleteTotalRecs = $party_work_req_role_delete->Recordset->RecordCount(); // Get record count
if ($party_work_req_role_deleteTotalRecs <= 0) { // No record found, exit
	if ($party_work_req_role_delete->Recordset)
		$party_work_req_role_delete->Recordset->Close();
	$party_work_req_role_delete->Page_Terminate("party_work_req_rolelist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $party_work_req_role_delete->ShowPageHeader(); ?>
<?php
$party_work_req_role_delete->ShowMessage();
?>
<form name="fparty_work_req_roledelete" id="fparty_work_req_roledelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($party_work_req_role_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $party_work_req_role_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="party_work_req_role">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($party_work_req_role_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $party_work_req_role->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($party_work_req_role->pwrr_id->Visible) { // pwrr_id ?>
		<th><span id="elh_party_work_req_role_pwrr_id" class="party_work_req_role_pwrr_id"><?php echo $party_work_req_role->pwrr_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($party_work_req_role->fk_pwrr->Visible) { // fk_pwrr ?>
		<th><span id="elh_party_work_req_role_fk_pwrr" class="party_work_req_role_fk_pwrr"><?php echo $party_work_req_role->fk_pwrr->FldCaption() ?></span></th>
<?php } ?>
<?php if ($party_work_req_role->fk_pwrr2->Visible) { // fk_pwrr2 ?>
		<th><span id="elh_party_work_req_role_fk_pwrr2" class="party_work_req_role_fk_pwrr2"><?php echo $party_work_req_role->fk_pwrr2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($party_work_req_role->fk_pwrr3->Visible) { // fk_pwrr3 ?>
		<th><span id="elh_party_work_req_role_fk_pwrr3" class="party_work_req_role_fk_pwrr3"><?php echo $party_work_req_role->fk_pwrr3->FldCaption() ?></span></th>
<?php } ?>
<?php if ($party_work_req_role->from_date->Visible) { // from_date ?>
		<th><span id="elh_party_work_req_role_from_date" class="party_work_req_role_from_date"><?php echo $party_work_req_role->from_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($party_work_req_role->thru_date->Visible) { // thru_date ?>
		<th><span id="elh_party_work_req_role_thru_date" class="party_work_req_role_thru_date"><?php echo $party_work_req_role->thru_date->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$party_work_req_role_delete->RecCnt = 0;
$i = 0;
while (!$party_work_req_role_delete->Recordset->EOF) {
	$party_work_req_role_delete->RecCnt++;
	$party_work_req_role_delete->RowCnt++;

	// Set row properties
	$party_work_req_role->ResetAttrs();
	$party_work_req_role->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$party_work_req_role_delete->LoadRowValues($party_work_req_role_delete->Recordset);

	// Render row
	$party_work_req_role_delete->RenderRow();
?>
	<tr<?php echo $party_work_req_role->RowAttributes() ?>>
<?php if ($party_work_req_role->pwrr_id->Visible) { // pwrr_id ?>
		<td<?php echo $party_work_req_role->pwrr_id->CellAttributes() ?>>
<span id="el<?php echo $party_work_req_role_delete->RowCnt ?>_party_work_req_role_pwrr_id" class="form-group party_work_req_role_pwrr_id">
<span<?php echo $party_work_req_role->pwrr_id->ViewAttributes() ?>>
<?php echo $party_work_req_role->pwrr_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($party_work_req_role->fk_pwrr->Visible) { // fk_pwrr ?>
		<td<?php echo $party_work_req_role->fk_pwrr->CellAttributes() ?>>
<span id="el<?php echo $party_work_req_role_delete->RowCnt ?>_party_work_req_role_fk_pwrr" class="form-group party_work_req_role_fk_pwrr">
<span<?php echo $party_work_req_role->fk_pwrr->ViewAttributes() ?>>
<?php echo $party_work_req_role->fk_pwrr->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($party_work_req_role->fk_pwrr2->Visible) { // fk_pwrr2 ?>
		<td<?php echo $party_work_req_role->fk_pwrr2->CellAttributes() ?>>
<span id="el<?php echo $party_work_req_role_delete->RowCnt ?>_party_work_req_role_fk_pwrr2" class="form-group party_work_req_role_fk_pwrr2">
<span<?php echo $party_work_req_role->fk_pwrr2->ViewAttributes() ?>>
<?php echo $party_work_req_role->fk_pwrr2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($party_work_req_role->fk_pwrr3->Visible) { // fk_pwrr3 ?>
		<td<?php echo $party_work_req_role->fk_pwrr3->CellAttributes() ?>>
<span id="el<?php echo $party_work_req_role_delete->RowCnt ?>_party_work_req_role_fk_pwrr3" class="form-group party_work_req_role_fk_pwrr3">
<span<?php echo $party_work_req_role->fk_pwrr3->ViewAttributes() ?>>
<?php echo $party_work_req_role->fk_pwrr3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($party_work_req_role->from_date->Visible) { // from_date ?>
		<td<?php echo $party_work_req_role->from_date->CellAttributes() ?>>
<span id="el<?php echo $party_work_req_role_delete->RowCnt ?>_party_work_req_role_from_date" class="form-group party_work_req_role_from_date">
<span<?php echo $party_work_req_role->from_date->ViewAttributes() ?>>
<?php echo $party_work_req_role->from_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($party_work_req_role->thru_date->Visible) { // thru_date ?>
		<td<?php echo $party_work_req_role->thru_date->CellAttributes() ?>>
<span id="el<?php echo $party_work_req_role_delete->RowCnt ?>_party_work_req_role_thru_date" class="form-group party_work_req_role_thru_date">
<span<?php echo $party_work_req_role->thru_date->ViewAttributes() ?>>
<?php echo $party_work_req_role->thru_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$party_work_req_role_delete->Recordset->MoveNext();
}
$party_work_req_role_delete->Recordset->Close();
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
fparty_work_req_roledelete.Init();
</script>
<?php
$party_work_req_role_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$party_work_req_role_delete->Page_Terminate();
?>
