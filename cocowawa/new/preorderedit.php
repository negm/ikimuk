<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "preorderinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$preorder_edit = NULL; // Initialize page object first

class cpreorder_edit extends cpreorder {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'preorder';

	// Page object name
	var $PageObjName = 'preorder_edit';

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
	var $AuditTrailOnEdit = TRUE;

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
			$html .= "<p class=\"ewMessage\">" . $sMessage . "</p>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewWarningIcon\"></td><td class=\"ewWarningMessage\">" . $sWarningMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewSuccessIcon\"></td><td class=\"ewSuccessMessage\">" . $sSuccessMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewErrorIcon\"></td><td class=\"ewErrorMessage\">" . $sErrorMessage . "</td></tr></table>";
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
			echo "<p class=\"phpmaker\">" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Fotoer exists, display
			echo "<p class=\"phpmaker\">" . $sFooter . "</p>";
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

		// Table object (preorder)
		if (!isset($GLOBALS["preorder"])) {
			$GLOBALS["preorder"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["preorder"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'preorder', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
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
		if (@$_GET["id"] <> "")
			$this->id->setQueryStringValue($_GET["id"]);

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate("preorderlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("preorderlist.php"); // No matching record, return to list
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
		$index = $objForm->Index; // Save form index
		$objForm->Index = -1;
		$confirmPage = (strval($objForm->GetValue("a_confirm")) <> "");
		$objForm->Index = $index; // Restore form index
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
		if (!$this->product_id->FldIsDetailKey) {
			$this->product_id->setFormValue($objForm->GetValue("x_product_id"));
		}
		if (!$this->phone->FldIsDetailKey) {
			$this->phone->setFormValue($objForm->GetValue("x_phone"));
		}
		if (!$this->country->FldIsDetailKey) {
			$this->country->setFormValue($objForm->GetValue("x_country"));
		}
		if (!$this->region->FldIsDetailKey) {
			$this->region->setFormValue($objForm->GetValue("x_region"));
		}
		if (!$this->address->FldIsDetailKey) {
			$this->address->setFormValue($objForm->GetValue("x_address"));
		}
		if (!$this->size->FldIsDetailKey) {
			$this->size->setFormValue($objForm->GetValue("x_size"));
		}
		if (!$this->price->FldIsDetailKey) {
			$this->price->setFormValue($objForm->GetValue("x_price"));
		}
		if (!$this->total->FldIsDetailKey) {
			$this->total->setFormValue($objForm->GetValue("x_total"));
		}
		if (!$this->status_id->FldIsDetailKey) {
			$this->status_id->setFormValue($objForm->GetValue("x_status_id"));
		}
		if (!$this->comments->FldIsDetailKey) {
			$this->comments->setFormValue($objForm->GetValue("x_comments"));
		}
		if (!$this->last_modified->FldIsDetailKey) {
			$this->last_modified->setFormValue($objForm->GetValue("x_last_modified"));
			$this->last_modified->CurrentValue = ew_UnFormatDateTime($this->last_modified->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->user_id->CurrentValue = $this->user_id->FormValue;
		$this->product_id->CurrentValue = $this->product_id->FormValue;
		$this->phone->CurrentValue = $this->phone->FormValue;
		$this->country->CurrentValue = $this->country->FormValue;
		$this->region->CurrentValue = $this->region->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->size->CurrentValue = $this->size->FormValue;
		$this->price->CurrentValue = $this->price->FormValue;
		$this->total->CurrentValue = $this->total->FormValue;
		$this->status_id->CurrentValue = $this->status_id->FormValue;
		$this->comments->CurrentValue = $this->comments->FormValue;
		$this->last_modified->CurrentValue = $this->last_modified->FormValue;
		$this->last_modified->CurrentValue = ew_UnFormatDateTime($this->last_modified->CurrentValue, 7);
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
		$this->id->setDbValue($rs->fields('id'));
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->product_id->setDbValue($rs->fields('product_id'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->country->setDbValue($rs->fields('country'));
		$this->region->setDbValue($rs->fields('region'));
		$this->address->setDbValue($rs->fields('address'));
		$this->size->setDbValue($rs->fields('size'));
		$this->price->setDbValue($rs->fields('price'));
		$this->total->setDbValue($rs->fields('total'));
		$this->status_id->setDbValue($rs->fields('status_id'));
		$this->comments->setDbValue($rs->fields('comments'));
		$this->last_modified->setDbValue($rs->fields('last_modified'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->price->FormValue == $this->price->CurrentValue && is_numeric(ew_StrToFloat($this->price->CurrentValue)))
			$this->price->CurrentValue = ew_StrToFloat($this->price->CurrentValue);

		// Convert decimal values if posted back
		if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ew_StrToFloat($this->total->CurrentValue)))
			$this->total->CurrentValue = ew_StrToFloat($this->total->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// user_id
		// product_id
		// phone
		// country
		// region
		// address
		// size
		// price
		// total
		// status_id
		// comments
		// last_modified

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// user_id
			$this->user_id->ViewValue = $this->user_id->CurrentValue;
			$this->user_id->ViewCustomAttributes = "";

			// product_id
			$this->product_id->ViewValue = $this->product_id->CurrentValue;
			$this->product_id->ViewCustomAttributes = "";

			// phone
			$this->phone->ViewValue = $this->phone->CurrentValue;
			$this->phone->ViewCustomAttributes = "";

			// country
			$this->country->ViewValue = $this->country->CurrentValue;
			$this->country->ViewCustomAttributes = "";

			// region
			$this->region->ViewValue = $this->region->CurrentValue;
			$this->region->ViewCustomAttributes = "";

			// address
			$this->address->ViewValue = $this->address->CurrentValue;
			$this->address->ViewCustomAttributes = "";

			// size
			$this->size->ViewValue = $this->size->CurrentValue;
			$this->size->ViewCustomAttributes = "";

			// price
			$this->price->ViewValue = $this->price->CurrentValue;
			$this->price->ViewCustomAttributes = "";

			// total
			$this->total->ViewValue = $this->total->CurrentValue;
			$this->total->ViewCustomAttributes = "";

			// status_id
			$this->status_id->ViewValue = $this->status_id->CurrentValue;
			$this->status_id->ViewCustomAttributes = "";

			// comments
			$this->comments->ViewValue = $this->comments->CurrentValue;
			$this->comments->ViewCustomAttributes = "";

			// last_modified
			$this->last_modified->ViewValue = $this->last_modified->CurrentValue;
			$this->last_modified->ViewValue = ew_FormatDateTime($this->last_modified->ViewValue, 7);
			$this->last_modified->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// product_id
			$this->product_id->LinkCustomAttributes = "";
			$this->product_id->HrefValue = "";
			$this->product_id->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// country
			$this->country->LinkCustomAttributes = "";
			$this->country->HrefValue = "";
			$this->country->TooltipValue = "";

			// region
			$this->region->LinkCustomAttributes = "";
			$this->region->HrefValue = "";
			$this->region->TooltipValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// size
			$this->size->LinkCustomAttributes = "";
			$this->size->HrefValue = "";
			$this->size->TooltipValue = "";

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";
			$this->price->TooltipValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";

			// status_id
			$this->status_id->LinkCustomAttributes = "";
			$this->status_id->HrefValue = "";
			$this->status_id->TooltipValue = "";

			// comments
			$this->comments->LinkCustomAttributes = "";
			$this->comments->HrefValue = "";
			$this->comments->TooltipValue = "";

			// last_modified
			$this->last_modified->LinkCustomAttributes = "";
			$this->last_modified->HrefValue = "";
			$this->last_modified->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// user_id
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);

			// product_id
			$this->product_id->EditCustomAttributes = "";
			$this->product_id->EditValue = ew_HtmlEncode($this->product_id->CurrentValue);

			// phone
			$this->phone->EditCustomAttributes = "";
			$this->phone->EditValue = ew_HtmlEncode($this->phone->CurrentValue);

			// country
			$this->country->EditCustomAttributes = "";
			$this->country->EditValue = ew_HtmlEncode($this->country->CurrentValue);

			// region
			$this->region->EditCustomAttributes = "";
			$this->region->EditValue = ew_HtmlEncode($this->region->CurrentValue);

			// address
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);

			// size
			$this->size->EditCustomAttributes = "";
			$this->size->EditValue = ew_HtmlEncode($this->size->CurrentValue);

			// price
			$this->price->EditCustomAttributes = "";
			$this->price->EditValue = ew_HtmlEncode($this->price->CurrentValue);
			if (strval($this->price->EditValue) <> "" && is_numeric($this->price->EditValue)) $this->price->EditValue = ew_FormatNumber($this->price->EditValue, -2, -1, -2, 0);

			// total
			$this->total->EditCustomAttributes = "";
			$this->total->EditValue = ew_HtmlEncode($this->total->CurrentValue);
			if (strval($this->total->EditValue) <> "" && is_numeric($this->total->EditValue)) $this->total->EditValue = ew_FormatNumber($this->total->EditValue, -2, -1, -2, 0);

			// status_id
			$this->status_id->EditCustomAttributes = "";
			$this->status_id->EditValue = ew_HtmlEncode($this->status_id->CurrentValue);

			// comments
			$this->comments->EditCustomAttributes = "";
			$this->comments->EditValue = ew_HtmlEncode($this->comments->CurrentValue);

			// last_modified
			$this->last_modified->EditCustomAttributes = "";
			$this->last_modified->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->last_modified->CurrentValue, 7));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// user_id
			$this->user_id->HrefValue = "";

			// product_id
			$this->product_id->HrefValue = "";

			// phone
			$this->phone->HrefValue = "";

			// country
			$this->country->HrefValue = "";

			// region
			$this->region->HrefValue = "";

			// address
			$this->address->HrefValue = "";

			// size
			$this->size->HrefValue = "";

			// price
			$this->price->HrefValue = "";

			// total
			$this->total->HrefValue = "";

			// status_id
			$this->status_id->HrefValue = "";

			// comments
			$this->comments->HrefValue = "";

			// last_modified
			$this->last_modified->HrefValue = "";
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
		if (!is_null($this->user_id->FormValue) && $this->user_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->user_id->FldCaption());
		}
		if (!ew_CheckInteger($this->user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->user_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->product_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->product_id->FldErrMsg());
		}
		if (!is_null($this->phone->FormValue) && $this->phone->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->phone->FldCaption());
		}
		if (!is_null($this->country->FormValue) && $this->country->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->country->FldCaption());
		}
		if (!is_null($this->region->FormValue) && $this->region->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->region->FldCaption());
		}
		if (!is_null($this->address->FormValue) && $this->address->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->address->FldCaption());
		}
		if (!ew_CheckNumber($this->price->FormValue)) {
			ew_AddMessage($gsFormError, $this->price->FldErrMsg());
		}
		if (!ew_CheckNumber($this->total->FormValue)) {
			ew_AddMessage($gsFormError, $this->total->FldErrMsg());
		}
		if (!is_null($this->status_id->FormValue) && $this->status_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->status_id->FldCaption());
		}
		if (!ew_CheckInteger($this->status_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->status_id->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->last_modified->FormValue)) {
			ew_AddMessage($gsFormError, $this->last_modified->FldErrMsg());
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
			$rsnew = array();

			// user_id
			$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, 0, $this->user_id->ReadOnly);

			// product_id
			$this->product_id->SetDbValueDef($rsnew, $this->product_id->CurrentValue, NULL, $this->product_id->ReadOnly);

			// phone
			$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, "", $this->phone->ReadOnly);

			// country
			$this->country->SetDbValueDef($rsnew, $this->country->CurrentValue, "", $this->country->ReadOnly);

			// region
			$this->region->SetDbValueDef($rsnew, $this->region->CurrentValue, "", $this->region->ReadOnly);

			// address
			$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, "", $this->address->ReadOnly);

			// size
			$this->size->SetDbValueDef($rsnew, $this->size->CurrentValue, NULL, $this->size->ReadOnly);

			// price
			$this->price->SetDbValueDef($rsnew, $this->price->CurrentValue, NULL, $this->price->ReadOnly);

			// total
			$this->total->SetDbValueDef($rsnew, $this->total->CurrentValue, NULL, $this->total->ReadOnly);

			// status_id
			$this->status_id->SetDbValueDef($rsnew, $this->status_id->CurrentValue, 0, $this->status_id->ReadOnly);

			// comments
			$this->comments->SetDbValueDef($rsnew, $this->comments->CurrentValue, NULL, $this->comments->ReadOnly);

			// last_modified
			$this->last_modified->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->last_modified->CurrentValue, 7), NULL, $this->last_modified->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
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
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
		$rs->Close();
		return $EditRow;
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'preorder';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'preorder';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserName();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
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
if (!isset($preorder_edit)) $preorder_edit = new cpreorder_edit();

