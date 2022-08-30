var idEditor = 3;
var optionColorpickerDefault = {
    color: "#fff",
    flat: false,
    showInput: true,
    className: "full-spectrum",
    showInitial: false,
    showPalette: false,
    showSelectionPalette: true,
    maxPaletteSize: 10,
    preferredFormat: "hex",
    chooseText: "選択する",
    cancelText: "閉じる",
    clickoutFiresChange: false,
    move: function (color) {},
    show: function () {},
    beforeShow: function () {},
    hide: function (color) {},
    change: function (color) {
        // $(this).parents(".slot_sort").css("background-color", color.toHexString());
        var $el = $(this).parents(".slot_sort");
        if ($el.length) {
            $el.css("background-color", color.toHexString());
        } else {
            $("#" + $(this).parents(".slot-box").attr("id")).css(
                "background-color",
                color.toHexString()
            );
        }
    },
};
var dataNotHTML = `＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

こんにちは [customer_name]さま

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝



‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥
編集・発行元
[shop_name]：[shop_shop_url]
プライバシーポリシー：http://xxxx.jp/contents/privacypolicy
お問い合わせTEL：[shop_tel]
 お問い合わせMAIL：xxx＠xxx.jp

こちらは配信用メールアドレスです。返信いただいても対応は致しかねます。
対応ご希望の際はお問い合わせメールアドレスへお願い致します。
‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥‥`;
window.vue = new Vue({
    //Thành phần áp dụng
    el: "#app",
    //khai báo dữ liệu ban đầu
    data: {
        tab: "tab1",
        viewTemplate: "1",
        dataSave: {
            dataHTML: "",
            data: dataNotHTML,
            test_send_to: "",
            test_memo: "",
            send_type: 1,
        },
        dataDesign: "",
        param: {},
    },
    //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
    methods: {
        /**
         * handle change tab
         * @param string nameTab name for tab need show
         */
        changeTab(nameTab) {
            this.tab = nameTab;
        },
        /**
         * Xử lý cho các sự kiện khi tạo thêm phần tử
         */
        /**
         * Xử lý sự kiện js chỉ gọi 1 lần
         */
        handleCallOnlyOne() {
            var _this = this;
            var listElTemplate = $("#slot-wrap");
            listElTemplateFirst = listElTemplate.offset().top;
            $(window).scroll(function () {
                var heightNavTop = $(".main-header").outerHeight();
                var btnAction = $(".btn-action-main");
                var btnActionHeight = btnAction.outerHeight();
                if (heightNavTop + btnActionHeight <= $(window).scrollTop()) {
                    btnAction.addClass("fixed");
                } else {
                    btnAction.removeClass("fixed");
                }
                if ($(window).scrollTop() < listElTemplateFirst) {
                    listElTemplate.css("top", 0 + "px");
                } else {
                    if ($(window).scrollTop() >= listElTemplate.offset().top) {
                        listElTemplate.css(
                            "top",
                            $(window).scrollTop() -
                                listElTemplateFirst +
                                btnActionHeight +
                                "px"
                        );
                    } else if (
                        listElTemplate.offset().top > listElTemplateFirst
                    ) {
                        listElTemplate.css(
                            "top",
                            $(window).scrollTop() -
                                listElTemplateFirst +
                                btnActionHeight +
                                "px"
                        );
                    }
                }
            });
            //Event auto copy where click input for variable list
            var listInput = $("#variable-tag_list input");
            var notifyCopy = $("#variable-tag_notify");
            listInput.click(function () {
                var vInput = document.createElement("input");
                vInput.style =
                    "position: absolute; left: -1000px; top: -1000px";
                vInput.value = $(this).val();
                document.body.appendChild(vInput);
                vInput.select();
                document.execCommand("copy");
                document.body.removeChild(vInput);
                notifyCopy.addClass("show");
                setTimeout(function () {
                    notifyCopy.removeClass("show");
                }, 1500);
            });

            $("#variable-tag_button").on("click", function (e) {
                e.preventDefault();
                var variableTag = $("#variable-tag");
                if (variableTag.hasClass("variable-tag_show")) {
                    variableTag.removeClass("variable-tag_show");
                } else {
                    variableTag.addClass("variable-tag_show");
                }
            });

            function changeColor(color) {
                $(".mail-body-wrap").css("background-color", color);
                $(".container").attr("bgcolor", color);
                $(".container-padding")
                    .attr("bgcolor", color)
                    .css("background-color", color);
                if (!$("#form_body_bgcolor").length) {
                    $("<input>", {
                        type: "hidden",
                        id: "form_body_bgcolor",
                        name: "layout_genre_id",
                        value: color,
                    }).appendTo("#form_layout");
                } else {
                    $("#form_body_bgcolor").val(color);
                }
            }
            function initBgColor(dataBgColor, changeFlg) {
                var bgColor;
                if (changeFlg) {
                    bgColor = !dataBgColor ? "#FFFFFF" : dataBgColor;
                } else {
                    bgColor = "#FFFFFF";
                }
                changeColor(bgColor);
                $(".js-bgcolor").spectrum({
                    showInput: true,
                    chooseText: "選択する",
                    cancelText: "閉じる",
                    preferredFormat: "hex",
                    color: bgColor,
                    change: function (color) {
                        changeColor(color.toHexString());
                    },
                });
            }
            //Convert template email
            var changeModeMsg =
                "モード変更後に保存すると、変更前の編集モードで保存されていたデザイン内容は、すべて破棄されますので、ご注意ください。";
            $("#source_edit_mode").click(function () {
                if (confirm(changeModeMsg)) {
                    var reflectMsg =
                        "かんたん編集モードで作成した内容をHTMLに反映しますか？";
                    var error = false;
                    if (confirm(reflectMsg)) {
                        var params = _this.getTestParam();
                        $.ajax({
                            type: "post",
                            url: urlConvert,
                            data: params,
                            async: false,
                            success: function (data) {
                                _this.dataSave.dataHTML = data;
                            },
                            error: function (error) {
                                var res = $.parseJSON(e.responseText);
                                console.error(res.error);
                                loading.hide();
                            },
                        });
                    }
                    if (!error) {
                        $(".slot-wrap, #mail_body_text").hide();
                        $(this).hide();
                        $("#easy_edit_mode").show();
                        $("#mail_body_source").show();
                        $(".mail-body-wrap .caution").show();
                        $(".js-bgcolor").hide();
                        $(".mail-body-wrap").css("background-color", "#FFFFFF");
                        $("#source_edit").val(1);
                        $(".mail-body-wrap").css("border", "none");
                    }
                }
            });

            $("#easy_edit_mode").click(function () {
                if (confirm(changeModeMsg)) {
                    $(".slot-wrap, #mail_body_text").show();
                    $(this).hide();
                    $("#source_edit_mode").show();
                    $("#mail_body_source").hide();
                    $(".mail-body-wrap .caution").hide();
                    $(".js-bgcolor").show();
                    initBgColor("#FFFFFF", true);
                    $("#source_edit").val(0);
                    $(".mail-body-wrap").css("border", "1px dashed #bebebe");
                }
            });
            //kiểm tra slot
            // chưa có thì hiển thị phần tử mặc định, ngược lại ẩn đi
            function changeMainSlotSort() {
                if ($("#mail-body .slot_sort").length <= 0) {
                    // $('#mail-body > tbody').sortable('disable');
                    $("#main_slot_end").show();
                } else {
                    // $('#mail-body > tbody').sortable('enable');
                    $("#main_slot_end").hide();
                }
                if ($("#mail-head .slot_sort").length <= 0) {
                    $("#mail-head-end").show();
                } else {
                    $("#mail-head-end").hide();
                }
                if ($("#mail-foot .slot_sort").length <= 0) {
                    $("#mail-foot-end").show();
                } else {
                    $("#mail-foot-end").hide();
                }
            }
            // function isCreateAfterAtagLimitReleased() {
            //     return true;
            // }
            // function isAbleToAddSlot() {
            //     var result = true;
            //     if (isCreateAfterAtagLimitReleased() && $('td.active').length >= 50) {
            //         result = false;
            //     }
            //     return result;
            // }
            function slotEvent() {
                //remove event
                $(".del, .copy, .edit-btn a").unbind();
                $(".del").click(function (e) {
                    var elementDelete = $(this).closest(".slot_sort");
                    // var tbody = elementDelete.closest("tbody");
                    elementDelete.remove();
                    changeMainSlotSort();
                });
                $(".edit-btn a").click(function (e) {
                    var id = $(this).data("ideditor");
                    loadEdit(id);
                });
                $(".copy").click(function (e) {
                    var parent = $(this).parents(".slot_sort");
                    var clone = parent.clone();
                    parent.after(clone);
                    var elEdit = $(clone.find(".edit-wrap"));
                    var elEditLength = elEdit.length;
                    if (elEditLength > 0) {
                        for (var index = 0; index < elEditLength; index++) {
                            idEditor++;
                            var id = "editor" + idEditor;
                            var editWrap = $(elEdit[index]);
                            editWrap.find(".edit-box").attr("id", id);
                            editWrap
                                .find(".edit-btn a")
                                .attr("data-ideditor", id);
                        }
                    }
                    slotEvent();
                });
            }

            slotEvent();
            //sự kiện tạo phần tử
            $(".slot-list li").draggable({
                connectToSortable: "#mail-body > tbody",
                helper: "clone",
                revertDuration: 200,
                opacity: 0.8,
                scroll: true,
                appendTo: "#mail-body > tbody:first-child",
                start: function () {
                    $("#add-slot,#add-slot-head,#add-slot-foot").css(
                        "background-color",
                        "#f7f7f7"
                    );
                },
                stop: function () {
                    $("#add-slot,#add-slot-head,#add-slot-foot").css(
                        "background-color",
                        ""
                    );
                },
                revert: "invalid",
            });
            //sự kiện sort
            $("#mail-body > tbody").sortable({
                helper: "clone",
                connecHeight: ".slot_sort",
                placeholder: "ui-state-highlight",
                //edit style khi nhấn vào phần tử
                sort: function (event, ui) {
                    var $target = $(event.target);
                    if (!/html|body/i.test($target.offsetParent()[0].tagName)) {
                        var top =
                            event.pageY -
                            $target.offsetParent().offset().top -
                            ui.helper.outerHeight(true) / 2;
                        ui.helper.css({ top: top + "px" });
                    }
                },
                //xử lý khi có phần tử mới
                receive: function (event, ui) {
                    // if (!isAbleToAddSlot()) {
                    //     alert('挿入できるスロットは50個までとなります');
                    //     return false;
                    // }
                    drOptions.drop(event, ui);
                },
            });
            $("#mail-body > tbody").disableSelection();

            //sử lý khi kéo thả slot
            var drOptions;
            drOptions = {
                accept: "li.js-add-slot",
                tolerance: "pointer",
                drop: function (e, ui) {
                    var parent = $(e.target);
                    //tạo id cho slot
                    var slotAll = $(e.target).find("slot_sort");
                    var maxID = 1;
                    var lengthSlotAll = slotAll.length;
                    if (lengthSlotAll > 0) {
                        for (var index = 0; index < lengthSlotAll; index++) {
                            var id = parseInt(
                                $(slotAll[index]).attr("id").split("_")[1]
                            );
                            if (maxID <= id) {
                                maxID = id + 1;
                            }
                        }
                    }
                    idSlot = "slot_" + maxID;
                    //lấy phần tử được thả vào
                    var slot = parent.find(".js-add-slot");
                    //lấy id của layout slot muốn tạo
                    var idTemplate = slot.data("template");
                    //phần tử chứa mẫu
                    var libTemplate = $("#table-lib-template-slot");
                    //tìm và sao chép mẫu
                    var template = libTemplate.find("#" + idTemplate).clone();
                    template.attr("id", idSlot);
                    //thêm mẫu vào sau slot
                    slot.after(template);
                    //xóa slot
                    slot.remove();
                    var elEdit = $(template.find(".edit-wrap"));
                    var elEditLength = elEdit.length;
                    if (elEditLength > 0) {
                        for (var index = 0; index < elEditLength; index++) {
                            idEditor++;
                            var id = "editor" + idEditor;
                            var editWrap = $(elEdit[index]);
                            editWrap.find(".edit-box").attr("id", id);
                            editWrap
                                .find(".edit-btn a")
                                .attr("data-ideditor", id);
                        }
                    }
                    slotEvent();
                    changeMainSlotSort();
                    $(template.find(".paint")).spectrum(
                        optionColorpickerDefault
                    );
                },
            };
            function loadEdit(id) {
                $("#" + id).attr("contenteditable", true);
                // Kiểm tra nếu đã tồn tại id trong CKEDITOR thì remove để tránh trùng sự kiện
                var editor = CKEDITOR.instances[id];
                if (editor) {
                    CKEDITOR.remove(editor);
                }
                //Khởi tạo CKEDITOR
                editor = CKEDITOR.inline(id, {
                    startupFocus: true,
                });

                //sự kiện focus
                editor.on("focus", function (event) {
                    showEditor(event, id);
                    $("#" + id)
                        .parent()
                        .find(".edit-btn")
                        .attr("style", "display: none !important;");
                });

                // Sự kiện out focus
                editor.on("blur", function (event) {
                    hideEditor(event, id);
                    $("#" + id)
                        .parent()
                        .find(".edit-btn")
                        .css("display", "none");
                    setTimeout(function () {
                        CKEDITOR.instances[id].destroy();
                    }, 0);
                });
            }

            // Sử lý show editor
            function showEditor(event, id) {
                $("#" + id)
                    .parents(".slot-box")
                    .find(".over-btn,.edit-btn")
                    .css("display", "none");
                $("#" + id)
                    .parents(".edit-wrap, .active")
                    .unbind("mouseenter")
                    .unbind("mouseleave");
                $("#" + id).removeAttr("title");
                $("#mail-body > tbody").sortable("disable");
            }

            // Sử lý sự kiện thi ẩn editor
            function hideEditor(event, id) {
                // maskOverContents();
                $("#mail-body > tbody").sortable("enable");
                $("#" + id).removeAttr("contenteditable");
            }

            //sự kiện kéo thả lúc chưa có slot nào được tạo
            $("#main_slot_end").droppable(drOptions);
            $("#add-slot-head, #add-slot-foot").droppable({
                accept: "li.js-add-slot",
                tolerance: "pointer",
                drop: function (e, ui) {
                    var parent = $(e.target).closest("tbody");
                    var slot = parent.find(">tr:first-child");
                    var prefix = "slot_head_";
                    if (parent.parents("#mail-foot").length > 0) {
                        prefix = "slot_foot_";
                    }
                    //tạo id cho slot
                    var slotAll = parent.find("slot_sort");
                    var maxID = 1;
                    var lengthSlotAll = slotAll.length;
                    if (lengthSlotAll > 0) {
                        for (var index = 0; index < lengthSlotAll; index++) {
                            var id = parseInt(
                                $(slotAll[index]).attr("id").split("_")[1]
                            );
                            if (maxID <= id) {
                                maxID = id + 1;
                            }
                        }
                    }
                    idSlot = prefix + maxID;
                    //lấy id của layout slot muốn tạo
                    var idTemplate = $(ui.draggable).data("template");
                    //phần tử chứa mẫu
                    var libTemplate = $("#table-lib-template-slot");
                    //tìm và sao chép mẫu
                    var template = libTemplate.find("#" + idTemplate).clone();
                    template.attr("id", idSlot);
                    //thêm mẫu vào sau slot
                    slot.after(template);
                    var elEdit = $(template.find(".edit-wrap"));
                    var elEditLength = elEdit.length;
                    if (elEditLength > 0) {
                        for (var index = 0; index < elEditLength; index++) {
                            idEditor++;
                            var id = "editor" + idEditor;
                            var editWrap = $(elEdit[index]);
                            editWrap.find(".edit-box").attr("id", id);
                            editWrap
                                .find(".edit-btn a")
                                .attr("data-ideditor", id);
                        }
                    }
                    changeMainSlotSort();
                    slotEvent();
                    $(template.find(".paint")).spectrum(
                        optionColorpickerDefault
                    );
                    //xóa slot
                },
            });
            $(".paint").spectrum(optionColorpickerDefault);
            $(".js-bgcolor").spectrum({
                color: "#fff",
                flat: false,
                showInput: true,
                className: "full-spectrum",
                showInitial: false,
                showPalette: false,
                showSelectionPalette: true,
                maxPaletteSize: 10,
                preferredFormat: "hex",
                chooseText: "選択する",
                cancelText: "閉じる",
                clickoutFiresChange: false,
                move: function (color) {},
                show: function () {},
                beforeShow: function () {},
                hide: function (color) {},
                change: function (color) {
                    changeColor(color.toHexString());
                },
            });

            $("#review-template").on("show.bs.modal", function (e) {
                _this.handleSaveProvisional();
                $(".review-template-tab_content_item iframe").attr(
                    "src",
                    urlReview
                );
            });
        },
        /**
         * Handle param template
         */
        getTestParam() {
            input = $("#form_test_mail").serializeArray();
            for (key in input) {
                var data = input[key] || {};
                if (data) {
                    this.param[data.name] = data.value;
                }
            }

            if (
                !this.param.send_type &&
                $("input[name=body_type]:checked").length
            ) {
                this.param.send_type = $("input[name=body_type]:checked").val();

                if (getEditType() === "text_edit") {
                    this.param.send_type = 1;
                }
            }

            var easyEditFlg = (Number($("#source_edit").val()) || 0) === 0;
            this.param.easy_edit_flg = Number(easyEditFlg || false);

            if (Number(this.param.send_type) === 1) {
                if (easyEditFlg) {
                    this.param.body = $("#mail_body_text").html();
                } else {
                    this.param.body = $("#mail_body_source").val();
                }
                this.param.text_body = $("#form_text_body").val();
            } else {
                this.param.body = $("#form_text_body").val();
            }

            var responsiveFlg = true;
            if ($("input[name=responsive]:checked").length) {
                responsiveFlg = Boolean(
                    Number($("input[name=responsive]:checked").val())
                );
            }
            this.param.responsive_flg = Number(responsiveFlg);

            this.param.bgcolor = $("#form_body_bgcolor").val() || "";

            return this.param;
        },
        /**
         * Handle save provisional design template
         */
        handleSaveProvisional() {
            var params = this.getTestParam();
            $.ajax({
                type: "post",
                url: urlSaveProvisional,
                data: params,
                async: false,
                headers: { "X-Requested-With": "XMLHttpRequest" },
                success: function (response) {
                    loading.hide();
                },
                error: function (error) {
                    var res = $.parseJSON(e.responseText);
                    console.error(res.error);
                    loading.hide();
                },
            });
        },
        //Event change tab review template
        handleChangeTabReview() {
            if (this.viewTemplate == 1) {
                this.viewTemplate = 2;
            } else {
                this.viewTemplate = 1;
            }
        },
    },
    //Sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiền hoặc dữ liệu thuộc function đó có thay đổi
    //Chỉ cho những sự kiện không truyền tham số
    computed: {},
    // Xẩy ra trước khi khởi tạo, data và event được khai báo chưa tự thay đổi khi cập nhật
    beforeCreate() {
        console.log("beforeCreate");
    },
    //Khởi tạo giống như __contructor của react, diễn ra trước quá trình tạo DOM
    created() {
        console.log("create");
    },
    // Trước khi có đầy đủ quyền truy cập vào phần tử DOM
    beforeMount() {
        console.log("beforeMount");
    },
    // mounted khi đã có đầy đủ quyền truy cập vào phần tử DOM
    mounted() {
        $("#app").show();
        this.handleCallOnlyOne();
        console.log("mounted");
    },
    //xử lý trước khi dữ liệu bị thay đổi
    beforeUpdate() {
        console.log("beforeUpdate");
    },
    //Xử lý khi dữ liệu đã thay đổi
    updated() {
        activeTabelResponsive();
        console.log("updated");
    },
    //Xử lý trước khi hủy đối tượng
    beforeDestroy() {
        console.log("beforeDestroy");
    },
    //Xử lý khi hủy đối tượng
    destroyed() {
        console.log("destroyed");
    },
});
