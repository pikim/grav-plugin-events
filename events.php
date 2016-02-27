<?php

namespace Grav\Plugin;

// import classes
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/classes/calendar.php';
require_once __DIR__.'/classes/events.php';

use Grav\Common\Plugin;
use Grav\Common\Grav;
use Grav\Common\Page\Collection;
use Grav\Common\Page\Page;
use Grav\Common\Page\Pages;
use Grav\Common\Taxonomy;
use RocketTheme\Toolbox\Event\Event;

use Carbon\Carbon;

use Events\Calendar;
use Events\Events;

class EventsPlugin extends Plugin
{
	/**
	 * Carbon Currente Date Time
	 * @var object
	 */
	protected $now;

	/**
	 * Events Events Class
	 * @var object
	 */
	protected $events;

	/**
	 * Events Calendar Class
	 * @var object
	 */
	protected $calendar;

	/**
	 * Date Range
	 * @var array
	 */
	protected $dateRange;

	/**
	 * Get Subscribed Events
	 * @return array
	 */
	public static function getSubscribedEvents() 
	{
		return [
			'onPluginsInitialized' => ['onPluginsInitialized', 0],
		];
	}

	/**
	 * Initialize configuration
	 */
	public function onPluginsInitialized()
	{

		// Nothing else is needed for admin so close it out
		if ( $this->isAdmin() ) {
			$this->active = false;
			return;
		}

		// Add these to taxonomy for events management
		$event_taxonomies = array('type', 'event_freq', 'event_repeat');
		$taxonomy_config = array_merge((array)$this->config->get('site.taxonomies'), $event_taxonomies);
		$this->config->set('site.taxonomies', $taxonomy_config);

		// get the current datetime with c
		$this->now = Carbon::now();

		// set the calendar accessor 
		$this->calendar = new Calendar();

		// set the events accessor
		$this->events = new Events($this->grav, $this->config);

		$this->enable([
			'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
			'onPagesInitialized' => ['onPagesInitialized', 0],
			'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
		]);
	}

	/**
	 * Add current directory to twig lookup paths.
	 */ 
	public function onTwigTemplatePaths()
	{
		// add templates to twig path
		$this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
	}

	/**
	 * Check for repeating entries and add them to the page collection
	 */
	public function onPagesInitialized()
	{
		// get instances of all events
		$events = $this->events->instances();
	}

	/**
	 * Add Events blueprints to admin
	 * @return [type] [description]
	 */
	public function onBlueprintCreated()
	{
		// todo: add events event blueprint to admin
		// $this->grav['blueprints'];
	}

	/**
	 * Set needed variables to display events
	 */
	public function onTwigSiteVariables()
	{
		// setup 
		$page = $this->grav['page'];
		$collection = $page->collection();
		$twig = $this->grav['twig'];

		// only load the vars if calendar page
		if ($page->template() == 'calendar') 
		{

			$yearParam = $this->grav['uri']->param('year');
			$monthParam = $this->grav['uri']->param('month');

			$twigVars = $this->calendar->twigVars($yearParam, $monthParam);
			$calVars = $this->calendar->calendarVars($collection);

			// add calendar to twig as calendar
			$twigVars['calendar']['events'] = $calVars;
			$twig->twig_vars['calendar'] = array_shift($twigVars);

			// styles
			$css = 'plugin://events/css-compiled/events.css';
			$js = 'plugin://events/js/events.js';
			$assets = $this->grav['assets'];
			$assets->addCss($css);
			$assets->add('jquery');
			$assets->addJs($js);
		}

		if ( $page->template() == 'event' )
		{
			$dt = $this->grav['uri']->param('dt');
			$twig->twig_vars['event']['date'] = $dt;
		}

	}

}