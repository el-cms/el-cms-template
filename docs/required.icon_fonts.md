# Required files: Icon fonts
You have the choice of using glyphicons icons (from twitter Bootstrap) or fontAwesome icons.

By default, fontAwesome icons are used in this theme, but you can change if you want.

## How to change:

 * Update both `admin-bootstrap.less` and `public-bootstrap.less` (comment the fontAwesome parts, uncomment the glyphicons lines)
 * Compile your CSS
 * Copy the required files (<i class="icon-cog"></i> `Sb.Shell required`)
 * Update your config file: `theme.iconPack: glyphicon` instead of `theme.iconPack: fa`.
 * Bake all your views, files and menus (<i class="icon-cogs"></i> `Sb.Shell views`, `Sb.Shell files`, `Sb.Shell menus`)
 * Your app now uses Glyphicons instead of FontAwesome icons.

## How to import these files:
<div class="row">
<div class="col-sm-6">
<h3>FontAwesome</h3>
<pre class="syntax yaml">
[...]
  required:
   fonts-awesome:
     type: folder
     ## Source
     source: css::font-awesome::fonts
     ## Target folder
     target: webroot::fonts
     contentOnly: true
</pre>
</div>
<div class="col-sm-6">
<h3>Glyphicons:</h3>
<pre class="syntax yaml">
[...]
  required:
   afonts-glyphs:
     type: folder
     ## Source
     source: css::twitter-bootstrap::dist::fonts
     ## Target folder
     target: webroot::fonts
     contentOnly: true
</pre>
</div>
</div>