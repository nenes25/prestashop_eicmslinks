/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function (tinymce) {
    tinymce.create('tinymce.plugins.EiCmsLinksPlugin', {
        init: function (ed, url) {

            ed.addCommand('mceCmsLinks', function () {
                ed.windowManager.open({
                    file: url + '/eicmslinks.php',
                    width: 400 + 'px',
                    height: 400 + 'px',
                    inline: 1
                }, {
                    plugin_url: url, // Plugin absolute URL
                    some_custom_arg: 'custom arg' // Custom argument
                });
            });

            // Register buttons
            ed.addButton('eicmslinks', {title: 'Ajouter un lien vers une page cms', cmd: 'mceCmsLinks', image: url + '/img/eicmslinks.gif'});
        },

        getInfo: function () {
            return {
                longname: 'Ei Cms Links',
                author: 'contact@h-hennes.fr',
                authorurl: 'http://www.h-hennes.fr/blog/',
                infourl: 'http://www.h-hennes.fr/blog/',
                version: tinymce.majorVersion + "." + tinymce.minorVersion
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('eicmslinks', tinymce.plugins.EiCmsLinksPlugin);
})(tinymce);