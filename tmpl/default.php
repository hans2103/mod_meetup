<?php

/**
 * @package        Module Meetup
 * @copyright    Copyright (C) 2013 Hans Kuijpers
 * @license        GNU General Public License version 3 or later
 * This is a fork of 'NS_Meet_v2.0' for Joomla 2.5 by Natural Selection 
 * The extension 'NS_Meet_v2.0' is a fork of 'Meetup events extension for Joomla' for Joomla 1.5 by Phuong Quan
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if ($params->get('showEvents')) : ?>
<ul class="meetup_events">

    <?php foreach ($events['results'] as $event) :?>
    <?php
        $event_url = $event['event_url'];
        $event_name = $event['name'];
        $event_description = $event['description'];
        $event_venue = $event['venue'];
/*
        $event_starttime = JHTML::date($event['time'], JText::_('Y-m-d H:i:s'));
        $event_date = JHTML::date($event['time'], JText::_('D d M'));
        $event_time = JHTML::date($event['time'], JText::_('H:i'));
*/
        $rsvpcount_yes = $event['yes_rsvp_count'];
        $rsvpcount_spots_left = $event['rsvp_limit']-$event['yes_rsvp_count'];
        $rsvpcount_waiting = $event['waitlist_count'];
        $venue_name = $event['venue']['name'];
        $venue_address_1 = $event['venue']['address_1'];
        $venue_city = $event['venue']['city'];
        $venue_map = 'http://maps.google.com/maps?q='.str_replace(",", "%2C",str_replace(" ","+",$venue_address_1)).'%2C'.str_replace(",", "%2C",str_replace(" ","+",$venue_city));
    
    ?>
    <li class="meetup_event-item" itemscope itemtype="http://data-vocabulary.org/Event">
        <span itemprop="eventType" style="display:none;">Meetup</span>
        <div class="event-title">
            <?php if ($params->get('showEventLink') == 'title') echo '<a href="'.$event_url.'" itemprop="url" target="_blank">'; ?>
            <span itemprop="summary"><?php echo $event_name ; ?></span>
            <?php if ($params->get('showEventLink') == 'title') echo '</a>'; ?>
        </div>

        <div class="event-meta">
            <ul class="resetList">
                <li class="dateTime">
                        <time itemprop="startDate" datetime="<?php echo $event_starttime; ?>">
                            <span class="date"><?php echo $event_date; ?></span><br />
                            <span class="time"><?php echo $event_time; ?></span>
                        </time>
                </li>
                <li class="rsvp-callout-outer">
                    <a href="<?php echo $event_url; ?>" class="rsvp-callout-rsvp">
                        RSVP<span class="rsvp-callout-arrow">&rarr;</span>
                    </a>
                </li>
                <li class="rsvpCount event-count rsvp-meta-subtle-link attending-count">
                    <a href="<?php echo $event_url; ?>" class="no-underline-link">
                        <em><?php echo $rsvpcount_yes; ?></em> attending
                    </a>
                </li>
                <li class="rsvpCount event-count rsvp-meta-subtle-link">
                    <a href="<?php echo $event_url; ?>" class="no-underline-link">
                        <em><?php echo $rsvpcount_spots_left; ?></em> spots left
                    </a>
                </li>
            </ul>
        </div>

        <div class="event-content">
            <?php if ($params->get('showEventVenue') && strlen($event_venue > 0 )) : ?>
            <span itemprop="location">
                <dl class="event-where">
                    <dt class="event-venuename">
                        <a href="<?php echo $event_url; ?>"><?php echo $venue_name; ?></a>
                    </dt>
                    <dd>
                        <?php echo $venue_address_1; ?>, <?php echo $venue_city; ?>
                            (<a href="<?php echo $venue_map; ?>" target="_blank">map</a>)
                    </dd>
                </dl>
            </span>
            <?php endif; ?>

            <div class="event-desc" itemprop="description">
                <?php 
                    $limit = 175;
                    $description = preg_replace('/\s+?(\S+)?$/', '', $event_description);
                    if (strlen($description) > $limit) {
                        echo (substr($description, 0, $limit)) . " ... ";
                        echo '<a href="'. $event_url .'" target="_blank">Read on<a>';
                    } else {
                        echo $description;
                    }
                     ?>
            </div>
        </div>

    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?> 

<?php if ($params->get('showNumMembers')) : ?>
    <p class="meetup_membercount"><?php echo $num_members; ?> </p>
<?php endif; ?> 
    
