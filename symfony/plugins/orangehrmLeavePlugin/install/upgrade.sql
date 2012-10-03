
TRUNCATE TABLE ohrm_leave_entitlement;
DELETE FROM ohrm_leave_type;

alter table `hs_hr_leavetype` add column int_id int not null auto_increment unique key;

INSERT INTO `ohrm_leave_type` (`id`, `name`, `deleted`, `operational_country_id`)
                    SELECT old_lt.`int_id`, old_lt.`leave_type_name`, 
                    IF(old_lt.`available_flag` = 1, 0, 1) , old_lt.`operational_country_id`
                    FROM `hs_hr_leavetype` old_lt;

INSERT INTO `ohrm_leave_entitlement`(emp_number, no_of_days, leave_type_id, from_date, to_date, 
                                credited_date, note, entitlement_type, `deleted`)
            SELECT q.employee_id, q.no_of_days_allotted, lt.int_id, p.leave_period_start_date, p.leave_period_end_date, 
            p.leave_period_start_date, 'record created by upgrade', 1, 0
            FROM `hs_hr_employee_leave_quota` q left join `hs_hr_leavetype` lt on lt.leave_type_id = q.leave_type_id
            left join hs_hr_leave_period p on p.leave_period_id = q.leave_period_id; 

alter table `hs_hr_leavetype` drop column int_id;
