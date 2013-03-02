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

$preorder_details_list = NULL; // Initialize page object first

class cpreorder_details_list extends cpreorder_details {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'preorder_details';

	// Page object name
	var $PageObjName = 'preorder_details_list';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "preorder_detailsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "preorder_detailsdelete.php";
		$this->MultiUpdateUrl = "preorder_detailsupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'preorder_details', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $RestoreSearch;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";
		if ($this->IsPageRequest()) { // Validate request

			// Handle reset command
			$this->ResetCmd();

			// Hide all options
			if ($this->Export <> "" ||
				$this->CurrentAction == "gridadd" ||
				$this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ExportOptions->HideAllOptions();
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			if ($this->Export <> "email")
				$this->Page_Terminate(); // Terminate response
			exit();
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id, $bCtrl); // id
			$this->UpdateSort($this->preorder_id, $bCtrl); // preorder_id
			$this->UpdateSort($this->product_id, $bCtrl); // product_id
			$this->UpdateSort($this->size, $bCtrl); // size
			$this->UpdateSort($this->cut, $bCtrl); // cut
			$this->UpdateSort($this->quantity, $bCtrl); // quantity
			$this->UpdateSort($this->price, $bCtrl); // price
			$this->UpdateSort($this->date_added, $bCtrl); // date_added
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// cmd=reset (Reset search parameters)
	// cmd=resetall (Reset search and master/detail parameters)
	// cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Get reset command
		if (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];

			// Reset sorting order
			if (strtolower($sCmd) == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id->setSort("");
				$this->preorder_id->setSort("");
				$this->product_id->setSort("");
				$this->size->setSort("");
				$this->cut->setSort("");
				$this->quantity->setSort("");
				$this->price->setSort("");
				$this->date_added->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->ViewUrl . "\">" . $Language->Phrase("ViewLink") . "</a>";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->EditUrl . "\">" . $Language->Phrase("EditLink") . "</a>";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->CopyUrl . "\">" . $Language->Phrase("CopyLink") . "</a>";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		$item->Body = "<a name=\"emf_preorder_details\" id=\"emf_preorder_details\" href=\"javascript:void(0);\" onclick=\"ew_EmailDialogShow({lnk:'emf_preorder_details',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fpreorder_detailslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Hide options for export/action
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs < 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs < 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "h");
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
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "");
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

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'preorder_details';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($preorder_details_list)) $preorder_details_list = new cpreorder_details_list();

// Page init
$preorder_details_list->Page_Init();

