<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$user_add = NULL; // Initialize page object first

class cuser_add extends cuser {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'user';

	// Page object name
	var $PageObjName = 'user_add';

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

		// Table object (user)
		if (!isset($GLOBALS["user"])) {
			$GLOBALS["user"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["user"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'user', TRUE);

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
					$this->Page_Terminate("userlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "userview.php")
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
		$this->fbid->CurrentValue = NULL;
		$this->fbid->OldValue = $this->fbid->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->password->CurrentValue = NULL;
		$this->password->OldValue = $this->password->CurrentValue;
		$this->validated_mobile->CurrentValue = NULL;
		$this->validated_mobile->OldValue = $this->validated_mobile->CurrentValue;
		$this->role_id->CurrentValue = 2;
		$this->image->CurrentValue = NULL;
		$this->image->OldValue = $this->image->CurrentValue;
		$this->newsletter->CurrentValue = 0;
		$this->points->CurrentValue = 0;
		$this->last_modified->CurrentValue = NULL;
		$this->last_modified->OldValue = $this->last_modified->CurrentValue;
		$this->p2->CurrentValue = NULL;
		$this->p2->OldValue = $this->p2->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->fbid->FldIsDetailKey) {
			$this->fbid->setFormValue($objForm->GetValue("x_fbid"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->password->FldIsDetailKey) {
			$this->password->setFormValue($objForm->GetValue("x_password"));
		}
		if (!$this->validated_mobile->FldIsDetailKey) {
			$this->validated_mobile->setFormValue($objForm->GetValue("x_validated_mobile"));
		}
		if (!$this->role_id->FldIsDetailKey) {
			$this->role_id->setFormValue($objForm->GetValue("x_role_id"));
		}
		if (!$this->image->FldIsDetailKey) {
			$this->image->setFormValue($objForm->GetValue("x_image"));
		}
		if (!$this->newsletter->FldIsDetailKey) {
			$this->newsletter->setFormValue($objForm->GetValue("x_newsletter"));
		}
		if (!$this->points->FldIsDetailKey) {
			$this->points->setFormValue($objForm->GetValue("x_points"));
		}
		if (!$this->last_modified->FldIsDetailKey) {
			$this->last_modified->setFormValue($objForm->GetValue("x_last_modified"));
			$this->last_modified->CurrentValue = ew_UnFormatDateTime($this->last_modified->CurrentValue, 7);
		}
		if (!$this->p2->FldIsDetailKey) {
			$this->p2->setFormValue($objForm->GetValue("x_p2"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->fbid->CurrentValue = $this->fbid->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->validated_mobile->CurrentValue = $this->validated_mobile->FormValue;
		$this->role_id->CurrentValue = $this->role_id->FormValue;
		$this->image->CurrentValue = $this->image->FormValue;
		$this->newsletter->CurrentValue = $this->newsletter->FormValue;
		$this->points->CurrentValue = $this->points->FormValue;
		$this->last_modified->CurrentValue = $this->last_modified->FormValue;
		$this->last_modified->CurrentValue = ew_UnFormatDateTime($this->last_modified->CurrentValue, 7);
		$this->p2->CurrentValue = $this->p2->FormValue;
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
		$this->fbid->setDbValue($rs->fields('fbid'));
		$this->name->setDbValue($rs->fields('name'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->password->setDbValue($rs->fields('password'));
		$this->validated_mobile->setDbValue($rs->fields('validated_mobile'));
		$this->role_id->setDbValue($rs->fields('role_id'));
		$this->image->setDbValue($rs->fields('image'));
		$this->newsletter->setDbValue($rs->fields('newsletter'));
		$this->points->setDbValue($rs->fields('points'));
		$this->last_modified->setDbValue($rs->fields('last_modified'));
		$this->p2->setDbValue($rs->fields('p2'));
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// fbid
		// name
		// email
		// password
		// validated_mobile
		// role_id
		// image
		// newsletter
		// points
		// last_modified
		// p2

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// fbid
			$this->fbid->ViewValue = $this->fbid->CurrentValue;
			$this->fbid->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// password
			$this->password->ViewValue = $this->password->CurrentValue;
			$this->password->ViewCustomAttributes = "";

			// validated_mobile
			$this->validated_mobile->ViewValue = $this->validated_mobile->CurrentValue;
			$this->validated_mobile->ViewCustomAttributes = "";

			// role_id
			if (strval($this->role_id->CurrentValue) <> "") {
				switch ($this->role_id->CurrentValue) {
					case $this->role_id->FldTagValue(1):
						$this->role_id->ViewValue = $this->role_id->FldTagCaption(1) <> "" ? $this->role_id->FldTagCaption(1) : $this->role_id->CurrentValue;
						break;
					case $this->role_id->FldTagValue(2):
						$this->role_id->ViewValue = $this->role_id->FldTagCaption(2) <> "" ? $this->role_id->FldTagCaption(2) : $this->role_id->CurrentValue;
						break;
					default:
						$this->role_id->ViewValue = $this->role_id->CurrentValue;
				}
			} else {
				$this->role_id->ViewValue = NULL;
			}
			$this->role_id->ViewCustomAttributes = "";

			// image
			$this->image->ViewValue = $this->image->CurrentValue;
			$this->image->ViewCustomAttributes = "";

			// newsletter
			$this->newsletter->ViewValue = $this->newsletter->CurrentValue;
			$this->newsletter->ViewCustomAttributes = "";

			// points
			$this->points->ViewValue = $this->points->CurrentValue;
			$this->points->ViewCustomAttributes = "";

			// last_modified
			$this->last_modified->ViewValue = $this->last_modified->CurrentValue;
			$this->last_modified->ViewValue = ew_FormatDateTime($this->last_modified->ViewValue, 7);
			$this->last_modified->ViewCustomAttributes = "";

			// p2
			$this->p2->ViewValue = $this->p2->CurrentValue;
			$this->p2->ViewCustomAttributes = "";

			// fbid
			$this->fbid->LinkCustomAttributes = "";
			$this->fbid->HrefValue = "";
			$this->fbid->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// validated_mobile
			$this->validated_mobile->LinkCustomAttributes = "";
			$this->validated_mobile->HrefValue = "";
			$this->validated_mobile->TooltipValue = "";

			// role_id
			$this->role_id->LinkCustomAttributes = "";
			$this->role_id->HrefValue = "";
			$this->role_id->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->HrefValue = "";
			$this->image->TooltipValue = "";

			// newsletter
			$this->newsletter->LinkCustomAttributes = "";
			$this->newsletter->HrefValue = "";
			$this->newsletter->TooltipValue = "";

			// points
			$this->points->LinkCustomAttributes = "";
			$this->points->HrefValue = "";
			$this->points->TooltipValue = "";

			// last_modified
			$this->last_modified->LinkCustomAttributes = "";
			$this->last_modified->HrefValue = "";
			$this->last_modified->TooltipValue = "";

			// p2
			$this->p2->LinkCustomAttributes = "";
			$this->p2->HrefValue = "";
			$this->p2->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// fbid
			$this->fbid->EditCustomAttributes = "";
			$this->fbid->EditValue = ew_HtmlEncode($this->fbid->CurrentValue);

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);

			// email
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);

			// password
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->CurrentValue);

			// validated_mobile
			$this->validated_mobile->EditCustomAttributes = "";
			$this->validated_mobile->EditValue = ew_HtmlEncode($this->validated_mobile->CurrentValue);

			// role_id
			$this->role_id->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->role_id->FldTagValue(1), $this->role_id->FldTagCaption(1) <> "" ? $this->role_id->FldTagCaption(1) : $this->role_id->FldTagValue(1));
			$arwrk[] = array($this->role_id->FldTagValue(2), $this->role_id->FldTagCaption(2) <> "" ? $this->role_id->FldTagCaption(2) : $this->role_id->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->role_id->EditValue = $arwrk;

			// image
			$this->image->EditCustomAttributes = "";
			$this->image->EditValue = ew_HtmlEncode($this->image->CurrentValue);

			// newsletter
			$this->newsletter->EditCustomAttributes = "";
			$this->newsletter->EditValue = ew_HtmlEncode($this->newsletter->CurrentValue);

			// points
			$this->points->EditCustomAttributes = "";
			$this->points->EditValue = ew_HtmlEncode($this->points->CurrentValue);

			// last_modified
			$this->last_modified->EditCustomAttributes = "";
			$this->last_modified->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->last_modified->CurrentValue, 7));

			// p2
			$this->p2->EditCustomAttributes = "";
			$this->p2->EditValue = ew_HtmlEncode($this->p2->CurrentValue);

			// Edit refer script
			// fbid

			$this->fbid->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// email
			$this->_email->HrefValue = "";

			// password
			$this->password->HrefValue = "";

			// validated_mobile
			$this->validated_mobile->HrefValue = "";

			// role_id
			$this->role_id->HrefValue = "";

			// image
			$this->image->HrefValue = "";

			// newsletter
			$this->newsletter->HrefValue = "";

			// points
			$this->points->HrefValue = "";

			// last_modified
			$this->last_modified->HrefValue = "";

			// p2
			$this->p2->HrefValue = "";
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
		if (!ew_CheckInteger($this->fbid->FormValue)) {
			ew_AddMessage($gsFormError, $this->fbid->FldErrMsg());
		}
		if (!is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->name->FldCaption());
		}
		if (!is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->_email->FldCaption());
		}
		if (!is_null($this->role_id->FormValue) && $this->role_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->role_id->FldCaption());
		}
		if (!is_null($this->newsletter->FormValue) && $this->newsletter->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->newsletter->FldCaption());
		}
		if (!ew_CheckInteger($this->newsletter->FormValue)) {
			ew_AddMessage($gsFormError, $this->newsletter->FldErrMsg());
		}
		if (!ew_CheckInteger($this->points->FormValue)) {
			ew_AddMessage($gsFormError, $this->points->FldErrMsg());
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
		if ($this->fbid->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(fbid = " . ew_AdjustSql($this->fbid->CurrentValue) . ")";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->fbid->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->fbid->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		$rsnew = array();

		// fbid
		$this->fbid->SetDbValueDef($rsnew, $this->fbid->CurrentValue, NULL, FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", FALSE);

		// password
		$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, NULL, FALSE);

		// validated_mobile
		$this->validated_mobile->SetDbValueDef($rsnew, $this->validated_mobile->CurrentValue, NULL, FALSE);

		// role_id
		$this->role_id->SetDbValueDef($rsnew, $this->role_id->CurrentValue, 0, strval($this->role_id->CurrentValue) == "");

		// image
		$this->image->SetDbValueDef($rsnew, $this->image->CurrentValue, NULL, FALSE);

		// newsletter
		$this->newsletter->SetDbValueDef($rsnew, $this->newsletter->CurrentValue, 0, strval($this->newsletter->CurrentValue) == "");

		// points
		$this->points->SetDbValueDef($rsnew, $this->points->CurrentValue, NULL, strval($this->points->CurrentValue) == "");

		// last_modified
		$this->last_modified->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->last_modified->CurrentValue, 7), NULL, FALSE);

