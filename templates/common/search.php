<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
 
$searchObj = $records['searchObj'];
    
$searchResults = $searchObj->getSearchResults();
$displayFields = $searchObj->getDisplayFields();
$sortBy = $searchObj->getSortField();
$sortOrder = $searchObj->getSortOrder();
$matchType = $searchObj->getMatchType();
$pageNo = $searchObj->getPageNo();
$itemsPerPage = $searchObj->getItemsPerPage();
$numResults = $searchObj->getNumResults();

if ($sortOrder == AbstractSearch::SORT_ASCENDING) {
    $nextSort = 'DESC';
} else {
    $nextSort = 'ASC';
}    

$themeDir = '../../themes/' . $styleSheet;
$picDir = $themeDir . '/pictures/';
$iconDir = $themeDir . '/icons/';
$locRights=$_SESSION['localRights'];

/**
 * Print a field select box based on passed search filter object.
 * If $search filter is null, prints default select box
 * 
 * @param Object $searchObj Search object
 * @param SearchFilter $searchfilter
 */
function printFilterRow($searchObj, $searchFilter = null) {

    require ROOT_PATH . '/language/default/lang_default_full.php';
    $lan = new Language();
    require($lan->getLangPath("full.php"));
    
    if (empty($searchFilter)) {
        $selectedField = null;
        $selectedFieldName = '';                        
    } else {        
        $selectedField = $searchFilter->getSearchField();
        $selectedFieldName = $selectedField->getFieldName();                
    }
    
    echo "<tr>\n";        
    echo "<td>\n";              
    echo '<select onChange="onFilterFieldChange(this);" class="filterField" name="searchField[]">' . "\n";
    echo '    <option value="-1">' . $lang_Search_Select_Field . '</option>' . "\n";
    foreach ($searchObj->getSearchFields() as $searchField) {
        $searchFieldName = $searchField->getFieldName();
        $displayTextVar = $searchField->getDisplayNameVar();
        $selected = ($selectedFieldName == $searchFieldName) ? 'selected' : '';
        echo '<option value="' . $searchFieldName . '"' . $selected . '>' . $$displayTextVar . '</option>' . "\n";               
    }
    echo "</select>\n";

    echo "<td>\n";
    echo '<select onChange="onOperatorChange(this);" class="filterOperator" name="operator[]">' . "\n";
    if (!empty($searchFilter)) {
        $operators = $searchFilter->getSearchField()->getOperators();
        $selectedOperator = $searchFilter->getOperator();

        foreach ($operators as $operator) {
            $operatorLabel = 'lang_Search_Operator_' . $operator->getType();
            $selected = ($operator == $selectedOperator) ? 'selected' : '';
            echo '<option value="' . $operator->getType() . '" ' . $selected . ' >' . $$operatorLabel . '</option>' . "\n";
        }
    }
    echo "</select>\n";
    echo "</td>\n";
    echo "<td>\n";
    
    $searchValue = empty($searchFilter) ? '' : $searchFilter->getSearchValue();
    
    /* Hide value field if operator is unary */
    if (!empty($selectedField) && ($selectedField->getFieldType() == DataField::FIELD_TYPE_SELECT) 
            && ($selectedOperator->isBinary())) {
        echo '<select class="filterValue" name="searchValue[]" >' . "\n";
        
        $selectOptions = $selectedField->getSelectOptions();
        foreach ($selectOptions as $option) {
            $value = $option->getValue();
            $name = $option->getName();
            $selected = ($value == $searchValue) ? 'selected' : '';
            echo '<option value="' . $value . '" ' . $selected . ' >' . $name . '</option>' . "\n";
        }

        echo "</select>\n";
    } else {
        
        if (empty($selectedOperator) || $selectedOperator->isBinary()) {
            $hide = '';        
        } else {
            $hide = 'style="display:none;"'; 
        }             
        echo '<input ' . $hide . ' type="text" class="filterValue" name="searchValue[]" value="' . $searchValue . '"/>' . "\n";        
    }
    echo "</td>\n";
    echo '<td><input class="button" type="button" onclick="addRow(this);" name="addRowBtn[]" value="+" />' . "\n";
    echo '    <input class="button" type="button" onclick="removeRow(this);" name="removeRowBtn[]" value="-" />' . "\n";
    echo "</td>\n";
    echo '</tr>' . "\n";       
}

