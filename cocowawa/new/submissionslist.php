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

$submissions_list = NULL; // Initialize page object first

class csubmissions_list extends csubmissions {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0CA276D3-D17C-410E-8A7B-A18E811C9C5A}";

	// Table name
	var $TableName = 'submissions';

	// Page object name
	var $PageObjName = 'submissions_list';

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
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "submissionsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "submissionsdelete.php";
		$this->MultiUpdateUrl = "submissionsupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'submissions', TRUE);

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

		// Create form object
		$objForm = new cFormObj();

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
	var $HashValue; // Hash value
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

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();
				}
			}

			// Hide all options
			if ($this->Export <> "" ||
				$this->CurrentAction == "gridadd" ||
				$this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ExportOptions->HideAllOptions();
			}

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session
			$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
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

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
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
			if ($sSrchAdvanced == "")
				$this->ResetAdvancedSearchParms();
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

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("id", ""); // Clear inline edit key
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		$bInlineEdit = TRUE;
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("id", $this->id->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {

			// Overwrite record, just reload hash value
			if ($this->CurrentAction == "overwrite")
				$this->LoadRowHash();
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue("k_key"));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("id")) <> strval($this->id->CurrentValue))
			return FALSE;
		return TRUE;
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

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		$this->BuildSearchSql($sWhere, $this->id, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->competition_id, FALSE); // competition_id
		$this->BuildSearchSql($sWhere, $this->user_id, FALSE); // user_id
		$this->BuildSearchSql($sWhere, $this->title, FALSE); // title
		$this->BuildSearchSql($sWhere, $this->comments, FALSE); // comments
		$this->BuildSearchSql($sWhere, $this->newsletter, FALSE); // newsletter
		$this->BuildSearchSql($sWhere, $this->submission_date, FALSE); // submission_date

		// Set up search parm
		//if ($sWhere <> "") {

		if (!$this->RestoreSearch) {
			$this->SetSearchParm($this->id); // id
			$this->SetSearchParm($this->competition_id); // competition_id
			$this->SetSearchParm($this->user_id); // user_id
			$this->SetSearchParm($this->title); // title
			$this->SetSearchParm($this->comments); // comments
			$this->SetSearchParm($this->newsletter); // newsletter
			$this->SetSearchParm($this->submission_date); // submission_date
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);		
		$FldVal = $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Set search parameters
	function SetSearchParm(&$Fld) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$this->setAdvancedSearch("x_$FldParm", $FldVal);
		$this->setAdvancedSearch("z_$FldParm", $Fld->AdvancedSearch->SearchOperator); // @$_GET["z_$FldParm"]
		$this->setAdvancedSearch("v_$FldParm", $Fld->AdvancedSearch->SearchCondition); // @$_GET["v_$FldParm"]
		$this->setAdvancedSearch("y_$FldParm", $FldVal2);
		$this->setAdvancedSearch("w_$FldParm", $Fld->AdvancedSearch->SearchOperator2); // @$_GET["w_$FldParm"]
	}

	// Get search parameters
	function GetSearchParm(&$Fld) {
		$FldParm = substr($Fld->FldVar, 2);
		$Fld->AdvancedSearch->SearchValue = $this->getAdvancedSearch("x_$FldParm");
		$Fld->AdvancedSearch->SearchOperator = $this->getAdvancedSearch("z_$FldParm");
		$Fld->AdvancedSearch->SearchCondition = $this->getAdvancedSearch("v_$FldParm");
		$Fld->AdvancedSearch->SearchValue2 = $this->getAdvancedSearch("y_$FldParm");
		$Fld->AdvancedSearch->SearchOperator2 = $this->getAdvancedSearch("w_$FldParm");
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->title, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->comments, $Keyword);
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

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
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

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->setAdvancedSearch("x_id", "");
		$this->setAdvancedSearch("x_competition_id", "");
		$this->setAdvancedSearch("x_user_id", "");
		$this->setAdvancedSearch("x_title", "");
		$this->setAdvancedSearch("x_comments", "");
		$this->setAdvancedSearch("x_newsletter", "");
		$this->setAdvancedSearch("x_submission_date", "");
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$bRestore = TRUE;
		if ($this->BasicSearchKeyword <> "") $bRestore = FALSE;
		if ($this->id->AdvancedSearch->SearchValue <> "" || $this->id->AdvancedSearch->SearchOperator <> "") $bRestore = FALSE;
		if ($this->competition_id->AdvancedSearch->SearchValue <> "" || $this->competition_id->AdvancedSearch->SearchOperator <> "") $bRestore = FALSE;
		if ($this->user_id->AdvancedSearch->SearchValue <> "" || $this->user_id->AdvancedSearch->SearchOperator <> "") $bRestore = FALSE;
		if ($this->title->AdvancedSearch->SearchValue <> "" || $this->title->AdvancedSearch->SearchOperator <> "") $bRestore = FALSE;
		if ($this->comments->AdvancedSearch->SearchValue <> "" || $this->comments->AdvancedSearch->SearchOperator <> "") $bRestore = FALSE;
		if ($this->newsletter->AdvancedSearch->SearchValue <> "" || $this->newsletter->AdvancedSearch->SearchOperator <> "") $bRestore = FALSE;
		if ($this->submission_date->AdvancedSearch->SearchValue <> "" || $this->submission_date->AdvancedSearch->SearchOperator <> "") $bRestore = FALSE;
		$this->RestoreSearch = $bRestore;
		if ($bRestore) {

			// Restore basic search values
			$this->BasicSearchKeyword = $this->getSessionBasicSearchKeyword();
			if ($this->getSessionBasicSearchType() == "") $this->setSessionBasicSearchType("=");
			$this->BasicSearchType = $this->getSessionBasicSearchType();

			// Restore advanced search values
			$this->GetSearchParm($this->id);
			$this->GetSearchParm($this->competition_id);
			$this->GetSearchParm($this->user_id);
			$this->GetSearchParm($this->title);
			$this->GetSearchParm($this->comments);
			$this->GetSearchParm($this->newsletter);
			$this->GetSearchParm($this->submission_date);
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
			$this->UpdateSort($this->competition_id, $bCtrl); // competition_id
			$this->UpdateSort($this->user_id, $bCtrl); // user_id
			$this->UpdateSort($this->title, $bCtrl); // title
			$this->UpdateSort($this->comments, $bCtrl); // comments
			$this->UpdateSort($this->newsletter, $bCtrl); // newsletter
			$this->UpdateSort($this->submission_date, $bCtrl); // submission_date
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
				$this->competition_id->setSort("");
				$this->user_id->setSort("");
				$this->title->setSort("");
				$this->comments->setSort("");
				$this->newsletter->setSort("");
				$this->submission_date->setSort("");
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

		// Set up row action and key
		if (is_numeric($this->RowIndex)) {
			$objForm->Index = $this->RowIndex;
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_action\" id=\"k" . $this->RowIndex . "_action\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue("k_key");
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_blankrow\" id=\"k" . $this->RowIndex . "_blankrow\" value=\"1\">";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
			if ($this->UpdateConflict == "U") {
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewRowLink\" href=\"" . $this->InlineEditUrl . "#" . $this->PageObjName . "_row_" . $this->RowCnt . "\">" .
					$Language->Phrase("ReloadLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink\" href=\"\" onclick=\"return ewForms['fsubmissionslist'].Submit('" . $this->PageName() . "#" . $this->PageObjName . "_row_" . $this->RowCnt . "');\">" . $Language->Phrase("OverwriteLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("ConflictCancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"overwrite\"></div>";
			} else {
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink\" href=\"\" onclick=\"return ewForms['fsubmissionslist'].Submit('" . $this->PageName() . "#" . $this->PageObjName . "_row_" . $this->RowCnt . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			}
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_hash\" id=\"k" . $this->RowIndex . "_hash\" value=\"" . $this->HashValue . "\">";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->ViewUrl . "\">" . $Language->Phrase("ViewLink") . "</a>";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->EditUrl . "\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<span class=\"ewSeparator\">&nbsp;|&nbsp;</span>";
			$oListOpt->Body .= "<a class=\"ewRowLink\" href=\"" . $this->InlineEditUrl . "#" . $this->PageObjName . "_row_" . $this->RowCnt . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
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

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->competition_id->CurrentValue = NULL;
		$this->competition_id->OldValue = $this->competition_id->CurrentValue;
		$this->user_id->CurrentValue = NULL;
		$this->user_id->OldValue = $this->user_id->CurrentValue;
		$this->title->CurrentValue = NULL;
		$this->title->OldValue = $this->title->CurrentValue;
		$this->comments->CurrentValue = NULL;
		$this->comments->OldValue = $this->comments->CurrentValue;
		$this->newsletter->CurrentValue = NULL;
		$this->newsletter->OldValue = $this->newsletter->CurrentValue;
		$this->submission_date->CurrentValue = NULL;
		$this->submission_date->OldValue = $this->submission_date->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearchKeyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		$this->BasicSearchType = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id

		$this->id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id"]);
		$this->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// competition_id
		$this->competition_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_competition_id"]);
		$this->competition_id->AdvancedSearch->SearchOperator = @$_GET["z_competition_id"];

		// user_id
		$this->user_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_user_id"]);
		$this->user_id->AdvancedSearch->SearchOperator = @$_GET["z_user_id"];

		// title
		$this->title->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_title"]);
		$this->title->AdvancedSearch->SearchOperator = @$_GET["z_title"];

		// comments
		$this->comments->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_comments"]);
		$this->comments->AdvancedSearch->SearchOperator = @$_GET["z_comments"];

		// newsletter
		$this->newsletter->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_newsletter"]);
		$this->newsletter->AdvancedSearch->SearchOperator = @$_GET["z_newsletter"];

		// submission_date
		$this->submission_date->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_submission_date"]);
		$this->submission_date->AdvancedSearch->SearchOperator = @$_GET["z_submission_date"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->competition_id->FldIsDetailKey) {
			$this->competition_id->setFormValue($objForm->GetValue("x_competition_id"));
		}
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->comments->FldIsDetailKey) {
			$this->comments->setFormValue($objForm->GetValue("x_comments"));
		}
		if (!$this->newsletter->FldIsDetailKey) {
			$this->newsletter->setFormValue($objForm->GetValue("x_newsletter"));
		}
		if (!$this->submission_date->FldIsDetailKey) {
			$this->submission_date->setFormValue($objForm->GetValue("x_submission_date"));
			$this->submission_date->CurrentValue = ew_UnFormatDateTime($this->submission_date->CurrentValue, 7);
		}
		if ($this->CurrentAction <> "overwrite")
			$this->HashValue = $objForm->GetValue("k_hash");
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->CurrentValue = $this->id->FormValue;
		$this->competition_id->CurrentValue = $this->competition_id->FormValue;
		$this->user_id->CurrentValue = $this->user_id->FormValue;
		$this->title->CurrentValue = $this->title->FormValue;
		$this->comments->CurrentValue = $this->comments->FormValue;
		$this->newsletter->CurrentValue = $this->newsletter->FormValue;
		$this->submission_date->CurrentValue = $this->submission_date->FormValue;
		$this->submission_date->CurrentValue = ew_UnFormatDateTime($this->submission_date->CurrentValue, 7);
		if ($this->CurrentAction <> "overwrite")
			$this->HashValue = $objForm->GetValue("k_hash");
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
			if (!$this->EventCancelled)
				$this->HashValue = $this->GetRowHash($rs); // Get hash value for record
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
		$this->competition_id->setDbValue($rs->fields('competition_id'));
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->title->setDbValue($rs->fields('title'));
		$this->comments->setDbValue($rs->fields('comments'));
		$this->newsletter->setDbValue($rs->fields('newsletter'));
		$this->submission_date->setDbValue($rs->fields('submission_date'));
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			// competition_id

			$this->competition_id->EditCustomAttributes = "";
			$this->competition_id->EditValue = ew_HtmlEncode($this->competition_id->CurrentValue);

			// user_id
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);

			// title
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);

			// comments
			$this->comments->EditCustomAttributes = "";
			$this->comments->EditValue = ew_HtmlEncode($this->comments->CurrentValue);

			// newsletter
			$this->newsletter->EditCustomAttributes = "";
			$this->newsletter->EditValue = ew_HtmlEncode($this->newsletter->CurrentValue);

			// submission_date
			$this->submission_date->EditCustomAttributes = "";
			$this->submission_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->submission_date->CurrentValue, 7));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// competition_id
			$this->competition_id->HrefValue = "";

			// user_id
			$this->user_id->HrefValue = "";

			// title
			$this->title->HrefValue = "";

			// comments
			$this->comments->HrefValue = "";

			// newsletter
			$this->newsletter->HrefValue = "";

			// submission_date
			$this->submission_date->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// competition_id
			$this->competition_id->EditCustomAttributes = "";
			$this->competition_id->EditValue = ew_HtmlEncode($this->competition_id->CurrentValue);

			// user_id
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);

			// title
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);

			// comments
			$this->comments->EditCustomAttributes = "";
			$this->comments->EditValue = ew_HtmlEncode($this->comments->CurrentValue);

			// newsletter
			$this->newsletter->EditCustomAttributes = "";
			$this->newsletter->EditValue = ew_HtmlEncode($this->newsletter->CurrentValue);

			// submission_date
			$this->submission_date->EditCustomAttributes = "";
			$this->submission_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->submission_date->CurrentValue, 7));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// competition_id
			$this->competition_id->HrefValue = "";

			// user_id
			$this->user_id->HrefValue = "";

			// title
			$this->title->HrefValue = "";

			// comments
			$this->comments->HrefValue = "";

			// newsletter
			$this->newsletter->HrefValue = "";

			// submission_date
			$this->submission_date->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!is_null($this->competition_id->FormValue) && $this->competition_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->competition_id->FldCaption());
		}
		if (!ew_CheckInteger($this->competition_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->competition_id->FldErrMsg());
		}
		if (!is_null($this->user_id->FormValue) && $this->user_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->user_id->FldCaption());
		}
		if (!ew_CheckInteger($this->user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->user_id->FldErrMsg());
		}
		if (!is_null($this->title->FormValue) && $this->title->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->title->FldCaption());
		}
		if (!ew_CheckInteger($this->newsletter->FormValue)) {
			ew_AddMessage($gsFormError, $this->newsletter->FldErrMsg());
		}
		if (!is_null($this->submission_date->FormValue) && $this->submission_date->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->submission_date->FldCaption());
		}
		if (!ew_CheckEuroDate($this->submission_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->submission_date->FldErrMsg());
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

			// competition_id
			$this->competition_id->SetDbValueDef($rsnew, $this->competition_id->CurrentValue, 0, $this->competition_id->ReadOnly);

			// user_id
			$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, 0, $this->user_id->ReadOnly);

			// title
			$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", $this->title->ReadOnly);

			// comments
			$this->comments->SetDbValueDef($rsnew, $this->comments->CurrentValue, NULL, $this->comments->ReadOnly);

			// newsletter
			$this->newsletter->SetDbValueDef($rsnew, $this->newsletter->CurrentValue, NULL, $this->newsletter->ReadOnly);

			// submission_date
			$this->submission_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->submission_date->CurrentValue, 7), ew_CurrentDate(), $this->submission_date->ReadOnly);

			// Check hash value
			$bRowHasConflict = ($this->GetRowHash($rs) <> $this->HashValue);

			// Call Row Update Conflict event
			if ($bRowHasConflict)
				$bRowHasConflict = $this->Row_UpdateConflict($rsold, $rsnew);
			if ($bRowHasConflict) {
				$this->setFailureMessage($Language->Phrase("RecordChangedByOtherUser"));
				$this->UpdateConflict = "U";
				$rs->Close();
				return FALSE; // Update Failed
			}

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

	// Load row hash
	function LoadRowHash() {
		global $conn;
		$sFilter = $this->KeyFilter();

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$RsRow = $conn->Execute($sSql);
		$this->HashValue = ($RsRow && !$RsRow->EOF) ? $this->GetRowHash($RsRow) : ""; // Get hash value for record
		$RsRow->Close();
	}

	// Get Row Hash
	function GetRowHash(&$rs) {
		if (!$rs)
			return "";
		$sHash = "";
		$sHash .= ew_GetFldHash($rs->fields('competition_id')); // competition_id
		$sHash .= ew_GetFldHash($rs->fields('user_id')); // user_id
		$sHash .= ew_GetFldHash($rs->fields('title')); // title
		$sHash .= ew_GetFldHash($rs->fields('comments')); // comments
		$sHash .= ew_GetFldHash($rs->fields('newsletter')); // newsletter
		$sHash .= ew_GetFldHash($rs->fields('submission_date')); // submission_date
		return md5($sHash);
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;
		$rsnew = array();

		// competition_id
		$this->competition_id->SetDbValueDef($rsnew, $this->competition_id->CurrentValue, 0, FALSE);

		// user_id
		$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, 0, FALSE);

		// title
		$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", FALSE);

		// comments
		$this->comments->SetDbValueDef($rsnew, $this->comments->CurrentValue, NULL, FALSE);

		// newsletter
		$this->newsletter->SetDbValueDef($rsnew, $this->newsletter->CurrentValue, NULL, FALSE);

		// submission_date
		$this->submission_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->submission_date->CurrentValue, 7), ew_CurrentDate(), FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $this->id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
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
		$item->Body = "<a name=\"emf_submissions\" id=\"emf_submissions\" href=\"javascript:void(0);\" onclick=\"ew_EmailDialogShow({lnk:'emf_submissions',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fsubmissionslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$table = 'submissions';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'submissions';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['id'];

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

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'submissions';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $curUser = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
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
if (!isset($submissions_list)) $submissions_list = new csubmissions_list();

