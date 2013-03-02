<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "productinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$product_add = NULL; // Initialize page object first

class cproduct_add extends cproduct {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'product';

	// Page object name
	var $PageObjName = 'product_add';

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
	var $AuditTrailOnAdd = TRUE;

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

		// Table object (product)
		if (!isset($GLOBALS["product"])) {
			$GLOBALS["product"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["product"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'product', TRUE);

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
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
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

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
					$this->Page_Terminate("productlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "productview.php")
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
		global $objForm;

		// Get upload data
		$index = $objForm->Index; // Save form index
		$objForm->Index = -1;
		$confirmPage = (strval($objForm->GetValue("a_confirm")) <> "");
		$objForm->Index = $index; // Restore form index
	}

	// Load default values
	function LoadDefaultValues() {
		$this->title->CurrentValue = NULL;
		$this->title->OldValue = $this->title->CurrentValue;
		$this->artist_id->CurrentValue = NULL;
		$this->artist_id->OldValue = $this->artist_id->CurrentValue;
		$this->competition_id->CurrentValue = NULL;
		$this->competition_id->OldValue = $this->competition_id->CurrentValue;
		$this->shop->CurrentValue = 0;
		$this->price->CurrentValue = 0;
		$this->desc->CurrentValue = NULL;
		$this->desc->OldValue = $this->desc->CurrentValue;
		$this->preorders->CurrentValue = 0;
		$this->views->CurrentValue = 0;
		$this->order->CurrentValue = NULL;
		$this->order->OldValue = $this->order->CurrentValue;
		$this->last_modified->CurrentValue = "0000-00-00 00:00:00";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->artist_id->FldIsDetailKey) {
			$this->artist_id->setFormValue($objForm->GetValue("x_artist_id"));
		}
		if (!$this->competition_id->FldIsDetailKey) {
			$this->competition_id->setFormValue($objForm->GetValue("x_competition_id"));
		}
		if (!$this->shop->FldIsDetailKey) {
			$this->shop->setFormValue($objForm->GetValue("x_shop"));
		}
		if (!$this->price->FldIsDetailKey) {
			$this->price->setFormValue($objForm->GetValue("x_price"));
		}
		if (!$this->desc->FldIsDetailKey) {
			$this->desc->setFormValue($objForm->GetValue("x_desc"));
		}
		if (!$this->preorders->FldIsDetailKey) {
			$this->preorders->setFormValue($objForm->GetValue("x_preorders"));
		}
		if (!$this->views->FldIsDetailKey) {
			$this->views->setFormValue($objForm->GetValue("x_views"));
		}
		if (!$this->order->FldIsDetailKey) {
			$this->order->setFormValue($objForm->GetValue("x_order"));
		}
		if (!$this->last_modified->FldIsDetailKey) {
			$this->last_modified->setFormValue($objForm->GetValue("x_last_modified"));
			$this->last_modified->CurrentValue = ew_UnFormatDateTime($this->last_modified->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->title->CurrentValue = $this->title->FormValue;
		$this->artist_id->CurrentValue = $this->artist_id->FormValue;
		$this->competition_id->CurrentValue = $this->competition_id->FormValue;
		$this->shop->CurrentValue = $this->shop->FormValue;
		$this->price->CurrentValue = $this->price->FormValue;
		$this->desc->CurrentValue = $this->desc->FormValue;
		$this->preorders->CurrentValue = $this->preorders->FormValue;
		$this->views->CurrentValue = $this->views->FormValue;
		$this->order->CurrentValue = $this->order->FormValue;
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
		$this->title->setDbValue($rs->fields('title'));
		$this->artist_id->setDbValue($rs->fields('artist_id'));
		$this->competition_id->setDbValue($rs->fields('competition_id'));
		$this->shop->setDbValue($rs->fields('shop'));
		$this->price->setDbValue($rs->fields('price'));
		$this->desc->setDbValue($rs->fields('desc'));
		$this->preorders->setDbValue($rs->fields('preorders'));
		$this->views->setDbValue($rs->fields('views'));
		$this->order->setDbValue($rs->fields('order'));
		$this->last_modified->setDbValue($rs->fields('last_modified'));
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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
		// Convert decimal values if posted back

		if ($this->price->FormValue == $this->price->CurrentValue && is_numeric(ew_StrToFloat($this->price->CurrentValue)))
			$this->price->CurrentValue = ew_StrToFloat($this->price->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// title
		// artist_id
		// competition_id
		// shop
		// price
		// desc
		// preorders
		// views
		// order
		// last_modified

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// title
			$this->title->ViewValue = $this->title->CurrentValue;
			$this->title->ViewCustomAttributes = "";

			// artist_id
			$this->artist_id->ViewValue = $this->artist_id->CurrentValue;
			$this->artist_id->ViewCustomAttributes = "";

			// competition_id
			$this->competition_id->ViewValue = $this->competition_id->CurrentValue;
			$this->competition_id->ViewCustomAttributes = "";

			// shop
			$this->shop->ViewValue = $this->shop->CurrentValue;
			$this->shop->ViewCustomAttributes = "";

			// price
			$this->price->ViewValue = $this->price->CurrentValue;
			$this->price->ViewCustomAttributes = "";

			// desc
			$this->desc->ViewValue = $this->desc->CurrentValue;
			$this->desc->ViewCustomAttributes = "";

			// preorders
			$this->preorders->ViewValue = $this->preorders->CurrentValue;
			$this->preorders->ViewCustomAttributes = "";

			// views
			$this->views->ViewValue = $this->views->CurrentValue;
			$this->views->ViewCustomAttributes = "";

			// order
			$this->order->ViewValue = $this->order->CurrentValue;
			$this->order->ViewCustomAttributes = "";

			// last_modified
			$this->last_modified->ViewValue = $this->last_modified->CurrentValue;
			$this->last_modified->ViewValue = ew_FormatDateTime($this->last_modified->ViewValue, 7);
			$this->last_modified->ViewCustomAttributes = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// artist_id
			$this->artist_id->LinkCustomAttributes = "";
			$this->artist_id->HrefValue = "";
			$this->artist_id->TooltipValue = "";

			// competition_id
			$this->competition_id->LinkCustomAttributes = "";
			$this->competition_id->HrefValue = "";
			$this->competition_id->TooltipValue = "";

			// shop
			$this->shop->LinkCustomAttributes = "";
			$this->shop->HrefValue = "";
			$this->shop->TooltipValue = "";

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";
			$this->price->TooltipValue = "";

			// desc
			$this->desc->LinkCustomAttributes = "";
			$this->desc->HrefValue = "";
			$this->desc->TooltipValue = "";

			// preorders
			$this->preorders->LinkCustomAttributes = "";
			$this->preorders->HrefValue = "";
			$this->preorders->TooltipValue = "";

			// views
			$this->views->LinkCustomAttributes = "";
			$this->views->HrefValue = "";
			$this->views->TooltipValue = "";

			// order
			$this->order->LinkCustomAttributes = "";
			$this->order->HrefValue = "";
			$this->order->TooltipValue = "";

			// last_modified
			$this->last_modified->LinkCustomAttributes = "";
			$this->last_modified->HrefValue = "";
			$this->last_modified->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// title
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);

			// artist_id
			$this->artist_id->EditCustomAttributes = "";
			$this->artist_id->EditValue = ew_HtmlEncode($this->artist_id->CurrentValue);

			// competition_id
			$this->competition_id->EditCustomAttributes = "";
			$this->competition_id->EditValue = ew_HtmlEncode($this->competition_id->CurrentValue);

			// shop
			$this->shop->EditCustomAttributes = "";
			$this->shop->EditValue = ew_HtmlEncode($this->shop->CurrentValue);

			// price
			$this->price->EditCustomAttributes = "";
			$this->price->EditValue = ew_HtmlEncode($this->price->CurrentValue);
			if (strval($this->price->EditValue) <> "" && is_numeric($this->price->EditValue)) $this->price->EditValue = ew_FormatNumber($this->price->EditValue, -2, -1, -2, 0);

			// desc
			$this->desc->EditCustomAttributes = "";
			$this->desc->EditValue = ew_HtmlEncode($this->desc->CurrentValue);

			// preorders
			$this->preorders->EditCustomAttributes = "";
			$this->preorders->EditValue = ew_HtmlEncode($this->preorders->CurrentValue);

			// views
			$this->views->EditCustomAttributes = "";
			$this->views->EditValue = ew_HtmlEncode($this->views->CurrentValue);

			// order
			$this->order->EditCustomAttributes = "";
			$this->order->EditValue = ew_HtmlEncode($this->order->CurrentValue);

			// last_modified
			$this->last_modified->EditCustomAttributes = "";
			$this->last_modified->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->last_modified->CurrentValue, 7));

			// Edit refer script
			// title

			$this->title->HrefValue = "";

			// artist_id
			$this->artist_id->HrefValue = "";

			// competition_id
			$this->competition_id->HrefValue = "";

			// shop
			$this->shop->HrefValue = "";

			// price
			$this->price->HrefValue = "";

			// desc
			$this->desc->HrefValue = "";

			// preorders
			$this->preorders->HrefValue = "";

			// views
			$this->views->HrefValue = "";

			// order
			$this->order->HrefValue = "";

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
		if (!is_null($this->title->FormValue) && $this->title->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->title->FldCaption());
		}
		if (!is_null($this->artist_id->FormValue) && $this->artist_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->artist_id->FldCaption());
		}
		if (!ew_CheckInteger($this->artist_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->artist_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->competition_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->competition_id->FldErrMsg());
		}
		if (!is_null($this->shop->FormValue) && $this->shop->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->shop->FldCaption());
		}
		if (!ew_CheckInteger($this->shop->FormValue)) {
			ew_AddMessage($gsFormError, $this->shop->FldErrMsg());
		}
		if (!is_null($this->price->FormValue) && $this->price->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->price->FldCaption());
		}
		if (!ew_CheckNumber($this->price->FormValue)) {
			ew_AddMessage($gsFormError, $this->price->FldErrMsg());
		}
		if (!is_null($this->preorders->FormValue) && $this->preorders->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->preorders->FldCaption());
		}
		if (!ew_CheckInteger($this->preorders->FormValue)) {
			ew_AddMessage($gsFormError, $this->preorders->FldErrMsg());
		}
		if (!is_null($this->views->FormValue) && $this->views->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->views->FldCaption());
		}
		if (!ew_CheckInteger($this->views->FormValue)) {
			ew_AddMessage($gsFormError, $this->views->FldErrMsg());
		}
		if (!ew_CheckInteger($this->order->FormValue)) {
			ew_AddMessage($gsFormError, $this->order->FldErrMsg());
		}
		if (!is_null($this->last_modified->FormValue) && $this->last_modified->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->last_modified->FldCaption());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;
		$rsnew = array();

		// title
		$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", FALSE);

		// artist_id
		$this->artist_id->SetDbValueDef($rsnew, $this->artist_id->CurrentValue, 0, FALSE);

		// competition_id
		$this->competition_id->SetDbValueDef($rsnew, $this->competition_id->CurrentValue, NULL, FALSE);

		// shop
		$this->shop->SetDbValueDef($rsnew, $this->shop->CurrentValue, 0, strval($this->shop->CurrentValue) == "");

		// price
		$this->price->SetDbValueDef($rsnew, $this->price->CurrentValue, 0, strval($this->price->CurrentValue) == "");

		// desc
		$this->desc->SetDbValueDef($rsnew, $this->desc->CurrentValue, NULL, FALSE);

		// preorders
		$this->preorders->SetDbValueDef($rsnew, $this->preorders->CurrentValue, 0, strval($this->preorders->CurrentValue) == "");

		// views
		$this->views->SetDbValueDef($rsnew, $this->views->CurrentValue, 0, strval($this->views->CurrentValue) == "");

		// order
		$this->order->SetDbValueDef($rsnew, $this->order->CurrentValue, NULL, FALSE);

		// last_modified
		$this->last_modified->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->last_modified->CurrentValue, 7), ew_CurrentDate(), strval($this->last_modified->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
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
			$this->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $this->id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'product';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'product';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
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
if (!isset($product_add)) $product_add = new cproduct_add();

// Page init
$product_add->Page_Init();

// Page main
$product_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var product_add = new ew_Page("product_add");
product_add.PageID = "add"; // Page ID
var EW_PAGE_ID = product_add.PageID; // For backward compatibility

// Form object
var fproductadd = new ew_Form("fproductadd");

// Validate form
fproductadd.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_title"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($product->title->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_artist_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($product->artist_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_artist_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($product->artist_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_competition_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($product->competition_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_shop"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($product->shop->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_shop"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($product->shop->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_price"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($product->price->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_price"];
		if (elm && !ew_CheckNumber(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($product->price->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_preorders"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($product->preorders->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_preorders"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($product->preorders->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_views"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($product->views->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_views"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($product->views->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_order"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($product->order->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_last_modified"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($product->last_modified->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_last_modified"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($product->last_modified->FldErrMsg()) ?>");

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
fproductadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fproductadd.ValidateRequired = true;
<?php } else { ?>
fproductadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $product->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $product->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $product_add->ShowPageHeader(); ?>
<?php
$product_add->ShowMessage();
?>
<form name="fproductadd" id="fproductadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="product">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_productadd" class="ewTable">
<?php if ($product->title->Visible) { // title ?>
	<tr id="r_title"<?php echo $product->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_product_title"><?php echo $product->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $product->title->CellAttributes() ?>><span id="el_product_title">
<input type="text" name="x_title" id="x_title" size="30" maxlength="128" value="<?php echo $product->title->EditValue ?>"<?php echo $product->title->EditAttributes() ?>>
</span><?php echo $product->title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($product->artist_id->Visible) { // artist_id ?>
	<tr id="r_artist_id"<?php echo $product->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_product_artist_id"><?php echo $product->artist_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $product->artist_id->CellAttributes() ?>><span id="el_product_artist_id">
<input type="text" name="x_artist_id" id="x_artist_id" size="30" value="<?php echo $product->artist_id->EditValue ?>"<?php echo $product->artist_id->EditAttributes() ?>>
</span><?php echo $product->artist_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($product->competition_id->Visible) { // competition_id ?>
	<tr id="r_competition_id"<?php echo $product->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_product_competition_id"><?php echo $product->competition_id->FldCaption() ?></span></td>
		<td<?php echo $product->competition_id->CellAttributes() ?>><span id="el_product_competition_id">
<input type="text" name="x_competition_id" id="x_competition_id" size="30" value="<?php echo $product->competition_id->EditValue ?>"<?php echo $product->competition_id->EditAttributes() ?>>
</span><?php echo $product->competition_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($product->shop->Visible) { // shop ?>
	<tr id="r_shop"<?php echo $product->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_product_shop"><?php echo $product->shop->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $product->shop->CellAttributes() ?>><span id="el_product_shop">
<input type="text" name="x_shop" id="x_shop" size="30" value="<?php echo $product->shop->EditValue ?>"<?php echo $product->shop->EditAttributes() ?>>
</span><?php echo $product->shop->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($product->price->Visible) { // price ?>
	<tr id="r_price"<?php echo $product->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_product_price"><?php echo $product->price->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $product->price->CellAttributes() ?>><span id="el_product_price">
<input type="text" name="x_price" id="x_price" size="30" value="<?php echo $product->price->EditValue ?>"<?php echo $product->price->EditAttributes() ?>>
</span><?php echo $product->price->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($product->desc->Visible) { // desc ?>
	<tr id="r_desc"<?php echo $product->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_product_desc"><?php echo $product->desc->FldCaption() ?></span></td>
		<td<?php echo $product->desc->CellAttributes() ?>><span id="el_product_desc">
<input type="text" name="x_desc" id="x_desc" size="30" maxlength="128" value="<?php echo $product->desc->EditValue ?>"<?php echo $product->desc->EditAttributes() ?>>
</span><?php echo $product->desc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($product->preorders->Visible) { // preorders ?>
	<tr id="r_preorders"<?php echo $product->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_product_preorders"><?php echo $product->preorders->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $product->preorders->CellAttributes() ?>><span id="el_product_preorders">
<input type="text" name="x_preorders" id="x_preorders" size="30" value="<?php echo $product->preorders->EditValue ?>"<?php echo $product->preorders->EditAttributes() ?>>
</span><?php echo $product->preorders->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($product->views->Visible) { // views ?>
	<tr id="r_views"<?php echo $product->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_product_views"><?php echo $product->views->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $product->views->CellAttributes() ?>><span id="el_product_views">
<input type="text" name="x_views" id="x_views" size="30" value="<?php echo $product->views->EditValue ?>"<?php echo $product->views->EditAttributes() ?>>
</span><?php echo $product->views->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($product->order->Visible) { // order ?>
	<tr id="r_order"<?php echo $product->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_product_order"><?php echo $product->order->FldCaption() ?></span></td>
		<td<?php echo $product->order->CellAttributes() ?>><span id="el_product_order">
<input type="text" name="x_order" id="x_order" size="30" value="<?php echo $product->order->EditValue ?>"<?php echo $product->order->EditAttributes() ?>>
</span><?php echo $product->order->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($product->last_modified->Visible) { // last_modified ?>
	<tr id="r_last_modified"<?php echo $product->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_product_last_modified"><?php echo $product->last_modified->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $product->last_modified->CellAttributes() ?>><span id="el_product_last_modified">
<input type="text" name="x_last_modified" id="x_last_modified" value="<?php echo $product->last_modified->EditValue ?>"<?php echo $product->last_modified->EditAttributes() ?>>
</span><?php echo $product->last_modified->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>">
</form>
<script type="text/javascript">
fproductadd.Init();
</script>
<?php
$product_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$product_add->Page_Terminate();
?>