/**
 * Print the operator select box based on passed search filter object.
 * If $search filter is null, prints default select box
 * 
 * @param Object $searchObj Search object 
 * @param SearchFilter $searchfilter 
 */
function printOperatorSelect($searchObj, $searchFilter = null) {

}
?>

<html>
<head>
<link href="<?php echo $themeDir;?>/css/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo $themeDir;?>/css/octopus.css" rel="stylesheet" type="text/css">
<link href="<?php echo $themeDir;?>/css/search.css" rel="stylesheet" type="text/css">
<style type="text/css">
</style>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="../../scripts/archive.js"></script>
<?php include ROOT_PATH."/lib/common/calendar.php"; ?>
<script type="text/javascript" src="../../scripts/octopus.js"></script>
<script type="text/javascript">

    var searchFields = new Object();
    var fieldTypes = new Object();
    var fieldOptions = new Object();
    
<?php
foreach ($searchObj->getSearchFields() as $searchField) {
    $searchFieldName = $searchField->getFieldName();
    $operators = $searchField->getOperators();
    $fieldType = $searchField->getFieldType();
?>  
    var optionArray = new Array();
<?php
   
    foreach ($operators as $operator) {
        $operatorLabel = 'lang_Search_Operator_' . $operator->getType();
?>
        optionArray.push(new Option('<?php echo $$operatorLabel;?>', '<?php echo $operator->getType();?>')) 
<?php        
    }
?>
    searchFields['<?php echo $searchFieldName;?>'] = optionArray;
    fieldTypes['<?php echo $searchFieldName;?>'] = '<?php echo $fieldType; ?>';
<?php    
    if ($fieldType == DataField::FIELD_TYPE_SELECT) {
        $selectOptions = $searchField->getSelectOptions();
?>
        var selectOptionArray = new Array();
<?php
        if (!empty($selectOptions)) {
            foreach ($selectOptions as $option) {
            	var_dump($option);
                $value = $option->getValue();
                $name = $option->getName();
                if (empty($name)) {
                	var_dump($name);
                    $nameVar = $option->getNameVar();
                    //$name = $$nameVar;
                }
?>
            selectOptionArray.push(new Option('<?php echo $name;?>', '<?php echo $value;?>'))
<?php                
            }
        }
?>        
        fieldOptions['<?php echo $searchFieldName;?>'] = selectOptionArray;
<?php
                
    }
}
?>

    function getFieldSelectObj() {
        var fieldSelect = document.createElement('select');
        fieldSelect.options[0] = new Option('<?php echo $lang_Search_Select_Field;?>', '-1');        
<?php
        $i = 0;
    foreach ($searchObj->getSearchFields() as $searchField) {
        $i++;
        $searchFieldName = $searchField->getFieldName();
        $displayTextVar = $searchField->getDisplayNameVar();
?>
        fieldSelect.options[<?php echo $i;?>] = new Option('<?php echo $$displayTextVar;?>', '<?php echo $searchFieldName;?>');        
<?php        
    }
?>      
        fieldSelect.name = "searchField[]";
        fieldSelect.onchange = function() {onFilterFieldChange(this)};
        fieldSelect.className = 'filterField';
        return fieldSelect;                  
    }
           
    function isUnaryOperator(Operator) {
<?php 
    foreach(SearchOperator::getAll() as $operator) {
        if (!$operator->isBinary()) {
?>
        if (Operator == '<?php echo $operator->getType();?>') {
            return true;
        }
<?php            
        }
    }
?>        
        return false;
    }
               
    function addRow(obj) {
        var row = YAHOO.util.Dom.getAncestorByTagName(obj, "tr");
        var tbl = YAHOO.util.Dom.getAncestorByTagName(row, "table");
        var newRow = tbl.insertRow(row.rowIndex+1);
        var firstCell = newRow.insertCell(0);
        var secondCell = newRow.insertCell(1);        
        var thirdCell = newRow.insertCell(2);
        var forthCell = newRow.insertCell(3);

        var fieldSelect = getFieldSelectObj();
        
        var operatorSelect = document.createElement('select');
        operatorSelect.className = "filterOperator";
        operatorSelect.name = "operator[]";
        operatorSelect.onchange = function() {onOperatorChange(this)};
        
        var valueInput = document.createElement('input');
        valueInput.type = 'text';
        valueInput.className = "filterValue";
        valueInput.name = "searchValue[]";
        
        var addBtn = document.createElement('input');
        addBtn.type = 'button';
        addBtn.className = 'button';
        addBtn.value = '+';
        addBtn.name = 'addRowBtn[]';
        addBtn.onclick = function() {addRow(this)};
        
        var remBtn = document.createElement('input');
        remBtn.type = 'button';
        remBtn.className = 'button';
        remBtn.value = '-';
        remBtn.name = 'removeRowBtn[]';
        remBtn.onclick = function() {removeRow(this)};
                        
        firstCell.appendChild(fieldSelect);
        secondCell.appendChild(operatorSelect);
        thirdCell.appendChild(valueInput);
        forthCell.appendChild(addBtn);
        forthCell.appendChild(remBtn);                                
    }

    function removeRow(obj) {
        var row = YAHOO.util.Dom.getAncestorByTagName(obj, "tr");
        var tbl = YAHOO.util.Dom.getAncestorByTagName(row, "table");
        if (tbl.rows.length > 1) {
            tbl.deleteRow(row.rowIndex);
        }        
    }
    
    function clearSearchForm() {
        var tbl = $('filterTable');
        var numRows = tbl.rows.length;
        for (var i = numRows - 1; i > 0; i--) {
            tbl.deleteRow(i);
        }

        // set filter field to default        
        var children = YAHOO.util.Dom.getElementsByClassName("filterField", 'select', tbl.rows[0]);
        children[0].selectedIndex = 0;
        
        // clear options from operator select box
        var children = YAHOO.util.Dom.getElementsByClassName("filterOperator", 'select', tbl.rows[0]);
        children[0].options.length = 0;
        
        // Reset value to text input box
    }
    
    /**
     * Function run when the selected filter changes.
     * Will update the operator drop down with options supported by this field.
     */
    function onFilterFieldChange(obj) {
        var parentRow = YAHOO.util.Dom.getAncestorByTagName(obj, "tr");
        var children = YAHOO.util.Dom.getElementsByClassName("filterOperator", 'select', parentRow);
        var selectObj = children[0]; 
        
        var valueFields = YAHOO.util.Dom.getElementsByClassName("filterValue", undefined, parentRow);        
        var valueField = valueFields[0];
        
        // Default field type
        var fieldType = 'string';
        
        selectObj.options.length = 0;
        
        var selectedIndex = obj.selectedIndex;                
        if (selectedIndex > 0) {
            var selectedField = obj.options[selectedIndex].value;
                   
            var optionArray = searchFields[selectedField];
            fieldType = fieldTypes[selectedField];

                    
            var numOptions = optionArray.length;
            for (i = 0 ; i < numOptions; i++) {
                selectObj.options[i] = new Option(optionArray[i].text, optionArray[i].value);
            }
        }
        
        onOperatorChange(selectObj); 
    }
    
    /**
     * Function run when the selected operation changes.
     */
    function onOperatorChange(selectObj) {
        
        // Get current value field
        var parentRow = YAHOO.util.Dom.getAncestorByTagName(selectObj, "tr");        
        var children = YAHOO.util.Dom.getElementsByClassName("filterField", 'select', parentRow);

        var filterSelect = children[0];
                         
        var valueFields = YAHOO.util.Dom.getElementsByClassName("filterValue", undefined, parentRow);        
        var valueField = valueFields[0];        

        // Get field type
        var fieldType = 'string';        
        var selectedIndex = filterSelect.selectedIndex;                
        if (selectedIndex > 0) {
            var selectedField = filterSelect.options[selectedIndex].value;
            fieldType = fieldTypes[selectedField];
        }

        selectedOperator = selectObj.options[selectObj.selectedIndex];
        if (isUnaryOperator(selectedOperator.value)) {
            
            // No need for value field. hide it
            valueField.style.display = 'none';            
        } else if (fieldType == '<?php echo DataField::FIELD_TYPE_SELECT;?>') {
            
            // need to create a select box
            if (valueField.type == 'select') {
                valueField.style.display = 'inline';
                newValue = valueField;
            } else {
                var newValue = document.createElement('select');
                newValue.className = "filterValue";
                newValue.name = "searchValue[]";
                valueField.parentNode.insertBefore(newValue, valueField);
                valueField.parentNode.removeChild(valueField);                
            }
            
            newValue.options.length = 0;
            valueArray = fieldOptions[selectedField];
             
            var numOptions = valueArray.length;
            for (i = 0 ; i < numOptions; i++) {
                newValue.options[i] = new Option(valueArray[i].text, valueArray[i].value);
            }
            
        } else {
            
            // need to create an input text box
            if (!(valueField.type == 'text')) {
                var newValue = document.createElement('input');
                newValue.type = 'text';
                newValue.className = "filterValue";
                newValue.name = "searchValue[]";
                var res = valueField.parentNode.insertBefore(newValue, valueField);
                valueField.parentNode.removeChild(valueField);                                
            } else {
                valueField.style.display = 'inline';
            }
        }
    }
        
    function doHandleAll() {
        var allCheck = $('allCheck');
        var resultsTable = $('resultsTable');
        var checkBoxes = YAHOO.util.Dom.getElementsByClassName('checkbox', 'input', resultsTable);
        
        var newStatus;
        if (allCheck.checked) {
            newStatus = true;
        } else {
            newStatus = false;
        }
        
        var numBoxes = checkBoxes.length;
        for (var i = 0; i < numBoxes; i++) {
            checkBoxes[i].checked = newStatus;
        }
    }
    
    function search() {
        $('searchForm').submit();    
    }
    
    function sort(field) {
    
        /* field currently sorted by */
        var currentSortField = "<?php echo $sortBy;?>";
        
        /* Opposite of current sort */
        var nextSort = "<?php echo $nextSort;?>";
        
        /* Default sort */
        var defaultSort = 'ASC';
        
        var form = $('searchForm');
        if (field == currentSortField) {
            form.sortOrder.value = nextSort;
        } else {
            form.sortBy.value = field;
            form.sortOrder.value = defaultSort;
        }

        form.submit();        
    }
    
    function chgPage(pageNo) {
        $('searchForm').pageNo.value = pageNo;
        $('searchForm').submit();
    }
    
    function nextPage() {
        chgPage(<?php echo ($pageNo + 1);?>);
    }
    
    function prevPage() {
        chgPage(<?php echo ($pageNo - 1);?>);
    }
  
