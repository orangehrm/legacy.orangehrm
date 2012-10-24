DELETE FROM ohrm_advanced_report where id = 6;
INSERT INTO ohrm_advanced_report (id, name, definition) VALUES
(6, 'Leave Balance Report', '
<report>
    <settings>
        <csv>
            <include_group_header>1</include_group_header>
            <include_header>1</include_header>
        </csv>
    </settings>
<filter_fields>
	<input_field type="text" name="empNumber" label="Employee Number"></input_field>
	<input_field type="text" name="fromDate" label="From"></input_field>
        <input_field type="text" name="toDate" label="To"></input_field>
</filter_fields> 

<sub_report type="sql" name="mainTable">       
    <query>FROM ohrm_leave_type WHERE deleted = 0 ORDER BY ohrm_leave_type.id</query>
    <id_field>leaveTypeId</id_field>
    <display_groups>
        <display_group name="leavetype" type="one" display="true">
            <group_header></group_header>
            <fields>
                <field display="false">
                    <field_name>ohrm_leave_type.id</field_name>
                    <field_alias>leaveTypeId</field_alias>
                    <display_name>Leave Type ID</display_name>
                    <width>1</width>	
                </field>   
                <field display="true">
                    <field_name>ohrm_leave_type.name</field_name>
                    <field_alias>leaveType</field_alias>
                    <display_name>Leave Type</display_name>
                    <width>160</width>	
                </field>s                                                                                                     
            </fields>
        </display_group>
    </display_groups> 
</sub_report>

<sub_report type="sql" name="subTable1">
                    <query>
FROM ohrm_leave_entitlement where $X{=,emp_number,empNumber}
GROUP BY leave_type_id
ORDER BY ohrm_leave_entitlement.leave_type_id
</query>
    <id_field>leaveTypeId</id_field>
    <display_groups>
            <display_group name="g1" type="one" display="true">
                <group_header></group_header>
                <fields>
                    <field display="false">
                        <field_name>ohrm_leave_entitlement.leave_type_id</field_name>
                        <field_alias>leaveTypeId</field_alias>
                        <display_name>Leave Type ID</display_name>
                        <width>1</width>
                    </field>                                
                    <field display="true">
                        <field_name>sum(no_of_days)</field_name>
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
FROM ohrm_leave_entitlement WHERE $X{=,emp_number,empNumber} AND
from_date &lt; curdate()
GROUP BY leave_type_id
ORDER BY ohrm_leave_entitlement.leave_type_id
</query>
    <id_field>leaveTypeId</id_field>
    <display_groups>
            <display_group name="g2" type="one" display="true">
                <group_header></group_header>
                <fields>
                    <field display="false">
                        <field_name>ohrm_leave_entitlement.leave_type_id</field_name>
                        <field_alias>leaveTypeId</field_alias>
                        <display_name>Leave Type ID</display_name>
                        <width>1</width>
                    </field>                                
                    <field display="true">
                        <field_name>sum(no_of_days)</field_name>
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
FROM ohrm_leave WHERE $X{=,employee_id,empNumber} AND
leave_status = 3
GROUP BY leave_type_id
ORDER BY ohrm_leave.leave_type_id
</query>
    <id_field>leaveTypeId</id_field>
    <display_groups>
            <display_group name="g3" type="one" display="true">
                <group_header></group_header>
                <fields>
                    <field display="false">
                        <field_name>ohrm_leave.leave_type_id</field_name>
                        <field_alias>leaveTypeId</field_alias>
                        <display_name>Leave Type ID</display_name>
                        <width>1</width>
                    </field>                                
                    <field display="true">
                        <field_name>sum(leave_length_days)</field_name>
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
FROM ohrm_leave WHERE $X{=,employee_id,empNumber} AND
leave_status = 3
GROUP BY leave_type_id
ORDER BY ohrm_leave.leave_type_id
</query>
    <id_field>leaveTypeId</id_field>
    <display_groups>
            <display_group name="g4" type="one" display="true">
                <group_header></group_header>
                <fields>
                    <field display="false">
                        <field_name>ohrm_leave.leave_type_id</field_name>
                        <field_alias>leaveTypeId</field_alias>
                        <display_name>Leave Type ID</display_name>
                        <width>1</width>
                    </field>                                
                    <field display="true">
                        <field_name>sum(leave_length_days)</field_name>
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
FROM ohrm_leave_entitlement where $X{=,emp_number,empNumber}
GROUP BY leave_type_id
ORDER BY ohrm_leave_entitlement.leave_type_id
</query>
    <id_field>leaveTypeId</id_field>
    <display_groups>
            <display_group name="g6" type="one" display="true">
                <group_header></group_header>
                <fields>
                    <field display="false">
                        <field_name>ohrm_leave_entitlement.leave_type_id</field_name>
                        <field_alias>leaveTypeId</field_alias>
                        <display_name>Leave Type ID</display_name>
                        <width>1</width>
                    </field>                                
                    <field display="true">
                        <field_name>sum(no_of_days)</field_name>
                        <field_alias>closing</field_alias>
                        <display_name>Closing Balance</display_name>
                        <width>120</width>
                    </field>                                
                </fields>
            </display_group>
    </display_groups>
    </sub_report>

    <join>             
        <join_by sub_report="mainTable" id="leaveTypeId"></join_by>
        <join_by sub_report="subTable1" id="leaveTypeId"></join_by>               
        <join_by sub_report="subTable2" id="leaveTypeId"></join_by>   
        <join_by sub_report="subTable3" id="leaveTypeId"></join_by>  
        <join_by sub_report="subTable4" id="leaveTypeId"></join_by>  
        <join_by sub_report="subTable5" id="leaveTypeId"></join_by> 
    </join>
    <page_limit>100</page_limit>
    <decorators>
        <decorator>
            <decorator_name>ChangeCase1</decorator_name>
            <field>leaveTypeId</field>
        </decorator>
    </decorators>            
</report>'); 