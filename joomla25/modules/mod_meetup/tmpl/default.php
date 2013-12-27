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

<div class="items-row">
<?php foreach ($events as $event) :  ?>
    <div class="item" itemscope itemtype="http://data-vocabulary.org/Event">
        <span itemprop="eventType" style="display:none;">Meetup</span>
        <div class="item-header">
            <h3>
                <?php if ($params->get('showEventLink') == 'title') : ?>
                    <a href="<?php echo $event->url ?>" itemprop="url" target="_blank"><span itemprop="summary"><?php echo $event->name; ?></span></a>
                <?php else : ?>
                    <span itemprop="summary"><?php echo $event->name; ?></span>
                <?php endif; ?>
            </h3>
        </div>
 
        <div class="item-content row-fluid">
            <div class="span9">
                <?php if ($params->get('showEventVenue') && strlen($event->venue) > 0 ) : ?>
                <dl class="item-info" itemprop="location">
                    <dt class="event-venuename">
                        <a href="<?php echo $event->url; ?>"><?php echo $event->venue; ?></a>
                    </dt>
                    <dd>
                        <?php echo $event->address_1; ?>, <?php echo $event->city; ?>
                        <?php if ($params->get('showEventMap')) : ?>(<a href="<?php echo $event->map; ?>" target="_blank"><?php echo JText::_('MOD_MEETUP_MAP'); ?></a>)<?php endif; ?>
                    </dd>
                </dl>
                <?php endif; ?>
                <div class="event-desc" itemprop="description">
                    <?php echo $event->description; ?>
                    <a href="<?php echo $event->url; ?>" itemprop="url" target="_blank"><?php echo JText::_('COM_CONTENT_READ_MORE_TITLE'); ?></a>
                </div>
            </div>

            <div class="event-meta well span3">
                <ul class="unstyled">
                    <li class="dateTime">
                        <time itemprop="startDate" datetime="<?php echo $event->starttime; ?>">
                            <span class="date"><?php echo $event->date; ?></span><br />
                            <span class="time"><?php echo $event->time; ?></span>
                        </time>
                    </li>
                    <li class="rsvp-callout-outer">
                        <a href="<?php echo $event->url; ?>" class="rsvp-callout-rsvp"><?php echo JText::_('MOD_MEETUP_RSVP'); ?></a>
                    </li>
                    <li class="rsvpCount event-count rsvp-meta-subtle-link attending-count">
                        <a href="<?php echo $event->url; ?>" class="no-underline-link"><?php echo JText::sprintf('MOD_MEETUP_ATTENDING', $event->rsvpcount_yes); ?></a>
                    </li>
                    <li class="rsvpCount event-count rsvp-meta-subtle-link">
                        <a href="<?php echo $event->url; ?>" class="no-underline-link"><?php echo JText::sprintf('MOD_MEETUP_SPOTSLEFT', $event->rsvpcount_spots_left); ?></a>
                    </li>
                </ul>
            </div>
            
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php if ($params->get('showNumMembers')) : ?>
    <p class="meetup_membercount"><?php echo $num_members; ?> </p>
<?php endif; ?> 
    
