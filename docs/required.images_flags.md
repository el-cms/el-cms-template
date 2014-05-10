# Required files: CSS
<i class="icon-folder-open"></i> **folder:** *required/images/flags*

## Needed files
These flags images are used in some "add" views and footer links. You should copy he whole folder if you use languages support on your site.

## Adding flags
Icons comes from the famfamfam icon set, but remember to rename the ones you will add, as cakePHP uses a 3 chars country code instead of the original 2 chars.

## How to import these files:
<pre class="syntax yaml">
[...]
  required:
   Image_flags:
     type: folder
     source: images::flags
     target: webroot::img
     ## Copy the source folder too
     contentOnly: false
</pre>