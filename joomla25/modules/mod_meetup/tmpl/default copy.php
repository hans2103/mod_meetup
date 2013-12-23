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
    <?php if ($params->get('renderStyle') == 'list') : ?>
        <ul class="meetup_events">
    <?php endif; ?> 

    <?php foreach ($events as $event) :  ?>
        <?php if ($params->get('renderStyle') == 'list') : ?>
            <li class="meetup_event-item" itemscope itemtype="http://data-vocabulary.org/Event">
                <span itemprop="eventType" style="display:none;">Meetup</span>
        <?php elseif ($params->get('renderStyle') == 'paragraph') : ?>
            <p class="meetup_event" itemscope itemtype="http://data-vocabulary.org/Event">
        <?php endif; ?> 

                <div class="event-title">
                    <?php if ($params->get('showEventLink') == 'title') echo '<a href="'.$event->url.'" itemprop="url" target="_blank">'; ?>
                    <span itemprop="summary"><?php echo $event->name; ?></span>
                    <?php if ($params->get('showEventLink') == 'title') echo '</a>'; ?>
                </div>

                <div class="event-meta">
                    <ul class="resetList">
                        <li class="dateTime">
                                <time itemprop="startDate" datetime="<?php echo $event->starttime; ?>">
                                    <span class="date"><?php echo $event->date; ?></span><br />
                                    <span class="time"><?php echo $event->time; ?></span>
                                </time>
                        </li>
                        <li class="rsvp-callout-outer">
                            <a href="<?php echo $event->url; ?>" class="rsvp-callout-rsvp">
                                RSVP<span class="rsvp-callout-arrow">&rarr;</span>
                            </a>
                        </li>
                        <li class="rsvpCount event-count rsvp-meta-subtle-link attending-count">
                            <a href="<?php echo $event->url; ?>" class="no-underline-link">
                                <em><?php echo $event->rsvpcount_yes; ?></em> attending
                            </a>
                        </li>
                        <li class="rsvpCount event-count rsvp-meta-subtle-link">
                            <a href="<?php echo $event->url; ?>" class="no-underline-link">
                                <em><?php echo $event->rsvpcount_spots_left; ?></em> spots left
                            </a>
                        </li>
                    </ul>
                </div>
        
                <div class="event-content">
                    <?php if ($params->get('showEventVenue') && strlen($event->venue) > 0 ) : ?>
                    <span itemprop="location">
                        <dl class="event-where">
                            <dt class="event-venuename">
                                <a href="<?php echo $event->url; ?>"><?php echo $event->venue; ?></a>
                            </dt>
                            <dd>
                                <?php echo $event->address_1; ?>, <?php echo $event->city; ?>
                                    (<a href="<?php echo $event->map; ?>" target="_blank">map</a>)
                            </dd>
                        </dl>
                    </span>
                    <?php endif; ?>

                    <div class="event-desc" itemprop="description">
                        <?php echo $event->description; ?>
                        <a href="<?php echo $event->url; ?>" target="_blank">Read on<a>
                    </div>
                </div>

        <?php if ($params->get('renderStyle') == 'list') : ?>
            </li>
        <?php elseif ($params->get('renderStyle') == 'paragraph') : ?>
            </p>
        <?php endif; ?> 
    <?php endforeach; ?>
    
    <?php if ($params->get('renderStyle') == 'list') : ?>
        </ul>
    <?php endif; ?>
<?php endif; ?> 


<?php if ($params->get('showNumMembers')) : ?>
    <p class="meetup_membercount"><?php echo $num_members; ?> </p>
<?php endif; ?> 
    
