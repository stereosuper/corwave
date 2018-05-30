(function() {
    tinymce.create('tinymce.plugins.CORWAVE', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(editor, url) {
            editor.addButton('bckq', {
                title : 'Blockquote',
                cmd : 'bckq',
                image : url + '/thumbnail_bckq.png'
            });

            editor.addButton('cta', {
                title : 'Button CTA',
                cmd : 'cta',
                image : url + '/thumbnail_mkto.png'
            });

            editor.addCommand('mkto', function(){
                editor.windowManager.open({
                    title: 'Marketo Form',
                    minWidth: 400,
                    body: [
                    {
                       type: 'textbox',
                       label: 'Label',
                       name: 'cta_label',
                       value: ''
                    }],
                    onsubmit: function(e) {
                        var cta = `<a class='cta'>${e.data.cta_label}</a>`;
                        editor.execCommand('mceInsertContent', 0, cta);
                    }
                });
            });

            editor.addCommand('bckq', function() {

                if( editor.selection.getContent() ){
                    alert('You have to unselect text before insert a quote.');
                }else{
                    editor.windowManager.open({
                        title: 'Blockquote',
                        minWidth: 400,
                        body: [
                        {
                            type: 'textbox',
                            label: 'Quote',
                            name: 'quote',
                            value: '',
                            multiline: true
                        },
                        {
                            type: 'textbox',
                            label: 'Source',
                            name: 'source',
                            value: ''
                        },
                        {
                            type: 'textbox',
                            label: 'Author',
                            name: 'cite',
                            value: ''
                        }],
                        onsubmit: function(e) {
                            var quote = `<blockquote cite='${e.data.source}'><span class="quote-span">&ldquo; ${e.data.quote} &rdquo;</span>`;
                            if( e.data.cite !== '' ){
                                quote += `<cite> ${e.data.cite} </cite>`
                            }
                            quote += '</blockquote>';
                            editor.execCommand('mceInsertContent', 0, quote);
                        }   
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
        createControl : function(n, cm) {
            return null;
        },
 
        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Corwave Buttons',
                author : 'Stereosuper',
                authorurl : 'http://stereosuper.fr',
                infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
                version : "0.1"
            };
        }
    });
 
    // Register plugin
    tinymce.PluginManager.add( 'corwave', tinymce.plugins.CORWAVE );
})();