		// p2
		$this->p2->SetDbValueDef($rsnew, $this->p2->CurrentValue, NULL, FALSE);

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
		$table = 'user';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'user';

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
if (!isset($user_add)) $user_add = new cuser_add();

// Page init
$user_add->Page_Init();

// Page main
$user_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var user_add = new ew_Page("user_add");
user_add.PageID = "add"; // Page ID
var EW_PAGE_ID = user_add.PageID; // For backward compatibility

// Form object
var fuseradd = new ew_Form("fuseradd");

// Validate form
fuseradd.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_fbid"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($user->fbid->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_name"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($user->name->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "__email"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($user->_email->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_role_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($user->role_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_newsletter"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($user->newsletter->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_newsletter"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($user->newsletter->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_points"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($user->points->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_last_modified"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($user->last_modified->FldErrMsg()) ?>");

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
fuseradd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuseradd.ValidateRequired = true;
<?php } else { ?>
fuseradd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $user->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $user->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $user_add->ShowPageHeader(); ?>
<?php
$user_add->ShowMessage();
?>
<form name="fuseradd" id="fuseradd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="user">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_useradd" class="ewTable">
<?php if ($user->fbid->Visible) { // fbid ?>
	<tr id="r_fbid"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_fbid"><?php echo $user->fbid->FldCaption() ?></span></td>
		<td<?php echo $user->fbid->CellAttributes() ?>><span id="el_user_fbid">
<input type="text" name="x_fbid" id="x_fbid" size="30" value="<?php echo $user->fbid->EditValue ?>"<?php echo $user->fbid->EditAttributes() ?>>
</span><?php echo $user->fbid->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($user->name->Visible) { // name ?>
	<tr id="r_name"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_name"><?php echo $user->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $user->name->CellAttributes() ?>><span id="el_user_name">
<input type="text" name="x_name" id="x_name" size="30" maxlength="128" value="<?php echo $user->name->EditValue ?>"<?php echo $user->name->EditAttributes() ?>>
</span><?php echo $user->name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($user->_email->Visible) { // email ?>
	<tr id="r__email"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user__email"><?php echo $user->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $user->_email->CellAttributes() ?>><span id="el_user__email">
<input type="text" name="x__email" id="x__email" size="30" maxlength="128" value="<?php echo $user->_email->EditValue ?>"<?php echo $user->_email->EditAttributes() ?>>
</span><?php echo $user->_email->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($user->password->Visible) { // password ?>
	<tr id="r_password"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_password"><?php echo $user->password->FldCaption() ?></span></td>
		<td<?php echo $user->password->CellAttributes() ?>><span id="el_user_password">
<textarea name="x_password" id="x_password" cols="35" rows="4"<?php echo $user->password->EditAttributes() ?>><?php echo $user->password->EditValue ?></textarea>
</span><?php echo $user->password->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($user->validated_mobile->Visible) { // validated_mobile ?>
	<tr id="r_validated_mobile"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_validated_mobile"><?php echo $user->validated_mobile->FldCaption() ?></span></td>
		<td<?php echo $user->validated_mobile->CellAttributes() ?>><span id="el_user_validated_mobile">
<input type="text" name="x_validated_mobile" id="x_validated_mobile" size="30" maxlength="64" value="<?php echo $user->validated_mobile->EditValue ?>"<?php echo $user->validated_mobile->EditAttributes() ?>>
</span><?php echo $user->validated_mobile->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($user->role_id->Visible) { // role_id ?>
	<tr id="r_role_id"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_role_id"><?php echo $user->role_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $user->role_id->CellAttributes() ?>><span id="el_user_role_id">
<select id="x_role_id" name="x_role_id"<?php echo $user->role_id->EditAttributes() ?>>
<?php
if (is_array($user->role_id->EditValue)) {
	$arwrk = $user->role_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($user->role_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span><?php echo $user->role_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($user->image->Visible) { // image ?>
	<tr id="r_image"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_image"><?php echo $user->image->FldCaption() ?></span></td>
		<td<?php echo $user->image->CellAttributes() ?>><span id="el_user_image">
<input type="text" name="x_image" id="x_image" size="30" maxlength="128" value="<?php echo $user->image->EditValue ?>"<?php echo $user->image->EditAttributes() ?>>
</span><?php echo $user->image->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($user->newsletter->Visible) { // newsletter ?>
	<tr id="r_newsletter"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_newsletter"><?php echo $user->newsletter->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $user->newsletter->CellAttributes() ?>><span id="el_user_newsletter">
<input type="text" name="x_newsletter" id="x_newsletter" size="30" value="<?php echo $user->newsletter->EditValue ?>"<?php echo $user->newsletter->EditAttributes() ?>>
</span><?php echo $user->newsletter->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($user->points->Visible) { // points ?>
	<tr id="r_points"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_points"><?php echo $user->points->FldCaption() ?></span></td>
		<td<?php echo $user->points->CellAttributes() ?>><span id="el_user_points">
<input type="text" name="x_points" id="x_points" size="30" value="<?php echo $user->points->EditValue ?>"<?php echo $user->points->EditAttributes() ?>>
</span><?php echo $user->points->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($user->last_modified->Visible) { // last_modified ?>
	<tr id="r_last_modified"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_last_modified"><?php echo $user->last_modified->FldCaption() ?></span></td>
		<td<?php echo $user->last_modified->CellAttributes() ?>><span id="el_user_last_modified">
<input type="text" name="x_last_modified" id="x_last_modified" value="<?php echo $user->last_modified->EditValue ?>"<?php echo $user->last_modified->EditAttributes() ?>>
</span><?php echo $user->last_modified->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($user->p2->Visible) { // p2 ?>
	<tr id="r_p2"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_p2"><?php echo $user->p2->FldCaption() ?></span></td>
		<td<?php echo $user->p2->CellAttributes() ?>><span id="el_user_p2">
<input type="text" name="x_p2" id="x_p2" size="30" maxlength="128" value="<?php echo $user->p2->EditValue ?>"<?php echo $user->p2->EditAttributes() ?>>
</span><?php echo $user->p2->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>">
</form>
<script type="text/javascript">
fuseradd.Init();
</script>
<?php
$user_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$user_add->Page_Terminate();
?>
