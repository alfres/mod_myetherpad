<?php
defined('MOODLE_INTERNAL') || die;
if ($ADMIN->fulltree) {
  $name = 'myetherpad_path';
    $title = 'etherpad site path';
    $description = 'define path of your etherpad site (the final "/" is needded)';
    $setting = new admin_setting_configtextarea($name, $title, $description, 'https://etherpad.net/');
    $settings->add($setting);
 }
