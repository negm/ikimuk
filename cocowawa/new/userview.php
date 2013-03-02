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

$user_view = NULL; // Initialize page object first

class cuser_view extends cuser {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'user';

	// Page object name
	var $PageObjName = 'user_view';

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

		// Table object (user)
		if (!isset($GLOBALS["user"])) {
			$GLOBALS["user"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["user"];
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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'user', TRUE);

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
				$sReturnUrl = "userlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "userlist.php"; // No matching record, return to list
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
			$sReturnUrl = "userlist.php"; // Not page request, return to list
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

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

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
		$item->Body = "<a name=\"emf_user\" id=\"emf_user\" href=\"javascript:void(0);\" onclick=\"ew_EmailDialogShow({lnk:'emf_user',hdr:ewLanguage.Phrase('ExportToEmail'),key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($user_view)) $user_view = new cuser_view();

// Page init
$user_view->Page_Init();

// Page main
$user_view->Page_Main();
?>
<?php include_once "header.php" ?>
<?php if ($user->Export == "") { ?>
<script type="text/javascript">

// Page object
var user_view = new ew_Page("user_view");
user_view.PageID = "view"; // Page ID
var EW_PAGE_ID = user_view.PageID; // For backward compatibility

// Form object
var fuserview = new ew_Form("fuserview");

// Form_CustomValidate event
fuserview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuserview.ValidateRequired = true;
<?php } else { ?>
fuserview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $user->TableCaption() ?>&nbsp;&nbsp;</span><?php $user_view->ExportOptions->Render("body"); ?>
</p>
<?php if ($user->Export == "") { ?>
<p class="phpmaker">
<a href="<?php echo $user_view->ListUrl ?>"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($user_view->AddUrl <> "") { ?>
<a href="<?php echo $user_view->AddUrl ?>"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($user_view->EditUrl <> "") { ?>
<a href="<?php echo $user_view->EditUrl ?>"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($user_view->CopyUrl <> "") { ?>
<a href="<?php echo $user_view->CopyUrl ?>"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
</p>
<?php } ?>
<?php $user_view->ShowPageHeader(); ?>
<?php
$user_view->ShowMessage();
?>
<form name="fuserview" id="fuserview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="user">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_userview" class="ewTable">
<?php if ($user->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_id"><?php echo $user->id->FldCaption() ?></span></td>
		<td<?php echo $user->id->CellAttributes() ?>><span id="el_user_id">
<span<?php echo $user->id->ViewAttributes() ?>>
<?php echo $user->id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($user->fbid->Visible) { // fbid ?>
	<tr id="r_fbid"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_fbid"><?php echo $user->fbid->FldCaption() ?></span></td>
		<td<?php echo $user->fbid->CellAttributes() ?>><span id="el_user_fbid">
<span<?php echo $user->fbid->ViewAttributes() ?>>
<?php echo $user->fbid->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($user->name->Visible) { // name ?>
	<tr id="r_name"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_name"><?php echo $user->name->FldCaption() ?></span></td>
		<td<?php echo $user->name->CellAttributes() ?>><span id="el_user_name">
<span<?php echo $user->name->ViewAttributes() ?>>
<?php echo $user->name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($user->_email->Visible) { // email ?>
	<tr id="r__email"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user__email"><?php echo $user->_email->FldCaption() ?></span></td>
		<td<?php echo $user->_email->CellAttributes() ?>><span id="el_user__email">
<span<?php echo $user->_email->ViewAttributes() ?>>
<?php echo $user->_email->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($user->password->Visible) { // password ?>
	<tr id="r_password"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_password"><?php echo $user->password->FldCaption() ?></span></td>
		<td<?php echo $user->password->CellAttributes() ?>><span id="el_user_password">
<span<?php echo $user->password->ViewAttributes() ?>>
<?php echo $user->password->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($user->validated_mobile->Visible) { // validated_mobile ?>
	<tr id="r_validated_mobile"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_validated_mobile"><?php echo $user->validated_mobile->FldCaption() ?></span></td>
		<td<?php echo $user->validated_mobile->CellAttributes() ?>><span id="el_user_validated_mobile">
<span<?php echo $user->validated_mobile->ViewAttributes() ?>>
<?php echo $user->validated_mobile->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($user->role_id->Visible) { // role_id ?>
	<tr id="r_role_id"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_role_id"><?php echo $user->role_id->FldCaption() ?></span></td>
		<td<?php echo $user->role_id->CellAttributes() ?>><span id="el_user_role_id">
<span<?php echo $user->role_id->ViewAttributes() ?>>
<?php echo $user->role_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($user->image->Visible) { // image ?>
	<tr id="r_image"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_image"><?php echo $user->image->FldCaption() ?></span></td>
		<td<?php echo $user->image->CellAttributes() ?>><span id="el_user_image">
<span<?php echo $user->image->ViewAttributes() ?>>
<?php echo $user->image->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($user->newsletter->Visible) { // newsletter ?>
	<tr id="r_newsletter"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_newsletter"><?php echo $user->newsletter->FldCaption() ?></span></td>
		<td<?php echo $user->newsletter->CellAttributes() ?>><span id="el_user_newsletter">
<span<?php echo $user->newsletter->ViewAttributes() ?>>
<?php echo $user->newsletter->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($user->points->Visible) { // points ?>
	<tr id="r_points"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_points"><?php echo $user->points->FldCaption() ?></span></td>
		<td<?php echo $user->points->CellAttributes() ?>><span id="el_user_points">
<span<?php echo $user->points->ViewAttributes() ?>>
<?php echo $user->points->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($user->last_modified->Visible) { // last_modified ?>
	<tr id="r_last_modified"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_last_modified"><?php echo $user->last_modified->FldCaption() ?></span></td>
		<td<?php echo $user->last_modified->CellAttributes() ?>><span id="el_user_last_modified">
<span<?php echo $user->last_modified->ViewAttributes() ?>>
<?php echo $user->last_modified->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($user->p2->Visible) { // p2 ?>
	<tr id="r_p2"<?php echo $user->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_user_p2"><?php echo $user->p2->FldCaption() ?></span></td>
		<td<?php echo $user->p2->CellAttributes() ?>><span id="el_user_p2">
<span<?php echo $user->p2->ViewAttributes() ?>>
<?php echo $user->p2->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<p>
<script type="text/javascript">
fuserview.Init();
</script>
<?php
$user_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($user->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$user_view->Page_Terminate();
?>
