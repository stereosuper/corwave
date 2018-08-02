(function corwavePlugins() {
    tinymce.create('tinymce.plugins.CORWAVE', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init(editor, url) {
            editor.addButton('bckq', {
                title: 'Blockquote',
                cmd: 'bckq',
                image: `${url}/thumbnail_bckq.png`,
            });

            // editor.addButton('cta', {
            //     title: 'Button CTA',
            //     cmd: 'cta',
            //     image: `${url}/thumbnail_cta.png`,
            // });

            // editor.addCommand('cta', () => {
            //     editor.windowManager.open({
            //         title: 'Button CTA',
            //         minWidth: 400,
            //         body: [
            //             {
            //                 type: 'textbox',
            //                 label: 'Label',
            //                 name: 'cta_label',
            //                 value: '',
            //             },
            //             {
            //                 type: 'textbox',
            //                 label: 'Lien',
            //                 name: 'cta_link',
            //                 value: '',
            //             },
            //         ],
            //         onsubmit(e) {
            //             const cta = `<a class='cta' href="${
            //                 e.data.cta_link
            //             }" title="${
            //                 e.data.cta_label
            //             }"><span><svg class='ellypsis top' width='52' height='12' viewBox='0 0 52 12' fill='none'><path d='M0 9.66831C5.4502 3.81882 14.4298 0 24.584 0C34.7382 0 43.7178 3.81882 49.168 9.66831' transform='translate(1 1)' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/></svg><svg class='ellypsis bottom' width='52' height='12' viewBox='0 0 52 12' fill='none'><path d='M0 9.66831C5.4502 3.81882 14.4298 0 24.584 0C34.7382 0 43.7178 3.81882 49.168 9.66831' transform='translate(1.83203 11) scale(1 -1)' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/></svg>${
            //                 e.data.cta_label
            //             }</span><svg class='icon icon-arrow' viewBox="0 0 75 32"><path d="M46.542 0.604l26.667 15.396-26.667 15.396v-12.729h-42.667c-1.473 0-2.667-1.194-2.667-2.667s1.194-2.667 2.667-2.667h42.667v-12.729z"></path></svg></a>`;
            //             editor.execCommand('mceInsertContent', 0, cta);
            //         },
            //     });
            // });

            editor.addCommand('bckq', () => {
                if (editor.selection.getContent()) {
                    alert('You have to unselect text before insert a quote.');
                } else {
                    editor.windowManager.open({
                        title: 'Blockquote',
                        minWidth: 400,
                        body: [
                            {
                                type: 'textbox',
                                label: 'Quote',
                                name: 'quote',
                                value: '',
                                multiline: true,
                            },
                            {
                                type: 'textbox',
                                label: 'Source',
                                name: 'source',
                                value: '',
                            },
                            {
                                type: 'textbox',
                                label: 'Author',
                                name: 'cite',
                                value: '',
                            },
                        ],
                        onsubmit(e) {
                            let quote = `<blockquote cite='${
                                e.data.source
                            }'><span class="quote-span">&ldquo; ${
                                e.data.quote
                            } &rdquo;</span>`;
                            if (e.data.cite !== '') {
                                quote += `<cite> ${e.data.cite} </cite>`;
                            }
                            quote += '</blockquote>';
                            editor.execCommand('mceInsertContent', 0, quote);
                        },
                    });
                }
            });
        },

        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl(n, cm) {
            return null;
        },

        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo() {
            return {
                longname: 'Corwave Buttons',
                author: 'Stereosuper',
                authorurl: 'http://stereosuper.fr',
                infourl:
                    'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
                version: '0.1',
            };
        },
    });

    // Register plugin
    tinymce.PluginManager.add('corwave', tinymce.plugins.CORWAVE);
})();
