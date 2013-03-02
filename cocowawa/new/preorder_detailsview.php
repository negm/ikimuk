<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "preorder_detailsinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$preorder_details_view = NULL; // Initialize page object first

class cpreorder_details_view extends cpreorder_details {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'preorder_details';

	// Page object name
	var $PageObjName = 'preorder_details_view';

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

		// Table object (preorder_details)
		if (!isset($GLOBALS["preorder_details"])) {
			$GLOBALS["preorder_details"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["preorder_details"];
		}
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&id=" . urlencode($this->RecKey["id"]);
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
			define("EW_TABLE_NAME", 'preorder_details', TRUE);

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
		if (@$_GET["id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["id"]);
		}

		// Setup export options
		$this->SetupExportOptions();
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
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} else {
				$sReturnUrl = "preorder_detailslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "preorder_detailslist.php"; // No matching record, return to list
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
			$sReturnUrl = "preorder_detailslist.php"; // Not page request, return to list
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
		$this->id->setDbValue($rs->fields('id'));
		$this->preorder_id->setDbValue($rs->fields('preorder_id'));
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();

		// Convert decimal values if posted back
		if ($this->price->FormValue == $this->price->CurrentValue && is_numeric(ew_StrToFloat($this->price->CurrentValue)))
			$this->price->CurrentValue = ew_StrToFloat($this->price->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// preorder_id
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

			// preorder_id
			$this->preorder_id->ViewValue = $this->preorder_id->CurrentValue;
			$this->preorder_id->ViewCustomAttributes = "";

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

			// preorder_id
			$this->preorder_id->LinkCustomAttributes = "";
			$this->preorder_id->HrefValue = "";
			$this->preorder_id->TooltipValue = "";

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
		$item->Body = "<a name=\"emf_preorder_details\" id=\"emf_preorder_details\" href=\"javascript:void(0);\" onclick=\"ew_EmailDialogShow({lnk:'emf_preorder_details',hdr:ewLanguage.Phrase('ExportToEmail'),key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($preorder_details_view)) $preorder_details_view = new cpreorder_details_view();

// Page init
$preorder_details_view->Page_Init();

// Page main
$preorder_details_view->Page_Main();
?>
<?php include_once "header.php" ?>
<?php if ($preorder_details->Export == "") { ?>
<script type="text/javascript">

// Page object
var preorder_details_view = new ew_Page("preorder_details_view");
preorder_details_view.PageID = "view"; // Page ID
var EW_PAGE_ID = preorder_details_view.PageID; // For backward compatibility

// Form object
var fpreorder_detailsview = new ew_Form("fpreorder_detailsview");

// Form_CustomValidate event
fpreorder_detailsview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpreorder_detailsview.ValidateRequired = true;
<?php } else { ?>
fpreorder_detailsview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $preorder_details->TableCaption() ?>&nbsp;&nbsp;</span><?php $preorder_details_view->ExportOptions->Render("body"); ?>
</p>
<?php if ($preorder_details->Export == "") { ?>
<p class="phpmaker">
<a href="<?php echo $preorder_details_view->ListUrl ?>"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($preorder_details_view->AddUrl <> "") { ?>
<a href="<?php echo $preorder_details_view->AddUrl ?>"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($preorder_details_view->EditUrl <> "") { ?>
<a href="<?php echo $preorder_details_view->EditUrl ?>"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($preorder_details_view->CopyUrl <> "") { ?>
<a href="<?php echo $preorder_details_view->CopyUrl ?>"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
</p>
<?php } ?>
<?php $preorder_details_view->ShowPageHeader(); ?>
<?php
$preorder_details_view->ShowMessage();
?>
<form name="fpreorder_detailsview" id="fpreorder_detailsview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="preorder_details">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_preorder_detailsview" class="ewTable">
<?php if ($preorder_details->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $preorder_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_details_id"><?php echo $preorder_details->id->FldCaption() ?></span></td>
		<td<?php echo $preorder_details->id->CellAttributes() ?>><span id="el_preorder_details_id">
<span<?php echo $preorder_details->id->ViewAttributes() ?>>
<?php echo $preorder_details->id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($preorder_details->preorder_id->Visible) { // preorder_id ?>
	<tr id="r_preorder_id"<?php echo $preorder_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_details_preorder_id"><?php echo $preorder_details->preorder_id->FldCaption() ?></span></td>
		<td<?php echo $preorder_details->preorder_id->CellAttributes() ?>><span id="el_preorder_details_preorder_id">
<span<?php echo $preorder_details->preorder_id->ViewAttributes() ?>>
<?php echo $preorder_details->preorder_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($preorder_details->product_id->Visible) { // product_id ?>
	<tr id="r_product_id"<?php echo $preorder_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_details_product_id"><?php echo $preorder_details->product_id->FldCaption() ?></span></td>
		<td<?php echo $preorder_details->product_id->CellAttributes() ?>><span id="el_preorder_details_product_id">
<span<?php echo $preorder_details->product_id->ViewAttributes() ?>>
<?php echo $preorder_details->product_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($preorder_details->size->Visible) { // size ?>
	<tr id="r_size"<?php echo $preorder_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_details_size"><?php echo $preorder_details->size->FldCaption() ?></span></td>
		<td<?php echo $preorder_details->size->CellAttributes() ?>><span id="el_preorder_details_size">
<span<?php echo $preorder_details->size->ViewAttributes() ?>>
<?php echo $preorder_details->size->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($preorder_details->cut->Visible) { // cut ?>
	<tr id="r_cut"<?php echo $preorder_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_details_cut"><?php echo $preorder_details->cut->FldCaption() ?></span></td>
		<td<?php echo $preorder_details->cut->CellAttributes() ?>><span id="el_preorder_details_cut">
<span<?php echo $preorder_details->cut->ViewAttributes() ?>>
<?php echo $preorder_details->cut->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($preorder_details->quantity->Visible) { // quantity ?>
	<tr id="r_quantity"<?php echo $preorder_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_details_quantity"><?php echo $preorder_details->quantity->FldCaption() ?></span></td>
		<td<?php echo $preorder_details->quantity->CellAttributes() ?>><span id="el_preorder_details_quantity">
<span<?php echo $preorder_details->quantity->ViewAttributes() ?>>
<?php echo $preorder_details->quantity->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($preorder_details->price->Visible) { // price ?>
	<tr id="r_price"<?php echo $preorder_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_details_price"><?php echo $preorder_details->price->FldCaption() ?></span></td>
		<td<?php echo $preorder_details->price->CellAttributes() ?>><span id="el_preorder_details_price">
<span<?php echo $preorder_details->price->ViewAttributes() ?>>
<?php echo $preorder_details->price->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($preorder_details->date_added->Visible) { // date_added ?>
	<tr id="r_date_added"<?php echo $preorder_details->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_preorder_details_date_added"><?php echo $preorder_details->date_added->FldCaption() ?></span></td>
		<td<?php echo $preorder_details->date_added->CellAttributes() ?>><span id="el_preorder_details_date_added">
<span<?php echo $preorder_details->date_added->ViewAttributes() ?>>
<?php echo $preorder_details->date_added->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<p>
<script type="text/javascript">
fpreorder_detailsview.Init();
</script>
<?php
$preorder_details_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($preorder_details->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$preorder_details_view->Page_Terminate();
?>
