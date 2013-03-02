<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "ip2nation_countriesinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$ip2nation_countries_edit = NULL; // Initialize page object first

class cip2nation_countries_edit extends cip2nation_countries {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'ip2nation_countries';

	// Page object name
	var $PageObjName = 'ip2nation_countries_edit';

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

		// Table object (ip2nation_countries)
		if (!isset($GLOBALS["ip2nation_countries"])) {
			$GLOBALS["ip2nation_countries"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ip2nation_countries"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ip2nation_countries', TRUE);

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["country_code"] <> "")
			$this->country_code->setQueryStringValue($_GET["country_code"]);

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->country_code->CurrentValue == "")
			$this->Page_Terminate("ip2nation_countrieslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("ip2nation_countrieslist.php"); // No matching record, return to list
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
		if (!$this->country_code->FldIsDetailKey) {
			$this->country_code->setFormValue($objForm->GetValue("x_country_code"));
		}
		if (!$this->iso_code_2->FldIsDetailKey) {
			$this->iso_code_2->setFormValue($objForm->GetValue("x_iso_code_2"));
		}
		if (!$this->iso_code_3->FldIsDetailKey) {
			$this->iso_code_3->setFormValue($objForm->GetValue("x_iso_code_3"));
		}
		if (!$this->iso_country->FldIsDetailKey) {
			$this->iso_country->setFormValue($objForm->GetValue("x_iso_country"));
		}
		if (!$this->country_name->FldIsDetailKey) {
			$this->country_name->setFormValue($objForm->GetValue("x_country_name"));
		}
		if (!$this->delivery_charge->FldIsDetailKey) {
			$this->delivery_charge->setFormValue($objForm->GetValue("x_delivery_charge"));
		}
		if (!$this->phone_code->FldIsDetailKey) {
			$this->phone_code->setFormValue($objForm->GetValue("x_phone_code"));
		}
		if (!$this->lat->FldIsDetailKey) {
			$this->lat->setFormValue($objForm->GetValue("x_lat"));
		}
		if (!$this->lon->FldIsDetailKey) {
			$this->lon->setFormValue($objForm->GetValue("x_lon"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->country_code->CurrentValue = $this->country_code->FormValue;
		$this->iso_code_2->CurrentValue = $this->iso_code_2->FormValue;
		$this->iso_code_3->CurrentValue = $this->iso_code_3->FormValue;
		$this->iso_country->CurrentValue = $this->iso_country->FormValue;
		$this->country_name->CurrentValue = $this->country_name->FormValue;
		$this->delivery_charge->CurrentValue = $this->delivery_charge->FormValue;
		$this->phone_code->CurrentValue = $this->phone_code->FormValue;
		$this->lat->CurrentValue = $this->lat->FormValue;
		$this->lon->CurrentValue = $this->lon->FormValue;
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
		$this->country_code->setDbValue($rs->fields('country_code'));
		$this->iso_code_2->setDbValue($rs->fields('iso_code_2'));
		$this->iso_code_3->setDbValue($rs->fields('iso_code_3'));
		$this->iso_country->setDbValue($rs->fields('iso_country'));
		$this->country_name->setDbValue($rs->fields('country_name'));
		$this->delivery_charge->setDbValue($rs->fields('delivery_charge'));
		$this->phone_code->setDbValue($rs->fields('phone_code'));
		$this->lat->setDbValue($rs->fields('lat'));
		$this->lon->setDbValue($rs->fields('lon'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->delivery_charge->FormValue == $this->delivery_charge->CurrentValue && is_numeric(ew_StrToFloat($this->delivery_charge->CurrentValue)))
			$this->delivery_charge->CurrentValue = ew_StrToFloat($this->delivery_charge->CurrentValue);

		// Convert decimal values if posted back
		if ($this->lat->FormValue == $this->lat->CurrentValue && is_numeric(ew_StrToFloat($this->lat->CurrentValue)))
			$this->lat->CurrentValue = ew_StrToFloat($this->lat->CurrentValue);

		// Convert decimal values if posted back
		if ($this->lon->FormValue == $this->lon->CurrentValue && is_numeric(ew_StrToFloat($this->lon->CurrentValue)))
			$this->lon->CurrentValue = ew_StrToFloat($this->lon->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// country_code
		// iso_code_2
		// iso_code_3
		// iso_country
		// country_name
		// delivery_charge
		// phone_code
		// lat
		// lon

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// country_code
			$this->country_code->ViewValue = $this->country_code->CurrentValue;
			$this->country_code->ViewCustomAttributes = "";

			// iso_code_2
			$this->iso_code_2->ViewValue = $this->iso_code_2->CurrentValue;
			$this->iso_code_2->ViewCustomAttributes = "";

			// iso_code_3
			$this->iso_code_3->ViewValue = $this->iso_code_3->CurrentValue;
			$this->iso_code_3->ViewCustomAttributes = "";

			// iso_country
			$this->iso_country->ViewValue = $this->iso_country->CurrentValue;
			$this->iso_country->ViewCustomAttributes = "";

			// country_name
			$this->country_name->ViewValue = $this->country_name->CurrentValue;
			$this->country_name->ViewCustomAttributes = "";

			// delivery_charge
			$this->delivery_charge->ViewValue = $this->delivery_charge->CurrentValue;
			$this->delivery_charge->ViewCustomAttributes = "";

			// phone_code
			$this->phone_code->ViewValue = $this->phone_code->CurrentValue;
			$this->phone_code->ViewCustomAttributes = "";

			// lat
			$this->lat->ViewValue = $this->lat->CurrentValue;
			$this->lat->ViewCustomAttributes = "";

			// lon
			$this->lon->ViewValue = $this->lon->CurrentValue;
			$this->lon->ViewCustomAttributes = "";

			// country_code
			$this->country_code->LinkCustomAttributes = "";
			$this->country_code->HrefValue = "";
			$this->country_code->TooltipValue = "";

			// iso_code_2
			$this->iso_code_2->LinkCustomAttributes = "";
			$this->iso_code_2->HrefValue = "";
			$this->iso_code_2->TooltipValue = "";

			// iso_code_3
			$this->iso_code_3->LinkCustomAttributes = "";
			$this->iso_code_3->HrefValue = "";
			$this->iso_code_3->TooltipValue = "";

			// iso_country
			$this->iso_country->LinkCustomAttributes = "";
			$this->iso_country->HrefValue = "";
			$this->iso_country->TooltipValue = "";

			// country_name
			$this->country_name->LinkCustomAttributes = "";
			$this->country_name->HrefValue = "";
			$this->country_name->TooltipValue = "";

			// delivery_charge
			$this->delivery_charge->LinkCustomAttributes = "";
			$this->delivery_charge->HrefValue = "";
			$this->delivery_charge->TooltipValue = "";

			// phone_code
			$this->phone_code->LinkCustomAttributes = "";
			$this->phone_code->HrefValue = "";
			$this->phone_code->TooltipValue = "";

			// lat
			$this->lat->LinkCustomAttributes = "";
			$this->lat->HrefValue = "";
			$this->lat->TooltipValue = "";

			// lon
			$this->lon->LinkCustomAttributes = "";
			$this->lon->HrefValue = "";
			$this->lon->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// country_code
			$this->country_code->EditCustomAttributes = "";
			$this->country_code->EditValue = $this->country_code->CurrentValue;
			$this->country_code->ViewCustomAttributes = "";

			// iso_code_2
			$this->iso_code_2->EditCustomAttributes = "";
			$this->iso_code_2->EditValue = ew_HtmlEncode($this->iso_code_2->CurrentValue);

			// iso_code_3
			$this->iso_code_3->EditCustomAttributes = "";
			$this->iso_code_3->EditValue = ew_HtmlEncode($this->iso_code_3->CurrentValue);

			// iso_country
			$this->iso_country->EditCustomAttributes = "";
			$this->iso_country->EditValue = ew_HtmlEncode($this->iso_country->CurrentValue);

			// country_name
			$this->country_name->EditCustomAttributes = "";
			$this->country_name->EditValue = ew_HtmlEncode($this->country_name->CurrentValue);

			// delivery_charge
			$this->delivery_charge->EditCustomAttributes = "";
			$this->delivery_charge->EditValue = ew_HtmlEncode($this->delivery_charge->CurrentValue);
			if (strval($this->delivery_charge->EditValue) <> "" && is_numeric($this->delivery_charge->EditValue)) $this->delivery_charge->EditValue = ew_FormatNumber($this->delivery_charge->EditValue, -2, -1, -2, 0);

			// phone_code
			$this->phone_code->EditCustomAttributes = "";
			$this->phone_code->EditValue = ew_HtmlEncode($this->phone_code->CurrentValue);

			// lat
			$this->lat->EditCustomAttributes = "";
			$this->lat->EditValue = ew_HtmlEncode($this->lat->CurrentValue);
			if (strval($this->lat->EditValue) <> "" && is_numeric($this->lat->EditValue)) $this->lat->EditValue = ew_FormatNumber($this->lat->EditValue, -2, -1, -2, 0);

			// lon
			$this->lon->EditCustomAttributes = "";
			$this->lon->EditValue = ew_HtmlEncode($this->lon->CurrentValue);
			if (strval($this->lon->EditValue) <> "" && is_numeric($this->lon->EditValue)) $this->lon->EditValue = ew_FormatNumber($this->lon->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// country_code

			$this->country_code->HrefValue = "";

			// iso_code_2
			$this->iso_code_2->HrefValue = "";

			// iso_code_3
			$this->iso_code_3->HrefValue = "";

			// iso_country
			$this->iso_country->HrefValue = "";

			// country_name
			$this->country_name->HrefValue = "";

			// delivery_charge
			$this->delivery_charge->HrefValue = "";

			// phone_code
			$this->phone_code->HrefValue = "";

			// lat
			$this->lat->HrefValue = "";

			// lon
			$this->lon->HrefValue = "";
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
		if (!is_null($this->country_code->FormValue) && $this->country_code->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->country_code->FldCaption());
		}
		if (!is_null($this->iso_code_2->FormValue) && $this->iso_code_2->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->iso_code_2->FldCaption());
		}
		if (!is_null($this->iso_country->FormValue) && $this->iso_country->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->iso_country->FldCaption());
		}
		if (!is_null($this->country_name->FormValue) && $this->country_name->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->country_name->FldCaption());
		}
		if (!is_null($this->delivery_charge->FormValue) && $this->delivery_charge->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->delivery_charge->FldCaption());
		}
		if (!ew_CheckNumber($this->delivery_charge->FormValue)) {
			ew_AddMessage($gsFormError, $this->delivery_charge->FldErrMsg());
		}
		if (!is_null($this->phone_code->FormValue) && $this->phone_code->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->phone_code->FldCaption());
		}
		if (!ew_CheckInteger($this->phone_code->FormValue)) {
			ew_AddMessage($gsFormError, $this->phone_code->FldErrMsg());
		}
		if (!is_null($this->lat->FormValue) && $this->lat->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->lat->FldCaption());
		}
		if (!ew_CheckNumber($this->lat->FormValue)) {
			ew_AddMessage($gsFormError, $this->lat->FldErrMsg());
		}
		if (!is_null($this->lon->FormValue) && $this->lon->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->lon->FldCaption());
		}
		if (!ew_CheckNumber($this->lon->FormValue)) {
			ew_AddMessage($gsFormError, $this->lon->FldErrMsg());
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

			// country_code
			// iso_code_2

			$this->iso_code_2->SetDbValueDef($rsnew, $this->iso_code_2->CurrentValue, "", $this->iso_code_2->ReadOnly);

			// iso_code_3
			$this->iso_code_3->SetDbValueDef($rsnew, $this->iso_code_3->CurrentValue, NULL, $this->iso_code_3->ReadOnly);

			// iso_country
			$this->iso_country->SetDbValueDef($rsnew, $this->iso_country->CurrentValue, "", $this->iso_country->ReadOnly);

			// country_name
			$this->country_name->SetDbValueDef($rsnew, $this->country_name->CurrentValue, "", $this->country_name->ReadOnly);

			// delivery_charge
			$this->delivery_charge->SetDbValueDef($rsnew, $this->delivery_charge->CurrentValue, 0, $this->delivery_charge->ReadOnly);

			// phone_code
			$this->phone_code->SetDbValueDef($rsnew, $this->phone_code->CurrentValue, 0, $this->phone_code->ReadOnly);

			// lat
			$this->lat->SetDbValueDef($rsnew, $this->lat->CurrentValue, 0, $this->lat->ReadOnly);

			// lon
			$this->lon->SetDbValueDef($rsnew, $this->lon->CurrentValue, 0, $this->lon->ReadOnly);

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
		$table = 'ip2nation_countries';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'ip2nation_countries';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['country_code'];

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
if (!isset($ip2nation_countries_edit)) $ip2nation_countries_edit = new cip2nation_countries_edit();

// Page init
$ip2nation_countries_edit->Page_Init();

// Page main
$ip2nation_countries_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var ip2nation_countries_edit = new ew_Page("ip2nation_countries_edit");
ip2nation_countries_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = ip2nation_countries_edit.PageID; // For backward compatibility

// Form object
var fip2nation_countriesedit = new ew_Form("fip2nation_countriesedit");

// Validate form
fip2nation_countriesedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_country_code"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ip2nation_countries->country_code->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_iso_code_2"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ip2nation_countries->iso_code_2->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_iso_country"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ip2nation_countries->iso_country->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_country_name"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ip2nation_countries->country_name->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_delivery_charge"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ip2nation_countries->delivery_charge->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_delivery_charge"];
		if (elm && !ew_CheckNumber(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($ip2nation_countries->delivery_charge->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_phone_code"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ip2nation_countries->phone_code->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_phone_code"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($ip2nation_countries->phone_code->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_lat"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ip2nation_countries->lat->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_lat"];
		if (elm && !ew_CheckNumber(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($ip2nation_countries->lat->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_lon"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ip2nation_countries->lon->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_lon"];
		if (elm && !ew_CheckNumber(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($ip2nation_countries->lon->FldErrMsg()) ?>");

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
fip2nation_countriesedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fip2nation_countriesedit.ValidateRequired = true;
<?php } else { ?>
fip2nation_countriesedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $ip2nation_countries->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $ip2nation_countries->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $ip2nation_countries_edit->ShowPageHeader(); ?>
<?php
$ip2nation_countries_edit->ShowMessage();
?>
<form name="fip2nation_countriesedit" id="fip2nation_countriesedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="ip2nation_countries">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_ip2nation_countriesedit" class="ewTable">
<?php if ($ip2nation_countries->country_code->Visible) { // country_code ?>
	<tr id="r_country_code"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_country_code"><?php echo $ip2nation_countries->country_code->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ip2nation_countries->country_code->CellAttributes() ?>><span id="el_ip2nation_countries_country_code">
<span<?php echo $ip2nation_countries->country_code->ViewAttributes() ?>>
<?php echo $ip2nation_countries->country_code->EditValue ?></span>
<input type="hidden" name="x_country_code" id="x_country_code" value="<?php echo ew_HtmlEncode($ip2nation_countries->country_code->CurrentValue) ?>">
</span><?php echo $ip2nation_countries->country_code->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->iso_code_2->Visible) { // iso_code_2 ?>
	<tr id="r_iso_code_2"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_iso_code_2"><?php echo $ip2nation_countries->iso_code_2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ip2nation_countries->iso_code_2->CellAttributes() ?>><span id="el_ip2nation_countries_iso_code_2">
<input type="text" name="x_iso_code_2" id="x_iso_code_2" size="30" maxlength="2" value="<?php echo $ip2nation_countries->iso_code_2->EditValue ?>"<?php echo $ip2nation_countries->iso_code_2->EditAttributes() ?>>
</span><?php echo $ip2nation_countries->iso_code_2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->iso_code_3->Visible) { // iso_code_3 ?>
	<tr id="r_iso_code_3"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_iso_code_3"><?php echo $ip2nation_countries->iso_code_3->FldCaption() ?></span></td>
		<td<?php echo $ip2nation_countries->iso_code_3->CellAttributes() ?>><span id="el_ip2nation_countries_iso_code_3">
<input type="text" name="x_iso_code_3" id="x_iso_code_3" size="30" maxlength="3" value="<?php echo $ip2nation_countries->iso_code_3->EditValue ?>"<?php echo $ip2nation_countries->iso_code_3->EditAttributes() ?>>
</span><?php echo $ip2nation_countries->iso_code_3->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->iso_country->Visible) { // iso_country ?>
	<tr id="r_iso_country"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_iso_country"><?php echo $ip2nation_countries->iso_country->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ip2nation_countries->iso_country->CellAttributes() ?>><span id="el_ip2nation_countries_iso_country">
<input type="text" name="x_iso_country" id="x_iso_country" size="30" maxlength="255" value="<?php echo $ip2nation_countries->iso_country->EditValue ?>"<?php echo $ip2nation_countries->iso_country->EditAttributes() ?>>
</span><?php echo $ip2nation_countries->iso_country->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->country_name->Visible) { // country_name ?>
	<tr id="r_country_name"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_country_name"><?php echo $ip2nation_countries->country_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ip2nation_countries->country_name->CellAttributes() ?>><span id="el_ip2nation_countries_country_name">
<input type="text" name="x_country_name" id="x_country_name" size="30" maxlength="255" value="<?php echo $ip2nation_countries->country_name->EditValue ?>"<?php echo $ip2nation_countries->country_name->EditAttributes() ?>>
</span><?php echo $ip2nation_countries->country_name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->delivery_charge->Visible) { // delivery_charge ?>
	<tr id="r_delivery_charge"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_delivery_charge"><?php echo $ip2nation_countries->delivery_charge->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ip2nation_countries->delivery_charge->CellAttributes() ?>><span id="el_ip2nation_countries_delivery_charge">
<input type="text" name="x_delivery_charge" id="x_delivery_charge" size="30" value="<?php echo $ip2nation_countries->delivery_charge->EditValue ?>"<?php echo $ip2nation_countries->delivery_charge->EditAttributes() ?>>
</span><?php echo $ip2nation_countries->delivery_charge->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->phone_code->Visible) { // phone_code ?>
	<tr id="r_phone_code"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_phone_code"><?php echo $ip2nation_countries->phone_code->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ip2nation_countries->phone_code->CellAttributes() ?>><span id="el_ip2nation_countries_phone_code">
<input type="text" name="x_phone_code" id="x_phone_code" size="30" value="<?php echo $ip2nation_countries->phone_code->EditValue ?>"<?php echo $ip2nation_countries->phone_code->EditAttributes() ?>>
</span><?php echo $ip2nation_countries->phone_code->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->lat->Visible) { // lat ?>
	<tr id="r_lat"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_lat"><?php echo $ip2nation_countries->lat->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ip2nation_countries->lat->CellAttributes() ?>><span id="el_ip2nation_countries_lat">
<input type="text" name="x_lat" id="x_lat" size="30" value="<?php echo $ip2nation_countries->lat->EditValue ?>"<?php echo $ip2nation_countries->lat->EditAttributes() ?>>
</span><?php echo $ip2nation_countries->lat->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->lon->Visible) { // lon ?>
	<tr id="r_lon"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_lon"><?php echo $ip2nation_countries->lon->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ip2nation_countries->lon->CellAttributes() ?>><span id="el_ip2nation_countries_lon">
<input type="text" name="x_lon" id="x_lon" size="30" value="<?php echo $ip2nation_countries->lon->EditValue ?>"<?php echo $ip2nation_countries->lon->EditAttributes() ?>>
</span><?php echo $ip2nation_countries->lon->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
fip2nation_countriesedit.Init();
</script>
<?php
$ip2nation_countries_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ip2nation_countries_edit->Page_Terminate();
?>
