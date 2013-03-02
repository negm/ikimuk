<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "submissionsinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$submissions_add = NULL; // Initialize page object first

class csubmissions_add extends csubmissions {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'submissions';

	// Page object name
	var $PageObjName = 'submissions_add';

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

		// Table object (submissions)
		if (!isset($GLOBALS["submissions"])) {
			$GLOBALS["submissions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["submissions"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'submissions', TRUE);

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
					$this->Page_Terminate("submissionslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "submissionsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		if ($this->CurrentAction == "F") { // Confirm page
		  $this->RowType = EW_ROWTYPE_VIEW; // Render view type
		} else {
		  $this->RowType = EW_ROWTYPE_ADD; // Render add type
		}

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
		$this->competition_id->CurrentValue = NULL;
		$this->competition_id->OldValue = $this->competition_id->CurrentValue;
		$this->user_id->CurrentValue = NULL;
		$this->user_id->OldValue = $this->user_id->CurrentValue;
		$this->title->CurrentValue = NULL;
		$this->title->OldValue = $this->title->CurrentValue;
		$this->comments->CurrentValue = NULL;
		$this->comments->OldValue = $this->comments->CurrentValue;
		$this->newsletter->CurrentValue = NULL;
		$this->newsletter->OldValue = $this->newsletter->CurrentValue;
		$this->submission_date->CurrentValue = NULL;
		$this->submission_date->OldValue = $this->submission_date->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->competition_id->FldIsDetailKey) {
			$this->competition_id->setFormValue($objForm->GetValue("x_competition_id"));
		}
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->comments->FldIsDetailKey) {
			$this->comments->setFormValue($objForm->GetValue("x_comments"));
		}
		if (!$this->newsletter->FldIsDetailKey) {
			$this->newsletter->setFormValue($objForm->GetValue("x_newsletter"));
		}
		if (!$this->submission_date->FldIsDetailKey) {
			$this->submission_date->setFormValue($objForm->GetValue("x_submission_date"));
			$this->submission_date->CurrentValue = ew_UnFormatDateTime($this->submission_date->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->competition_id->CurrentValue = $this->competition_id->FormValue;
		$this->user_id->CurrentValue = $this->user_id->FormValue;
		$this->title->CurrentValue = $this->title->FormValue;
		$this->comments->CurrentValue = $this->comments->FormValue;
		$this->newsletter->CurrentValue = $this->newsletter->FormValue;
		$this->submission_date->CurrentValue = $this->submission_date->FormValue;
		$this->submission_date->CurrentValue = ew_UnFormatDateTime($this->submission_date->CurrentValue, 7);
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
		$this->competition_id->setDbValue($rs->fields('competition_id'));
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->title->setDbValue($rs->fields('title'));
		$this->comments->setDbValue($rs->fields('comments'));
		$this->newsletter->setDbValue($rs->fields('newsletter'));
		$this->submission_date->setDbValue($rs->fields('submission_date'));
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
		// competition_id
		// user_id
		// title
		// comments
		// newsletter
		// submission_date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// competition_id
			$this->competition_id->ViewValue = $this->competition_id->CurrentValue;
			$this->competition_id->ViewCustomAttributes = "";

			// user_id
			$this->user_id->ViewValue = $this->user_id->CurrentValue;
			$this->user_id->ViewCustomAttributes = "";

			// title
			$this->title->ViewValue = $this->title->CurrentValue;
			$this->title->ViewCustomAttributes = "";

			// comments
			$this->comments->ViewValue = $this->comments->CurrentValue;
			$this->comments->ViewCustomAttributes = "";

			// newsletter
			$this->newsletter->ViewValue = $this->newsletter->CurrentValue;
			$this->newsletter->ViewCustomAttributes = "";

			// submission_date
			$this->submission_date->ViewValue = $this->submission_date->CurrentValue;
			$this->submission_date->ViewValue = ew_FormatDateTime($this->submission_date->ViewValue, 7);
			$this->submission_date->ViewCustomAttributes = "";

			// competition_id
			$this->competition_id->LinkCustomAttributes = "";
			$this->competition_id->HrefValue = "";
			$this->competition_id->TooltipValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// comments
			$this->comments->LinkCustomAttributes = "";
			$this->comments->HrefValue = "";
			$this->comments->TooltipValue = "";

			// newsletter
			$this->newsletter->LinkCustomAttributes = "";
			$this->newsletter->HrefValue = "";
			$this->newsletter->TooltipValue = "";

			// submission_date
			$this->submission_date->LinkCustomAttributes = "";
			$this->submission_date->HrefValue = "";
			$this->submission_date->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// competition_id
			$this->competition_id->EditCustomAttributes = "";
			$this->competition_id->EditValue = ew_HtmlEncode($this->competition_id->CurrentValue);

			// user_id
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);

			// title
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);

			// comments
			$this->comments->EditCustomAttributes = "";
			$this->comments->EditValue = ew_HtmlEncode($this->comments->CurrentValue);

			// newsletter
			$this->newsletter->EditCustomAttributes = "";
			$this->newsletter->EditValue = ew_HtmlEncode($this->newsletter->CurrentValue);

			// submission_date
			$this->submission_date->EditCustomAttributes = "";
			$this->submission_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->submission_date->CurrentValue, 7));

			// Edit refer script
			// competition_id

			$this->competition_id->HrefValue = "";

			// user_id
			$this->user_id->HrefValue = "";

			// title
			$this->title->HrefValue = "";

			// comments
			$this->comments->HrefValue = "";

			// newsletter
			$this->newsletter->HrefValue = "";

			// submission_date
			$this->submission_date->HrefValue = "";
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
		if (!is_null($this->competition_id->FormValue) && $this->competition_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->competition_id->FldCaption());
		}
		if (!ew_CheckInteger($this->competition_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->competition_id->FldErrMsg());
		}
		if (!is_null($this->user_id->FormValue) && $this->user_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->user_id->FldCaption());
		}
		if (!ew_CheckInteger($this->user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->user_id->FldErrMsg());
		}
		if (!is_null($this->title->FormValue) && $this->title->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->title->FldCaption());
		}
		if (!ew_CheckInteger($this->newsletter->FormValue)) {
			ew_AddMessage($gsFormError, $this->newsletter->FldErrMsg());
		}
		if (!is_null($this->submission_date->FormValue) && $this->submission_date->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->submission_date->FldCaption());
		}
		if (!ew_CheckEuroDate($this->submission_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->submission_date->FldErrMsg());
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

		// competition_id
		$this->competition_id->SetDbValueDef($rsnew, $this->competition_id->CurrentValue, 0, FALSE);

		// user_id
		$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, 0, FALSE);

		// title
		$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", FALSE);

		// comments
		$this->comments->SetDbValueDef($rsnew, $this->comments->CurrentValue, NULL, FALSE);

		// newsletter
		$this->newsletter->SetDbValueDef($rsnew, $this->newsletter->CurrentValue, NULL, FALSE);

		// submission_date
		$this->submission_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->submission_date->CurrentValue, 7), ew_CurrentDate(), FALSE);

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
		$table = 'submissions';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'submissions';

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
if (!isset($submissions_add)) $submissions_add = new csubmissions_add();

