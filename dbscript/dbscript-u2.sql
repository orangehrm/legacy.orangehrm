INSERT INTO `hs_hr_empstat`
  (`estat_code`, `estat_name`)
  VALUES ('EST000', 'Terminated')
  ON DUPLICATE KEY UPDATE `estat_name`='Terminated';

INSERT INTO `hs_hr_jobtit_empstat`
  (`jobtit_code`, `estat_code`)
  SELECT `jobtit_code`, 'EST000' FROM `hs_hr_job_title`
  ON DUPLICATE KEY UPDATE `estat_code`='EST000';

