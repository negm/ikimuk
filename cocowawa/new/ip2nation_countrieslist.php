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

$ip2nation_countries_list = NULL; // Initialize page object first

class cip2nation_countries_list extends cip2nation_countries {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'ip2nation_countries';

	// Page object name
	var $PageObjName = 'ip2nation_countries_list';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "ip2nation_countriesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "ip2nation_countriesdelete.php";
		$this->MultiUpdateUrl = "ip2nation_countriesupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ip2nation_countries', TRUE);

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
			$this->country_code->setFormValue($arrKeyFlds[0]);
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->country_code, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->iso_code_2, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->iso_code_3, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->iso_country, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->country_name, $Keyword);
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
			$this->UpdateSort($this->country_code, $bCtrl); // country_code
			$this->UpdateSort($this->iso_code_2, $bCtrl); // iso_code_2
			$this->UpdateSort($this->iso_code_3, $bCtrl); // iso_code_3
			$this->UpdateSort($this->iso_country, $bCtrl); // iso_country
			$this->UpdateSort($this->country_name, $bCtrl); // country_name
			$this->UpdateSort($this->delivery_charge, $bCtrl); // delivery_charge
			$this->UpdateSort($this->phone_code, $bCtrl); // phone_code
			$this->UpdateSort($this->lat, $bCtrl); // lat
			$this->UpdateSort($this->lon, $bCtrl); // lon
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
				$this->country_code->setSort("");
				$this->iso_code_2->setSort("");
				$this->iso_code_3->setSort("");
				$this->iso_country->setSort("");
				$this->country_name->setSort("");
				$this->delivery_charge->setSort("");
				$this->phone_code->setSort("");
				$this->lat->setSort("");
				$this->lon->setSort("");
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("country_code")) <> "")
			$this->country_code->CurrentValue = $this->getKey("country_code"); // country_code
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
		$item->Body = "<a name=\"emf_ip2nation_countries\" id=\"emf_ip2nation_countries\" href=\"javascript:void(0);\" onclick=\"ew_EmailDialogShow({lnk:'emf_ip2nation_countries',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fip2nation_countrieslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$table = 'ip2nation_countries';
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
if (!isset($ip2nation_countries_list)) $ip2nation_countries_list = new cip2nation_countries_list();

// Page init
$ip2nation_countries_list->Page_Init();

