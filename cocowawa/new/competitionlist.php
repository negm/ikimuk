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

$competition_list = NULL; // Initialize page object first

class ccompetition_list extends ccompetition {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'competition';

	// Page object name
	var $PageObjName = 'competition_list';

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

		// Table object (competition)
		if (!isset($GLOBALS["competition"])) {
			$GLOBALS["competition"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["competition"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "competitionadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "competitiondelete.php";
		$this->MultiUpdateUrl = "competitionupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'competition', TRUE);

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

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session
			$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if ($sSrchBasic == "" && $sSrchAdvanced == "") {

			// Load basic search from default
			$this->BasicSearchKeyword = $this->BasicSearchKeywordDefault;
			$this->BasicSearchType = $this->BasicSearchTypeDefault;
			$this->setSessionBasicSearchType($this->BasicSearchTypeDefault);
			if ($this->BasicSearchKeyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->SearchWhere <> "") {
			if ($sSrchBasic == "")
				$this->ResetBasicSearchParms();
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			if (!$this->RestoreSearch) {
				$this->StartRec = 1; // Reset start record counter
				$this->setStartRecordNumber($this->StartRec);
			}

		//} else {
		} elseif ($this->RestoreSearch) {
			$this->SearchWhere = $this->getSearchWhere();
		}

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

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->title, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->desc, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->competition_header, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->submission_header, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = $this->BasicSearchKeyword;
		$sSearchType = $this->BasicSearchType;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
		}
		if ($sSearchKeyword <> "") {
			$this->setSessionBasicSearchKeyword($sSearchKeyword);
			$this->setSessionBasicSearchType($sSearchType);
		}
		return $sSearchStr;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->setSessionBasicSearchKeyword("");
		$this->setSessionBasicSearchType($this->BasicSearchTypeDefault);
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$bRestore = TRUE;
		if ($this->BasicSearchKeyword <> "") $bRestore = FALSE;
		$this->RestoreSearch = $bRestore;
		if ($bRestore) {

			// Restore basic search values
			$this->BasicSearchKeyword = $this->getSessionBasicSearchKeyword();
			if ($this->getSessionBasicSearchType() == "") $this->setSessionBasicSearchType("=");
			$this->BasicSearchType = $this->getSessionBasicSearchType();
		}
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
			$this->UpdateSort($this->title, $bCtrl); // title
			$this->UpdateSort($this->desc, $bCtrl); // desc
			$this->UpdateSort($this->competition_header, $bCtrl); // competition_header
			$this->UpdateSort($this->submission_header, $bCtrl); // submission_header
			$this->UpdateSort($this->competition_order, $bCtrl); // competition_order
			$this->UpdateSort($this->start_date, $bCtrl); // start_date
			$this->UpdateSort($this->end_date, $bCtrl); // end_date
			$this->UpdateSort($this->submission_deadline, $bCtrl); // submission_deadline
			$this->UpdateSort($this->date_created, $bCtrl); // date_created
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

			// Reset search criteria
			if (strtolower($sCmd) == "reset" || strtolower($sCmd) == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if (strtolower($sCmd) == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id->setSort("");
				$this->title->setSort("");
				$this->desc->setSort("");
				$this->competition_header->setSort("");
				$this->submission_header->setSort("");
				$this->competition_order->setSort("");
				$this->start_date->setSort("");
				$this->end_date->setSort("");
				$this->submission_deadline->setSort("");
				$this->date_created->setSort("");
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearchKeyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		$this->BasicSearchType = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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
		$item->Body = "<a name=\"emf_competition\" id=\"emf_competition\" href=\"javascript:void(0);\" onclick=\"ew_EmailDialogShow({lnk:'emf_competition',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fcompetitionlist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$table = 'competition';
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
if (!isset($competition_list)) $competition_list = new ccompetition_list();

// Page init
$competition_list->Page_Init();

// Page main
$competition_list->Page_Main();
?>
<?php include_once "header.php" ?>
<?php if ($competition->Export == "") { ?>
<script type="text/javascript">

// Page object
var competition_list = new ew_Page("competition_list");
competition_list.PageID = "list"; // Page ID
var EW_PAGE_ID = competition_list.PageID; // For backward compatibility

// Form object
var fcompetitionlist = new ew_Form("fcompetitionlist");

// Form_CustomValidate event
fcompetitionlist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcompetitionlist.ValidateRequired = true;
<?php } else { ?>
fcompetitionlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fcompetitionlistsrch = new ew_Form("fcompetitionlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$competition_list->TotalRecs = $competition->SelectRecordCount();
	} else {
		if ($competition_list->Recordset = $competition_list->LoadRecordset())
			$competition_list->TotalRecs = $competition_list->Recordset->RecordCount();
	}
	$competition_list->StartRec = 1;
	if ($competition_list->DisplayRecs <= 0 || ($competition->Export <> "" && $competition->ExportAll)) // Display all records
		$competition_list->DisplayRecs = $competition_list->TotalRecs;
	if (!($competition->Export <> "" && $competition->ExportAll))
		$competition_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$competition_list->Recordset = $competition_list->LoadRecordset($competition_list->StartRec-1, $competition_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $competition->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $competition_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($competition->Export == "" && $competition->CurrentAction == "") { ?>
<form name="fcompetitionlistsrch" id="fcompetitionlistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<a href="javascript:fcompetitionlistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="fcompetitionlistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" border="0"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="fcompetitionlistsrch_SearchPanel">
<input type="hidden" name="t" value="competition">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($competition->getSessionBasicSearchKeyword()) ?>">
	<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $competition_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
</div>
<div id="xsr_2" class="ewRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($competition->getSessionBasicSearchType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($competition->getSessionBasicSearchType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($competition->getSessionBasicSearchType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $competition_list->ShowPageHeader(); ?>
<?php
$competition_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($competition->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($competition->CurrentAction <> "gridadd" && $competition->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php if (!isset($competition_list->Pager)) $competition_list->Pager = new cNumericPager($competition_list->StartRec, $competition_list->DisplayRecs, $competition_list->TotalRecs, $competition_list->RecRange) ?>
<?php if ($competition_list->Pager->RecordCount > 0) { ?>
	<?php if ($competition_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $competition_list->PageUrl() ?>start=<?php echo $competition_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($competition_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $competition_list->PageUrl() ?>start=<?php echo $competition_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($competition_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $competition_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($competition_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $competition_list->PageUrl() ?>start=<?php echo $competition_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($competition_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $competition_list->PageUrl() ?>start=<?php echo $competition_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($competition_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $competition_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $competition_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $competition_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($competition_list->SearchWhere == "0=101") { ?>
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
<?php if ($competition_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $competition_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<form name="fcompetitionlist" id="fcompetitionlist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="competition">
<div id="gmp_competition" class="ewGridMiddlePanel">
<?php if ($competition_list->TotalRecs > 0) { ?>
<table cellspacing="0" id="tbl_competitionlist" class="ewTable ewTableSeparate">
<?php echo $competition->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$competition_list->RenderListOptions();

// Render list options (header, left)
$competition_list->ListOptions->Render("header", "left");
?>
<?php if ($competition->id->Visible) { // id ?>
	<?php if ($competition->SortUrl($competition->id) == "") { ?>
		<td><span id="elh_competition_id" class="competition_id"><?php echo $competition->id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $competition->SortUrl($competition->id) ?>',2);"><span id="elh_competition_id" class="competition_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $competition->id->FldCaption() ?></td><td style="width: 10px;"><?php if ($competition->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($competition->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($competition->title->Visible) { // title ?>
	<?php if ($competition->SortUrl($competition->title) == "") { ?>
		<td><span id="elh_competition_title" class="competition_title"><?php echo $competition->title->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $competition->SortUrl($competition->title) ?>',2);"><span id="elh_competition_title" class="competition_title">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $competition->title->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($competition->title->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($competition->title->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($competition->desc->Visible) { // desc ?>
	<?php if ($competition->SortUrl($competition->desc) == "") { ?>
		<td><span id="elh_competition_desc" class="competition_desc"><?php echo $competition->desc->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $competition->SortUrl($competition->desc) ?>',2);"><span id="elh_competition_desc" class="competition_desc">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $competition->desc->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($competition->desc->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($competition->desc->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($competition->competition_header->Visible) { // competition_header ?>
	<?php if ($competition->SortUrl($competition->competition_header) == "") { ?>
		<td><span id="elh_competition_competition_header" class="competition_competition_header"><?php echo $competition->competition_header->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $competition->SortUrl($competition->competition_header) ?>',2);"><span id="elh_competition_competition_header" class="competition_competition_header">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $competition->competition_header->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($competition->competition_header->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($competition->competition_header->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($competition->submission_header->Visible) { // submission_header ?>
	<?php if ($competition->SortUrl($competition->submission_header) == "") { ?>
		<td><span id="elh_competition_submission_header" class="competition_submission_header"><?php echo $competition->submission_header->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $competition->SortUrl($competition->submission_header) ?>',2);"><span id="elh_competition_submission_header" class="competition_submission_header">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $competition->submission_header->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($competition->submission_header->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($competition->submission_header->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($competition->competition_order->Visible) { // competition_order ?>
	<?php if ($competition->SortUrl($competition->competition_order) == "") { ?>
		<td><span id="elh_competition_competition_order" class="competition_competition_order"><?php echo $competition->competition_order->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $competition->SortUrl($competition->competition_order) ?>',2);"><span id="elh_competition_competition_order" class="competition_competition_order">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $competition->competition_order->FldCaption() ?></td><td style="width: 10px;"><?php if ($competition->competition_order->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($competition->competition_order->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($competition->start_date->Visible) { // start_date ?>
	<?php if ($competition->SortUrl($competition->start_date) == "") { ?>
		<td><span id="elh_competition_start_date" class="competition_start_date"><?php echo $competition->start_date->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $competition->SortUrl($competition->start_date) ?>',2);"><span id="elh_competition_start_date" class="competition_start_date">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $competition->start_date->FldCaption() ?></td><td style="width: 10px;"><?php if ($competition->start_date->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($competition->start_date->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($competition->end_date->Visible) { // end_date ?>
	<?php if ($competition->SortUrl($competition->end_date) == "") { ?>
		<td><span id="elh_competition_end_date" class="competition_end_date"><?php echo $competition->end_date->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $competition->SortUrl($competition->end_date) ?>',2);"><span id="elh_competition_end_date" class="competition_end_date">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $competition->end_date->FldCaption() ?></td><td style="width: 10px;"><?php if ($competition->end_date->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($competition->end_date->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($competition->submission_deadline->Visible) { // submission_deadline ?>
	<?php if ($competition->SortUrl($competition->submission_deadline) == "") { ?>
		<td><span id="elh_competition_submission_deadline" class="competition_submission_deadline"><?php echo $competition->submission_deadline->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $competition->SortUrl($competition->submission_deadline) ?>',2);"><span id="elh_competition_submission_deadline" class="competition_submission_deadline">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $competition->submission_deadline->FldCaption() ?></td><td style="width: 10px;"><?php if ($competition->submission_deadline->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($competition->submission_deadline->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($competition->date_created->Visible) { // date_created ?>
	<?php if ($competition->SortUrl($competition->date_created) == "") { ?>
		<td><span id="elh_competition_date_created" class="competition_date_created"><?php echo $competition->date_created->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $competition->SortUrl($competition->date_created) ?>',2);"><span id="elh_competition_date_created" class="competition_date_created">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $competition->date_created->FldCaption() ?></td><td style="width: 10px;"><?php if ($competition->date_created->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($competition->date_created->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$competition_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($competition->ExportAll && $competition->Export <> "") {
	$competition_list->StopRec = $competition_list->TotalRecs;
} else {

	// Set the last record to display
	if ($competition_list->TotalRecs > $competition_list->StartRec + $competition_list->DisplayRecs - 1)
		$competition_list->StopRec = $competition_list->StartRec + $competition_list->DisplayRecs - 1;
	else
		$competition_list->StopRec = $competition_list->TotalRecs;
}
$competition_list->RecCnt = $competition_list->StartRec - 1;
if ($competition_list->Recordset && !$competition_list->Recordset->EOF) {
	$competition_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $competition_list->StartRec > 1)
		$competition_list->Recordset->Move($competition_list->StartRec - 1);
} elseif (!$competition->AllowAddDeleteRow && $competition_list->StopRec == 0) {
	$competition_list->StopRec = $competition->GridAddRowCount;
}

// Initialize aggregate
$competition->RowType = EW_ROWTYPE_AGGREGATEINIT;
$competition->ResetAttrs();
$competition_list->RenderRow();
while ($competition_list->RecCnt < $competition_list->StopRec) {
	$competition_list->RecCnt++;
	if (intval($competition_list->RecCnt) >= intval($competition_list->StartRec)) {
		$competition_list->RowCnt++;

		// Set up key count
		$competition_list->KeyCount = $competition_list->RowIndex;

		// Init row class and style
		$competition->ResetAttrs();
		$competition->CssClass = "";
		if ($competition->CurrentAction == "gridadd") {
		} else {
			$competition_list->LoadRowValues($competition_list->Recordset); // Load row values
		}
		$competition->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$competition->RowAttrs = array_merge($competition->RowAttrs, array('data-rowindex'=>$competition_list->RowCnt, 'id'=>'r' . $competition_list->RowCnt . '_competition', 'data-rowtype'=>$competition->RowType));

		// Render row
		$competition_list->RenderRow();

		// Render list options
		$competition_list->RenderListOptions();
?>
	<tr<?php echo $competition->RowAttributes() ?>>
<?php

// Render list options (body, left)
$competition_list->ListOptions->Render("body", "left", $competition_list->RowCnt);
?>
	<?php if ($competition->id->Visible) { // id ?>
		<td<?php echo $competition->id->CellAttributes() ?>><span id="el<?php echo $competition_list->RowCnt ?>_competition_id" class="competition_id">
<span<?php echo $competition->id->ViewAttributes() ?>>
<?php echo $competition->id->ListViewValue() ?></span>
<a name="<?php echo $competition_list->PageObjName . "_row_" . $competition_list->RowCnt ?>" id="<?php echo $competition_list->PageObjName . "_row_" . $competition_list->RowCnt ?>"></a></span></td>
	<?php } ?>
	<?php if ($competition->title->Visible) { // title ?>
		<td<?php echo $competition->title->CellAttributes() ?>><span id="el<?php echo $competition_list->RowCnt ?>_competition_title" class="competition_title">
<span<?php echo $competition->title->ViewAttributes() ?>>
<?php echo $competition->title->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($competition->desc->Visible) { // desc ?>
		<td<?php echo $competition->desc->CellAttributes() ?>><span id="el<?php echo $competition_list->RowCnt ?>_competition_desc" class="competition_desc">
<span<?php echo $competition->desc->ViewAttributes() ?>>
<?php echo $competition->desc->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($competition->competition_header->Visible) { // competition_header ?>
		<td<?php echo $competition->competition_header->CellAttributes() ?>><span id="el<?php echo $competition_list->RowCnt ?>_competition_competition_header" class="competition_competition_header">
<span<?php echo $competition->competition_header->ViewAttributes() ?>>
<?php echo $competition->competition_header->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($competition->submission_header->Visible) { // submission_header ?>
		<td<?php echo $competition->submission_header->CellAttributes() ?>><span id="el<?php echo $competition_list->RowCnt ?>_competition_submission_header" class="competition_submission_header">
<span<?php echo $competition->submission_header->ViewAttributes() ?>>
<?php echo $competition->submission_header->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($competition->competition_order->Visible) { // competition_order ?>
		<td<?php echo $competition->competition_order->CellAttributes() ?>><span id="el<?php echo $competition_list->RowCnt ?>_competition_competition_order" class="competition_competition_order">
<span<?php echo $competition->competition_order->ViewAttributes() ?>>
<?php echo $competition->competition_order->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($competition->start_date->Visible) { // start_date ?>
		<td<?php echo $competition->start_date->CellAttributes() ?>><span id="el<?php echo $competition_list->RowCnt ?>_competition_start_date" class="competition_start_date">
<span<?php echo $competition->start_date->ViewAttributes() ?>>
<?php echo $competition->start_date->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($competition->end_date->Visible) { // end_date ?>
		<td<?php echo $competition->end_date->CellAttributes() ?>><span id="el<?php echo $competition_list->RowCnt ?>_competition_end_date" class="competition_end_date">
<span<?php echo $competition->end_date->ViewAttributes() ?>>
<?php echo $competition->end_date->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($competition->submission_deadline->Visible) { // submission_deadline ?>
		<td<?php echo $competition->submission_deadline->CellAttributes() ?>><span id="el<?php echo $competition_list->RowCnt ?>_competition_submission_deadline" class="competition_submission_deadline">
<span<?php echo $competition->submission_deadline->ViewAttributes() ?>>
<?php echo $competition->submission_deadline->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($competition->date_created->Visible) { // date_created ?>
		<td<?php echo $competition->date_created->CellAttributes() ?>><span id="el<?php echo $competition_list->RowCnt ?>_competition_date_created" class="competition_date_created">
<span<?php echo $competition->date_created->ViewAttributes() ?>>
<?php echo $competition->date_created->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$competition_list->ListOptions->Render("body", "right", $competition_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($competition->CurrentAction <> "gridadd")
		$competition_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($competition->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($competition_list->Recordset)
	$competition_list->Recordset->Close();
?>
<?php if ($competition_list->TotalRecs > 0) { ?>
<?php if ($competition->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($competition->CurrentAction <> "gridadd" && $competition->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php if (!isset($competition_list->Pager)) $competition_list->Pager = new cNumericPager($competition_list->StartRec, $competition_list->DisplayRecs, $competition_list->TotalRecs, $competition_list->RecRange) ?>
<?php if ($competition_list->Pager->RecordCount > 0) { ?>
	<?php if ($competition_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $competition_list->PageUrl() ?>start=<?php echo $competition_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($competition_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $competition_list->PageUrl() ?>start=<?php echo $competition_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($competition_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $competition_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($competition_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $competition_list->PageUrl() ?>start=<?php echo $competition_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($competition_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $competition_list->PageUrl() ?>start=<?php echo $competition_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($competition_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $competition_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $competition_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $competition_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($competition_list->SearchWhere == "0=101") { ?>
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
<?php if ($competition_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $competition_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($competition->Export == "") { ?>
<script type="text/javascript">
fcompetitionlistsrch.Init();
fcompetitionlist.Init();
</script>
<?php } ?>
<?php
$competition_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($competition->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$competition_list->Page_Terminate();
?>