// Page init
$preorder_edit->Page_Init();

// Page main
$preorder_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var preorder_edit = new ew_Page("preorder_edit");
preorder_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = preorder_edit.PageID; // For backward compatibility

// Form object
var fpreorderedit = new ew_Form("fpreorderedit");

// Validate form
fpreorderedit.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();	
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var elm, aelm;
	var rowcnt = 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // rowcnt == 0 => Inline-Add
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = "";
		elm = fobj.elements["x" + infix + "_user_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($preorder->user_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_user_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($preorder->user_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_product_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($preorder->product_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_phone"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($preorder->phone->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_country"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($preorder->country->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_region"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($preorder->region->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_address"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($preorder->address->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_price"];
		if (elm && !ew_CheckNumber(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($preorder->price->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_total"];
		if (elm && !ew_CheckNumber(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($preorder->total->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_status_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($preorder->status_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_status_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($preorder->status_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_last_modified"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($preorder->last_modified->FldErrMsg()) ?>");

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
	}

	// Process detail page
	if (fobj.detailpage && fobj.detailpage.value && ewForms[fobj.detailpage.value])
		return ewForms[fobj.detailpage.value].Validate(fobj);
	return true;
}

// Form_CustomValidate event
fpreorderedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpreorderedit.ValidateRequired = true;
<?php } else { ?>
fpreorderedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $preorder->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $preorder->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $preorder_edit->ShowPageHeader(); ?>
<?php
$preorder_edit->ShowMessage();
?>
<form name="fpreorderedit" id="fpreorderedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="preorder">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_preorderedit" class="ewTable">
<?php if ($preorder->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $preorder->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_id"><?php echo $preorder->id->FldCaption() ?></span></td>
		<td<?php echo $preorder->id->CellAttributes() ?>><span id="el_preorder_id">
<span<?php echo $preorder->id->ViewAttributes() ?>>
<?php echo $preorder->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($preorder->id->CurrentValue) ?>">
</span><?php echo $preorder->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($preorder->user_id->Visible) { // user_id ?>
	<tr id="r_user_id"<?php echo $preorder->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_user_id"><?php echo $preorder->user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $preorder->user_id->CellAttributes() ?>><span id="el_preorder_user_id">
<input type="text" name="x_user_id" id="x_user_id" size="30" value="<?php echo $preorder->user_id->EditValue ?>"<?php echo $preorder->user_id->EditAttributes() ?>>
</span><?php echo $preorder->user_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($preorder->product_id->Visible) { // product_id ?>
	<tr id="r_product_id"<?php echo $preorder->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_product_id"><?php echo $preorder->product_id->FldCaption() ?></span></td>
		<td<?php echo $preorder->product_id->CellAttributes() ?>><span id="el_preorder_product_id">
<input type="text" name="x_product_id" id="x_product_id" size="30" value="<?php echo $preorder->product_id->EditValue ?>"<?php echo $preorder->product_id->EditAttributes() ?>>
</span><?php echo $preorder->product_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($preorder->phone->Visible) { // phone ?>
	<tr id="r_phone"<?php echo $preorder->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_phone"><?php echo $preorder->phone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $preorder->phone->CellAttributes() ?>><span id="el_preorder_phone">
<input type="text" name="x_phone" id="x_phone" size="30" maxlength="128" value="<?php echo $preorder->phone->EditValue ?>"<?php echo $preorder->phone->EditAttributes() ?>>
</span><?php echo $preorder->phone->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($preorder->country->Visible) { // country ?>
	<tr id="r_country"<?php echo $preorder->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_country"><?php echo $preorder->country->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $preorder->country->CellAttributes() ?>><span id="el_preorder_country">
<input type="text" name="x_country" id="x_country" size="30" maxlength="128" value="<?php echo $preorder->country->EditValue ?>"<?php echo $preorder->country->EditAttributes() ?>>
</span><?php echo $preorder->country->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($preorder->region->Visible) { // region ?>
	<tr id="r_region"<?php echo $preorder->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_region"><?php echo $preorder->region->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $preorder->region->CellAttributes() ?>><span id="el_preorder_region">
<input type="text" name="x_region" id="x_region" size="30" maxlength="128" value="<?php echo $preorder->region->EditValue ?>"<?php echo $preorder->region->EditAttributes() ?>>
</span><?php echo $preorder->region->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($preorder->address->Visible) { // address ?>
	<tr id="r_address"<?php echo $preorder->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_address"><?php echo $preorder->address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $preorder->address->CellAttributes() ?>><span id="el_preorder_address">
<textarea name="x_address" id="x_address" cols="35" rows="4"<?php echo $preorder->address->EditAttributes() ?>><?php echo $preorder->address->EditValue ?></textarea>
</span><?php echo $preorder->address->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($preorder->size->Visible) { // size ?>
	<tr id="r_size"<?php echo $preorder->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_size"><?php echo $preorder->size->FldCaption() ?></span></td>
		<td<?php echo $preorder->size->CellAttributes() ?>><span id="el_preorder_size">
<input type="text" name="x_size" id="x_size" size="30" maxlength="128" value="<?php echo $preorder->size->EditValue ?>"<?php echo $preorder->size->EditAttributes() ?>>
</span><?php echo $preorder->size->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($preorder->price->Visible) { // price ?>
	<tr id="r_price"<?php echo $preorder->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_price"><?php echo $preorder->price->FldCaption() ?></span></td>
		<td<?php echo $preorder->price->CellAttributes() ?>><span id="el_preorder_price">
<input type="text" name="x_price" id="x_price" size="30" value="<?php echo $preorder->price->EditValue ?>"<?php echo $preorder->price->EditAttributes() ?>>
</span><?php echo $preorder->price->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($preorder->total->Visible) { // total ?>
	<tr id="r_total"<?php echo $preorder->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_total"><?php echo $preorder->total->FldCaption() ?></span></td>
		<td<?php echo $preorder->total->CellAttributes() ?>><span id="el_preorder_total">
<input type="text" name="x_total" id="x_total" size="30" value="<?php echo $preorder->total->EditValue ?>"<?php echo $preorder->total->EditAttributes() ?>>
</span><?php echo $preorder->total->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($preorder->status_id->Visible) { // status_id ?>
	<tr id="r_status_id"<?php echo $preorder->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_status_id"><?php echo $preorder->status_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $preorder->status_id->CellAttributes() ?>><span id="el_preorder_status_id">
<input type="text" name="x_status_id" id="x_status_id" size="30" value="<?php echo $preorder->status_id->EditValue ?>"<?php echo $preorder->status_id->EditAttributes() ?>>
</span><?php echo $preorder->status_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($preorder->comments->Visible) { // comments ?>
	<tr id="r_comments"<?php echo $preorder->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_comments"><?php echo $preorder->comments->FldCaption() ?></span></td>
		<td<?php echo $preorder->comments->CellAttributes() ?>><span id="el_preorder_comments">
<textarea name="x_comments" id="x_comments" cols="35" rows="4"<?php echo $preorder->comments->EditAttributes() ?>><?php echo $preorder->comments->EditValue ?></textarea>
</span><?php echo $preorder->comments->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($preorder->last_modified->Visible) { // last_modified ?>
	<tr id="r_last_modified"<?php echo $preorder->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_last_modified"><?php echo $preorder->last_modified->FldCaption() ?></span></td>
		<td<?php echo $preorder->last_modified->CellAttributes() ?>><span id="el_preorder_last_modified">
<input type="text" name="x_last_modified" id="x_last_modified" value="<?php echo $preorder->last_modified->EditValue ?>"<?php echo $preorder->last_modified->EditAttributes() ?>>
</span><?php echo $preorder->last_modified->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
fpreorderedit.Init();
</script>
<?php
$preorder_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$preorder_edit->Page_Terminate();
?>
