# "Composed" menu
<i class="icon-file"></i> **template:** *menus/composed.ctp*
<i class="icon-cogs"></i> **related elements:** [menus::elements::sfw_switch](../menus.element_sfw_switch.md/docs:template), [menus::elements::user_menu](../menus.element_user_menu.md/docs:template)

## Description
This template is a composed menu file: it will contain only defined thing, so if you create `plugins/controllers/actions` undefined in the menu options, they won't be created.

## Required config
Nothing

## Options

### Options for the template
 * `elements:` *array* - List of elements. Elements are of this form:

   * `[elementName]:` *string*  - Name used in item title

     * `t:` *string, cakeUrl* - Link type. Can be `userMenu` (the elements::user_menu), `sfwSwitch` (the elements/sfw_switch), `text` (a full URL) or `cakeURL`
     * `[url config]`

#### [url config] for `userMenu`
 * `mustBeLoggedIn:` *bool, false* - Set to true if an user must be logged in for the menu to appear
The `[elementName]` will be the user's name, so put whatever you want.

#### [url config] for `sfwSwitch`
Adds [the switch](../menus.element_sfw_switch.md/docs:template) to hide/show sfw data
The `[elementName]` is defined in the element, so put whatever you want.
Note that the element will be created only if `theme.sfw.useSFW` is set to true.

#### [url config] for `text`

 * `icon:` *string, null*  - Icon for the item
 * `url:` *string* - Plain text URL

Text URLs will open in a new window.

#### [url config] `cakeUrl`

 * `p:` *string* - Plugin name
 * `c:` *string* - Controller name
 * `a:` *string* - Action name
 * `o:` *array* - Other URL parameters

### Options from theme:

## Example:
<pre class="syntax yaml">
[menuname]:
	generate: true
  template: composed
  targetPath: Elements::menus::public.ctp
  options:
    elements:
      # Link name
      Articles:
        # type: cake URL
        t: cakeUrl
        # Plugin
        p: Blog
        # Controller
        c: Posts
        # Action
        a: index
      [other links...]
      Members:
        t: userMenu
        mustBeLoggedIn: true
      SFW:
        t: sfwMenu

</pre>
Note that you should also define the used prefixes as for the standard menu template (menu.ctp).