// Page main
$ip2nation_countries_list->Page_Main();
?>
<?php include_once "header.php" ?>
<?php if ($ip2nation_countries->Export == "") { ?>
<script type="text/javascript">

// Page object
var ip2nation_countries_list = new ew_Page("ip2nation_countries_list");
ip2nation_countries_list.PageID = "list"; // Page ID
var EW_PAGE_ID = ip2nation_countries_list.PageID; // For backward compatibility

// Form object
var fip2nation_countrieslist = new ew_Form("fip2nation_countrieslist");

// Form_CustomValidate event
fip2nation_countrieslist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fip2nation_countrieslist.ValidateRequired = true;
<?php } else { ?>
fip2nation_countrieslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fip2nation_countrieslistsrch = new ew_Form("fip2nation_countrieslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$ip2nation_countries_list->TotalRecs = $ip2nation_countries->SelectRecordCount();
	} else {
		if ($ip2nation_countries_list->Recordset = $ip2nation_countries_list->LoadRecordset())
			$ip2nation_countries_list->TotalRecs = $ip2nation_countries_list->Recordset->RecordCount();
	}
	$ip2nation_countries_list->StartRec = 1;
	if ($ip2nation_countries_list->DisplayRecs <= 0 || ($ip2nation_countries->Export <> "" && $ip2nation_countries->ExportAll)) // Display all records
		$ip2nation_countries_list->DisplayRecs = $ip2nation_countries_list->TotalRecs;
	if (!($ip2nation_countries->Export <> "" && $ip2nation_countries->ExportAll))
		$ip2nation_countries_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$ip2nation_countries_list->Recordset = $ip2nation_countries_list->LoadRecordset($ip2nation_countries_list->StartRec-1, $ip2nation_countries_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $ip2nation_countries->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $ip2nation_countries_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($ip2nation_countries->Export == "" && $ip2nation_countries->CurrentAction == "") { ?>
<form name="fip2nation_countrieslistsrch" id="fip2nation_countrieslistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<a href="javascript:fip2nation_countrieslistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="fip2nation_countrieslistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" border="0"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="fip2nation_countrieslistsrch_SearchPanel">
<input type="hidden" name="t" value="ip2nation_countries">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($ip2nation_countries->getSessionBasicSearchKeyword()) ?>">
	<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $ip2nation_countries_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
</div>
<div id="xsr_2" class="ewRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($ip2nation_countries->getSessionBasicSearchType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($ip2nation_countries->getSessionBasicSearchType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($ip2nation_countries->getSessionBasicSearchType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $ip2nation_countries_list->ShowPageHeader(); ?>
<?php
$ip2nation_countries_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($ip2nation_countries->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($ip2nation_countries->CurrentAction <> "gridadd" && $ip2nation_countries->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php if (!isset($ip2nation_countries_list->Pager)) $ip2nation_countries_list->Pager = new cNumericPager($ip2nation_countries_list->StartRec, $ip2nation_countries_list->DisplayRecs, $ip2nation_countries_list->TotalRecs, $ip2nation_countries_list->RecRange) ?>
<?php if ($ip2nation_countries_list->Pager->RecordCount > 0) { ?>
	<?php if ($ip2nation_countries_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $ip2nation_countries_list->PageUrl() ?>start=<?php echo $ip2nation_countries_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($ip2nation_countries_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $ip2nation_countries_list->PageUrl() ?>start=<?php echo $ip2nation_countries_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($ip2nation_countries_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $ip2nation_countries_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($ip2nation_countries_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $ip2nation_countries_list->PageUrl() ?>start=<?php echo $ip2nation_countries_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($ip2nation_countries_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $ip2nation_countries_list->PageUrl() ?>start=<?php echo $ip2nation_countries_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($ip2nation_countries_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $ip2nation_countries_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $ip2nation_countries_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $ip2nation_countries_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($ip2nation_countries_list->SearchWhere == "0=101") { ?>
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
<?php if ($ip2nation_countries_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $ip2nation_countries_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<form name="fip2nation_countrieslist" id="fip2nation_countrieslist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="ip2nation_countries">
<div id="gmp_ip2nation_countries" class="ewGridMiddlePanel">
<?php if ($ip2nation_countries_list->TotalRecs > 0) { ?>
<table cellspacing="0" id="tbl_ip2nation_countrieslist" class="ewTable ewTableSeparate">
<?php echo $ip2nation_countries->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$ip2nation_countries_list->RenderListOptions();

// Render list options (header, left)
$ip2nation_countries_list->ListOptions->Render("header", "left");
?>
<?php if ($ip2nation_countries->country_code->Visible) { // country_code ?>
	<?php if ($ip2nation_countries->SortUrl($ip2nation_countries->country_code) == "") { ?>
		<td><span id="elh_ip2nation_countries_country_code" class="ip2nation_countries_country_code"><?php echo $ip2nation_countries->country_code->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $ip2nation_countries->SortUrl($ip2nation_countries->country_code) ?>',2);"><span id="elh_ip2nation_countries_country_code" class="ip2nation_countries_country_code">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $ip2nation_countries->country_code->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($ip2nation_countries->country_code->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($ip2nation_countries->country_code->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($ip2nation_countries->iso_code_2->Visible) { // iso_code_2 ?>
	<?php if ($ip2nation_countries->SortUrl($ip2nation_countries->iso_code_2) == "") { ?>
		<td><span id="elh_ip2nation_countries_iso_code_2" class="ip2nation_countries_iso_code_2"><?php echo $ip2nation_countries->iso_code_2->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $ip2nation_countries->SortUrl($ip2nation_countries->iso_code_2) ?>',2);"><span id="elh_ip2nation_countries_iso_code_2" class="ip2nation_countries_iso_code_2">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $ip2nation_countries->iso_code_2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($ip2nation_countries->iso_code_2->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($ip2nation_countries->iso_code_2->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($ip2nation_countries->iso_code_3->Visible) { // iso_code_3 ?>
	<?php if ($ip2nation_countries->SortUrl($ip2nation_countries->iso_code_3) == "") { ?>
		<td><span id="elh_ip2nation_countries_iso_code_3" class="ip2nation_countries_iso_code_3"><?php echo $ip2nation_countries->iso_code_3->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $ip2nation_countries->SortUrl($ip2nation_countries->iso_code_3) ?>',2);"><span id="elh_ip2nation_countries_iso_code_3" class="ip2nation_countries_iso_code_3">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $ip2nation_countries->iso_code_3->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($ip2nation_countries->iso_code_3->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($ip2nation_countries->iso_code_3->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($ip2nation_countries->iso_country->Visible) { // iso_country ?>
	<?php if ($ip2nation_countries->SortUrl($ip2nation_countries->iso_country) == "") { ?>
		<td><span id="elh_ip2nation_countries_iso_country" class="ip2nation_countries_iso_country"><?php echo $ip2nation_countries->iso_country->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $ip2nation_countries->SortUrl($ip2nation_countries->iso_country) ?>',2);"><span id="elh_ip2nation_countries_iso_country" class="ip2nation_countries_iso_country">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $ip2nation_countries->iso_country->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($ip2nation_countries->iso_country->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($ip2nation_countries->iso_country->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($ip2nation_countries->country_name->Visible) { // country_name ?>
	<?php if ($ip2nation_countries->SortUrl($ip2nation_countries->country_name) == "") { ?>
		<td><span id="elh_ip2nation_countries_country_name" class="ip2nation_countries_country_name"><?php echo $ip2nation_countries->country_name->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $ip2nation_countries->SortUrl($ip2nation_countries->country_name) ?>',2);"><span id="elh_ip2nation_countries_country_name" class="ip2nation_countries_country_name">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $ip2nation_countries->country_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($ip2nation_countries->country_name->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($ip2nation_countries->country_name->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($ip2nation_countries->delivery_charge->Visible) { // delivery_charge ?>
	<?php if ($ip2nation_countries->SortUrl($ip2nation_countries->delivery_charge) == "") { ?>
		<td><span id="elh_ip2nation_countries_delivery_charge" class="ip2nation_countries_delivery_charge"><?php echo $ip2nation_countries->delivery_charge->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $ip2nation_countries->SortUrl($ip2nation_countries->delivery_charge) ?>',2);"><span id="elh_ip2nation_countries_delivery_charge" class="ip2nation_countries_delivery_charge">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $ip2nation_countries->delivery_charge->FldCaption() ?></td><td style="width: 10px;"><?php if ($ip2nation_countries->delivery_charge->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($ip2nation_countries->delivery_charge->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($ip2nation_countries->phone_code->Visible) { // phone_code ?>
	<?php if ($ip2nation_countries->SortUrl($ip2nation_countries->phone_code) == "") { ?>
		<td><span id="elh_ip2nation_countries_phone_code" class="ip2nation_countries_phone_code"><?php echo $ip2nation_countries->phone_code->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $ip2nation_countries->SortUrl($ip2nation_countries->phone_code) ?>',2);"><span id="elh_ip2nation_countries_phone_code" class="ip2nation_countries_phone_code">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $ip2nation_countries->phone_code->FldCaption() ?></td><td style="width: 10px;"><?php if ($ip2nation_countries->phone_code->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($ip2nation_countries->phone_code->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($ip2nation_countries->lat->Visible) { // lat ?>
	<?php if ($ip2nation_countries->SortUrl($ip2nation_countries->lat) == "") { ?>
		<td><span id="elh_ip2nation_countries_lat" class="ip2nation_countries_lat"><?php echo $ip2nation_countries->lat->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $ip2nation_countries->SortUrl($ip2nation_countries->lat) ?>',2);"><span id="elh_ip2nation_countries_lat" class="ip2nation_countries_lat">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $ip2nation_countries->lat->FldCaption() ?></td><td style="width: 10px;"><?php if ($ip2nation_countries->lat->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($ip2nation_countries->lat->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($ip2nation_countries->lon->Visible) { // lon ?>
	<?php if ($ip2nation_countries->SortUrl($ip2nation_countries->lon) == "") { ?>
		<td><span id="elh_ip2nation_countries_lon" class="ip2nation_countries_lon"><?php echo $ip2nation_countries->lon->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $ip2nation_countries->SortUrl($ip2nation_countries->lon) ?>',2);"><span id="elh_ip2nation_countries_lon" class="ip2nation_countries_lon">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $ip2nation_countries->lon->FldCaption() ?></td><td style="width: 10px;"><?php if ($ip2nation_countries->lon->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($ip2nation_countries->lon->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$ip2nation_countries_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($ip2nation_countries->ExportAll && $ip2nation_countries->Export <> "") {
	$ip2nation_countries_list->StopRec = $ip2nation_countries_list->TotalRecs;
} else {

	// Set the last record to display
	if ($ip2nation_countries_list->TotalRecs > $ip2nation_countries_list->StartRec + $ip2nation_countries_list->DisplayRecs - 1)
		$ip2nation_countries_list->StopRec = $ip2nation_countries_list->StartRec + $ip2nation_countries_list->DisplayRecs - 1;
	else
		$ip2nation_countries_list->StopRec = $ip2nation_countries_list->TotalRecs;
}
$ip2nation_countries_list->RecCnt = $ip2nation_countries_list->StartRec - 1;
if ($ip2nation_countries_list->Recordset && !$ip2nation_countries_list->Recordset->EOF) {
	$ip2nation_countries_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $ip2nation_countries_list->StartRec > 1)
		$ip2nation_countries_list->Recordset->Move($ip2nation_countries_list->StartRec - 1);
} elseif (!$ip2nation_countries->AllowAddDeleteRow && $ip2nation_countries_list->StopRec == 0) {
	$ip2nation_countries_list->StopRec = $ip2nation_countries->GridAddRowCount;
}

// Initialize aggregate
$ip2nation_countries->RowType = EW_ROWTYPE_AGGREGATEINIT;
$ip2nation_countries->ResetAttrs();
$ip2nation_countries_list->RenderRow();
while ($ip2nation_countries_list->RecCnt < $ip2nation_countries_list->StopRec) {
	$ip2nation_countries_list->RecCnt++;
	if (intval($ip2nation_countries_list->RecCnt) >= intval($ip2nation_countries_list->StartRec)) {
		$ip2nation_countries_list->RowCnt++;

		// Set up key count
		$ip2nation_countries_list->KeyCount = $ip2nation_countries_list->RowIndex;

		// Init row class and style
		$ip2nation_countries->ResetAttrs();
		$ip2nation_countries->CssClass = "";
		if ($ip2nation_countries->CurrentAction == "gridadd") {
		} else {
			$ip2nation_countries_list->LoadRowValues($ip2nation_countries_list->Recordset); // Load row values
		}
		$ip2nation_countries->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$ip2nation_countries->RowAttrs = array_merge($ip2nation_countries->RowAttrs, array('data-rowindex'=>$ip2nation_countries_list->RowCnt, 'id'=>'r' . $ip2nation_countries_list->RowCnt . '_ip2nation_countries', 'data-rowtype'=>$ip2nation_countries->RowType));

		// Render row
		$ip2nation_countries_list->RenderRow();

		// Render list options
		$ip2nation_countries_list->RenderListOptions();
?>
	<tr<?php echo $ip2nation_countries->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ip2nation_countries_list->ListOptions->Render("body", "left", $ip2nation_countries_list->RowCnt);
?>
	<?php if ($ip2nation_countries->country_code->Visible) { // country_code ?>
		<td<?php echo $ip2nation_countries->country_code->CellAttributes() ?>><span id="el<?php echo $ip2nation_countries_list->RowCnt ?>_ip2nation_countries_country_code" class="ip2nation_countries_country_code">
<span<?php echo $ip2nation_countries->country_code->ViewAttributes() ?>>
<?php echo $ip2nation_countries->country_code->ListViewValue() ?></span>
<a name="<?php echo $ip2nation_countries_list->PageObjName . "_row_" . $ip2nation_countries_list->RowCnt ?>" id="<?php echo $ip2nation_countries_list->PageObjName . "_row_" . $ip2nation_countries_list->RowCnt ?>"></a></span></td>
	<?php } ?>
	<?php if ($ip2nation_countries->iso_code_2->Visible) { // iso_code_2 ?>
		<td<?php echo $ip2nation_countries->iso_code_2->CellAttributes() ?>><span id="el<?php echo $ip2nation_countries_list->RowCnt ?>_ip2nation_countries_iso_code_2" class="ip2nation_countries_iso_code_2">
<span<?php echo $ip2nation_countries->iso_code_2->ViewAttributes() ?>>
<?php echo $ip2nation_countries->iso_code_2->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($ip2nation_countries->iso_code_3->Visible) { // iso_code_3 ?>
		<td<?php echo $ip2nation_countries->iso_code_3->CellAttributes() ?>><span id="el<?php echo $ip2nation_countries_list->RowCnt ?>_ip2nation_countries_iso_code_3" class="ip2nation_countries_iso_code_3">
<span<?php echo $ip2nation_countries->iso_code_3->ViewAttributes() ?>>
<?php echo $ip2nation_countries->iso_code_3->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($ip2nation_countries->iso_country->Visible) { // iso_country ?>
		<td<?php echo $ip2nation_countries->iso_country->CellAttributes() ?>><span id="el<?php echo $ip2nation_countries_list->RowCnt ?>_ip2nation_countries_iso_country" class="ip2nation_countries_iso_country">
<span<?php echo $ip2nation_countries->iso_country->ViewAttributes() ?>>
<?php echo $ip2nation_countries->iso_country->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($ip2nation_countries->country_name->Visible) { // country_name ?>
		<td<?php echo $ip2nation_countries->country_name->CellAttributes() ?>><span id="el<?php echo $ip2nation_countries_list->RowCnt ?>_ip2nation_countries_country_name" class="ip2nation_countries_country_name">
<span<?php echo $ip2nation_countries->country_name->ViewAttributes() ?>>
<?php echo $ip2nation_countries->country_name->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($ip2nation_countries->delivery_charge->Visible) { // delivery_charge ?>
		<td<?php echo $ip2nation_countries->delivery_charge->CellAttributes() ?>><span id="el<?php echo $ip2nation_countries_list->RowCnt ?>_ip2nation_countries_delivery_charge" class="ip2nation_countries_delivery_charge">
<span<?php echo $ip2nation_countries->delivery_charge->ViewAttributes() ?>>
<?php echo $ip2nation_countries->delivery_charge->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($ip2nation_countries->phone_code->Visible) { // phone_code ?>
		<td<?php echo $ip2nation_countries->phone_code->CellAttributes() ?>><span id="el<?php echo $ip2nation_countries_list->RowCnt ?>_ip2nation_countries_phone_code" class="ip2nation_countries_phone_code">
<span<?php echo $ip2nation_countries->phone_code->ViewAttributes() ?>>
<?php echo $ip2nation_countries->phone_code->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($ip2nation_countries->lat->Visible) { // lat ?>
		<td<?php echo $ip2nation_countries->lat->CellAttributes() ?>><span id="el<?php echo $ip2nation_countries_list->RowCnt ?>_ip2nation_countries_lat" class="ip2nation_countries_lat">
<span<?php echo $ip2nation_countries->lat->ViewAttributes() ?>>
<?php echo $ip2nation_countries->lat->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($ip2nation_countries->lon->Visible) { // lon ?>
		<td<?php echo $ip2nation_countries->lon->CellAttributes() ?>><span id="el<?php echo $ip2nation_countries_list->RowCnt ?>_ip2nation_countries_lon" class="ip2nation_countries_lon">
<span<?php echo $ip2nation_countries->lon->ViewAttributes() ?>>
<?php echo $ip2nation_countries->lon->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$ip2nation_countries_list->ListOptions->Render("body", "right", $ip2nation_countries_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($ip2nation_countries->CurrentAction <> "gridadd")
		$ip2nation_countries_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($ip2nation_countries->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($ip2nation_countries_list->Recordset)
	$ip2nation_countries_list->Recordset->Close();
?>
<?php if ($ip2nation_countries_list->TotalRecs > 0) { ?>
<?php if ($ip2nation_countries->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($ip2nation_countries->CurrentAction <> "gridadd" && $ip2nation_countries->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php if (!isset($ip2nation_countries_list->Pager)) $ip2nation_countries_list->Pager = new cNumericPager($ip2nation_countries_list->StartRec, $ip2nation_countries_list->DisplayRecs, $ip2nation_countries_list->TotalRecs, $ip2nation_countries_list->RecRange) ?>
<?php if ($ip2nation_countries_list->Pager->RecordCount > 0) { ?>
	<?php if ($ip2nation_countries_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $ip2nation_countries_list->PageUrl() ?>start=<?php echo $ip2nation_countries_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($ip2nation_countries_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $ip2nation_countries_list->PageUrl() ?>start=<?php echo $ip2nation_countries_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($ip2nation_countries_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $ip2nation_countries_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($ip2nation_countries_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $ip2nation_countries_list->PageUrl() ?>start=<?php echo $ip2nation_countries_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($ip2nation_countries_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $ip2nation_countries_list->PageUrl() ?>start=<?php echo $ip2nation_countries_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($ip2nation_countries_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $ip2nation_countries_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $ip2nation_countries_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $ip2nation_countries_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($ip2nation_countries_list->SearchWhere == "0=101") { ?>
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
<?php if ($ip2nation_countries_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $ip2nation_countries_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($ip2nation_countries->Export == "") { ?>
<script type="text/javascript">
fip2nation_countrieslistsrch.Init();
fip2nation_countrieslist.Init();
</script>
<?php } ?>
<?php
$ip2nation_countries_list->ShowPageFooter();
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
$ip2nation_countries_list->Page_Terminate();
?>
