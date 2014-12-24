<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "we_from_order_iteminfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$we_from_order_item_add = NULL; // Initialize page object first

class cwe_from_order_item_add extends cwe_from_order_item {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'we_from_order_item';

	// Page object name
	var $PageObjName = 'we_from_order_item_add';

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

		// Table object (we_from_order_item)
		if (!isset($GLOBALS["we_from_order_item"]) || get_class($GLOBALS["we_from_order_item"]) == "cwe_from_order_item") {
			$GLOBALS["we_from_order_item"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["we_from_order_item"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// User table object (user)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'we_from_order_item', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("we_from_order_itemlist.php"));
		}

		// Create form object
		$objForm = new cFormObj();
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
		global $EW_EXPORT, $we_from_order_item;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($we_from_order_item);
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
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["wfoi_id"] != "") {
				$this->wfoi_id->setQueryStringValue($_GET["wfoi_id"]);
				$this->setKey("wfoi_id", $this->wfoi_id->CurrentValue); // Set up key
			} else {
				$this->setKey("wfoi_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("we_from_order_itemlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "we_from_order_itemview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->wfoi_id->CurrentValue = NULL;
		$this->wfoi_id->OldValue = $this->wfoi_id->CurrentValue;
		$this->fk_wfoi2->CurrentValue = NULL;
		$this->fk_wfoi2->OldValue = $this->fk_wfoi2->CurrentValue;
		$this->fk_wfoi->CurrentValue = NULL;
		$this->fk_wfoi->OldValue = $this->fk_wfoi->CurrentValue;
		$this->req_item->CurrentValue = NULL;
		$this->req_item->OldValue = $this->req_item->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->wfoi_id->FldIsDetailKey) {
			$this->wfoi_id->setFormValue($objForm->GetValue("x_wfoi_id"));
		}
		if (!$this->fk_wfoi2->FldIsDetailKey) {
			$this->fk_wfoi2->setFormValue($objForm->GetValue("x_fk_wfoi2"));
		}
		if (!$this->fk_wfoi->FldIsDetailKey) {
			$this->fk_wfoi->setFormValue($objForm->GetValue("x_fk_wfoi"));
		}
		if (!$this->req_item->FldIsDetailKey) {
			$this->req_item->setFormValue($objForm->GetValue("x_req_item"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->wfoi_id->CurrentValue = $this->wfoi_id->FormValue;
		$this->fk_wfoi2->CurrentValue = $this->fk_wfoi2->FormValue;
		$this->fk_wfoi->CurrentValue = $this->fk_wfoi->FormValue;
		$this->req_item->CurrentValue = $this->req_item->FormValue;
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
		$this->wfoi_id->setDbValue($rs->fields('wfoi_id'));
		$this->fk_wfoi2->setDbValue($rs->fields('fk_wfoi2'));
		$this->fk_wfoi->setDbValue($rs->fields('fk_wfoi'));
		$this->req_item->setDbValue($rs->fields('req_item'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->wfoi_id->DbValue = $row['wfoi_id'];
		$this->fk_wfoi2->DbValue = $row['fk_wfoi2'];
		$this->fk_wfoi->DbValue = $row['fk_wfoi'];
		$this->req_item->DbValue = $row['req_item'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("wfoi_id")) <> "")
			$this->wfoi_id->CurrentValue = $this->getKey("wfoi_id"); // wfoi_id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// wfoi_id
		// fk_wfoi2
		// fk_wfoi
		// req_item

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// wfoi_id
			$this->wfoi_id->ViewValue = $this->wfoi_id->CurrentValue;
			$this->wfoi_id->ViewCustomAttributes = "";

			// fk_wfoi2
			$this->fk_wfoi2->ViewValue = $this->fk_wfoi2->CurrentValue;
			$this->fk_wfoi2->ViewCustomAttributes = "";

			// fk_wfoi
			$this->fk_wfoi->ViewValue = $this->fk_wfoi->CurrentValue;
			$this->fk_wfoi->ViewCustomAttributes = "";

			// req_item
			$this->req_item->ViewValue = $this->req_item->CurrentValue;
			$this->req_item->ViewCustomAttributes = "";

			// wfoi_id
			$this->wfoi_id->LinkCustomAttributes = "";
			$this->wfoi_id->HrefValue = "";
			$this->wfoi_id->TooltipValue = "";

			// fk_wfoi2
			$this->fk_wfoi2->LinkCustomAttributes = "";
			$this->fk_wfoi2->HrefValue = "";
			$this->fk_wfoi2->TooltipValue = "";

			// fk_wfoi
			$this->fk_wfoi->LinkCustomAttributes = "";
			$this->fk_wfoi->HrefValue = "";
			$this->fk_wfoi->TooltipValue = "";

			// req_item
			$this->req_item->LinkCustomAttributes = "";
			$this->req_item->HrefValue = "";
			$this->req_item->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// wfoi_id
			$this->wfoi_id->EditAttrs["class"] = "form-control";
			$this->wfoi_id->EditCustomAttributes = "";
			$this->wfoi_id->EditValue = ew_HtmlEncode($this->wfoi_id->CurrentValue);
			$this->wfoi_id->PlaceHolder = ew_RemoveHtml($this->wfoi_id->FldCaption());

			// fk_wfoi2
			$this->fk_wfoi2->EditAttrs["class"] = "form-control";
			$this->fk_wfoi2->EditCustomAttributes = "";
			$this->fk_wfoi2->EditValue = ew_HtmlEncode($this->fk_wfoi2->CurrentValue);
			$this->fk_wfoi2->PlaceHolder = ew_RemoveHtml($this->fk_wfoi2->FldCaption());

			// fk_wfoi
			$this->fk_wfoi->EditAttrs["class"] = "form-control";
			$this->fk_wfoi->EditCustomAttributes = "";
			$this->fk_wfoi->EditValue = ew_HtmlEncode($this->fk_wfoi->CurrentValue);
			$this->fk_wfoi->PlaceHolder = ew_RemoveHtml($this->fk_wfoi->FldCaption());

			// req_item
			$this->req_item->EditAttrs["class"] = "form-control";
			$this->req_item->EditCustomAttributes = "";
			$this->req_item->EditValue = ew_HtmlEncode($this->req_item->CurrentValue);
			$this->req_item->PlaceHolder = ew_RemoveHtml($this->req_item->FldCaption());

			// Edit refer script
			// wfoi_id

			$this->wfoi_id->HrefValue = "";

			// fk_wfoi2
			$this->fk_wfoi2->HrefValue = "";

			// fk_wfoi
			$this->fk_wfoi->HrefValue = "";

			// req_item
			$this->req_item->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->wfoi_id->FldIsDetailKey && !is_null($this->wfoi_id->FormValue) && $this->wfoi_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->wfoi_id->FldCaption(), $this->wfoi_id->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// wfoi_id
		$this->wfoi_id->SetDbValueDef($rsnew, $this->wfoi_id->CurrentValue, "", FALSE);

		// fk_wfoi2
		$this->fk_wfoi2->SetDbValueDef($rsnew, $this->fk_wfoi2->CurrentValue, NULL, FALSE);

		// fk_wfoi
		$this->fk_wfoi->SetDbValueDef($rsnew, $this->fk_wfoi->CurrentValue, NULL, FALSE);

		// req_item
		$this->req_item->SetDbValueDef($rsnew, $this->req_item->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['wfoi_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "we_from_order_itemlist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($we_from_order_item_add)) $we_from_order_item_add = new cwe_from_order_item_add();

// Page init
$we_from_order_item_add->Page_Init();

// Page main
$we_from_order_item_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$we_from_order_item_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var we_from_order_item_add = new ew_Page("we_from_order_item_add");
we_from_order_item_add.PageID = "add"; // Page ID
var EW_PAGE_ID = we_from_order_item_add.PageID; // For backward compatibility

// Form object
var fwe_from_order_itemadd = new ew_Form("fwe_from_order_itemadd");

// Validate form
fwe_from_order_itemadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_wfoi_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $we_from_order_item->wfoi_id->FldCaption(), $we_from_order_item->wfoi_id->ReqErrMsg)) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fwe_from_order_itemadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwe_from_order_itemadd.ValidateRequired = true;
<?php } else { ?>
fwe_from_order_itemadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $we_from_order_item_add->ShowPageHeader(); ?>
<?php
$we_from_order_item_add->ShowMessage();
?>
<form name="fwe_from_order_itemadd" id="fwe_from_order_itemadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($we_from_order_item_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $we_from_order_item_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="we_from_order_item">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($we_from_order_item->wfoi_id->Visible) { // wfoi_id ?>
	<div id="r_wfoi_id" class="form-group">
		<label id="elh_we_from_order_item_wfoi_id" for="x_wfoi_id" class="col-sm-2 control-label ewLabel"><?php echo $we_from_order_item->wfoi_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $we_from_order_item->wfoi_id->CellAttributes() ?>>
<span id="el_we_from_order_item_wfoi_id">
<input type="text" data-field="x_wfoi_id" name="x_wfoi_id" id="x_wfoi_id" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_from_order_item->wfoi_id->PlaceHolder) ?>" value="<?php echo $we_from_order_item->wfoi_id->EditValue ?>"<?php echo $we_from_order_item->wfoi_id->EditAttributes() ?>>
</span>
<?php echo $we_from_order_item->wfoi_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_from_order_item->fk_wfoi2->Visible) { // fk_wfoi2 ?>
	<div id="r_fk_wfoi2" class="form-group">
		<label id="elh_we_from_order_item_fk_wfoi2" for="x_fk_wfoi2" class="col-sm-2 control-label ewLabel"><?php echo $we_from_order_item->fk_wfoi2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_from_order_item->fk_wfoi2->CellAttributes() ?>>
<span id="el_we_from_order_item_fk_wfoi2">
<input type="text" data-field="x_fk_wfoi2" name="x_fk_wfoi2" id="x_fk_wfoi2" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_from_order_item->fk_wfoi2->PlaceHolder) ?>" value="<?php echo $we_from_order_item->fk_wfoi2->EditValue ?>"<?php echo $we_from_order_item->fk_wfoi2->EditAttributes() ?>>
</span>
<?php echo $we_from_order_item->fk_wfoi2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_from_order_item->fk_wfoi->Visible) { // fk_wfoi ?>
	<div id="r_fk_wfoi" class="form-group">
		<label id="elh_we_from_order_item_fk_wfoi" for="x_fk_wfoi" class="col-sm-2 control-label ewLabel"><?php echo $we_from_order_item->fk_wfoi->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_from_order_item->fk_wfoi->CellAttributes() ?>>
<span id="el_we_from_order_item_fk_wfoi">
<input type="text" data-field="x_fk_wfoi" name="x_fk_wfoi" id="x_fk_wfoi" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_from_order_item->fk_wfoi->PlaceHolder) ?>" value="<?php echo $we_from_order_item->fk_wfoi->EditValue ?>"<?php echo $we_from_order_item->fk_wfoi->EditAttributes() ?>>
</span>
<?php echo $we_from_order_item->fk_wfoi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_from_order_item->req_item->Visible) { // req_item ?>
	<div id="r_req_item" class="form-group">
		<label id="elh_we_from_order_item_req_item" for="x_req_item" class="col-sm-2 control-label ewLabel"><?php echo $we_from_order_item->req_item->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_from_order_item->req_item->CellAttributes() ?>>
<span id="el_we_from_order_item_req_item">
<input type="text" data-field="x_req_item" name="x_req_item" id="x_req_item" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_from_order_item->req_item->PlaceHolder) ?>" value="<?php echo $we_from_order_item->req_item->EditValue ?>"<?php echo $we_from_order_item->req_item->EditAttributes() ?>>
</span>
<?php echo $we_from_order_item->req_item->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fwe_from_order_itemadd.Init();
</script>
<?php
$we_from_order_item_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$we_from_order_item_add->Page_Terminate();
?>
