<?php

/**
 * @package         Module Meetup
 * @copyright       Copyright (C) 2013 Hans Kuijpers
 * @license         GNU General Public License version 3 or later
 * This is a fork of 'NS_Meet_v2.0' for Joomla 2.5 by Natural Selection 
 * The extension 'NS_Meet_v2.0' is a fork of 'Meetup events extension for Joomla' for Joomla 1.5 by Phuong Quan
 */

// no direct access
defined('_JEXEC') or die;

// Global variables
$document = JFactory::getDocument();

// Load CSS
if($params->get('load_css', 1) == 1) {
    $document->addStylesheet('media/mod_meetup/css/default.css');
}

// Include the syndicate functions only once
require_once __DIR__.'/helper.php';

// Params
modMeetupHelper::$_key = $params->get('meetupApiKey');
modMeetupHelper::$_group_id = $params->get('meetupGroupId');

if ($params->get('showNumMembers')) { 
    $num_members = modMeetupHelper::getNumMembers(
        $params->get('numMembersFormat'), 
        $params->get('timeout')
    );
}

if ($params->get('showEvents')) { 
    $events = modMeetupHelper::getEvents( 
        $params->get('maxEvents'), 
        $params->get('eventDateFormat'), 
        $params->get('eventTimeFormat'), 
        $params->get('eventVenueFormat'), 
        $params->get('timeout'), 
        $params->get('showEventTodayAll'), 
        $params->get('eventEmptyMessage'),
        $params->get('maxDescCharacters')
    );
}

// Display template
require( JModuleHelper::getLayoutPath( 'mod_meetup' ) );
?>