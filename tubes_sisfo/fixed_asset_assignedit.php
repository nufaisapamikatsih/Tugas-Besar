<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "fixed_asset_assigninfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$fixed_asset_assign_edit = NULL; // Initialize page object first

class cfixed_asset_assign_edit extends cfixed_asset_assign {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'fixed_asset_assign';

	// Page object name
	var $PageObjName = 'fixed_asset_assign_edit';

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

		// Table object (fixed_asset_assign)
		if (!isset($GLOBALS["fixed_asset_assign"]) || get_class($GLOBALS["fixed_asset_assign"]) == "cfixed_asset_assign") {
			$GLOBALS["fixed_asset_assign"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["fixed_asset_assign"];
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
			define("EW_TABLE_NAME", 'fixed_asset_assign', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("fixed_asset_assignlist.php"));
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
		global $EW_EXPORT, $fixed_asset_assign;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($fixed_asset_assign);
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
		if (@$_GET["faa_id"] <> "") {
			$this->faa_id->setQueryStringValue($_GET["faa_id"]);
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
		if ($this->faa_id->CurrentValue == "")
			$this->Page_Terminate("fixed_asset_assignlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("fixed_asset_assignlist.php"); // No matching record, return to list
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
		if (!$this->faa_id->FldIsDetailKey) {
			$this->faa_id->setFormValue($objForm->GetValue("x_faa_id"));
		}
		if (!$this->fk_faa->FldIsDetailKey) {
			$this->fk_faa->setFormValue($objForm->GetValue("x_fk_faa"));
		}
		if (!$this->fk_faa2->FldIsDetailKey) {
			$this->fk_faa2->setFormValue($objForm->GetValue("x_fk_faa2"));
		}
		if (!$this->from_date->FldIsDetailKey) {
			$this->from_date->setFormValue($objForm->GetValue("x_from_date"));
		}
		if (!$this->thru_date->FldIsDetailKey) {
			$this->thru_date->setFormValue($objForm->GetValue("x_thru_date"));
		}
		if (!$this->comm->FldIsDetailKey) {
			$this->comm->setFormValue($objForm->GetValue("x_comm"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->faa_id->CurrentValue = $this->faa_id->FormValue;
		$this->fk_faa->CurrentValue = $this->fk_faa->FormValue;
		$this->fk_faa2->CurrentValue = $this->fk_faa2->FormValue;
		$this->from_date->CurrentValue = $this->from_date->FormValue;
		$this->thru_date->CurrentValue = $this->thru_date->FormValue;
		$this->comm->CurrentValue = $this->comm->FormValue;
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
		$this->faa_id->setDbValue($rs->fields('faa_id'));
		$this->fk_faa->setDbValue($rs->fields('fk_faa'));
		$this->fk_faa2->setDbValue($rs->fields('fk_faa2'));
		$this->from_date->setDbValue($rs->fields('from_date'));
		$this->thru_date->setDbValue($rs->fields('thru_date'));
		$this->comm->setDbValue($rs->fields('comm'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->faa_id->DbValue = $row['faa_id'];
		$this->fk_faa->DbValue = $row['fk_faa'];
		$this->fk_faa2->DbValue = $row['fk_faa2'];
		$this->from_date->DbValue = $row['from_date'];
		$this->thru_date->DbValue = $row['thru_date'];
		$this->comm->DbValue = $row['comm'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// faa_id
		// fk_faa
		// fk_faa2
		// from_date
		// thru_date
		// comm

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// faa_id
			$this->faa_id->ViewValue = $this->faa_id->CurrentValue;
			$this->faa_id->ViewCustomAttributes = "";

			// fk_faa
			$this->fk_faa->ViewValue = $this->fk_faa->CurrentValue;
			$this->fk_faa->ViewCustomAttributes = "";

			// fk_faa2
			$this->fk_faa2->ViewValue = $this->fk_faa2->CurrentValue;
			$this->fk_faa2->ViewCustomAttributes = "";

			// from_date
			$this->from_date->ViewValue = $this->from_date->CurrentValue;
			$this->from_date->ViewCustomAttributes = "";

			// thru_date
			$this->thru_date->ViewValue = $this->thru_date->CurrentValue;
			$this->thru_date->ViewCustomAttributes = "";

			// comm
			$this->comm->ViewValue = $this->comm->CurrentValue;
			$this->comm->ViewCustomAttributes = "";

			// faa_id
			$this->faa_id->LinkCustomAttributes = "";
			$this->faa_id->HrefValue = "";
			$this->faa_id->TooltipValue = "";

			// fk_faa
			$this->fk_faa->LinkCustomAttributes = "";
			$this->fk_faa->HrefValue = "";
			$this->fk_faa->TooltipValue = "";

			// fk_faa2
			$this->fk_faa2->LinkCustomAttributes = "";
			$this->fk_faa2->HrefValue = "";
			$this->fk_faa2->TooltipValue = "";

			// from_date
			$this->from_date->LinkCustomAttributes = "";
			$this->from_date->HrefValue = "";
			$this->from_date->TooltipValue = "";

			// thru_date
			$this->thru_date->LinkCustomAttributes = "";
			$this->thru_date->HrefValue = "";
			$this->thru_date->TooltipValue = "";

			// comm
			$this->comm->LinkCustomAttributes = "";
			$this->comm->HrefValue = "";
			$this->comm->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// faa_id
			$this->faa_id->EditAttrs["class"] = "form-control";
			$this->faa_id->EditCustomAttributes = "";
			$this->faa_id->EditValue = $this->faa_id->CurrentValue;
			$this->faa_id->ViewCustomAttributes = "";

			// fk_faa
			$this->fk_faa->EditAttrs["class"] = "form-control";
			$this->fk_faa->EditCustomAttributes = "";
			$this->fk_faa->EditValue = ew_HtmlEncode($this->fk_faa->CurrentValue);
			$this->fk_faa->PlaceHolder = ew_RemoveHtml($this->fk_faa->FldCaption());

			// fk_faa2
			$this->fk_faa2->EditAttrs["class"] = "form-control";
			$this->fk_faa2->EditCustomAttributes = "";
			$this->fk_faa2->EditValue = ew_HtmlEncode($this->fk_faa2->CurrentValue);
			$this->fk_faa2->PlaceHolder = ew_RemoveHtml($this->fk_faa2->FldCaption());

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

			// comm
			$this->comm->EditAttrs["class"] = "form-control";
			$this->comm->EditCustomAttributes = "";
			$this->comm->EditValue = ew_HtmlEncode($this->comm->CurrentValue);
			$this->comm->PlaceHolder = ew_RemoveHtml($this->comm->FldCaption());

			// Edit refer script
			// faa_id

			$this->faa_id->HrefValue = "";

			// fk_faa
			$this->fk_faa->HrefValue = "";

			// fk_faa2
			$this->fk_faa2->HrefValue = "";

			// from_date
			$this->from_date->HrefValue = "";

			// thru_date
			$this->thru_date->HrefValue = "";

			// comm
			$this->comm->HrefValue = "";
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
		if (!$this->faa_id->FldIsDetailKey && !is_null($this->faa_id->FormValue) && $this->faa_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->faa_id->FldCaption(), $this->faa_id->ReqErrMsg));
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

			// faa_id
			// fk_faa

			$this->fk_faa->SetDbValueDef($rsnew, $this->fk_faa->CurrentValue, NULL, $this->fk_faa->ReadOnly);

			// fk_faa2
			$this->fk_faa2->SetDbValueDef($rsnew, $this->fk_faa2->CurrentValue, NULL, $this->fk_faa2->ReadOnly);

			// from_date
			$this->from_date->SetDbValueDef($rsnew, $this->from_date->CurrentValue, NULL, $this->from_date->ReadOnly);

			// thru_date
			$this->thru_date->SetDbValueDef($rsnew, $this->thru_date->CurrentValue, NULL, $this->thru_date->ReadOnly);

			// comm
			$this->comm->SetDbValueDef($rsnew, $this->comm->CurrentValue, NULL, $this->comm->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "fixed_asset_assignlist.php", "", $this->TableVar, TRUE);
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
if (!isset($fixed_asset_assign_edit)) $fixed_asset_assign_edit = new cfixed_asset_assign_edit();

// Page init
$fixed_asset_assign_edit->Page_Init();

// Page main
$fixed_asset_assign_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$fixed_asset_assign_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var fixed_asset_assign_edit = new ew_Page("fixed_asset_assign_edit");
fixed_asset_assign_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = fixed_asset_assign_edit.PageID; // For backward compatibility

// Form object
var ffixed_asset_assignedit = new ew_Form("ffixed_asset_assignedit");

// Validate form
ffixed_asset_assignedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_faa_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $fixed_asset_assign->faa_id->FldCaption(), $fixed_asset_assign->faa_id->ReqErrMsg)) ?>");

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
ffixed_asset_assignedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffixed_asset_assignedit.ValidateRequired = true;
<?php } else { ?>
ffixed_asset_assignedit.ValidateRequired = false; 
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
<?php $fixed_asset_assign_edit->ShowPageHeader(); ?>
<?php
$fixed_asset_assign_edit->ShowMessage();
?>
<form name="ffixed_asset_assignedit" id="ffixed_asset_assignedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($fixed_asset_assign_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $fixed_asset_assign_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="fixed_asset_assign">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($fixed_asset_assign->faa_id->Visible) { // faa_id ?>
	<div id="r_faa_id" class="form-group">
		<label id="elh_fixed_asset_assign_faa_id" for="x_faa_id" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset_assign->faa_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset_assign->faa_id->CellAttributes() ?>>
<span id="el_fixed_asset_assign_faa_id">
<span<?php echo $fixed_asset_assign->faa_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $fixed_asset_assign->faa_id->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_faa_id" name="x_faa_id" id="x_faa_id" value="<?php echo ew_HtmlEncode($fixed_asset_assign->faa_id->CurrentValue) ?>">
<?php echo $fixed_asset_assign->faa_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fixed_asset_assign->fk_faa->Visible) { // fk_faa ?>
	<div id="r_fk_faa" class="form-group">
		<label id="elh_fixed_asset_assign_fk_faa" for="x_fk_faa" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset_assign->fk_faa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset_assign->fk_faa->CellAttributes() ?>>
<span id="el_fixed_asset_assign_fk_faa">
<input type="text" data-field="x_fk_faa" name="x_fk_faa" id="x_fk_faa" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($fixed_asset_assign->fk_faa->PlaceHolder) ?>" value="<?php echo $fixed_asset_assign->fk_faa->EditValue ?>"<?php echo $fixed_asset_assign->fk_faa->EditAttributes() ?>>
</span>
<?php echo $fixed_asset_assign->fk_faa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fixed_asset_assign->fk_faa2->Visible) { // fk_faa2 ?>
	<div id="r_fk_faa2" class="form-group">
		<label id="elh_fixed_asset_assign_fk_faa2" for="x_fk_faa2" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset_assign->fk_faa2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset_assign->fk_faa2->CellAttributes() ?>>
<span id="el_fixed_asset_assign_fk_faa2">
<input type="text" data-field="x_fk_faa2" name="x_fk_faa2" id="x_fk_faa2" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($fixed_asset_assign->fk_faa2->PlaceHolder) ?>" value="<?php echo $fixed_asset_assign->fk_faa2->EditValue ?>"<?php echo $fixed_asset_assign->fk_faa2->EditAttributes() ?>>
</span>
<?php echo $fixed_asset_assign->fk_faa2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fixed_asset_assign->from_date->Visible) { // from_date ?>
	<div id="r_from_date" class="form-group">
		<label id="elh_fixed_asset_assign_from_date" for="x_from_date" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset_assign->from_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset_assign->from_date->CellAttributes() ?>>
<span id="el_fixed_asset_assign_from_date">
<input type="text" data-field="x_from_date" name="x_from_date" id="x_from_date" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($fixed_asset_assign->from_date->PlaceHolder) ?>" value="<?php echo $fixed_asset_assign->from_date->EditValue ?>"<?php echo $fixed_asset_assign->from_date->EditAttributes() ?>>
</span>
<?php echo $fixed_asset_assign->from_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fixed_asset_assign->thru_date->Visible) { // thru_date ?>
	<div id="r_thru_date" class="form-group">
		<label id="elh_fixed_asset_assign_thru_date" for="x_thru_date" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset_assign->thru_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset_assign->thru_date->CellAttributes() ?>>
<span id="el_fixed_asset_assign_thru_date">
<input type="text" data-field="x_thru_date" name="x_thru_date" id="x_thru_date" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($fixed_asset_assign->thru_date->PlaceHolder) ?>" value="<?php echo $fixed_asset_assign->thru_date->EditValue ?>"<?php echo $fixed_asset_assign->thru_date->EditAttributes() ?>>
</span>
<?php echo $fixed_asset_assign->thru_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fixed_asset_assign->comm->Visible) { // comm ?>
	<div id="r_comm" class="form-group">
		<label id="elh_fixed_asset_assign_comm" for="x_comm" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset_assign->comm->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset_assign->comm->CellAttributes() ?>>
<span id="el_fixed_asset_assign_comm">
<input type="text" data-field="x_comm" name="x_comm" id="x_comm" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($fixed_asset_assign->comm->PlaceHolder) ?>" value="<?php echo $fixed_asset_assign->comm->EditValue ?>"<?php echo $fixed_asset_assign->comm->EditAttributes() ?>>
</span>
<?php echo $fixed_asset_assign->comm->CustomMsg ?></div></div>
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
ffixed_asset_assignedit.Init();
</script>
<?php
$fixed_asset_assign_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$fixed_asset_assign_edit->Page_Terminate();
?>
