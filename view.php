<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of myetherpad
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_myetherpad
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace myetherpad with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... myetherpad instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('myetherpad', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $myetherpad  = $DB->get_record('myetherpad', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $myetherpad  = $DB->get_record('myetherpad', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $myetherpad->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('myetherpad', $myetherpad->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_myetherpad\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $myetherpad);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/myetherpad/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($myetherpad->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('myetherpad-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();
// Conditions to show the intro can change to look for own settings or whatever.
if ($myetherpad->intro) {
    echo $OUTPUT->box(format_module_intro('myetherpad', $myetherpad, $cm->id), 'generalbox mod_introbox', 'myetherpadintro');
}
// Replace the following lines with you own code.
global $USER;
$first = $USER->firstname;
$last = $USER->lastname;
$username = $first . " " . $last ;


$resulset = $DB->get_record('myetherpad',array('id'=>$cm->instance),'padname');

if($resulset)
{
foreach($resulset as $row)
{
$pad = $row;
}
}



$path  = $DB->get_record('config_plugins',array('plugin'=>'myetherpad'),'value');
if($path)
{
foreach($path as $row)
{
$path2 = $row;
}
}

$url =  $path2 . "p/" . $pad . "?userName=" . $username;
echo '<iframe src="' . $url . '" style="width:100%;  height:480px" >';

// Finish the page.
echo $OUTPUT->footer();