// Page init
$submissions_list->Page_Init();

// Page main
$submissions_list->Page_Main();
?>
<?php include_once "header.php" ?>
<?php if ($submissions->Export == "") { ?>
<script type="text/javascript">

// Page object
var submissions_list = new ew_Page("submissions_list");
submissions_list.PageID = "list"; // Page ID
var EW_PAGE_ID = submissions_list.PageID; // For backward compatibility

// Form object
var fsubmissionslist = new ew_Form("fsubmissionslist");

// Validate form
fsubmissionslist.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();	
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var elm, aelm;
	var rowcnt = (fobj.key_count) ? Number(fobj.key_count.value) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // rowcnt == 0 => Inline-Add
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = (fobj.key_count) ? String(i) : "";
		elm = fobj.elements["x" + infix + "_competition_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($submissions->competition_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_competition_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submissions->competition_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_user_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($submissions->user_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_user_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submissions->user_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_title"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($submissions->title->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_newsletter"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submissions->newsletter->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_submission_date"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($submissions->submission_date->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_submission_date"];
		if (elm && !ew_CheckEuroDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($submissions->submission_date->FldErrMsg()) ?>");

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
	}
	return true;
}

// Form_CustomValidate event
fsubmissionslist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsubmissionslist.ValidateRequired = true;
<?php } else { ?>
fsubmissionslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fsubmissionslistsrch = new ew_Form("fsubmissionslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$submissions_list->TotalRecs = $submissions->SelectRecordCount();
	} else {
		if ($submissions_list->Recordset = $submissions_list->LoadRecordset())
			$submissions_list->TotalRecs = $submissions_list->Recordset->RecordCount();
	}
	$submissions_list->StartRec = 1;
	if ($submissions_list->DisplayRecs <= 0 || ($submissions->Export <> "" && $submissions->ExportAll)) // Display all records
		$submissions_list->DisplayRecs = $submissions_list->TotalRecs;
	if (!($submissions->Export <> "" && $submissions->ExportAll))
		$submissions_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$submissions_list->Recordset = $submissions_list->LoadRecordset($submissions_list->StartRec-1, $submissions_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $submissions->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $submissions_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($submissions->Export == "" && $submissions->CurrentAction == "") { ?>
<form name="fsubmissionslistsrch" id="fsubmissionslistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<a href="javascript:fsubmissionslistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="fsubmissionslistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" border="0"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="fsubmissionslistsrch_SearchPanel">
<input type="hidden" name="t" value="submissions">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($submissions->getSessionBasicSearchKeyword()) ?>">
	<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $submissions_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
	<a href="submissionssrch.php"><?php echo $Language->Phrase("AdvancedSearch") ?></a>&nbsp;
</div>
<div id="xsr_2" class="ewRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($submissions->getSessionBasicSearchType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($submissions->getSessionBasicSearchType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($submissions->getSessionBasicSearchType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $submissions_list->ShowPageHeader(); ?>
<?php
$submissions_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($submissions->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($submissions->CurrentAction <> "gridadd" && $submissions->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php if (!isset($submissions_list->Pager)) $submissions_list->Pager = new cNumericPager($submissions_list->StartRec, $submissions_list->DisplayRecs, $submissions_list->TotalRecs, $submissions_list->RecRange) ?>
<?php if ($submissions_list->Pager->RecordCount > 0) { ?>
	<?php if ($submissions_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $submissions_list->PageUrl() ?>start=<?php echo $submissions_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($submissions_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $submissions_list->PageUrl() ?>start=<?php echo $submissions_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($submissions_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $submissions_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($submissions_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $submissions_list->PageUrl() ?>start=<?php echo $submissions_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($submissions_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $submissions_list->PageUrl() ?>start=<?php echo $submissions_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($submissions_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $submissions_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $submissions_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $submissions_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($submissions_list->SearchWhere == "0=101") { ?>
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
<?php if ($submissions_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $submissions_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<form name="fsubmissionslist" id="fsubmissionslist" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="submissions">
<div id="gmp_submissions" class="ewGridMiddlePanel">
<?php if ($submissions_list->TotalRecs > 0) { ?>
<table cellspacing="0" id="tbl_submissionslist" class="ewTable ewTableSeparate">
<?php echo $submissions->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$submissions_list->RenderListOptions();

// Render list options (header, left)
$submissions_list->ListOptions->Render("header", "left");
?>
<?php if ($submissions->id->Visible) { // id ?>
	<?php if ($submissions->SortUrl($submissions->id) == "") { ?>
		<td><span id="elh_submissions_id" class="submissions_id"><?php echo $submissions->id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $submissions->SortUrl($submissions->id) ?>',2);"><span id="elh_submissions_id" class="submissions_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $submissions->id->FldCaption() ?></td><td style="width: 10px;"><?php if ($submissions->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($submissions->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($submissions->competition_id->Visible) { // competition_id ?>
	<?php if ($submissions->SortUrl($submissions->competition_id) == "") { ?>
		<td><span id="elh_submissions_competition_id" class="submissions_competition_id"><?php echo $submissions->competition_id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $submissions->SortUrl($submissions->competition_id) ?>',2);"><span id="elh_submissions_competition_id" class="submissions_competition_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $submissions->competition_id->FldCaption() ?></td><td style="width: 10px;"><?php if ($submissions->competition_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($submissions->competition_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($submissions->user_id->Visible) { // user_id ?>
	<?php if ($submissions->SortUrl($submissions->user_id) == "") { ?>
		<td><span id="elh_submissions_user_id" class="submissions_user_id"><?php echo $submissions->user_id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $submissions->SortUrl($submissions->user_id) ?>',2);"><span id="elh_submissions_user_id" class="submissions_user_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $submissions->user_id->FldCaption() ?></td><td style="width: 10px;"><?php if ($submissions->user_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($submissions->user_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($submissions->title->Visible) { // title ?>
	<?php if ($submissions->SortUrl($submissions->title) == "") { ?>
		<td><span id="elh_submissions_title" class="submissions_title"><?php echo $submissions->title->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $submissions->SortUrl($submissions->title) ?>',2);"><span id="elh_submissions_title" class="submissions_title">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $submissions->title->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($submissions->title->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($submissions->title->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($submissions->comments->Visible) { // comments ?>
	<?php if ($submissions->SortUrl($submissions->comments) == "") { ?>
		<td><span id="elh_submissions_comments" class="submissions_comments"><?php echo $submissions->comments->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $submissions->SortUrl($submissions->comments) ?>',2);"><span id="elh_submissions_comments" class="submissions_comments">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $submissions->comments->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($submissions->comments->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($submissions->comments->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($submissions->newsletter->Visible) { // newsletter ?>
	<?php if ($submissions->SortUrl($submissions->newsletter) == "") { ?>
		<td><span id="elh_submissions_newsletter" class="submissions_newsletter"><?php echo $submissions->newsletter->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $submissions->SortUrl($submissions->newsletter) ?>',2);"><span id="elh_submissions_newsletter" class="submissions_newsletter">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $submissions->newsletter->FldCaption() ?></td><td style="width: 10px;"><?php if ($submissions->newsletter->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($submissions->newsletter->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($submissions->submission_date->Visible) { // submission_date ?>
	<?php if ($submissions->SortUrl($submissions->submission_date) == "") { ?>
		<td><span id="elh_submissions_submission_date" class="submissions_submission_date"><?php echo $submissions->submission_date->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $submissions->SortUrl($submissions->submission_date) ?>',2);"><span id="elh_submissions_submission_date" class="submissions_submission_date">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $submissions->submission_date->FldCaption() ?></td><td style="width: 10px;"><?php if ($submissions->submission_date->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($submissions->submission_date->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$submissions_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($submissions->ExportAll && $submissions->Export <> "") {
	$submissions_list->StopRec = $submissions_list->TotalRecs;
} else {

	// Set the last record to display
	if ($submissions_list->TotalRecs > $submissions_list->StartRec + $submissions_list->DisplayRecs - 1)
		$submissions_list->StopRec = $submissions_list->StartRec + $submissions_list->DisplayRecs - 1;
	else
		$submissions_list->StopRec = $submissions_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue("key_count") && ($submissions->CurrentAction == "gridadd" || $submissions->CurrentAction == "gridedit" || $submissions->CurrentAction == "F")) {
		$submissions_list->KeyCount = $objForm->GetValue("key_count");
		$submissions_list->StopRec = $submissions_list->KeyCount;
	}
}
$submissions_list->RecCnt = $submissions_list->StartRec - 1;
if ($submissions_list->Recordset && !$submissions_list->Recordset->EOF) {
	$submissions_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $submissions_list->StartRec > 1)
		$submissions_list->Recordset->Move($submissions_list->StartRec - 1);
} elseif (!$submissions->AllowAddDeleteRow && $submissions_list->StopRec == 0) {
	$submissions_list->StopRec = $submissions->GridAddRowCount;
}

// Initialize aggregate
$submissions->RowType = EW_ROWTYPE_AGGREGATEINIT;
$submissions->ResetAttrs();
$submissions_list->RenderRow();
$submissions_list->EditRowCnt = 0;
if ($submissions->CurrentAction == "edit")
	$submissions_list->RowIndex = 1;
while ($submissions_list->RecCnt < $submissions_list->StopRec) {
	$submissions_list->RecCnt++;
	if (intval($submissions_list->RecCnt) >= intval($submissions_list->StartRec)) {
		$submissions_list->RowCnt++;

		// Set up key count
		$submissions_list->KeyCount = $submissions_list->RowIndex;

		// Init row class and style
		$submissions->ResetAttrs();
		$submissions->CssClass = "";
		if ($submissions->CurrentAction == "gridadd") {
			$submissions_list->LoadDefaultValues(); // Load default values
		} else {
			$submissions_list->LoadRowValues($submissions_list->Recordset); // Load row values
		}
		$submissions->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($submissions->CurrentAction == "edit") {
			if ($submissions_list->CheckInlineEditKey() && $submissions_list->EditRowCnt == 0) { // Inline edit
				$submissions->RowType = EW_ROWTYPE_EDIT; // Render edit
				if (!$submissions->EventCancelled)
					$submissions_list->HashValue = $submissions_list->GetRowHash($submissions_list->Recordset); // Get hash value for record
			}
		}
		if ($submissions->CurrentAction == "edit" && $submissions->RowType == EW_ROWTYPE_EDIT && $submissions->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$submissions_list->RestoreFormValues(); // Restore form values
		}
		if ($submissions->RowType == EW_ROWTYPE_EDIT) // Edit row
			$submissions_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$submissions->RowAttrs = array_merge($submissions->RowAttrs, array('data-rowindex'=>$submissions_list->RowCnt, 'id'=>'r' . $submissions_list->RowCnt . '_submissions', 'data-rowtype'=>$submissions->RowType));

		// Render row
		$submissions_list->RenderRow();

		// Render list options
		$submissions_list->RenderListOptions();
?>
	<tr<?php echo $submissions->RowAttributes() ?>>
<?php

// Render list options (body, left)
$submissions_list->ListOptions->Render("body", "left", $submissions_list->RowCnt);
?>
	<?php if ($submissions->id->Visible) { // id ?>
		<td<?php echo $submissions->id->CellAttributes() ?>><span id="el<?php echo $submissions_list->RowCnt ?>_submissions_id" class="submissions_id">
<?php if ($submissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $submissions->id->ViewAttributes() ?>>
<?php echo $submissions->id->EditValue ?></span>
<input type="hidden" name="x<?php echo $submissions_list->RowIndex ?>_id" id="x<?php echo $submissions_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($submissions->id->CurrentValue) ?>">
<?php } ?>
<?php if ($submissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $submissions->id->ViewAttributes() ?>>
<?php echo $submissions->id->ListViewValue() ?></span>
<?php } ?>
<a name="<?php echo $submissions_list->PageObjName . "_row_" . $submissions_list->RowCnt ?>" id="<?php echo $submissions_list->PageObjName . "_row_" . $submissions_list->RowCnt ?>"></a></span></td>
	<?php } ?>
	<?php if ($submissions->competition_id->Visible) { // competition_id ?>
		<td<?php echo $submissions->competition_id->CellAttributes() ?>><span id="el<?php echo $submissions_list->RowCnt ?>_submissions_competition_id" class="submissions_competition_id">
<?php if ($submissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $submissions_list->RowIndex ?>_competition_id" id="x<?php echo $submissions_list->RowIndex ?>_competition_id" size="30" value="<?php echo $submissions->competition_id->EditValue ?>"<?php echo $submissions->competition_id->EditAttributes() ?>>
<?php } ?>
<?php if ($submissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $submissions->competition_id->ViewAttributes() ?>>
<?php echo $submissions->competition_id->ListViewValue() ?></span>
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($submissions->user_id->Visible) { // user_id ?>
		<td<?php echo $submissions->user_id->CellAttributes() ?>><span id="el<?php echo $submissions_list->RowCnt ?>_submissions_user_id" class="submissions_user_id">
<?php if ($submissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $submissions_list->RowIndex ?>_user_id" id="x<?php echo $submissions_list->RowIndex ?>_user_id" size="30" value="<?php echo $submissions->user_id->EditValue ?>"<?php echo $submissions->user_id->EditAttributes() ?>>
<?php } ?>
<?php if ($submissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $submissions->user_id->ViewAttributes() ?>>
<?php echo $submissions->user_id->ListViewValue() ?></span>
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($submissions->title->Visible) { // title ?>
		<td<?php echo $submissions->title->CellAttributes() ?>><span id="el<?php echo $submissions_list->RowCnt ?>_submissions_title" class="submissions_title">
<?php if ($submissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $submissions_list->RowIndex ?>_title" id="x<?php echo $submissions_list->RowIndex ?>_title" size="30" maxlength="128" value="<?php echo $submissions->title->EditValue ?>"<?php echo $submissions->title->EditAttributes() ?>>
<?php } ?>
<?php if ($submissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $submissions->title->ViewAttributes() ?>>
<?php echo $submissions->title->ListViewValue() ?></span>
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($submissions->comments->Visible) { // comments ?>
		<td<?php echo $submissions->comments->CellAttributes() ?>><span id="el<?php echo $submissions_list->RowCnt ?>_submissions_comments" class="submissions_comments">
<?php if ($submissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $submissions_list->RowIndex ?>_comments" id="x<?php echo $submissions_list->RowIndex ?>_comments" size="30" maxlength="128" value="<?php echo $submissions->comments->EditValue ?>"<?php echo $submissions->comments->EditAttributes() ?>>
<?php } ?>
<?php if ($submissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $submissions->comments->ViewAttributes() ?>>
<?php echo $submissions->comments->ListViewValue() ?></span>
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($submissions->newsletter->Visible) { // newsletter ?>
		<td<?php echo $submissions->newsletter->CellAttributes() ?>><span id="el<?php echo $submissions_list->RowCnt ?>_submissions_newsletter" class="submissions_newsletter">
<?php if ($submissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $submissions_list->RowIndex ?>_newsletter" id="x<?php echo $submissions_list->RowIndex ?>_newsletter" size="30" value="<?php echo $submissions->newsletter->EditValue ?>"<?php echo $submissions->newsletter->EditAttributes() ?>>
<?php } ?>
<?php if ($submissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $submissions->newsletter->ViewAttributes() ?>>
<?php echo $submissions->newsletter->ListViewValue() ?></span>
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($submissions->submission_date->Visible) { // submission_date ?>
		<td<?php echo $submissions->submission_date->CellAttributes() ?>><span id="el<?php echo $submissions_list->RowCnt ?>_submissions_submission_date" class="submissions_submission_date">
<?php if ($submissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $submissions_list->RowIndex ?>_submission_date" id="x<?php echo $submissions_list->RowIndex ?>_submission_date" value="<?php echo $submissions->submission_date->EditValue ?>"<?php echo $submissions->submission_date->EditAttributes() ?>>
<?php } ?>
<?php if ($submissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $submissions->submission_date->ViewAttributes() ?>>
<?php echo $submissions->submission_date->ListViewValue() ?></span>
<?php } ?>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$submissions_list->ListOptions->Render("body", "right", $submissions_list->RowCnt);
?>
	</tr>
<?php if ($submissions->RowType == EW_ROWTYPE_ADD || $submissions->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsubmissionslist.UpdateOpts(<?php echo $submissions_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	if ($submissions->CurrentAction <> "gridadd")
		$submissions_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($submissions->CurrentAction == "edit") { ?>
<input type="hidden" name="key_count" id="key_count" value="<?php echo $submissions_list->KeyCount ?>">
<?php } ?>
<?php if ($submissions->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($submissions_list->Recordset)
	$submissions_list->Recordset->Close();
?>
<?php if ($submissions_list->TotalRecs > 0) { ?>
<?php if ($submissions->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($submissions->CurrentAction <> "gridadd" && $submissions->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php if (!isset($submissions_list->Pager)) $submissions_list->Pager = new cNumericPager($submissions_list->StartRec, $submissions_list->DisplayRecs, $submissions_list->TotalRecs, $submissions_list->RecRange) ?>
<?php if ($submissions_list->Pager->RecordCount > 0) { ?>
	<?php if ($submissions_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $submissions_list->PageUrl() ?>start=<?php echo $submissions_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($submissions_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $submissions_list->PageUrl() ?>start=<?php echo $submissions_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($submissions_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $submissions_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($submissions_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $submissions_list->PageUrl() ?>start=<?php echo $submissions_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($submissions_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $submissions_list->PageUrl() ?>start=<?php echo $submissions_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($submissions_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $submissions_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $submissions_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $submissions_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($submissions_list->SearchWhere == "0=101") { ?>
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
<?php if ($submissions_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $submissions_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($submissions->Export == "") { ?>
<script type="text/javascript">
fsubmissionslistsrch.Init();
fsubmissionslist.Init();
</script>
<?php } ?>
<?php
$submissions_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($submissions->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$submissions_list->Page_Terminate();
?>
