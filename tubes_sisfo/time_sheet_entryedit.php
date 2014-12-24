<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "time_sheet_entryinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$time_sheet_entry_edit = NULL; // Initialize page object first

class ctime_sheet_entry_edit extends ctime_sheet_entry {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'time_sheet_entry';

	// Page object name
	var $PageObjName = 'time_sheet_entry_edit';

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

		// Table object (time_sheet_entry)
		if (!isset($GLOBALS["time_sheet_entry"]) || get_class($GLOBALS["time_sheet_entry"]) == "ctime_sheet_entry") {
			$GLOBALS["time_sheet_entry"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["time_sheet_entry"];
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
			define("EW_TABLE_NAME", 'time_sheet_entry', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("time_sheet_entrylist.php"));
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
		global $EW_EXPORT, $time_sheet_entry;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($time_sheet_entry);
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
		if (@$_GET["tse_id"] <> "") {
			$this->tse_id->setQueryStringValue($_GET["tse_id"]);
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
		if ($this->tse_id->CurrentValue == "")
			$this->Page_Terminate("time_sheet_entrylist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("time_sheet_entrylist.php"); // No matching record, return to list
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
		if (!$this->tse_id->FldIsDetailKey) {
			$this->tse_id->setFormValue($objForm->GetValue("x_tse_id"));
		}
		if (!$this->ts_from->FldIsDetailKey) {
			$this->ts_from->setFormValue($objForm->GetValue("x_ts_from"));
		}
		if (!$this->ts_thru->FldIsDetailKey) {
			$this->ts_thru->setFormValue($objForm->GetValue("x_ts_thru"));
		}
		if (!$this->fk_tse->FldIsDetailKey) {
			$this->fk_tse->setFormValue($objForm->GetValue("x_fk_tse"));
		}
		if (!$this->we_id->FldIsDetailKey) {
			$this->we_id->setFormValue($objForm->GetValue("x_we_id"));
		}
		if (!$this->te_from->FldIsDetailKey) {
			$this->te_from->setFormValue($objForm->GetValue("x_te_from"));
		}
		if (!$this->te_thru->FldIsDetailKey) {
			$this->te_thru->setFormValue($objForm->GetValue("x_te_thru"));
		}
		if (!$this->hours->FldIsDetailKey) {
			$this->hours->setFormValue($objForm->GetValue("x_hours"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->tse_id->CurrentValue = $this->tse_id->FormValue;
		$this->ts_from->CurrentValue = $this->ts_from->FormValue;
		$this->ts_thru->CurrentValue = $this->ts_thru->FormValue;
		$this->fk_tse->CurrentValue = $this->fk_tse->FormValue;
		$this->we_id->CurrentValue = $this->we_id->FormValue;
		$this->te_from->CurrentValue = $this->te_from->FormValue;
		$this->te_thru->CurrentValue = $this->te_thru->FormValue;
		$this->hours->CurrentValue = $this->hours->FormValue;
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
		$this->tse_id->setDbValue($rs->fields('tse_id'));
		$this->ts_from->setDbValue($rs->fields('ts_from'));
		$this->ts_thru->setDbValue($rs->fields('ts_thru'));
		$this->fk_tse->setDbValue($rs->fields('fk_tse'));
		$this->we_id->setDbValue($rs->fields('we_id'));
		$this->te_from->setDbValue($rs->fields('te_from'));
		$this->te_thru->setDbValue($rs->fields('te_thru'));
		$this->hours->setDbValue($rs->fields('hours'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->tse_id->DbValue = $row['tse_id'];
		$this->ts_from->DbValue = $row['ts_from'];
		$this->ts_thru->DbValue = $row['ts_thru'];
		$this->fk_tse->DbValue = $row['fk_tse'];
		$this->we_id->DbValue = $row['we_id'];
		$this->te_from->DbValue = $row['te_from'];
		$this->te_thru->DbValue = $row['te_thru'];
		$this->hours->DbValue = $row['hours'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// tse_id
		// ts_from
		// ts_thru
		// fk_tse
		// we_id
		// te_from
		// te_thru
		// hours

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// tse_id
			$this->tse_id->ViewValue = $this->tse_id->CurrentValue;
			$this->tse_id->ViewCustomAttributes = "";

			// ts_from
			$this->ts_from->ViewValue = $this->ts_from->CurrentValue;
			$this->ts_from->ViewCustomAttributes = "";

			// ts_thru
			$this->ts_thru->ViewValue = $this->ts_thru->CurrentValue;
			$this->ts_thru->ViewCustomAttributes = "";

			// fk_tse
			$this->fk_tse->ViewValue = $this->fk_tse->CurrentValue;
			$this->fk_tse->ViewCustomAttributes = "";

			// we_id
			$this->we_id->ViewValue = $this->we_id->CurrentValue;
			$this->we_id->ViewCustomAttributes = "";

			// te_from
			$this->te_from->ViewValue = $this->te_from->CurrentValue;
			$this->te_from->ViewCustomAttributes = "";

			// te_thru
			$this->te_thru->ViewValue = $this->te_thru->CurrentValue;
			$this->te_thru->ViewCustomAttributes = "";

			// hours
			$this->hours->ViewValue = $this->hours->CurrentValue;
			$this->hours->ViewCustomAttributes = "";

			// tse_id
			$this->tse_id->LinkCustomAttributes = "";
			$this->tse_id->HrefValue = "";
			$this->tse_id->TooltipValue = "";

			// ts_from
			$this->ts_from->LinkCustomAttributes = "";
			$this->ts_from->HrefValue = "";
			$this->ts_from->TooltipValue = "";

			// ts_thru
			$this->ts_thru->LinkCustomAttributes = "";
			$this->ts_thru->HrefValue = "";
			$this->ts_thru->TooltipValue = "";

			// fk_tse
			$this->fk_tse->LinkCustomAttributes = "";
			$this->fk_tse->HrefValue = "";
			$this->fk_tse->TooltipValue = "";

			// we_id
			$this->we_id->LinkCustomAttributes = "";
			$this->we_id->HrefValue = "";
			$this->we_id->TooltipValue = "";

			// te_from
			$this->te_from->LinkCustomAttributes = "";
			$this->te_from->HrefValue = "";
			$this->te_from->TooltipValue = "";

			// te_thru
			$this->te_thru->LinkCustomAttributes = "";
			$this->te_thru->HrefValue = "";
			$this->te_thru->TooltipValue = "";

			// hours
			$this->hours->LinkCustomAttributes = "";
			$this->hours->HrefValue = "";
			$this->hours->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// tse_id
			$this->tse_id->EditAttrs["class"] = "form-control";
			$this->tse_id->EditCustomAttributes = "";
			$this->tse_id->EditValue = $this->tse_id->CurrentValue;
			$this->tse_id->ViewCustomAttributes = "";

			// ts_from
			$this->ts_from->EditAttrs["class"] = "form-control";
			$this->ts_from->EditCustomAttributes = "";
			$this->ts_from->EditValue = ew_HtmlEncode($this->ts_from->CurrentValue);
			$this->ts_from->PlaceHolder = ew_RemoveHtml($this->ts_from->FldCaption());

			// ts_thru
			$this->ts_thru->EditAttrs["class"] = "form-control";
			$this->ts_thru->EditCustomAttributes = "";
			$this->ts_thru->EditValue = ew_HtmlEncode($this->ts_thru->CurrentValue);
			$this->ts_thru->PlaceHolder = ew_RemoveHtml($this->ts_thru->FldCaption());

			// fk_tse
			$this->fk_tse->EditAttrs["class"] = "form-control";
			$this->fk_tse->EditCustomAttributes = "";
			$this->fk_tse->EditValue = ew_HtmlEncode($this->fk_tse->CurrentValue);
			$this->fk_tse->PlaceHolder = ew_RemoveHtml($this->fk_tse->FldCaption());

			// we_id
			$this->we_id->EditAttrs["class"] = "form-control";
			$this->we_id->EditCustomAttributes = "";
			$this->we_id->EditValue = ew_HtmlEncode($this->we_id->CurrentValue);
			$this->we_id->PlaceHolder = ew_RemoveHtml($this->we_id->FldCaption());

			// te_from
			$this->te_from->EditAttrs["class"] = "form-control";
			$this->te_from->EditCustomAttributes = "";
			$this->te_from->EditValue = ew_HtmlEncode($this->te_from->CurrentValue);
			$this->te_from->PlaceHolder = ew_RemoveHtml($this->te_from->FldCaption());

			// te_thru
			$this->te_thru->EditAttrs["class"] = "form-control";
			$this->te_thru->EditCustomAttributes = "";
			$this->te_thru->EditValue = ew_HtmlEncode($this->te_thru->CurrentValue);
			$this->te_thru->PlaceHolder = ew_RemoveHtml($this->te_thru->FldCaption());

			// hours
			$this->hours->EditAttrs["class"] = "form-control";
			$this->hours->EditCustomAttributes = "";
			$this->hours->EditValue = ew_HtmlEncode($this->hours->CurrentValue);
			$this->hours->PlaceHolder = ew_RemoveHtml($this->hours->FldCaption());

			// Edit refer script
			// tse_id

			$this->tse_id->HrefValue = "";

			// ts_from
			$this->ts_from->HrefValue = "";

			// ts_thru
			$this->ts_thru->HrefValue = "";

			// fk_tse
			$this->fk_tse->HrefValue = "";

			// we_id
			$this->we_id->HrefValue = "";

			// te_from
			$this->te_from->HrefValue = "";

			// te_thru
			$this->te_thru->HrefValue = "";

			// hours
			$this->hours->HrefValue = "";
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
		if (!$this->tse_id->FldIsDetailKey && !is_null($this->tse_id->FormValue) && $this->tse_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tse_id->FldCaption(), $this->tse_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->hours->FormValue)) {
			ew_AddMessage($gsFormError, $this->hours->FldErrMsg());
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

			// tse_id
			// ts_from

			$this->ts_from->SetDbValueDef($rsnew, $this->ts_from->CurrentValue, NULL, $this->ts_from->ReadOnly);

			// ts_thru
			$this->ts_thru->SetDbValueDef($rsnew, $this->ts_thru->CurrentValue, NULL, $this->ts_thru->ReadOnly);

			// fk_tse
			$this->fk_tse->SetDbValueDef($rsnew, $this->fk_tse->CurrentValue, NULL, $this->fk_tse->ReadOnly);

			// we_id
			$this->we_id->SetDbValueDef($rsnew, $this->we_id->CurrentValue, NULL, $this->we_id->ReadOnly);

			// te_from
			$this->te_from->SetDbValueDef($rsnew, $this->te_from->CurrentValue, NULL, $this->te_from->ReadOnly);

			// te_thru
			$this->te_thru->SetDbValueDef($rsnew, $this->te_thru->CurrentValue, NULL, $this->te_thru->ReadOnly);

			// hours
			$this->hours->SetDbValueDef($rsnew, $this->hours->CurrentValue, NULL, $this->hours->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "time_sheet_entrylist.php", "", $this->TableVar, TRUE);
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
if (!isset($time_sheet_entry_edit)) $time_sheet_entry_edit = new ctime_sheet_entry_edit();

// Page init
$time_sheet_entry_edit->Page_Init();

// Page main
$time_sheet_entry_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$time_sheet_entry_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var time_sheet_entry_edit = new ew_Page("time_sheet_entry_edit");
time_sheet_entry_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = time_sheet_entry_edit.PageID; // For backward compatibility

// Form object
var ftime_sheet_entryedit = new ew_Form("ftime_sheet_entryedit");

// Validate form
ftime_sheet_entryedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_tse_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $time_sheet_entry->tse_id->FldCaption(), $time_sheet_entry->tse_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hours");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($time_sheet_entry->hours->FldErrMsg()) ?>");

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
ftime_sheet_entryedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftime_sheet_entryedit.ValidateRequired = true;
<?php } else { ?>
ftime_sheet_entryedit.ValidateRequired = false; 
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
<?php $time_sheet_entry_edit->ShowPageHeader(); ?>
<?php
$time_sheet_entry_edit->ShowMessage();
?>
<form name="ftime_sheet_entryedit" id="ftime_sheet_entryedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($time_sheet_entry_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $time_sheet_entry_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="time_sheet_entry">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($time_sheet_entry->tse_id->Visible) { // tse_id ?>
	<div id="r_tse_id" class="form-group">
		<label id="elh_time_sheet_entry_tse_id" for="x_tse_id" class="col-sm-2 control-label ewLabel"><?php echo $time_sheet_entry->tse_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $time_sheet_entry->tse_id->CellAttributes() ?>>
<span id="el_time_sheet_entry_tse_id">
<span<?php echo $time_sheet_entry->tse_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $time_sheet_entry->tse_id->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_tse_id" name="x_tse_id" id="x_tse_id" value="<?php echo ew_HtmlEncode($time_sheet_entry->tse_id->CurrentValue) ?>">
<?php echo $time_sheet_entry->tse_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($time_sheet_entry->ts_from->Visible) { // ts_from ?>
	<div id="r_ts_from" class="form-group">
		<label id="elh_time_sheet_entry_ts_from" for="x_ts_from" class="col-sm-2 control-label ewLabel"><?php echo $time_sheet_entry->ts_from->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $time_sheet_entry->ts_from->CellAttributes() ?>>
<span id="el_time_sheet_entry_ts_from">
<input type="text" data-field="x_ts_from" name="x_ts_from" id="x_ts_from" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($time_sheet_entry->ts_from->PlaceHolder) ?>" value="<?php echo $time_sheet_entry->ts_from->EditValue ?>"<?php echo $time_sheet_entry->ts_from->EditAttributes() ?>>
</span>
<?php echo $time_sheet_entry->ts_from->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($time_sheet_entry->ts_thru->Visible) { // ts_thru ?>
	<div id="r_ts_thru" class="form-group">
		<label id="elh_time_sheet_entry_ts_thru" for="x_ts_thru" class="col-sm-2 control-label ewLabel"><?php echo $time_sheet_entry->ts_thru->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $time_sheet_entry->ts_thru->CellAttributes() ?>>
<span id="el_time_sheet_entry_ts_thru">
<input type="text" data-field="x_ts_thru" name="x_ts_thru" id="x_ts_thru" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($time_sheet_entry->ts_thru->PlaceHolder) ?>" value="<?php echo $time_sheet_entry->ts_thru->EditValue ?>"<?php echo $time_sheet_entry->ts_thru->EditAttributes() ?>>
</span>
<?php echo $time_sheet_entry->ts_thru->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($time_sheet_entry->fk_tse->Visible) { // fk_tse ?>
	<div id="r_fk_tse" class="form-group">
		<label id="elh_time_sheet_entry_fk_tse" for="x_fk_tse" class="col-sm-2 control-label ewLabel"><?php echo $time_sheet_entry->fk_tse->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $time_sheet_entry->fk_tse->CellAttributes() ?>>
<span id="el_time_sheet_entry_fk_tse">
<input type="text" data-field="x_fk_tse" name="x_fk_tse" id="x_fk_tse" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($time_sheet_entry->fk_tse->PlaceHolder) ?>" value="<?php echo $time_sheet_entry->fk_tse->EditValue ?>"<?php echo $time_sheet_entry->fk_tse->EditAttributes() ?>>
</span>
<?php echo $time_sheet_entry->fk_tse->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($time_sheet_entry->we_id->Visible) { // we_id ?>
	<div id="r_we_id" class="form-group">
		<label id="elh_time_sheet_entry_we_id" for="x_we_id" class="col-sm-2 control-label ewLabel"><?php echo $time_sheet_entry->we_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $time_sheet_entry->we_id->CellAttributes() ?>>
<span id="el_time_sheet_entry_we_id">
<input type="text" data-field="x_we_id" name="x_we_id" id="x_we_id" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($time_sheet_entry->we_id->PlaceHolder) ?>" value="<?php echo $time_sheet_entry->we_id->EditValue ?>"<?php echo $time_sheet_entry->we_id->EditAttributes() ?>>
</span>
<?php echo $time_sheet_entry->we_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($time_sheet_entry->te_from->Visible) { // te_from ?>
	<div id="r_te_from" class="form-group">
		<label id="elh_time_sheet_entry_te_from" for="x_te_from" class="col-sm-2 control-label ewLabel"><?php echo $time_sheet_entry->te_from->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $time_sheet_entry->te_from->CellAttributes() ?>>
<span id="el_time_sheet_entry_te_from">
<input type="text" data-field="x_te_from" name="x_te_from" id="x_te_from" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($time_sheet_entry->te_from->PlaceHolder) ?>" value="<?php echo $time_sheet_entry->te_from->EditValue ?>"<?php echo $time_sheet_entry->te_from->EditAttributes() ?>>
</span>
<?php echo $time_sheet_entry->te_from->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($time_sheet_entry->te_thru->Visible) { // te_thru ?>
	<div id="r_te_thru" class="form-group">
		<label id="elh_time_sheet_entry_te_thru" for="x_te_thru" class="col-sm-2 control-label ewLabel"><?php echo $time_sheet_entry->te_thru->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $time_sheet_entry->te_thru->CellAttributes() ?>>
<span id="el_time_sheet_entry_te_thru">
<input type="text" data-field="x_te_thru" name="x_te_thru" id="x_te_thru" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($time_sheet_entry->te_thru->PlaceHolder) ?>" value="<?php echo $time_sheet_entry->te_thru->EditValue ?>"<?php echo $time_sheet_entry->te_thru->EditAttributes() ?>>
</span>
<?php echo $time_sheet_entry->te_thru->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($time_sheet_entry->hours->Visible) { // hours ?>
	<div id="r_hours" class="form-group">
		<label id="elh_time_sheet_entry_hours" for="x_hours" class="col-sm-2 control-label ewLabel"><?php echo $time_sheet_entry->hours->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $time_sheet_entry->hours->CellAttributes() ?>>
<span id="el_time_sheet_entry_hours">
<input type="text" data-field="x_hours" name="x_hours" id="x_hours" size="30" placeholder="<?php echo ew_HtmlEncode($time_sheet_entry->hours->PlaceHolder) ?>" value="<?php echo $time_sheet_entry->hours->EditValue ?>"<?php echo $time_sheet_entry->hours->EditAttributes() ?>>
</span>
<?php echo $time_sheet_entry->hours->CustomMsg ?></div></div>
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
ftime_sheet_entryedit.Init();
</script>
<?php
$time_sheet_entry_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$time_sheet_entry_edit->Page_Terminate();
?>
