import ClassicEditor from "@ckeditor/ckeditor5-editor-classic/src/classiceditor";
import InlineEditor from "@ckeditor/ckeditor5-editor-inline/src/inlineeditor";
import BalloonEditor from "@ckeditor/ckeditor5-editor-balloon/src/ballooneditor";
import DocumentEditor from "@ckeditor/ckeditor5-editor-decoupled/src/decouplededitor";
import EssentialsPlugin from "@ckeditor/ckeditor5-essentials/src/essentials";
import BoldPlugin from "@ckeditor/ckeditor5-basic-styles/src/bold";
import ItalicPlugin from "@ckeditor/ckeditor5-basic-styles/src/italic";
import UnderlinePlugin from "@ckeditor/ckeditor5-basic-styles/src/underline";
import StrikethroughPlugin from "@ckeditor/ckeditor5-basic-styles/src/strikethrough";
import CodePlugin from "@ckeditor/ckeditor5-basic-styles/src/code";
import SubscriptPlugin from "@ckeditor/ckeditor5-basic-styles/src/subscript";
import SuperscriptPlugin from "@ckeditor/ckeditor5-basic-styles/src/superscript";
import ListStyle from '@ckeditor/ckeditor5-list/src/liststyle';
import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment';
import LinkPlugin from "@ckeditor/ckeditor5-link/src/link";
import EasyImage from "@ckeditor/ckeditor5-easy-image/src/easyimage";
import ImagePlugin from "@ckeditor/ckeditor5-image/src/image";
import ImageInsert from "@ckeditor/ckeditor5-image/src/imageinsert";
import ImageCaption from "@ckeditor/ckeditor5-image/src/imagecaption";
import ImageStyle from "@ckeditor/ckeditor5-image/src/imagestyle";
import ImageToolbar from "@ckeditor/ckeditor5-image/src/imagetoolbar";
import ImageResizeEditing from "@ckeditor/ckeditor5-image/src/imageresize/imageresizeediting";
import ImageResizeButtons from "@ckeditor/ckeditor5-image/src/imageresize/imageresizebuttons";
import ImageUploadPlugin from "@ckeditor/ckeditor5-image/src/imageupload";
import CloudServicesPlugin from "@ckeditor/ckeditor5-cloud-services/src/cloudservices";
import Font from "@ckeditor/ckeditor5-font/src/font";
import Heading from "@ckeditor/ckeditor5-heading/src/heading";
import HeadingButtonsUI from "@ckeditor/ckeditor5-heading/src/headingbuttonsui";
import Highlight from "@ckeditor/ckeditor5-highlight/src/highlight";
import SimpleUploadAdapter from '@ckeditor/ckeditor5-upload/src/adapters/simpleuploadadapter';
import cash from "cash-dom";

let simpleEditorConfig = {
    plugins: [
        //ParagraphPlugin,
        BoldPlugin,
        UnderlinePlugin,
        ItalicPlugin,
        LinkPlugin,
    ],
    toolbar: {
        items: ["bold", "italic", "underline", "link"],
    },
};

let editorConfig = {
    cloudServices: {
        tokenUrl: "",
        uploadUrl: "",
    },
    plugins: [
        Font,
        EssentialsPlugin,
        BoldPlugin,
        UnderlinePlugin,
        StrikethroughPlugin,
        ItalicPlugin,
        LinkPlugin,
        Alignment,
        ListStyle,
        CodePlugin,
        SubscriptPlugin,
        SuperscriptPlugin,
        EasyImage,
        ImagePlugin,
        ImageInsert,
        ImageToolbar,
        ImageCaption,
        ImageStyle,
        ImageResizeEditing,
        ImageResizeButtons,
        ImageUploadPlugin,
        CloudServicesPlugin,
        SimpleUploadAdapter,
        Heading,
        HeadingButtonsUI,
        Highlight,
    ],
    image: {
        // Configure the available styles.
        styles: [
            'alignLeft', 'alignCenter', 'alignRight'
            ],
        resizeOptions: [
            {
                name: 'resizeImage:original',
                value: null,
                icon: 'original'
            },
            {
                name: 'resizeImage:25',
                value: '25',
                icon: 'small'
            },
            {
                name: 'resizeImage:50',
                value: '50',
                icon: 'medium'
            },
            {
                name: 'resizeImage:75',
                value: '75',
                icon: 'large'
            }
        ],

        // You need to configure the image toolbar, too, so it shows the new style
        // buttons as well as the resize buttons.
        toolbar: [
            'imageStyle:alignLeft',
            'imageStyle:alignCenter',
            'imageStyle:alignRight',
            '|',
            'resizeImage',
            '|',
            'imageTextAlternative'
        ]
    },
    toolbar: {
        items: [
            "fontSize",
            "fontFamily",
            "fontColor",
            "fontBackgroundColor",
            "heading",
            "|",
            "bulletedList",
            "numberedList",
            "alignment",
            "bold",
            "italic",
            "underline",
            "strikethrough",
            "code",
            "subscript",
            "superscript",
            "link",
            "undo",
            "redo",
            //"imageUpload",
            "insertImage",
            "highlight",
        ],
    },
    simpleUpload: {

        // The URL that the images are uploaded to.
        uploadUrl: '/ckupload',
        
        // Enable the XMLHttpRequest.withCredentials property.
        withCredentials: true,

        // Headers sent along with the XMLHttpRequest to the upload server.
        headers: {
            'X-CSRF-TOKEN': cash('meta[name="csrf-token"]').attr('content'),
            Authorization: 'Bearer <JSON Web Token>'
        }
    },
};


cash(".editor").each(function () {
    let editor = ClassicEditor;
    let options = editorConfig;
    let el = this;

    if (cash(el).data("simple-toolbar")) {
        options = simpleEditorConfig;
    }

    if (cash(el).data("editor") == "inline") {
        editor = InlineEditor;
    } else if (cash(el).data("editor") == "balloon") {
        editor = BalloonEditor;
    } else if (cash(el).data("editor") == "document") {
        editor = DocumentEditor;
        el = cash(el).find(".document-editor__editable")[0];
    }

    editor
        .create(el, options)
        .then((editor) => {
            if (cash(el).closest(".editor").data("editor") == "document") {
                cash(el)
                    .closest(".editor")
                    .find(".document-editor__toolbar")
                    .append(editor.ui.view.toolbar.element);
            }

            if (cash(el).attr("name")) {
                window[cash(el).attr("name")] = editor;
            }

            if (cash(el).closest(".editor").data("status") == "readOnly") {
                editor.isReadOnly = true;
            }
        })
        .catch((error) => {
            console.error(error.stack);
        });
});