// Page main
$preorder_details_list->Page_Main();
?>
<?php include_once "header.php" ?>
<?php if ($preorder_details->Export == "") { ?>
<script type="text/javascript">

// Page object
var preorder_details_list = new ew_Page("preorder_details_list");
preorder_details_list.PageID = "list"; // Page ID
var EW_PAGE_ID = preorder_details_list.PageID; // For backward compatibility

// Form object
var fpreorder_detailslist = new ew_Form("fpreorder_detailslist");

// Form_CustomValidate event
fpreorder_detailslist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpreorder_detailslist.ValidateRequired = true;
<?php } else { ?>
fpreorder_detailslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$preorder_details_list->TotalRecs = $preorder_details->SelectRecordCount();
	} else {
		if ($preorder_details_list->Recordset = $preorder_details_list->LoadRecordset())
			$preorder_details_list->TotalRecs = $preorder_details_list->Recordset->RecordCount();
	}
	$preorder_details_list->StartRec = 1;
	if ($preorder_details_list->DisplayRecs <= 0 || ($preorder_details->Export <> "" && $preorder_details->ExportAll)) // Display all records
		$preorder_details_list->DisplayRecs = $preorder_details_list->TotalRecs;
	if (!($preorder_details->Export <> "" && $preorder_details->ExportAll))
		$preorder_details_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$preorder_details_list->Recordset = $preorder_details_list->LoadRecordset($preorder_details_list->StartRec-1, $preorder_details_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $preorder_details->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $preorder_details_list->ExportOptions->Render("body"); ?>
</p>
<?php $preorder_details_list->ShowPageHeader(); ?>
<?php
$preorder_details_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($preorder_details->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($preorder_details->CurrentAction <> "gridadd" && $preorder_details->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php if (!isset($preorder_details_list->Pager)) $preorder_details_list->Pager = new cNumericPager($preorder_details_list->StartRec, $preorder_details_list->DisplayRecs, $preorder_details_list->TotalRecs, $preorder_details_list->RecRange) ?>
<?php if ($preorder_details_list->Pager->RecordCount > 0) { ?>
	<?php if ($preorder_details_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $preorder_details_list->PageUrl() ?>start=<?php echo $preorder_details_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($preorder_details_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $preorder_details_list->PageUrl() ?>start=<?php echo $preorder_details_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($preorder_details_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $preorder_details_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($preorder_details_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $preorder_details_list->PageUrl() ?>start=<?php echo $preorder_details_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($preorder_details_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $preorder_details_list->PageUrl() ?>start=<?php echo $preorder_details_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($preorder_details_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $preorder_details_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $preorder_details_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $preorder_details_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($preorder_details_list->SearchWhere == "0=101") { ?>
	<?php echo $Language->Phrase("EnterSearchCriteria") ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoRecord") ?>
	<?php } ?>
<?php } ?>
</span>
		</td>
	</tr>
</table>
</form>
<?php } ?>
<span class="phpmaker">
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($preorder_details_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $preorder_details_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<form name="fpreorder_detailslist" id="fpreorder_detailslist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="preorder_details">
<div id="gmp_preorder_details" class="ewGridMiddlePanel">
<?php if ($preorder_details_list->TotalRecs > 0) { ?>
<table cellspacing="0" id="tbl_preorder_detailslist" class="ewTable ewTableSeparate">
<?php echo $preorder_details->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$preorder_details_list->RenderListOptions();

// Render list options (header, left)
$preorder_details_list->ListOptions->Render("header", "left");
?>
<?php if ($preorder_details->id->Visible) { // id ?>
	<?php if ($preorder_details->SortUrl($preorder_details->id) == "") { ?>
		<td><span id="elh_preorder_details_id" class="preorder_details_id"><?php echo $preorder_details->id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $preorder_details->SortUrl($preorder_details->id) ?>',2);"><span id="elh_preorder_details_id" class="preorder_details_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $preorder_details->id->FldCaption() ?></td><td style="width: 10px;"><?php if ($preorder_details->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($preorder_details->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($preorder_details->preorder_id->Visible) { // preorder_id ?>
	<?php if ($preorder_details->SortUrl($preorder_details->preorder_id) == "") { ?>
		<td><span id="elh_preorder_details_preorder_id" class="preorder_details_preorder_id"><?php echo $preorder_details->preorder_id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $preorder_details->SortUrl($preorder_details->preorder_id) ?>',2);"><span id="elh_preorder_details_preorder_id" class="preorder_details_preorder_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $preorder_details->preorder_id->FldCaption() ?></td><td style="width: 10px;"><?php if ($preorder_details->preorder_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($preorder_details->preorder_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($preorder_details->product_id->Visible) { // product_id ?>
	<?php if ($preorder_details->SortUrl($preorder_details->product_id) == "") { ?>
		<td><span id="elh_preorder_details_product_id" class="preorder_details_product_id"><?php echo $preorder_details->product_id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $preorder_details->SortUrl($preorder_details->product_id) ?>',2);"><span id="elh_preorder_details_product_id" class="preorder_details_product_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $preorder_details->product_id->FldCaption() ?></td><td style="width: 10px;"><?php if ($preorder_details->product_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($preorder_details->product_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($preorder_details->size->Visible) { // size ?>
	<?php if ($preorder_details->SortUrl($preorder_details->size) == "") { ?>
		<td><span id="elh_preorder_details_size" class="preorder_details_size"><?php echo $preorder_details->size->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $preorder_details->SortUrl($preorder_details->size) ?>',2);"><span id="elh_preorder_details_size" class="preorder_details_size">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $preorder_details->size->FldCaption() ?></td><td style="width: 10px;"><?php if ($preorder_details->size->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($preorder_details->size->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($preorder_details->cut->Visible) { // cut ?>
	<?php if ($preorder_details->SortUrl($preorder_details->cut) == "") { ?>
		<td><span id="elh_preorder_details_cut" class="preorder_details_cut"><?php echo $preorder_details->cut->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $preorder_details->SortUrl($preorder_details->cut) ?>',2);"><span id="elh_preorder_details_cut" class="preorder_details_cut">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $preorder_details->cut->FldCaption() ?></td><td style="width: 10px;"><?php if ($preorder_details->cut->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($preorder_details->cut->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($preorder_details->quantity->Visible) { // quantity ?>
	<?php if ($preorder_details->SortUrl($preorder_details->quantity) == "") { ?>
		<td><span id="elh_preorder_details_quantity" class="preorder_details_quantity"><?php echo $preorder_details->quantity->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $preorder_details->SortUrl($preorder_details->quantity) ?>',2);"><span id="elh_preorder_details_quantity" class="preorder_details_quantity">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $preorder_details->quantity->FldCaption() ?></td><td style="width: 10px;"><?php if ($preorder_details->quantity->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($preorder_details->quantity->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($preorder_details->price->Visible) { // price ?>
	<?php if ($preorder_details->SortUrl($preorder_details->price) == "") { ?>
		<td><span id="elh_preorder_details_price" class="preorder_details_price"><?php echo $preorder_details->price->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $preorder_details->SortUrl($preorder_details->price) ?>',2);"><span id="elh_preorder_details_price" class="preorder_details_price">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $preorder_details->price->FldCaption() ?></td><td style="width: 10px;"><?php if ($preorder_details->price->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($preorder_details->price->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($preorder_details->date_added->Visible) { // date_added ?>
	<?php if ($preorder_details->SortUrl($preorder_details->date_added) == "") { ?>
		<td><span id="elh_preorder_details_date_added" class="preorder_details_date_added"><?php echo $preorder_details->date_added->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $preorder_details->SortUrl($preorder_details->date_added) ?>',2);"><span id="elh_preorder_details_date_added" class="preorder_details_date_added">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $preorder_details->date_added->FldCaption() ?></td><td style="width: 10px;"><?php if ($preorder_details->date_added->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($preorder_details->date_added->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$preorder_details_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($preorder_details->ExportAll && $preorder_details->Export <> "") {
	$preorder_details_list->StopRec = $preorder_details_list->TotalRecs;
} else {

	// Set the last record to display
	if ($preorder_details_list->TotalRecs > $preorder_details_list->StartRec + $preorder_details_list->DisplayRecs - 1)
		$preorder_details_list->StopRec = $preorder_details_list->StartRec + $preorder_details_list->DisplayRecs - 1;
	else
		$preorder_details_list->StopRec = $preorder_details_list->TotalRecs;
}
$preorder_details_list->RecCnt = $preorder_details_list->StartRec - 1;
if ($preorder_details_list->Recordset && !$preorder_details_list->Recordset->EOF) {
	$preorder_details_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $preorder_details_list->StartRec > 1)
		$preorder_details_list->Recordset->Move($preorder_details_list->StartRec - 1);
} elseif (!$preorder_details->AllowAddDeleteRow && $preorder_details_list->StopRec == 0) {
	$preorder_details_list->StopRec = $preorder_details->GridAddRowCount;
}

// Initialize aggregate
$preorder_details->RowType = EW_ROWTYPE_AGGREGATEINIT;
$preorder_details->ResetAttrs();
$preorder_details_list->RenderRow();
while ($preorder_details_list->RecCnt < $preorder_details_list->StopRec) {
	$preorder_details_list->RecCnt++;
	if (intval($preorder_details_list->RecCnt) >= intval($preorder_details_list->StartRec)) {
		$preorder_details_list->RowCnt++;

		// Set up key count
		$preorder_details_list->KeyCount = $preorder_details_list->RowIndex;

		// Init row class and style
		$preorder_details->ResetAttrs();
		$preorder_details->CssClass = "";
		if ($preorder_details->CurrentAction == "gridadd") {
		} else {
			$preorder_details_list->LoadRowValues($preorder_details_list->Recordset); // Load row values
		}
		$preorder_details->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$preorder_details->RowAttrs = array_merge($preorder_details->RowAttrs, array('data-rowindex'=>$preorder_details_list->RowCnt, 'id'=>'r' . $preorder_details_list->RowCnt . '_preorder_details', 'data-rowtype'=>$preorder_details->RowType));

		// Render row
		$preorder_details_list->RenderRow();

		// Render list options
		$preorder_details_list->RenderListOptions();
?>
	<tr<?php echo $preorder_details->RowAttributes() ?>>
<?php

// Render list options (body, left)
$preorder_details_list->ListOptions->Render("body", "left", $preorder_details_list->RowCnt);
?>
	<?php if ($preorder_details->id->Visible) { // id ?>
		<td<?php echo $preorder_details->id->CellAttributes() ?>><span id="el<?php echo $preorder_details_list->RowCnt ?>_preorder_details_id" class="preorder_details_id">
<span<?php echo $preorder_details->id->ViewAttributes() ?>>
<?php echo $preorder_details->id->ListViewValue() ?></span>
<a name="<?php echo $preorder_details_list->PageObjName . "_row_" . $preorder_details_list->RowCnt ?>" id="<?php echo $preorder_details_list->PageObjName . "_row_" . $preorder_details_list->RowCnt ?>"></a></span></td>
	<?php } ?>
	<?php if ($preorder_details->preorder_id->Visible) { // preorder_id ?>
		<td<?php echo $preorder_details->preorder_id->CellAttributes() ?>><span id="el<?php echo $preorder_details_list->RowCnt ?>_preorder_details_preorder_id" class="preorder_details_preorder_id">
<span<?php echo $preorder_details->preorder_id->ViewAttributes() ?>>
<?php echo $preorder_details->preorder_id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($preorder_details->product_id->Visible) { // product_id ?>
		<td<?php echo $preorder_details->product_id->CellAttributes() ?>><span id="el<?php echo $preorder_details_list->RowCnt ?>_preorder_details_product_id" class="preorder_details_product_id">
<span<?php echo $preorder_details->product_id->ViewAttributes() ?>>
<?php echo $preorder_details->product_id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($preorder_details->size->Visible) { // size ?>
		<td<?php echo $preorder_details->size->CellAttributes() ?>><span id="el<?php echo $preorder_details_list->RowCnt ?>_preorder_details_size" class="preorder_details_size">
<span<?php echo $preorder_details->size->ViewAttributes() ?>>
<?php echo $preorder_details->size->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($preorder_details->cut->Visible) { // cut ?>
		<td<?php echo $preorder_details->cut->CellAttributes() ?>><span id="el<?php echo $preorder_details_list->RowCnt ?>_preorder_details_cut" class="preorder_details_cut">
<span<?php echo $preorder_details->cut->ViewAttributes() ?>>
<?php echo $preorder_details->cut->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($preorder_details->quantity->Visible) { // quantity ?>
		<td<?php echo $preorder_details->quantity->CellAttributes() ?>><span id="el<?php echo $preorder_details_list->RowCnt ?>_preorder_details_quantity" class="preorder_details_quantity">
<span<?php echo $preorder_details->quantity->ViewAttributes() ?>>
<?php echo $preorder_details->quantity->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($preorder_details->price->Visible) { // price ?>
		<td<?php echo $preorder_details->price->CellAttributes() ?>><span id="el<?php echo $preorder_details_list->RowCnt ?>_preorder_details_price" class="preorder_details_price">
<span<?php echo $preorder_details->price->ViewAttributes() ?>>
<?php echo $preorder_details->price->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($preorder_details->date_added->Visible) { // date_added ?>
		<td<?php echo $preorder_details->date_added->CellAttributes() ?>><span id="el<?php echo $preorder_details_list->RowCnt ?>_preorder_details_date_added" class="preorder_details_date_added">
<span<?php echo $preorder_details->date_added->ViewAttributes() ?>>
<?php echo $preorder_details->date_added->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$preorder_details_list->ListOptions->Render("body", "right", $preorder_details_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($preorder_details->CurrentAction <> "gridadd")
		$preorder_details_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($preorder_details->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($preorder_details_list->Recordset)
	$preorder_details_list->Recordset->Close();
?>
<?php if ($preorder_details_list->TotalRecs > 0) { ?>
<?php if ($preorder_details->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($preorder_details->CurrentAction <> "gridadd" && $preorder_details->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php if (!isset($preorder_details_list->Pager)) $preorder_details_list->Pager = new cNumericPager($preorder_details_list->StartRec, $preorder_details_list->DisplayRecs, $preorder_details_list->TotalRecs, $preorder_details_list->RecRange) ?>
<?php if ($preorder_details_list->Pager->RecordCount > 0) { ?>
	<?php if ($preorder_details_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $preorder_details_list->PageUrl() ?>start=<?php echo $preorder_details_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($preorder_details_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $preorder_details_list->PageUrl() ?>start=<?php echo $preorder_details_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($preorder_details_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $preorder_details_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($preorder_details_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $preorder_details_list->PageUrl() ?>start=<?php echo $preorder_details_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($preorder_details_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $preorder_details_list->PageUrl() ?>start=<?php echo $preorder_details_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($preorder_details_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $preorder_details_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $preorder_details_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $preorder_details_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($preorder_details_list->SearchWhere == "0=101") { ?>
	<?php echo $Language->Phrase("EnterSearchCriteria") ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoRecord") ?>
	<?php } ?>
<?php } ?>
</span>
		</td>
	</tr>
</table>
</form>
<?php } ?>
<span class="phpmaker">
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($preorder_details_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $preorder_details_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($preorder_details->Export == "") { ?>
<script type="text/javascript">
fpreorder_detailslist.Init();
</script>
<?php } ?>
<?php
$preorder_details_list->ShowPageFooter();
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
$preorder_details_list->Page_Terminate();
?>
