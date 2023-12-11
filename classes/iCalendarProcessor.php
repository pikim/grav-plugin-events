<?php

namespace Events;

require_once __DIR__.'/../vendor/autoload.php';

use Carbon\Carbon;
use ICal\ICal;

/**
 * Events Plugin iCalendar Class
 *
 * The Events iCalendar Class provides variables and functions to read one or
 * more ics file(s) and creates a page for each event found. The created
 * events are parsed by the plugin in the usual way.
 *
 * Based on the already existing calendarProcessor.php by Kaleb Heitzman.
 *
 * @package    Events
 * @author     Michael <pikim@web.de>
 * @copyright  2019 Michael
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    1.1.0
 * @link       https://github.com/pikim/grav-plugin-events
 * @since      1.1.0 Initial Release
 */
class iCalendarProcessor
{
    /**
     * @var  plugin config
     * @since  1.1.0  Initial Release
     */
    protected $config;

    /**
     * @var  Grav locator
     * @since  1.1.0  Initial Release
     */
    protected $loc;

    /**
     * iCalendar Class Construct
     *
     * Setup a pointer to plugin config and Grav locator.
     *
     * @since  1.1.0  Initial Release
     * @return void
     */
    public function __construct()
    {
        // get a grav instance
        $grav = \Grav\Common\Grav::instance();

        $this->config = $grav['config']->get('plugins.events');
        $this->loc = $grav['locator']->base;
    }

    /**
     * Process iCalendar file(s)
     *
     * Parses the given ics file(s), sorts them and creates the output folder(s)
     * with the parsed event(s).
     *
     * @param[in] mode, 0 to add new events only;
     *                  1 to recreate all events after
     *                  deleting the whole folder first
     *
     * @since  1.1.0  Initial Release
     * @return void
     */
    public function process( $mode )
    {
        // if path to icalendar folder isn't set, set it to a default
        if ( ! key_exists('icalendar_folder', $this->config) ) {
            $this->config['icalendar_folder'] = "/ical";
        }

        // generate path
        $ical_path = $this->loc . '/user/pages' . $this->config['icalendar_folder'];

        // eventually clear/delete the folder first
        if ( is_dir($ical_path) && $mode != 0 ) {
            $this->rmdir_recursive($ical_path);
        }

        // recreate desired folder
        if ( ! is_dir($ical_path) ) {
            mkdir($ical_path, 0755, true);
        }

        // if icalendars isn't set, set it to a default
        if ( ! key_exists('icalendars', $this->config) ) {
            $this->config['icalendars'] = "";
        }

        // if icalendar_recurrence isn't set, set it to a default
        if ( ! key_exists('icalendar_recurrence', $this->config) ) {
            $this->config['icalendar_recurrence'] = 0;
        }

        // get the single iCalendar file(s) as array
        $ical_files = explode("\n", $this->config['icalendars']);

        // open and parse iCalendar file(s)
        $ical = new ICal(
            $ical_files,
            array(
                'defaultSpan'                 => 2,     // Default value
//                'defaultTimeZone'             => 'Europe/Berlin',
                'defaultWeekStart'            => 'MO',  // Default value
                'disableCharacterReplacement' => false, // Default value
                'filterDaysAfter'             => null,  // Default value
                'filterDaysBefore'            => null,  // Default value
                'skipRecurrence'              => $this->config['icalendar_recurrence'],
            )
        );

        // get events sorted by date
        $events = $ical->sortEventsWithOrder($ical->events());

        // create an array to hold the filepaths
        // this helps to handle recurrences while creating the pages
        $files = array();

        // create a page from each event
        foreach ( $events as $event ) {
            $this->create_page($ical_path, $event, $files);
        }
    }

    /**
     * Delete folder(s) and file(s)
     *
     * Recursively deletes a folder with all subfolder(s) and file(s).
     *
     * @since  1.1.0  Initial Release
     * @return void
     */
    private function rmdir_recursive( $dir )
    {
        foreach ( scandir($dir) as $file ) {
            if ( '.' === $file || '..' === $file )
                continue;

            if ( is_dir("$dir/$file") ) {
                $this->rmdir_recursive("$dir/$file");
            }
            else {
                unlink("$dir/$file");
            }
        }

        rmdir($dir);
    }

