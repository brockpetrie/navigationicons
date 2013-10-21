# Admin Navigation Icons

-  Version: 1.0
-  Author: [Brock Petrie](http://www.brockpetrie.com)
-  Build Date: 2013-10-20
-  Requirements: Symphony 2.3.x

## Description

The extension allows for spicing up your Symphony admin navigation with webfont icons. The example webfont included with this extension contains 3 glyphs: a dashboard gauge, a puzzle piece and a settings cog.

## Usage

1.  Add the `navigationicons` folder to your Extensions directory
2.  Enable the extension from the Extensions page
3.  If you want to use your own icons, upload the webfont containing your icon set to `assets/fonts` in this extension's directory and declare your symbols/ligatures in System Preferences. (Details below.)

## Custom icons

While this extension does come with a default webfont containing a trio of icons, the real goal is to make it easy for you to use your own. Here's how:

1.  **Generate your webfont, or use an existing one.** If you want to use one that's already made and bundled up for you, go use your Googlefu. [Symbolset](http://example.net/) has some cool ones, and there's always [Font Awesome](http://fortawesome.github.io/Font-Awesome/). If you want to generate your own, I recommend [IcoMoon](http://icomoon.io/).
    - *Use an existing webfont.* Simply either rename your font files to `navigationicons` (e.g. *navigationicons.ttf*) and overwrite the font files in `assets/fonts` in this extension's directory — OR — upload your font files as is and change the paths in `assets/navigationicons.css`.
    - *Roll your own using IcoMoon.* I'll keep this brief, as you can find docs/instructions on how to use the app on their site. Basically, you'll pick your glyphs — either by picking and choosing from their available sets, or by uploading your own vector shapes —, then head into the font options. I recommend either enabling ligatures (read their docs if you don't know what that means) or replacing the default character assignments for each glyph with something memorable. Your memory will thank you later. Name your font `navigationicons` in the Preferences, then click download. Now that you have your font files, follow the upload instructions in **1A**.

2. **Assign your glyphs.** In the Symphony admin area, head into System Preferences and find the `Navigation Icons` section. You'll see a list of your navigation groups on the left and a text input on the right. In each text input, type the ligature or character assignment of the icon you want to use for that navigation group.

3. **Save.** Save your preferences and bask in the glory of your icon-adorned nav bar.

## Change log

**Version 1.0** 

-  Initial release.
