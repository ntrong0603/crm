/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    // CKEDITOR.config.removeButtons = "Save";
    // CKEDITOR.config.removeButtons = "Find";
    // CKEDITOR.config.removeButtons = "Templates";
    config.removeButtons =
        "NewPage,Print,NewPage,Preview,DocProps,Templates,Scayt,BidiLtr,BidiRtl,Flash,Smiley,Iframe,UIColor,PageBreak,SpecialChar,About,MediaEmbed,Cut,Copy,Paste,PasteText,PasteFromWord,Anchor,Blockquote,RemoveFormat,ShowBlocks,NumberedList,BulletedList,Replace,Find,SelectAll,CopyFormatting,Language";
    // CKEDITOR.config.removeButtons = "NewPage";
    // CKEDITOR.config.removeButtons = "DocProps";
    // CKEDITOR.config.removeButtons = "Preview";

    config.skin = "moono";
    // config.language = "ja";
    //Tắt rule remove của ckeditor
    config.allowedContent=true;
    CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
    CKEDITOR.config.width = "600px";
    config.extraPlugins = "colordialog, sourcedialog";
    config.removePlugins = "easyimage, cloudservices";
    config.linkShowTargetTab = false;
};

CKEDITOR.config.image_previewText = " ";

// CKEDITOR.on("dialogDefinition", function (ev) {
//     var dialogName = ev.data.name;
//     var dialogDefinition = ev.data.definition;
//     if (dialogName == "link") {
//         var targetTab = dialogDefinition.getContents("target");
//         var targetField = targetTab.get("linkTargetType");
//         targetField["default"] = "_blank";

//         var infoTab = dialogDefinition.getContents("info");
//         var protocolField = infoTab.get("protocol");
//         protocolField.items.splice(2, 3);
//     }

//     if (dialogName == "image") {
//         var linkTab = dialogDefinition.getContents("Link");
//         var cmbTargetField = linkTab.get("cmbTarget");
//         cmbTargetField["default"] = "_blank";
//     }
// });
