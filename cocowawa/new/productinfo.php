<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
// Global variable for table object
$product = NULL;

//
// Table class for product
//
class cproduct extends cTable {
	var $id;
	var $title;
	var $artist_id;
	var $competition_id;
	var $shop;
	var $price;
	var $desc;
	var $preorders;
	var $views;
	var $order;
	var $last_modified;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'product';
		$this->TableName = 'product';
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
		$this->id = new cField('product', 'product', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// title
		$this->title = new cField('product', 'product', 'x_title', 'title', '`title`', '`title`', 200, -1, FALSE, '`title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['title'] = &$this->title;

		// artist_id
		$this->artist_id = new cField('product', 'product', 'x_artist_id', 'artist_id', '`artist_id`', '`artist_id`', 3, -1, FALSE, '`artist_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->artist_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['artist_id'] = &$this->artist_id;

		// competition_id
		$this->competition_id = new cField('product', 'product', 'x_competition_id', 'competition_id', '`competition_id`', '`competition_id`', 3, -1, FALSE, '`competition_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->competition_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['competition_id'] = &$this->competition_id;

		// shop
		$this->shop = new cField('product', 'product', 'x_shop', 'shop', '`shop`', '`shop`', 16, -1, FALSE, '`shop`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->shop->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['shop'] = &$this->shop;

		// price
		$this->price = new cField('product', 'product', 'x_price', 'price', '`price`', '`price`', 4, -1, FALSE, '`price`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->price->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['price'] = &$this->price;

		// desc
		$this->desc = new cField('product', 'product', 'x_desc', 'desc', '`desc`', '`desc`', 200, -1, FALSE, '`desc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['desc'] = &$this->desc;

		// preorders
		$this->preorders = new cField('product', 'product', 'x_preorders', 'preorders', '`preorders`', '`preorders`', 3, -1, FALSE, '`preorders`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->preorders->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['preorders'] = &$this->preorders;

		// views
		$this->views = new cField('product', 'product', 'x_views', 'views', '`views`', '`views`', 3, -1, FALSE, '`views`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->views->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['views'] = &$this->views;

		// order
		$this->order = new cField('product', 'product', 'x_order', 'order', '`order`', '`order`', 3, -1, FALSE, '`order`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->order->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['order'] = &$this->order;

		// last_modified
		$this->last_modified = new cField('product', 'product', 'x_last_modified', 'last_modified', '`last_modified`', 'DATE_FORMAT(`last_modified`, \'%d/%m/%Y %H:%i:%s\')', 135, 7, FALSE, '`last_modified`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->last_modified->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['last_modified'] = &$this->last_modified;
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
		return "`product`";
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
	var $UpdateTable = "`product`";

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
			return "productlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "productlist.php";
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl("productview.php", $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return "productadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("productedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("productadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("productdelete.php", $this->UrlParm());
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
		$this->artist_id->setDbValue($rs->fields('artist_id'));
		$this->competition_id->setDbValue($rs->fields('competition_id'));
		$this->shop->setDbValue($rs->fields('shop'));
		$this->price->setDbValue($rs->fields('price'));
		$this->desc->setDbValue($rs->fields('desc'));
		$this->preorders->setDbValue($rs->fields('preorders'));
		$this->views->setDbValue($rs->fields('views'));
		$this->order->setDbValue($rs->fields('order'));
		$this->last_modified->setDbValue($rs->fields('last_modified'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// title
		// artist_id
		// competition_id
		// shop
		// price
		// desc
		// preorders
		// views
		// order
		// last_modified
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// artist_id
		$this->artist_id->ViewValue = $this->artist_id->CurrentValue;
		$this->artist_id->ViewCustomAttributes = "";

		// competition_id
		$this->competition_id->ViewValue = $this->competition_id->CurrentValue;
		$this->competition_id->ViewCustomAttributes = "";

		// shop
		$this->shop->ViewValue = $this->shop->CurrentValue;
		$this->shop->ViewCustomAttributes = "";

		// price
		$this->price->ViewValue = $this->price->CurrentValue;
		$this->price->ViewCustomAttributes = "";

		// desc
		$this->desc->ViewValue = $this->desc->CurrentValue;
		$this->desc->ViewCustomAttributes = "";

		// preorders
		$this->preorders->ViewValue = $this->preorders->CurrentValue;
		$this->preorders->ViewCustomAttributes = "";

		// views
		$this->views->ViewValue = $this->views->CurrentValue;
		$this->views->ViewCustomAttributes = "";

		// order
		$this->order->ViewValue = $this->order->CurrentValue;
		$this->order->ViewCustomAttributes = "";

		// last_modified
		$this->last_modified->ViewValue = $this->last_modified->CurrentValue;
		$this->last_modified->ViewValue = ew_FormatDateTime($this->last_modified->ViewValue, 7);
		$this->last_modified->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// title
		$this->title->LinkCustomAttributes = "";
		$this->title->HrefValue = "";
		$this->title->TooltipValue = "";

		// artist_id
		$this->artist_id->LinkCustomAttributes = "";
		$this->artist_id->HrefValue = "";
		$this->artist_id->TooltipValue = "";

		// competition_id
		$this->competition_id->LinkCustomAttributes = "";
		$this->competition_id->HrefValue = "";
		$this->competition_id->TooltipValue = "";

		// shop
		$this->shop->LinkCustomAttributes = "";
		$this->shop->HrefValue = "";
		$this->shop->TooltipValue = "";

		// price
		$this->price->LinkCustomAttributes = "";
		$this->price->HrefValue = "";
		$this->price->TooltipValue = "";

		// desc
		$this->desc->LinkCustomAttributes = "";
		$this->desc->HrefValue = "";
		$this->desc->TooltipValue = "";

		// preorders
		$this->preorders->LinkCustomAttributes = "";
		$this->preorders->HrefValue = "";
		$this->preorders->TooltipValue = "";

		// views
		$this->views->LinkCustomAttributes = "";
		$this->views->HrefValue = "";
		$this->views->TooltipValue = "";

		// order
		$this->order->LinkCustomAttributes = "";
		$this->order->HrefValue = "";
		$this->order->TooltipValue = "";

		// last_modified
		$this->last_modified->LinkCustomAttributes = "";
		$this->last_modified->HrefValue = "";
		$this->last_modified->TooltipValue = "";

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
				$Doc->ExportCaption($this->artist_id);
				$Doc->ExportCaption($this->competition_id);
				$Doc->ExportCaption($this->shop);
				$Doc->ExportCaption($this->price);
				$Doc->ExportCaption($this->desc);
				$Doc->ExportCaption($this->preorders);
				$Doc->ExportCaption($this->views);
				$Doc->ExportCaption($this->order);
				$Doc->ExportCaption($this->last_modified);
			} else {
				$Doc->ExportCaption($this->id);
				$Doc->ExportCaption($this->title);
				$Doc->ExportCaption($this->artist_id);
				$Doc->ExportCaption($this->competition_id);
				$Doc->ExportCaption($this->shop);
				$Doc->ExportCaption($this->price);
				$Doc->ExportCaption($this->desc);
				$Doc->ExportCaption($this->preorders);
				$Doc->ExportCaption($this->views);
				$Doc->ExportCaption($this->order);
				$Doc->ExportCaption($this->last_modified);
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
					$Doc->ExportField($this->artist_id);
					$Doc->ExportField($this->competition_id);
					$Doc->ExportField($this->shop);
					$Doc->ExportField($this->price);
					$Doc->ExportField($this->desc);
					$Doc->ExportField($this->preorders);
					$Doc->ExportField($this->views);
					$Doc->ExportField($this->order);
					$Doc->ExportField($this->last_modified);
				} else {
					$Doc->ExportField($this->id);
					$Doc->ExportField($this->title);
					$Doc->ExportField($this->artist_id);
					$Doc->ExportField($this->competition_id);
					$Doc->ExportField($this->shop);
					$Doc->ExportField($this->price);
					$Doc->ExportField($this->desc);
					$Doc->ExportField($this->preorders);
					$Doc->ExportField($this->views);
					$Doc->ExportField($this->order);
					$Doc->ExportField($this->last_modified);
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
