<?php

/**
 * @package        Module Meetup
 * @copyright    Copyright (C) 2013 Hans Kuijpers
 * @license        GNU General Public License version 3 or later
 * This is a fork of 'NS_Meet_v2.0' for Joomla 2.5 by Natural Selection 
 * The extension 'NS_Meet_v2.0' is a fork of 'Meetup events extension for Joomla' for Joomla 1.5 by Phuong Quan
 */

// no direct access
class modMeetupHelper
{
    static $_key;
    static $_group_id;


    function getNumMembers($format, $timeout)
    {
        $url = 'http://api.meetup.com/groups.xml/?id='.self::$_group_id.'&key='.self::$_key;
        $cache_name = 'mod_meetup_groupinfo';
        $xml = self::getXml($url, $cache_name, $timeout);
        
        if ($xml->document->name() == 'oops') // if an error was returned just print the error
        { 
            $s = $xml->document->toString(); 
        }
        else
        {
            $num = $xml->document->getElementByPath( '/items/item/members' )->data();
            $s = htmlspecialchars(str_replace('{x}', $num, $format));
        }
        return $s;
    }

    function getEvents( $max_number, $time_format, $venue_format, $timeout, $todayall, $empty_message, $max_text )
    {
        $url = 'http://api.meetup.com/events.xml/?group_id='.self::$_group_id.'&key='.self::$_key;
        if( $todayall )
        { $url .= '&after='. date('mdY', time()); }
        $cache_name = 'mod_meetup_events';
        $xml = self::getXml($url, $cache_name, $timeout);
        $events = array();
        if ($xml->document->name() == 'oops') // if an error was returned just print the error
        { 
            $events[0]->name = $xml->document->toString(); 
            $events[0]->time = '';
            $events[0]->venue = '';
            $events[0]->address_1 = '';
            $events[0]->url = '';
            $events[0]->noevent = 1;
        }    
        else
        {
            $items = $xml->document->getElementByPath( '/items' );
            if( count($items->children()) == 0)
            {
                $events[0]->name = htmlspecialchars($empty_message); 
                $events[0]->time = '';
                $events[0]->venue = '';
                $events[0]->address_1 = '';
                $events[0]->city = '';
                $events[0]->url = '';
                $events[0]->noevent = 1;
            }
            else
            {
                $i = 0;
                foreach ($items->children() as $child)
                {
                    $events[$i]->name = $child->name[0]->data();
                    $events[$i]->url = $child->event_url[0]->data();
                    $events[$i]->starttime = htmlspecialchars(self::formatDateTime($child->time[0]->data(), 'Y-m-d H:i:s'));;
                    $events[$i]->date = htmlspecialchars(self::formatDateTime($child->time[0]->data(), 'D d M'));;
                    $events[$i]->time = htmlspecialchars(self::formatDateTime($child->time[0]->data(), 'H:i'));;
                    $events[$i]->rsvpcount_yes = $child->rsvpcount[0]->data();
                    $events[$i]->rsvpcount_spots_left = $child->rsvp_limit[0]->data()-$child->rsvpcount[0]->data();
                    $events[$i]->rsvpcount_waiting = $child->waiting_rsvpcount[0]->data();
                    $events[$i]->venue = $child->venue_name[0]->data();
                    $events[$i]->address_1 = $child->venue_address1[0]->data();
                    $events[$i]->city = $child->venue_city[0]->data();
                    // $child->venue_map provides the wrong URL. Therefor we create it ourselves.
                    $events[$i]->map = 'http://maps.google.com/maps?q='.str_replace(",", "%2C",str_replace(" ","+",$child->venue_address1[0]->data())).'%2C'.str_replace(",", "%2C",str_replace(" ","+",$child->venue_city[0]->data()));
                    $events[$i]->description = preg_replace('/\s+?(\S+)?$/', '', substr(strip_tags($child->description[0]->data()), 0, $max_text)).'... ';
                    if ($max_number > 0 && $i == ($max_number - 1))
                    { break; }
                    $i++;
                }
            }
        }
        return $events;
    }

    function getXml( $url, $cache_name, $timeout )
    {
        $cache =& JFactory::getCache($cache_name);
        $cache->setCaching( 1 ); //turn on caching even if global value set to false
        $cache->setLifeTime(15*60);
        $xmlstring = $cache->call(array('modMeetupHelper', 'getXmlResponse'), $url, $timeout);
        $xml =& JFactory::getXMLParser('simple');
        $valid = $xml->loadString($xmlstring);
        // check for errors
        if( !$valid || !is_object($xml) || !is_object($xml->document) // invalid xml
            || ( $xml->document->name() != 'results' && $xml->document->name() != 'oops' ) // unrecognised xml root node 
            ||  !is_object($xml->document->getElementByPath( '/items' )) // items node missing
            )
        { 
            $xml =& JFactory::getXMLParser('simple'); // re-initialise xmldoc
            $xml->loadString('<oops>An error occurred connecting to Meetup.  Please check the site directly.</oops>');
            error_log( 'Invalid xml returned from API: '.$xmlstring, 0);
        }
        return $xml;
    }

    function getXmlResponse( $url, $timeout )
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $xml = curl_exec($ch);
        curl_close($ch);
        return $xml;
    }

    function formatDateTime( $sdate, $format )
    {
        // remove timezone element (as it is not reliable)
        // this means we treat supplied time as local
        $newstr = substr(substr($sdate, 0, strripos($sdate, ' ')), 0, strripos(substr($sdate, 0, strripos($sdate, ' ')), ' ')).strrchr($sdate, ' ');
        $idate = strtotime($newstr);
        // if supplied string date cannot be converted to a date type then just use the full string
        if($idate == false)
        { $newstr = $sdate; }
        else
        { $newstr = date($format, $idate); }
        return $newstr;
    }

}
?>