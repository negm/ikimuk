<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "artistinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$artist_list = NULL; // Initialize page object first

class cartist_list extends cartist {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'artist';

	// Page object name
	var $PageObjName = 'artist_list';

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

		// Table object (artist)
		if (!isset($GLOBALS["artist"])) {
			$GLOBALS["artist"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["artist"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "artistadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "artistdelete.php";
		$this->MultiUpdateUrl = "artistupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'artist', TRUE);

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
		$this->BuildBasicSearchSQL($sWhere, $this->name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->image, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->location, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->website, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->twitter, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->facebook, $Keyword);
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
			$this->UpdateSort($this->user_id, $bCtrl); // user_id
			$this->UpdateSort($this->name, $bCtrl); // name
			$this->UpdateSort($this->image, $bCtrl); // image
			$this->UpdateSort($this->location, $bCtrl); // location
			$this->UpdateSort($this->website, $bCtrl); // website
			$this->UpdateSort($this->twitter, $bCtrl); // twitter
			$this->UpdateSort($this->facebook, $bCtrl); // facebook
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
				$this->user_id->setSort("");
				$this->name->setSort("");
				$this->image->setSort("");
				$this->location->setSort("");
				$this->website->setSort("");
				$this->twitter->setSort("");
				$this->facebook->setSort("");
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
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->name->setDbValue($rs->fields('name'));
		$this->image->setDbValue($rs->fields('image'));
		$this->location->setDbValue($rs->fields('location'));
		$this->website->setDbValue($rs->fields('website'));
		$this->twitter->setDbValue($rs->fields('twitter'));
		$this->facebook->setDbValue($rs->fields('facebook'));
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
		// user_id
		// name
		// image
		// location
		// website
		// twitter
		// facebook

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// user_id
			$this->user_id->ViewValue = $this->user_id->CurrentValue;
			$this->user_id->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// image
			$this->image->ViewValue = $this->image->CurrentValue;
			$this->image->ViewCustomAttributes = "";

			// location
			$this->location->ViewValue = $this->location->CurrentValue;
			$this->location->ViewCustomAttributes = "";

			// website
			$this->website->ViewValue = $this->website->CurrentValue;
			$this->website->ViewCustomAttributes = "";

			// twitter
			$this->twitter->ViewValue = $this->twitter->CurrentValue;
			$this->twitter->ViewCustomAttributes = "";

			// facebook
			$this->facebook->ViewValue = $this->facebook->CurrentValue;
			$this->facebook->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->HrefValue = "";
			$this->image->TooltipValue = "";

			// location
			$this->location->LinkCustomAttributes = "";
			$this->location->HrefValue = "";
			$this->location->TooltipValue = "";

			// website
			$this->website->LinkCustomAttributes = "";
			$this->website->HrefValue = "";
			$this->website->TooltipValue = "";

			// twitter
			$this->twitter->LinkCustomAttributes = "";
			$this->twitter->HrefValue = "";
			$this->twitter->TooltipValue = "";

			// facebook
			$this->facebook->LinkCustomAttributes = "";
			$this->facebook->HrefValue = "";
			$this->facebook->TooltipValue = "";
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
		$item->Body = "<a name=\"emf_artist\" id=\"emf_artist\" href=\"javascript:void(0);\" onclick=\"ew_EmailDialogShow({lnk:'emf_artist',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fartistlist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$table = 'artist';
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
if (!isset($artist_list)) $artist_list = new cartist_list();

// Page init
$artist_list->Page_Init();

// Page main
$artist_list->Page_Main();
?>
<?php include_once "header.php" ?>
<?php if ($artist->Export == "") { ?>
<script type="text/javascript">

// Page object
var artist_list = new ew_Page("artist_list");
artist_list.PageID = "list"; // Page ID
var EW_PAGE_ID = artist_list.PageID; // For backward compatibility

// Form object
var fartistlist = new ew_Form("fartistlist");

// Form_CustomValidate event
fartistlist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fartistlist.ValidateRequired = true;
<?php } else { ?>
fartistlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fartistlistsrch = new ew_Form("fartistlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$artist_list->TotalRecs = $artist->SelectRecordCount();
	} else {
		if ($artist_list->Recordset = $artist_list->LoadRecordset())
			$artist_list->TotalRecs = $artist_list->Recordset->RecordCount();
	}
	$artist_list->StartRec = 1;
	if ($artist_list->DisplayRecs <= 0 || ($artist->Export <> "" && $artist->ExportAll)) // Display all records
		$artist_list->DisplayRecs = $artist_list->TotalRecs;
	if (!($artist->Export <> "" && $artist->ExportAll))
		$artist_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$artist_list->Recordset = $artist_list->LoadRecordset($artist_list->StartRec-1, $artist_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $artist->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $artist_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($artist->Export == "" && $artist->CurrentAction == "") { ?>
<form name="fartistlistsrch" id="fartistlistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<a href="javascript:fartistlistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="fartistlistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" border="0"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="fartistlistsrch_SearchPanel">
<input type="hidden" name="t" value="artist">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($artist->getSessionBasicSearchKeyword()) ?>">
	<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $artist_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
</div>
<div id="xsr_2" class="ewRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($artist->getSessionBasicSearchType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($artist->getSessionBasicSearchType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($artist->getSessionBasicSearchType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $artist_list->ShowPageHeader(); ?>
<?php
$artist_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($artist->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($artist->CurrentAction <> "gridadd" && $artist->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php if (!isset($artist_list->Pager)) $artist_list->Pager = new cNumericPager($artist_list->StartRec, $artist_list->DisplayRecs, $artist_list->TotalRecs, $artist_list->RecRange) ?>
<?php if ($artist_list->Pager->RecordCount > 0) { ?>
	<?php if ($artist_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $artist_list->PageUrl() ?>start=<?php echo $artist_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($artist_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $artist_list->PageUrl() ?>start=<?php echo $artist_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($artist_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $artist_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($artist_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $artist_list->PageUrl() ?>start=<?php echo $artist_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($artist_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $artist_list->PageUrl() ?>start=<?php echo $artist_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($artist_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $artist_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $artist_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $artist_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($artist_list->SearchWhere == "0=101") { ?>
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
<?php if ($artist_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $artist_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<form name="fartistlist" id="fartistlist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="artist">
<div id="gmp_artist" class="ewGridMiddlePanel">
<?php if ($artist_list->TotalRecs > 0) { ?>
<table cellspacing="0" id="tbl_artistlist" class="ewTable ewTableSeparate">
<?php echo $artist->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$artist_list->RenderListOptions();

// Render list options (header, left)
$artist_list->ListOptions->Render("header", "left");
?>
<?php if ($artist->id->Visible) { // id ?>
	<?php if ($artist->SortUrl($artist->id) == "") { ?>
		<td><span id="elh_artist_id" class="artist_id"><?php echo $artist->id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $artist->SortUrl($artist->id) ?>',2);"><span id="elh_artist_id" class="artist_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $artist->id->FldCaption() ?></td><td style="width: 10px;"><?php if ($artist->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($artist->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($artist->user_id->Visible) { // user_id ?>
	<?php if ($artist->SortUrl($artist->user_id) == "") { ?>
		<td><span id="elh_artist_user_id" class="artist_user_id"><?php echo $artist->user_id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $artist->SortUrl($artist->user_id) ?>',2);"><span id="elh_artist_user_id" class="artist_user_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $artist->user_id->FldCaption() ?></td><td style="width: 10px;"><?php if ($artist->user_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($artist->user_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($artist->name->Visible) { // name ?>
	<?php if ($artist->SortUrl($artist->name) == "") { ?>
		<td><span id="elh_artist_name" class="artist_name"><?php echo $artist->name->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $artist->SortUrl($artist->name) ?>',2);"><span id="elh_artist_name" class="artist_name">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $artist->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($artist->name->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($artist->name->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($artist->image->Visible) { // image ?>
	<?php if ($artist->SortUrl($artist->image) == "") { ?>
		<td><span id="elh_artist_image" class="artist_image"><?php echo $artist->image->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $artist->SortUrl($artist->image) ?>',2);"><span id="elh_artist_image" class="artist_image">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $artist->image->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($artist->image->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($artist->image->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($artist->location->Visible) { // location ?>
	<?php if ($artist->SortUrl($artist->location) == "") { ?>
		<td><span id="elh_artist_location" class="artist_location"><?php echo $artist->location->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $artist->SortUrl($artist->location) ?>',2);"><span id="elh_artist_location" class="artist_location">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $artist->location->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($artist->location->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($artist->location->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($artist->website->Visible) { // website ?>
	<?php if ($artist->SortUrl($artist->website) == "") { ?>
		<td><span id="elh_artist_website" class="artist_website"><?php echo $artist->website->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $artist->SortUrl($artist->website) ?>',2);"><span id="elh_artist_website" class="artist_website">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $artist->website->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($artist->website->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($artist->website->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($artist->twitter->Visible) { // twitter ?>
	<?php if ($artist->SortUrl($artist->twitter) == "") { ?>
		<td><span id="elh_artist_twitter" class="artist_twitter"><?php echo $artist->twitter->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $artist->SortUrl($artist->twitter) ?>',2);"><span id="elh_artist_twitter" class="artist_twitter">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $artist->twitter->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($artist->twitter->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($artist->twitter->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($artist->facebook->Visible) { // facebook ?>
	<?php if ($artist->SortUrl($artist->facebook) == "") { ?>
		<td><span id="elh_artist_facebook" class="artist_facebook"><?php echo $artist->facebook->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $artist->SortUrl($artist->facebook) ?>',2);"><span id="elh_artist_facebook" class="artist_facebook">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $artist->facebook->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($artist->facebook->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($artist->facebook->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$artist_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($artist->ExportAll && $artist->Export <> "") {
	$artist_list->StopRec = $artist_list->TotalRecs;
} else {

	// Set the last record to display
	if ($artist_list->TotalRecs > $artist_list->StartRec + $artist_list->DisplayRecs - 1)
		$artist_list->StopRec = $artist_list->StartRec + $artist_list->DisplayRecs - 1;
	else
		$artist_list->StopRec = $artist_list->TotalRecs;
}
$artist_list->RecCnt = $artist_list->StartRec - 1;
if ($artist_list->Recordset && !$artist_list->Recordset->EOF) {
	$artist_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $artist_list->StartRec > 1)
		$artist_list->Recordset->Move($artist_list->StartRec - 1);
} elseif (!$artist->AllowAddDeleteRow && $artist_list->StopRec == 0) {
	$artist_list->StopRec = $artist->GridAddRowCount;
}

// Initialize aggregate
$artist->RowType = EW_ROWTYPE_AGGREGATEINIT;
$artist->ResetAttrs();
$artist_list->RenderRow();
while ($artist_list->RecCnt < $artist_list->StopRec) {
	$artist_list->RecCnt++;
	if (intval($artist_list->RecCnt) >= intval($artist_list->StartRec)) {
		$artist_list->RowCnt++;

		// Set up key count
		$artist_list->KeyCount = $artist_list->RowIndex;

		// Init row class and style
		$artist->ResetAttrs();
		$artist->CssClass = "";
		if ($artist->CurrentAction == "gridadd") {
		} else {
			$artist_list->LoadRowValues($artist_list->Recordset); // Load row values
		}
		$artist->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$artist->RowAttrs = array_merge($artist->RowAttrs, array('data-rowindex'=>$artist_list->RowCnt, 'id'=>'r' . $artist_list->RowCnt . '_artist', 'data-rowtype'=>$artist->RowType));

		// Render row
		$artist_list->RenderRow();

		// Render list options
		$artist_list->RenderListOptions();
?>
	<tr<?php echo $artist->RowAttributes() ?>>
<?php

// Render list options (body, left)
$artist_list->ListOptions->Render("body", "left", $artist_list->RowCnt);
?>
	<?php if ($artist->id->Visible) { // id ?>
		<td<?php echo $artist->id->CellAttributes() ?>><span id="el<?php echo $artist_list->RowCnt ?>_artist_id" class="artist_id">
<span<?php echo $artist->id->ViewAttributes() ?>>
<?php echo $artist->id->ListViewValue() ?></span>
<a name="<?php echo $artist_list->PageObjName . "_row_" . $artist_list->RowCnt ?>" id="<?php echo $artist_list->PageObjName . "_row_" . $artist_list->RowCnt ?>"></a></span></td>
	<?php } ?>
	<?php if ($artist->user_id->Visible) { // user_id ?>
		<td<?php echo $artist->user_id->CellAttributes() ?>><span id="el<?php echo $artist_list->RowCnt ?>_artist_user_id" class="artist_user_id">
<span<?php echo $artist->user_id->ViewAttributes() ?>>
<?php echo $artist->user_id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($artist->name->Visible) { // name ?>
		<td<?php echo $artist->name->CellAttributes() ?>><span id="el<?php echo $artist_list->RowCnt ?>_artist_name" class="artist_name">
<span<?php echo $artist->name->ViewAttributes() ?>>
<?php echo $artist->name->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($artist->image->Visible) { // image ?>
		<td<?php echo $artist->image->CellAttributes() ?>><span id="el<?php echo $artist_list->RowCnt ?>_artist_image" class="artist_image">
<span<?php echo $artist->image->ViewAttributes() ?>>
<?php echo $artist->image->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($artist->location->Visible) { // location ?>
		<td<?php echo $artist->location->CellAttributes() ?>><span id="el<?php echo $artist_list->RowCnt ?>_artist_location" class="artist_location">
<span<?php echo $artist->location->ViewAttributes() ?>>
<?php echo $artist->location->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($artist->website->Visible) { // website ?>
		<td<?php echo $artist->website->CellAttributes() ?>><span id="el<?php echo $artist_list->RowCnt ?>_artist_website" class="artist_website">
<span<?php echo $artist->website->ViewAttributes() ?>>
<?php echo $artist->website->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($artist->twitter->Visible) { // twitter ?>
		<td<?php echo $artist->twitter->CellAttributes() ?>><span id="el<?php echo $artist_list->RowCnt ?>_artist_twitter" class="artist_twitter">
<span<?php echo $artist->twitter->ViewAttributes() ?>>
<?php echo $artist->twitter->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($artist->facebook->Visible) { // facebook ?>
		<td<?php echo $artist->facebook->CellAttributes() ?>><span id="el<?php echo $artist_list->RowCnt ?>_artist_facebook" class="artist_facebook">
<span<?php echo $artist->facebook->ViewAttributes() ?>>
<?php echo $artist->facebook->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$artist_list->ListOptions->Render("body", "right", $artist_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($artist->CurrentAction <> "gridadd")
		$artist_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($artist->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($artist_list->Recordset)
	$artist_list->Recordset->Close();
?>
<?php if ($artist_list->TotalRecs > 0) { ?>
<?php if ($artist->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($artist->CurrentAction <> "gridadd" && $artist->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php if (!isset($artist_list->Pager)) $artist_list->Pager = new cNumericPager($artist_list->StartRec, $artist_list->DisplayRecs, $artist_list->TotalRecs, $artist_list->RecRange) ?>
<?php if ($artist_list->Pager->RecordCount > 0) { ?>
	<?php if ($artist_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $artist_list->PageUrl() ?>start=<?php echo $artist_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($artist_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $artist_list->PageUrl() ?>start=<?php echo $artist_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($artist_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $artist_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($artist_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $artist_list->PageUrl() ?>start=<?php echo $artist_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($artist_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $artist_list->PageUrl() ?>start=<?php echo $artist_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($artist_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $artist_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $artist_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $artist_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($artist_list->SearchWhere == "0=101") { ?>
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
<?php if ($artist_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $artist_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($artist->Export == "") { ?>
<script type="text/javascript">
fartistlistsrch.Init();
fartistlist.Init();
</script>
<?php } ?>
<?php
$artist_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($artist->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$artist_list->Page_Terminate();
?>
