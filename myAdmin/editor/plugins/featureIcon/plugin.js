CKEDITOR.plugins.add('featureIcon',
    {
        init: function (editor) {
            var pluginName = 'featureIcon';
            editor.ui.addButton('featureIcon',
                {
                    label: 'Feature Icon',
                    command: 'OpenWindow',
                    icon: CKEDITOR.plugins.getPath('featureIcon') + 'featureIcon.png'
                });
            var cmd = editor.addCommand('OpenWindow', { exec: showMyDialog });
        }
    });

function showMyDialog(textareaId) {
    id = textareaId.name;
    window.KCFinder = {
        callBackMultiple: function(files) {
            window.KCFinder = null;
            $("#"+id).val("");
            var  filesT= "";
            for (var i = 0; i < files.length; i++) {
                filesT += "<img src='" +files[i] + "' class='img-responsive'/>";
            }
            CKEDITOR.instances[id].setData(filesT);
        }

    };

    window.open('editor/kcfinder/browse.php?type=images&dir=images/feature',
        'kcfinder_multiple', 'status=0, toolbar=0, location=0, menubar=0, ' +
        'directories=0, resizable=1, scrollbars=0, width=800, height=600'
    );
}