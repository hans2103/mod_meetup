<?php

/**
 * @package        Module Meetup
 * @copyright      Copyright (C) 2014 Hans Kuijpers
 * @license        GNU General Public License version 3 or later
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<div class="items-row meetup">
<?php if($events["noevent"]) : ?>
    <div class="alert alert-info"><?php echo $events["noeventtxt"]; ?></div>
<?php else : ?>
    <?php foreach ($events as $event) :  ?>
    <div style="display:none"><?php var_dump($event); ?></div>
    <div class="item row-fluid gutter-border" itemscope itemtype="http://data-vocabulary.org/Event">
        <div class="span4">
            <div>
                <span itemprop="eventType" style="display:none;">Meetup</span>
                <div class="dateTime">
                    <time itemprop="startDate" datetime="<?php echo $event->starttime; ?>">
                        <span class="date"><?php echo $event->date; ?></span>
                        <span class="time"><?php echo $event->time; ?></span>
                    </time>
                </div>
                <h3>
                    <?php if ($params->get('showEventLink') == 'title') : ?>
                        <a href="<?php echo $event->url ?>" itemprop="url" target="_blank"><span itemprop="summary"><?php echo $event->name; ?></span></a>
                    <?php else : ?>
                        <span itemprop="summary"><?php echo $event->name; ?></span>
                    <?php endif; ?>
                </h3>
            </div>
        </div>
        <div class="span4">
            <div>
                <div class="event-desc" itemprop="description">
                    <?php echo $event->description; ?>
                    <a href="<?php echo $event->url; ?>" itemprop="url" target="_blank"><?php echo JText::_('COM_CONTENT_READ_MORE_TITLE'); ?></a>
                </div>
            </div>
        </div>
        <div class="span4">
            <div>
            <?php if ($params->get('showEventVenue') && strlen($event->venue) > 0) : ?>
            <dl class="item-info" itemprop="location">
                <dt class="event-venuename">
                    <a href="<?php echo $event->url; ?>" target="_blank"><?php echo $event->venue; ?></a>
                </dt>
                <dd>
                    <?php echo $event->address; ?>, <?php echo $event->city; ?>
                    <?php if ($params->get('showEventMap')) : ?>(<a href="<?php echo $event->map; ?>" target="_blank"><?php echo JText::_('MOD_MEETUP_MAP'); ?></a>)<?php endif; ?>
                </dd>
            </dl>
            <?php endif; ?>
            <div class="event-meta ">
                <ul class="unstyled">
                    <li class="rsvpCount event-count rsvp-meta-subtle-link attending-count">
                        <a href="<?php echo $event->url; ?>" class="no-underline-link" target="_blank"><?php echo JText::sprintf('MOD_MEETUP_ATTENDING', $event->rsvpcount_yes); ?></a>
                    </li>
                    <?php if($event->rsvpcount_limit > 0) : ?>
                    <li class="rsvpCount event-count rsvp-meta-subtle-link">
                        <a href="<?php echo $event->url; ?>" class="no-underline-link" target="_blank"><?php echo JText::sprintf('MOD_MEETUP_SPOTSLEFT', $event->rsvpcount_spots_left); ?><?php if($event->rsvpcount_spots_left == 0) : ?>, add yourself to the wait list<?php endif; ?></a>
                    </li>
                    <?php endif; ?>
                    <?php if(false) : ?>
                    <?php //if($event->rsvpcount_waiting > 0) : ?>
                    <li class="rsvpCount event-count rsvp-meta-subtle-link waiting-list">
                        <a href="<?php echo $event->url; ?>" class="no-underline-link" target="_blank"><?php echo JText::sprintf('MOD_MEETUP_WAITLIST', $event->rsvpcount_waiting); ?></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="rsvp-callout-outer">
                <a href="<?php echo $event->url; ?>" class="btn btn-info rsvp-callout-rsvp" target="_blank"><?php echo JText::_('MOD_MEETUP_RSVP'); ?></a>
            </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>