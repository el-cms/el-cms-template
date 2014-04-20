# Required files: CSS
<i class="icon-folder-open"></i> **folder:** *required/js*

## Needed plugins:

### ckeditor
<i class="icon-folder-open"></i> **folder:** *required/js/ckeditor*
WYSIWYG editor for TEXT fields

## Needed files:
### datetime
<i class="icon-file"></i> **folder:** *required/datetime*
Datetime picker for datetime fields

### jQuery
<i class="icon-file"></i> **folder:** *required/jquery-1.10.2.min.js*
jQuery is needed for all the plugins and twitter Bootstrap themes.

### Twitter Bootstrap scripts
<i class="icon-folder-open"></i> **folder:** *required/css/dist/js/bootstrap.min.js*
Twitter bootstrap JS script

###



## How to import these files:
<pre class="syntax yaml">
[...]
  required:
   ##
   ## ckEditor
   js-ckEditor:
     type: folder
     source: js::ckeditor
     target: webroot::js
     contentOnly: false

   ##
   ## Datetime picker
   js-datetimepicker:
     type: file
     source: js::datetime::js::bootstrap-datetimepicker.js
     target: webroot::js::bootstrap-datetimepicker.js

   ##
   ## jQuery
   js-jQuery:
     type: file
     source: js::jquery-1.10.2.min.js
     target: webroot::js::jquery-1.10.2.min.js
   ##
   ## Twitter bootstrap
   js-TBS:
     type: file
     source: css::twitter-bootstrap::dist::js::bootstrap.min.js
     target: webroot::js::bootstrap.min.js
</pre>