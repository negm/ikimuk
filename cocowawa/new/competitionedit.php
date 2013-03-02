<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "competitioninfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$competition_edit = NULL; // Initialize page object first

class ccompetition_edit extends ccompetition {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'competition';

	// Page object name
	var $PageObjName = 'competition_edit';

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

		// Table object (competition)
		if (!isset($GLOBALS["competition"])) {
			$GLOBALS["competition"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["competition"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'competition', TRUE);

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
			$this->Page_Terminate("competitionlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("competitionlist.php"); // No matching record, return to list
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
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->desc->FldIsDetailKey) {
			$this->desc->setFormValue($objForm->GetValue("x_desc"));
		}
		if (!$this->competition_header->FldIsDetailKey) {
			$this->competition_header->setFormValue($objForm->GetValue("x_competition_header"));
		}
		if (!$this->submission_header->FldIsDetailKey) {
			$this->submission_header->setFormValue($objForm->GetValue("x_submission_header"));
		}
		if (!$this->competition_order->FldIsDetailKey) {
			$this->competition_order->setFormValue($objForm->GetValue("x_competition_order"));
		}
		if (!$this->start_date->FldIsDetailKey) {
			$this->start_date->setFormValue($objForm->GetValue("x_start_date"));
			$this->start_date->CurrentValue = ew_UnFormatDateTime($this->start_date->CurrentValue, 7);
		}
		if (!$this->end_date->FldIsDetailKey) {
			$this->end_date->setFormValue($objForm->GetValue("x_end_date"));
			$this->end_date->CurrentValue = ew_UnFormatDateTime($this->end_date->CurrentValue, 7);
		}
		if (!$this->submission_deadline->FldIsDetailKey) {
			$this->submission_deadline->setFormValue($objForm->GetValue("x_submission_deadline"));
			$this->submission_deadline->CurrentValue = ew_UnFormatDateTime($this->submission_deadline->CurrentValue, 7);
		}
		if (!$this->date_created->FldIsDetailKey) {
			$this->date_created->setFormValue($objForm->GetValue("x_date_created"));
			$this->date_created->CurrentValue = ew_UnFormatDateTime($this->date_created->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->title->CurrentValue = $this->title->FormValue;
		$this->desc->CurrentValue = $this->desc->FormValue;
		$this->competition_header->CurrentValue = $this->competition_header->FormValue;
		$this->submission_header->CurrentValue = $this->submission_header->FormValue;
		$this->competition_order->CurrentValue = $this->competition_order->FormValue;
		$this->start_date->CurrentValue = $this->start_date->FormValue;
		$this->start_date->CurrentValue = ew_UnFormatDateTime($this->start_date->CurrentValue, 7);
		$this->end_date->CurrentValue = $this->end_date->FormValue;
		$this->end_date->CurrentValue = ew_UnFormatDateTime($this->end_date->CurrentValue, 7);
		$this->submission_deadline->CurrentValue = $this->submission_deadline->FormValue;
		$this->submission_deadline->CurrentValue = ew_UnFormatDateTime($this->submission_deadline->CurrentValue, 7);
		$this->date_created->CurrentValue = $this->date_created->FormValue;
		$this->date_created->CurrentValue = ew_UnFormatDateTime($this->date_created->CurrentValue, 7);
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
		$this->desc->setDbValue($rs->fields('desc'));
		$this->competition_header->setDbValue($rs->fields('competition_header'));
		$this->submission_header->setDbValue($rs->fields('submission_header'));
		$this->competition_order->setDbValue($rs->fields('competition_order'));
		$this->start_date->setDbValue($rs->fields('start_date'));
		$this->end_date->setDbValue($rs->fields('end_date'));
		$this->submission_deadline->setDbValue($rs->fields('submission_deadline'));
		$this->date_created->setDbValue($rs->fields('date_created'));
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
		// title
		// desc
		// competition_header
		// submission_header
		// competition_order
		// start_date
		// end_date
		// submission_deadline
		// date_created

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// title
			$this->title->ViewValue = $this->title->CurrentValue;
			$this->title->ViewCustomAttributes = "";

			// desc
			$this->desc->ViewValue = $this->desc->CurrentValue;
			$this->desc->ViewCustomAttributes = "";

			// competition_header
			$this->competition_header->ViewValue = $this->competition_header->CurrentValue;
			$this->competition_header->ViewCustomAttributes = "";

			// submission_header
			$this->submission_header->ViewValue = $this->submission_header->CurrentValue;
			$this->submission_header->ViewCustomAttributes = "";

			// competition_order
			$this->competition_order->ViewValue = $this->competition_order->CurrentValue;
			$this->competition_order->ViewCustomAttributes = "";

			// start_date
			$this->start_date->ViewValue = $this->start_date->CurrentValue;
			$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 7);
			$this->start_date->ViewCustomAttributes = "";

			// end_date
			$this->end_date->ViewValue = $this->end_date->CurrentValue;
			$this->end_date->ViewValue = ew_FormatDateTime($this->end_date->ViewValue, 7);
			$this->end_date->ViewCustomAttributes = "";

			// submission_deadline
			$this->submission_deadline->ViewValue = $this->submission_deadline->CurrentValue;
			$this->submission_deadline->ViewValue = ew_FormatDateTime($this->submission_deadline->ViewValue, 7);
			$this->submission_deadline->ViewCustomAttributes = "";

			// date_created
			$this->date_created->ViewValue = $this->date_created->CurrentValue;
			$this->date_created->ViewValue = ew_FormatDateTime($this->date_created->ViewValue, 7);
			$this->date_created->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// desc
			$this->desc->LinkCustomAttributes = "";
			$this->desc->HrefValue = "";
			$this->desc->TooltipValue = "";

			// competition_header
			$this->competition_header->LinkCustomAttributes = "";
			$this->competition_header->HrefValue = "";
			$this->competition_header->TooltipValue = "";

			// submission_header
			$this->submission_header->LinkCustomAttributes = "";
			$this->submission_header->HrefValue = "";
			$this->submission_header->TooltipValue = "";

			// competition_order
			$this->competition_order->LinkCustomAttributes = "";
			$this->competition_order->HrefValue = "";
			$this->competition_order->TooltipValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
			$this->start_date->TooltipValue = "";

			// end_date
			$this->end_date->LinkCustomAttributes = "";
			$this->end_date->HrefValue = "";
			$this->end_date->TooltipValue = "";

			// submission_deadline
			$this->submission_deadline->LinkCustomAttributes = "";
			$this->submission_deadline->HrefValue = "";
			$this->submission_deadline->TooltipValue = "";

			// date_created
			$this->date_created->LinkCustomAttributes = "";
			$this->date_created->HrefValue = "";
			$this->date_created->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// title
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);

			// desc
			$this->desc->EditCustomAttributes = "";
			$this->desc->EditValue = ew_HtmlEncode($this->desc->CurrentValue);

			// competition_header
			$this->competition_header->EditCustomAttributes = "";
			$this->competition_header->EditValue = ew_HtmlEncode($this->competition_header->CurrentValue);

			// submission_header
			$this->submission_header->EditCustomAttributes = "";
			$this->submission_header->EditValue = ew_HtmlEncode($this->submission_header->CurrentValue);

			// competition_order
			$this->competition_order->EditCustomAttributes = "";
			$this->competition_order->EditValue = ew_HtmlEncode($this->competition_order->CurrentValue);

			// start_date
			$this->start_date->EditCustomAttributes = "";
			$this->start_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->start_date->CurrentValue, 7));

