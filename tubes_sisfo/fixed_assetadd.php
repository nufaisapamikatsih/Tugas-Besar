<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "fixed_assetinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$fixed_asset_add = NULL; // Initialize page object first

class cfixed_asset_add extends cfixed_asset {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{1F84AD9A-35E5-45ED-842B-365EF8643C81}";

	// Table name
	var $TableName = 'fixed_asset';

	// Page object name
	var $PageObjName = 'fixed_asset_add';

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

		// Table object (fixed_asset)
		if (!isset($GLOBALS["fixed_asset"]) || get_class($GLOBALS["fixed_asset"]) == "cfixed_asset") {
			$GLOBALS["fixed_asset"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["fixed_asset"];
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
			define("EW_TABLE_NAME", 'fixed_asset', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("fixed_assetlist.php"));
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
		global $EW_EXPORT, $fixed_asset;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($fixed_asset);
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
			if (@$_GET["fasset_id"] != "") {
				$this->fasset_id->setQueryStringValue($_GET["fasset_id"]);
				$this->setKey("fasset_id", $this->fasset_id->CurrentValue); // Set up key
			} else {
				$this->setKey("fasset_id", ""); // Clear key
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
					$this->Page_Terminate("fixed_assetlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "fixed_assetview.php")
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
		$this->fasset_id->CurrentValue = NULL;
		$this->fasset_id->OldValue = $this->fasset_id->CurrentValue;
		$this->fk_fasset->CurrentValue = NULL;
		$this->fk_fasset->OldValue = $this->fk_fasset->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->date_acquired->CurrentValue = NULL;
		$this->date_acquired->OldValue = $this->date_acquired->CurrentValue;
		$this->last_service->CurrentValue = NULL;
		$this->last_service->OldValue = $this->last_service->CurrentValue;
		$this->next_service->CurrentValue = NULL;
		$this->next_service->OldValue = $this->next_service->CurrentValue;
		$this->prod_capacity->CurrentValue = NULL;
		$this->prod_capacity->OldValue = $this->prod_capacity->CurrentValue;
		$this->uom->CurrentValue = NULL;
		$this->uom->OldValue = $this->uom->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->fasset_id->FldIsDetailKey) {
			$this->fasset_id->setFormValue($objForm->GetValue("x_fasset_id"));
		}
		if (!$this->fk_fasset->FldIsDetailKey) {
			$this->fk_fasset->setFormValue($objForm->GetValue("x_fk_fasset"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->date_acquired->FldIsDetailKey) {
			$this->date_acquired->setFormValue($objForm->GetValue("x_date_acquired"));
		}
		if (!$this->last_service->FldIsDetailKey) {
			$this->last_service->setFormValue($objForm->GetValue("x_last_service"));
		}
		if (!$this->next_service->FldIsDetailKey) {
			$this->next_service->setFormValue($objForm->GetValue("x_next_service"));
		}
		if (!$this->prod_capacity->FldIsDetailKey) {
			$this->prod_capacity->setFormValue($objForm->GetValue("x_prod_capacity"));
		}
		if (!$this->uom->FldIsDetailKey) {
			$this->uom->setFormValue($objForm->GetValue("x_uom"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->fasset_id->CurrentValue = $this->fasset_id->FormValue;
		$this->fk_fasset->CurrentValue = $this->fk_fasset->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->date_acquired->CurrentValue = $this->date_acquired->FormValue;
		$this->last_service->CurrentValue = $this->last_service->FormValue;
		$this->next_service->CurrentValue = $this->next_service->FormValue;
		$this->prod_capacity->CurrentValue = $this->prod_capacity->FormValue;
		$this->uom->CurrentValue = $this->uom->FormValue;
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
		$this->fasset_id->setDbValue($rs->fields('fasset_id'));
		$this->fk_fasset->setDbValue($rs->fields('fk_fasset'));
		$this->name->setDbValue($rs->fields('name'));
		$this->date_acquired->setDbValue($rs->fields('date_acquired'));
		$this->last_service->setDbValue($rs->fields('last_service'));
		$this->next_service->setDbValue($rs->fields('next_service'));
		$this->prod_capacity->setDbValue($rs->fields('prod_capacity'));
		$this->uom->setDbValue($rs->fields('uom'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->fasset_id->DbValue = $row['fasset_id'];
		$this->fk_fasset->DbValue = $row['fk_fasset'];
		$this->name->DbValue = $row['name'];
		$this->date_acquired->DbValue = $row['date_acquired'];
		$this->last_service->DbValue = $row['last_service'];
		$this->next_service->DbValue = $row['next_service'];
		$this->prod_capacity->DbValue = $row['prod_capacity'];
		$this->uom->DbValue = $row['uom'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("fasset_id")) <> "")
			$this->fasset_id->CurrentValue = $this->getKey("fasset_id"); // fasset_id
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
		// fasset_id
		// fk_fasset
		// name
		// date_acquired
		// last_service
		// next_service
		// prod_capacity
		// uom

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// fasset_id
			$this->fasset_id->ViewValue = $this->fasset_id->CurrentValue;
			$this->fasset_id->ViewCustomAttributes = "";

			// fk_fasset
			$this->fk_fasset->ViewValue = $this->fk_fasset->CurrentValue;
			$this->fk_fasset->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// date_acquired
			$this->date_acquired->ViewValue = $this->date_acquired->CurrentValue;
			$this->date_acquired->ViewCustomAttributes = "";

			// last_service
			$this->last_service->ViewValue = $this->last_service->CurrentValue;
			$this->last_service->ViewCustomAttributes = "";

			// next_service
			$this->next_service->ViewValue = $this->next_service->CurrentValue;
			$this->next_service->ViewCustomAttributes = "";

			// prod_capacity
			$this->prod_capacity->ViewValue = $this->prod_capacity->CurrentValue;
			$this->prod_capacity->ViewCustomAttributes = "";

			// uom
			$this->uom->ViewValue = $this->uom->CurrentValue;
			$this->uom->ViewCustomAttributes = "";

			// fasset_id
			$this->fasset_id->LinkCustomAttributes = "";
			$this->fasset_id->HrefValue = "";
			$this->fasset_id->TooltipValue = "";

			// fk_fasset
			$this->fk_fasset->LinkCustomAttributes = "";
			$this->fk_fasset->HrefValue = "";
			$this->fk_fasset->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// date_acquired
			$this->date_acquired->LinkCustomAttributes = "";
			$this->date_acquired->HrefValue = "";
			$this->date_acquired->TooltipValue = "";

			// last_service
			$this->last_service->LinkCustomAttributes = "";
			$this->last_service->HrefValue = "";
			$this->last_service->TooltipValue = "";

			// next_service
			$this->next_service->LinkCustomAttributes = "";
			$this->next_service->HrefValue = "";
			$this->next_service->TooltipValue = "";

			// prod_capacity
			$this->prod_capacity->LinkCustomAttributes = "";
			$this->prod_capacity->HrefValue = "";
			$this->prod_capacity->TooltipValue = "";

			// uom
			$this->uom->LinkCustomAttributes = "";
			$this->uom->HrefValue = "";
			$this->uom->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// fasset_id
			$this->fasset_id->EditAttrs["class"] = "form-control";
			$this->fasset_id->EditCustomAttributes = "";
			$this->fasset_id->EditValue = ew_HtmlEncode($this->fasset_id->CurrentValue);
			$this->fasset_id->PlaceHolder = ew_RemoveHtml($this->fasset_id->FldCaption());

			// fk_fasset
			$this->fk_fasset->EditAttrs["class"] = "form-control";
			$this->fk_fasset->EditCustomAttributes = "";
			$this->fk_fasset->EditValue = ew_HtmlEncode($this->fk_fasset->CurrentValue);
			$this->fk_fasset->PlaceHolder = ew_RemoveHtml($this->fk_fasset->FldCaption());

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// date_acquired
			$this->date_acquired->EditAttrs["class"] = "form-control";
			$this->date_acquired->EditCustomAttributes = "";
			$this->date_acquired->EditValue = ew_HtmlEncode($this->date_acquired->CurrentValue);
			$this->date_acquired->PlaceHolder = ew_RemoveHtml($this->date_acquired->FldCaption());

			// last_service
			$this->last_service->EditAttrs["class"] = "form-control";
			$this->last_service->EditCustomAttributes = "";
			$this->last_service->EditValue = ew_HtmlEncode($this->last_service->CurrentValue);
			$this->last_service->PlaceHolder = ew_RemoveHtml($this->last_service->FldCaption());

			// next_service
			$this->next_service->EditAttrs["class"] = "form-control";
			$this->next_service->EditCustomAttributes = "";
			$this->next_service->EditValue = ew_HtmlEncode($this->next_service->CurrentValue);
			$this->next_service->PlaceHolder = ew_RemoveHtml($this->next_service->FldCaption());

			// prod_capacity
			$this->prod_capacity->EditAttrs["class"] = "form-control";
			$this->prod_capacity->EditCustomAttributes = "";
			$this->prod_capacity->EditValue = ew_HtmlEncode($this->prod_capacity->CurrentValue);
			$this->prod_capacity->PlaceHolder = ew_RemoveHtml($this->prod_capacity->FldCaption());

			// uom
			$this->uom->EditAttrs["class"] = "form-control";
			$this->uom->EditCustomAttributes = "";
			$this->uom->EditValue = ew_HtmlEncode($this->uom->CurrentValue);
			$this->uom->PlaceHolder = ew_RemoveHtml($this->uom->FldCaption());

			// Edit refer script
			// fasset_id

			$this->fasset_id->HrefValue = "";

			// fk_fasset
			$this->fk_fasset->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// date_acquired
			$this->date_acquired->HrefValue = "";

			// last_service
			$this->last_service->HrefValue = "";

			// next_service
			$this->next_service->HrefValue = "";

			// prod_capacity
			$this->prod_capacity->HrefValue = "";

			// uom
			$this->uom->HrefValue = "";
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
		if (!$this->fasset_id->FldIsDetailKey && !is_null($this->fasset_id->FormValue) && $this->fasset_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fasset_id->FldCaption(), $this->fasset_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->prod_capacity->FormValue)) {
			ew_AddMessage($gsFormError, $this->prod_capacity->FldErrMsg());
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

		// fasset_id
		$this->fasset_id->SetDbValueDef($rsnew, $this->fasset_id->CurrentValue, "", FALSE);

		// fk_fasset
		$this->fk_fasset->SetDbValueDef($rsnew, $this->fk_fasset->CurrentValue, NULL, FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, FALSE);

		// date_acquired
		$this->date_acquired->SetDbValueDef($rsnew, $this->date_acquired->CurrentValue, NULL, FALSE);

		// last_service
		$this->last_service->SetDbValueDef($rsnew, $this->last_service->CurrentValue, NULL, FALSE);

		// next_service
		$this->next_service->SetDbValueDef($rsnew, $this->next_service->CurrentValue, NULL, FALSE);

		// prod_capacity
		$this->prod_capacity->SetDbValueDef($rsnew, $this->prod_capacity->CurrentValue, NULL, FALSE);

		// uom
		$this->uom->SetDbValueDef($rsnew, $this->uom->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['fasset_id']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, "fixed_assetlist.php", "", $this->TableVar, TRUE);
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
if (!isset($fixed_asset_add)) $fixed_asset_add = new cfixed_asset_add();

// Page init
$fixed_asset_add->Page_Init();

// Page main
$fixed_asset_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$fixed_asset_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var fixed_asset_add = new ew_Page("fixed_asset_add");
fixed_asset_add.PageID = "add"; // Page ID
var EW_PAGE_ID = fixed_asset_add.PageID; // For backward compatibility

// Form object
var ffixed_assetadd = new ew_Form("ffixed_assetadd");

// Validate form
ffixed_assetadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fasset_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $fixed_asset->fasset_id->FldCaption(), $fixed_asset->fasset_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_prod_capacity");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($fixed_asset->prod_capacity->FldErrMsg()) ?>");

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
ffixed_assetadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffixed_assetadd.ValidateRequired = true;
<?php } else { ?>
ffixed_assetadd.ValidateRequired = false; 
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
<?php $fixed_asset_add->ShowPageHeader(); ?>
<?php
$fixed_asset_add->ShowMessage();
?>
<form name="ffixed_assetadd" id="ffixed_assetadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($fixed_asset_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $fixed_asset_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="fixed_asset">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($fixed_asset->fasset_id->Visible) { // fasset_id ?>
	<div id="r_fasset_id" class="form-group">
		<label id="elh_fixed_asset_fasset_id" for="x_fasset_id" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset->fasset_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset->fasset_id->CellAttributes() ?>>
<span id="el_fixed_asset_fasset_id">
<input type="text" data-field="x_fasset_id" name="x_fasset_id" id="x_fasset_id" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($fixed_asset->fasset_id->PlaceHolder) ?>" value="<?php echo $fixed_asset->fasset_id->EditValue ?>"<?php echo $fixed_asset->fasset_id->EditAttributes() ?>>
</span>
<?php echo $fixed_asset->fasset_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fixed_asset->fk_fasset->Visible) { // fk_fasset ?>
	<div id="r_fk_fasset" class="form-group">
		<label id="elh_fixed_asset_fk_fasset" for="x_fk_fasset" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset->fk_fasset->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset->fk_fasset->CellAttributes() ?>>
<span id="el_fixed_asset_fk_fasset">
<input type="text" data-field="x_fk_fasset" name="x_fk_fasset" id="x_fk_fasset" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($fixed_asset->fk_fasset->PlaceHolder) ?>" value="<?php echo $fixed_asset->fk_fasset->EditValue ?>"<?php echo $fixed_asset->fk_fasset->EditAttributes() ?>>
</span>
<?php echo $fixed_asset->fk_fasset->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fixed_asset->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_fixed_asset_name" for="x_name" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset->name->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset->name->CellAttributes() ?>>
<span id="el_fixed_asset_name">
<input type="text" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($fixed_asset->name->PlaceHolder) ?>" value="<?php echo $fixed_asset->name->EditValue ?>"<?php echo $fixed_asset->name->EditAttributes() ?>>
</span>
<?php echo $fixed_asset->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fixed_asset->date_acquired->Visible) { // date_acquired ?>
	<div id="r_date_acquired" class="form-group">
		<label id="elh_fixed_asset_date_acquired" for="x_date_acquired" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset->date_acquired->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset->date_acquired->CellAttributes() ?>>
<span id="el_fixed_asset_date_acquired">
<input type="text" data-field="x_date_acquired" name="x_date_acquired" id="x_date_acquired" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($fixed_asset->date_acquired->PlaceHolder) ?>" value="<?php echo $fixed_asset->date_acquired->EditValue ?>"<?php echo $fixed_asset->date_acquired->EditAttributes() ?>>
</span>
<?php echo $fixed_asset->date_acquired->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fixed_asset->last_service->Visible) { // last_service ?>
	<div id="r_last_service" class="form-group">
		<label id="elh_fixed_asset_last_service" for="x_last_service" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset->last_service->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset->last_service->CellAttributes() ?>>
<span id="el_fixed_asset_last_service">
<input type="text" data-field="x_last_service" name="x_last_service" id="x_last_service" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($fixed_asset->last_service->PlaceHolder) ?>" value="<?php echo $fixed_asset->last_service->EditValue ?>"<?php echo $fixed_asset->last_service->EditAttributes() ?>>
</span>
<?php echo $fixed_asset->last_service->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fixed_asset->next_service->Visible) { // next_service ?>
	<div id="r_next_service" class="form-group">
		<label id="elh_fixed_asset_next_service" for="x_next_service" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset->next_service->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset->next_service->CellAttributes() ?>>
<span id="el_fixed_asset_next_service">
<input type="text" data-field="x_next_service" name="x_next_service" id="x_next_service" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($fixed_asset->next_service->PlaceHolder) ?>" value="<?php echo $fixed_asset->next_service->EditValue ?>"<?php echo $fixed_asset->next_service->EditAttributes() ?>>
</span>
<?php echo $fixed_asset->next_service->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fixed_asset->prod_capacity->Visible) { // prod_capacity ?>
	<div id="r_prod_capacity" class="form-group">
		<label id="elh_fixed_asset_prod_capacity" for="x_prod_capacity" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset->prod_capacity->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset->prod_capacity->CellAttributes() ?>>
<span id="el_fixed_asset_prod_capacity">
<input type="text" data-field="x_prod_capacity" name="x_prod_capacity" id="x_prod_capacity" size="30" placeholder="<?php echo ew_HtmlEncode($fixed_asset->prod_capacity->PlaceHolder) ?>" value="<?php echo $fixed_asset->prod_capacity->EditValue ?>"<?php echo $fixed_asset->prod_capacity->EditAttributes() ?>>
</span>
<?php echo $fixed_asset->prod_capacity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fixed_asset->uom->Visible) { // uom ?>
	<div id="r_uom" class="form-group">
		<label id="elh_fixed_asset_uom" for="x_uom" class="col-sm-2 control-label ewLabel"><?php echo $fixed_asset->uom->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fixed_asset->uom->CellAttributes() ?>>
<span id="el_fixed_asset_uom">
<input type="text" data-field="x_uom" name="x_uom" id="x_uom" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($fixed_asset->uom->PlaceHolder) ?>" value="<?php echo $fixed_asset->uom->EditValue ?>"<?php echo $fixed_asset->uom->EditAttributes() ?>>
</span>
<?php echo $fixed_asset->uom->CustomMsg ?></div></div>
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
ffixed_assetadd.Init();
</script>
<?php
$fixed_asset_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$fixed_asset_add->Page_Terminate();
?>
