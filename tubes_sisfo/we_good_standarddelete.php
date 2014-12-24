<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "we_good_standardinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$we_good_standard_delete = NULL; // Initialize page object first

class cwe_good_standard_delete extends cwe_good_standard {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'we_good_standard';

	// Page object name
	var $PageObjName = 'we_good_standard_delete';

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

		// Table object (we_good_standard)
		if (!isset($GLOBALS["we_good_standard"]) || get_class($GLOBALS["we_good_standard"]) == "cwe_good_standard") {
			$GLOBALS["we_good_standard"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["we_good_standard"];
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
			define("EW_TABLE_NAME", 'we_good_standard', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("we_good_standardlist.php"));
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
		global $EW_EXPORT, $we_good_standard;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($we_good_standard);
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
			$this->Page_Terminate("we_good_standardlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in we_good_standard class, we_good_standardinfo.php

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
		$this->wegs_id->setDbValue($rs->fields('wegs_id'));
		$this->fk_wegs->setDbValue($rs->fields('fk_wegs'));
		$this->item->setDbValue($rs->fields('item'));
		$this->est_quantity->setDbValue($rs->fields('est_quantity'));
		$this->est_cost->setDbValue($rs->fields('est_cost'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->wegs_id->DbValue = $row['wegs_id'];
		$this->fk_wegs->DbValue = $row['fk_wegs'];
		$this->item->DbValue = $row['item'];
		$this->est_quantity->DbValue = $row['est_quantity'];
		$this->est_cost->DbValue = $row['est_cost'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// wegs_id
		// fk_wegs
		// item
		// est_quantity
		// est_cost

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// wegs_id
			$this->wegs_id->ViewValue = $this->wegs_id->CurrentValue;
			$this->wegs_id->ViewCustomAttributes = "";

			// fk_wegs
			$this->fk_wegs->ViewValue = $this->fk_wegs->CurrentValue;
			$this->fk_wegs->ViewCustomAttributes = "";

			// item
			$this->item->ViewValue = $this->item->CurrentValue;
			$this->item->ViewCustomAttributes = "";

			// est_quantity
			$this->est_quantity->ViewValue = $this->est_quantity->CurrentValue;
			$this->est_quantity->ViewCustomAttributes = "";

			// est_cost
			$this->est_cost->ViewValue = $this->est_cost->CurrentValue;
			$this->est_cost->ViewCustomAttributes = "";

			// wegs_id
			$this->wegs_id->LinkCustomAttributes = "";
			$this->wegs_id->HrefValue = "";
			$this->wegs_id->TooltipValue = "";

			// fk_wegs
			$this->fk_wegs->LinkCustomAttributes = "";
			$this->fk_wegs->HrefValue = "";
			$this->fk_wegs->TooltipValue = "";

			// item
			$this->item->LinkCustomAttributes = "";
			$this->item->HrefValue = "";
			$this->item->TooltipValue = "";

			// est_quantity
			$this->est_quantity->LinkCustomAttributes = "";
			$this->est_quantity->HrefValue = "";
			$this->est_quantity->TooltipValue = "";

			// est_cost
			$this->est_cost->LinkCustomAttributes = "";
			$this->est_cost->HrefValue = "";
			$this->est_cost->TooltipValue = "";
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
				$sThisKey .= $row['wegs_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, "we_good_standardlist.php", "", $this->TableVar, TRUE);
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
if (!isset($we_good_standard_delete)) $we_good_standard_delete = new cwe_good_standard_delete();

// Page init
$we_good_standard_delete->Page_Init();

// Page main
$we_good_standard_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$we_good_standard_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var we_good_standard_delete = new ew_Page("we_good_standard_delete");
we_good_standard_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = we_good_standard_delete.PageID; // For backward compatibility

// Form object
var fwe_good_standarddelete = new ew_Form("fwe_good_standarddelete");

// Form_CustomValidate event
fwe_good_standarddelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwe_good_standarddelete.ValidateRequired = true;
<?php } else { ?>
fwe_good_standarddelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($we_good_standard_delete->Recordset = $we_good_standard_delete->LoadRecordset())
	$we_good_standard_deleteTotalRecs = $we_good_standard_delete->Recordset->RecordCount(); // Get record count
if ($we_good_standard_deleteTotalRecs <= 0) { // No record found, exit
	if ($we_good_standard_delete->Recordset)
		$we_good_standard_delete->Recordset->Close();
	$we_good_standard_delete->Page_Terminate("we_good_standardlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $we_good_standard_delete->ShowPageHeader(); ?>
<?php
$we_good_standard_delete->ShowMessage();
?>
<form name="fwe_good_standarddelete" id="fwe_good_standarddelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($we_good_standard_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $we_good_standard_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="we_good_standard">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($we_good_standard_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $we_good_standard->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($we_good_standard->wegs_id->Visible) { // wegs_id ?>
		<th><span id="elh_we_good_standard_wegs_id" class="we_good_standard_wegs_id"><?php echo $we_good_standard->wegs_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($we_good_standard->fk_wegs->Visible) { // fk_wegs ?>
		<th><span id="elh_we_good_standard_fk_wegs" class="we_good_standard_fk_wegs"><?php echo $we_good_standard->fk_wegs->FldCaption() ?></span></th>
<?php } ?>
<?php if ($we_good_standard->item->Visible) { // item ?>
		<th><span id="elh_we_good_standard_item" class="we_good_standard_item"><?php echo $we_good_standard->item->FldCaption() ?></span></th>
<?php } ?>
<?php if ($we_good_standard->est_quantity->Visible) { // est_quantity ?>
		<th><span id="elh_we_good_standard_est_quantity" class="we_good_standard_est_quantity"><?php echo $we_good_standard->est_quantity->FldCaption() ?></span></th>
<?php } ?>
<?php if ($we_good_standard->est_cost->Visible) { // est_cost ?>
		<th><span id="elh_we_good_standard_est_cost" class="we_good_standard_est_cost"><?php echo $we_good_standard->est_cost->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$we_good_standard_delete->RecCnt = 0;
$i = 0;
while (!$we_good_standard_delete->Recordset->EOF) {
	$we_good_standard_delete->RecCnt++;
	$we_good_standard_delete->RowCnt++;

	// Set row properties
	$we_good_standard->ResetAttrs();
	$we_good_standard->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$we_good_standard_delete->LoadRowValues($we_good_standard_delete->Recordset);

	// Render row
	$we_good_standard_delete->RenderRow();
?>
	<tr<?php echo $we_good_standard->RowAttributes() ?>>
<?php if ($we_good_standard->wegs_id->Visible) { // wegs_id ?>
		<td<?php echo $we_good_standard->wegs_id->CellAttributes() ?>>
<span id="el<?php echo $we_good_standard_delete->RowCnt ?>_we_good_standard_wegs_id" class="form-group we_good_standard_wegs_id">
<span<?php echo $we_good_standard->wegs_id->ViewAttributes() ?>>
<?php echo $we_good_standard->wegs_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($we_good_standard->fk_wegs->Visible) { // fk_wegs ?>
		<td<?php echo $we_good_standard->fk_wegs->CellAttributes() ?>>
<span id="el<?php echo $we_good_standard_delete->RowCnt ?>_we_good_standard_fk_wegs" class="form-group we_good_standard_fk_wegs">
<span<?php echo $we_good_standard->fk_wegs->ViewAttributes() ?>>
<?php echo $we_good_standard->fk_wegs->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($we_good_standard->item->Visible) { // item ?>
		<td<?php echo $we_good_standard->item->CellAttributes() ?>>
<span id="el<?php echo $we_good_standard_delete->RowCnt ?>_we_good_standard_item" class="form-group we_good_standard_item">
<span<?php echo $we_good_standard->item->ViewAttributes() ?>>
<?php echo $we_good_standard->item->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($we_good_standard->est_quantity->Visible) { // est_quantity ?>
		<td<?php echo $we_good_standard->est_quantity->CellAttributes() ?>>
<span id="el<?php echo $we_good_standard_delete->RowCnt ?>_we_good_standard_est_quantity" class="form-group we_good_standard_est_quantity">
<span<?php echo $we_good_standard->est_quantity->ViewAttributes() ?>>
<?php echo $we_good_standard->est_quantity->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($we_good_standard->est_cost->Visible) { // est_cost ?>
		<td<?php echo $we_good_standard->est_cost->CellAttributes() ?>>
<span id="el<?php echo $we_good_standard_delete->RowCnt ?>_we_good_standard_est_cost" class="form-group we_good_standard_est_cost">
<span<?php echo $we_good_standard->est_cost->ViewAttributes() ?>>
<?php echo $we_good_standard->est_cost->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$we_good_standard_delete->Recordset->MoveNext();
}
$we_good_standard_delete->Recordset->Close();
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
fwe_good_standarddelete.Init();
</script>
<?php
$we_good_standard_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$we_good_standard_delete->Page_Terminate();
?>
