CREATE TABLE `crm_naf_main` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `initiator` varchar(200) DEFAULT NULL,
  `userid` int(20) NOT NULL,
  `emp_code` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `post_comment` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `submitted_on` datetime NOT NULL,
  `submitted` int(1) NOT NULL,
  `pendingwithid` varchar(20) NOT NULL,
  `authorise` int(1) NOT NULL,
  `approved_date` datetime NOT NULL,
  `deleted` int(1) NOT NULL,
  `deleted_on` datetime DEFAULT NULL,
  `eventdate` date DEFAULT NULL,
  `level` int(5) NOT NULL DEFAULT '0',
  `naf_no` varchar(50) NOT NULL,
  `naf_activity_name` varchar(100) NOT NULL,
  `naf_city` int(10) NOT NULL,
  `naf_proposed_venue` varchar(100) NOT NULL,
  `naf_estimate_no_participents` int(10) NOT NULL,
  `naf_start_date` date NOT NULL,
  `naf_end_date` date NOT NULL,
  `naf_objective_rational` text NOT NULL,
  `remarks` text,
  `quarter` int(5) DEFAULT NULL,
  `mode` int(5) DEFAULT NULL,
  `proposed_activity_count` int(11) DEFAULT NULL,
  `proposed_hcp_no` int(11) DEFAULT NULL,
  `proposed_activity` text,
  `proposed_objective` text,
  `rationale_remark` text,
  `lead_event` text,
  `medical_equipments` text,
  `deviation_amount` int(11) DEFAULT NULL,
  `parent_id` varchar(60) DEFAULT NULL,
  `event_benefit_society` text,
  `budget_amount` int(11) DEFAULT NULL,
  `role_of_advisory` text,
  `doc_upload_path` varchar(150) DEFAULT NULL,
  `status` int(2) NOT NULL,
  `advance_payment` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `emp_code` (`emp_code`),
  KEY `date` (`date`),
  KEY `pendingwithid` (`pendingwithid`),
  KEY `authorise` (`authorise`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=latin1;



CREATE TABLE `crm_request_camp_letter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crm_request_main_id` int(11) DEFAULT NULL,
  `hcp_id` int(11) DEFAULT NULL,
  `nature_of_camp` varchar(100) DEFAULT NULL,
  `proposed_camp_date` date DEFAULT NULL,
  `proposed_camp_location` text,
  `proposed_camp_duration` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `crm_request_main` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `request_no` varchar(255) NOT NULL,
  `naf_no` varchar(255) NOT NULL,
  `category_id` int(20) NOT NULL,
  `requestor_id` int(20) NOT NULL,
  `requestor_empcode` int(6) NOT NULL,
  `submitted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `submitted` int(1) NOT NULL,
  `pendingwithid` varchar(20) NOT NULL,
  `authorise` int(1) NOT NULL,
  `approved_date` datetime NOT NULL,
  `level` int(5) NOT NULL DEFAULT '0',
  `e_sign_doctor` varchar(255) NOT NULL,
  `cheque_path` varchar(255) NOT NULL,
  `e_sign_cheque_date` date DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_on` datetime NOT NULL,
  `remarks` text,
  PRIMARY KEY (`id`),
  KEY `userid` (`requestor_id`),
  KEY `emp_code` (`requestor_empcode`),
  KEY `date` (`e_sign_cheque_date`),
  KEY `pendingwithid` (`pendingwithid`),
  KEY `authorise` (`authorise`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1;


CREATE TABLE `crm_request_details` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `crm_request_main_id` int(15) NOT NULL,
  `hcp_id` varchar(255) NOT NULL,
  `hcp_address` varchar(255) NOT NULL,
  `hcp_pan` varchar(255) NOT NULL,
  `hcp_qualification` varchar(255) NOT NULL,
  `hcp_associated_hospital_id` varchar(100) NOT NULL,
  `govt_type` varchar(25) NOT NULL,
  `yr_of_experience` int(11) NOT NULL DEFAULT '0',
  `role_of_hcp` varchar(20) NOT NULL,
  `honorarium_amount` int(25) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `submitted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_request_main_id` (`crm_request_main_id`),
  KEY `hcp_id` (`hcp_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;


CREATE TABLE `crm_hcp_information` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `crm_request_main_id` int(15) NOT NULL,
  `yr_of_registration` int(15) NOT NULL,
  `no_of_yr_experience_doctor` int(15) NOT NULL,
  `speciality_id` int(15) NOT NULL,
  `no_of_publication` int(15) NOT NULL,
  `part_of` tinytext NOT NULL,
  `speaker` tinytext NOT NULL,
  `part_of_peer` tinytext NOT NULL,
  `position` tinytext NOT NULL,
  `no_of_yr_experience_clinic` int(20) NOT NULL,
  `hcp_sign` varchar(255) NOT NULL,
  `hcp_sign_date` datetime NOT NULL,
  `emp_sign` varchar(255) NOT NULL,
  `emp_sign_date` datetime NOT NULL,
  `submitted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `submitted` int(1) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_request_main_id` (`crm_request_main_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;


CREATE TABLE `crm_request_details` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `crm_request_main_id` int(15) NOT NULL,
  `hcp_id` varchar(255) NOT NULL,
  `hcp_address` varchar(255) NOT NULL,
  `hcp_pan` varchar(255) NOT NULL,
  `hcp_qualification` varchar(255) NOT NULL,
  `hcp_associated_hospital_id` varchar(100) NOT NULL,
  `govt_type` varchar(25) NOT NULL,
  `yr_of_experience` int(11) NOT NULL DEFAULT '0',
  `role_of_hcp` varchar(20) NOT NULL,
  `honorarium_amount` int(25) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `submitted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_request_main_id` (`crm_request_main_id`),
  KEY `hcp_id` (`hcp_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;



CREATE TABLE `crm_naf_camp_report` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `crm_request_id` int(11) DEFAULT NULL,
  `objective` text,
  `camp_duration` date DEFAULT NULL,
  `type_of_diagnostic` text,
  `diagnostic_charges` int(11) DEFAULT NULL,
  `total_no_ind` int(11) DEFAULT NULL,
  `camp_organised` int(2) DEFAULT NULL,
  `camp_received` int(2) DEFAULT NULL,
  `remarks` text,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `crm_naf_delivery_form` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `crm_naf_main_id` int(15) NOT NULL,
  `employee_id` int(15) NOT NULL,
  `type_of_activity` int(15) NOT NULL,
  `name_of_activity` varchar(255) NOT NULL,
  `details_of_activity` varchar(255) DEFAULT NULL,
  `mode` varchar(255) NOT NULL,
  `vendor_services` int(2) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `vendor_description` varchar(255) NOT NULL,
  `submitted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_naf_main_id` (`crm_naf_main_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;


CREATE TABLE `crm_naf_delivery_form_cost_details` (
  `pid` int(15) NOT NULL AUTO_INCREMENT,
  `naf_delivery_form_id` int(15) NOT NULL,
  `actual_vendor_cost` int(20) NOT NULL,
  `naf_travel_flight_cost` int(20) NOT NULL,
  `naf_insurance_cost` int(20) NOT NULL,
  `naf_flight_cost` int(20) NOT NULL,
  `naf_travel_cab_cost` int(20) NOT NULL,
  `naf_visa_cost` int(20) NOT NULL,
  `naf_stay_hotel_cost` int(20) NOT NULL,
  `naf_audio_visual_cost` int(20) NOT NULL,
  `naf_meal_snack_cost` int(20) NOT NULL,
  `naf_banners_pamphlets_cost` int(20) NOT NULL,
  `naf_other_additonal_cost` int(20) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pid`),
  KEY `naf_delivery_form_id` (`naf_delivery_form_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;



CREATE TABLE `crm_naf_hcp_details` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `naf_main_id` int(11) NOT NULL,
  `hcp_id` int(11) NOT NULL,
  `hcp_address` varchar(255) NOT NULL,
  `hcp_pan` varchar(255) NOT NULL,
  `hcp_qualification` varchar(255) NOT NULL,
  `hcp_associated_hospital_id` varchar(100) NOT NULL,
  `govt_type` varchar(25) NOT NULL,
  `yr_of_experience` int(11) NOT NULL DEFAULT '0',
  `role_of_hcp` varchar(20) NOT NULL,
  `honorarium_amount` int(25) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `submitted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `naf_main_id` (`naf_main_id`),
  KEY `hcp_id` (`hcp_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;


CREATE TABLE `crm_naf_camp_letter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crm_naf_hcp_details_id` int(11) DEFAULT NULL,
  `nature_of_camp` varchar(100) DEFAULT NULL,
  `proposed_camp_date` date DEFAULT NULL,
  `proposed_camp_location` text,
  `estimated_cost` int(11) DEFAULT NULL,
  `diagnostic_lab` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `crm_naf_document_upload` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `crm_request_main_id` int(19) NOT NULL,
  `document_type_id` int(2) NOT NULL,
  `file_name` text NOT NULL,
  `file_path` text,
  `uploaded_on` datetime NOT NULL,
  `deleted` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_request_main_id` (`crm_request_main_id`),
  KEY `deleted` (`deleted`),
  KEY `type_document_id` (`document_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=latin1;
