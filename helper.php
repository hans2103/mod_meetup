<?php

/**
 * @package        Module Meetup
 * @copyright      Copyright (C) 2014 Hans Kuijpers
 * @license        GNU General Public License version 3 or later
 */

// no direct access
class modMeetupHelper
{
    static $_key;
    static $_group_id;

    function getEvents($params)
    {
        $url = 'https://api.meetup.com/2/events?&group_id='.self::$_group_id.'&key='.self::$_key;
        if($params->get('showEventTodayAll'))
        {
            $url .= '&after='. date('mdY', time());
        }

        $cache_name = 'mod_meetup_events';

        try 
        {   
            $content = @file_get_contents($url);
            
            if(!$content)
            {
                throw new Exception('<b>Error!</b> Error retrieving JSON file from Meetup.com');
            }
            
        }
        catch(Exception $e )
        {
            $events = array();
            $events["noevent"]                      = 1;
            $events["noeventtxt"]                   = $e->getMessage();
            return $events;
        }

        file_put_contents($cache_name, $content);

        $json = json_decode($content, true);

        $events = array();

        if($json["meta"]["count"] != 0)
        {
            $i = 0;
            foreach($json["results"] as $event)
            {
                $time       = $event["time"]/1000;
                $offset     = $event["offset"]/1000;
                $max_number = $params->get('maxEvents');
                $max_text   = $params->get('maxDescCharacters');

                $events[$i]->name                   = $event["name"];
                $events[$i]->url                    = $event["event_url"];
                $events[$i]->starttime              = date('Y-m-d H:i:s',$time + $offset);
                $events[$i]->date                   = date($params->get('eventDateFormat'),$time + $offset);
                $events[$i]->time                   = date($params->get('eventTimeFormat'),$time + $offset);
                $events[$i]->rsvpcount_yes          = $event["yes_rsvp_count"];
                $events[$i]->rsvpcount_limit        = $event["rsvp_limit"];
                $events[$i]->rsvpcount_spots_left   = $event["rsvp_limit"]-$event["yes_rsvp_count"];
                $events[$i]->rsvpcount_waiting      = $event["waitlist_count"];
                $events[$i]->venue                  = $event["venue"]["name"];
                $events[$i]->address                = $event["venue"]["address_1"];
                $events[$i]->city                   = $event["venue"]["city"];
                $events[$i]->map                    = 'http://maps.google.com/maps?q='.str_replace(",", "%2C",str_replace(" ","+",$event["venue"]["address_1"])).'%2C'.str_replace(",", "%2C",str_replace(" ","+",$event["venue"]["city"]));
                $events[$i]->description            = preg_replace('/\s+?(\S+)?$/', '', substr(strip_tags($event["description"]), 0, $max_text)).'... ';

                if ($max_number > 0 && $i == ($max_number - 1))
                {
                    break; 
                }
                $i++;
            }
        }
        else
        {
            $events["noevent"]                      = 1;
            $events["noeventtxt"]                   = $params->get('eventEmptyMessage');
        }

        return $events;
    }
}
?>