			// end_date
			$this->end_date->EditCustomAttributes = "";
			$this->end_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->end_date->CurrentValue, 7));

			// submission_deadline
			$this->submission_deadline->EditCustomAttributes = "";
			$this->submission_deadline->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->submission_deadline->CurrentValue, 7));

			// date_created
			$this->date_created->EditCustomAttributes = "";
			$this->date_created->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_created->CurrentValue, 7));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// title
			$this->title->HrefValue = "";

			// desc
			$this->desc->HrefValue = "";

			// competition_header
			$this->competition_header->HrefValue = "";

			// submission_header
			$this->submission_header->HrefValue = "";

			// competition_order
			$this->competition_order->HrefValue = "";

			// start_date
			$this->start_date->HrefValue = "";

			// end_date
			$this->end_date->HrefValue = "";

			// submission_deadline
			$this->submission_deadline->HrefValue = "";

			// date_created
			$this->date_created->HrefValue = "";
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
		if (!is_null($this->desc->FormValue) && $this->desc->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->desc->FldCaption());
		}
		if (!ew_CheckInteger($this->competition_order->FormValue)) {
			ew_AddMessage($gsFormError, $this->competition_order->FldErrMsg());
		}
		if (!is_null($this->start_date->FormValue) && $this->start_date->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->start_date->FldCaption());
		}
		if (!ew_CheckEuroDate($this->start_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->start_date->FldErrMsg());
		}
		if (!is_null($this->end_date->FormValue) && $this->end_date->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->end_date->FldCaption());
		}
		if (!ew_CheckEuroDate($this->end_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->end_date->FldErrMsg());
		}
		if (!is_null($this->submission_deadline->FormValue) && $this->submission_deadline->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->submission_deadline->FldCaption());
		}
		if (!ew_CheckEuroDate($this->submission_deadline->FormValue)) {
			ew_AddMessage($gsFormError, $this->submission_deadline->FldErrMsg());
		}
		if (!is_null($this->date_created->FormValue) && $this->date_created->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->date_created->FldCaption());
		}
		if (!ew_CheckEuroDate($this->date_created->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_created->FldErrMsg());
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

			// title
			$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", $this->title->ReadOnly);

			// desc
			$this->desc->SetDbValueDef($rsnew, $this->desc->CurrentValue, "", $this->desc->ReadOnly);

			// competition_header
			$this->competition_header->SetDbValueDef($rsnew, $this->competition_header->CurrentValue, NULL, $this->competition_header->ReadOnly);

			// submission_header
			$this->submission_header->SetDbValueDef($rsnew, $this->submission_header->CurrentValue, NULL, $this->submission_header->ReadOnly);

			// competition_order
			$this->competition_order->SetDbValueDef($rsnew, $this->competition_order->CurrentValue, NULL, $this->competition_order->ReadOnly);

			// start_date
			$this->start_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->start_date->CurrentValue, 7), ew_CurrentDate(), $this->start_date->ReadOnly);

			// end_date
			$this->end_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->end_date->CurrentValue, 7), ew_CurrentDate(), $this->end_date->ReadOnly);

			// submission_deadline
			$this->submission_deadline->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->submission_deadline->CurrentValue, 7), ew_CurrentDate(), $this->submission_deadline->ReadOnly);

			// date_created
			$this->date_created->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_created->CurrentValue, 7), ew_CurrentDate(), $this->date_created->ReadOnly);

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
		$table = 'competition';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'competition';

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
if (!isset($competition_edit)) $competition_edit = new ccompetition_edit();