<?php
    if ($searchObj->isInlineEditAllowed() && ($numResults > 0) && $locRights['edit']) {
?>  
    var inlineEditMode = false;
    
    function moutInlineEdit() {
        if(inlineEditMode) {
            $('inlineEditBtn').src='<?php echo $picDir;?>btn_save.gif';
        } else {
            $('inlineEditBtn').src='<?php echo $picDir;?>btn_edit.gif';
        }
    }

    function moverInlineEdit() {
        if(inlineEditMode) {
            $('inlineEditBtn').src='<?php echo $picDir;?>btn_save_02.gif';
        } else {
            $('inlineEditBtn').src='<?php echo $picDir;?>btn_edit_02.gif';
        }
    }
    
    function validateInlineEdit() {
        return true;    
    }
    
    function resetInline() {
    
<?php    
    foreach ($searchObj->getEditableFields() as $editableField) {
        $fieldName = $editableField->getFieldName();
?>
        var editItems = document.getElementsByName('in_<?php echo $fieldName;?>[]');
        var hiddenItems = document.getElementsByName('orig_<?php echo $fieldName;?>[]');
        var numElements = editItems.length;
        
        for (var i=0; i < numElements; i++) {
            editItems[i].value = hiddenItems[i].value;
        }

<?php                           
    }     
?>
    }
    
    function editInline() {

        if (inlineEditMode) {
            if (validateInlineEdit()) {
                $('searchForm').updateMode.value = 'inline';
                $('searchForm').submit();
            }
            return;
        }
        inlineEditMode = true;

        var resultsTable = $('resultsTable');
        var editItems = YAHOO.util.Dom.getElementsByClassName('inlineEdit', undefined, resultsTable);
        var numElements = editItems.length;
        for (var i=0; i < numElements; i++) {
            editItems[i].readOnly = false;
        }
        
        $('inlineEditBtn').src="<?php echo $picDir;?>btn_save.gif";
        $('inlineEditBtn').title="<?php echo $lang_Common_Save; ?>";
    }
<?php 
    }
