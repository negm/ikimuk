<?php require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (@$gsExport == "") { ?>
<?php if (@!$gbSkipHeaderFooter) { ?>
				<p>&nbsp;</p>			
			<!-- right column (end) -->
			<?php if (isset($gTimer)) $gTimer->Stop() ?>
	    </td>	
		</tr>
	</table>
	<!-- content (end) -->	
	<!-- footer (begin) --><!-- *** Note: Only licensed users are allowed to remove or change the following copyright statement. *** -->
	<div class="ewFooterRow">	
		<div class="ewFooterText">&nbsp;<?php echo $Language->ProjectPhrase("FooterText") ?></div>
		<!-- Place other links, for example, disclaimer, here -->		
	</div>
	<!-- footer (end) -->	
</div>
<?php } ?>
<div class="yui-tt" id="ewTooltipDiv" style="visibility: hidden; border: 0px;"></div>
<?php } ?>
<script type="text/javascript">
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
ew_Select("table." + EW_TABLE_CLASSNAME, document, ew_SetupTable); // Init tables
ew_Select("table." + EW_GRID_CLASSNAME, document, ew_SetupGrid); // Init grids
<?php } ?>
<?php if (@$gsExport == "") { ?>
ew_InitTooltipDiv(); // init tooltip div
<?php } ?>
</script>
<?php if (@$gsExport == "") { ?>
<script type="text/javascript">

// Write your global startup script here
// document.write("page loaded");

</script>
<?php } ?>
</body>
</html>