// Page init
$competition_edit->Page_Init();

// Page main
$competition_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var competition_edit = new ew_Page("competition_edit");
competition_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = competition_edit.PageID; // For backward compatibility

// Form object
var fcompetitionedit = new ew_Form("fcompetitionedit");

// Validate form
fcompetitionedit.Validate = function(fobj) {
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
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($competition->title->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_desc"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($competition->desc->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_competition_order"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($competition->competition_order->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_start_date"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($competition->start_date->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_start_date"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($competition->start_date->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_end_date"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($competition->end_date->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_end_date"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($competition->end_date->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_submission_deadline"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($competition->submission_deadline->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_submission_deadline"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($competition->submission_deadline->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_date_created"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($competition->date_created->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_date_created"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($competition->date_created->FldErrMsg()) ?>");

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
fcompetitionedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcompetitionedit.ValidateRequired = true;
<?php } else { ?>
fcompetitionedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $competition->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $competition->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $competition_edit->ShowPageHeader(); ?>
<?php
$competition_edit->ShowMessage();
?>
<form name="fcompetitionedit" id="fcompetitionedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="competition">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_competitionedit" class="ewTable">
<?php if ($competition->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $competition->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_competition_id"><?php echo $competition->id->FldCaption() ?></span></td>
		<td<?php echo $competition->id->CellAttributes() ?>><span id="el_competition_id">
<span<?php echo $competition->id->ViewAttributes() ?>>
<?php echo $competition->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($competition->id->CurrentValue) ?>">
</span><?php echo $competition->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($competition->title->Visible) { // title ?>
	<tr id="r_title"<?php echo $competition->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_competition_title"><?php echo $competition->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $competition->title->CellAttributes() ?>><span id="el_competition_title">
<input type="text" name="x_title" id="x_title" size="30" maxlength="128" value="<?php echo $competition->title->EditValue ?>"<?php echo $competition->title->EditAttributes() ?>>
</span><?php echo $competition->title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($competition->desc->Visible) { // desc ?>
	<tr id="r_desc"<?php echo $competition->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_competition_desc"><?php echo $competition->desc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $competition->desc->CellAttributes() ?>><span id="el_competition_desc">
<input type="text" name="x_desc" id="x_desc" size="30" maxlength="128" value="<?php echo $competition->desc->EditValue ?>"<?php echo $competition->desc->EditAttributes() ?>>
</span><?php echo $competition->desc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($competition->competition_header->Visible) { // competition_header ?>
	<tr id="r_competition_header"<?php echo $competition->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_competition_competition_header"><?php echo $competition->competition_header->FldCaption() ?></span></td>
		<td<?php echo $competition->competition_header->CellAttributes() ?>><span id="el_competition_competition_header">
<input type="text" name="x_competition_header" id="x_competition_header" size="30" maxlength="128" value="<?php echo $competition->competition_header->EditValue ?>"<?php echo $competition->competition_header->EditAttributes() ?>>
</span><?php echo $competition->competition_header->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($competition->submission_header->Visible) { // submission_header ?>
	<tr id="r_submission_header"<?php echo $competition->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_competition_submission_header"><?php echo $competition->submission_header->FldCaption() ?></span></td>
		<td<?php echo $competition->submission_header->CellAttributes() ?>><span id="el_competition_submission_header">
<input type="text" name="x_submission_header" id="x_submission_header" size="30" maxlength="128" value="<?php echo $competition->submission_header->EditValue ?>"<?php echo $competition->submission_header->EditAttributes() ?>>
</span><?php echo $competition->submission_header->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($competition->competition_order->Visible) { // competition_order ?>
	<tr id="r_competition_order"<?php echo $competition->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_competition_competition_order"><?php echo $competition->competition_order->FldCaption() ?></span></td>
		<td<?php echo $competition->competition_order->CellAttributes() ?>><span id="el_competition_competition_order">
<input type="text" name="x_competition_order" id="x_competition_order" size="30" value="<?php echo $competition->competition_order->EditValue ?>"<?php echo $competition->competition_order->EditAttributes() ?>>
</span><?php echo $competition->competition_order->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($competition->start_date->Visible) { // start_date ?>
	<tr id="r_start_date"<?php echo $competition->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_competition_start_date"><?php echo $competition->start_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $competition->start_date->CellAttributes() ?>><span id="el_competition_start_date">
<input type="text" name="x_start_date" id="x_start_date" value="<?php echo $competition->start_date->EditValue ?>"<?php echo $competition->start_date->EditAttributes() ?>>
</span><?php echo $competition->start_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($competition->end_date->Visible) { // end_date ?>
	<tr id="r_end_date"<?php echo $competition->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_competition_end_date"><?php echo $competition->end_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $competition->end_date->CellAttributes() ?>><span id="el_competition_end_date">
<input type="text" name="x_end_date" id="x_end_date" value="<?php echo $competition->end_date->EditValue ?>"<?php echo $competition->end_date->EditAttributes() ?>>
</span><?php echo $competition->end_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($competition->submission_deadline->Visible) { // submission_deadline ?>
	<tr id="r_submission_deadline"<?php echo $competition->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_competition_submission_deadline"><?php echo $competition->submission_deadline->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $competition->submission_deadline->CellAttributes() ?>><span id="el_competition_submission_deadline">
<input type="text" name="x_submission_deadline" id="x_submission_deadline" value="<?php echo $competition->submission_deadline->EditValue ?>"<?php echo $competition->submission_deadline->EditAttributes() ?>>
</span><?php echo $competition->submission_deadline->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($competition->date_created->Visible) { // date_created ?>
	<tr id="r_date_created"<?php echo $competition->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_competition_date_created"><?php echo $competition->date_created->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $competition->date_created->CellAttributes() ?>><span id="el_competition_date_created">
<input type="text" name="x_date_created" id="x_date_created" value="<?php echo $competition->date_created->EditValue ?>"<?php echo $competition->date_created->EditAttributes() ?>>
</span><?php echo $competition->date_created->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
fcompetitionedit.Init();
</script>
<?php
$competition_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$competition_edit->Page_Terminate();
?>
