<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
// Global variable for table object
$competition = NULL;

//
// Table class for competition
//
class ccompetition extends cTable {
	var $id;
	var $title;
	var $desc;
	var $competition_header;
	var $submission_header;
	var $competition_order;
	var $start_date;
	var $end_date;
	var $submission_deadline;
	var $date_created;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'competition';
		$this->TableName = 'competition';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row

		// id
		$this->id = new cField('competition', 'competition', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// title
		$this->title = new cField('competition', 'competition', 'x_title', 'title', '`title`', '`title`', 200, -1, FALSE, '`title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['title'] = &$this->title;

		// desc
		$this->desc = new cField('competition', 'competition', 'x_desc', 'desc', '`desc`', '`desc`', 200, -1, FALSE, '`desc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['desc'] = &$this->desc;

		// competition_header
		$this->competition_header = new cField('competition', 'competition', 'x_competition_header', 'competition_header', '`competition_header`', '`competition_header`', 200, -1, FALSE, '`competition_header`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['competition_header'] = &$this->competition_header;

		// submission_header
		$this->submission_header = new cField('competition', 'competition', 'x_submission_header', 'submission_header', '`submission_header`', '`submission_header`', 200, -1, FALSE, '`submission_header`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['submission_header'] = &$this->submission_header;

		// competition_order
		$this->competition_order = new cField('competition', 'competition', 'x_competition_order', 'competition_order', '`competition_order`', '`competition_order`', 3, -1, FALSE, '`competition_order`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->competition_order->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['competition_order'] = &$this->competition_order;

		// start_date
		$this->start_date = new cField('competition', 'competition', 'x_start_date', 'start_date', '`start_date`', 'DATE_FORMAT(`start_date`, \'%d/%m/%Y %H:%i:%s\')', 135, 7, FALSE, '`start_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->start_date->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['start_date'] = &$this->start_date;

		// end_date
		$this->end_date = new cField('competition', 'competition', 'x_end_date', 'end_date', '`end_date`', 'DATE_FORMAT(`end_date`, \'%d/%m/%Y %H:%i:%s\')', 135, 7, FALSE, '`end_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->end_date->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['end_date'] = &$this->end_date;

		// submission_deadline
		$this->submission_deadline = new cField('competition', 'competition', 'x_submission_deadline', 'submission_deadline', '`submission_deadline`', 'DATE_FORMAT(`submission_deadline`, \'%d/%m/%Y %H:%i:%s\')', 135, 7, FALSE, '`submission_deadline`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->submission_deadline->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['submission_deadline'] = &$this->submission_deadline;

		// date_created
		$this->date_created = new cField('competition', 'competition', 'x_date_created', 'date_created', '`date_created`', 'DATE_FORMAT(`date_created`, \'%d/%m/%Y %H:%i:%s\')', 135, 7, FALSE, '`date_created`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->date_created->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['date_created'] = &$this->date_created;
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`competition`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		return TRUE;
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`competition`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			$sql .= ew_QuotedName('id') . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType) . ' AND ';
		}
		if (substr($sql, -5) == " AND ") $sql = substr($sql, 0, -5);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " AND " . $filter;
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "competitionlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "competitionlist.php";
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl("competitionview.php", $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return "competitionadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("competitionedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("competitionadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("competitiondelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id"]; // id

			//return $arKeys; // do not return yet, so the values will also be checked by the following code
		}

		// check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				$Doc->ExportCaption($this->id);
				$Doc->ExportCaption($this->title);
				$Doc->ExportCaption($this->desc);
				$Doc->ExportCaption($this->competition_header);
				$Doc->ExportCaption($this->submission_header);
				$Doc->ExportCaption($this->competition_order);
				$Doc->ExportCaption($this->start_date);
				$Doc->ExportCaption($this->end_date);
				$Doc->ExportCaption($this->submission_deadline);
				$Doc->ExportCaption($this->date_created);
			} else {
				$Doc->ExportCaption($this->id);
				$Doc->ExportCaption($this->title);
				$Doc->ExportCaption($this->desc);
				$Doc->ExportCaption($this->competition_header);
				$Doc->ExportCaption($this->submission_header);
				$Doc->ExportCaption($this->competition_order);
				$Doc->ExportCaption($this->start_date);
				$Doc->ExportCaption($this->end_date);
				$Doc->ExportCaption($this->submission_deadline);
				$Doc->ExportCaption($this->date_created);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					$Doc->ExportField($this->id);
					$Doc->ExportField($this->title);
					$Doc->ExportField($this->desc);
					$Doc->ExportField($this->competition_header);
					$Doc->ExportField($this->submission_header);
					$Doc->ExportField($this->competition_order);
					$Doc->ExportField($this->start_date);
					$Doc->ExportField($this->end_date);
					$Doc->ExportField($this->submission_deadline);
					$Doc->ExportField($this->date_created);
				} else {
					$Doc->ExportField($this->id);
					$Doc->ExportField($this->title);
					$Doc->ExportField($this->desc);
					$Doc->ExportField($this->competition_header);
					$Doc->ExportField($this->submission_header);
					$Doc->ExportField($this->competition_order);
					$Doc->ExportField($this->start_date);
					$Doc->ExportField($this->end_date);
					$Doc->ExportField($this->submission_deadline);
					$Doc->ExportField($this->date_created);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
