/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    Hennes Hervé <contact@h-hennes.fr>
 *  @copyright 2013-2016 Hennes Hervé
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  http://www.h-hennes.fr/blog/
 */
(function ($) {
    function setupLinksWidget()
    {

        tinymce.PluginManager.add('eicmslinks', function (editor, url) {
            // Add a button that opens a window
            editor.addButton('example', {
                text: 'My button',
                icon: false,
                onclick: function () {
                    // Open window
                    editor.windowManager.open({
                        title: 'Example plugin',
                        body: [
                            {type: 'textbox', name: 'title', label: 'Title'}
                        ],
                        onsubmit: function (e) {
                            // Insert content when the window form is submitted
                            editor.insertContent('Title: ' + e.data.title);
                        }
                    });
                }
            });

            // Adds a menu item to the tools menu
            editor.addMenuItem('example', {
                text: 'Example plugin',
                context: 'tools',
                onclick: function () {
                    // Open window with a specific url
                    editor.windowManager.open({
                        title: 'TinyMCE site',
                        url: 'https://www.tinymce.com',
                        width: 800,
                        height: 600,
                        buttons: [{
                                text: 'Close',
                                onclick: 'close'
                            }]
                    });
                }
            });

            return {
                getMetadata: function () {
                    return  {
                        title: "Example plugin",
                        url: "http://exampleplugindocsurl.com"
                    };
                }
            };
        });
        tinySetup({
            editor_selector :"autoload_rte",
            plugins : "align colorpicker link image filemanager table media placeholder advlist code table media autoresize eicmslinks",
            toolbar1 :"",
            toolbar2: "eicmslinks",
        });
    }

    $(document).ready(function () {
        
        if (typeof tinyMCE === 'undefined') {
            setTimeout(function () {
                console.log('Launch function')
                setupLinksWidget();
            }, 5000);
            
            return;

        } else {
            console.log('TinyMce defined');
        }
    });
})(jQuery);
