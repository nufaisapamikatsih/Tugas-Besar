<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "we_rateinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$we_rate_add = NULL; // Initialize page object first

class cwe_rate_add extends cwe_rate {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'we_rate';

	// Page object name
	var $PageObjName = 'we_rate_add';

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

		// Table object (we_rate)
		if (!isset($GLOBALS["we_rate"]) || get_class($GLOBALS["we_rate"]) == "cwe_rate") {
			$GLOBALS["we_rate"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["we_rate"];
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
			define("EW_TABLE_NAME", 'we_rate', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("we_ratelist.php"));
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
		global $EW_EXPORT, $we_rate;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($we_rate);
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
			if (@$_GET["werate_id"] != "") {
				$this->werate_id->setQueryStringValue($_GET["werate_id"]);
				$this->setKey("werate_id", $this->werate_id->CurrentValue); // Set up key
			} else {
				$this->setKey("werate_id", ""); // Clear key
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
					$this->Page_Terminate("we_ratelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "we_rateview.php")
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
		$this->werate_id->CurrentValue = NULL;
		$this->werate_id->OldValue = $this->werate_id->CurrentValue;
		$this->work_task->CurrentValue = NULL;
		$this->work_task->OldValue = $this->work_task->CurrentValue;
		$this->fk_werate->CurrentValue = NULL;
		$this->fk_werate->OldValue = $this->fk_werate->CurrentValue;
		$this->fk_werate2->CurrentValue = NULL;
		$this->fk_werate2->OldValue = $this->fk_werate2->CurrentValue;
		$this->from_date->CurrentValue = NULL;
		$this->from_date->OldValue = $this->from_date->CurrentValue;
		$this->thru_date->CurrentValue = NULL;
		$this->thru_date->OldValue = $this->thru_date->CurrentValue;
		$this->rate->CurrentValue = NULL;
		$this->rate->OldValue = $this->rate->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->werate_id->FldIsDetailKey) {
			$this->werate_id->setFormValue($objForm->GetValue("x_werate_id"));
		}
		if (!$this->work_task->FldIsDetailKey) {
			$this->work_task->setFormValue($objForm->GetValue("x_work_task"));
		}
		if (!$this->fk_werate->FldIsDetailKey) {
			$this->fk_werate->setFormValue($objForm->GetValue("x_fk_werate"));
		}
		if (!$this->fk_werate2->FldIsDetailKey) {
			$this->fk_werate2->setFormValue($objForm->GetValue("x_fk_werate2"));
		}
		if (!$this->from_date->FldIsDetailKey) {
			$this->from_date->setFormValue($objForm->GetValue("x_from_date"));
		}
		if (!$this->thru_date->FldIsDetailKey) {
			$this->thru_date->setFormValue($objForm->GetValue("x_thru_date"));
		}
		if (!$this->rate->FldIsDetailKey) {
			$this->rate->setFormValue($objForm->GetValue("x_rate"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->werate_id->CurrentValue = $this->werate_id->FormValue;
		$this->work_task->CurrentValue = $this->work_task->FormValue;
		$this->fk_werate->CurrentValue = $this->fk_werate->FormValue;
		$this->fk_werate2->CurrentValue = $this->fk_werate2->FormValue;
		$this->from_date->CurrentValue = $this->from_date->FormValue;
		$this->thru_date->CurrentValue = $this->thru_date->FormValue;
		$this->rate->CurrentValue = $this->rate->FormValue;
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
		$this->werate_id->setDbValue($rs->fields('werate_id'));
		$this->work_task->setDbValue($rs->fields('work_task'));
		$this->fk_werate->setDbValue($rs->fields('fk_werate'));
		$this->fk_werate2->setDbValue($rs->fields('fk_werate2'));
		$this->from_date->setDbValue($rs->fields('from_date'));
		$this->thru_date->setDbValue($rs->fields('thru_date'));
		$this->rate->setDbValue($rs->fields('rate'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->werate_id->DbValue = $row['werate_id'];
		$this->work_task->DbValue = $row['work_task'];
		$this->fk_werate->DbValue = $row['fk_werate'];
		$this->fk_werate2->DbValue = $row['fk_werate2'];
		$this->from_date->DbValue = $row['from_date'];
		$this->thru_date->DbValue = $row['thru_date'];
		$this->rate->DbValue = $row['rate'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("werate_id")) <> "")
			$this->werate_id->CurrentValue = $this->getKey("werate_id"); // werate_id
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
		// werate_id
		// work_task
		// fk_werate
		// fk_werate2
		// from_date
		// thru_date
		// rate

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// werate_id
			$this->werate_id->ViewValue = $this->werate_id->CurrentValue;
			$this->werate_id->ViewCustomAttributes = "";

			// work_task
			$this->work_task->ViewValue = $this->work_task->CurrentValue;
			$this->work_task->ViewCustomAttributes = "";

			// fk_werate
			$this->fk_werate->ViewValue = $this->fk_werate->CurrentValue;
			$this->fk_werate->ViewCustomAttributes = "";

			// fk_werate2
			$this->fk_werate2->ViewValue = $this->fk_werate2->CurrentValue;
			$this->fk_werate2->ViewCustomAttributes = "";

			// from_date
			$this->from_date->ViewValue = $this->from_date->CurrentValue;
			$this->from_date->ViewCustomAttributes = "";

			// thru_date
			$this->thru_date->ViewValue = $this->thru_date->CurrentValue;
			$this->thru_date->ViewCustomAttributes = "";

			// rate
			$this->rate->ViewValue = $this->rate->CurrentValue;
			$this->rate->ViewCustomAttributes = "";

			// werate_id
			$this->werate_id->LinkCustomAttributes = "";
			$this->werate_id->HrefValue = "";
			$this->werate_id->TooltipValue = "";

			// work_task
			$this->work_task->LinkCustomAttributes = "";
			$this->work_task->HrefValue = "";
			$this->work_task->TooltipValue = "";

			// fk_werate
			$this->fk_werate->LinkCustomAttributes = "";
			$this->fk_werate->HrefValue = "";
			$this->fk_werate->TooltipValue = "";

			// fk_werate2
			$this->fk_werate2->LinkCustomAttributes = "";
			$this->fk_werate2->HrefValue = "";
			$this->fk_werate2->TooltipValue = "";

			// from_date
			$this->from_date->LinkCustomAttributes = "";
			$this->from_date->HrefValue = "";
			$this->from_date->TooltipValue = "";

			// thru_date
			$this->thru_date->LinkCustomAttributes = "";
			$this->thru_date->HrefValue = "";
			$this->thru_date->TooltipValue = "";

			// rate
			$this->rate->LinkCustomAttributes = "";
			$this->rate->HrefValue = "";
			$this->rate->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// werate_id
			$this->werate_id->EditAttrs["class"] = "form-control";
			$this->werate_id->EditCustomAttributes = "";
			$this->werate_id->EditValue = ew_HtmlEncode($this->werate_id->CurrentValue);
			$this->werate_id->PlaceHolder = ew_RemoveHtml($this->werate_id->FldCaption());

			// work_task
			$this->work_task->EditAttrs["class"] = "form-control";
			$this->work_task->EditCustomAttributes = "";
			$this->work_task->EditValue = ew_HtmlEncode($this->work_task->CurrentValue);
			$this->work_task->PlaceHolder = ew_RemoveHtml($this->work_task->FldCaption());

			// fk_werate
			$this->fk_werate->EditAttrs["class"] = "form-control";
			$this->fk_werate->EditCustomAttributes = "";
			$this->fk_werate->EditValue = ew_HtmlEncode($this->fk_werate->CurrentValue);
			$this->fk_werate->PlaceHolder = ew_RemoveHtml($this->fk_werate->FldCaption());

			// fk_werate2
			$this->fk_werate2->EditAttrs["class"] = "form-control";
			$this->fk_werate2->EditCustomAttributes = "";
			$this->fk_werate2->EditValue = ew_HtmlEncode($this->fk_werate2->CurrentValue);
			$this->fk_werate2->PlaceHolder = ew_RemoveHtml($this->fk_werate2->FldCaption());

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

			// rate
			$this->rate->EditAttrs["class"] = "form-control";
			$this->rate->EditCustomAttributes = "";
			$this->rate->EditValue = ew_HtmlEncode($this->rate->CurrentValue);
			$this->rate->PlaceHolder = ew_RemoveHtml($this->rate->FldCaption());

			// Edit refer script
			// werate_id

			$this->werate_id->HrefValue = "";

			// work_task
			$this->work_task->HrefValue = "";

			// fk_werate
			$this->fk_werate->HrefValue = "";

			// fk_werate2
			$this->fk_werate2->HrefValue = "";

			// from_date
			$this->from_date->HrefValue = "";

			// thru_date
			$this->thru_date->HrefValue = "";

			// rate
			$this->rate->HrefValue = "";
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
		if (!$this->werate_id->FldIsDetailKey && !is_null($this->werate_id->FormValue) && $this->werate_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->werate_id->FldCaption(), $this->werate_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->rate->FormValue)) {
			ew_AddMessage($gsFormError, $this->rate->FldErrMsg());
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

		// werate_id
		$this->werate_id->SetDbValueDef($rsnew, $this->werate_id->CurrentValue, "", FALSE);

		// work_task
		$this->work_task->SetDbValueDef($rsnew, $this->work_task->CurrentValue, NULL, FALSE);

		// fk_werate
		$this->fk_werate->SetDbValueDef($rsnew, $this->fk_werate->CurrentValue, NULL, FALSE);

		// fk_werate2
		$this->fk_werate2->SetDbValueDef($rsnew, $this->fk_werate2->CurrentValue, NULL, FALSE);

		// from_date
		$this->from_date->SetDbValueDef($rsnew, $this->from_date->CurrentValue, NULL, FALSE);

		// thru_date
		$this->thru_date->SetDbValueDef($rsnew, $this->thru_date->CurrentValue, NULL, FALSE);

		// rate
		$this->rate->SetDbValueDef($rsnew, $this->rate->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['werate_id']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, "we_ratelist.php", "", $this->TableVar, TRUE);
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
if (!isset($we_rate_add)) $we_rate_add = new cwe_rate_add();

// Page init
$we_rate_add->Page_Init();

// Page main
$we_rate_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$we_rate_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var we_rate_add = new ew_Page("we_rate_add");
we_rate_add.PageID = "add"; // Page ID
var EW_PAGE_ID = we_rate_add.PageID; // For backward compatibility

// Form object
var fwe_rateadd = new ew_Form("fwe_rateadd");

// Validate form
fwe_rateadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_werate_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $we_rate->werate_id->FldCaption(), $we_rate->werate_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rate");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($we_rate->rate->FldErrMsg()) ?>");

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
fwe_rateadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwe_rateadd.ValidateRequired = true;
<?php } else { ?>
fwe_rateadd.ValidateRequired = false; 
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
<?php $we_rate_add->ShowPageHeader(); ?>
<?php
$we_rate_add->ShowMessage();
?>
<form name="fwe_rateadd" id="fwe_rateadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($we_rate_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $we_rate_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="we_rate">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($we_rate->werate_id->Visible) { // werate_id ?>
	<div id="r_werate_id" class="form-group">
		<label id="elh_we_rate_werate_id" for="x_werate_id" class="col-sm-2 control-label ewLabel"><?php echo $we_rate->werate_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $we_rate->werate_id->CellAttributes() ?>>
<span id="el_we_rate_werate_id">
<input type="text" data-field="x_werate_id" name="x_werate_id" id="x_werate_id" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_rate->werate_id->PlaceHolder) ?>" value="<?php echo $we_rate->werate_id->EditValue ?>"<?php echo $we_rate->werate_id->EditAttributes() ?>>
</span>
<?php echo $we_rate->werate_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_rate->work_task->Visible) { // work_task ?>
	<div id="r_work_task" class="form-group">
		<label id="elh_we_rate_work_task" for="x_work_task" class="col-sm-2 control-label ewLabel"><?php echo $we_rate->work_task->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_rate->work_task->CellAttributes() ?>>
<span id="el_we_rate_work_task">
<input type="text" data-field="x_work_task" name="x_work_task" id="x_work_task" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($we_rate->work_task->PlaceHolder) ?>" value="<?php echo $we_rate->work_task->EditValue ?>"<?php echo $we_rate->work_task->EditAttributes() ?>>
</span>
<?php echo $we_rate->work_task->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_rate->fk_werate->Visible) { // fk_werate ?>
	<div id="r_fk_werate" class="form-group">
		<label id="elh_we_rate_fk_werate" for="x_fk_werate" class="col-sm-2 control-label ewLabel"><?php echo $we_rate->fk_werate->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_rate->fk_werate->CellAttributes() ?>>
<span id="el_we_rate_fk_werate">
<input type="text" data-field="x_fk_werate" name="x_fk_werate" id="x_fk_werate" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_rate->fk_werate->PlaceHolder) ?>" value="<?php echo $we_rate->fk_werate->EditValue ?>"<?php echo $we_rate->fk_werate->EditAttributes() ?>>
</span>
<?php echo $we_rate->fk_werate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_rate->fk_werate2->Visible) { // fk_werate2 ?>
	<div id="r_fk_werate2" class="form-group">
		<label id="elh_we_rate_fk_werate2" for="x_fk_werate2" class="col-sm-2 control-label ewLabel"><?php echo $we_rate->fk_werate2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_rate->fk_werate2->CellAttributes() ?>>
<span id="el_we_rate_fk_werate2">
<input type="text" data-field="x_fk_werate2" name="x_fk_werate2" id="x_fk_werate2" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_rate->fk_werate2->PlaceHolder) ?>" value="<?php echo $we_rate->fk_werate2->EditValue ?>"<?php echo $we_rate->fk_werate2->EditAttributes() ?>>
</span>
<?php echo $we_rate->fk_werate2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_rate->from_date->Visible) { // from_date ?>
	<div id="r_from_date" class="form-group">
		<label id="elh_we_rate_from_date" for="x_from_date" class="col-sm-2 control-label ewLabel"><?php echo $we_rate->from_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_rate->from_date->CellAttributes() ?>>
<span id="el_we_rate_from_date">
<input type="text" data-field="x_from_date" name="x_from_date" id="x_from_date" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_rate->from_date->PlaceHolder) ?>" value="<?php echo $we_rate->from_date->EditValue ?>"<?php echo $we_rate->from_date->EditAttributes() ?>>
</span>
<?php echo $we_rate->from_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_rate->thru_date->Visible) { // thru_date ?>
	<div id="r_thru_date" class="form-group">
		<label id="elh_we_rate_thru_date" for="x_thru_date" class="col-sm-2 control-label ewLabel"><?php echo $we_rate->thru_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_rate->thru_date->CellAttributes() ?>>
<span id="el_we_rate_thru_date">
<input type="text" data-field="x_thru_date" name="x_thru_date" id="x_thru_date" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_rate->thru_date->PlaceHolder) ?>" value="<?php echo $we_rate->thru_date->EditValue ?>"<?php echo $we_rate->thru_date->EditAttributes() ?>>
</span>
<?php echo $we_rate->thru_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_rate->rate->Visible) { // rate ?>
	<div id="r_rate" class="form-group">
		<label id="elh_we_rate_rate" for="x_rate" class="col-sm-2 control-label ewLabel"><?php echo $we_rate->rate->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_rate->rate->CellAttributes() ?>>
<span id="el_we_rate_rate">
<input type="text" data-field="x_rate" name="x_rate" id="x_rate" size="30" placeholder="<?php echo ew_HtmlEncode($we_rate->rate->PlaceHolder) ?>" value="<?php echo $we_rate->rate->EditValue ?>"<?php echo $we_rate->rate->EditAttributes() ?>>
</span>
<?php echo $we_rate->rate->CustomMsg ?></div></div>
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
fwe_rateadd.Init();
</script>
<?php
$we_rate_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$we_rate_add->Page_Terminate();
?>
