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

$ip2nation_countries_view = NULL; // Initialize page object first

class cip2nation_countries_view extends cip2nation_countries {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'ip2nation_countries';

	// Page object name
	var $PageObjName = 'ip2nation_countries_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["country_code"] <> "") {
			$this->RecKey["country_code"] = $_GET["country_code"];
			$KeyUrl .= "&country_code=" . urlencode($this->RecKey["country_code"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ip2nation_countries', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";
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

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["country_code"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["country_code"]);
		}

		// Setup export options
		$this->SetupExportOptions();
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
	var $ExportOptions; // Export options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["country_code"] <> "") {
				$this->country_code->setQueryStringValue($_GET["country_code"]);
				$this->RecKey["country_code"] = $this->country_code->QueryStringValue;
			} else {
				$sReturnUrl = "ip2nation_countrieslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "ip2nation_countrieslist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				if ($this->Export == "email" && $this->ExportReturnUrl() == ew_CurrentPage()) // Default return page
					$this->setExportReturnUrl($this->GetViewUrl()); // Add key
				$this->ExportData();
				if ($this->Export <> "email")
					$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "ip2nation_countrieslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a name=\"emf_ip2nation_countries\" id=\"emf_ip2nation_countries\" href=\"javascript:void(0);\" onclick=\"ew_EmailDialogShow({lnk:'emf_ip2nation_countries',hdr:ewLanguage.Phrase('ExportToEmail'),key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Hide options for export/action
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs < 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "v");
		$ParentTable = "";
		if ($bSelectLimit) {
			$StartRec = 1;
			$StopRec = $this->DisplayRecs < 0 ? $this->TotalRecs : $this->DisplayRecs;;
		} else {
			$StartRec = $this->StartRec;
			$StopRec = $this->StopRec;
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$ExportDoc->Text .= $sHeader;
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$ExportDoc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$ExportDoc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$ExportDoc->Export();
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($ip2nation_countries_view)) $ip2nation_countries_view = new cip2nation_countries_view();

// Page init
$ip2nation_countries_view->Page_Init();

// Page main
$ip2nation_countries_view->Page_Main();
?>
<?php include_once "header.php" ?>
<?php if ($ip2nation_countries->Export == "") { ?>
<script type="text/javascript">

// Page object
var ip2nation_countries_view = new ew_Page("ip2nation_countries_view");
ip2nation_countries_view.PageID = "view"; // Page ID
var EW_PAGE_ID = ip2nation_countries_view.PageID; // For backward compatibility

// Form object
var fip2nation_countriesview = new ew_Form("fip2nation_countriesview");

// Form_CustomValidate event
fip2nation_countriesview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fip2nation_countriesview.ValidateRequired = true;
<?php } else { ?>
fip2nation_countriesview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $ip2nation_countries->TableCaption() ?>&nbsp;&nbsp;</span><?php $ip2nation_countries_view->ExportOptions->Render("body"); ?>
</p>
<?php if ($ip2nation_countries->Export == "") { ?>
<p class="phpmaker">
<a href="<?php echo $ip2nation_countries_view->ListUrl ?>"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($ip2nation_countries_view->AddUrl <> "") { ?>
<a href="<?php echo $ip2nation_countries_view->AddUrl ?>"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($ip2nation_countries_view->EditUrl <> "") { ?>
<a href="<?php echo $ip2nation_countries_view->EditUrl ?>"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($ip2nation_countries_view->CopyUrl <> "") { ?>
<a href="<?php echo $ip2nation_countries_view->CopyUrl ?>"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
</p>
<?php } ?>
<?php $ip2nation_countries_view->ShowPageHeader(); ?>
<?php
$ip2nation_countries_view->ShowMessage();
?>
<form name="fip2nation_countriesview" id="fip2nation_countriesview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="ip2nation_countries">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_ip2nation_countriesview" class="ewTable">
<?php if ($ip2nation_countries->country_code->Visible) { // country_code ?>
	<tr id="r_country_code"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_country_code"><?php echo $ip2nation_countries->country_code->FldCaption() ?></span></td>
		<td<?php echo $ip2nation_countries->country_code->CellAttributes() ?>><span id="el_ip2nation_countries_country_code">
<span<?php echo $ip2nation_countries->country_code->ViewAttributes() ?>>
<?php echo $ip2nation_countries->country_code->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->iso_code_2->Visible) { // iso_code_2 ?>
	<tr id="r_iso_code_2"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_iso_code_2"><?php echo $ip2nation_countries->iso_code_2->FldCaption() ?></span></td>
		<td<?php echo $ip2nation_countries->iso_code_2->CellAttributes() ?>><span id="el_ip2nation_countries_iso_code_2">
<span<?php echo $ip2nation_countries->iso_code_2->ViewAttributes() ?>>
<?php echo $ip2nation_countries->iso_code_2->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->iso_code_3->Visible) { // iso_code_3 ?>
	<tr id="r_iso_code_3"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_iso_code_3"><?php echo $ip2nation_countries->iso_code_3->FldCaption() ?></span></td>
		<td<?php echo $ip2nation_countries->iso_code_3->CellAttributes() ?>><span id="el_ip2nation_countries_iso_code_3">
<span<?php echo $ip2nation_countries->iso_code_3->ViewAttributes() ?>>
<?php echo $ip2nation_countries->iso_code_3->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->iso_country->Visible) { // iso_country ?>
	<tr id="r_iso_country"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_iso_country"><?php echo $ip2nation_countries->iso_country->FldCaption() ?></span></td>
		<td<?php echo $ip2nation_countries->iso_country->CellAttributes() ?>><span id="el_ip2nation_countries_iso_country">
<span<?php echo $ip2nation_countries->iso_country->ViewAttributes() ?>>
<?php echo $ip2nation_countries->iso_country->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->country_name->Visible) { // country_name ?>
	<tr id="r_country_name"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_country_name"><?php echo $ip2nation_countries->country_name->FldCaption() ?></span></td>
		<td<?php echo $ip2nation_countries->country_name->CellAttributes() ?>><span id="el_ip2nation_countries_country_name">
<span<?php echo $ip2nation_countries->country_name->ViewAttributes() ?>>
<?php echo $ip2nation_countries->country_name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->delivery_charge->Visible) { // delivery_charge ?>
	<tr id="r_delivery_charge"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_delivery_charge"><?php echo $ip2nation_countries->delivery_charge->FldCaption() ?></span></td>
		<td<?php echo $ip2nation_countries->delivery_charge->CellAttributes() ?>><span id="el_ip2nation_countries_delivery_charge">
<span<?php echo $ip2nation_countries->delivery_charge->ViewAttributes() ?>>
<?php echo $ip2nation_countries->delivery_charge->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->phone_code->Visible) { // phone_code ?>
	<tr id="r_phone_code"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_phone_code"><?php echo $ip2nation_countries->phone_code->FldCaption() ?></span></td>
		<td<?php echo $ip2nation_countries->phone_code->CellAttributes() ?>><span id="el_ip2nation_countries_phone_code">
<span<?php echo $ip2nation_countries->phone_code->ViewAttributes() ?>>
<?php echo $ip2nation_countries->phone_code->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->lat->Visible) { // lat ?>
	<tr id="r_lat"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_lat"><?php echo $ip2nation_countries->lat->FldCaption() ?></span></td>
		<td<?php echo $ip2nation_countries->lat->CellAttributes() ?>><span id="el_ip2nation_countries_lat">
<span<?php echo $ip2nation_countries->lat->ViewAttributes() ?>>
<?php echo $ip2nation_countries->lat->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($ip2nation_countries->lon->Visible) { // lon ?>
	<tr id="r_lon"<?php echo $ip2nation_countries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_ip2nation_countries_lon"><?php echo $ip2nation_countries->lon->FldCaption() ?></span></td>
		<td<?php echo $ip2nation_countries->lon->CellAttributes() ?>><span id="el_ip2nation_countries_lon">
<span<?php echo $ip2nation_countries->lon->ViewAttributes() ?>>
<?php echo $ip2nation_countries->lon->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<p>
<script type="text/javascript">
fip2nation_countriesview.Init();
</script>
<?php
$ip2nation_countries_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($ip2nation_countries->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$ip2nation_countries_view->Page_Terminate();
?>
