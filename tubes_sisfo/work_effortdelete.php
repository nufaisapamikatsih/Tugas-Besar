<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "work_effortinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$work_effort_delete = NULL; // Initialize page object first

class cwork_effort_delete extends cwork_effort {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'work_effort';

	// Page object name
	var $PageObjName = 'work_effort_delete';

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

		// Table object (work_effort)
		if (!isset($GLOBALS["work_effort"]) || get_class($GLOBALS["work_effort"]) == "cwork_effort") {
			$GLOBALS["work_effort"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["work_effort"];
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
			define("EW_TABLE_NAME", 'work_effort', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("work_effortlist.php"));
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
		global $EW_EXPORT, $work_effort;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($work_effort);
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
			$this->Page_Terminate("work_effortlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in work_effort class, work_effortinfo.php

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
		$this->we_id->setDbValue($rs->fields('we_id'));
		$this->fk_we->setDbValue($rs->fields('fk_we'));
		$this->name->setDbValue($rs->fields('name'));
		$this->des->setDbValue($rs->fields('des'));
		$this->start_date->setDbValue($rs->fields('start_date'));
		$this->completion_date->setDbValue($rs->fields('completion_date'));
		$this->estimated_hours->setDbValue($rs->fields('estimated_hours'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->we_id->DbValue = $row['we_id'];
		$this->fk_we->DbValue = $row['fk_we'];
		$this->name->DbValue = $row['name'];
		$this->des->DbValue = $row['des'];
		$this->start_date->DbValue = $row['start_date'];
		$this->completion_date->DbValue = $row['completion_date'];
		$this->estimated_hours->DbValue = $row['estimated_hours'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// we_id
		// fk_we
		// name
		// des
		// start_date
		// completion_date
		// estimated_hours

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// we_id
			$this->we_id->ViewValue = $this->we_id->CurrentValue;
			$this->we_id->ViewCustomAttributes = "";

			// fk_we
			$this->fk_we->ViewValue = $this->fk_we->CurrentValue;
			$this->fk_we->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// des
			$this->des->ViewValue = $this->des->CurrentValue;
			$this->des->ViewCustomAttributes = "";

			// start_date
			$this->start_date->ViewValue = $this->start_date->CurrentValue;
			$this->start_date->ViewCustomAttributes = "";

			// completion_date
			$this->completion_date->ViewValue = $this->completion_date->CurrentValue;
			$this->completion_date->ViewCustomAttributes = "";

			// estimated_hours
			$this->estimated_hours->ViewValue = $this->estimated_hours->CurrentValue;
			$this->estimated_hours->ViewCustomAttributes = "";

			// we_id
			$this->we_id->LinkCustomAttributes = "";
			$this->we_id->HrefValue = "";
			$this->we_id->TooltipValue = "";

			// fk_we
			$this->fk_we->LinkCustomAttributes = "";
			$this->fk_we->HrefValue = "";
			$this->fk_we->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// des
			$this->des->LinkCustomAttributes = "";
			$this->des->HrefValue = "";
			$this->des->TooltipValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
			$this->start_date->TooltipValue = "";

			// completion_date
			$this->completion_date->LinkCustomAttributes = "";
			$this->completion_date->HrefValue = "";
			$this->completion_date->TooltipValue = "";

			// estimated_hours
			$this->estimated_hours->LinkCustomAttributes = "";
			$this->estimated_hours->HrefValue = "";
			$this->estimated_hours->TooltipValue = "";
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
				$sThisKey .= $row['we_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, "work_effortlist.php", "", $this->TableVar, TRUE);
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
if (!isset($work_effort_delete)) $work_effort_delete = new cwork_effort_delete();

// Page init
$work_effort_delete->Page_Init();

// Page main
$work_effort_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$work_effort_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var work_effort_delete = new ew_Page("work_effort_delete");
work_effort_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = work_effort_delete.PageID; // For backward compatibility

// Form object
var fwork_effortdelete = new ew_Form("fwork_effortdelete");

// Form_CustomValidate event
fwork_effortdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwork_effortdelete.ValidateRequired = true;
<?php } else { ?>
fwork_effortdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($work_effort_delete->Recordset = $work_effort_delete->LoadRecordset())
	$work_effort_deleteTotalRecs = $work_effort_delete->Recordset->RecordCount(); // Get record count
if ($work_effort_deleteTotalRecs <= 0) { // No record found, exit
	if ($work_effort_delete->Recordset)
		$work_effort_delete->Recordset->Close();
	$work_effort_delete->Page_Terminate("work_effortlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $work_effort_delete->ShowPageHeader(); ?>
<?php
$work_effort_delete->ShowMessage();
?>
<form name="fwork_effortdelete" id="fwork_effortdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($work_effort_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $work_effort_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="work_effort">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($work_effort_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $work_effort->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($work_effort->we_id->Visible) { // we_id ?>
		<th><span id="elh_work_effort_we_id" class="work_effort_we_id"><?php echo $work_effort->we_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($work_effort->fk_we->Visible) { // fk_we ?>
		<th><span id="elh_work_effort_fk_we" class="work_effort_fk_we"><?php echo $work_effort->fk_we->FldCaption() ?></span></th>
<?php } ?>
<?php if ($work_effort->name->Visible) { // name ?>
		<th><span id="elh_work_effort_name" class="work_effort_name"><?php echo $work_effort->name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($work_effort->des->Visible) { // des ?>
		<th><span id="elh_work_effort_des" class="work_effort_des"><?php echo $work_effort->des->FldCaption() ?></span></th>
<?php } ?>
<?php if ($work_effort->start_date->Visible) { // start_date ?>
		<th><span id="elh_work_effort_start_date" class="work_effort_start_date"><?php echo $work_effort->start_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($work_effort->completion_date->Visible) { // completion_date ?>
		<th><span id="elh_work_effort_completion_date" class="work_effort_completion_date"><?php echo $work_effort->completion_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($work_effort->estimated_hours->Visible) { // estimated_hours ?>
		<th><span id="elh_work_effort_estimated_hours" class="work_effort_estimated_hours"><?php echo $work_effort->estimated_hours->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$work_effort_delete->RecCnt = 0;
$i = 0;
while (!$work_effort_delete->Recordset->EOF) {
	$work_effort_delete->RecCnt++;
	$work_effort_delete->RowCnt++;

	// Set row properties
	$work_effort->ResetAttrs();
	$work_effort->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$work_effort_delete->LoadRowValues($work_effort_delete->Recordset);

	// Render row
	$work_effort_delete->RenderRow();
?>
	<tr<?php echo $work_effort->RowAttributes() ?>>
<?php if ($work_effort->we_id->Visible) { // we_id ?>
		<td<?php echo $work_effort->we_id->CellAttributes() ?>>
<span id="el<?php echo $work_effort_delete->RowCnt ?>_work_effort_we_id" class="form-group work_effort_we_id">
<span<?php echo $work_effort->we_id->ViewAttributes() ?>>
<?php echo $work_effort->we_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($work_effort->fk_we->Visible) { // fk_we ?>
		<td<?php echo $work_effort->fk_we->CellAttributes() ?>>
<span id="el<?php echo $work_effort_delete->RowCnt ?>_work_effort_fk_we" class="form-group work_effort_fk_we">
<span<?php echo $work_effort->fk_we->ViewAttributes() ?>>
<?php echo $work_effort->fk_we->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($work_effort->name->Visible) { // name ?>
		<td<?php echo $work_effort->name->CellAttributes() ?>>
<span id="el<?php echo $work_effort_delete->RowCnt ?>_work_effort_name" class="form-group work_effort_name">
<span<?php echo $work_effort->name->ViewAttributes() ?>>
<?php echo $work_effort->name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($work_effort->des->Visible) { // des ?>
		<td<?php echo $work_effort->des->CellAttributes() ?>>
<span id="el<?php echo $work_effort_delete->RowCnt ?>_work_effort_des" class="form-group work_effort_des">
<span<?php echo $work_effort->des->ViewAttributes() ?>>
<?php echo $work_effort->des->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($work_effort->start_date->Visible) { // start_date ?>
		<td<?php echo $work_effort->start_date->CellAttributes() ?>>
<span id="el<?php echo $work_effort_delete->RowCnt ?>_work_effort_start_date" class="form-group work_effort_start_date">
<span<?php echo $work_effort->start_date->ViewAttributes() ?>>
<?php echo $work_effort->start_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($work_effort->completion_date->Visible) { // completion_date ?>
		<td<?php echo $work_effort->completion_date->CellAttributes() ?>>
<span id="el<?php echo $work_effort_delete->RowCnt ?>_work_effort_completion_date" class="form-group work_effort_completion_date">
<span<?php echo $work_effort->completion_date->ViewAttributes() ?>>
<?php echo $work_effort->completion_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($work_effort->estimated_hours->Visible) { // estimated_hours ?>
		<td<?php echo $work_effort->estimated_hours->CellAttributes() ?>>
<span id="el<?php echo $work_effort_delete->RowCnt ?>_work_effort_estimated_hours" class="form-group work_effort_estimated_hours">
<span<?php echo $work_effort->estimated_hours->ViewAttributes() ?>>
<?php echo $work_effort->estimated_hours->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$work_effort_delete->Recordset->MoveNext();
}
$work_effort_delete->Recordset->Close();
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
fwork_effortdelete.Init();
</script>
<?php
$work_effort_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$work_effort_delete->Page_Terminate();
?>