?>        
    YAHOO.OrangeHRM.container.init();    
</script>
<style type="text/css">
    <!--

    .roundbox {
        margin-top: 10px;
        margin-left: 0px;
        width:90%;
    }

    .roundbox_content {
        padding:15px 15px 20px 15px;
    }
    
    #filterOptions {
        display:block;
    }
    
    #filters {
        width:100%:
        display:block;
    }
    
    #filterTable {
        width: 100%;    
    }
    
    #searchBtnBar {
        width: 90%;
        text-align: right;
        padding-right: 10px;
        margin-top: 0px;
        font-size: 11px;    
    }

    #pagingBar {
        width: 90%;
        text-align: right;
        font-size: 11px;                
    }

    #searchSummary {
        padding-right: 50px;    
    }

    #pagingLinks {

    }
       
    input, select {
        padding: 0px 0px 0px 0px;
        margin: 3px 5px 3px 5px;
    }
   
    input.radioBtn {
        margin-top: 15px;
        margin-left: 10px;
        width:auto;
        vertical-align: bottom;
        display:block;
        float:left;
    }
    
    label {
        margin: 10px 5px 5px 2px;    
        padding-right: 20px;
        padding-left: 5px;
        width:auto;
        display:block;
        float:left;
    }
    
    .button {
        width: auto;
        margin-left: 2px;
        margin-right: 2px;
    }
    
    .readOnly {
        border:none;
    }
    
    #inlineEditRow {
        padding-top: 3px;
        border-top: 1px solid #cccccc;        
    }
    -->    
