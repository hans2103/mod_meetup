<?php

/**
 * @package         Module Meetup
 * @copyright       Copyright (C) 2014 Hans Kuijpers
 * @license         GNU General Public License version 3 or later
 */

// no direct access
defined('_JEXEC') or die;

// Global variables
$document = JFactory::getDocument();

// Load CSS
if($params->get('load_css', 1) == 1)
{
    $document->addStylesheet('media/mod_meetup/css/default.css');
}

// Include the syndicate functions only once
require_once __DIR__.'/helper.php';

// Params
modMeetupHelper::$_key = $params->get('meetupApiKey');
modMeetupHelper::$_group_id = $params->get('meetupGroupId');

if ($params->get('showEvents'))
{
    $events = modMeetupHelper::getEvents($params);
}

// Display template
require( JModuleHelper::getLayoutPath( 'mod_meetup' ) );
?>