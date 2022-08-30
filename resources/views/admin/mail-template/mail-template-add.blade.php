@extends('admin.layout')
@section('titlePage', 'テンプレート編集')
<!-- add libs, code css other -->
@section('stylecss')
<!-- spectrum colorpicker -->
<link rel="stylesheet" type="text/css" href="{{asset('adminLTE/plugins/spectrum/spectrum.css')}}">
@endsection

@section('main')

<!-- Main content -->
<div id="app" style="display: none">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2> テンプレート編集 </h2>
                            <div class="btn-action-main">
                                <a class="btn btn-popup" data-toggle="modal" data-target="#review-template">プレビュー</a>
                                <a class="btn btn-popup" data-toggle="modal" data-target="#send-mail">テスト送信</a>
                                <a class="btn btn-default btn-add" v-on:click.prevent="handleSave">保存</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="inner-body">
                                <div class="mail-edit-title mail-content"
                                    v-bind:class="errors.template_name ? 'has-error' : ''">
                                    <label for="mail-title">テンプレート名</label>
                                    <input type="text" id="mail-title" class="size-medium" name="name"
                                        v-model="dataSave.template_name">
                                    <div v-if="errors.template_name">
                                        <span v-for="error of errors.template_name" class="help-block"
                                            v-html="error"></span>
                                    </div>
                                </div>

                                <div class="mail-edit-wrap">
                                    <div id="intro_step3">
                                        <div class="mail-edit-switch mail-content">
                                            <h3>
                                                <i class="fas fa-feather-alt"></i>
                                                メールデザイン
                                                <span>「HTMLメール」が閲覧できない方向けに、「テキストメール」も必ず設定してください</span>
                                            </h3>
                                            <ul class="tabs clearfix">
                                                <li class="" v-bind:class="(tab == 'tab1')? 'active' : ''"
                                                    v-on:click.prevent="changeTab('tab1')">
                                                    <a class="tab_btn" id="tab01">HTMLメール</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- tab1  -->
                                        <div id="tab1" class="tab_content"
                                            v-bind:style="(tab == 'tab1')?  {display: 'block'} : ''">
                                            <div class="mail-edit-body mail-content">
                                                <a class="btn btn-gray size-small only-i paint js-bgcolor"
                                                    data-toggle="tooltip" title="Background color"><i
                                                        class="fas fa-tint"></i></a>
                                                <div class="mail-body-wrap clearfix"
                                                    style="border: 1px dashed rgb(190, 190, 190); background-color: rgb(255, 255, 255);">
                                                    <!--メール編集-->
                                                    <input type="hidden" name="body" id="mail_body">
                                                    <input type="hidden" name="preview_flg" id="mail_preview" value="0">
                                                    <input type="hidden" name="reflect_text" id="reflect_text"
                                                        value="0">
                                                    <input type="hidden" name="source_edit_flg" value="0"
                                                        id="source_edit">

                                                    <textarea name="mail_body_source" id="mail_body_source"
                                                        style="display:none;" v-model="dataSave.mail_content_html">
                                                    </textarea>
                                                    <div class="caution" style="display:none;">
                                                        <p>※DOCTYPE宣言からご入力ください。</p>
                                                    </div>
                                                    <div class="mail-wrap" id="mail_body_text" style="margin: auto;">
                                                        {{-- template-default --}}
                                                        @include('admin.mail-template.sub-item.template-default')
                                                        {{-- //template-default --}}
                                                    </div>
                                                    <!--//メール編集-->
                                                    <!--//メールエディタ-->
                                                    <!-- nav drog and drag -->

                                                    {{-- slot-wrap --}}
                                                    @include('admin.mail-template.sub-item.slot-wrap')
                                                    {{-- //slot-wrap --}}

                                                </div>
                                            </div>

                                        </div>
                                        <div id="tab2" class="tab_content"
                                            v-bind:style="(tab == 'tab2')?  {display: 'block'} : ''">
                                            <div class="mail-edit-body mail-content">
                                                <textarea id="form_text_body" class="text_body" name="text_body"
                                                    v-model="dataSave.mail_content_text">

                                                </textarea>
                                            </div>
                                            <!--//★テキストメール-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Param mail -->
                        @include('admin.mail-template.sub-item.param')
                        <!-- end Param mail -->

                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- table template slot email -->
        @include('admin.mail-template.sub-item.slot-template')
        <!-- end table template slot email -->
    </section>
    <!-- popup review template -->
    @include('admin.mail-template.sub-item.preview')
    <!-- popup review template -->
    <!-- popup send mail -->
    @include('admin.mail-template.sub-item.test-sendmail')
    <!-- popup send mail -->
</div>

@endsection

<!-- add libs, code, function js other -->
@section('libraryjs')
<script>
    let urlEdit            = "{{route('mail-template.viewEdit', ['id' => '?id?'])}}";
    let urlConvert         = "{{route('mail-template.convert')}}";
    let urlReview          = "{{route('mail-template.review')}}";
    let urlSaveProvisional = "{{route('mail-template.saveProvisional')}}";
    let urlSave            = "{{route('mail-template.save')}}";
    let urlSendMailTest    = "{{route('mail-template.send-mail-test')}}";
</script>
<!-- spectrum colorpicker -->
<script src="{{asset('adminLTE/plugins/spectrum/spectrum.js')}}"></script>
<script>
    "use strict";
//Bắt buộc khai báo để cho vuejs nhận biết chúng ta đang sử dụng thư viện thứ ba.
Vue.prototype.$axios = axios;
Vue.prototype.$jquery = $;
window.vue = new Vue({
    //Thành phần áp dụng
    el: "#app",
    //khai báo dữ liệu ban đầu
    data: {
        tab: "tab1",
        viewTemplate: "1",
        dataSave: {
            template_name: "【サンプル】テンプレート",
            mail_content_html: "",
            mail_content_text: `@include('admin.mail-template.sub-item.text-default')`,
            test_send_to: "",
            test_memo: "",
            send_type: 1,
        },
        idEditor: 3,
        dataDesign: "",
        param: {},
        errors: [],
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
         * Xử lý sự kiện navbar scroll
         */
        handleScroll() {
            //Xử lý scroll list element template
            let _this = this;
            let listElTemplate = _this.$jquery("#slot-wrap");
            let listVariable = _this.$jquery("#variable-tag");
            let heightNavTop = _this.$jquery(".main-header").outerHeight();
            let listElTemplateFirst = listElTemplate.offset().top;

            //set tọa độ và chiều cao variable lúc ban đầu
            listVariable.css("top", heightNavTop + "px");
            listVariable.css("height", "calc(100vh - " + heightNavTop + "px)");

            //Xử lý sự kiện khi scroll
            _this.$jquery(window).scroll(function () {
                let btnAction = _this.$jquery(".btn-action-main");
                let btnActionHeight = btnAction.outerHeight();
                //Kiểm tra khi scroll qua nav action
                if (
                    heightNavTop + btnActionHeight <=
                    _this.$jquery(window).scrollTop()
                ) {
                    btnAction.addClass("fixed");
                    listVariable.css("top", btnActionHeight + "px");
                    listVariable.css("height", "calc(100vh - " + btnActionHeight + "px)");
                } else {
                    btnAction.removeClass("fixed");
                    listVariable.css("top", heightNavTop + "px");
                    listVariable.css("height", "calc(100vh - " + heightNavTop + "px)");
                }
                if (_this.$jquery(window).scrollTop() < listElTemplateFirst) {
                    listElTemplate.css("top", 0 + "px");
                } else {
                    if (
                        _this.$jquery(window).scrollTop() >= listElTemplate.offset().top
                    ) {
                        listElTemplate.css(
                            "top",
                            _this.$jquery(window).scrollTop() -
                            listElTemplateFirst +
                            btnActionHeight +
                            "px"
                        );
                    } else if (listElTemplate.offset().top > listElTemplateFirst) {
                        listElTemplate.css(
                            "top",
                            _this.$jquery(window).scrollTop() -
                            listElTemplateFirst +
                            btnActionHeight +
                            "px"
                        );
                    }
                }
            });
        },
        /**
         * Xử lý sự kiện copy variable in variable list
         */
        handleVariableList() {
            let _this = this;
            //Event auto copy where click input for variable list
            let listInput = _this.$jquery("#variable-tag_list input");
            let notifyCopy = _this.$jquery("#variable-tag_notify");
            listInput.click(function () {
                let vInput = document.createElement("input");
                vInput.style = "position: absolute; left: -1000px; top: -1000px";
                vInput.value = _this.$jquery(this).val();
                document.body.appendChild(vInput);
                vInput.select();
                document.execCommand("copy");
                document.body.removeChild(vInput);
                notifyCopy.addClass("show");
                setTimeout(function () {
                    notifyCopy.removeClass("show");
                }, 1500);
            });

            _this.$jquery("#variable-tag_button").on("click", function (e) {
                e.preventDefault();
                let variableTag = _this.$jquery("#variable-tag");
                if (variableTag.hasClass("variable-tag_show")) {
                    variableTag.removeClass("variable-tag_show");
                } else {
                    variableTag.addClass("variable-tag_show");
                }
            });
        },

        /**
         * Xử lý sự kiện js chỉ gọi 1 lần
         */
        handleEventDragDrop() {
            let _this = this;
            //Cấu hình cho chức năng chọn màu nền
            let optionColorpickerDefault = {
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
                move: function (color) { },
                show: function () { },
                beforeShow: function () {
                    let slotSort = _this.$jquery(this).parents(".slot_sort");
                    let color = slotSort.css("background-color");
                    if (slotSort.length > 0) {
                        if (color != "rgba(0, 0, 0, 0)") {
                            _this.$jquery(this).spectrum("set", color);
                        } else {
                            _this.$jquery(this).spectrum("set", "#ffffff");
                        }
                    } else {
                        let slotBox = _this.$jquery(this).parents(".slot-box");
                        let colorBox = slotBox.css("background-color");
                        if (slotBox.length > 0) {
                            if (colorBox != "rgba(0, 0, 0, 0)") {
                                _this.$jquery(this).spectrum("set", colorBox);
                            } else {
                                _this.$jquery(this).spectrum("set", "#ffffff");
                            }
                        }
                    }
                },
                hide: function (color) { },
                change: function (color) {
                    let $el = $(this).parents(".slot_sort");
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
            //Option xử lý khi kéo thả slot
            let drOptions = {
                accept: "li.js-add-slot",
                tolerance: "pointer",
                drop: function (e, ui) {
                    let parent = _this.$jquery(e.target);
                    let prefix = "slot_";
                    //lấy phần tử được thả vào
                    let slot = parent.find(".js-add-slot");
                    //tạo id cho slot
                    let slotAll = _this.$jquery(e.target).find("slot_sort");
                    createdSlot(prefix, slot, slotAll);
                },
            };
            //Option xử lý khi kéo thả slot trong header và footer
            let drOptionsHeadAndFooter = {
                accept: "li.js-add-slot",
                tolerance: "pointer",
                drop: function (e, ui) {
                    let parent = _this.$jquery(e.target).closest("tbody");
                    let prefix = "slot_head_";
                    if (parent.parents("#mail-foot").length > 0) {
                        prefix = "slot_foot_";
                    }
                    let slot = parent.find(">tr:first-child");
                    let slotAll = parent.find("slot_sort");
                    createdSlot(prefix, slot, slotAll, false, ui);
                },
            };

            //kiểm tra slot
            // chưa có thì hiển thị phần tử mặc định, ngược lại ẩn đi
            function changeMainSlotSort() {
                //head mail template
                if (_this.$jquery("#mail-head .slot_sort").length <= 0) {
                    _this.$jquery("#mail-head-end").show();
                } else {
                    _this.$jquery("#mail-head-end").hide();
                }
                //body mail template
                if (_this.$jquery("#mail-body .slot_sort").length <= 0) {
                    _this.$jquery("#main_slot_end").show();
                } else {
                    _this.$jquery("#main_slot_end").hide();
                }
                //footer mail template
                if (_this.$jquery("#mail-foot .slot_sort").length <= 0) {
                    _this.$jquery("#mail-foot-end").show();
                } else {
                    _this.$jquery("#mail-foot-end").hide();
                }
            }
            //handel change color body
            function changeColor(color) {
                _this.$jquery(".mail-body-wrap").css("background-color", color);
                _this.$jquery(".container").attr("bgcolor", color);
                _this
                    .$jquery(".container-padding")
                    .attr("bgcolor", color)
                    .css("background-color", color);
                if (!_this.$jquery("#form_body_bgcolor").length) {
                    _this
                        .$jquery("<input>", {
                            type: "hidden",
                            id: "form_body_bgcolor",
                            name: "layout_genre_id",
                            value: color,
                        })
                        .appendTo("#form_layout");
                } else {
                    _this.$jquery("#form_body_bgcolor").val(color);
                }
            }
            /**
             * Function khởi tạo sự kiện trong slot (copy slot, khóa, edit)
             * @param object parent: đối tượng cần setup sự kiện cho các button chức năng. Mặc định ánh dụng cho đối tượng main khi load page lần đầu
             */
            function slotEvent(parent = _this.$jquery(".mail-edit-body")) {
                //remove event
                // _this.$jquery(".del, .copy, .edit-btn a, .btn.lock").unbind();
                //Sự kiện delete slot
                parent.find(".del").click(function (e) {
                    let elementDelete = _this.$jquery(this).closest(".slot_sort");
                    _this.$jquery(this).tooltip("dispose");
                    elementDelete.remove();
                    changeMainSlotSort();
                });
                //Sự kiện edit content slot
                parent.find(".edit-btn a").click(function (e) {
                    let id = _this.$jquery(this).data("ideditor");
                    loadEdit(id);
                });
                //Sự kiện copy slot
                parent.find(".copy").click(function (e) {
                    let parent = _this.$jquery(this).parents(".slot_sort");
                    let clone = parent.clone();
                    parent.after(clone);
                    let elEdit = _this.$jquery(clone.find(".edit-wrap"));
                    let elEditLength = elEdit.length;
                    if (elEditLength > 0) {
                        for (let index = 0; index < elEditLength; index++) {
                            _this.idEditor++;
                            let id = "editor" + _this.idEditor;
                            let editWrap = _this.$jquery(elEdit[index]);
                            editWrap.find(".edit-box").attr("id", id);
                            editWrap.find(".edit-btn a").attr("data-ideditor", id);
                        }
                    }
                    _this
                        .$jquery(clone.find(".paint"))
                        .spectrum(optionColorpickerDefault);
                    slotEvent(clone);
                });
                //Sự kiện khóa slot
                parent.find(".btn.lock").on("click", function (e) {
                    let btn = _this.$jquery(this);
                    let parent = btn.parents(".slot_sort");
                    if (parent.hasClass("lock")) {
                        parent.removeClass("lock");
                    } else {
                        parent.addClass("lock");
                    }
                });
                parent.find('[data-toggle="tooltip"]').tooltip("dispose");
                parent.find('[data-toggle="tooltip"]').tooltip();
            }

            /**
             * Function tạo slot
             *
             * @param string tiếp đầu ngữ cho đối tượng mới
             * @param object khai báo đối tượng đứng trước đối tượng mới
             * @param boolean khu vực đối tượng muốn khởi tạo có bải là body không
             * @param object đối tượng ui của jquery ui (Sử dụng để tạo các đối tượng trong khu vực không phải body)
             */
            function createdSlot(prefix, slot, slotAll, createBoy = true, ui = null) {
                let maxID = 1;
                let idSlot = prefix + maxID;
                let lengthSlotAll = slotAll.length;
                if (lengthSlotAll > 0) {
                    for (let index = 0; index < lengthSlotAll; index++) {
                        let id = parseInt(
                            _this.$jquery(slotAll[index]).attr("id").split("_")[1]
                        );
                        if (maxID <= id) {
                            maxID = id + 1;
                        }
                    }
                }
                //lấy id của layout slot muốn tạo
                let idTemplate = "";
                if (createBoy) {
                    idTemplate = slot.data("template");
                } else {
                    idTemplate = _this.$jquery(ui.draggable).data("template");
                }
                //phần tử chứa mẫu
                let libTemplate = _this.$jquery("#table-lib-template-slot");
                //tìm và sao chép mẫu
                let template = libTemplate.find("#" + idTemplate).clone();
                template.attr("id", idSlot);
                //thêm mẫu vào sau slot
                slot.after(template);
                //xóa slot
                slot.remove();
                // gắn ID nhận diện cho các chức năng trong slot
                let elEdit = _this.$jquery(template.find(".edit-wrap"));
                let elEditLength = elEdit.length;
                if (elEditLength > 0) {
                    for (let index = 0; index < elEditLength; index++) {
                        _this.idEditor++;
                        let id = "editor" + _this.idEditor;
                        let editWrap = _this.$jquery(elEdit[index]);
                        editWrap.find(".edit-box").attr("id", id);
                        editWrap.find(".edit-btn a").attr("data-ideditor", id);
                    }
                }
                //khởi tạo sự kiện cho các button trong slot
                slotEvent(template);
                //Khởi tạo sự kiện chọn màu background
                _this
                    .$jquery(template.find(".paint"))
                    .spectrum(optionColorpickerDefault);
                //tracking solot
                changeMainSlotSort();
            }

            //Xử lý sự kiện show/hide editor
            function loadEdit(id) {
                _this.$jquery("#" + id).attr("contenteditable", true);
                // Kiểm tra nếu đã tồn tại id trong CKEDITOR thì remove để tránh trùng sự kiện
                let editor = CKEDITOR.instances[id];
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
                    _this
                        .$jquery("#" + id)
                        .parent()
                        .find(".edit-btn")
                        .attr("style", "display: none !important;");
                    _this
                        .$jquery("#" + id)
                        .closest(".slot_sort")
                        .find(".over-btn")
                        .attr("style", "display: none !important;");
                });

                // Sự kiện out focus
                editor.on("blur", function (event) {
                    hideEditor(event, id);
                    _this
                        .$jquery("#" + id)
                        .parent()
                        .find(".edit-btn")
                        .css("display", "none");
                    _this
                        .$jquery("#" + id)
                        .closest(".slot_sort")
                        .find(".over-btn")
                        .attr("style", "display: none");
                    setTimeout(function () {
                        CKEDITOR.instances[id].destroy();
                    }, 0);
                });
            }

            // Xử lý show editor
            function showEditor(event, id) {
                let el = _this.$jquery("#" + id);
                el.parents(".slot-box")
                    .find(".over-btn,.edit-btn")
                    .css("display", "none");
                el.parents(".edit-wrap, .active")
                    .unbind("mouseenter")
                    .unbind("mouseleave");
                el.removeAttr("title");
                _this.$jquery("#mail-body > tbody").sortable("disable");
            }

            // Xử lý sự kiện thi ẩn editor
            function hideEditor(event, id) {
                // maskOverContents();
                _this.$jquery("#mail-body > tbody").sortable("enable");
                _this.$jquery("#" + id).removeAttr("contenteditable");
            }

            //sự kiện tạo phần tử (Kéo thả phần tử từ taskbar mẫu)
            _this.$jquery(".slot-list li").draggable({
                connectToSortable: "#mail-body > tbody",
                helper: "clone",
                revertDuration: 200,
                opacity: 0.8,
                scroll: true,
                appendTo: "#mail-body > tbody:first-child",
                start: function () {
                    _this
                        .$jquery("#add-slot,#add-slot-head,#add-slot-foot")
                        .css("background-color", "#f7f7f7");
                },
                stop: function () {
                    _this
                        .$jquery("#add-slot,#add-slot-head,#add-slot-foot")
                        .css("background-color", "");
                },
                revert: "invalid",
            });

            //sự kiện kéo thả sắp xếp các slot trong khu vực body
            _this.$jquery("#mail-body > tbody").sortable({
                helper: "clone",
                connecHeight: ".slot_sort",
                placeholder: "ui-state-highlight",
                //edit style khi nhấn vào phần tử
                sort: function (event, ui) {
                    let $target = _this.$jquery(event.target);
                    if (!/html|body/i.test($target.offsetParent()[0].tagName)) {
                        let top =
                            event.pageY -
                            $target.offsetParent().offset().top -
                            ui.helper.outerHeight(true) / 2;
                        ui.helper.css({ top: top + "px" });
                    }
                },
                //xử lý khi có phần tử mới
                receive: function (event, ui) {
                    drOptions.drop(event, ui);
                },
            });

            //sự kiện kéo thả lúc chưa có slot nào được tạo
            _this.$jquery("#main_slot_end").droppable(drOptions);
            _this
                .$jquery("#add-slot-head, #add-slot-foot")
                .droppable(drOptionsHeadAndFooter);

            // Khởi tạo sự kiện chọn background color cho các phần tử ban đầu
            _this.$jquery(".paint").spectrum(optionColorpickerDefault);
            //Sự kiện chọn background-color global
            _this.$jquery(".js-bgcolor").spectrum({
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
                move: function (color) { },
                show: function () { },
                beforeShow: function () {
                    let color = _this
                        .$jquery(this)
                        .parent()
                        .find("#mail_body_text table.container")
                        .attr("bgcolor");
                    _this.$jquery(this).spectrum("set", color);
                },
                hide: function (color) { },
                change: function (color) {
                    changeColor(color.toHexString());
                },
            });

            //Popup review template
            _this.$jquery("#review-template").on("show.bs.modal", function (e) {
                _this.handleSaveProvisional();
            });
            slotEvent();
        },
        /**
         * Handle param template
         * Xử lý các dữ liệu test cho template
         */
        getTestParam() {
            let input = this.$jquery("#form_test_mail").serializeArray();
            for (let key in input) {
                let data = input[key] || {};
                if (data) {
                    this.param[data.name] = data.value;
                }
            }

            if (
                !this.param.send_type &&
                this.$jquery("input[name=body_type]:checked").length
            ) {
                this.param.send_type = this.$jquery(
                    "input[name=body_type]:checked"
                ).val();

                if (getEditType() === "text_edit") {
                    this.param.send_type = 1;
                }
            }

            let easyEditFlg = (Number(this.$jquery("#source_edit").val()) || 0) === 0;
            this.param.easy_edit_flg = Number(easyEditFlg || false);

            this.param.mail_content_html = this.$jquery("#mail_body_text").html();

            let responsiveFlg = true;
            if (this.$jquery("input[name=responsive]:checked").length) {
                responsiveFlg = Boolean(
                    Number(this.$jquery("input[name=responsive]:checked").val())
                );
            }
            this.param.responsive_flg = Number(responsiveFlg);

            this.param.bgcolor = this.$jquery("#form_body_bgcolor").val() || "";

            return this.param;
        },
        /**
         * Convert from desgin in html
         */
        handleConvert(callBack = "") {
            let _this = this;
            let params = _this.getTestParam();
            _this.$axios
                .post(urlConvert, params)
                .then((response) => {
                    if (response.status == 200) {
                        _this.dataSave.mail_content_html = response.data;
                        if(callBack != ""){
                            callBack();
                        }
                    } else {
                        console.error(response);
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        /**
         * Handle save provisional design template
         */
        handleSaveProvisional() {
            let _this = this;
            let params = this.getTestParam();
            loading.show();
            this.$axios
                .post(urlSaveProvisional, params)
                .then((response) => {
                    if (response.status != 200) {
                        console.error(response);
                    }
                    _this.$jquery(".review-template-tab_content_item iframe").html("");
                    _this.$jquery(".review-template-tab_content_item iframe").attr("src", urlReview);
                    loading.hide();
                })
                .catch((error) => {
                    console.error(error);
                    loading.hide();
                });
        },
        /**
         * Handle save
         */
        handleSave() {
            //handle data save
            let _this = this;
            _this.errors = [];
            loading.show();
            //Bởi vì handleConvert là bất đồng bộ cho nên phải đợi handleConver chạy xong mới gọi các phần khác
            _this.handleConvert(save);
            function save(){
            _this.$axios
                .post(urlSave, _this.dataSave)
                .then((response) => {
                    if (response.status == 200) {
                        let data = response.data;
                        if (data.status == 0) {
                            window.location.href = urlEdit.replace("?id?", data.id);
                        } else {
                            if (data.message != "") {
                                msgCustom("error", data.message, 2000);
                            }
                        }
                    } else {
                        console.error(response);
                    }
                    loading.hide();
                })
                .catch((error) => {
                    console.error(error);
                    loading.hide();
                });
            }
        },

        onSubmitTestSend() {
            let _this = this;
            _this.errors = [];
            loading.show();
            //Bởi vì handleConvert là bất đồng bộ cho nên phải đợi handleConver chạy xong mới gọi các phần khác
            _this.handleConvert(send);
            function send(){
                _this.$axios
                    .post(urlSendMailTest, _this.dataSave)
                    .then((response) => {
                        if (response.status == 200) {
                            console.log(response.data);
                            let data = response.data;
                            if (data.status == 1) {
                                msgCustom("success", data.message, 2000, "top-end");
                                _this.$jquery("#send-mail").modal("hide");
                            } else {
                                msgCustom("error", data.message, 2000, "top-end");
                            }
                        } else {
                            console.error(response);
                        }
                        _this.dataSave.test_send_to = "";
                        _this.dataSave.test_memo = "";
                        loading.hide();
                    })
                    .catch((error) => {
                        console.error(error);
                        loading.hide();
                    });
            }
        },
    },
    //Sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
    //Chỉ cho những sự kiện không truyền tham số
    computed: {},
    // Xảy ra trước khi khởi tạo, data và event được khai báo chưa tự thay đổi khi cập nhật
    beforeCreate() { },
    //Khởi tạo giống như __contructor của react, diễn ra trước quá trình tạo DOM
    created() { },
    // Trước khi có đầy đủ quyền truy cập vào phần tử DOM
    beforeMount() { },
    // mounted khi đã có đầy đủ quyền truy cập vào phần tử DOM
    mounted() {
        this.$jquery("#app").show();
        this.handleScroll();
        this.handleVariableList();
        this.handleEventDragDrop();
    },
    //xử lý trước khi dữ liệu bị thay đổi
    beforeUpdate() { },
    //Xử lý khi dữ liệu đã thay đổi
    updated() { },
    //Xử lý trước khi hủy đối tượng
    beforeDestroy() {
        //Làm sạch instance vue
        delete this.$axios;
        delete this.$jquery;
        delete this.data;
    },
    //Xử lý khi hủy đối tượng
    destroyed() { },
});
</script>
@endsection
