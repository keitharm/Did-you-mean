=AUTHOR: Sole_Wolf (Keith Armstrong)=

NAME: Did you mean...

VERSION: 1.0.0

DATE: August 23th, 2012

DESCRIPTION: Provides helpful URL suggestions for users that might have mistyped an URL.

USAGE:
reindex.php creates an index of your entire site into a file called index.
It is recommended that you make this script run once a day via cron job to
have an updated database of your site so that the latest URLs can be synced.
You will have to run reindex.php at least one time in order for didyoumean
to have an index to work with.

To use this function, just call didyoumean() with a parameter containing $_SERVER['REQUEST_URI'].