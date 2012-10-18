DELETE FROM ohrm_advanced_report where id = 4;
INSERT INTO ohrm_advanced_report (id, name, definition) VALUES
(4, 'Leave Balance Report', '
<report>
    <settings>
        <csv>
            <include_group_header>1</include_group_header>
            <include_header>1</include_header>
        </csv>
    </settings>
<filter_fields>
	<input_field type="text" name="leaveType" label="Leave Type"></input_field>
	<input_field type="text" name="fromDate" label="From"></input_field>
        <input_field type="text" name="toDate" label="To"></input_field>
</filter_fields> 

<sub_report type="sql" name="mainTable">       
    <query>FROM hs_hr_employee ORDER BY hs_hr_employee.emp_number</query>
    <id_field>empNumber</id_field>
    <display_groups>
        <display_group name="personalDetails" type="one" display="true">
            <group_header></group_header>
            <fields>
                <field display="false">
                    <field_name>hs_hr_employee.emp_number</field_name>
                    <field_alias>empNumber</field_alias>
                    <display_name>Employee Number</display_name>
                    <width>1</width>	
                </field>                
                <field display="true">
                    <field_name>CONCAT(hs_hr_employee.emp_firstname, \' \', hs_hr_employee.emp_lastname)</field_name>
                    <field_alias>employeeName</field_alias>
                    <display_name>Employee</display_name>
                    <width>150</width>
                </field>                                                                                               
            </fields>
        </display_group>
    </display_groups> 
</sub_report>

<sub_report type="sql" name="subTable1">
                    <query>
FROM ohrm_leave_entitlement where leave_type_id = 1 
GROUP BY emp_number
ORDER BY emp_number
</query>
    <id_field>empNumber</id_field>
    <display_groups>
            <display_group name="g1" type="one" display="true">
                <group_header></group_header>
                <fields>
                    <field display="false">
                        <field_name>ohrm_leave_entitlement.emp_number</field_name>
                        <field_alias>empNumber</field_alias>
                        <display_name>Emp Number</display_name>
                        <width>1</width>
                    </field>                                
                    <field display="true">
                        <field_name>count(no_of_days)</field_name>
                        <field_alias>entitlement</field_alias>
                        <display_name>Opening Balance</display_name>
                        <width>120</width>
                    </field>                                
                </fields>
            </display_group>
    </display_groups>
    </sub_report>
<sub_report type="sql" name="subTable2">
                    <query>
FROM ohrm_leave_entitlement WHERE leave_type_id = 1 AND
from_date &lt; curdate()
GROUP BY emp_number
ORDER BY emp_number
</query>
    <id_field>empNumber</id_field>
    <display_groups>
            <display_group name="g2" type="one" display="true">
                <group_header></group_header>
                <fields>
                    <field display="false">
                        <field_name>ohrm_leave_entitlement.emp_number</field_name>
                        <field_alias>empNumber</field_alias>
                        <display_name>Emp Number</display_name>
                        <width>1</width>
                    </field>                                
                    <field display="true">
                        <field_name>count(no_of_days)</field_name>
                        <field_alias>earned</field_alias>
                        <display_name>Entitlements Earned</display_name>
                        <width>120</width>
                    </field>                                
                </fields>
            </display_group>
    </display_groups>
    </sub_report>

<sub_report type="sql" name="subTable3">
<query>
FROM ohrm_leave WHERE leave_type_id = 1 AND
leave_status = 3
GROUP BY employee_id
ORDER BY ohrm_leave.employee_id
</query>
    <id_field>empNumber</id_field>
    <display_groups>
            <display_group name="g3" type="one" display="true">
                <group_header></group_header>
                <fields>
                    <field display="false">
                        <field_name>ohrm_leave.employee_id</field_name>
                        <field_alias>empNumber</field_alias>
                        <display_name>Emp Number</display_name>
                        <width>1</width>
                    </field>                                
                    <field display="true">
                        <field_name>count(leave_length_days)</field_name>
                        <field_alias>taken</field_alias>
                        <display_name>Leave Taken</display_name>
                        <width>120</width>
                    </field>                                
                </fields>
            </display_group>
    </display_groups>
    </sub_report>

<sub_report type="sql" name="subTable4">
<query>
FROM ohrm_leave WHERE leave_type_id = 1 AND
leave_status = 3
GROUP BY employee_id
ORDER BY ohrm_leave.employee_id
</query>
    <id_field>empNumber</id_field>
    <display_groups>
            <display_group name="g4" type="one" display="true">
                <group_header></group_header>
                <fields>
                    <field display="false">
                        <field_name>ohrm_leave.employee_id</field_name>
                        <field_alias>empNumber</field_alias>
                        <display_name>Emp Number</display_name>
                        <width>1</width>
                    </field>                                
                    <field display="true">
                        <field_name>count(leave_length_days)</field_name>
                        <field_alias>scheduled</field_alias>
                        <display_name>Leave Scheduled</display_name>
                        <width>120</width>
                    </field>                                
                </fields>
            </display_group>
    </display_groups>
    </sub_report>

<sub_report type="sql" name="subTable5">
                    <query>
FROM ohrm_leave_entitlement where leave_type_id = 1 
GROUP BY emp_number
ORDER BY ohrm_leave_entitlement.emp_number
</query>
    <id_field>empNumber</id_field>
    <display_groups>
            <display_group name="g6" type="one" display="true">
                <group_header></group_header>
                <fields>
                    <field display="false">
                        <field_name>ohrm_leave_entitlement.emp_number</field_name>
                        <field_alias>empNumber</field_alias>
                        <display_name>Emp Number</display_name>
                        <width>1</width>
                    </field>                                
                    <field display="true">
                        <field_name>count(no_of_days)</field_name>
                        <field_alias>closing</field_alias>
                        <display_name>Closing Balance</display_name>
                        <width>120</width>
                    </field>                                
                </fields>
            </display_group>
    </display_groups>
    </sub_report>

    <join>             
        <join_by sub_report="mainTable" id="empNumber"></join_by>
        <join_by sub_report="subTable1" id="empNumber"></join_by>               
        <join_by sub_report="subTable2" id="empNumber"></join_by>   
        <join_by sub_report="subTable3" id="empNumber"></join_by>  
        <join_by sub_report="subTable4" id="empNumber"></join_by>  
        <join_by sub_report="subTable5" id="empNumber"></join_by> 
    </join>
    <page_limit>20</page_limit>
    <decorators>
        <decorator>
            <decorator_name>ChangeCase1</decorator_name>
            <field>leaveTypeId</field>
        </decorator>
    </decorators>            
</report>'); 