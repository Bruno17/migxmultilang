migxMultiLang
================================================================================
migxMultiLang is a simple to use Multi - Language - Solution for MODX Revolution

**Author:** Bruno Perner b.perner@gmx.de [webcmsolutions.de](http://www.webcmsolutions.de)

Thanks to Susan Ottwell https://github.com/sottwell for the idea and donation to
make starting this project possible

Features
--------------------------------------------------------------------------------
This MODX Revolution Extra is a simple to use Multi - Language - Solution.
All Tranlations and Language - switchings are done on the same Resource.
No Contexts or Language - Folders are needed.
No special .htaccess is needed

How does it work
--------------------------------------------------------------------------------
All translatable fields of the main-language are stored in normal TV-values,
also content, pagetitle, longtitle, introtext and so on.

All translated fields of all other languages are stored in a custom - table and 
can be marked as 'to Translate' and 'published'

only published field - translations are used for display with fallback to
default-language for each field.

For generating the Template and for all Resource-Listings pdoTools is used to get 
dynamically the translated field - values into the Template and into Listings.

switching between languages is done by urls like:


MyDomain/de/pathToResource.html

MyDomain/en/pathToResource.html

There are two plugins 

mml_initCulture, which determines the selected language with fallback to prefered 
client-browser-language and sets this system-settings dynamically 
on OnInitCulture:

`[[++cultureKey]]` - the selected cultureKey

`[[++site_url]]`  - does change to site_url/en/

`[[++original_cultureKey]]` - does hold the original cultureKey

`[[++original_site_url]]` - does hold the original site_url

mml_langRouter, which determines the correct Resource on OnPageNotFound

Backend - Features
--------------------------------------------------------------------------------
There is a CMP, for adding and managing multiple Languages.
For new Languages you just need to add another language and its lang_key to the
table

There is a second tab at this CMP, whith a list of Resources and its untranslated
fields, which can be used for translators.

The third Tab 'Form Manager' has a CMP to manage different Translate-forms.
Each form can be assigned to one or more Templates or can be marked as the 'default form'

At the Resources a custom-MIGX-TV-type is used, which lists all languages with a
button to edit all translatable fields in a MIGX-modal-window with a configurable
form.

Requirements
--------------------------------------------------------------------------------
MIGX - min-version: 2.6.2 pl

pdoTools - min-version: 1.9.0 rc2 (tested only with this version)

Installation
--------------------------------------------------------------------------------
Install the package by MODX package-management

modify the system-setting 

pdoFetch.class to: `pdotools.mmlfetch`

Make sure the tables get created and up to date by doing following steps:

Go to the MIGX - CMP to the tab 'Package Mangager'

put in Package Name: migxmultilang

go to the tab 'create tables' and click 'create Tables'

If you are upgrading do also the following to be sure the tables are up to date

go to the tab 'Add fields' and click the button 'Add fields'

go to the tab 'Update indexes' and click the button 'Update indexes'


Backend Setup
--------------------------------------------------------------------------------
Go to the Menu created by migxMultiLang 'migxMultiLang'.

Add your languages to the grid.

Go to the Tab 'Form Manager'

Click 'Import Configurations'

Edit this Configuration and set 
'Use as Default Formtabs for all other templates' to 'yes'

Click 'Create TVs' - this step does create all TVs, which are defined in that form

you don't need to assign them to any template

If you want create different Translation - Forms for different Templates, you can do it here.
Don't forget to click 'Create TVs' if you have additional translatable TVs added to your form.

Create the translations - TV in a extra category, for example: 'Translations'

with this setup:

name: translations (you can use any name)

input Type: migxdb

Configurations: mml_translations:migxmultilang,mml_translate:migxmultilang

the first is the name of the migx-configuration, which does automatically load our formtabs-configutation assigned to the current template, or our default-formtab

the second one is also allways needed to render some checkboxes (published, to translate) to each field
into the form

assign this TV to your template(s), which should be translatable

Translate fields
--------------------------------------------------------------------------------
On your Resource-form, there should be now a grid with all created languages and
a 'edit' - button for each language.

Click this button, which opens the form with all translatable fields 
(which was configured with the MIGX-configuration and exists a TV for each)

Fill out the values for each language.

The values for your default - language (the cultureKey within you system-setting
or context-setting is used) are stored into the normal TV-values.

The values for all other languages are stored into a custom-table.

The forms for all other languages have two checkboxes on each field.

to Translate - this is a marker for translators and is used as a filter in the 
Translators - CMP

published - only published fields are shown on the frontend, if fields for a
language are unpublished or empty, the value of the default-language is used.

There is also a CMP with a grid for quick translation of untranslated fields.

Frontend Setup
--------------------------------------------------------------------------------

### The Content of your Template

```
[[!mmlCache?
  &element=`pdoResources`
  &parents=`0` 
  &resources=`[[*id]]` 
  &tpl=`mml_resourceTemplate` 
  &includeTVs=`[[mmlGetTemplateTVs]]` 
  &prepareTVs=`1` 
  &processTVs=`1`
  &tvPrefix=`` 
  &loadModels=`migxmultilang`
  &prepareSnippet = `mmlTranslatePdoToolsRow`
]]
```

### The example Content of resourceTpl

```
<!doctype html>
<html lang="en">
<head>
<meta charset="[[++modx_charset]]">
<title>[[++site_name]] - [[+mml_pagetitle]]</title>
<base href="[[++site_url]]">
</head>
<body>
<div class="bg">
<div id="container">
<header>
    <a href="#" id="logo"><img src="/assets/images/logo.png" width="180" height="43" alt="logo"/></a>
    [[!mml_LangLinks]]    
    <nav>
        [[!pdoMenu? 
            &startId=`0` 
            &level=`1`
            &includeTVs=`mml_pagetitle`
            &prepareTVs=`1`
            &tvPrefix=`tv.`
            &loadModels=`migxmultilang`
            &prepareSnippet = `mmlTranslatePdoToolsRow`
            &tpl=`mml_MenuRowTpl`
        ]]
    </nav>
</header>
<section id="intro">
[[+mml_introtext]]
</section><!--end intro-->
<div class="holder_content" id="maincontent">


<h1>[[+mml_pagetitle]]</h1>
<p>
[[+mml_content:nl2br]]
</p>

[[*content]]
</div><!--end holder-->
<div class="holder_content" id="bottomcontent">
    <section class="group4">
    [[$latestArticle_[[++cultureKey]]]]
    </section>
</div><!--end holder-->
</div><!--end container-->
<footer>
    <div class="container">  
        <div id="FooterTwo"> &copy; 2011 [[++site_name]] </div>
        <div id="FooterThree"> Valid html5, design and code by <a href="http://www.marijazaric.com">marija zaric - creative simplicity</a> </div> 
    </div>
</footer>
</div><!--end bg-->
<!-- Free template distributed by http://freehtml5templates.com -->
</body>
</html>
```

Notes
--------------------------------------------------------------------------------
instaed of using the TV-placeholders for resourcefields like [[+mml_pagetitle]] [[+mml_longtitle]] [[+mml_content]]
you can just use [[+pagetitle]] [[+longtitle]] [[+content]]
they are also translated with the values of the current language by default.
In this case you would not fill out the translations for the default-language, but you would use the default resource-fields.


replace all getResourses - calls with pdoResources
and put allways these properties:

```
&includeTVs=`mml_pagetitle,.........all fields`
&prepareTVs=`1`
&loadModels=`migxmultilang`
&prepareSnippet = `mmlTranslatePdoToolsRow`
```

you can of course allways use lexicon-tags, which will get translated into the selected language

If you have contexts, where no translation and site_url - changes should happen use the context-setting

```mml.do_translate``` with a value of ```0```

