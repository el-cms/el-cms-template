# About the Elabs Theme
__Theme:__ ELabs
__Author:__ Manuel Tancoigne
__superBake version:__ 0.3
__License:__ GPL
__Description:__
ELabs is a theme developped for Experiments Labs with these capacities:

 * Support for internationalization fields in tables
 * Support for anonymous content
 * Support for safe/not safe for work content
 * Basic user support (still no ACL gestion for now)
 * Menus templates
 * Custom views
 * Design based on twitter Bootstrap
 * Support for ckEditor on TEXT fields
 * Support for a dateTimePicker widget on DATETIME fields
 * Separate layout for login action with no menus
 * ...


## Internationalization fields
_This exemple is for two languages, french and english, but you can setup what you want._

### Table fields
In tables with translated content, create one field per language with language extension at the end. In this exemple of a "Posts" table, we want the _title_ and _content_ fields to be translated:

 * content_eng; content_fra
 * title_eng; title_fra

### Configuration file
In the configration file, update the `theme.language` section:
<pre class="syntax yaml">
language:
  ## Content can be in different languages
  useLanguages: false
  ## Default language for empty content
  fallback: eng
  ## Languages available
  available:
    eng
    fra
  descriptions:
    fra: Version française
    eng: English version
  ## Date formats for the different languages
  dateFormats:
    fra: d-m-Y \à H:i
    eng: Y-m-d \a\t h:ia
</pre>

### Related templates

## Anonymous content
Anonymous content allows you to hide the author of some items, and hide the anonymous data from users lists.

### Table fields

In order to use this, you need to __add a BOOL field__ in tables that may contain anonymous data. In your example, we will name it `isanon`.

### Configuration file

In the `theme` section, enable and configure the anonymous support:
<pre class="syntax yaml">
## Support for anonymous content
anon:
  ## Use anonymous content
  useAnon: true
   ## Field that describes an item as anonymous
   anonField: isanon

  ## Fields that can compromise the user anonymity
  anonDataFields:
    email
    pseudo
    name
    realname
  ## Foreign key linking the user to the item:
  anonFK: user_id
  ## Name of the anonymous user:
  anonName: Anonymous
  ## UserId for the anon user
  anonId: 2
</pre>

## Safe for work content
This options allows you to define data as safe or not safe data. When enabled, NSFW data is hidden and users must enable it to display.

### Table field

In order to use this, you need to __add a BOOL field__ in tables that may contain anonymous data. In your example, we will name it `nsfw`. When this field will be true, content will be flagged as nsfw.

### Configuration file

<pre class="syntax yaml">
## Support for safe/nsfw content
sfw:
  ## Defines if the system use a kind of Safe For Work limitations
  useSFW: true

  ## Field that describes an item as sfw:
  sfwField: nsfw

  ## Content of the sfw field that define it safe.
  ## (if field 'nsfw' = 0, content is safe)
  sfwSafeContent: 0

  ## Fields that may contain nsfw data:
  nsfwDataFields:
    image
    file
    text
    excerpt
    description
    content
    data
    link
</pre>

### Related templates
Templates related to SFW content are:

 * The [SFW switch](../menus.element_sfw_switch.md/docs:template) to include in [composed menus](../menus.composed.md/docs:template)


## Custom menu

In addition to the default menu which is based on config file, a new menu has been added: the __[composed](../menus.composed.md/docs:template)__ menu. It allows to create a custom menu and is perfect for frontends.

## Custom views templates

### Index
New indexes templates are available: __[article](../views.index_article.md/docs:template)__ and __[gallery](../views.index_gallery.md/docs:template)__ which are perfect for article list and images lists

### View
The default _view_ template has been updated

### User-related views:

 * [login](../views.user_login.md/docs:template) view.
 * [register](../views.user_register.md/docs:template) view.
 * _dashboard_ view

## Design based on Twitter Bootstrap
The ELabs theme uses [Twitter Bootstrap](http://getbootstrap.com) with [FontAwesome](http://fontawesome.io/) icons. It's easy to customize it:

 * _required/css/_ `admin.less` is the LESSCSS file related to the _admin_ layout
 * _required/css/_ `public.less` is the LESSCSS file related to the _public_ layout

These files include what you need to run your app. Don't forget to compile the css before running `Sb.Shell required` or your changes won't show up.

You will find methods in the [Theme class](../theme_class.methods.md/docs:template) to easily create HTML elements.

## ckEditor
For each TEXT fields, a [ckEditor](http://github.com/ckeditor/ckeditor-releases) editor will be displayed on edit/add actions

## dateTimePicker widget
For each DATETIME fields, a [date/time picker widget](http://github.com/smalot/bootstrap-datetimepicker) will be added to help you select correct dates.
