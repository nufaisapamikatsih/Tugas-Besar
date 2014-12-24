<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "party_faainfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$party_faa_edit = NULL; // Initialize page object first

class cparty_faa_edit extends cparty_faa {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'party_faa';

	// Page object name
	var $PageObjName = 'party_faa_edit';

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

		// Table object (party_faa)
		if (!isset($GLOBALS["party_faa"]) || get_class($GLOBALS["party_faa"]) == "cparty_faa") {
			$GLOBALS["party_faa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["party_faa"];
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
			define("EW_TABLE_NAME", 'party_faa', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("party_faalist.php"));
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
		global $EW_EXPORT, $party_faa;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($party_faa);
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
		if (@$_GET["pfaa_id"] <> "") {
			$this->pfaa_id->setQueryStringValue($_GET["pfaa_id"]);
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
		if ($this->pfaa_id->CurrentValue == "")
			$this->Page_Terminate("party_faalist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("party_faalist.php"); // No matching record, return to list
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
		if (!$this->pfaa_id->FldIsDetailKey) {
			$this->pfaa_id->setFormValue($objForm->GetValue("x_pfaa_id"));
		}
		if (!$this->fk_pfaa->FldIsDetailKey) {
			$this->fk_pfaa->setFormValue($objForm->GetValue("x_fk_pfaa"));
		}
		if (!$this->fk_pfaa2->FldIsDetailKey) {
			$this->fk_pfaa2->setFormValue($objForm->GetValue("x_fk_pfaa2"));
		}
		if (!$this->start_date->FldIsDetailKey) {
			$this->start_date->setFormValue($objForm->GetValue("x_start_date"));
		}
		if (!$this->end_date->FldIsDetailKey) {
			$this->end_date->setFormValue($objForm->GetValue("x_end_date"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->pfaa_id->CurrentValue = $this->pfaa_id->FormValue;
		$this->fk_pfaa->CurrentValue = $this->fk_pfaa->FormValue;
		$this->fk_pfaa2->CurrentValue = $this->fk_pfaa2->FormValue;
		$this->start_date->CurrentValue = $this->start_date->FormValue;
		$this->end_date->CurrentValue = $this->end_date->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
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
		$this->pfaa_id->setDbValue($rs->fields('pfaa_id'));
		$this->fk_pfaa->setDbValue($rs->fields('fk_pfaa'));
		$this->fk_pfaa2->setDbValue($rs->fields('fk_pfaa2'));
		$this->start_date->setDbValue($rs->fields('start_date'));
		$this->end_date->setDbValue($rs->fields('end_date'));
		$this->status->setDbValue($rs->fields('status'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pfaa_id->DbValue = $row['pfaa_id'];
		$this->fk_pfaa->DbValue = $row['fk_pfaa'];
		$this->fk_pfaa2->DbValue = $row['fk_pfaa2'];
		$this->start_date->DbValue = $row['start_date'];
		$this->end_date->DbValue = $row['end_date'];
		$this->status->DbValue = $row['status'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// pfaa_id
		// fk_pfaa
		// fk_pfaa2
		// start_date
		// end_date
		// status

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// pfaa_id
			$this->pfaa_id->ViewValue = $this->pfaa_id->CurrentValue;
			$this->pfaa_id->ViewCustomAttributes = "";

			// fk_pfaa
			$this->fk_pfaa->ViewValue = $this->fk_pfaa->CurrentValue;
			$this->fk_pfaa->ViewCustomAttributes = "";

			// fk_pfaa2
			$this->fk_pfaa2->ViewValue = $this->fk_pfaa2->CurrentValue;
			$this->fk_pfaa2->ViewCustomAttributes = "";

			// start_date
			$this->start_date->ViewValue = $this->start_date->CurrentValue;
			$this->start_date->ViewCustomAttributes = "";

			// end_date
			$this->end_date->ViewValue = $this->end_date->CurrentValue;
			$this->end_date->ViewCustomAttributes = "";

			// status
			$this->status->ViewValue = $this->status->CurrentValue;
			$this->status->ViewCustomAttributes = "";

			// pfaa_id
			$this->pfaa_id->LinkCustomAttributes = "";
			$this->pfaa_id->HrefValue = "";
			$this->pfaa_id->TooltipValue = "";

			// fk_pfaa
			$this->fk_pfaa->LinkCustomAttributes = "";
			$this->fk_pfaa->HrefValue = "";
			$this->fk_pfaa->TooltipValue = "";

			// fk_pfaa2
			$this->fk_pfaa2->LinkCustomAttributes = "";
			$this->fk_pfaa2->HrefValue = "";
			$this->fk_pfaa2->TooltipValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
			$this->start_date->TooltipValue = "";

			// end_date
			$this->end_date->LinkCustomAttributes = "";
			$this->end_date->HrefValue = "";
			$this->end_date->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// pfaa_id
			$this->pfaa_id->EditAttrs["class"] = "form-control";
			$this->pfaa_id->EditCustomAttributes = "";
			$this->pfaa_id->EditValue = $this->pfaa_id->CurrentValue;
			$this->pfaa_id->ViewCustomAttributes = "";

			// fk_pfaa
			$this->fk_pfaa->EditAttrs["class"] = "form-control";
			$this->fk_pfaa->EditCustomAttributes = "";
			$this->fk_pfaa->EditValue = ew_HtmlEncode($this->fk_pfaa->CurrentValue);
			$this->fk_pfaa->PlaceHolder = ew_RemoveHtml($this->fk_pfaa->FldCaption());

			// fk_pfaa2
			$this->fk_pfaa2->EditAttrs["class"] = "form-control";
			$this->fk_pfaa2->EditCustomAttributes = "";
			$this->fk_pfaa2->EditValue = ew_HtmlEncode($this->fk_pfaa2->CurrentValue);
			$this->fk_pfaa2->PlaceHolder = ew_RemoveHtml($this->fk_pfaa2->FldCaption());

			// start_date
			$this->start_date->EditAttrs["class"] = "form-control";
			$this->start_date->EditCustomAttributes = "";
			$this->start_date->EditValue = ew_HtmlEncode($this->start_date->CurrentValue);
			$this->start_date->PlaceHolder = ew_RemoveHtml($this->start_date->FldCaption());

			// end_date
			$this->end_date->EditAttrs["class"] = "form-control";
			$this->end_date->EditCustomAttributes = "";
			$this->end_date->EditValue = ew_HtmlEncode($this->end_date->CurrentValue);
			$this->end_date->PlaceHolder = ew_RemoveHtml($this->end_date->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// Edit refer script
			// pfaa_id

			$this->pfaa_id->HrefValue = "";

			// fk_pfaa
			$this->fk_pfaa->HrefValue = "";

			// fk_pfaa2
			$this->fk_pfaa2->HrefValue = "";

			// start_date
			$this->start_date->HrefValue = "";

			// end_date
			$this->end_date->HrefValue = "";

			// status
			$this->status->HrefValue = "";
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
		if (!$this->pfaa_id->FldIsDetailKey && !is_null($this->pfaa_id->FormValue) && $this->pfaa_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pfaa_id->FldCaption(), $this->pfaa_id->ReqErrMsg));
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

			// pfaa_id
			// fk_pfaa

			$this->fk_pfaa->SetDbValueDef($rsnew, $this->fk_pfaa->CurrentValue, NULL, $this->fk_pfaa->ReadOnly);

			// fk_pfaa2
			$this->fk_pfaa2->SetDbValueDef($rsnew, $this->fk_pfaa2->CurrentValue, NULL, $this->fk_pfaa2->ReadOnly);

			// start_date
			$this->start_date->SetDbValueDef($rsnew, $this->start_date->CurrentValue, NULL, $this->start_date->ReadOnly);

			// end_date
			$this->end_date->SetDbValueDef($rsnew, $this->end_date->CurrentValue, NULL, $this->end_date->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "party_faalist.php", "", $this->TableVar, TRUE);
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
if (!isset($party_faa_edit)) $party_faa_edit = new cparty_faa_edit();

// Page init
$party_faa_edit->Page_Init();

// Page main
$party_faa_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$party_faa_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var party_faa_edit = new ew_Page("party_faa_edit");
party_faa_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = party_faa_edit.PageID; // For backward compatibility

// Form object
var fparty_faaedit = new ew_Form("fparty_faaedit");

// Validate form
fparty_faaedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_pfaa_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $party_faa->pfaa_id->FldCaption(), $party_faa->pfaa_id->ReqErrMsg)) ?>");

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
fparty_faaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparty_faaedit.ValidateRequired = true;
<?php } else { ?>
fparty_faaedit.ValidateRequired = false; 
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
<?php $party_faa_edit->ShowPageHeader(); ?>
<?php
$party_faa_edit->ShowMessage();
?>
<form name="fparty_faaedit" id="fparty_faaedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($party_faa_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $party_faa_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="party_faa">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($party_faa->pfaa_id->Visible) { // pfaa_id ?>
	<div id="r_pfaa_id" class="form-group">
		<label id="elh_party_faa_pfaa_id" for="x_pfaa_id" class="col-sm-2 control-label ewLabel"><?php echo $party_faa->pfaa_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $party_faa->pfaa_id->CellAttributes() ?>>
<span id="el_party_faa_pfaa_id">
<span<?php echo $party_faa->pfaa_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $party_faa->pfaa_id->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_pfaa_id" name="x_pfaa_id" id="x_pfaa_id" value="<?php echo ew_HtmlEncode($party_faa->pfaa_id->CurrentValue) ?>">
<?php echo $party_faa->pfaa_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($party_faa->fk_pfaa->Visible) { // fk_pfaa ?>
	<div id="r_fk_pfaa" class="form-group">
		<label id="elh_party_faa_fk_pfaa" for="x_fk_pfaa" class="col-sm-2 control-label ewLabel"><?php echo $party_faa->fk_pfaa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $party_faa->fk_pfaa->CellAttributes() ?>>
<span id="el_party_faa_fk_pfaa">
<input type="text" data-field="x_fk_pfaa" name="x_fk_pfaa" id="x_fk_pfaa" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($party_faa->fk_pfaa->PlaceHolder) ?>" value="<?php echo $party_faa->fk_pfaa->EditValue ?>"<?php echo $party_faa->fk_pfaa->EditAttributes() ?>>
</span>
<?php echo $party_faa->fk_pfaa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($party_faa->fk_pfaa2->Visible) { // fk_pfaa2 ?>
	<div id="r_fk_pfaa2" class="form-group">
		<label id="elh_party_faa_fk_pfaa2" for="x_fk_pfaa2" class="col-sm-2 control-label ewLabel"><?php echo $party_faa->fk_pfaa2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $party_faa->fk_pfaa2->CellAttributes() ?>>
<span id="el_party_faa_fk_pfaa2">
<input type="text" data-field="x_fk_pfaa2" name="x_fk_pfaa2" id="x_fk_pfaa2" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($party_faa->fk_pfaa2->PlaceHolder) ?>" value="<?php echo $party_faa->fk_pfaa2->EditValue ?>"<?php echo $party_faa->fk_pfaa2->EditAttributes() ?>>
</span>
<?php echo $party_faa->fk_pfaa2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($party_faa->start_date->Visible) { // start_date ?>
	<div id="r_start_date" class="form-group">
		<label id="elh_party_faa_start_date" for="x_start_date" class="col-sm-2 control-label ewLabel"><?php echo $party_faa->start_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $party_faa->start_date->CellAttributes() ?>>
<span id="el_party_faa_start_date">
<input type="text" data-field="x_start_date" name="x_start_date" id="x_start_date" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($party_faa->start_date->PlaceHolder) ?>" value="<?php echo $party_faa->start_date->EditValue ?>"<?php echo $party_faa->start_date->EditAttributes() ?>>
</span>
<?php echo $party_faa->start_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($party_faa->end_date->Visible) { // end_date ?>
	<div id="r_end_date" class="form-group">
		<label id="elh_party_faa_end_date" for="x_end_date" class="col-sm-2 control-label ewLabel"><?php echo $party_faa->end_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $party_faa->end_date->CellAttributes() ?>>
<span id="el_party_faa_end_date">
<input type="text" data-field="x_end_date" name="x_end_date" id="x_end_date" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($party_faa->end_date->PlaceHolder) ?>" value="<?php echo $party_faa->end_date->EditValue ?>"<?php echo $party_faa->end_date->EditAttributes() ?>>
</span>
<?php echo $party_faa->end_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($party_faa->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_party_faa_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $party_faa->status->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $party_faa->status->CellAttributes() ?>>
<span id="el_party_faa_status">
<input type="text" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($party_faa->status->PlaceHolder) ?>" value="<?php echo $party_faa->status->EditValue ?>"<?php echo $party_faa->status->EditAttributes() ?>>
</span>
<?php echo $party_faa->status->CustomMsg ?></div></div>
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
fparty_faaedit.Init();
</script>
<?php
$party_faa_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$party_faa_edit->Page_Terminate();
?>
