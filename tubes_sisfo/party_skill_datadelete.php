<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "party_skill_datainfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$party_skill_data_delete = NULL; // Initialize page object first

class cparty_skill_data_delete extends cparty_skill_data {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'party_skill_data';

	// Page object name
	var $PageObjName = 'party_skill_data_delete';

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

		// Table object (party_skill_data)
		if (!isset($GLOBALS["party_skill_data"]) || get_class($GLOBALS["party_skill_data"]) == "cparty_skill_data") {
			$GLOBALS["party_skill_data"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["party_skill_data"];
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
			define("EW_TABLE_NAME", 'party_skill_data', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("party_skill_datalist.php"));
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
		global $EW_EXPORT, $party_skill_data;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($party_skill_data);
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
			$this->Page_Terminate("party_skill_datalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in party_skill_data class, party_skill_datainfo.php

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
		$this->psd_id->setDbValue($rs->fields('psd_id'));
		$this->fk_psd->setDbValue($rs->fields('fk_psd'));
		$this->skill_type->setDbValue($rs->fields('skill_type'));
		$this->years_of_exp->setDbValue($rs->fields('years_of_exp'));
		$this->rating->setDbValue($rs->fields('rating'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->psd_id->DbValue = $row['psd_id'];
		$this->fk_psd->DbValue = $row['fk_psd'];
		$this->skill_type->DbValue = $row['skill_type'];
		$this->years_of_exp->DbValue = $row['years_of_exp'];
		$this->rating->DbValue = $row['rating'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// psd_id
		// fk_psd
		// skill_type
		// years_of_exp
		// rating

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// psd_id
			$this->psd_id->ViewValue = $this->psd_id->CurrentValue;
			$this->psd_id->ViewCustomAttributes = "";

			// fk_psd
			$this->fk_psd->ViewValue = $this->fk_psd->CurrentValue;
			$this->fk_psd->ViewCustomAttributes = "";

			// skill_type
			$this->skill_type->ViewValue = $this->skill_type->CurrentValue;
			$this->skill_type->ViewCustomAttributes = "";

			// years_of_exp
			$this->years_of_exp->ViewValue = $this->years_of_exp->CurrentValue;
			$this->years_of_exp->ViewCustomAttributes = "";

			// rating
			$this->rating->ViewValue = $this->rating->CurrentValue;
			$this->rating->ViewCustomAttributes = "";

			// psd_id
			$this->psd_id->LinkCustomAttributes = "";
			$this->psd_id->HrefValue = "";
			$this->psd_id->TooltipValue = "";

			// fk_psd
			$this->fk_psd->LinkCustomAttributes = "";
			$this->fk_psd->HrefValue = "";
			$this->fk_psd->TooltipValue = "";

			// skill_type
			$this->skill_type->LinkCustomAttributes = "";
			$this->skill_type->HrefValue = "";
			$this->skill_type->TooltipValue = "";

			// years_of_exp
			$this->years_of_exp->LinkCustomAttributes = "";
			$this->years_of_exp->HrefValue = "";
			$this->years_of_exp->TooltipValue = "";

			// rating
			$this->rating->LinkCustomAttributes = "";
			$this->rating->HrefValue = "";
			$this->rating->TooltipValue = "";
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
				$sThisKey .= $row['psd_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, "party_skill_datalist.php", "", $this->TableVar, TRUE);
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
if (!isset($party_skill_data_delete)) $party_skill_data_delete = new cparty_skill_data_delete();

// Page init
$party_skill_data_delete->Page_Init();

// Page main
$party_skill_data_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$party_skill_data_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var party_skill_data_delete = new ew_Page("party_skill_data_delete");
party_skill_data_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = party_skill_data_delete.PageID; // For backward compatibility

// Form object
var fparty_skill_datadelete = new ew_Form("fparty_skill_datadelete");

// Form_CustomValidate event
fparty_skill_datadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparty_skill_datadelete.ValidateRequired = true;
<?php } else { ?>
fparty_skill_datadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($party_skill_data_delete->Recordset = $party_skill_data_delete->LoadRecordset())
	$party_skill_data_deleteTotalRecs = $party_skill_data_delete->Recordset->RecordCount(); // Get record count
if ($party_skill_data_deleteTotalRecs <= 0) { // No record found, exit
	if ($party_skill_data_delete->Recordset)
		$party_skill_data_delete->Recordset->Close();
	$party_skill_data_delete->Page_Terminate("party_skill_datalist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $party_skill_data_delete->ShowPageHeader(); ?>
<?php
$party_skill_data_delete->ShowMessage();
?>
<form name="fparty_skill_datadelete" id="fparty_skill_datadelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($party_skill_data_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $party_skill_data_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="party_skill_data">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($party_skill_data_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $party_skill_data->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($party_skill_data->psd_id->Visible) { // psd_id ?>
		<th><span id="elh_party_skill_data_psd_id" class="party_skill_data_psd_id"><?php echo $party_skill_data->psd_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($party_skill_data->fk_psd->Visible) { // fk_psd ?>
		<th><span id="elh_party_skill_data_fk_psd" class="party_skill_data_fk_psd"><?php echo $party_skill_data->fk_psd->FldCaption() ?></span></th>
<?php } ?>
<?php if ($party_skill_data->skill_type->Visible) { // skill_type ?>
		<th><span id="elh_party_skill_data_skill_type" class="party_skill_data_skill_type"><?php echo $party_skill_data->skill_type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($party_skill_data->years_of_exp->Visible) { // years_of_exp ?>
		<th><span id="elh_party_skill_data_years_of_exp" class="party_skill_data_years_of_exp"><?php echo $party_skill_data->years_of_exp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($party_skill_data->rating->Visible) { // rating ?>
		<th><span id="elh_party_skill_data_rating" class="party_skill_data_rating"><?php echo $party_skill_data->rating->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$party_skill_data_delete->RecCnt = 0;
$i = 0;
while (!$party_skill_data_delete->Recordset->EOF) {
	$party_skill_data_delete->RecCnt++;
	$party_skill_data_delete->RowCnt++;

	// Set row properties
	$party_skill_data->ResetAttrs();
	$party_skill_data->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$party_skill_data_delete->LoadRowValues($party_skill_data_delete->Recordset);

	// Render row
	$party_skill_data_delete->RenderRow();
?>
	<tr<?php echo $party_skill_data->RowAttributes() ?>>
<?php if ($party_skill_data->psd_id->Visible) { // psd_id ?>
		<td<?php echo $party_skill_data->psd_id->CellAttributes() ?>>
<span id="el<?php echo $party_skill_data_delete->RowCnt ?>_party_skill_data_psd_id" class="form-group party_skill_data_psd_id">
<span<?php echo $party_skill_data->psd_id->ViewAttributes() ?>>
<?php echo $party_skill_data->psd_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($party_skill_data->fk_psd->Visible) { // fk_psd ?>
		<td<?php echo $party_skill_data->fk_psd->CellAttributes() ?>>
<span id="el<?php echo $party_skill_data_delete->RowCnt ?>_party_skill_data_fk_psd" class="form-group party_skill_data_fk_psd">
<span<?php echo $party_skill_data->fk_psd->ViewAttributes() ?>>
<?php echo $party_skill_data->fk_psd->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($party_skill_data->skill_type->Visible) { // skill_type ?>
		<td<?php echo $party_skill_data->skill_type->CellAttributes() ?>>
<span id="el<?php echo $party_skill_data_delete->RowCnt ?>_party_skill_data_skill_type" class="form-group party_skill_data_skill_type">
<span<?php echo $party_skill_data->skill_type->ViewAttributes() ?>>
<?php echo $party_skill_data->skill_type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($party_skill_data->years_of_exp->Visible) { // years_of_exp ?>
		<td<?php echo $party_skill_data->years_of_exp->CellAttributes() ?>>
<span id="el<?php echo $party_skill_data_delete->RowCnt ?>_party_skill_data_years_of_exp" class="form-group party_skill_data_years_of_exp">
<span<?php echo $party_skill_data->years_of_exp->ViewAttributes() ?>>
<?php echo $party_skill_data->years_of_exp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($party_skill_data->rating->Visible) { // rating ?>
		<td<?php echo $party_skill_data->rating->CellAttributes() ?>>
<span id="el<?php echo $party_skill_data_delete->RowCnt ?>_party_skill_data_rating" class="form-group party_skill_data_rating">
<span<?php echo $party_skill_data->rating->ViewAttributes() ?>>
<?php echo $party_skill_data->rating->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$party_skill_data_delete->Recordset->MoveNext();
}
$party_skill_data_delete->Recordset->Close();
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
fparty_skill_datadelete.Init();
</script>
<?php
$party_skill_data_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$party_skill_data_delete->Page_Terminate();
?>
