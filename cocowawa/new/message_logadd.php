<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "message_loginfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$message_log_add = NULL; // Initialize page object first

class cmessage_log_add extends cmessage_log {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'message_log';

	// Page object name
	var $PageObjName = 'message_log_add';

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

		// Table object (message_log)
		if (!isset($GLOBALS["message_log"])) {
			$GLOBALS["message_log"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["message_log"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'message_log', TRUE);

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
					$this->Page_Terminate("message_loglist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "message_logview.php")
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
		$this->messageId->CurrentValue = NULL;
		$this->messageId->OldValue = $this->messageId->CurrentValue;
		$this->requestId->CurrentValue = NULL;
		$this->requestId->OldValue = $this->requestId->CurrentValue;
		$this->recepient->CurrentValue = NULL;
		$this->recepient->OldValue = $this->recepient->CurrentValue;
		$this->subject->CurrentValue = NULL;
		$this->subject->OldValue = $this->subject->CurrentValue;
		$this->body->CurrentValue = NULL;
		$this->body->OldValue = $this->body->CurrentValue;
		$this->date_added->CurrentValue = NULL;
		$this->date_added->OldValue = $this->date_added->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->messageId->FldIsDetailKey) {
			$this->messageId->setFormValue($objForm->GetValue("x_messageId"));
		}
		if (!$this->requestId->FldIsDetailKey) {
			$this->requestId->setFormValue($objForm->GetValue("x_requestId"));
		}
		if (!$this->recepient->FldIsDetailKey) {
			$this->recepient->setFormValue($objForm->GetValue("x_recepient"));
		}
		if (!$this->subject->FldIsDetailKey) {
			$this->subject->setFormValue($objForm->GetValue("x_subject"));
		}
		if (!$this->body->FldIsDetailKey) {
			$this->body->setFormValue($objForm->GetValue("x_body"));
		}
		if (!$this->date_added->FldIsDetailKey) {
			$this->date_added->setFormValue($objForm->GetValue("x_date_added"));
			$this->date_added->CurrentValue = ew_UnFormatDateTime($this->date_added->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->messageId->CurrentValue = $this->messageId->FormValue;
		$this->requestId->CurrentValue = $this->requestId->FormValue;
		$this->recepient->CurrentValue = $this->recepient->FormValue;
		$this->subject->CurrentValue = $this->subject->FormValue;
		$this->body->CurrentValue = $this->body->FormValue;
		$this->date_added->CurrentValue = $this->date_added->FormValue;
		$this->date_added->CurrentValue = ew_UnFormatDateTime($this->date_added->CurrentValue, 7);
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
		$this->messageId->setDbValue($rs->fields('messageId'));
		$this->requestId->setDbValue($rs->fields('requestId'));
		$this->recepient->setDbValue($rs->fields('recepient'));
		$this->subject->setDbValue($rs->fields('subject'));
		$this->body->setDbValue($rs->fields('body'));
		$this->date_added->setDbValue($rs->fields('date_added'));
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
		// messageId
		// requestId
		// recepient
		// subject
		// body
		// date_added

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// messageId
			$this->messageId->ViewValue = $this->messageId->CurrentValue;
			$this->messageId->ViewCustomAttributes = "";

			// requestId
			$this->requestId->ViewValue = $this->requestId->CurrentValue;
			$this->requestId->ViewCustomAttributes = "";

			// recepient
			$this->recepient->ViewValue = $this->recepient->CurrentValue;
			$this->recepient->ViewCustomAttributes = "";

			// subject
			$this->subject->ViewValue = $this->subject->CurrentValue;
			$this->subject->ViewCustomAttributes = "";

			// body
			$this->body->ViewValue = $this->body->CurrentValue;
			$this->body->ViewCustomAttributes = "";

			// date_added
			$this->date_added->ViewValue = $this->date_added->CurrentValue;
			$this->date_added->ViewValue = ew_FormatDateTime($this->date_added->ViewValue, 7);
			$this->date_added->ViewCustomAttributes = "";

			// messageId
			$this->messageId->LinkCustomAttributes = "";
			$this->messageId->HrefValue = "";
			$this->messageId->TooltipValue = "";

			// requestId
			$this->requestId->LinkCustomAttributes = "";
			$this->requestId->HrefValue = "";
			$this->requestId->TooltipValue = "";

			// recepient
			$this->recepient->LinkCustomAttributes = "";
			$this->recepient->HrefValue = "";
			$this->recepient->TooltipValue = "";

			// subject
			$this->subject->LinkCustomAttributes = "";
			$this->subject->HrefValue = "";
			$this->subject->TooltipValue = "";

			// body
			$this->body->LinkCustomAttributes = "";
			$this->body->HrefValue = "";
			$this->body->TooltipValue = "";

			// date_added
			$this->date_added->LinkCustomAttributes = "";
			$this->date_added->HrefValue = "";
			$this->date_added->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// messageId
			$this->messageId->EditCustomAttributes = "";
			$this->messageId->EditValue = ew_HtmlEncode($this->messageId->CurrentValue);

			// requestId
			$this->requestId->EditCustomAttributes = "";
			$this->requestId->EditValue = ew_HtmlEncode($this->requestId->CurrentValue);

			// recepient
			$this->recepient->EditCustomAttributes = "";
			$this->recepient->EditValue = ew_HtmlEncode($this->recepient->CurrentValue);

			// subject
			$this->subject->EditCustomAttributes = "";
			$this->subject->EditValue = ew_HtmlEncode($this->subject->CurrentValue);

			// body
			$this->body->EditCustomAttributes = "";
			$this->body->EditValue = ew_HtmlEncode($this->body->CurrentValue);

			// date_added
			$this->date_added->EditCustomAttributes = "";
			$this->date_added->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_added->CurrentValue, 7));

			// Edit refer script
			// messageId

			$this->messageId->HrefValue = "";

			// requestId
			$this->requestId->HrefValue = "";

			// recepient
			$this->recepient->HrefValue = "";

			// subject
			$this->subject->HrefValue = "";

			// body
			$this->body->HrefValue = "";

			// date_added
			$this->date_added->HrefValue = "";
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
		if (!is_null($this->date_added->FormValue) && $this->date_added->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->date_added->FldCaption());
		}
		if (!ew_CheckEuroDate($this->date_added->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_added->FldErrMsg());
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

		// messageId
		$this->messageId->SetDbValueDef($rsnew, $this->messageId->CurrentValue, NULL, FALSE);

		// requestId
		$this->requestId->SetDbValueDef($rsnew, $this->requestId->CurrentValue, NULL, FALSE);

		// recepient
		$this->recepient->SetDbValueDef($rsnew, $this->recepient->CurrentValue, NULL, FALSE);

		// subject
		$this->subject->SetDbValueDef($rsnew, $this->subject->CurrentValue, NULL, FALSE);

		// body
		$this->body->SetDbValueDef($rsnew, $this->body->CurrentValue, NULL, FALSE);

		// date_added
		$this->date_added->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_added->CurrentValue, 7), ew_CurrentDate(), FALSE);

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
		}
		return $AddRow;
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
if (!isset($message_log_add)) $message_log_add = new cmessage_log_add();