// Page init
$submissions_add->Page_Init();

// Page main
$submissions_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var submissions_add = new ew_Page("submissions_add");
submissions_add.PageID = "add"; // Page ID
var EW_PAGE_ID = submissions_add.PageID; // For backward compatibility

// Form object
var fsubmissionsadd = new ew_Form("fsubmissionsadd");

// Validate form
fsubmissionsadd.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_competition_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($submissions->competition_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_competition_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submissions->competition_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_user_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($submissions->user_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_user_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submissions->user_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_title"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($submissions->title->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_newsletter"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submissions->newsletter->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_submission_date"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($submissions->submission_date->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_submission_date"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submissions->submission_date->FldErrMsg()) ?>");

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
fsubmissionsadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsubmissionsadd.ValidateRequired = true;
<?php } else { ?>
fsubmissionsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $submissions->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $submissions->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $submissions_add->ShowPageHeader(); ?>
<?php
$submissions_add->ShowMessage();
?>
<form name="fsubmissionsadd" id="fsubmissionsadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="submissions">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($submissions->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_submissionsadd" class="ewTable">
<?php if ($submissions->competition_id->Visible) { // competition_id ?>
	<tr id="r_competition_id"<?php echo $submissions->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submissions_competition_id"><?php echo $submissions->competition_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $submissions->competition_id->CellAttributes() ?>><span id="el_submissions_competition_id">
<?php if ($submissions->CurrentAction <> "F") { ?>
<input type="text" name="x_competition_id" id="x_competition_id" size="30" value="<?php echo $submissions->competition_id->EditValue ?>"<?php echo $submissions->competition_id->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $submissions->competition_id->ViewAttributes() ?>>
<?php echo $submissions->competition_id->ViewValue ?></span>
<input type="hidden" name="x_competition_id" id="x_competition_id" value="<?php echo ew_HtmlEncode($submissions->competition_id->FormValue) ?>">
<?php } ?>
</span><?php echo $submissions->competition_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($submissions->user_id->Visible) { // user_id ?>
	<tr id="r_user_id"<?php echo $submissions->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submissions_user_id"><?php echo $submissions->user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $submissions->user_id->CellAttributes() ?>><span id="el_submissions_user_id">
<?php if ($submissions->CurrentAction <> "F") { ?>
<input type="text" name="x_user_id" id="x_user_id" size="30" value="<?php echo $submissions->user_id->EditValue ?>"<?php echo $submissions->user_id->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $submissions->user_id->ViewAttributes() ?>>
<?php echo $submissions->user_id->ViewValue ?></span>
<input type="hidden" name="x_user_id" id="x_user_id" value="<?php echo ew_HtmlEncode($submissions->user_id->FormValue) ?>">
<?php } ?>
</span><?php echo $submissions->user_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($submissions->title->Visible) { // title ?>
	<tr id="r_title"<?php echo $submissions->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submissions_title"><?php echo $submissions->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $submissions->title->CellAttributes() ?>><span id="el_submissions_title">
<?php if ($submissions->CurrentAction <> "F") { ?>
<input type="text" name="x_title" id="x_title" size="30" maxlength="128" value="<?php echo $submissions->title->EditValue ?>"<?php echo $submissions->title->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $submissions->title->ViewAttributes() ?>>
<?php echo $submissions->title->ViewValue ?></span>
<input type="hidden" name="x_title" id="x_title" value="<?php echo ew_HtmlEncode($submissions->title->FormValue) ?>">
<?php } ?>
</span><?php echo $submissions->title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($submissions->comments->Visible) { // comments ?>
	<tr id="r_comments"<?php echo $submissions->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submissions_comments"><?php echo $submissions->comments->FldCaption() ?></span></td>
		<td<?php echo $submissions->comments->CellAttributes() ?>><span id="el_submissions_comments">
<?php if ($submissions->CurrentAction <> "F") { ?>
<input type="text" name="x_comments" id="x_comments" size="30" maxlength="128" value="<?php echo $submissions->comments->EditValue ?>"<?php echo $submissions->comments->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $submissions->comments->ViewAttributes() ?>>
<?php echo $submissions->comments->ViewValue ?></span>
<input type="hidden" name="x_comments" id="x_comments" value="<?php echo ew_HtmlEncode($submissions->comments->FormValue) ?>">
<?php } ?>
</span><?php echo $submissions->comments->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($submissions->newsletter->Visible) { // newsletter ?>
	<tr id="r_newsletter"<?php echo $submissions->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submissions_newsletter"><?php echo $submissions->newsletter->FldCaption() ?></span></td>
		<td<?php echo $submissions->newsletter->CellAttributes() ?>><span id="el_submissions_newsletter">
<?php if ($submissions->CurrentAction <> "F") { ?>
<input type="text" name="x_newsletter" id="x_newsletter" size="30" value="<?php echo $submissions->newsletter->EditValue ?>"<?php echo $submissions->newsletter->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $submissions->newsletter->ViewAttributes() ?>>
<?php echo $submissions->newsletter->ViewValue ?></span>
<input type="hidden" name="x_newsletter" id="x_newsletter" value="<?php echo ew_HtmlEncode($submissions->newsletter->FormValue) ?>">
<?php } ?>
</span><?php echo $submissions->newsletter->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($submissions->submission_date->Visible) { // submission_date ?>
	<tr id="r_submission_date"<?php echo $submissions->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submissions_submission_date"><?php echo $submissions->submission_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $submissions->submission_date->CellAttributes() ?>><span id="el_submissions_submission_date">
<?php if ($submissions->CurrentAction <> "F") { ?>
<input type="text" name="x_submission_date" id="x_submission_date" value="<?php echo $submissions->submission_date->EditValue ?>"<?php echo $submissions->submission_date->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $submissions->submission_date->ViewAttributes() ?>>
<?php echo $submissions->submission_date->ViewValue ?></span>
<input type="hidden" name="x_submission_date" id="x_submission_date" value="<?php echo ew_HtmlEncode($submissions->submission_date->FormValue) ?>">
<?php } ?>
</span><?php echo $submissions->submission_date->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<?php if ($submissions->CurrentAction <> "F") { // Confirm page ?>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>" onclick="this.form.a_add.value='F';">
<?php } else { ?>
<input type="submit" name="btnCancel" id="btnCancel" value="<?php echo ew_BtnCaption($Language->Phrase("CancelBtn")) ?>" onclick="this.form.a_add.value='X';">
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("ConfirmBtn")) ?>">
<?php } ?>
</form>
<script type="text/javascript">
fsubmissionsadd.Init();
</script>
<?php
$submissions_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$submissions_add->Page_Terminate();
?>
