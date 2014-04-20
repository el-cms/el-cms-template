# "Default" layout
<i class="icon-file"></i> **template:** *files/layouts/default.ctp*
<i class="icon-cogs"></i> **related files:** [files::elements::footer](../files.element_footer.md/docs:template),  [menus::menu](../menus.menu.md/docs:template),  [menus::composed](../menus.composed.md/docs:template)

## Description
Default layout template used to generate prefix-specific layouts.

 * Based on Twitter Bootstrap
 * A colored line appears under the menu, and the color changes with the prefix (css: `#[prefix]-line`)
 * Border color of the main container can change on different plugins: (css: `.container-[plugin], .container-other`).

## Required config

 * [AuthComponent](../theme_config.component_authComponent.md/docs:template) should be enabled in config to create admin/login links
 * `theme.language.useLanguages` should be set to true to have the language buttons (see [files::elements::lang-bar](../files.elements_lang-bar/docs:template)).

## Options

File options:

 * `usePluginColors:` *bool|false* - Use or not plugin-specific colors for main container.
 * `userLine:` *string, 'public'* - CSS class of the "user line", which is under the main navbar.
 * `style:` *string, 'style'* - Default CSS file to use in template
 * `menu:` *string, $userLine* Menu element name to include as menu

## Example:
An admin layout:
<pre class="syntax yaml">
[...]
files:
  adminLayout:
    targetPath: View::Layouts::admin.ctp
    template: layouts::default
    options:
      # will create a .line-admin line
      userLine: admin
      # Will include admin-style.css
      style: admin-style
      # Will use custom colors
      usePluginColors: true
</pre>
