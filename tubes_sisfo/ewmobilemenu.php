<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mmi_fixed_asset", $Language->MenuPhrase("1", "MenuText"), "fixed_assetlist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}fixed_asset'), FALSE);
$RootMenu->AddMenuItem(2, "mmi_fixed_asset_type", $Language->MenuPhrase("2", "MenuText"), "fixed_asset_typelist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}fixed_asset_type'), FALSE);
$RootMenu->AddMenuItem(3, "mmi_party", $Language->MenuPhrase("3", "MenuText"), "partylist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}party'), FALSE);
$RootMenu->AddMenuItem(4, "mmi_party_skill_data", $Language->MenuPhrase("4", "MenuText"), "party_skill_datalist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}party_skill_data'), FALSE);
$RootMenu->AddMenuItem(5, "mmi_rare_type", $Language->MenuPhrase("5", "MenuText"), "rare_typelist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}rare_type'), FALSE);
$RootMenu->AddMenuItem(6, "mmi_req_type", $Language->MenuPhrase("6", "MenuText"), "req_typelist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}req_type'), FALSE);
$RootMenu->AddMenuItem(7, "mmi_user", $Language->MenuPhrase("7", "MenuText"), "userlist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}user'), FALSE);
$RootMenu->AddMenuItem(8, "mmi_we_fa_req", $Language->MenuPhrase("8", "MenuText"), "we_fa_reqlist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}we_fa_req'), FALSE);
$RootMenu->AddMenuItem(9, "mmi_we_from_work_req", $Language->MenuPhrase("9", "MenuText"), "we_from_work_reqlist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}we_from_work_req'), FALSE);
$RootMenu->AddMenuItem(10, "mmi_we_good_standard", $Language->MenuPhrase("10", "MenuText"), "we_good_standardlist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}we_good_standard'), FALSE);
$RootMenu->AddMenuItem(11, "mmi_we_party_assignment_data", $Language->MenuPhrase("11", "MenuText"), "we_party_assignment_datalist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}we_party_assignment_data'), FALSE);
$RootMenu->AddMenuItem(12, "mmi_we_type", $Language->MenuPhrase("12", "MenuText"), "we_typelist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}we_type'), FALSE);
$RootMenu->AddMenuItem(13, "mmi_work_effort", $Language->MenuPhrase("13", "MenuText"), "work_effortlist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}work_effort'), FALSE);
$RootMenu->AddMenuItem(14, "mmi_work_req_type", $Language->MenuPhrase("14", "MenuText"), "work_req_typelist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}work_req_type'), FALSE);
$RootMenu->AddMenuItem(15, "mmi_fixed_asset_assign", $Language->MenuPhrase("15", "MenuText"), "fixed_asset_assignlist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}fixed_asset_assign'), FALSE);
$RootMenu->AddMenuItem(16, "mmi_order_item", $Language->MenuPhrase("16", "MenuText"), "order_itemlist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}order_item'), FALSE);
$RootMenu->AddMenuItem(17, "mmi_party_faa", $Language->MenuPhrase("17", "MenuText"), "party_faalist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}party_faa'), FALSE);
$RootMenu->AddMenuItem(18, "mmi_party_work_req_role", $Language->MenuPhrase("18", "MenuText"), "party_work_req_rolelist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}party_work_req_role'), FALSE);
$RootMenu->AddMenuItem(19, "mmi_req_role_type", $Language->MenuPhrase("19", "MenuText"), "req_role_typelist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}req_role_type'), FALSE);
$RootMenu->AddMenuItem(20, "mmi_time_sheet_entry", $Language->MenuPhrase("20", "MenuText"), "time_sheet_entrylist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}time_sheet_entry'), FALSE);
$RootMenu->AddMenuItem(21, "mmi_we_breakdown", $Language->MenuPhrase("21", "MenuText"), "we_breakdownlist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}we_breakdown'), FALSE);
$RootMenu->AddMenuItem(22, "mmi_we_from_order_item", $Language->MenuPhrase("22", "MenuText"), "we_from_order_itemlist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}we_from_order_item'), FALSE);
$RootMenu->AddMenuItem(23, "mmi_we_rate", $Language->MenuPhrase("23", "MenuText"), "we_ratelist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}we_rate'), FALSE);
$RootMenu->AddMenuItem(24, "mmi_we_status", $Language->MenuPhrase("24", "MenuText"), "we_statuslist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}we_status'), FALSE);
$RootMenu->AddMenuItem(25, "mmi_work_req", $Language->MenuPhrase("25", "MenuText"), "work_reqlist.php", -1, "", AllowListMenu('{1F84AD9A-35E5-45ED-842B-365EF8643C81}work_req'), FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
