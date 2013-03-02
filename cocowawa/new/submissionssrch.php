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

$submissions_search = NULL; // Initialize page object first

class csubmissions_search extends csubmissions {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'submissions';

	// Page object name
	var $PageObjName = 'submissions_search';

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

		// Table object (submissions)
		if (!isset($GLOBALS["submissions"])) {
			$GLOBALS["submissions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["submissions"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$this->Page_Terminate("submissionslist.php" . "?" . $sSrchStr); // Go to list page
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->id); // id
		$this->BuildSearchUrl($sSrchUrl, $this->competition_id); // competition_id
		$this->BuildSearchUrl($sSrchUrl, $this->user_id); // user_id
		$this->BuildSearchUrl($sSrchUrl, $this->title); // title
		$this->BuildSearchUrl($sSrchUrl, $this->comments); // comments
		$this->BuildSearchUrl($sSrchUrl, $this->newsletter); // newsletter
		$this->BuildSearchUrl($sSrchUrl, $this->submission_date); // submission_date
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && is_numeric($FldVal) && is_numeric($FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && is_numeric($FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && is_numeric($FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id

		$this->id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_id"));
		$this->id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id");

		// competition_id
		$this->competition_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_competition_id"));
		$this->competition_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_competition_id");

		// user_id
		$this->user_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_user_id"));
		$this->user_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_user_id");

		// title
		$this->title->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_title"));
		$this->title->AdvancedSearch->SearchOperator = $objForm->GetValue("z_title");

		// comments
		$this->comments->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_comments"));
		$this->comments->AdvancedSearch->SearchOperator = $objForm->GetValue("z_comments");

		// newsletter
		$this->newsletter->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_newsletter"));
		$this->newsletter->AdvancedSearch->SearchOperator = $objForm->GetValue("z_newsletter");

		// submission_date
		$this->submission_date->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_submission_date"));
		$this->submission_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_submission_date");
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

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);

			// competition_id
			$this->competition_id->EditCustomAttributes = "";
			$this->competition_id->EditValue = ew_HtmlEncode($this->competition_id->AdvancedSearch->SearchValue);

			// user_id
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->AdvancedSearch->SearchValue);

			// title
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->AdvancedSearch->SearchValue);

			// comments
			$this->comments->EditCustomAttributes = "";
			$this->comments->EditValue = ew_HtmlEncode($this->comments->AdvancedSearch->SearchValue);

			// newsletter
			$this->newsletter->EditCustomAttributes = "";
			$this->newsletter->EditValue = ew_HtmlEncode($this->newsletter->AdvancedSearch->SearchValue);

			// submission_date
			$this->submission_date->EditCustomAttributes = "";
			$this->submission_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->submission_date->AdvancedSearch->SearchValue, 7), 7));
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->competition_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->competition_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->user_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->user_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->newsletter->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->newsletter->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->submission_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->submission_date->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->id->AdvancedSearch->SearchValue = $this->getAdvancedSearch("x_id");
		$this->competition_id->AdvancedSearch->SearchValue = $this->getAdvancedSearch("x_competition_id");
		$this->user_id->AdvancedSearch->SearchValue = $this->getAdvancedSearch("x_user_id");
		$this->title->AdvancedSearch->SearchValue = $this->getAdvancedSearch("x_title");
		$this->comments->AdvancedSearch->SearchValue = $this->getAdvancedSearch("x_comments");
		$this->newsletter->AdvancedSearch->SearchValue = $this->getAdvancedSearch("x_newsletter");
		$this->submission_date->AdvancedSearch->SearchValue = $this->getAdvancedSearch("x_submission_date");
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
if (!isset($submissions_search)) $submissions_search = new csubmissions_search();

// Page init
$submissions_search->Page_Init();

// Page main
$submissions_search->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var submissions_search = new ew_Page("submissions_search");
submissions_search.PageID = "search"; // Page ID
var EW_PAGE_ID = submissions_search.PageID; // For backward compatibility

// Form object
var fsubmissionssearch = new ew_Form("fsubmissionssearch");

// Form_CustomValidate event
fsubmissionssearch.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsubmissionssearch.ValidateRequired = true;
<?php } else { ?>
fsubmissionssearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search
// Validate function for search

fsubmissionssearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = fobj.elements["x" + infix + "_id"];
	if (elm && !ew_CheckInteger(elm.value))
		return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submissions->id->FldErrMsg()) ?>");
	elm = fobj.elements["x" + infix + "_competition_id"];
	if (elm && !ew_CheckInteger(elm.value))
		return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submissions->competition_id->FldErrMsg()) ?>");
	elm = fobj.elements["x" + infix + "_user_id"];
	if (elm && !ew_CheckInteger(elm.value))
		return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submissions->user_id->FldErrMsg()) ?>");
	elm = fobj.elements["x" + infix + "_newsletter"];
	if (elm && !ew_CheckInteger(elm.value))
		return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submissions->newsletter->FldErrMsg()) ?>");
	elm = fobj.elements["x" + infix + "_submission_date"];
	if (elm && !ew_CheckEuroDate(elm.value))
		return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submissions->submission_date->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj, infix);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fsubmissionssearch.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsubmissionssearch.ValidateRequired = true; // uses JavaScript validation
