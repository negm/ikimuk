<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
// Global variable for table object
$ip2nation_countries = NULL;

//
// Table class for ip2nation_countries
//
class cip2nation_countries extends cTable {
	var $country_code;
	var $iso_code_2;
	var $iso_code_3;
	var $iso_country;
	var $country_name;
	var $delivery_charge;
	var $phone_code;
	var $lat;
	var $lon;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'ip2nation_countries';
		$this->TableName = 'ip2nation_countries';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row

		// country_code
		$this->country_code = new cField('ip2nation_countries', 'ip2nation_countries', 'x_country_code', 'country_code', '`country_code`', '`country_code`', 200, -1, FALSE, '`country_code`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['country_code'] = &$this->country_code;

		// iso_code_2
		$this->iso_code_2 = new cField('ip2nation_countries', 'ip2nation_countries', 'x_iso_code_2', 'iso_code_2', '`iso_code_2`', '`iso_code_2`', 200, -1, FALSE, '`iso_code_2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['iso_code_2'] = &$this->iso_code_2;

		// iso_code_3
		$this->iso_code_3 = new cField('ip2nation_countries', 'ip2nation_countries', 'x_iso_code_3', 'iso_code_3', '`iso_code_3`', '`iso_code_3`', 200, -1, FALSE, '`iso_code_3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['iso_code_3'] = &$this->iso_code_3;

		// iso_country
		$this->iso_country = new cField('ip2nation_countries', 'ip2nation_countries', 'x_iso_country', 'iso_country', '`iso_country`', '`iso_country`', 200, -1, FALSE, '`iso_country`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['iso_country'] = &$this->iso_country;

		// country_name
		$this->country_name = new cField('ip2nation_countries', 'ip2nation_countries', 'x_country_name', 'country_name', '`country_name`', '`country_name`', 200, -1, FALSE, '`country_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['country_name'] = &$this->country_name;

		// delivery_charge
		$this->delivery_charge = new cField('ip2nation_countries', 'ip2nation_countries', 'x_delivery_charge', 'delivery_charge', '`delivery_charge`', '`delivery_charge`', 4, -1, FALSE, '`delivery_charge`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->delivery_charge->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['delivery_charge'] = &$this->delivery_charge;

		// phone_code
		$this->phone_code = new cField('ip2nation_countries', 'ip2nation_countries', 'x_phone_code', 'phone_code', '`phone_code`', '`phone_code`', 3, -1, FALSE, '`phone_code`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->phone_code->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['phone_code'] = &$this->phone_code;

		// lat
		$this->lat = new cField('ip2nation_countries', 'ip2nation_countries', 'x_lat', 'lat', '`lat`', '`lat`', 4, -1, FALSE, '`lat`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->lat->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['lat'] = &$this->lat;

		// lon
		$this->lon = new cField('ip2nation_countries', 'ip2nation_countries', 'x_lon', 'lon', '`lon`', '`lon`', 4, -1, FALSE, '`lon`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->lon->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['lon'] = &$this->lon;
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
		return "`ip2nation_countries`";
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
	var $UpdateTable = "`ip2nation_countries`";

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
			$sql .= ew_QuotedName('country_code') . '=' . ew_QuotedValue($rs['country_code'], $this->country_code->FldDataType) . ' AND ';
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
		return "`country_code` = '@country_code@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@country_code@", ew_AdjustSql($this->country_code->CurrentValue), $sKeyFilter); // Replace key value
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
			return "ip2nation_countrieslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "ip2nation_countrieslist.php";
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl("ip2nation_countriesview.php", $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return "ip2nation_countriesadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("ip2nation_countriesedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("ip2nation_countriesadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("ip2nation_countriesdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->country_code->CurrentValue)) {
			$sUrl .= "country_code=" . urlencode($this->country_code->CurrentValue);
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
			$arKeys[] = @$_GET["country_code"]; // country_code

			//return $arKeys; // do not return yet, so the values will also be checked by the following code
		}

		// check keys
		$ar = array();
		foreach ($arKeys as $key) {
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
			$this->country_code->CurrentValue = $key;
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// country_code
		// iso_code_2
		// iso_code_3
		// iso_country
		// country_name
		// delivery_charge
		// phone_code
		// lat
		// lon
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
				$Doc->ExportCaption($this->country_code);
				$Doc->ExportCaption($this->iso_code_2);
				$Doc->ExportCaption($this->iso_code_3);
				$Doc->ExportCaption($this->iso_country);
				$Doc->ExportCaption($this->country_name);
				$Doc->ExportCaption($this->delivery_charge);
				$Doc->ExportCaption($this->phone_code);
				$Doc->ExportCaption($this->lat);
				$Doc->ExportCaption($this->lon);
			} else {
				$Doc->ExportCaption($this->country_code);
				$Doc->ExportCaption($this->iso_code_2);
				$Doc->ExportCaption($this->iso_code_3);
				$Doc->ExportCaption($this->iso_country);
				$Doc->ExportCaption($this->country_name);
				$Doc->ExportCaption($this->delivery_charge);
				$Doc->ExportCaption($this->phone_code);
				$Doc->ExportCaption($this->lat);
				$Doc->ExportCaption($this->lon);
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
					$Doc->ExportField($this->country_code);
					$Doc->ExportField($this->iso_code_2);
					$Doc->ExportField($this->iso_code_3);
					$Doc->ExportField($this->iso_country);
					$Doc->ExportField($this->country_name);
					$Doc->ExportField($this->delivery_charge);
					$Doc->ExportField($this->phone_code);
					$Doc->ExportField($this->lat);
					$Doc->ExportField($this->lon);
				} else {
					$Doc->ExportField($this->country_code);
					$Doc->ExportField($this->iso_code_2);
					$Doc->ExportField($this->iso_code_3);
					$Doc->ExportField($this->iso_country);
					$Doc->ExportField($this->country_name);
					$Doc->ExportField($this->delivery_charge);
					$Doc->ExportField($this->phone_code);
					$Doc->ExportField($this->lat);
					$Doc->ExportField($this->lon);
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