</style>
</head>


<body>
<h2 class="moduleTitle" style="padding-top:10px; padding-bottom:3px;">
<?php if (isset($records['titleVar']) && (!empty($records['titleVar']))) {
    $titleVar = $records['titleVar'];
    if (isset($$titleVar)) {
        echo $$titleVar;            
    }        
}
?>
</h2>
<form id="searchForm" method="post" action="">

<input type="hidden" name="sortBy" id="sortBy" value="<?php echo $sortBy;?>"/>
<input type="hidden" name="sortOrder" id="sortOrder" value="<?php echo $sortOrder;?>"/>
<input type="hidden" name="pageNo" id="pageNo" value="<?php echo $pageNo;?>"/>
<input type="hidden" name="updateMode" id="updateMode" value=""/>

<div id="filterOptions">
    <input class="radioBtn" type="radio" name="match" value="matchAll" id="matchAll" 
        <?php echo ($matchType == 'matchAll') ? 'checked="checked"' : '';?> />
    <label for="matchAll"><?php echo $lang_Search_Match_All;?></label>   
    <input class="radioBtn" type="radio" name="match" value="matchAny" id="matchAny"
        <?php echo ($matchType == 'matchAny') ? 'checked="checked"' : '';?> />    
    <label for="matchAny"><?php echo $lang_Search_Match_Any;?></label>    
</div><br />
<div id="filters">
    <table id="filterTable">
<?php
    $filters = $searchObj->getSearchFilters();
    
    if (!empty($filters)) {
        foreach ($filters as $searchFilter) {
            printFilterRow($searchObj, $searchFilter);
        }
    } else {
        printFilterRow($searchObj);        
    } 
?>    
    </table>
</div>
<div id="searchBtnBar">
<?php
    if (empty($searchResults)) {
?>
    <span class="message"><?php echo $lang_empview_norecorddisplay;?>!</span>
<?php        
    }
?>
    <img title="<?php echo $lang_Common_Search;?>" onClick="search();" 
        onMouseOut="this.src='<?php echo $picDir;?>btn_search.gif';" 
        onMouseOver="this.src='<?php echo $picDir;?>btn_search_02.gif';" 
        src="<?php echo $picDir;?>btn_search.gif">
    <img title="<?php echo $lang_Common_Clear;?>" onClick="clearSearchForm();" 
        onMouseOut="this.src='<?php echo $picDir;?>btn_clear.gif';" 
        onMouseOver="this.src='<?php echo $picDir;?>btn_clear_02.gif';" 
        src="<?php echo $picDir;?>btn_clear.gif">            
</div>
<?php
    if ($searchObj->isBulkEditAllowed() && $numResults > 0) {
?>
<div id="bulkEditDiv">
hello Bulk edit
</div>
<?php        
    }
