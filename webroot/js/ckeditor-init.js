let editor = document.querySelector( '.editor' );
if (editor == null) {
    // error shows if the .editor is not found on the page
    editor = {};
}
 ClassicEditor
.create( editor, {
    
    toolbar: {
        items: [
            'heading',
            '|',
            'bold',
            'italic',
            'link',
            'bulletedList',
            'numberedList',
            '|',
            'indent',
            'outdent',
            '|',
            'CKFinder',
            'imageUpload',
            'blockQuote',
            'insertTable',
            'mediaEmbed',
            'undo',
            'redo'
        ]
    },
    ckfinder: {
        width: '737',
        height: '280',
        uploadUrl: '/nixser/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
    },
    language: 'en-gb',
    image: {
        toolbar: [
            'imageTextAlternative',
            'imageStyle:full',
            'imageStyle:side'
        ]
    },
    table: {
        contentToolbar: [
            'tableColumn',
            'tableRow',
            'mergeTableCells'
        ]
    },
    // licenseKey: '',
    
})
.then( editor => {
    window.editor = editor
})
.catch( error => {
    console.error( 'Oops, something gone wrong!' );
    console.error( 'Please, report the following error in the https://github.com/ckeditor/ckeditor5 with the build id and the error stack trace:' );
    console.warn( 'Build id: 4icghxt0mg0i-4a0i1npub3g4' );
    console.error( error );
});

$('form').submit(function(e) {
    let ckcontent = $('.ck-content').html()
    if (ckcontent == '' || ckcontent == '<p><br data-cke-filler="true"></p>') {
        e.preventDefault();
        alert('Please add data to the required field.');
    } else {
        return true;
    }
})