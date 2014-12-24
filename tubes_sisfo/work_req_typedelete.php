<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "work_req_typeinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$work_req_type_delete = NULL; // Initialize page object first

class cwork_req_type_delete extends cwork_req_type {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'work_req_type';

	// Page object name
	var $PageObjName = 'work_req_type_delete';

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

		// Table object (work_req_type)
		if (!isset($GLOBALS["work_req_type"]) || get_class($GLOBALS["work_req_type"]) == "cwork_req_type") {
			$GLOBALS["work_req_type"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["work_req_type"];
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
			define("EW_TABLE_NAME", 'work_req_type', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("work_req_typelist.php"));
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
		global $EW_EXPORT, $work_req_type;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($work_req_type);
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
			$this->Page_Terminate("work_req_typelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in work_req_type class, work_req_typeinfo.php

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
		$this->wrt_id->setDbValue($rs->fields('wrt_id'));
		$this->fk_work_req_type->setDbValue($rs->fields('fk_work_req_type'));
		$this->description->setDbValue($rs->fields('description'));
		$this->product->setDbValue($rs->fields('product'));
		$this->quantity->setDbValue($rs->fields('quantity'));
		$this->deliverable->setDbValue($rs->fields('deliverable'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->wrt_id->DbValue = $row['wrt_id'];
		$this->fk_work_req_type->DbValue = $row['fk_work_req_type'];
		$this->description->DbValue = $row['description'];
		$this->product->DbValue = $row['product'];
		$this->quantity->DbValue = $row['quantity'];
		$this->deliverable->DbValue = $row['deliverable'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// wrt_id
		// fk_work_req_type
		// description
		// product
		// quantity
		// deliverable

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// wrt_id
			$this->wrt_id->ViewValue = $this->wrt_id->CurrentValue;
			$this->wrt_id->ViewCustomAttributes = "";

			// fk_work_req_type
			$this->fk_work_req_type->ViewValue = $this->fk_work_req_type->CurrentValue;
			$this->fk_work_req_type->ViewCustomAttributes = "";

			// description
			$this->description->ViewValue = $this->description->CurrentValue;
			$this->description->ViewCustomAttributes = "";

			// product
			$this->product->ViewValue = $this->product->CurrentValue;
			$this->product->ViewCustomAttributes = "";

			// quantity
			$this->quantity->ViewValue = $this->quantity->CurrentValue;
			$this->quantity->ViewCustomAttributes = "";

			// deliverable
			$this->deliverable->ViewValue = $this->deliverable->CurrentValue;
			$this->deliverable->ViewCustomAttributes = "";

			// wrt_id
			$this->wrt_id->LinkCustomAttributes = "";
			$this->wrt_id->HrefValue = "";
			$this->wrt_id->TooltipValue = "";

			// fk_work_req_type
			$this->fk_work_req_type->LinkCustomAttributes = "";
			$this->fk_work_req_type->HrefValue = "";
			$this->fk_work_req_type->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// product
			$this->product->LinkCustomAttributes = "";
			$this->product->HrefValue = "";
			$this->product->TooltipValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";

			// deliverable
			$this->deliverable->LinkCustomAttributes = "";
			$this->deliverable->HrefValue = "";
			$this->deliverable->TooltipValue = "";
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
				$sThisKey .= $row['wrt_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, "work_req_typelist.php", "", $this->TableVar, TRUE);
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
if (!isset($work_req_type_delete)) $work_req_type_delete = new cwork_req_type_delete();

// Page init
$work_req_type_delete->Page_Init();

// Page main
$work_req_type_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$work_req_type_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var work_req_type_delete = new ew_Page("work_req_type_delete");
work_req_type_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = work_req_type_delete.PageID; // For backward compatibility

// Form object
var fwork_req_typedelete = new ew_Form("fwork_req_typedelete");

// Form_CustomValidate event
fwork_req_typedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwork_req_typedelete.ValidateRequired = true;
<?php } else { ?>
fwork_req_typedelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($work_req_type_delete->Recordset = $work_req_type_delete->LoadRecordset())
	$work_req_type_deleteTotalRecs = $work_req_type_delete->Recordset->RecordCount(); // Get record count
if ($work_req_type_deleteTotalRecs <= 0) { // No record found, exit
	if ($work_req_type_delete->Recordset)
		$work_req_type_delete->Recordset->Close();
	$work_req_type_delete->Page_Terminate("work_req_typelist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $work_req_type_delete->ShowPageHeader(); ?>
<?php
$work_req_type_delete->ShowMessage();
?>
<form name="fwork_req_typedelete" id="fwork_req_typedelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($work_req_type_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $work_req_type_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="work_req_type">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($work_req_type_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $work_req_type->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($work_req_type->wrt_id->Visible) { // wrt_id ?>
		<th><span id="elh_work_req_type_wrt_id" class="work_req_type_wrt_id"><?php echo $work_req_type->wrt_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($work_req_type->fk_work_req_type->Visible) { // fk_work_req_type ?>
		<th><span id="elh_work_req_type_fk_work_req_type" class="work_req_type_fk_work_req_type"><?php echo $work_req_type->fk_work_req_type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($work_req_type->description->Visible) { // description ?>
		<th><span id="elh_work_req_type_description" class="work_req_type_description"><?php echo $work_req_type->description->FldCaption() ?></span></th>
<?php } ?>
<?php if ($work_req_type->product->Visible) { // product ?>
		<th><span id="elh_work_req_type_product" class="work_req_type_product"><?php echo $work_req_type->product->FldCaption() ?></span></th>
<?php } ?>
<?php if ($work_req_type->quantity->Visible) { // quantity ?>
		<th><span id="elh_work_req_type_quantity" class="work_req_type_quantity"><?php echo $work_req_type->quantity->FldCaption() ?></span></th>
<?php } ?>
<?php if ($work_req_type->deliverable->Visible) { // deliverable ?>
		<th><span id="elh_work_req_type_deliverable" class="work_req_type_deliverable"><?php echo $work_req_type->deliverable->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$work_req_type_delete->RecCnt = 0;
$i = 0;
while (!$work_req_type_delete->Recordset->EOF) {
	$work_req_type_delete->RecCnt++;
	$work_req_type_delete->RowCnt++;

	// Set row properties
	$work_req_type->ResetAttrs();
	$work_req_type->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$work_req_type_delete->LoadRowValues($work_req_type_delete->Recordset);

	// Render row
	$work_req_type_delete->RenderRow();
?>
	<tr<?php echo $work_req_type->RowAttributes() ?>>
<?php if ($work_req_type->wrt_id->Visible) { // wrt_id ?>
		<td<?php echo $work_req_type->wrt_id->CellAttributes() ?>>
<span id="el<?php echo $work_req_type_delete->RowCnt ?>_work_req_type_wrt_id" class="form-group work_req_type_wrt_id">
<span<?php echo $work_req_type->wrt_id->ViewAttributes() ?>>
<?php echo $work_req_type->wrt_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($work_req_type->fk_work_req_type->Visible) { // fk_work_req_type ?>
		<td<?php echo $work_req_type->fk_work_req_type->CellAttributes() ?>>
<span id="el<?php echo $work_req_type_delete->RowCnt ?>_work_req_type_fk_work_req_type" class="form-group work_req_type_fk_work_req_type">
<span<?php echo $work_req_type->fk_work_req_type->ViewAttributes() ?>>
<?php echo $work_req_type->fk_work_req_type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($work_req_type->description->Visible) { // description ?>
		<td<?php echo $work_req_type->description->CellAttributes() ?>>
<span id="el<?php echo $work_req_type_delete->RowCnt ?>_work_req_type_description" class="form-group work_req_type_description">
<span<?php echo $work_req_type->description->ViewAttributes() ?>>
<?php echo $work_req_type->description->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($work_req_type->product->Visible) { // product ?>
		<td<?php echo $work_req_type->product->CellAttributes() ?>>
<span id="el<?php echo $work_req_type_delete->RowCnt ?>_work_req_type_product" class="form-group work_req_type_product">
<span<?php echo $work_req_type->product->ViewAttributes() ?>>
<?php echo $work_req_type->product->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($work_req_type->quantity->Visible) { // quantity ?>
		<td<?php echo $work_req_type->quantity->CellAttributes() ?>>
<span id="el<?php echo $work_req_type_delete->RowCnt ?>_work_req_type_quantity" class="form-group work_req_type_quantity">
<span<?php echo $work_req_type->quantity->ViewAttributes() ?>>
<?php echo $work_req_type->quantity->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($work_req_type->deliverable->Visible) { // deliverable ?>
		<td<?php echo $work_req_type->deliverable->CellAttributes() ?>>
<span id="el<?php echo $work_req_type_delete->RowCnt ?>_work_req_type_deliverable" class="form-group work_req_type_deliverable">
<span<?php echo $work_req_type->deliverable->ViewAttributes() ?>>
<?php echo $work_req_type->deliverable->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$work_req_type_delete->Recordset->MoveNext();
}
$work_req_type_delete->Recordset->Close();
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
fwork_req_typedelete.Init();
</script>
<?php
$work_req_type_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$work_req_type_delete->Page_Terminate();
?>
