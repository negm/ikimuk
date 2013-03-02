<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "order_detailsinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$order_details_edit = NULL; // Initialize page object first

class corder_details_edit extends corder_details {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'order_details';

	// Page object name
	var $PageObjName = 'order_details_edit';

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

		// Table object (order_details)
		if (!isset($GLOBALS["order_details"])) {
			$GLOBALS["order_details"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["order_details"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'order_details', TRUE);

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
			$this->Page_Terminate("order_detailslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("order_detailslist.php"); // No matching record, return to list
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
		if (!$this->order_id->FldIsDetailKey) {
			$this->order_id->setFormValue($objForm->GetValue("x_order_id"));
		}
		if (!$this->product_id->FldIsDetailKey) {
			$this->product_id->setFormValue($objForm->GetValue("x_product_id"));
		}
		if (!$this->size->FldIsDetailKey) {
			$this->size->setFormValue($objForm->GetValue("x_size"));
		}
		if (!$this->cut->FldIsDetailKey) {
			$this->cut->setFormValue($objForm->GetValue("x_cut"));
		}
		if (!$this->quantity->FldIsDetailKey) {
			$this->quantity->setFormValue($objForm->GetValue("x_quantity"));
		}
		if (!$this->price->FldIsDetailKey) {
			$this->price->setFormValue($objForm->GetValue("x_price"));
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
		$this->order_id->CurrentValue = $this->order_id->FormValue;
		$this->product_id->CurrentValue = $this->product_id->FormValue;
		$this->size->CurrentValue = $this->size->FormValue;
		$this->cut->CurrentValue = $this->cut->FormValue;
		$this->quantity->CurrentValue = $this->quantity->FormValue;
		$this->price->CurrentValue = $this->price->FormValue;
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
		$this->order_id->setDbValue($rs->fields('order_id'));
		$this->product_id->setDbValue($rs->fields('product_id'));
		$this->size->setDbValue($rs->fields('size'));
		$this->cut->setDbValue($rs->fields('cut'));
		$this->quantity->setDbValue($rs->fields('quantity'));
		$this->price->setDbValue($rs->fields('price'));
		$this->date_added->setDbValue($rs->fields('date_added'));
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
		// order_id
		// product_id
		// size
		// cut
		// quantity
		// price
		// date_added

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// order_id
			$this->order_id->ViewValue = $this->order_id->CurrentValue;
			$this->order_id->ViewCustomAttributes = "";

			// product_id
			$this->product_id->ViewValue = $this->product_id->CurrentValue;
			$this->product_id->ViewCustomAttributes = "";

			// size
			$this->size->ViewValue = $this->size->CurrentValue;
			$this->size->ViewCustomAttributes = "";

			// cut
			$this->cut->ViewValue = $this->cut->CurrentValue;
			$this->cut->ViewCustomAttributes = "";

			// quantity
			$this->quantity->ViewValue = $this->quantity->CurrentValue;
			$this->quantity->ViewCustomAttributes = "";

			// price
			$this->price->ViewValue = $this->price->CurrentValue;
			$this->price->ViewCustomAttributes = "";

			// date_added
			$this->date_added->ViewValue = $this->date_added->CurrentValue;
			$this->date_added->ViewValue = ew_FormatDateTime($this->date_added->ViewValue, 7);
			$this->date_added->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// order_id
			$this->order_id->LinkCustomAttributes = "";
			$this->order_id->HrefValue = "";
			$this->order_id->TooltipValue = "";

			// product_id
			$this->product_id->LinkCustomAttributes = "";
			$this->product_id->HrefValue = "";
			$this->product_id->TooltipValue = "";

			// size
			$this->size->LinkCustomAttributes = "";
			$this->size->HrefValue = "";
			$this->size->TooltipValue = "";

			// cut
			$this->cut->LinkCustomAttributes = "";
			$this->cut->HrefValue = "";
			$this->cut->TooltipValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";
			$this->price->TooltipValue = "";

			// date_added
			$this->date_added->LinkCustomAttributes = "";
			$this->date_added->HrefValue = "";
			$this->date_added->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// order_id
			$this->order_id->EditCustomAttributes = "";
			$this->order_id->EditValue = ew_HtmlEncode($this->order_id->CurrentValue);

			// product_id
			$this->product_id->EditCustomAttributes = "";
			$this->product_id->EditValue = ew_HtmlEncode($this->product_id->CurrentValue);

			// size
			$this->size->EditCustomAttributes = "";
			$this->size->EditValue = ew_HtmlEncode($this->size->CurrentValue);

			// cut
			$this->cut->EditCustomAttributes = "";
			$this->cut->EditValue = ew_HtmlEncode($this->cut->CurrentValue);

			// quantity
			$this->quantity->EditCustomAttributes = "";
			$this->quantity->EditValue = ew_HtmlEncode($this->quantity->CurrentValue);

			// price
			$this->price->EditCustomAttributes = "";
			$this->price->EditValue = ew_HtmlEncode($this->price->CurrentValue);
			if (strval($this->price->EditValue) <> "" && is_numeric($this->price->EditValue)) $this->price->EditValue = ew_FormatNumber($this->price->EditValue, -2, -1, -2, 0);

			// date_added
			$this->date_added->EditCustomAttributes = "";
			$this->date_added->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_added->CurrentValue, 7));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// order_id
			$this->order_id->HrefValue = "";

			// product_id
			$this->product_id->HrefValue = "";

			// size
			$this->size->HrefValue = "";

			// cut
			$this->cut->HrefValue = "";

			// quantity
			$this->quantity->HrefValue = "";

			// price
			$this->price->HrefValue = "";

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
		if (!is_null($this->order_id->FormValue) && $this->order_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->order_id->FldCaption());
		}
		if (!ew_CheckInteger($this->order_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->order_id->FldErrMsg());
		}
		if (!is_null($this->product_id->FormValue) && $this->product_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->product_id->FldCaption());
		}
		if (!ew_CheckInteger($this->product_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->product_id->FldErrMsg());
		}
		if (!is_null($this->size->FormValue) && $this->size->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->size->FldCaption());
		}
		if (!ew_CheckInteger($this->size->FormValue)) {
			ew_AddMessage($gsFormError, $this->size->FldErrMsg());
		}
		if (!is_null($this->cut->FormValue) && $this->cut->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->cut->FldCaption());
		}
		if (!ew_CheckInteger($this->cut->FormValue)) {
			ew_AddMessage($gsFormError, $this->cut->FldErrMsg());
		}
		if (!is_null($this->quantity->FormValue) && $this->quantity->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->quantity->FldCaption());
		}
		if (!ew_CheckInteger($this->quantity->FormValue)) {
			ew_AddMessage($gsFormError, $this->quantity->FldErrMsg());
		}
		if (!is_null($this->price->FormValue) && $this->price->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->price->FldCaption());
		}
		if (!ew_CheckNumber($this->price->FormValue)) {
			ew_AddMessage($gsFormError, $this->price->FldErrMsg());
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

			// order_id
			$this->order_id->SetDbValueDef($rsnew, $this->order_id->CurrentValue, 0, $this->order_id->ReadOnly);

			// product_id
			$this->product_id->SetDbValueDef($rsnew, $this->product_id->CurrentValue, 0, $this->product_id->ReadOnly);

			// size
			$this->size->SetDbValueDef($rsnew, $this->size->CurrentValue, 0, $this->size->ReadOnly);

			// cut
			$this->cut->SetDbValueDef($rsnew, $this->cut->CurrentValue, 0, $this->cut->ReadOnly);

			// quantity
			$this->quantity->SetDbValueDef($rsnew, $this->quantity->CurrentValue, 0, $this->quantity->ReadOnly);

			// price
			$this->price->SetDbValueDef($rsnew, $this->price->CurrentValue, 0, $this->price->ReadOnly);

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
		$table = 'order_details';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'order_details';

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
if (!isset($order_details_edit)) $order_details_edit = new corder_details_edit();

// Page init
$order_details_edit->Page_Init();

// Page main
$order_details_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var order_details_edit = new ew_Page("order_details_edit");
order_details_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = order_details_edit.PageID; // For backward compatibility

// Form object
var forder_detailsedit = new ew_Form("forder_detailsedit");

// Validate form
forder_detailsedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_order_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($order_details->order_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_order_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($order_details->order_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_product_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($order_details->product_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_product_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($order_details->product_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_size"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($order_details->size->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_size"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($order_details->size->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_cut"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($order_details->cut->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_cut"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($order_details->cut->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_quantity"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($order_details->quantity->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_quantity"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($order_details->quantity->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_price"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($order_details->price->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_price"];
		if (elm && !ew_CheckNumber(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($order_details->price->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_date_added"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($order_details->date_added->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_date_added"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($order_details->date_added->FldErrMsg()) ?>");

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
forder_detailsedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
forder_detailsedit.ValidateRequired = true;
<?php } else { ?>
forder_detailsedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $order_details->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $order_details->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $order_details_edit->ShowPageHeader(); ?>
<?php
$order_details_edit->ShowMessage();
?>
<form name="forder_detailsedit" id="forder_detailsedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="order_details">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_order_detailsedit" class="ewTable">
<?php if ($order_details->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $order_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_order_details_id"><?php echo $order_details->id->FldCaption() ?></span></td>
		<td<?php echo $order_details->id->CellAttributes() ?>><span id="el_order_details_id">
<span<?php echo $order_details->id->ViewAttributes() ?>>
<?php echo $order_details->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($order_details->id->CurrentValue) ?>">
</span><?php echo $order_details->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($order_details->order_id->Visible) { // order_id ?>
	<tr id="r_order_id"<?php echo $order_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_order_details_order_id"><?php echo $order_details->order_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $order_details->order_id->CellAttributes() ?>><span id="el_order_details_order_id">
<input type="text" name="x_order_id" id="x_order_id" size="30" value="<?php echo $order_details->order_id->EditValue ?>"<?php echo $order_details->order_id->EditAttributes() ?>>
</span><?php echo $order_details->order_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($order_details->product_id->Visible) { // product_id ?>
	<tr id="r_product_id"<?php echo $order_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_order_details_product_id"><?php echo $order_details->product_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $order_details->product_id->CellAttributes() ?>><span id="el_order_details_product_id">
<input type="text" name="x_product_id" id="x_product_id" size="30" value="<?php echo $order_details->product_id->EditValue ?>"<?php echo $order_details->product_id->EditAttributes() ?>>
</span><?php echo $order_details->product_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($order_details->size->Visible) { // size ?>
	<tr id="r_size"<?php echo $order_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_order_details_size"><?php echo $order_details->size->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $order_details->size->CellAttributes() ?>><span id="el_order_details_size">
<input type="text" name="x_size" id="x_size" size="30" value="<?php echo $order_details->size->EditValue ?>"<?php echo $order_details->size->EditAttributes() ?>>
</span><?php echo $order_details->size->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($order_details->cut->Visible) { // cut ?>
	<tr id="r_cut"<?php echo $order_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_order_details_cut"><?php echo $order_details->cut->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $order_details->cut->CellAttributes() ?>><span id="el_order_details_cut">
<input type="text" name="x_cut" id="x_cut" size="30" value="<?php echo $order_details->cut->EditValue ?>"<?php echo $order_details->cut->EditAttributes() ?>>
</span><?php echo $order_details->cut->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($order_details->quantity->Visible) { // quantity ?>
	<tr id="r_quantity"<?php echo $order_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_order_details_quantity"><?php echo $order_details->quantity->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $order_details->quantity->CellAttributes() ?>><span id="el_order_details_quantity">
<input type="text" name="x_quantity" id="x_quantity" size="30" value="<?php echo $order_details->quantity->EditValue ?>"<?php echo $order_details->quantity->EditAttributes() ?>>
</span><?php echo $order_details->quantity->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($order_details->price->Visible) { // price ?>
	<tr id="r_price"<?php echo $order_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_order_details_price"><?php echo $order_details->price->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $order_details->price->CellAttributes() ?>><span id="el_order_details_price">
<input type="text" name="x_price" id="x_price" size="30" value="<?php echo $order_details->price->EditValue ?>"<?php echo $order_details->price->EditAttributes() ?>>
</span><?php echo $order_details->price->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($order_details->date_added->Visible) { // date_added ?>
	<tr id="r_date_added"<?php echo $order_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_order_details_date_added"><?php echo $order_details->date_added->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $order_details->date_added->CellAttributes() ?>><span id="el_order_details_date_added">
<input type="text" name="x_date_added" id="x_date_added" value="<?php echo $order_details->date_added->EditValue ?>"<?php echo $order_details->date_added->EditAttributes() ?>>
</span><?php echo $order_details->date_added->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
forder_detailsedit.Init();
</script>
<?php
$order_details_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$order_details_edit->Page_Terminate();
?>