?>
<?php 
    if ($searchObj->getPageCount() > 1) {
?>        
<div id="pagingBar">
<?php    
    $startNum = (($pageNo - 1) * $itemsPerPage) + 1;
    $endNum = $startNum + $itemsPerPage - 1;
    $endNum = ($endNum > $numResults) ? $numResults : $endNum;
    
    $summaryStr = preg_replace(array('/#start/', '/#end/', '/#all/'), array($startNum, $endNum, $numResults), 
        $lang_Search_Results_n_to_m_of_All);    
                  
    $commonFunc = new CommonFunctions();
    $pageStr = $commonFunc->printPageLinks($numResults, $pageNo);
    $pageStr = preg_replace(array('/#first/', '/#previous/', '/#next/', '/#last/'), array($lang_empview_first, $lang_empview_previous, $lang_empview_next, $lang_empview_last), $pageStr);
?>
<span id="searchSummary" ><?php echo $summaryStr;?></span>
<span id="pagingLinks"><?php echo $pageStr;?></span>
</div>
<?php } ?>
<div class="roundbox">
    <table class="results" id="resultsTable">
        <thead>
            <tr>
            <th>
                <input type='checkbox' class='checkbox' name='allCheck' id='allCheck' value='' onClick="doHandleAll();">
            </th>
<?php    
    foreach ($searchObj->getDisplayFields() as $displayField) {
        $displayName = $displayField->getDisplayNameVar();
        $fieldName = $displayField->getFieldName();
        $sortImg = ($fieldName == $sortBy) ? $sortOrder : 'null'; 
?>
            <th class="heading"><a href="#" onclick="sort('<?php echo $fieldName;?>');"><?php echo $$displayName; ?></a>
                <img src="<?php echo $themeDir;?>/icons/<?php echo $sortImg;?>.png">
            </th>
<?php        
    }
?>                    
            </tr>
        </thead>
        <tbody>
<?php
    $rowClass = 'odd';
    foreach ($searchResults as $searchResult) {
        $idField = $searchObj->getIdField();
        $id = CommonFunctions::getObjectProperty($searchResult, $fieldName); 
?>
        <tr class="<?php echo $rowClass;?>">
            <td><input type='checkbox' class='checkbox' name='chkID[]' value='<?php echo $id;?>'/>
<?php
        if ($searchObj->isInlineEditAllowed() && ($locRights['edit'])) {
?>
                <input type="hidden" name="id" value="<?php echo $id;?>"/>
<?            
        }
?>            
            </td>
<?php
        
        foreach ($displayFields as $displayField) {                        
            $fieldName = $displayField->getFieldName();
            $fieldValue = CommonFunctions::getObjectProperty($searchResult, $fieldName);
            
            if ($searchObj->isInlineEditAllowed() && ($displayField instanceof EditableField) && ($locRights['edit'])) {   
?>   
                <td>
                    <input readonly="true" type="text" class="inlineEdit" 
                        name="in_<?php echo $fieldName;?>[]" value="<?php echo $fieldValue;?>"/>
                    <input type="hidden" class="inlineHidden" name="orig_<?php echo $fieldName;?>[]" 
                        value="<?php echo $fieldValue;?>"/>
                </td>
<?php
            } else {
?>
                <td><?php echo $fieldValue;?></td>
<?php                
            }
        }
?>                        
        </tr>
<?php
        $rowClass = ($rowClass == 'odd') ? 'even' : 'odd';        
    }
?>        
<?php
    if ($searchObj->isInlineEditAllowed() && ($numResults > 0) && $locRights['edit']) {
?>
<tr id="inlineEditRow">
  <td colspan="<?php echo (count($searchObj->getDisplayFields()) + 1);?>">
    <img onClick="editInline();" id="inlineEditBtn"
        onMouseOut="moutInlineEdit();" onMouseOver="moverInlineEdit();"
        src="<?php echo $picDir . 'btn_edit.gif';?>">
    <img id="inlineClearBtn" src="<?php echo $iconDir;?>reset.gif"
    onMouseOut="this.src='<?php echo $iconDir;?>reset.gif';"
    onMouseOver="this.src='<?php echo $iconDir;?>reset_o.gif';" onClick="resetInline();" >
</tr>
<?php        
    }
?>
        </tbody>
    </table>    
</div>
</form>

<script type="text/javascript">
<!--
    if (document.getElementById && document.createElement) {
        initOctopus();
    }
-->
</script>
<div id="cal1Container" style="position:absolute;" ></div>
</body>
</html>