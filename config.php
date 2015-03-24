<?php

const UNKNOWN_ERROR       = 'Unknown error';
const WRONG_ACTION        = 'Action is wrong.';
const MISSING_ARGUMENTS   = 'Check arguments, seems not OK';
const CREATE_ERROR        = 'Create a record not successfull';
const RECORD_EXISTS       = 'Record exists';
const NOT_SAVED           = 'Not saved';
const NOT_FOUND           = 'Record not found';
const NO_RECORDS          = 'No records found';
const NO_KEYS             = 'Keys or talkens are missing';
const CURL_MISSING        = 'cUrl PHP extension is missing.
                              Please make sure it is installed';
const INVALID_SOURCE       = 'Please check name of the source in a database.
                              Credentials are not found';


defined('CLIENT_ID') ? null : define('CLIENT_ID', '***');//api key
defined('CLIENT_SECRET') ? null : define('CLIENT_SECRET', '***');//api secret
