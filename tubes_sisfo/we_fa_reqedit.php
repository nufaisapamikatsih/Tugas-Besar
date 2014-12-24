<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "we_fa_reqinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$we_fa_req_edit = NULL; // Initialize page object first

class cwe_fa_req_edit extends cwe_fa_req {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'we_fa_req';

	// Page object name
	var $PageObjName = 'we_fa_req_edit';

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

		// Table object (we_fa_req)
		if (!isset($GLOBALS["we_fa_req"]) || get_class($GLOBALS["we_fa_req"]) == "cwe_fa_req") {
			$GLOBALS["we_fa_req"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["we_fa_req"];
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
			define("EW_TABLE_NAME", 'we_fa_req', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("we_fa_reqlist.php"));
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
		global $EW_EXPORT, $we_fa_req;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($we_fa_req);
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
		if (@$_GET["wefr_id"] <> "") {
			$this->wefr_id->setQueryStringValue($_GET["wefr_id"]);
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
		if ($this->wefr_id->CurrentValue == "")
			$this->Page_Terminate("we_fa_reqlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("we_fa_reqlist.php"); // No matching record, return to list
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
		if (!$this->wefr_id->FldIsDetailKey) {
			$this->wefr_id->setFormValue($objForm->GetValue("x_wefr_id"));
		}
		if (!$this->fk_wefr->FldIsDetailKey) {
			$this->fk_wefr->setFormValue($objForm->GetValue("x_fk_wefr"));
		}
		if (!$this->fk_wefr2->FldIsDetailKey) {
			$this->fk_wefr2->setFormValue($objForm->GetValue("x_fk_wefr2"));
		}
		if (!$this->est_quantity->FldIsDetailKey) {
			$this->est_quantity->setFormValue($objForm->GetValue("x_est_quantity"));
		}
		if (!$this->est_duration->FldIsDetailKey) {
			$this->est_duration->setFormValue($objForm->GetValue("x_est_duration"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->wefr_id->CurrentValue = $this->wefr_id->FormValue;
		$this->fk_wefr->CurrentValue = $this->fk_wefr->FormValue;
		$this->fk_wefr2->CurrentValue = $this->fk_wefr2->FormValue;
		$this->est_quantity->CurrentValue = $this->est_quantity->FormValue;
		$this->est_duration->CurrentValue = $this->est_duration->FormValue;
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
		$this->wefr_id->setDbValue($rs->fields('wefr_id'));
		$this->fk_wefr->setDbValue($rs->fields('fk_wefr'));
		$this->fk_wefr2->setDbValue($rs->fields('fk_wefr2'));
		$this->est_quantity->setDbValue($rs->fields('est_quantity'));
		$this->est_duration->setDbValue($rs->fields('est_duration'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->wefr_id->DbValue = $row['wefr_id'];
		$this->fk_wefr->DbValue = $row['fk_wefr'];
		$this->fk_wefr2->DbValue = $row['fk_wefr2'];
		$this->est_quantity->DbValue = $row['est_quantity'];
		$this->est_duration->DbValue = $row['est_duration'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// wefr_id
		// fk_wefr
		// fk_wefr2
		// est_quantity
		// est_duration

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// wefr_id
			$this->wefr_id->ViewValue = $this->wefr_id->CurrentValue;
			$this->wefr_id->ViewCustomAttributes = "";

			// fk_wefr
			$this->fk_wefr->ViewValue = $this->fk_wefr->CurrentValue;
			$this->fk_wefr->ViewCustomAttributes = "";

			// fk_wefr2
			$this->fk_wefr2->ViewValue = $this->fk_wefr2->CurrentValue;
			$this->fk_wefr2->ViewCustomAttributes = "";

			// est_quantity
			$this->est_quantity->ViewValue = $this->est_quantity->CurrentValue;
			$this->est_quantity->ViewCustomAttributes = "";

			// est_duration
			$this->est_duration->ViewValue = $this->est_duration->CurrentValue;
			$this->est_duration->ViewCustomAttributes = "";

			// wefr_id
			$this->wefr_id->LinkCustomAttributes = "";
			$this->wefr_id->HrefValue = "";
			$this->wefr_id->TooltipValue = "";

			// fk_wefr
			$this->fk_wefr->LinkCustomAttributes = "";
			$this->fk_wefr->HrefValue = "";
			$this->fk_wefr->TooltipValue = "";

			// fk_wefr2
			$this->fk_wefr2->LinkCustomAttributes = "";
			$this->fk_wefr2->HrefValue = "";
			$this->fk_wefr2->TooltipValue = "";

			// est_quantity
			$this->est_quantity->LinkCustomAttributes = "";
			$this->est_quantity->HrefValue = "";
			$this->est_quantity->TooltipValue = "";

			// est_duration
			$this->est_duration->LinkCustomAttributes = "";
			$this->est_duration->HrefValue = "";
			$this->est_duration->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// wefr_id
			$this->wefr_id->EditAttrs["class"] = "form-control";
			$this->wefr_id->EditCustomAttributes = "";
			$this->wefr_id->EditValue = $this->wefr_id->CurrentValue;
			$this->wefr_id->ViewCustomAttributes = "";

			// fk_wefr
			$this->fk_wefr->EditAttrs["class"] = "form-control";
			$this->fk_wefr->EditCustomAttributes = "";
			$this->fk_wefr->EditValue = ew_HtmlEncode($this->fk_wefr->CurrentValue);
			$this->fk_wefr->PlaceHolder = ew_RemoveHtml($this->fk_wefr->FldCaption());

			// fk_wefr2
			$this->fk_wefr2->EditAttrs["class"] = "form-control";
			$this->fk_wefr2->EditCustomAttributes = "";
			$this->fk_wefr2->EditValue = ew_HtmlEncode($this->fk_wefr2->CurrentValue);
			$this->fk_wefr2->PlaceHolder = ew_RemoveHtml($this->fk_wefr2->FldCaption());

			// est_quantity
			$this->est_quantity->EditAttrs["class"] = "form-control";
			$this->est_quantity->EditCustomAttributes = "";
			$this->est_quantity->EditValue = ew_HtmlEncode($this->est_quantity->CurrentValue);
			$this->est_quantity->PlaceHolder = ew_RemoveHtml($this->est_quantity->FldCaption());

			// est_duration
			$this->est_duration->EditAttrs["class"] = "form-control";
			$this->est_duration->EditCustomAttributes = "";
			$this->est_duration->EditValue = ew_HtmlEncode($this->est_duration->CurrentValue);
			$this->est_duration->PlaceHolder = ew_RemoveHtml($this->est_duration->FldCaption());

			// Edit refer script
			// wefr_id

			$this->wefr_id->HrefValue = "";

			// fk_wefr
			$this->fk_wefr->HrefValue = "";

			// fk_wefr2
			$this->fk_wefr2->HrefValue = "";

			// est_quantity
			$this->est_quantity->HrefValue = "";

			// est_duration
			$this->est_duration->HrefValue = "";
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
		if (!$this->wefr_id->FldIsDetailKey && !is_null($this->wefr_id->FormValue) && $this->wefr_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->wefr_id->FldCaption(), $this->wefr_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->est_quantity->FormValue)) {
			ew_AddMessage($gsFormError, $this->est_quantity->FldErrMsg());
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

			// wefr_id
			// fk_wefr

			$this->fk_wefr->SetDbValueDef($rsnew, $this->fk_wefr->CurrentValue, NULL, $this->fk_wefr->ReadOnly);

			// fk_wefr2
			$this->fk_wefr2->SetDbValueDef($rsnew, $this->fk_wefr2->CurrentValue, NULL, $this->fk_wefr2->ReadOnly);

			// est_quantity
			$this->est_quantity->SetDbValueDef($rsnew, $this->est_quantity->CurrentValue, NULL, $this->est_quantity->ReadOnly);

			// est_duration
			$this->est_duration->SetDbValueDef($rsnew, $this->est_duration->CurrentValue, NULL, $this->est_duration->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "we_fa_reqlist.php", "", $this->TableVar, TRUE);
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
if (!isset($we_fa_req_edit)) $we_fa_req_edit = new cwe_fa_req_edit();

// Page init
$we_fa_req_edit->Page_Init();

// Page main
$we_fa_req_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$we_fa_req_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var we_fa_req_edit = new ew_Page("we_fa_req_edit");
we_fa_req_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = we_fa_req_edit.PageID; // For backward compatibility

// Form object
var fwe_fa_reqedit = new ew_Form("fwe_fa_reqedit");

// Validate form
fwe_fa_reqedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_wefr_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $we_fa_req->wefr_id->FldCaption(), $we_fa_req->wefr_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_est_quantity");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($we_fa_req->est_quantity->FldErrMsg()) ?>");

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
fwe_fa_reqedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwe_fa_reqedit.ValidateRequired = true;
<?php } else { ?>
fwe_fa_reqedit.ValidateRequired = false; 
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
<?php $we_fa_req_edit->ShowPageHeader(); ?>
<?php
$we_fa_req_edit->ShowMessage();
?>
<form name="fwe_fa_reqedit" id="fwe_fa_reqedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($we_fa_req_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $we_fa_req_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="we_fa_req">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($we_fa_req->wefr_id->Visible) { // wefr_id ?>
	<div id="r_wefr_id" class="form-group">
		<label id="elh_we_fa_req_wefr_id" for="x_wefr_id" class="col-sm-2 control-label ewLabel"><?php echo $we_fa_req->wefr_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $we_fa_req->wefr_id->CellAttributes() ?>>
<span id="el_we_fa_req_wefr_id">
<span<?php echo $we_fa_req->wefr_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $we_fa_req->wefr_id->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_wefr_id" name="x_wefr_id" id="x_wefr_id" value="<?php echo ew_HtmlEncode($we_fa_req->wefr_id->CurrentValue) ?>">
<?php echo $we_fa_req->wefr_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_fa_req->fk_wefr->Visible) { // fk_wefr ?>
	<div id="r_fk_wefr" class="form-group">
		<label id="elh_we_fa_req_fk_wefr" for="x_fk_wefr" class="col-sm-2 control-label ewLabel"><?php echo $we_fa_req->fk_wefr->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_fa_req->fk_wefr->CellAttributes() ?>>
<span id="el_we_fa_req_fk_wefr">
<input type="text" data-field="x_fk_wefr" name="x_fk_wefr" id="x_fk_wefr" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_fa_req->fk_wefr->PlaceHolder) ?>" value="<?php echo $we_fa_req->fk_wefr->EditValue ?>"<?php echo $we_fa_req->fk_wefr->EditAttributes() ?>>
</span>
<?php echo $we_fa_req->fk_wefr->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_fa_req->fk_wefr2->Visible) { // fk_wefr2 ?>
	<div id="r_fk_wefr2" class="form-group">
		<label id="elh_we_fa_req_fk_wefr2" for="x_fk_wefr2" class="col-sm-2 control-label ewLabel"><?php echo $we_fa_req->fk_wefr2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_fa_req->fk_wefr2->CellAttributes() ?>>
<span id="el_we_fa_req_fk_wefr2">
<input type="text" data-field="x_fk_wefr2" name="x_fk_wefr2" id="x_fk_wefr2" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($we_fa_req->fk_wefr2->PlaceHolder) ?>" value="<?php echo $we_fa_req->fk_wefr2->EditValue ?>"<?php echo $we_fa_req->fk_wefr2->EditAttributes() ?>>
</span>
<?php echo $we_fa_req->fk_wefr2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_fa_req->est_quantity->Visible) { // est_quantity ?>
	<div id="r_est_quantity" class="form-group">
		<label id="elh_we_fa_req_est_quantity" for="x_est_quantity" class="col-sm-2 control-label ewLabel"><?php echo $we_fa_req->est_quantity->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_fa_req->est_quantity->CellAttributes() ?>>
<span id="el_we_fa_req_est_quantity">
<input type="text" data-field="x_est_quantity" name="x_est_quantity" id="x_est_quantity" size="30" placeholder="<?php echo ew_HtmlEncode($we_fa_req->est_quantity->PlaceHolder) ?>" value="<?php echo $we_fa_req->est_quantity->EditValue ?>"<?php echo $we_fa_req->est_quantity->EditAttributes() ?>>
</span>
<?php echo $we_fa_req->est_quantity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($we_fa_req->est_duration->Visible) { // est_duration ?>
	<div id="r_est_duration" class="form-group">
		<label id="elh_we_fa_req_est_duration" for="x_est_duration" class="col-sm-2 control-label ewLabel"><?php echo $we_fa_req->est_duration->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $we_fa_req->est_duration->CellAttributes() ?>>
<span id="el_we_fa_req_est_duration">
<input type="text" data-field="x_est_duration" name="x_est_duration" id="x_est_duration" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($we_fa_req->est_duration->PlaceHolder) ?>" value="<?php echo $we_fa_req->est_duration->EditValue ?>"<?php echo $we_fa_req->est_duration->EditAttributes() ?>>
</span>
<?php echo $we_fa_req->est_duration->CustomMsg ?></div></div>
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
fwe_fa_reqedit.Init();
</script>
<?php
$we_fa_req_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$we_fa_req_edit->Page_Terminate();
?>
