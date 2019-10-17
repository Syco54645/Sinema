## Requirements
* https://github.com/gboudreau/XBMCnfoMoviesImporter.bundle
* https://internetarchive.readthedocs.io/en/stable/cli.html
* ./ia download --search 'collection:DriveInMovieAds'

## Import Flow
1. Pull from Plex and insert into database
2. Go to step 2 and then pull from database and get tags from python. Do this in batches with a limit.


## Credits


## Sitemap
* index
    * grind
        * new - user locked
        * upcoming
        * past
        * calendar(?)
    * admin - user locked
        * import plex
        * settings
        * manage films(?)
    * films
    * about


## Roadmap
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
* clean up flashdata