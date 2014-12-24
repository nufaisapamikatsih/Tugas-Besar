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

$we_good_standard_edit = NULL; // Initialize page object first

class cwe_good_standard_edit extends cwe_good_standard {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'we_good_standard';

	// Page object name
	var $PageObjName = 'we_good_standard_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("we_good_standardlist.php"));
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["wegs_id"] <> "") {
			$this->wegs_id->setQueryStringValue($_GET["wegs_id"]);
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
		if ($this->wegs_id->CurrentValue == "")
			$this->Page_Terminate("we_good_standardlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("we_good_standardlist.php"); // No matching record, return to list
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
		if (!$this->wegs_id->FldIsDetailKey) {
			$this->wegs_id->setFormValue($objForm->GetValue("x_wegs_id"));
		}
		if (!$this->fk_wegs->FldIsDetailKey) {
			$this->fk_wegs->setFormValue($objForm->GetValue("x_fk_wegs"));
		}
		if (!$this->item->FldIsDetailKey) {
			$this->item->setFormValue($objForm->GetValue("x_item"));
		}
		if (!$this->est_quantity->FldIsDetailKey) {
			$this->est_quantity->setFormValue($objForm->GetValue("x_est_quantity"));
		}
		if (!$this->est_cost->FldIsDetailKey) {
			$this->est_cost->setFormValue($objForm->GetValue("x_est_cost"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->wegs_id->CurrentValue = $this->wegs_id->FormValue;
		$this->fk_wegs->CurrentValue = $this->fk_wegs->FormValue;
		$this->item->CurrentValue = $this->item->FormValue;
		$this->est_quantity->CurrentValue = $this->est_quantity->FormValue;
		$this->est_cost->CurrentValue = $this->est_cost->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// wegs_id
			$this->wegs_id->EditAttrs["class"] = "form-control";
			$this->wegs_id->EditCustomAttributes = "";
			$this->wegs_id->EditValue = $this->wegs_id->CurrentValue;
			$this->wegs_id->ViewCustomAttributes = "";

			// fk_wegs
			$this->fk_wegs->EditAttrs["class"] = "form-control";
			$this->fk_wegs->EditCustomAttributes = "";
			$this->fk_wegs->EditValue = ew_HtmlEncode($this->fk_wegs->CurrentValue);
			$this->fk_wegs->PlaceHolder = ew_RemoveHtml($this->fk_wegs->FldCaption());

			// item
			$this->item->EditAttrs["class"] = "form-control";
			$this->item->EditCustomAttributes = "";
			$this->item->EditValue = ew_HtmlEncode($this->item->CurrentValue);
			$this->item->PlaceHolder = ew_RemoveHtml($this->item->FldCaption());

			// est_quantity
			$this->est_quantity->EditAttrs["class"] = "form-control";
			$this->est_quantity->EditCustomAttributes = "";
			$this->est_quantity->EditValue = ew_HtmlEncode($this->est_quantity->CurrentValue);
			$this->est_quantity->PlaceHolder = ew_RemoveHtml($this->est_quantity->FldCaption());

			// est_cost
			$this->est_cost->EditAttrs["class"] = "form-control";
			$this->est_cost->EditCustomAttributes = "";
			$this->est_cost->EditValue = ew_HtmlEncode($this->est_cost->CurrentValue);
			$this->est_cost->PlaceHolder = ew_RemoveHtml($this->est_cost->FldCaption());

			// Edit refer script
			// wegs_id

			$this->wegs_id->HrefValue = "";

			// fk_wegs
			$this->fk_wegs->HrefValue = "";

			// item
			$this->item->HrefValue = "";

			// est_quantity
			$this->est_quantity->HrefValue = "";

			// est_cost
			$this->est_cost->HrefValue = "";
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
		if (!$this->wegs_id->FldIsDetailKey && !is_null($this->wegs_id->FormValue) && $this->wegs_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->wegs_id->FldCaption(), $this->wegs_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->est_quantity->FormValue)) {
			ew_AddMessage($gsFormError, $this->est_quantity->FldErrMsg());
		}
		if (!ew_CheckInteger($this->est_cost->FormValue)) {
			ew_AddMessage($gsFormError, $this->est_cost->FldErrMsg());
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

			// wegs_id
			// fk_wegs

			$this->fk_wegs->SetDbValueDef($rsnew, $this->fk_wegs->CurrentValue, NULL, $this->fk_wegs->ReadOnly);

			// item
			$this->item->SetDbValueDef($rsnew, $this->item->CurrentValue, NULL, $this->item->ReadOnly);

			// est_quantity
			$this->est_quantity->SetDbValueDef($rsnew, $this->est_quantity->CurrentValue, NULL, $this->est_quantity->ReadOnly);

			// est_cost
			$this->est_cost->SetDbValueDef($rsnew, $this->est_cost->CurrentValue, NULL, $this->est_cost->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "we_good_standardlist.php", "", $this->TableVar, TRUE);
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
if (!isset($we_good_standard_edit)) $we_good_standard_edit = new cwe_good_standard_edit();

// Page init
$we_good_standard_edit->Page_Init();

// Page main
$we_good_standard_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$we_good_standard_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var we_good_standard_edit = new ew_Page("we_good_standard_edit");
we_good_standard_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = we_good_standard_edit.PageID; // For backward compatibility

// Form object
var fwe_good_standardedit = new ew_Form("fwe_good_standardedit");

// Validate form
fwe_good_standardedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_wegs_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $we_good_standard->wegs_id->FldCaption(), $we_good_standard->wegs_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_est_quantity");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($we_good_standard->est_quantity->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_est_cost");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($we_good_standard->est_cost->FldErrMsg()) ?>");

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
fwe_good_standardedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwe_good_standardedit.ValidateRequired = true;
<?php } else { ?>
fwe_good_standardedit.ValidateRequired = false; 
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
<?php $we_good_standard_edit->ShowPageHeader(); ?>
<?php
$we_good_standard_edit->ShowMessage();
?>
<form name="fwe_good_standardedit" id="fwe_good_standardedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($we_good_standard_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $we_good_standard_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="we_good_standard">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($we_good_standard->wegs_id->Visible) { // wegs_id ?>
	<div id="r_wegs_id" class="form-group">
		<label id="elh_we_good_standard_wegs_id" for="x_wegs_id" class="col-sm-2 control-label ewLabel"><?php echo $we_good_standard->wegs_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $we_good_standard->wegs_id->CellAttributes() ?>>
<span id="el_we_good_standard_wegs_id">
<span<?php echo $we_good_standard->wegs_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $we_good_standard->wegs_id->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_wegs_id" name="x_wegs_id" id="x_wegs_id" value="<?php echo ew_HtmlEncode($we_good_standard->wegs_id->CurrentValue) ?>">
<?php echo $we_good_standard->wegs_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_good_standard->fk_wegs->Visible) { // fk_wegs ?>
	<div id="r_fk_wegs" class="form-group">
		<label id="elh_we_good_standard_fk_wegs" for="x_fk_wegs" class="col-sm-2 control-label ewLabel"><?php echo $we_good_standard->fk_wegs->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_good_standard->fk_wegs->CellAttributes() ?>>
<span id="el_we_good_standard_fk_wegs">
<input type="text" data-field="x_fk_wegs" name="x_fk_wegs" id="x_fk_wegs" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_good_standard->fk_wegs->PlaceHolder) ?>" value="<?php echo $we_good_standard->fk_wegs->EditValue ?>"<?php echo $we_good_standard->fk_wegs->EditAttributes() ?>>
</span>
<?php echo $we_good_standard->fk_wegs->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_good_standard->item->Visible) { // item ?>
	<div id="r_item" class="form-group">
		<label id="elh_we_good_standard_item" for="x_item" class="col-sm-2 control-label ewLabel"><?php echo $we_good_standard->item->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_good_standard->item->CellAttributes() ?>>
<span id="el_we_good_standard_item">
<input type="text" data-field="x_item" name="x_item" id="x_item" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($we_good_standard->item->PlaceHolder) ?>" value="<?php echo $we_good_standard->item->EditValue ?>"<?php echo $we_good_standard->item->EditAttributes() ?>>
</span>
<?php echo $we_good_standard->item->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_good_standard->est_quantity->Visible) { // est_quantity ?>
	<div id="r_est_quantity" class="form-group">
		<label id="elh_we_good_standard_est_quantity" for="x_est_quantity" class="col-sm-2 control-label ewLabel"><?php echo $we_good_standard->est_quantity->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_good_standard->est_quantity->CellAttributes() ?>>
<span id="el_we_good_standard_est_quantity">
<input type="text" data-field="x_est_quantity" name="x_est_quantity" id="x_est_quantity" size="30" placeholder="<?php echo ew_HtmlEncode($we_good_standard->est_quantity->PlaceHolder) ?>" value="<?php echo $we_good_standard->est_quantity->EditValue ?>"<?php echo $we_good_standard->est_quantity->EditAttributes() ?>>
</span>
<?php echo $we_good_standard->est_quantity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_good_standard->est_cost->Visible) { // est_cost ?>
	<div id="r_est_cost" class="form-group">
		<label id="elh_we_good_standard_est_cost" for="x_est_cost" class="col-sm-2 control-label ewLabel"><?php echo $we_good_standard->est_cost->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_good_standard->est_cost->CellAttributes() ?>>
<span id="el_we_good_standard_est_cost">
<input type="text" data-field="x_est_cost" name="x_est_cost" id="x_est_cost" size="30" placeholder="<?php echo ew_HtmlEncode($we_good_standard->est_cost->PlaceHolder) ?>" value="<?php echo $we_good_standard->est_cost->EditValue ?>"<?php echo $we_good_standard->est_cost->EditAttributes() ?>>
</span>
<?php echo $we_good_standard->est_cost->CustomMsg ?></div></div>
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
fwe_good_standardedit.Init();
</script>
<?php
$we_good_standard_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$we_good_standard_edit->Page_Terminate();
?>