// Page init
$message_log_add->Page_Init();

// Page main
$message_log_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var message_log_add = new ew_Page("message_log_add");
message_log_add.PageID = "add"; // Page ID
var EW_PAGE_ID = message_log_add.PageID; // For backward compatibility

// Form object
var fmessage_logadd = new ew_Form("fmessage_logadd");

// Validate form
fmessage_logadd.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_date_added"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($message_log->date_added->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_date_added"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($message_log->date_added->FldErrMsg()) ?>");

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
fmessage_logadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmessage_logadd.ValidateRequired = true;
<?php } else { ?>
fmessage_logadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $message_log->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $message_log->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $message_log_add->ShowPageHeader(); ?>
<?php
$message_log_add->ShowMessage();
?>
<form name="fmessage_logadd" id="fmessage_logadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="message_log">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_message_logadd" class="ewTable">
<?php if ($message_log->messageId->Visible) { // messageId ?>
	<tr id="r_messageId"<?php echo $message_log->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_message_log_messageId"><?php echo $message_log->messageId->FldCaption() ?></span></td>
		<td<?php echo $message_log->messageId->CellAttributes() ?>><span id="el_message_log_messageId">
<input type="text" name="x_messageId" id="x_messageId" size="30" maxlength="128" value="<?php echo $message_log->messageId->EditValue ?>"<?php echo $message_log->messageId->EditAttributes() ?>>
</span><?php echo $message_log->messageId->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($message_log->requestId->Visible) { // requestId ?>
	<tr id="r_requestId"<?php echo $message_log->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_message_log_requestId"><?php echo $message_log->requestId->FldCaption() ?></span></td>
		<td<?php echo $message_log->requestId->CellAttributes() ?>><span id="el_message_log_requestId">
<input type="text" name="x_requestId" id="x_requestId" size="30" maxlength="128" value="<?php echo $message_log->requestId->EditValue ?>"<?php echo $message_log->requestId->EditAttributes() ?>>
</span><?php echo $message_log->requestId->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($message_log->recepient->Visible) { // recepient ?>
	<tr id="r_recepient"<?php echo $message_log->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_message_log_recepient"><?php echo $message_log->recepient->FldCaption() ?></span></td>
		<td<?php echo $message_log->recepient->CellAttributes() ?>><span id="el_message_log_recepient">
<input type="text" name="x_recepient" id="x_recepient" size="30" maxlength="128" value="<?php echo $message_log->recepient->EditValue ?>"<?php echo $message_log->recepient->EditAttributes() ?>>
</span><?php echo $message_log->recepient->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($message_log->subject->Visible) { // subject ?>
	<tr id="r_subject"<?php echo $message_log->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_message_log_subject"><?php echo $message_log->subject->FldCaption() ?></span></td>
		<td<?php echo $message_log->subject->CellAttributes() ?>><span id="el_message_log_subject">
<input type="text" name="x_subject" id="x_subject" size="30" maxlength="128" value="<?php echo $message_log->subject->EditValue ?>"<?php echo $message_log->subject->EditAttributes() ?>>
</span><?php echo $message_log->subject->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($message_log->body->Visible) { // body ?>
	<tr id="r_body"<?php echo $message_log->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_message_log_body"><?php echo $message_log->body->FldCaption() ?></span></td>
		<td<?php echo $message_log->body->CellAttributes() ?>><span id="el_message_log_body">
<input type="text" name="x_body" id="x_body" size="30" maxlength="128" value="<?php echo $message_log->body->EditValue ?>"<?php echo $message_log->body->EditAttributes() ?>>
</span><?php echo $message_log->body->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($message_log->date_added->Visible) { // date_added ?>
	<tr id="r_date_added"<?php echo $message_log->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_message_log_date_added"><?php echo $message_log->date_added->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $message_log->date_added->CellAttributes() ?>><span id="el_message_log_date_added">
<input type="text" name="x_date_added" id="x_date_added" value="<?php echo $message_log->date_added->EditValue ?>"<?php echo $message_log->date_added->EditAttributes() ?>>
</span><?php echo $message_log->date_added->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>">
</form>
<script type="text/javascript">
fmessage_logadd.Init();
</script>
<?php
$message_log_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$message_log_add->Page_Terminate();
?>