    /**
     * Creates a new page for a given event.
     *
     * Parses a given event and creates the accoring folder and event.md file.
     *
     * Currently ignores rrules as the used ics-parser doesn't support them
     * correctly. Instead, it prefixes each event folder with the month and day
     * of the according event.
     *
     * @since  1.1.0  Initial Release
     * @return void
     */
    private function create_page( $ical_path, $event, &$files )
    {
        // we expect that summary is never empty
        $slug = strtolower($event->summary_array[1]);

        $description = array();
        $file_names = array();
        $location = array();
        $summary = array();

        // handle (multi-language) summary
        if ( isset($event->summary_array) ) {
            for ( $i = 0; $i < count($event->summary_array); $i += 2 ) {
                $info = $event->summary_array[$i];

                if ( $info != null && array_key_exists("LANGUAGE", $info)) {
                    $lang = $info["LANGUAGE"];
                    $file_names[$lang] = '/event.' . $lang . '.md';
                    $summary[$lang] = $event->summary_array[$i+1];
                }
                else {
                    $summary[""] = $event->summary;
                }
            }
        }

        // handle (multi-language) description
        if ( isset($event->description_array) ) {
            for ( $i = 0; $i < count($event->description_array); $i += 2 ) {
                $info = $event->description_array[$i];

                if ( $info != null && array_key_exists("LANGUAGE", $info)) {
                    $lang = $info["LANGUAGE"];
                    $file_names[$lang] = '/event.' . $lang . '.md';
                    $description[$lang] = $event->description_array[$i+1];
                }
                else {
                    $description[""] = $event->description;
                }
            }
        }

        // handle (multi-language) location
        if ( isset($event->location_array) ) {
            for ( $i = 0; $i < count($event->location_array); $i += 2 ) {
                $info = $event->location_array[$i];

                if ( $info != null && array_key_exists("LANGUAGE", $info)) {
                    $lang = $info["LANGUAGE"];
                    $file_names[$lang] = '/event.' . $lang . '.md';
                    $location[$lang] = $event->location_array[$i+1];
                }
                else {
                    $location[""] = $event->location;
                }
            }
        }

        // eventually set default file_name
        if ( count($file_names) == 0 ) {
            $file_names[""] = '/event.md';
        }

        // get the event information
        $uid = $event->uid;

        // get timezone of start date/time
        $tz = null; // add failure tolerance
        if ( isset($event->dtstart_array[0]["TZID"]) ) {
            $tz = $event->dtstart_array[0]["TZID"];
        }
/*        // more sophisticated than simple $tz = null;
        elseif ( isset($event->dtstart_array[0]["VALUE"]) ) {
            if ( $event->dtstart_array[0]["VALUE"] == "DATE" ) {
                $tz = null;
            }
        }*/

        // get dates/times and eventually correct the timezone
        if ( substr($event->last_modified, -1) == "Z" ) {
            $last_modified = Carbon::parse($event->last_modified, "UTC");
        }
        else {
            $last_modified = Carbon::parse($event->last_modified, $tz);
        }

        if ( substr($event->dtstart, -1) == "Z" ) {
            $start = Carbon::parse($event->dtstart, "UTC");
        }
        else {
            $start = Carbon::parse($event->dtstart, $tz);
        }

        if ( substr($event->dtend, -1) == "Z" ) {
            $end = Carbon::parse($event->dtend, "UTC");
        }
        else {
            $end = Carbon::parse($event->dtend, $tz);
        }

        if ( isset($event->recurrence_id) ) {
            $recurrence_id = Carbon::parse($event->recurrence_id);
        }

        // split if element exists
        $categories = array();
        if ( isset($event->categories) ) {
            $categories = explode(',', $event->categories);
        }

        // split if element exists
        $rrule = array();
        if ( isset($event->rrule) ) {
            $rrule = explode(';', $event->rrule);
        }

        // create path to destination folder
        $year = $start->format('Y');
        $moda = $start->format('md'); // recurrences don't work atm, prefix the path with month & day

        // remove special characters from slug
        $search = array(" ", "&amp;", "ä", "ö", "ü", "ß");
        $replace = array("-", "-", "ae", "oe", "ue", "ss");
        $slug = str_replace($search, $replace, $slug);
        $slug = preg_replace('/[^\da-z\-]/i', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = rtrim(ltrim($slug, '-'), '-');

        // create path
//        $path = $ical_path . '/' . $year . '/' . $slug;
        $path = $ical_path . '/' . $year . '/' . $moda . '_' . $slug;

        // create desired folders
        if ( ! is_dir($path) ) {
            mkdir($path, 0755, true);
        }

        foreach ($file_names as $lang => $file_name) {
            // append file name to path
            $file = $path . $file_name;

            // if a file with this name already exists
            if ( is_file($file) ) {
                $file_time = filemtime($file);

                // get uid of existing file
                $lines = file($file);
                $file_uid = $lines[1];
                $file_uid = str_replace("uid: '", "", $file_uid);
                $file_uid = rtrim($file_uid, "'".PHP_EOL);

                if ( $file_time === $last_modified && $file_uid === $uid ) {
                    // leave if file exists and hasn't changed
                    return;
                }

                // handle events with the same slug => two events have the same title
//                if ( $file_uid !== $uid ) {
                    // create token and append it to the slug
                    $token = substr(md5($uid . $start->format('d-m-Y H:i')), 0, 6);
                    $path = str_replace($slug, $slug . '-' . $token, $path);

                    // create folder and new filename
                    if ( ! is_dir($path) ) {
                        mkdir($path, 0755, true);
                    }

                    $file = $path . $file_name;
//                }
            }

            // append file to the list of files
            $files[$start . '__' . $uid] = $file;

            // handle recurrences
//            if ( isset($recurrence_id) ) {
//            }

            // write new page:
            // https://discourse.getgrav.org/t/creating-pages-dynamically-from-plugin/20223/3

            // prepare summary
            if ( array_key_exists($lang, $summary) ) {
                $summ = $summary[$lang];
            }
            elseif ( array_key_exists("", $summary) ) {
                $summ = $summary[""];
            }

            // double ' to make it work as title
            $title = str_replace("'", "''", $summ);

            // prepare file content
            $content  = "---".PHP_EOL;
            $content .= "uid: '{$uid}'".PHP_EOL;
            $content .= "title: '{$title}'".PHP_EOL;
//            $content .= "subtitle: '" . $start->format('d-m-Y H:i') . "'".PHP_EOL;

            if ( is_array($categories) && count($categories) > 0 ) {
                $content .= "taxonomy:".PHP_EOL;
                $content .= "    category:".PHP_EOL;
                foreach ( $categories as $category ) {
                    $content .= "        - {$category}".PHP_EOL;
                }
            }

            $content .= "event:".PHP_EOL;
            $content .= "    start: '" . $start->format('d-m-Y H:i') . "'".PHP_EOL;
            $content .= "    end: '" . $end->format('d-m-Y H:i') . "'".PHP_EOL;

            if ( $this->config['icalendar_recurrence'] && is_array($rrule) ) {
                $freq = "";
                $repeat = "";
                $until = "";

                foreach ( $rrule as $rule ) {
                    $rule = explode('=', $rule);

                    switch ( $rule[0] ) {
                        case "FREQ":
                            $freq = "    freq: " . strtolower($rule[1]) .PHP_EOL;
                            break;

                        case "BYDAY":
                            // replace iCal days with plugin days
                            $search = array("MO", "TU", "WE", "TH", "FR", "SA", "SU");
                            $replace = array("M", "T", "W", "R", "F", "S", "U");
                            $days = str_replace($search, $replace, $rule[1]);

                            // remove commas
                            $days = str_replace(',', '', $days);
                            $repeat = "    repeat: {$days}".PHP_EOL;
                            break;
/*
                        // currently unsupported iCal rrules
                        case "BYWEEKNO":
                            break;

                        case "BYMONTH":
                            break;

                        case "BYMONTHDAY":
                            break;

                        case "BYYEARDAY":
                            break;

                        case "BYSETPOS":
                            break;

                        case "COUNT":
                            break;

                        case "INTERVAL":
                            break;

                        case "WKST":
                            break;
*/
                        case "UNTIL":
                            $time = Carbon::parse($rule[1]);
                            $time->setTimezone($tz);
                            $until = "    until: '" . $time->format('d-m-Y H:i') . "'".PHP_EOL;
                            break;
                    }
                }

                $content .= $freq;
                $content .= $repeat;
                $content .= $until;
            }

            // prepare location
            if ( array_key_exists($lang, $location) ) {
                $loca = $location[$lang];
            }
            elseif ( array_key_exists("", $location) ) {
                $loca = $location[""];
            }
            else {
                $loca = null;
            }

            if ( isset($loca) ) {
                $content .= "    location: '{$loca}'".PHP_EOL;
            }

            $content .= "---".PHP_EOL;
            $content .= "".PHP_EOL;

            // prepare description
            if ( array_key_exists($lang, $description) ) {
                $desc = $description[$lang];
            }
            elseif ( array_key_exists("", $description) ) {
                $desc = $description[""];
            }
            else {
                $desc = null;
            }

            if ( isset($desc) ) {
                $content .= "{$desc}".PHP_EOL;
            }

            // write content to file
            $fp = fopen($file, 'w');
            fwrite($fp, $content);
            fclose($fp);

            // set modification time
            touch($file, $last_modified->unix());
            touch($path, $last_modified->unix());
        }
    }
}
