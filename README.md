# Sinema

Sinema is an automation tool to help recreate the Grindhouse experience of the 70s and 80s. It is built to interface with Plex and will read and store the required metadata for films, prerolls, and trailers to create a grindhouse double feature.

## Features (expanding rapidly at this point)

* Import films from Plex
* Import prerolls from Plex
* Import "subgenres" using IMDbPY
* Create double feature from films using genre/subgenre
* Manage prerolls
* Manage films

## Installation

### Requirements
* Prerolls from archive.org
   * You can download these in bulk using the cli tool [ia](https://internetarchive.readthedocs.io/en/stable/cli.html)
   * `./ia download --search 'collection:DriveInMovieAds'`
* Internet Archive metadata will be downloaded via the above command. Unfortunately the metadata is XML. I wrote [ArchiveMetaToKodiNfo](https://github.com/Syco54645/ArchiveMetaToKodiNfo) to convert the XML to Kodi style nfo. 
* To use nfo metadata in Plex you need to install the [XBMCnfoMoviesImporter](https://github.com/gboudreau/XBMCnfoMoviesImporter.bundle) plugin and enable it for the repository.
* Subgenres are not supported in Plex so to pull those I used the fantastic [IMDbPY](https://imdbpy.github.io/) and just call the small python script via PHP.
* sqlite3 - you will have to import the file [grindhouse.sql](/databases/grindhouse.sql)
* php 5.6.40 with mcrypt

## Configuring Development Environment

Follow the Installation guide above.

### Requirements
* Run `npm install` in the main directory. This will get grunt setup
* For css compilation run `grunt watch`


## Credits/Shoutouts
* drpenismd - For putting up with my excitement about this project and providing the name
* tuna - Again, for putting up with my excitement and talking exploitation cinema with me
* lnewlf11 - Helping with ideas and helping to prune down a list of 5,000+ subgenres that we wanted to keep. Oh and for watching the films with me


## Sitemap
* Home
    * Grindhouse
        * Create New - user locked
        * Upcoming
        * Past
        * Calendar - may be implementing this eventually
    * Admin
        * Import From Plex - user locked
        * Manage Films - user locked
        * Manage Prerolls - user locked
        * Manage Trailers - user locked
        * Settings - user locked
        * Login/Logout
    * Films
    * About


## Todo
* ~User System~
* ~settings to session!!!~
* ~add sass~
* live reload
* ~Move plex ip and token to vars~
* save country
* ~Edit var functionality (save settings)~
* global enable trailer and var for id setting
* global enable preroll and var for id setting
* individual enable trailer/preroll
* psa/short subject library support
* ~work out better way to call imdb api. perhaps a perpetual loading mechanism~
* clean up existing functions and remove all of the test crap
* library count on import screen.
* import count for totals after import report
* active route highlight in navbar
* make create search work with tighter genre requirements (dont use where in but rather a bunch of wehere)
* ~fix base model~
* clean up flashdata - started on this with angular libray
* some sort of branding