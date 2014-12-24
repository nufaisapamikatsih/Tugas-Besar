<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "party_skill_datainfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$party_skill_data_add = NULL; // Initialize page object first

class cparty_skill_data_add extends cparty_skill_data {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'party_skill_data';

	// Page object name
	var $PageObjName = 'party_skill_data_add';

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

		// Table object (party_skill_data)
		if (!isset($GLOBALS["party_skill_data"]) || get_class($GLOBALS["party_skill_data"]) == "cparty_skill_data") {
			$GLOBALS["party_skill_data"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["party_skill_data"];
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
			define("EW_TABLE_NAME", 'party_skill_data', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("party_skill_datalist.php"));
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
		global $EW_EXPORT, $party_skill_data;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($party_skill_data);
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
			if (@$_GET["psd_id"] != "") {
				$this->psd_id->setQueryStringValue($_GET["psd_id"]);
				$this->setKey("psd_id", $this->psd_id->CurrentValue); // Set up key
			} else {
				$this->setKey("psd_id", ""); // Clear key
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
					$this->Page_Terminate("party_skill_datalist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "party_skill_dataview.php")
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
		$this->psd_id->CurrentValue = NULL;
		$this->psd_id->OldValue = $this->psd_id->CurrentValue;
		$this->fk_psd->CurrentValue = NULL;
		$this->fk_psd->OldValue = $this->fk_psd->CurrentValue;
		$this->skill_type->CurrentValue = NULL;
		$this->skill_type->OldValue = $this->skill_type->CurrentValue;
		$this->years_of_exp->CurrentValue = NULL;
		$this->years_of_exp->OldValue = $this->years_of_exp->CurrentValue;
		$this->rating->CurrentValue = NULL;
		$this->rating->OldValue = $this->rating->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->psd_id->FldIsDetailKey) {
			$this->psd_id->setFormValue($objForm->GetValue("x_psd_id"));
		}
		if (!$this->fk_psd->FldIsDetailKey) {
			$this->fk_psd->setFormValue($objForm->GetValue("x_fk_psd"));
		}
		if (!$this->skill_type->FldIsDetailKey) {
			$this->skill_type->setFormValue($objForm->GetValue("x_skill_type"));
		}
		if (!$this->years_of_exp->FldIsDetailKey) {
			$this->years_of_exp->setFormValue($objForm->GetValue("x_years_of_exp"));
		}
		if (!$this->rating->FldIsDetailKey) {
			$this->rating->setFormValue($objForm->GetValue("x_rating"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->psd_id->CurrentValue = $this->psd_id->FormValue;
		$this->fk_psd->CurrentValue = $this->fk_psd->FormValue;
		$this->skill_type->CurrentValue = $this->skill_type->FormValue;
		$this->years_of_exp->CurrentValue = $this->years_of_exp->FormValue;
		$this->rating->CurrentValue = $this->rating->FormValue;
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
		$this->psd_id->setDbValue($rs->fields('psd_id'));
		$this->fk_psd->setDbValue($rs->fields('fk_psd'));
		$this->skill_type->setDbValue($rs->fields('skill_type'));
		$this->years_of_exp->setDbValue($rs->fields('years_of_exp'));
		$this->rating->setDbValue($rs->fields('rating'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->psd_id->DbValue = $row['psd_id'];
		$this->fk_psd->DbValue = $row['fk_psd'];
		$this->skill_type->DbValue = $row['skill_type'];
		$this->years_of_exp->DbValue = $row['years_of_exp'];
		$this->rating->DbValue = $row['rating'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("psd_id")) <> "")
			$this->psd_id->CurrentValue = $this->getKey("psd_id"); // psd_id
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
		// psd_id
		// fk_psd
		// skill_type
		// years_of_exp
		// rating

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// psd_id
			$this->psd_id->ViewValue = $this->psd_id->CurrentValue;
			$this->psd_id->ViewCustomAttributes = "";

			// fk_psd
			$this->fk_psd->ViewValue = $this->fk_psd->CurrentValue;
			$this->fk_psd->ViewCustomAttributes = "";

			// skill_type
			$this->skill_type->ViewValue = $this->skill_type->CurrentValue;
			$this->skill_type->ViewCustomAttributes = "";

			// years_of_exp
			$this->years_of_exp->ViewValue = $this->years_of_exp->CurrentValue;
			$this->years_of_exp->ViewCustomAttributes = "";

			// rating
			$this->rating->ViewValue = $this->rating->CurrentValue;
			$this->rating->ViewCustomAttributes = "";

			// psd_id
			$this->psd_id->LinkCustomAttributes = "";
			$this->psd_id->HrefValue = "";
			$this->psd_id->TooltipValue = "";

			// fk_psd
			$this->fk_psd->LinkCustomAttributes = "";
			$this->fk_psd->HrefValue = "";
			$this->fk_psd->TooltipValue = "";

			// skill_type
			$this->skill_type->LinkCustomAttributes = "";
			$this->skill_type->HrefValue = "";
			$this->skill_type->TooltipValue = "";

			// years_of_exp
			$this->years_of_exp->LinkCustomAttributes = "";
			$this->years_of_exp->HrefValue = "";
			$this->years_of_exp->TooltipValue = "";

			// rating
			$this->rating->LinkCustomAttributes = "";
			$this->rating->HrefValue = "";
			$this->rating->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// psd_id
			$this->psd_id->EditAttrs["class"] = "form-control";
			$this->psd_id->EditCustomAttributes = "";
			$this->psd_id->EditValue = ew_HtmlEncode($this->psd_id->CurrentValue);
			$this->psd_id->PlaceHolder = ew_RemoveHtml($this->psd_id->FldCaption());

			// fk_psd
			$this->fk_psd->EditAttrs["class"] = "form-control";
			$this->fk_psd->EditCustomAttributes = "";
			$this->fk_psd->EditValue = ew_HtmlEncode($this->fk_psd->CurrentValue);
			$this->fk_psd->PlaceHolder = ew_RemoveHtml($this->fk_psd->FldCaption());

			// skill_type
			$this->skill_type->EditAttrs["class"] = "form-control";
			$this->skill_type->EditCustomAttributes = "";
			$this->skill_type->EditValue = ew_HtmlEncode($this->skill_type->CurrentValue);
			$this->skill_type->PlaceHolder = ew_RemoveHtml($this->skill_type->FldCaption());

			// years_of_exp
			$this->years_of_exp->EditAttrs["class"] = "form-control";
			$this->years_of_exp->EditCustomAttributes = "";
			$this->years_of_exp->EditValue = ew_HtmlEncode($this->years_of_exp->CurrentValue);
			$this->years_of_exp->PlaceHolder = ew_RemoveHtml($this->years_of_exp->FldCaption());

			// rating
			$this->rating->EditAttrs["class"] = "form-control";
			$this->rating->EditCustomAttributes = "";
			$this->rating->EditValue = ew_HtmlEncode($this->rating->CurrentValue);
			$this->rating->PlaceHolder = ew_RemoveHtml($this->rating->FldCaption());

			// Edit refer script
			// psd_id

			$this->psd_id->HrefValue = "";

			// fk_psd
			$this->fk_psd->HrefValue = "";

			// skill_type
			$this->skill_type->HrefValue = "";

			// years_of_exp
			$this->years_of_exp->HrefValue = "";

			// rating
			$this->rating->HrefValue = "";
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
		if (!$this->psd_id->FldIsDetailKey && !is_null($this->psd_id->FormValue) && $this->psd_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->psd_id->FldCaption(), $this->psd_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->years_of_exp->FormValue)) {
			ew_AddMessage($gsFormError, $this->years_of_exp->FldErrMsg());
		}
		if (!ew_CheckInteger($this->rating->FormValue)) {
			ew_AddMessage($gsFormError, $this->rating->FldErrMsg());
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

		// psd_id
		$this->psd_id->SetDbValueDef($rsnew, $this->psd_id->CurrentValue, "", FALSE);

		// fk_psd
		$this->fk_psd->SetDbValueDef($rsnew, $this->fk_psd->CurrentValue, NULL, FALSE);

		// skill_type
		$this->skill_type->SetDbValueDef($rsnew, $this->skill_type->CurrentValue, NULL, FALSE);

		// years_of_exp
		$this->years_of_exp->SetDbValueDef($rsnew, $this->years_of_exp->CurrentValue, NULL, FALSE);

		// rating
		$this->rating->SetDbValueDef($rsnew, $this->rating->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['psd_id']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, "party_skill_datalist.php", "", $this->TableVar, TRUE);
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
if (!isset($party_skill_data_add)) $party_skill_data_add = new cparty_skill_data_add();

// Page init
$party_skill_data_add->Page_Init();

// Page main
$party_skill_data_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$party_skill_data_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var party_skill_data_add = new ew_Page("party_skill_data_add");
party_skill_data_add.PageID = "add"; // Page ID
var EW_PAGE_ID = party_skill_data_add.PageID; // For backward compatibility

// Form object
var fparty_skill_dataadd = new ew_Form("fparty_skill_dataadd");

// Validate form
fparty_skill_dataadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_psd_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $party_skill_data->psd_id->FldCaption(), $party_skill_data->psd_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_years_of_exp");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($party_skill_data->years_of_exp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_rating");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($party_skill_data->rating->FldErrMsg()) ?>");

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
fparty_skill_dataadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparty_skill_dataadd.ValidateRequired = true;
<?php } else { ?>
fparty_skill_dataadd.ValidateRequired = false; 
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
<?php $party_skill_data_add->ShowPageHeader(); ?>
<?php
$party_skill_data_add->ShowMessage();
?>
<form name="fparty_skill_dataadd" id="fparty_skill_dataadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($party_skill_data_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $party_skill_data_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="party_skill_data">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($party_skill_data->psd_id->Visible) { // psd_id ?>
	<div id="r_psd_id" class="form-group">
		<label id="elh_party_skill_data_psd_id" for="x_psd_id" class="col-sm-2 control-label ewLabel"><?php echo $party_skill_data->psd_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $party_skill_data->psd_id->CellAttributes() ?>>
<span id="el_party_skill_data_psd_id">
<input type="text" data-field="x_psd_id" name="x_psd_id" id="x_psd_id" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($party_skill_data->psd_id->PlaceHolder) ?>" value="<?php echo $party_skill_data->psd_id->EditValue ?>"<?php echo $party_skill_data->psd_id->EditAttributes() ?>>
</span>
<?php echo $party_skill_data->psd_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($party_skill_data->fk_psd->Visible) { // fk_psd ?>
	<div id="r_fk_psd" class="form-group">
		<label id="elh_party_skill_data_fk_psd" for="x_fk_psd" class="col-sm-2 control-label ewLabel"><?php echo $party_skill_data->fk_psd->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $party_skill_data->fk_psd->CellAttributes() ?>>
<span id="el_party_skill_data_fk_psd">
<input type="text" data-field="x_fk_psd" name="x_fk_psd" id="x_fk_psd" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($party_skill_data->fk_psd->PlaceHolder) ?>" value="<?php echo $party_skill_data->fk_psd->EditValue ?>"<?php echo $party_skill_data->fk_psd->EditAttributes() ?>>
</span>
<?php echo $party_skill_data->fk_psd->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($party_skill_data->skill_type->Visible) { // skill_type ?>
	<div id="r_skill_type" class="form-group">
		<label id="elh_party_skill_data_skill_type" for="x_skill_type" class="col-sm-2 control-label ewLabel"><?php echo $party_skill_data->skill_type->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $party_skill_data->skill_type->CellAttributes() ?>>
<span id="el_party_skill_data_skill_type">
<input type="text" data-field="x_skill_type" name="x_skill_type" id="x_skill_type" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($party_skill_data->skill_type->PlaceHolder) ?>" value="<?php echo $party_skill_data->skill_type->EditValue ?>"<?php echo $party_skill_data->skill_type->EditAttributes() ?>>
</span>
<?php echo $party_skill_data->skill_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($party_skill_data->years_of_exp->Visible) { // years_of_exp ?>
	<div id="r_years_of_exp" class="form-group">
		<label id="elh_party_skill_data_years_of_exp" for="x_years_of_exp" class="col-sm-2 control-label ewLabel"><?php echo $party_skill_data->years_of_exp->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $party_skill_data->years_of_exp->CellAttributes() ?>>
<span id="el_party_skill_data_years_of_exp">
<input type="text" data-field="x_years_of_exp" name="x_years_of_exp" id="x_years_of_exp" size="30" placeholder="<?php echo ew_HtmlEncode($party_skill_data->years_of_exp->PlaceHolder) ?>" value="<?php echo $party_skill_data->years_of_exp->EditValue ?>"<?php echo $party_skill_data->years_of_exp->EditAttributes() ?>>
</span>
<?php echo $party_skill_data->years_of_exp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($party_skill_data->rating->Visible) { // rating ?>
	<div id="r_rating" class="form-group">
		<label id="elh_party_skill_data_rating" for="x_rating" class="col-sm-2 control-label ewLabel"><?php echo $party_skill_data->rating->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $party_skill_data->rating->CellAttributes() ?>>
<span id="el_party_skill_data_rating">
<input type="text" data-field="x_rating" name="x_rating" id="x_rating" size="30" placeholder="<?php echo ew_HtmlEncode($party_skill_data->rating->PlaceHolder) ?>" value="<?php echo $party_skill_data->rating->EditValue ?>"<?php echo $party_skill_data->rating->EditAttributes() ?>>
</span>
<?php echo $party_skill_data->rating->CustomMsg ?></div></div>
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
fparty_skill_dataadd.Init();
</script>
<?php
$party_skill_data_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$party_skill_data_add->Page_Terminate();
?>
