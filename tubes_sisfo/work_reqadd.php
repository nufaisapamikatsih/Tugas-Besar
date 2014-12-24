<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "work_reqinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$work_req_add = NULL; // Initialize page object first

class cwork_req_add extends cwork_req {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'work_req';

	// Page object name
	var $PageObjName = 'work_req_add';

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

		// Table object (work_req)
		if (!isset($GLOBALS["work_req"]) || get_class($GLOBALS["work_req"]) == "cwork_req") {
			$GLOBALS["work_req"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["work_req"];
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
			define("EW_TABLE_NAME", 'work_req', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("work_reqlist.php"));
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
		global $EW_EXPORT, $work_req;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($work_req);
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
			if (@$_GET["work_req_id"] != "") {
				$this->work_req_id->setQueryStringValue($_GET["work_req_id"]);
				$this->setKey("work_req_id", $this->work_req_id->CurrentValue); // Set up key
			} else {
				$this->setKey("work_req_id", ""); // Clear key
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
					$this->Page_Terminate("work_reqlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "work_reqview.php")
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
		$this->work_req_id->CurrentValue = NULL;
		$this->work_req_id->OldValue = $this->work_req_id->CurrentValue;
		$this->fk_work_req->CurrentValue = NULL;
		$this->fk_work_req->OldValue = $this->fk_work_req->CurrentValue;
		$this->req_creation_date->CurrentValue = NULL;
		$this->req_creation_date->OldValue = $this->req_creation_date->CurrentValue;
		$this->req_by_date->CurrentValue = NULL;
		$this->req_by_date->OldValue = $this->req_by_date->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->work_req_id->FldIsDetailKey) {
			$this->work_req_id->setFormValue($objForm->GetValue("x_work_req_id"));
		}
		if (!$this->fk_work_req->FldIsDetailKey) {
			$this->fk_work_req->setFormValue($objForm->GetValue("x_fk_work_req"));
		}
		if (!$this->req_creation_date->FldIsDetailKey) {
			$this->req_creation_date->setFormValue($objForm->GetValue("x_req_creation_date"));
		}
		if (!$this->req_by_date->FldIsDetailKey) {
			$this->req_by_date->setFormValue($objForm->GetValue("x_req_by_date"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->work_req_id->CurrentValue = $this->work_req_id->FormValue;
		$this->fk_work_req->CurrentValue = $this->fk_work_req->FormValue;
		$this->req_creation_date->CurrentValue = $this->req_creation_date->FormValue;
		$this->req_by_date->CurrentValue = $this->req_by_date->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
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
		$this->work_req_id->setDbValue($rs->fields('work_req_id'));
		$this->fk_work_req->setDbValue($rs->fields('fk_work_req'));
		$this->req_creation_date->setDbValue($rs->fields('req_creation_date'));
		$this->req_by_date->setDbValue($rs->fields('req_by_date'));
		$this->description->setDbValue($rs->fields('description'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->work_req_id->DbValue = $row['work_req_id'];
		$this->fk_work_req->DbValue = $row['fk_work_req'];
		$this->req_creation_date->DbValue = $row['req_creation_date'];
		$this->req_by_date->DbValue = $row['req_by_date'];
		$this->description->DbValue = $row['description'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("work_req_id")) <> "")
			$this->work_req_id->CurrentValue = $this->getKey("work_req_id"); // work_req_id
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
		// work_req_id
		// fk_work_req
		// req_creation_date
		// req_by_date
		// description

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// work_req_id
			$this->work_req_id->ViewValue = $this->work_req_id->CurrentValue;
			$this->work_req_id->ViewCustomAttributes = "";

			// fk_work_req
			$this->fk_work_req->ViewValue = $this->fk_work_req->CurrentValue;
			$this->fk_work_req->ViewCustomAttributes = "";

			// req_creation_date
			$this->req_creation_date->ViewValue = $this->req_creation_date->CurrentValue;
			$this->req_creation_date->ViewCustomAttributes = "";

			// req_by_date
			$this->req_by_date->ViewValue = $this->req_by_date->CurrentValue;
			$this->req_by_date->ViewCustomAttributes = "";

			// description
			$this->description->ViewValue = $this->description->CurrentValue;
			$this->description->ViewCustomAttributes = "";

			// work_req_id
			$this->work_req_id->LinkCustomAttributes = "";
			$this->work_req_id->HrefValue = "";
			$this->work_req_id->TooltipValue = "";

			// fk_work_req
			$this->fk_work_req->LinkCustomAttributes = "";
			$this->fk_work_req->HrefValue = "";
			$this->fk_work_req->TooltipValue = "";

			// req_creation_date
			$this->req_creation_date->LinkCustomAttributes = "";
			$this->req_creation_date->HrefValue = "";
			$this->req_creation_date->TooltipValue = "";

			// req_by_date
			$this->req_by_date->LinkCustomAttributes = "";
			$this->req_by_date->HrefValue = "";
			$this->req_by_date->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// work_req_id
			$this->work_req_id->EditAttrs["class"] = "form-control";
			$this->work_req_id->EditCustomAttributes = "";
			$this->work_req_id->EditValue = ew_HtmlEncode($this->work_req_id->CurrentValue);
			$this->work_req_id->PlaceHolder = ew_RemoveHtml($this->work_req_id->FldCaption());

			// fk_work_req
			$this->fk_work_req->EditAttrs["class"] = "form-control";
			$this->fk_work_req->EditCustomAttributes = "";
			$this->fk_work_req->EditValue = ew_HtmlEncode($this->fk_work_req->CurrentValue);
			$this->fk_work_req->PlaceHolder = ew_RemoveHtml($this->fk_work_req->FldCaption());

			// req_creation_date
			$this->req_creation_date->EditAttrs["class"] = "form-control";
			$this->req_creation_date->EditCustomAttributes = "";
			$this->req_creation_date->EditValue = ew_HtmlEncode($this->req_creation_date->CurrentValue);
			$this->req_creation_date->PlaceHolder = ew_RemoveHtml($this->req_creation_date->FldCaption());

			// req_by_date
			$this->req_by_date->EditAttrs["class"] = "form-control";
			$this->req_by_date->EditCustomAttributes = "";
			$this->req_by_date->EditValue = ew_HtmlEncode($this->req_by_date->CurrentValue);
			$this->req_by_date->PlaceHolder = ew_RemoveHtml($this->req_by_date->FldCaption());

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// Edit refer script
			// work_req_id

			$this->work_req_id->HrefValue = "";

			// fk_work_req
			$this->fk_work_req->HrefValue = "";

			// req_creation_date
			$this->req_creation_date->HrefValue = "";

			// req_by_date
			$this->req_by_date->HrefValue = "";

			// description
			$this->description->HrefValue = "";
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
		if (!$this->work_req_id->FldIsDetailKey && !is_null($this->work_req_id->FormValue) && $this->work_req_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->work_req_id->FldCaption(), $this->work_req_id->ReqErrMsg));
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

		// work_req_id
		$this->work_req_id->SetDbValueDef($rsnew, $this->work_req_id->CurrentValue, "", FALSE);

		// fk_work_req
		$this->fk_work_req->SetDbValueDef($rsnew, $this->fk_work_req->CurrentValue, NULL, FALSE);

		// req_creation_date
		$this->req_creation_date->SetDbValueDef($rsnew, $this->req_creation_date->CurrentValue, NULL, FALSE);

		// req_by_date
		$this->req_by_date->SetDbValueDef($rsnew, $this->req_by_date->CurrentValue, NULL, FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['work_req_id']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, "work_reqlist.php", "", $this->TableVar, TRUE);
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
if (!isset($work_req_add)) $work_req_add = new cwork_req_add();

// Page init
$work_req_add->Page_Init();

// Page main
$work_req_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$work_req_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var work_req_add = new ew_Page("work_req_add");
work_req_add.PageID = "add"; // Page ID
var EW_PAGE_ID = work_req_add.PageID; // For backward compatibility

// Form object
var fwork_reqadd = new ew_Form("fwork_reqadd");

// Validate form
fwork_reqadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_work_req_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $work_req->work_req_id->FldCaption(), $work_req->work_req_id->ReqErrMsg)) ?>");

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
fwork_reqadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwork_reqadd.ValidateRequired = true;
<?php } else { ?>
fwork_reqadd.ValidateRequired = false; 
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
<?php $work_req_add->ShowPageHeader(); ?>
<?php
$work_req_add->ShowMessage();
?>
<form name="fwork_reqadd" id="fwork_reqadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($work_req_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $work_req_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="work_req">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($work_req->work_req_id->Visible) { // work_req_id ?>
	<div id="r_work_req_id" class="form-group">
		<label id="elh_work_req_work_req_id" for="x_work_req_id" class="col-sm-2 control-label ewLabel"><?php echo $work_req->work_req_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $work_req->work_req_id->CellAttributes() ?>>
<span id="el_work_req_work_req_id">
<input type="text" data-field="x_work_req_id" name="x_work_req_id" id="x_work_req_id" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($work_req->work_req_id->PlaceHolder) ?>" value="<?php echo $work_req->work_req_id->EditValue ?>"<?php echo $work_req->work_req_id->EditAttributes() ?>>
</span>
<?php echo $work_req->work_req_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($work_req->fk_work_req->Visible) { // fk_work_req ?>
	<div id="r_fk_work_req" class="form-group">
		<label id="elh_work_req_fk_work_req" for="x_fk_work_req" class="col-sm-2 control-label ewLabel"><?php echo $work_req->fk_work_req->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $work_req->fk_work_req->CellAttributes() ?>>
<span id="el_work_req_fk_work_req">
<input type="text" data-field="x_fk_work_req" name="x_fk_work_req" id="x_fk_work_req" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($work_req->fk_work_req->PlaceHolder) ?>" value="<?php echo $work_req->fk_work_req->EditValue ?>"<?php echo $work_req->fk_work_req->EditAttributes() ?>>
</span>
<?php echo $work_req->fk_work_req->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($work_req->req_creation_date->Visible) { // req_creation_date ?>
	<div id="r_req_creation_date" class="form-group">
		<label id="elh_work_req_req_creation_date" for="x_req_creation_date" class="col-sm-2 control-label ewLabel"><?php echo $work_req->req_creation_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $work_req->req_creation_date->CellAttributes() ?>>
<span id="el_work_req_req_creation_date">
<input type="text" data-field="x_req_creation_date" name="x_req_creation_date" id="x_req_creation_date" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($work_req->req_creation_date->PlaceHolder) ?>" value="<?php echo $work_req->req_creation_date->EditValue ?>"<?php echo $work_req->req_creation_date->EditAttributes() ?>>
</span>
<?php echo $work_req->req_creation_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($work_req->req_by_date->Visible) { // req_by_date ?>
	<div id="r_req_by_date" class="form-group">
		<label id="elh_work_req_req_by_date" for="x_req_by_date" class="col-sm-2 control-label ewLabel"><?php echo $work_req->req_by_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $work_req->req_by_date->CellAttributes() ?>>
<span id="el_work_req_req_by_date">
<input type="text" data-field="x_req_by_date" name="x_req_by_date" id="x_req_by_date" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($work_req->req_by_date->PlaceHolder) ?>" value="<?php echo $work_req->req_by_date->EditValue ?>"<?php echo $work_req->req_by_date->EditAttributes() ?>>
</span>
<?php echo $work_req->req_by_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($work_req->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_work_req_description" for="x_description" class="col-sm-2 control-label ewLabel"><?php echo $work_req->description->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $work_req->description->CellAttributes() ?>>
<span id="el_work_req_description">
<input type="text" data-field="x_description" name="x_description" id="x_description" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($work_req->description->PlaceHolder) ?>" value="<?php echo $work_req->description->EditValue ?>"<?php echo $work_req->description->EditAttributes() ?>>
</span>
<?php echo $work_req->description->CustomMsg ?></div></div>
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
fwork_reqadd.Init();
</script>
<?php
$work_req_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$work_req_add->Page_Terminate();
?>
