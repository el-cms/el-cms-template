# "Login" layout
<i class="icon-file"></i> **template:** *files/layouts/login.ctp*

## Description
Simple centered layout template designed for login actions.

 * Based on Twitter Bootstrap
 * No navbar, so no menu.
 * Small, centered main content, perfect for login forms

## Required config

 * [AuthComponent](../theme_config.component_authComponent.md/docs:template) should be enabled in config to create admin/login links
 * `theme.language.useLanguages` should be set to true to have the language buttons (see [files::elements::lang-bar](../files.elements_lang-bar/docs:template)).

## Options

File options:

 * `style:` *string|style* - Default CSS file to use in template

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
