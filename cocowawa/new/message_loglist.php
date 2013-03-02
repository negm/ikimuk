<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "message_loginfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$message_log_list = NULL; // Initialize page object first

class cmessage_log_list extends cmessage_log {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'message_log';

	// Page object name
	var $PageObjName = 'message_log_list';

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

		// Table object (message_log)
		if (!isset($GLOBALS["message_log"])) {
			$GLOBALS["message_log"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["message_log"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "message_logadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "message_logdelete.php";
		$this->MultiUpdateUrl = "message_logupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'message_log', TRUE);

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
		$this->BuildBasicSearchSQL($sWhere, $this->messageId, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->requestId, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->recepient, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->subject, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->body, $Keyword);
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
			$this->UpdateSort($this->messageId, $bCtrl); // messageId
			$this->UpdateSort($this->requestId, $bCtrl); // requestId
			$this->UpdateSort($this->recepient, $bCtrl); // recepient
			$this->UpdateSort($this->subject, $bCtrl); // subject
			$this->UpdateSort($this->body, $bCtrl); // body
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

			// Reset search criteria
			if (strtolower($sCmd) == "reset" || strtolower($sCmd) == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if (strtolower($sCmd) == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id->setSort("");
				$this->messageId->setSort("");
				$this->requestId->setSort("");
				$this->recepient->setSort("");
				$this->subject->setSort("");
				$this->body->setSort("");
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
		$this->messageId->setDbValue($rs->fields('messageId'));
		$this->requestId->setDbValue($rs->fields('requestId'));
		$this->recepient->setDbValue($rs->fields('recepient'));
		$this->subject->setDbValue($rs->fields('subject'));
		$this->body->setDbValue($rs->fields('body'));
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// messageId
		// requestId
		// recepient
		// subject
		// body
		// date_added

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// messageId
			$this->messageId->ViewValue = $this->messageId->CurrentValue;
			$this->messageId->ViewCustomAttributes = "";

			// requestId
			$this->requestId->ViewValue = $this->requestId->CurrentValue;
			$this->requestId->ViewCustomAttributes = "";

			// recepient
			$this->recepient->ViewValue = $this->recepient->CurrentValue;
			$this->recepient->ViewCustomAttributes = "";

			// subject
			$this->subject->ViewValue = $this->subject->CurrentValue;
			$this->subject->ViewCustomAttributes = "";

			// body
			$this->body->ViewValue = $this->body->CurrentValue;
			$this->body->ViewCustomAttributes = "";

			// date_added
			$this->date_added->ViewValue = $this->date_added->CurrentValue;
			$this->date_added->ViewValue = ew_FormatDateTime($this->date_added->ViewValue, 7);
			$this->date_added->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// messageId
			$this->messageId->LinkCustomAttributes = "";
			$this->messageId->HrefValue = "";
			$this->messageId->TooltipValue = "";

			// requestId
			$this->requestId->LinkCustomAttributes = "";
			$this->requestId->HrefValue = "";
			$this->requestId->TooltipValue = "";

			// recepient
			$this->recepient->LinkCustomAttributes = "";
			$this->recepient->HrefValue = "";
			$this->recepient->TooltipValue = "";

			// subject
			$this->subject->LinkCustomAttributes = "";
			$this->subject->HrefValue = "";
			$this->subject->TooltipValue = "";

			// body
			$this->body->LinkCustomAttributes = "";
			$this->body->HrefValue = "";
			$this->body->TooltipValue = "";

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
		$item->Body = "<a name=\"emf_message_log\" id=\"emf_message_log\" href=\"javascript:void(0);\" onclick=\"ew_EmailDialogShow({lnk:'emf_message_log',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fmessage_loglist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($message_log_list)) $message_log_list = new cmessage_log_list();

// Page init
$message_log_list->Page_Init();

// Page main
$message_log_list->Page_Main();
?>
<?php include_once "header.php" ?>
<?php if ($message_log->Export == "") { ?>
<script type="text/javascript">

// Page object
var message_log_list = new ew_Page("message_log_list");
message_log_list.PageID = "list"; // Page ID
var EW_PAGE_ID = message_log_list.PageID; // For backward compatibility

// Form object
var fmessage_loglist = new ew_Form("fmessage_loglist");

// Form_CustomValidate event
fmessage_loglist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmessage_loglist.ValidateRequired = true;
<?php } else { ?>
fmessage_loglist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fmessage_loglistsrch = new ew_Form("fmessage_loglistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$message_log_list->TotalRecs = $message_log->SelectRecordCount();
	} else {
		if ($message_log_list->Recordset = $message_log_list->LoadRecordset())
			$message_log_list->TotalRecs = $message_log_list->Recordset->RecordCount();
	}
	$message_log_list->StartRec = 1;
	if ($message_log_list->DisplayRecs <= 0 || ($message_log->Export <> "" && $message_log->ExportAll)) // Display all records
		$message_log_list->DisplayRecs = $message_log_list->TotalRecs;
	if (!($message_log->Export <> "" && $message_log->ExportAll))
		$message_log_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$message_log_list->Recordset = $message_log_list->LoadRecordset($message_log_list->StartRec-1, $message_log_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $message_log->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $message_log_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($message_log->Export == "" && $message_log->CurrentAction == "") { ?>
<form name="fmessage_loglistsrch" id="fmessage_loglistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<a href="javascript:fmessage_loglistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="fmessage_loglistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" border="0"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="fmessage_loglistsrch_SearchPanel">
<input type="hidden" name="t" value="message_log">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($message_log->getSessionBasicSearchKeyword()) ?>">
	<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $message_log_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
</div>
<div id="xsr_2" class="ewRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($message_log->getSessionBasicSearchType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($message_log->getSessionBasicSearchType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($message_log->getSessionBasicSearchType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $message_log_list->ShowPageHeader(); ?>
<?php
$message_log_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($message_log->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($message_log->CurrentAction <> "gridadd" && $message_log->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php if (!isset($message_log_list->Pager)) $message_log_list->Pager = new cNumericPager($message_log_list->StartRec, $message_log_list->DisplayRecs, $message_log_list->TotalRecs, $message_log_list->RecRange) ?>
<?php if ($message_log_list->Pager->RecordCount > 0) { ?>
	<?php if ($message_log_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $message_log_list->PageUrl() ?>start=<?php echo $message_log_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($message_log_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $message_log_list->PageUrl() ?>start=<?php echo $message_log_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($message_log_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $message_log_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($message_log_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $message_log_list->PageUrl() ?>start=<?php echo $message_log_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($message_log_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $message_log_list->PageUrl() ?>start=<?php echo $message_log_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($message_log_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $message_log_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $message_log_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $message_log_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($message_log_list->SearchWhere == "0=101") { ?>
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
<?php if ($message_log_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $message_log_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<form name="fmessage_loglist" id="fmessage_loglist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="message_log">
<div id="gmp_message_log" class="ewGridMiddlePanel">
<?php if ($message_log_list->TotalRecs > 0) { ?>
<table cellspacing="0" id="tbl_message_loglist" class="ewTable ewTableSeparate">
<?php echo $message_log->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$message_log_list->RenderListOptions();

// Render list options (header, left)
$message_log_list->ListOptions->Render("header", "left");
?>
<?php if ($message_log->id->Visible) { // id ?>
	<?php if ($message_log->SortUrl($message_log->id) == "") { ?>
		<td><span id="elh_message_log_id" class="message_log_id"><?php echo $message_log->id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $message_log->SortUrl($message_log->id) ?>',2);"><span id="elh_message_log_id" class="message_log_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $message_log->id->FldCaption() ?></td><td style="width: 10px;"><?php if ($message_log->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($message_log->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($message_log->messageId->Visible) { // messageId ?>
	<?php if ($message_log->SortUrl($message_log->messageId) == "") { ?>
		<td><span id="elh_message_log_messageId" class="message_log_messageId"><?php echo $message_log->messageId->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $message_log->SortUrl($message_log->messageId) ?>',2);"><span id="elh_message_log_messageId" class="message_log_messageId">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $message_log->messageId->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($message_log->messageId->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($message_log->messageId->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($message_log->requestId->Visible) { // requestId ?>
	<?php if ($message_log->SortUrl($message_log->requestId) == "") { ?>
		<td><span id="elh_message_log_requestId" class="message_log_requestId"><?php echo $message_log->requestId->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $message_log->SortUrl($message_log->requestId) ?>',2);"><span id="elh_message_log_requestId" class="message_log_requestId">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $message_log->requestId->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($message_log->requestId->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($message_log->requestId->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($message_log->recepient->Visible) { // recepient ?>
	<?php if ($message_log->SortUrl($message_log->recepient) == "") { ?>
		<td><span id="elh_message_log_recepient" class="message_log_recepient"><?php echo $message_log->recepient->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $message_log->SortUrl($message_log->recepient) ?>',2);"><span id="elh_message_log_recepient" class="message_log_recepient">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $message_log->recepient->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($message_log->recepient->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($message_log->recepient->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($message_log->subject->Visible) { // subject ?>
	<?php if ($message_log->SortUrl($message_log->subject) == "") { ?>
		<td><span id="elh_message_log_subject" class="message_log_subject"><?php echo $message_log->subject->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $message_log->SortUrl($message_log->subject) ?>',2);"><span id="elh_message_log_subject" class="message_log_subject">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $message_log->subject->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($message_log->subject->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($message_log->subject->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($message_log->body->Visible) { // body ?>
	<?php if ($message_log->SortUrl($message_log->body) == "") { ?>
		<td><span id="elh_message_log_body" class="message_log_body"><?php echo $message_log->body->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $message_log->SortUrl($message_log->body) ?>',2);"><span id="elh_message_log_body" class="message_log_body">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $message_log->body->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($message_log->body->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($message_log->body->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($message_log->date_added->Visible) { // date_added ?>
	<?php if ($message_log->SortUrl($message_log->date_added) == "") { ?>
		<td><span id="elh_message_log_date_added" class="message_log_date_added"><?php echo $message_log->date_added->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $message_log->SortUrl($message_log->date_added) ?>',2);"><span id="elh_message_log_date_added" class="message_log_date_added">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $message_log->date_added->FldCaption() ?></td><td style="width: 10px;"><?php if ($message_log->date_added->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($message_log->date_added->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$message_log_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($message_log->ExportAll && $message_log->Export <> "") {
	$message_log_list->StopRec = $message_log_list->TotalRecs;
} else {

	// Set the last record to display
	if ($message_log_list->TotalRecs > $message_log_list->StartRec + $message_log_list->DisplayRecs - 1)
		$message_log_list->StopRec = $message_log_list->StartRec + $message_log_list->DisplayRecs - 1;
	else
		$message_log_list->StopRec = $message_log_list->TotalRecs;
}
$message_log_list->RecCnt = $message_log_list->StartRec - 1;
if ($message_log_list->Recordset && !$message_log_list->Recordset->EOF) {
	$message_log_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $message_log_list->StartRec > 1)
		$message_log_list->Recordset->Move($message_log_list->StartRec - 1);
} elseif (!$message_log->AllowAddDeleteRow && $message_log_list->StopRec == 0) {
	$message_log_list->StopRec = $message_log->GridAddRowCount;
}

// Initialize aggregate
$message_log->RowType = EW_ROWTYPE_AGGREGATEINIT;
$message_log->ResetAttrs();
$message_log_list->RenderRow();
while ($message_log_list->RecCnt < $message_log_list->StopRec) {
	$message_log_list->RecCnt++;
	if (intval($message_log_list->RecCnt) >= intval($message_log_list->StartRec)) {
		$message_log_list->RowCnt++;

		// Set up key count
		$message_log_list->KeyCount = $message_log_list->RowIndex;

		// Init row class and style
		$message_log->ResetAttrs();
		$message_log->CssClass = "";
		if ($message_log->CurrentAction == "gridadd") {
		} else {
			$message_log_list->LoadRowValues($message_log_list->Recordset); // Load row values
		}
		$message_log->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$message_log->RowAttrs = array_merge($message_log->RowAttrs, array('data-rowindex'=>$message_log_list->RowCnt, 'id'=>'r' . $message_log_list->RowCnt . '_message_log', 'data-rowtype'=>$message_log->RowType));

		// Render row
		$message_log_list->RenderRow();

		// Render list options
		$message_log_list->RenderListOptions();
?>
	<tr<?php echo $message_log->RowAttributes() ?>>
<?php

// Render list options (body, left)
$message_log_list->ListOptions->Render("body", "left", $message_log_list->RowCnt);
?>
	<?php if ($message_log->id->Visible) { // id ?>
		<td<?php echo $message_log->id->CellAttributes() ?>><span id="el<?php echo $message_log_list->RowCnt ?>_message_log_id" class="message_log_id">
<span<?php echo $message_log->id->ViewAttributes() ?>>
<?php echo $message_log->id->ListViewValue() ?></span>
<a name="<?php echo $message_log_list->PageObjName . "_row_" . $message_log_list->RowCnt ?>" id="<?php echo $message_log_list->PageObjName . "_row_" . $message_log_list->RowCnt ?>"></a></span></td>
	<?php } ?>
	<?php if ($message_log->messageId->Visible) { // messageId ?>
		<td<?php echo $message_log->messageId->CellAttributes() ?>><span id="el<?php echo $message_log_list->RowCnt ?>_message_log_messageId" class="message_log_messageId">
<span<?php echo $message_log->messageId->ViewAttributes() ?>>
<?php echo $message_log->messageId->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($message_log->requestId->Visible) { // requestId ?>
		<td<?php echo $message_log->requestId->CellAttributes() ?>><span id="el<?php echo $message_log_list->RowCnt ?>_message_log_requestId" class="message_log_requestId">
<span<?php echo $message_log->requestId->ViewAttributes() ?>>
<?php echo $message_log->requestId->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($message_log->recepient->Visible) { // recepient ?>
		<td<?php echo $message_log->recepient->CellAttributes() ?>><span id="el<?php echo $message_log_list->RowCnt ?>_message_log_recepient" class="message_log_recepient">
<span<?php echo $message_log->recepient->ViewAttributes() ?>>
<?php echo $message_log->recepient->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($message_log->subject->Visible) { // subject ?>
		<td<?php echo $message_log->subject->CellAttributes() ?>><span id="el<?php echo $message_log_list->RowCnt ?>_message_log_subject" class="message_log_subject">
<span<?php echo $message_log->subject->ViewAttributes() ?>>
<?php echo $message_log->subject->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($message_log->body->Visible) { // body ?>
		<td<?php echo $message_log->body->CellAttributes() ?>><span id="el<?php echo $message_log_list->RowCnt ?>_message_log_body" class="message_log_body">
<span<?php echo $message_log->body->ViewAttributes() ?>>
<?php echo $message_log->body->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($message_log->date_added->Visible) { // date_added ?>
		<td<?php echo $message_log->date_added->CellAttributes() ?>><span id="el<?php echo $message_log_list->RowCnt ?>_message_log_date_added" class="message_log_date_added">
<span<?php echo $message_log->date_added->ViewAttributes() ?>>
<?php echo $message_log->date_added->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$message_log_list->ListOptions->Render("body", "right", $message_log_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($message_log->CurrentAction <> "gridadd")
		$message_log_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($message_log->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($message_log_list->Recordset)
	$message_log_list->Recordset->Close();
?>
<?php if ($message_log_list->TotalRecs > 0) { ?>
<?php if ($message_log->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($message_log->CurrentAction <> "gridadd" && $message_log->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php if (!isset($message_log_list->Pager)) $message_log_list->Pager = new cNumericPager($message_log_list->StartRec, $message_log_list->DisplayRecs, $message_log_list->TotalRecs, $message_log_list->RecRange) ?>
<?php if ($message_log_list->Pager->RecordCount > 0) { ?>
	<?php if ($message_log_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $message_log_list->PageUrl() ?>start=<?php echo $message_log_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($message_log_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $message_log_list->PageUrl() ?>start=<?php echo $message_log_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($message_log_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $message_log_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($message_log_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $message_log_list->PageUrl() ?>start=<?php echo $message_log_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($message_log_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $message_log_list->PageUrl() ?>start=<?php echo $message_log_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($message_log_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $message_log_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $message_log_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $message_log_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($message_log_list->SearchWhere == "0=101") { ?>
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
<?php if ($message_log_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $message_log_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($message_log->Export == "") { ?>
<script type="text/javascript">
fmessage_loglistsrch.Init();
fmessage_loglist.Init();
</script>
<?php } ?>
<?php
$message_log_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($message_log->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$message_log_list->Page_Terminate();
?>
