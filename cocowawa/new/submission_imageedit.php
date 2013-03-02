<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "submission_imageinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$submission_image_edit = NULL; // Initialize page object first

class csubmission_image_edit extends csubmission_image {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'submission_image';

	// Page object name
	var $PageObjName = 'submission_image_edit';

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

		// Table object (submission_image)
		if (!isset($GLOBALS["submission_image"])) {
			$GLOBALS["submission_image"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["submission_image"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'submission_image', TRUE);

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
			$this->Page_Terminate("submission_imagelist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("submission_imagelist.php"); // No matching record, return to list
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
		if (!$this->submission_id->FldIsDetailKey) {
			$this->submission_id->setFormValue($objForm->GetValue("x_submission_id"));
		}
		if (!$this->url->FldIsDetailKey) {
			$this->url->setFormValue($objForm->GetValue("x_url"));
		}
		if (!$this->date_added->FldIsDetailKey) {
			$this->date_added->setFormValue($objForm->GetValue("x_date_added"));
			$this->date_added->CurrentValue = ew_UnFormatDateTime($this->date_added->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->submission_id->CurrentValue = $this->submission_id->FormValue;
		$this->url->CurrentValue = $this->url->FormValue;
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
		$this->submission_id->setDbValue($rs->fields('submission_id'));
		$this->url->setDbValue($rs->fields('url'));
		$this->date_added->setDbValue($rs->fields('date_added'));
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
		// submission_id
		// url
		// date_added

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// submission_id
			$this->submission_id->ViewValue = $this->submission_id->CurrentValue;
			$this->submission_id->ViewCustomAttributes = "";

			// url
			$this->url->ViewValue = $this->url->CurrentValue;
			$this->url->ViewCustomAttributes = "";

			// date_added
			$this->date_added->ViewValue = $this->date_added->CurrentValue;
			$this->date_added->ViewValue = ew_FormatDateTime($this->date_added->ViewValue, 7);
			$this->date_added->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// submission_id
			$this->submission_id->LinkCustomAttributes = "";
			$this->submission_id->HrefValue = "";
			$this->submission_id->TooltipValue = "";

			// url
			$this->url->LinkCustomAttributes = "";
			$this->url->HrefValue = "";
			$this->url->TooltipValue = "";

			// date_added
			$this->date_added->LinkCustomAttributes = "";
			$this->date_added->HrefValue = "";
			$this->date_added->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// submission_id
			$this->submission_id->EditCustomAttributes = "";
			$this->submission_id->EditValue = ew_HtmlEncode($this->submission_id->CurrentValue);

			// url
			$this->url->EditCustomAttributes = "";
			$this->url->EditValue = ew_HtmlEncode($this->url->CurrentValue);

			// date_added
			$this->date_added->EditCustomAttributes = "";
			$this->date_added->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_added->CurrentValue, 7));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// submission_id
			$this->submission_id->HrefValue = "";

			// url
			$this->url->HrefValue = "";

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
		if (!is_null($this->submission_id->FormValue) && $this->submission_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->submission_id->FldCaption());
		}
		if (!ew_CheckInteger($this->submission_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->submission_id->FldErrMsg());
		}
		if (!is_null($this->url->FormValue) && $this->url->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->url->FldCaption());
		}
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

			// submission_id
			$this->submission_id->SetDbValueDef($rsnew, $this->submission_id->CurrentValue, 0, $this->submission_id->ReadOnly);

			// url
			$this->url->SetDbValueDef($rsnew, $this->url->CurrentValue, "", $this->url->ReadOnly);

			// date_added
			$this->date_added->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_added->CurrentValue, 7), ew_CurrentDate(), $this->date_added->ReadOnly);

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
		$table = 'submission_image';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'submission_image';

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
if (!isset($submission_image_edit)) $submission_image_edit = new csubmission_image_edit();

// Page init
$submission_image_edit->Page_Init();

// Page main
$submission_image_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var submission_image_edit = new ew_Page("submission_image_edit");
submission_image_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = submission_image_edit.PageID; // For backward compatibility

// Form object
var fsubmission_imageedit = new ew_Form("fsubmission_imageedit");

// Validate form
fsubmission_imageedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_submission_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($submission_image->submission_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_submission_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submission_image->submission_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_url"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($submission_image->url->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_date_added"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($submission_image->date_added->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_date_added"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submission_image->date_added->FldErrMsg()) ?>");

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
fsubmission_imageedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsubmission_imageedit.ValidateRequired = true;
<?php } else { ?>
fsubmission_imageedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $submission_image->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $submission_image->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $submission_image_edit->ShowPageHeader(); ?>
<?php
$submission_image_edit->ShowMessage();
?>
<form name="fsubmission_imageedit" id="fsubmission_imageedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="submission_image">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_submission_imageedit" class="ewTable">
<?php if ($submission_image->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $submission_image->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submission_image_id"><?php echo $submission_image->id->FldCaption() ?></span></td>
		<td<?php echo $submission_image->id->CellAttributes() ?>><span id="el_submission_image_id">
<span<?php echo $submission_image->id->ViewAttributes() ?>>
<?php echo $submission_image->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($submission_image->id->CurrentValue) ?>">
</span><?php echo $submission_image->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($submission_image->submission_id->Visible) { // submission_id ?>
	<tr id="r_submission_id"<?php echo $submission_image->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submission_image_submission_id"><?php echo $submission_image->submission_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $submission_image->submission_id->CellAttributes() ?>><span id="el_submission_image_submission_id">
<input type="text" name="x_submission_id" id="x_submission_id" size="30" value="<?php echo $submission_image->submission_id->EditValue ?>"<?php echo $submission_image->submission_id->EditAttributes() ?>>
</span><?php echo $submission_image->submission_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($submission_image->url->Visible) { // url ?>
	<tr id="r_url"<?php echo $submission_image->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submission_image_url"><?php echo $submission_image->url->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $submission_image->url->CellAttributes() ?>><span id="el_submission_image_url">
<input type="text" name="x_url" id="x_url" size="30" maxlength="128" value="<?php echo $submission_image->url->EditValue ?>"<?php echo $submission_image->url->EditAttributes() ?>>
</span><?php echo $submission_image->url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($submission_image->date_added->Visible) { // date_added ?>
	<tr id="r_date_added"<?php echo $submission_image->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submission_image_date_added"><?php echo $submission_image->date_added->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $submission_image->date_added->CellAttributes() ?>><span id="el_submission_image_date_added">
<input type="text" name="x_date_added" id="x_date_added" value="<?php echo $submission_image->date_added->EditValue ?>"<?php echo $submission_image->date_added->EditAttributes() ?>>
</span><?php echo $submission_image->date_added->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
fsubmission_imageedit.Init();
</script>
<?php
$submission_image_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$submission_image_edit->Page_Terminate();
?>
