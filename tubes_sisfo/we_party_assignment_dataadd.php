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

$we_party_assignment_data_add = NULL; // Initialize page object first

class cwe_party_assignment_data_add extends cwe_party_assignment_data {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'we_party_assignment_data';

	// Page object name
	var $PageObjName = 'we_party_assignment_data_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("we_party_assignment_datalist.php"));
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
			if (@$_GET["wepad_id"] != "") {
				$this->wepad_id->setQueryStringValue($_GET["wepad_id"]);
				$this->setKey("wepad_id", $this->wepad_id->CurrentValue); // Set up key
			} else {
				$this->setKey("wepad_id", ""); // Clear key
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
					$this->Page_Terminate("we_party_assignment_datalist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "we_party_assignment_dataview.php")
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
		$this->wepad_id->CurrentValue = NULL;
		$this->wepad_id->OldValue = $this->wepad_id->CurrentValue;
		$this->fk_wepad->CurrentValue = NULL;
		$this->fk_wepad->OldValue = $this->fk_wepad->CurrentValue;
		$this->fk_wepad2->CurrentValue = NULL;
		$this->fk_wepad2->OldValue = $this->fk_wepad2->CurrentValue;
		$this->we_role_type->CurrentValue = NULL;
		$this->we_role_type->OldValue = $this->we_role_type->CurrentValue;
		$this->from_date->CurrentValue = NULL;
		$this->from_date->OldValue = $this->from_date->CurrentValue;
		$this->thru_date->CurrentValue = NULL;
		$this->thru_date->OldValue = $this->thru_date->CurrentValue;
		$this->com->CurrentValue = NULL;
		$this->com->OldValue = $this->com->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->wepad_id->FldIsDetailKey) {
			$this->wepad_id->setFormValue($objForm->GetValue("x_wepad_id"));
		}
		if (!$this->fk_wepad->FldIsDetailKey) {
			$this->fk_wepad->setFormValue($objForm->GetValue("x_fk_wepad"));
		}
		if (!$this->fk_wepad2->FldIsDetailKey) {
			$this->fk_wepad2->setFormValue($objForm->GetValue("x_fk_wepad2"));
		}
		if (!$this->we_role_type->FldIsDetailKey) {
			$this->we_role_type->setFormValue($objForm->GetValue("x_we_role_type"));
		}
		if (!$this->from_date->FldIsDetailKey) {
			$this->from_date->setFormValue($objForm->GetValue("x_from_date"));
		}
		if (!$this->thru_date->FldIsDetailKey) {
			$this->thru_date->setFormValue($objForm->GetValue("x_thru_date"));
		}
		if (!$this->com->FldIsDetailKey) {
			$this->com->setFormValue($objForm->GetValue("x_com"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->wepad_id->CurrentValue = $this->wepad_id->FormValue;
		$this->fk_wepad->CurrentValue = $this->fk_wepad->FormValue;
		$this->fk_wepad2->CurrentValue = $this->fk_wepad2->FormValue;
		$this->we_role_type->CurrentValue = $this->we_role_type->FormValue;
		$this->from_date->CurrentValue = $this->from_date->FormValue;
		$this->thru_date->CurrentValue = $this->thru_date->FormValue;
		$this->com->CurrentValue = $this->com->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("wepad_id")) <> "")
			$this->wepad_id->CurrentValue = $this->getKey("wepad_id"); // wepad_id
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// wepad_id
			$this->wepad_id->EditAttrs["class"] = "form-control";
			$this->wepad_id->EditCustomAttributes = "";
			$this->wepad_id->EditValue = ew_HtmlEncode($this->wepad_id->CurrentValue);
			$this->wepad_id->PlaceHolder = ew_RemoveHtml($this->wepad_id->FldCaption());

			// fk_wepad
			$this->fk_wepad->EditAttrs["class"] = "form-control";
			$this->fk_wepad->EditCustomAttributes = "";
			$this->fk_wepad->EditValue = ew_HtmlEncode($this->fk_wepad->CurrentValue);
			$this->fk_wepad->PlaceHolder = ew_RemoveHtml($this->fk_wepad->FldCaption());

			// fk_wepad2
			$this->fk_wepad2->EditAttrs["class"] = "form-control";
			$this->fk_wepad2->EditCustomAttributes = "";
			$this->fk_wepad2->EditValue = ew_HtmlEncode($this->fk_wepad2->CurrentValue);
			$this->fk_wepad2->PlaceHolder = ew_RemoveHtml($this->fk_wepad2->FldCaption());

			// we_role_type
			$this->we_role_type->EditAttrs["class"] = "form-control";
			$this->we_role_type->EditCustomAttributes = "";
			$this->we_role_type->EditValue = ew_HtmlEncode($this->we_role_type->CurrentValue);
			$this->we_role_type->PlaceHolder = ew_RemoveHtml($this->we_role_type->FldCaption());

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

			// com
			$this->com->EditAttrs["class"] = "form-control";
			$this->com->EditCustomAttributes = "";
			$this->com->EditValue = ew_HtmlEncode($this->com->CurrentValue);
			$this->com->PlaceHolder = ew_RemoveHtml($this->com->FldCaption());

			// Edit refer script
			// wepad_id

			$this->wepad_id->HrefValue = "";

			// fk_wepad
			$this->fk_wepad->HrefValue = "";

			// fk_wepad2
			$this->fk_wepad2->HrefValue = "";

			// we_role_type
			$this->we_role_type->HrefValue = "";

			// from_date
			$this->from_date->HrefValue = "";

			// thru_date
			$this->thru_date->HrefValue = "";

			// com
			$this->com->HrefValue = "";
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
		if (!$this->wepad_id->FldIsDetailKey && !is_null($this->wepad_id->FormValue) && $this->wepad_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->wepad_id->FldCaption(), $this->wepad_id->ReqErrMsg));
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

		// wepad_id
		$this->wepad_id->SetDbValueDef($rsnew, $this->wepad_id->CurrentValue, "", FALSE);

		// fk_wepad
		$this->fk_wepad->SetDbValueDef($rsnew, $this->fk_wepad->CurrentValue, NULL, FALSE);

		// fk_wepad2
		$this->fk_wepad2->SetDbValueDef($rsnew, $this->fk_wepad2->CurrentValue, NULL, FALSE);

		// we_role_type
		$this->we_role_type->SetDbValueDef($rsnew, $this->we_role_type->CurrentValue, NULL, FALSE);

		// from_date
		$this->from_date->SetDbValueDef($rsnew, $this->from_date->CurrentValue, NULL, FALSE);

		// thru_date
		$this->thru_date->SetDbValueDef($rsnew, $this->thru_date->CurrentValue, NULL, FALSE);

		// com
		$this->com->SetDbValueDef($rsnew, $this->com->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['wepad_id']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, "we_party_assignment_datalist.php", "", $this->TableVar, TRUE);
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
if (!isset($we_party_assignment_data_add)) $we_party_assignment_data_add = new cwe_party_assignment_data_add();

// Page init
$we_party_assignment_data_add->Page_Init();

// Page main
$we_party_assignment_data_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$we_party_assignment_data_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var we_party_assignment_data_add = new ew_Page("we_party_assignment_data_add");
we_party_assignment_data_add.PageID = "add"; // Page ID
var EW_PAGE_ID = we_party_assignment_data_add.PageID; // For backward compatibility

// Form object
var fwe_party_assignment_dataadd = new ew_Form("fwe_party_assignment_dataadd");

// Validate form
fwe_party_assignment_dataadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_wepad_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $we_party_assignment_data->wepad_id->FldCaption(), $we_party_assignment_data->wepad_id->ReqErrMsg)) ?>");

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
fwe_party_assignment_dataadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwe_party_assignment_dataadd.ValidateRequired = true;
<?php } else { ?>
fwe_party_assignment_dataadd.ValidateRequired = false; 
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
<?php $we_party_assignment_data_add->ShowPageHeader(); ?>
<?php
$we_party_assignment_data_add->ShowMessage();
?>
<form name="fwe_party_assignment_dataadd" id="fwe_party_assignment_dataadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($we_party_assignment_data_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $we_party_assignment_data_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="we_party_assignment_data">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($we_party_assignment_data->wepad_id->Visible) { // wepad_id ?>
	<div id="r_wepad_id" class="form-group">
		<label id="elh_we_party_assignment_data_wepad_id" for="x_wepad_id" class="col-sm-2 control-label ewLabel"><?php echo $we_party_assignment_data->wepad_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $we_party_assignment_data->wepad_id->CellAttributes() ?>>
<span id="el_we_party_assignment_data_wepad_id">
<input type="text" data-field="x_wepad_id" name="x_wepad_id" id="x_wepad_id" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_party_assignment_data->wepad_id->PlaceHolder) ?>" value="<?php echo $we_party_assignment_data->wepad_id->EditValue ?>"<?php echo $we_party_assignment_data->wepad_id->EditAttributes() ?>>
</span>
<?php echo $we_party_assignment_data->wepad_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_party_assignment_data->fk_wepad->Visible) { // fk_wepad ?>
	<div id="r_fk_wepad" class="form-group">
		<label id="elh_we_party_assignment_data_fk_wepad" for="x_fk_wepad" class="col-sm-2 control-label ewLabel"><?php echo $we_party_assignment_data->fk_wepad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_party_assignment_data->fk_wepad->CellAttributes() ?>>
<span id="el_we_party_assignment_data_fk_wepad">
<input type="text" data-field="x_fk_wepad" name="x_fk_wepad" id="x_fk_wepad" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_party_assignment_data->fk_wepad->PlaceHolder) ?>" value="<?php echo $we_party_assignment_data->fk_wepad->EditValue ?>"<?php echo $we_party_assignment_data->fk_wepad->EditAttributes() ?>>
</span>
<?php echo $we_party_assignment_data->fk_wepad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_party_assignment_data->fk_wepad2->Visible) { // fk_wepad2 ?>
	<div id="r_fk_wepad2" class="form-group">
		<label id="elh_we_party_assignment_data_fk_wepad2" for="x_fk_wepad2" class="col-sm-2 control-label ewLabel"><?php echo $we_party_assignment_data->fk_wepad2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_party_assignment_data->fk_wepad2->CellAttributes() ?>>
<span id="el_we_party_assignment_data_fk_wepad2">
<input type="text" data-field="x_fk_wepad2" name="x_fk_wepad2" id="x_fk_wepad2" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_party_assignment_data->fk_wepad2->PlaceHolder) ?>" value="<?php echo $we_party_assignment_data->fk_wepad2->EditValue ?>"<?php echo $we_party_assignment_data->fk_wepad2->EditAttributes() ?>>
</span>
<?php echo $we_party_assignment_data->fk_wepad2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_party_assignment_data->we_role_type->Visible) { // we_role_type ?>
	<div id="r_we_role_type" class="form-group">
		<label id="elh_we_party_assignment_data_we_role_type" for="x_we_role_type" class="col-sm-2 control-label ewLabel"><?php echo $we_party_assignment_data->we_role_type->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_party_assignment_data->we_role_type->CellAttributes() ?>>
<span id="el_we_party_assignment_data_we_role_type">
<input type="text" data-field="x_we_role_type" name="x_we_role_type" id="x_we_role_type" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($we_party_assignment_data->we_role_type->PlaceHolder) ?>" value="<?php echo $we_party_assignment_data->we_role_type->EditValue ?>"<?php echo $we_party_assignment_data->we_role_type->EditAttributes() ?>>
</span>
<?php echo $we_party_assignment_data->we_role_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_party_assignment_data->from_date->Visible) { // from_date ?>
	<div id="r_from_date" class="form-group">
		<label id="elh_we_party_assignment_data_from_date" for="x_from_date" class="col-sm-2 control-label ewLabel"><?php echo $we_party_assignment_data->from_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_party_assignment_data->from_date->CellAttributes() ?>>
<span id="el_we_party_assignment_data_from_date">
<input type="text" data-field="x_from_date" name="x_from_date" id="x_from_date" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($we_party_assignment_data->from_date->PlaceHolder) ?>" value="<?php echo $we_party_assignment_data->from_date->EditValue ?>"<?php echo $we_party_assignment_data->from_date->EditAttributes() ?>>
</span>
<?php echo $we_party_assignment_data->from_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_party_assignment_data->thru_date->Visible) { // thru_date ?>
	<div id="r_thru_date" class="form-group">
		<label id="elh_we_party_assignment_data_thru_date" for="x_thru_date" class="col-sm-2 control-label ewLabel"><?php echo $we_party_assignment_data->thru_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_party_assignment_data->thru_date->CellAttributes() ?>>
<span id="el_we_party_assignment_data_thru_date">
<input type="text" data-field="x_thru_date" name="x_thru_date" id="x_thru_date" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($we_party_assignment_data->thru_date->PlaceHolder) ?>" value="<?php echo $we_party_assignment_data->thru_date->EditValue ?>"<?php echo $we_party_assignment_data->thru_date->EditAttributes() ?>>
</span>
<?php echo $we_party_assignment_data->thru_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_party_assignment_data->com->Visible) { // com ?>
	<div id="r_com" class="form-group">
		<label id="elh_we_party_assignment_data_com" for="x_com" class="col-sm-2 control-label ewLabel"><?php echo $we_party_assignment_data->com->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_party_assignment_data->com->CellAttributes() ?>>
<span id="el_we_party_assignment_data_com">
<input type="text" data-field="x_com" name="x_com" id="x_com" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($we_party_assignment_data->com->PlaceHolder) ?>" value="<?php echo $we_party_assignment_data->com->EditValue ?>"<?php echo $we_party_assignment_data->com->EditAttributes() ?>>
</span>
<?php echo $we_party_assignment_data->com->CustomMsg ?></div></div>
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
fwe_party_assignment_dataadd.Init();
</script>
<?php
$we_party_assignment_data_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$we_party_assignment_data_add->Page_Terminate();
?>
