This script import your worpress entries into Tumblr. This version processes the entries ONLY. No tags, no comments, nothing else. Please fork it and make it better.

###Usage
First, [export your wordpress content](http://en.support.wordpress.com/export/) into an XML file. Modify the ` wp2tumblr.php ` file by updating the ` $file_name ` variable with the name of the export XML file and the ` $tumblr_username `, ` $tumblr_password ` variables with your Tumblr credentials.

Then run the following:
    [~#] php wp2tumblr.php
