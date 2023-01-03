# v1.1.8
## 01/03/2023
1. [](#bugfix)
    * fix event blueprint
2. [](#improved)
    * shorten todays URL in calendar header

# v1.1.7
## 12/26/2022
1. [](#bugfix)
    * fix day/month name length selection

# v1.1.6
## 12/21/2022
1. [](#new)
    * french and italian translation
    * support open doors
    * support tickets
    * allow length adjustment of day and month names
    * button for ical recreation selection
2. [](#improved)
    * removed bourbon and neat
    * some minor things and reorderings
3. [](#bugfix)
    * |raw filter
    * line endings

# v1.1.5
## 07/20/2020
1. [](#new)
    * merged code from https://github.com/pikim/grav-plugin-events/pull/12
    * merged code from https://github.com/pikim/grav-plugin-events/pull/16
2. [](#improved)
    * some reordering
3. [](#bugfix)
    * fixed https://github.com/pikim/grav-plugin-events/issues/8 by adding default cases to switch statements
    * merged code from https://github.com/pikim/grav-plugin-events/pull/11

# v1.1.4
## 02/16/2020
1. [](#improved)
    * updated dependencies
    * some minor layout changes
3. [](#bugfix)
    * fixed date translation issue (https://github.com/pikim/grav-plugin-events/issues/5)
    * fixed geocoding issue (https://github.com/pikim/grav-plugin-events/issues/4)
    * fixed an issue with the calendar table height, table is now fully shown
    * fixed years in changelog

# v1.1.3
## 01/15/2020
1. [](#improved)
    * cleaned up settings, made more dates and times formatable, removed some obsolete code

# v1.1.2
## 01/14/2020
1. [](#improved)
    * merged code from https://github.com/ChrisGitIt/grav-plugin-events/commit/b1296cfd066b40b55a7fe18d831732526946b0bc and https://github.com/ChrisGitIt/grav-plugin-events/commit/63cef2dc3690229651c8cf458dcc6a2af6320357
    * merged code from https://github.com/bmrankin/grav-plugin-events/commit/ea778ebaf9dc5b81dedcbcd4fa65bc32d2adcd17
    * added message for virtual folder usage
    * merged fix from https://github.com/pikim/grav-plugin-events/pull/2
3. [](#bugfix)
    * fixed code from https://github.com/kalebheitzman/grav-plugin-events/pull/48/commits/8455f71826f74bda299486772d269468c6ed4347
    * merged fix from https://github.com/maelanleborgne/grav-plugin-events/commit/8005044f5db0b760fbf74cbff6e4e175f09d5675
    * fixed issue from https://discourse.getgrav.org/t/there-is-no-direct-support-at-github-for-events-plugin/10733
    * merged fix from https://github.com/pikim/grav-plugin-events/pull/1
    * merged fix from https://github.com/pikim/grav-plugin-events/pull/3

# v1.1.1
## 10/05/2019
1. [](#improved)
    * updated dependencies
3. [](#bugfix)
    * merged fix from https://github.com/u01jmg3/ics-parser/issues/238#issuecomment-527717558

# v1.1.0
## 08/15/2019
1. [](#new)
    * added ics file parsing
    * added German translation
    * merged some code from several pull requests and known issues
2. [](#improved)
    * removed unused elements from admin site and added some new
    * revised templates and CSS
    * updated dependencies
    * some minor changes
3. [](#bugfix)
    * fixed translation of weekdays

# v1.0.16
## 12/25/2016
1. [](#new)
	* Added the ability to add date exceptions to events
1. [](#improved)
	* More intuitive calendar navigation

# v1.0.15
## 10/02/2016

1. [](#new)
	* Added a location field with auto geo-decoded coordinates from address
	* New visual styles and templates for calendar and events
	* Calendar shows a modal when clicking on a day so the end user can see every event that day.
	* French language translation has been added
1. [](#improved)
	* Cleaned up plugin blueprint but preserved old options in comments
	* The events processor has been rewritten from the ground up to use Page and Collection objects instead of a custom tokenized array for serving pages.
	* Atoum testing framework has been added to the plugin and I'll be writing tests in the near future.

# v1.0.14
## 09/15/2016

1. [#bugfix]
	* Issue #25 - Variable not initialized throws error in for loop.

# v1.0.13
## 08/19/2016

1. [](#new)
	* Added Events sidebar with events listing
1. [](#bugfix)
	* Issue #21 - Admin form now automatically shows up
	* Fixed event template types in blueprints.
	* Fixed monthly frequency dates.
	* Fixed doubling of events.
	* Fixed repeat rules.
	* Removed uncoded show future events toggle

# v1.0.12
## 08/18/2016

1. [](#new)
	* Issue #24 - Added German translation from @aender6840

# v1.0.11
## 08/12/2016

1. [](#new)
	* [microformats2](http://microformats.org) support
	* Dates are now translated in the `event_item` template, if `events.date_format.translate` setting

# v1.0.10
## 07/04/2016

1. [](#bugfix)
	* Bumped version number

# v1.0.9
## 07/04/2016

1. [](#improved)
	* Added start and end times to calendar template
	* Added demo link that points to the start for this calendar (advance forward to see new events, etc)
	* Added a link to a github repo to see Event configuration under the user/pages Grav directory
1. [](#bugfix)
	* Issue #13 - Wrong link in read me for demo site.
	* Issue #15 - Current day in current month only (not multiple months)
	* Issue #16 - Update for Grav 1.1 fixed with 1.0.4 DateTools plugin

# v1.0.8
## 03/15/2016

1. [](#bugfix)
	* Issue #8 - Fixed unset arrays causing fatal error

# v1.0.7
## 03/14/2016

1. [](#improved)
	* Templates now reflect default Grav Antimatter Theme
1. [](#bugfix)
	* Issue #7 - Event repeating once a week not rendedered correctly
	* Issue #6 - Media now being displayed with each dynamic event

# v1.0.6
## 03/05/2016

1. [](#new)
	* Templates now display default tag and category taxonomy type as links.
1. [](#improved)
	* Default templates updated
	* Page load times have been decreased from ~250ms to ~90ms on PHP7.
1. [](#bugfix)
	* Issue #4 - Fixed repeating rule display from MTWRFSU to full Monday, Tuesday, etc in templates.
	* Fixed singular repeating display rule in templates.

# v1.0.5
## 02/29/2016

1. [](#new)
	* Added detailed code documentation via phpdoc. These can be found under the /docs folder.
1. [](#bugfix)
	* Updated changelog to work on Grav Website

# v1.0.4
## 02/28/2016

1. [](#new)
	* Refactored code into events and calendar classes
	* Added phpdoc based docs under the docs folder
1. [](#improved)
	* When generating a large number of events, page load speeds would drastically slow down. That has been improved to roughly 100ms on PHP 7 and 160ms on PHP 5.6
	* Instead of using an epoch string in the url to generate date times, we use a unique 6 digit token and reference event date information via the
	token.
1. [](#bugfix)
	* There were several repeating and frequency date issues that have now been resolved. Please update to 1.0.4 to ensure you don't run into these issues.

# v1.0.3
## 02/24/2016

1. [](#bugfix)
	* Fixed major fatal error when events don't exist

# v1.0.2
## 02/24/2016

1. [](#new)
	* Added calendar controls
1. [](#improved)
	* Updated readme documentation
1. [](#bugfix)
	* Fixed repeating issues
	* Fixed frequency issues

# v1.0.1
## 02/23/2016

1. [](#new)
	* Added calendar view with previous and next month navigation
1. [](#bugfix)
	* Issue #2 - Fixed Changelog format

# v1.0.0
## 02/22/2016

1. [](#new)
    * ChangeLog started...