<?php } else { ?>
fsubmissionssearch.ValidateRequired = false; // no JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Search") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $submissions->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $submissions->getReturnUrl() ?>"><?php echo $Language->Phrase("BackToList") ?></a></p>
<?php $submissions_search->ShowPageHeader(); ?>
<?php
$submissions_search->ShowMessage();
?>
<form name="fsubmissionssearch" id="fsubmissionssearch" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="submissions">
<input type="hidden" name="a_search" id="a_search" value="S">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_submissionssearch" class="ewTable">
<?php if ($submissions->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $submissions->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submissions_id"><?php echo $submissions->id->FldCaption() ?></span></td>
		<td class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></td>
		<td<?php echo $submissions->id->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_submissions_id" class="phpmaker">
<input type="text" name="x_id" id="x_id" value="<?php echo $submissions->id->EditValue ?>"<?php echo $submissions->id->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($submissions->competition_id->Visible) { // competition_id ?>
	<tr id="r_competition_id"<?php echo $submissions->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submissions_competition_id"><?php echo $submissions->competition_id->FldCaption() ?></span></td>
		<td class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_competition_id" id="z_competition_id" value="="></td>
		<td<?php echo $submissions->competition_id->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_submissions_competition_id" class="phpmaker">
<input type="text" name="x_competition_id" id="x_competition_id" size="30" value="<?php echo $submissions->competition_id->EditValue ?>"<?php echo $submissions->competition_id->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($submissions->user_id->Visible) { // user_id ?>
	<tr id="r_user_id"<?php echo $submissions->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submissions_user_id"><?php echo $submissions->user_id->FldCaption() ?></span></td>
		<td class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_user_id" id="z_user_id" value="="></td>
		<td<?php echo $submissions->user_id->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_submissions_user_id" class="phpmaker">
<input type="text" name="x_user_id" id="x_user_id" size="30" value="<?php echo $submissions->user_id->EditValue ?>"<?php echo $submissions->user_id->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($submissions->title->Visible) { // title ?>
	<tr id="r_title"<?php echo $submissions->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submissions_title"><?php echo $submissions->title->FldCaption() ?></span></td>
		<td class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_title" id="z_title" value="LIKE"></td>
		<td<?php echo $submissions->title->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_submissions_title" class="phpmaker">
<input type="text" name="x_title" id="x_title" size="30" maxlength="128" value="<?php echo $submissions->title->EditValue ?>"<?php echo $submissions->title->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($submissions->comments->Visible) { // comments ?>
	<tr id="r_comments"<?php echo $submissions->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submissions_comments"><?php echo $submissions->comments->FldCaption() ?></span></td>
		<td class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_comments" id="z_comments" value="LIKE"></td>
		<td<?php echo $submissions->comments->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_submissions_comments" class="phpmaker">
<input type="text" name="x_comments" id="x_comments" size="30" maxlength="128" value="<?php echo $submissions->comments->EditValue ?>"<?php echo $submissions->comments->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($submissions->newsletter->Visible) { // newsletter ?>
	<tr id="r_newsletter"<?php echo $submissions->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submissions_newsletter"><?php echo $submissions->newsletter->FldCaption() ?></span></td>
		<td class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_newsletter" id="z_newsletter" value="="></td>
		<td<?php echo $submissions->newsletter->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_submissions_newsletter" class="phpmaker">
<input type="text" name="x_newsletter" id="x_newsletter" size="30" value="<?php echo $submissions->newsletter->EditValue ?>"<?php echo $submissions->newsletter->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($submissions->submission_date->Visible) { // submission_date ?>
	<tr id="r_submission_date"<?php echo $submissions->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_submissions_submission_date"><?php echo $submissions->submission_date->FldCaption() ?></span></td>
		<td class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_submission_date" id="z_submission_date" value="="></td>
		<td<?php echo $submissions->submission_date->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_submissions_submission_date" class="phpmaker">
<input type="text" name="x_submission_date" id="x_submission_date" value="<?php echo $submissions->submission_date->EditValue ?>"<?php echo $submissions->submission_date->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("Search")) ?>">
<input type="button" name="Reset" value="<?php echo ew_BtnCaption($Language->Phrase("Reset")) ?>" onclick="ew_ClearForm(this.form);">
</form>
<script type="text/javascript">
fsubmissionssearch.Init();
</script>
<?php
$submissions_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$submissions_search->Page_Terminate();
?>
