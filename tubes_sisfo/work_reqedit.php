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

$work_req_edit = NULL; // Initialize page object first

class cwork_req_edit extends cwork_req {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'work_req';

	// Page object name
	var $PageObjName = 'work_req_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["work_req_id"] <> "") {
			$this->work_req_id->setQueryStringValue($_GET["work_req_id"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->work_req_id->CurrentValue == "")
			$this->Page_Terminate("work_reqlist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("work_reqlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
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
		$this->LoadRow();
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// work_req_id
			$this->work_req_id->EditAttrs["class"] = "form-control";
			$this->work_req_id->EditCustomAttributes = "";
			$this->work_req_id->EditValue = $this->work_req_id->CurrentValue;
			$this->work_req_id->ViewCustomAttributes = "";

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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// work_req_id
			// fk_work_req

			$this->fk_work_req->SetDbValueDef($rsnew, $this->fk_work_req->CurrentValue, NULL, $this->fk_work_req->ReadOnly);

			// req_creation_date
			$this->req_creation_date->SetDbValueDef($rsnew, $this->req_creation_date->CurrentValue, NULL, $this->req_creation_date->ReadOnly);

			// req_by_date
			$this->req_by_date->SetDbValueDef($rsnew, $this->req_by_date->CurrentValue, NULL, $this->req_by_date->ReadOnly);

			// description
			$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, $this->description->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "work_reqlist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, ew_CurrentUrl());
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
if (!isset($work_req_edit)) $work_req_edit = new cwork_req_edit();

// Page init
$work_req_edit->Page_Init();

// Page main
$work_req_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$work_req_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var work_req_edit = new ew_Page("work_req_edit");
work_req_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = work_req_edit.PageID; // For backward compatibility

// Form object
var fwork_reqedit = new ew_Form("fwork_reqedit");

// Validate form
fwork_reqedit.Validate = function() {
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
fwork_reqedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwork_reqedit.ValidateRequired = true;
<?php } else { ?>
fwork_reqedit.ValidateRequired = false; 
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
<?php $work_req_edit->ShowPageHeader(); ?>
<?php
$work_req_edit->ShowMessage();
?>
<form name="fwork_reqedit" id="fwork_reqedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($work_req_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $work_req_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="work_req">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($work_req->work_req_id->Visible) { // work_req_id ?>
	<div id="r_work_req_id" class="form-group">
		<label id="elh_work_req_work_req_id" for="x_work_req_id" class="col-sm-2 control-label ewLabel"><?php echo $work_req->work_req_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $work_req->work_req_id->CellAttributes() ?>>
<span id="el_work_req_work_req_id">
<span<?php echo $work_req->work_req_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $work_req->work_req_id->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_work_req_id" name="x_work_req_id" id="x_work_req_id" value="<?php echo ew_HtmlEncode($work_req->work_req_id->CurrentValue) ?>">
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
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fwork_reqedit.Init();
</script>
<?php
$work_req_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$work_req_edit->Page_Terminate();
?>
