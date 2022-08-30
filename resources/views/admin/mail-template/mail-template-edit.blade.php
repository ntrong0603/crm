@extends('admin.layout')
@section('titlePage', 'テンプレート編集')
{{-- add libs, code css other  --}}
@section('stylecss')
{{-- spectrum colorpicker  --}}
<link rel="stylesheet" type="text/css" href="{{asset('adminLTE/plugins/spectrum/spectrum.css')}}">

@endsection

@section('main')

{{-- Main content  --}}
<div id="app" style="display: none">
    <section class="content template-form">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2> テンプレート編集 </h2>
                            <div class="btn-action-main">
                                @if (!empty($editSchedule))
                                <div class="back-scenario">
                                    <a class="btn"
                                        href="{{route('scenario.viewEdit', ['id' => $schedule->mail_setting_id])}}">←
                                        シナリオメール設定</a>
                                </div>
                                <div style="display: inline-block;">
                                    <input type="checkbox" name="save_design" id="save_design"
                                        v-model="dataSave.save_design">
                                    <label for="save_design">このデザインをテンプレートとしても保存</label>
                                </div>
                                @endif
                                <a class="btn btn-popup" data-toggle="modal" data-target="#review-template">プレビュー</a>
                                <a class="btn btn-popup" data-toggle="modal" data-target="#send-mail">テスト送信</a>
                                <a class="btn btn-default btn-add" v-on:click.prevent="handleSave">保存</a>
                            </div>
                        </div>
                        {{-- /.card-header  --}}
                        <div class="card-body">
                            @if (!empty($editSchedule))
                            <div class="mailset-info">
                                <div class="mailset-status">
                                    <p class="mailset-name">
                                        <em class="status-action" v-if="dataSave.schedule_action == 1">有効中</em>
                                        <em class="status-blocked" v-if="dataSave.schedule_action == 0">停止中</em>
                                        スケジュール名：
                                        @{{dataSave.schedule_name}}
                                    </p>
                                </div>
                            </div>
                            @endif
                            <div class="inner-body">
                                <input type="hidden" id="mail-title" class="size-medium" name="mail_template_id"
                                    v-model="dataSave.mail_template_id">
                                <input type="hidden" id="mail-title" class="size-medium" name="schedule_id"
                                    v-model="dataSave.schedule_id">
                                @if (empty($editSchedule))
                                <div class="mail-edit-title mail-content"
                                    v-bind:class="errors.template_name ? 'has-error' : ''">
                                    <label for="mail-title">テンプレート名</label>
                                    <input type="text" id="mail-title" class="size-medium" name="template_name"
                                        v-model="dataSave.template_name">
                                    <div v-if="errors.template_name">
                                        <span v-for="error of errors.template_name" class="help-block"
                                            v-html="error"></span>
                                    </div>
                                </div>
                                @else
                                <input type="hidden" id="mail-title" class="size-medium" name="mail_template_id"
                                    v-model="dataSave.schedule_id">
                                <div class="mail-edit-title mail-content"
                                    v-bind:class="errors.subject ? 'has-error' : ''">
                                    <label for="mail-title">メール件名<span class="required-icon">必須</span></label>
                                    <input type="text" id="mail-title" class="size-medium" name="subject"
                                        v-model="dataSave.subject">
                                    <div v-if="errors.subject">
                                        <span v-for="error of errors.subject" class="help-block" v-html="error"></span>
                                    </div>
                                </div>
                                @endif

                                <div class="mail-edit-wrap">
                                    <div id="intro_step3">
                                        <div class="mail-edit-switch mail-content">
                                            @if (!empty($editSchedule))
                                            @include('admin.mail-template.sub-item.schedule-form')
                                            @endif
                                            <h3>
                                                <i class="fas fa-feather-alt"></i>
                                                メールデザイン
                                                <span>「HTMLメール」が閲覧できない方向けに、「テキストメール」も必ず設定してください</span>
                                                @if (!empty($editSchedule))
                                                {{-- button show list change template --}}
                                                <a href="javascript:void()" class="btn btn-yellow btn-change-template"
                                                    data-toggle="modal" data-target="#list-template">テンプレート選択</a>
                                                @endif
                                            </h3>
                                            <ul class="tabs clearfix">
                                                <li class="" v-bind:class="(tab == 'tab1')? 'active' : ''"
                                                    v-on:click.prevent="changeTab('tab1')">
                                                    <a class="tab_btn" id="tab01">HTMLメール</a>
                                                </li>
                                                {{-- <li class="" v-bind:class="(tab == 'tab2')? 'active' : ''"
                                                    v-on:click.prevent="changeTab('tab2')">
                                                    <a class="tab_btn" id="tab02">テキストメール</a>
                                                </li> --}}
                                            </ul>
                                        </div>
                                        {{-- tab1   --}}
                                        <div id="tab1" class="tab_content"
                                            v-bind:style="(tab == 'tab1')?  {display: 'block'} : ''">
                                            <div class="mail-edit-body mail-content">
                                                {{-- <div class="edit-type-switch">
                                                    <p><a href="javascript:void(0);"
                                                            id="source_edit_mode">→HTML編集モードで作成する</a></p>
                                                    <p><a href="javascript:void(0);" id="easy_edit_mode"
                                                            style="display:none;">→かんたん編集モードで作成する</a></p>
                                                </div> --}}
                                                <a class="btn btn-gray size-small only-i paint js-bgcolor"
                                                    data-toggle="tooltip" title="Background color"><i
                                                        class="fas fa-tint"></i></a>
                                                <div class="mail-body-wrap clearfix"
                                                    style="border: 1px dashed rgb(190, 190, 190); background-color: rgb(255, 255, 255);">
                                                    {{--メール編集 --}}

                                                    <textarea name="mail_body_source" id="mail_body_source"
                                                        style="display:none;" v-model="dataSave.mail_content_html">
                                                    </textarea>
                                                    <div class="caution" style="display:none;">
                                                        <p>※DOCTYPE宣言からご入力ください。</p>
                                                    </div>
                                                    <div class="mail-wrap" id="mail_body_text" style="margin: auto;">
                                                        {{-- template-default --}}
                                                        @if (!empty($template['body']))
                                                        {!!$template['body']!!}
                                                        @else
                                                        @include('admin.mail-template.sub-item.template-default')
                                                        @endif
                                                        {{-- //template-default --}}
                                                    </div>
                                                    {{--//メール編集 --}}
                                                    {{--//メールエディタ --}}
                                                    {{-- nav drog and drag  --}}

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
                                            {{--//★テキストメール --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Param mail  --}}
                        @include('admin.mail-template.sub-item.param')
                        {{-- end Param mail  --}}

                    </div>
                    {{-- /.card-body  --}}
                </div>
            </div>
            {{-- /.col  --}}
        </div>
        {{-- /.row  --}}
        {{-- table template slot email  --}}
        @include('admin.mail-template.sub-item.slot-template')
        {{-- end table template slot email  --}}
    </section>
    {{-- popup review template  --}}
    @include('admin.mail-template.sub-item.preview')
    {{-- popup review template  --}}
    {{-- popup send mail  --}}
    @include('admin.mail-template.sub-item.test-sendmail')
    {{-- popup send mail  --}}
    {{-- popup change template --}}
    @if (!empty($editSchedule))
    @include('admin.mail-template.sub-item.change-template')
    @endif
</div>

@endsection

{{-- add libs, code, function js other  --}}
@section('libraryjs')
<script>
    @if(!empty($editSchedule))
    var urlEdit            = "{{route('schedule.viewEdit', ['id' => '?id?'])}}";
    var urlSave            = "{{route('schedule.save')}}";
    @else
    var urlEdit            = "{{route('mail-template.viewEdit', ['id' => '?id?'])}}";
    var urlSave            = "{{route('mail-template.save')}}";
    @endif
    var urlConvert         = "{{route('mail-template.convert')}}";
    var urlReview          = "{{route('mail-template.review')}}";
    var urlSaveProvisional = "{{route('mail-template.saveProvisional')}}";
    var urlGetTemplate     = "{{route('mail-template.getTemplate')}}";
    var urlSendMailTest    = "{{route('mail-template.send-mail-test')}}";
</script>
{{-- spectrum colorpicker  --}}
<script src="{{asset('adminLTE/plugins/spectrum/spectrum.js')}}"></script>

<script>
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
    beforeShow: function () {
        var slotSort = $(this).parents(".slot_sort");
        var color = slotSort.css("background-color");
        console.log();
        if(slotSort.length > 0){
            if(color != 'rgba(0, 0, 0, 0)'){
                $(this).spectrum("set", color);
            }else{
                $(this).spectrum("set", '#ffffff');
            }
        }else{
            var slotBox = $(this).parents(".slot-box");
            var colorBox = slotBox.css("background-color");
            if(slotBox.length > 0){
                if(colorBox != 'rgba(0, 0, 0, 0)'){
                    $(this).spectrum("set", colorBox);
                }else{
                    $(this).spectrum("set", '#ffffff');
                }
            }
        }
    },
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
</script>

<script>
    var dataNotHTML = `@include('admin.mail-template.sub-item.text-default')`;

window.vue = new Vue({
    //Thành phần áp dụng
    el: "#app",
    //khai báo dữ liệu ban đầu
    data: {
        tab: "tab1",
        viewTemplate: "1",
        dataSave: {
            mail_template_id: "{{$template['mail_template_id'] ?? ''}}",
            template_name: "{{$template['template_name'] ?? ''}}",
            mail_content_html: `{{$template['mail_content_html'] ?? ''}}`,
            mail_content_text: @if(!empty($template['mail_content_text'])) `{{$template['mail_content_text']}}` @else dataNotHTML @endif,
            is_protected: {{$template['is_protected'] ?? 0}},
            test_send_to: "",
            test_memo: "",
            send_type: 1,
            //infor schedule
            schedule_id: "{{$schedule['schedule_id'] ?? ''}}",
            schedule_name: "{{$schedule['schedule_name'] ?? ''}}",
            save_design: false,
            schedule_action: "{{$schedule['schedule_action'] ?? 0}}",
            subject: "{{$schedule['subject'] ?? ''}}",
            mail_from_name: "{{$schedule['mail_from_name'] ?? ''}}",
            mail_from: "{{$schedule['mail_from'] ?? ''}}",
            mail_template_option: {{$schedule['mail_template_option'] ?? 1 }},
            link_unsubscribe: {{$schedule['link_unsubscribe'] ?? 1}},
        },
        keyTemplate: "{{$template['key'] ?? ''}}",
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
         * Event tootip
        */
        tooltip(){
            $('[data-toggle="tooltip"]').tooltip('dispose');
            $('[data-toggle="tooltip"]').tooltip();
        },
        /**
         * Tracking lock element
         * Remove element edit with condition parent is lock
        */
        handleTrackingLock(){
            // set template only
            if(this.keyTemplate != ''){
                var slotOnly = $("#mail-body .slot_only")[0];
                var editor = $($(slotOnly).find(".edit-box")[0]);
                var contentSlot = $($("#table-lib-template-slot .slot_" + this.keyTemplate +" .edit-box")[0]).html();
                editor.html(contentSlot);
            }

            var listLock = $(".slot_sort.lock");
            for(var index = 0; index < listLock.length; index++){
                var el = $(listLock[index]);
                el.find(".btn.lock").remove();
                el.find(".btn.copy").remove();
                el.find(".btn.del").remove();
                el.find(".edit-btn").remove();
            }

        },
        //handel change color body
        changeColor(color) {
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
        },
        /**
         * Xử lý sự kiện js chỉ gọi 1 lần
         */
        handleCallOnlyOne() {
            var _this = this;
            _this.handleResizeWidth();
            $(window).resize(function () {
                _this.handleResizeWidth();
            });
            _this.handleResizeWidth();
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
            //change color background
            var bkgColor = $('#mail_body_text table.container').attr("bgcolor");
            $(".mail-body-wrap").css("background-color", bkgColor);

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
                //notify thông báo đã copy text
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

            $("#review-template").on("show.bs.modal", function (e) {
                _this.handleSaveProvisional();
                $(".review-template-tab_content_item iframe").attr(
                    "src",
                    urlReview
                );
            });

            $(".js-change-template").on("click", function (e) {
                var btn = $(this);
                var id = btn.data("id-tempalte");
                _this.handleChangeBodyTemplate(id);
                $("#list-template").modal("hide");
            });
            _this.handleDropDrag();
        },
        /**
         * Xử lý ẩn các button edit khi template có is_protected = 1
        */
        handleHiddenBtn()  {
            var tabHTML = $("#tab1");
            if(this.dataSave.is_protected == 1 && !tabHTML.hasClass("hidden-all-btn")){
                tabHTML.addClass("hidden-all-btn");
            }else if(this.dataSave.is_protected == 0){
                tabHTML.removeClass("hidden-all-btn");
            }
        },
        /**
         * resize width group action
        */
        handleResizeWidth(){
            var width = $(".template-form").outerWidth();
            $(".btn-action-main").css("width", width + "px");
        },
        /**
         * Event drop & drag
        */
        handleDropDrag(){
            var _this = this;
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
            function slotEvent() {
                //remove event
                $(".del, .copy, .edit-btn a, .btn.lock").unbind();
                $(".del").click(function (e) {
                    var elementDelete = $(this).closest(".slot_sort");
                    // var tbody = elementDelete.closest("tbody");
                    $(this).tooltip('dispose');
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
                    var elEdit = $(clone).find(".edit-wrap");
                    var elEditLength = elEdit.length;
                    if (elEditLength > 0) {
                        for (var index = 0; index < elEditLength; index++) {
                            _this.idEditor++;
                            var id = "editor" + _this.idEditor;
                            var editWrap = $(elEdit[index]);
                            editWrap.find(".edit-box").attr("id", id);
                            editWrap
                                .find(".edit-btn a")
                                .attr("data-ideditor", id);
                        }
                    }
                    parent.after(clone);
                    slotEvent();
                });
                $(".btn.lock").on("click", function (e) {
                    var btn = $(this);
                    var parent = btn.parents(".slot_sort");
                    if(parent.hasClass("lock")){
                        parent.removeClass("lock");
                    } else {
                        parent.addClass("lock");
                    }
                });
                _this.tooltip();
            }

            slotEvent();
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
                    _this.tooltip();
                },
                revert: "invalid",
            });
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
            // $("#mail-body > tbody").disableSelection();

            //Xử lý khi kéo thả slot
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
                            _this.idEditor++;
                            var id = "editor" + _this.idEditor;
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
                    $("#" + id)
                        .closest(".slot_sort")
                        .find(".over-btn")
                        .attr("style", "display: none !important;");
                });

                // Sự kiện out focus
                editor.on("blur", function (event) {
                    hideEditor(event, id);
                    $("#" + id)
                        .parent()
                        .find(".edit-btn")
                        .css("display", "none");
                    $("#" + id)
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

            // Xử lý sự kiện thi ẩn editor
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
                            _this.idEditor++;
                            var id = "editor" + _this.idEditor;
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
                beforeShow: function () {
                    var color = $(this).parent().find('#mail_body_text table.container').attr("bgcolor");
                    $(this).spectrum("set", color);
                },
                hide: function (color) {},
                change: function (color) {
                    _this.changeColor(color.toHexString());
                },
            });
        },
        /**
         * Convert from desgin in html
        */
        handleConvert(isSave = false){
            var _this = this;
            var params = _this.getTestParam(isSave);
            $.ajax({
                type: "post",
                url: urlConvert,
                data: params,
                async: false,
                success: function (data) {
                    _this.dataSave.mail_content_html = data;
                },
                error: function (error) {
                    var res = $.parseJSON(e.responseText);
                    console.error(res.error);
                    loading.hide();
                },
            });
        },
        /**
         * Handle param template
         */
        getTestParam(isSave = false) {
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
            //clone data design
            // if
            var cloneDesign = $("#mail_body_text").clone();
            var slotOnly = cloneDesign.find(".slot_only");
            if(isSave && slotOnly.length != 0){
                var elConten = $(slotOnly[0]).find(".edit-box")[0];
                $(elConten).html("[data-body]");
            }
            this.param.mail_content_html = cloneDesign.html();

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
            loading.show();
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
        // handleChangeTabReview() {
        //     if (this.viewTemplate == 1) {
        //         this.viewTemplate = 2;
        //     } else {
        //         this.viewTemplate = 1;
        //     }
        // },
        /**
        * Handle save
        */
        handleSave(){
            //handle data save
            var _this = this;
            _this.errors = [];
            loading.show();
            _this.handleConvert(true);
            $.ajax({
                type: "post",
                url: urlSave,
                data: _this.dataSave,
                success: function (result) {
                    if(result.status == 0){
                        msgCustom('success', result.message, 1500);
                        window.location.href = urlEdit.replace('?id?', result.id);
                    }else{
                        if(result.message != ''){
                            msgCustom('error', result.message, 2000);
                        }
                    }
                    loading.hide();
                },
                error: function (e) {
                    var res = $.parseJSON(e.responseText);
                    _this.errors = res.errors;
                    if(res.errors.mail_template_id){
                        var message = "";
                        for(var index=0; index < res.errors.mail_template_id.length; index++){
                            message += res.errors.mail_template_id[index];
                            if((index + 1) < res.errors.mail_template_id.length){
                                message += "<br>";
                            }
                        }
                        msgCustom('error', message, 2000);
                    }
                    loading.hide();
                },
            });
        },
        onSubmitTestSend() {
            var _this = this;
            _this.errors = [];
            loading.show();
            _this.handleConvert();
            $.ajax({
                type: "post",
                url: urlSendMailTest,
                data: _this.dataSave,
                success: function (result) {
                    if (result.status == 1) {
                        msgCustom('success', result.message, 2000, 'top-end');
                        $("#send-mail").modal('hide');
                    } else {
                        msgCustom('error', result.message, 2000, 'top-end');
                    }
                    _this.dataSave.test_send_to = '';
                    _this.dataSave.test_memo    = '';
                    loading.hide();
                },
                error: function (e) {
                    var res = $.parseJSON(e.responseText);
                    _this.errors = res.errors;
                    loading.hide();
                },
            });
        },
        /**
         * Handle calc id edit for element
         * Dùng để tính id của các phần tử được edit
        */
        handleCalcIDEditor(){
            var _this = this;
            var listEl = $("*[id^='editor']");
            for(var index = 0; index < listEl.length; index++)
            {
                var id = $(listEl[index]).attr('id').replace('editor', '');
                if(parseInt(id) > _this.idEditor) {
                    _this.idEditor = parseInt(id);
                }
            }
            // _this.idEditor
        },
        /**
         * Change body model template
        */
        handleChangeBodyTemplate(id){
            //#mail_body_text
            var _this = this;
            _this.dataSave.mail_template_id = id;
            //Get new infor mail tempalte
            $.ajax({
                url: urlGetTemplate,
                type: "GET",
                data: {
                    idTemplate: id
                },
                success: function(result) {
                    if(result.error == 1){
                        msgCustom('error', result.message);
                    } else {
                        _this.dataSave.mail_template_id  = result.mail_template_id;
                        _this.dataSave.template_name     = result.template_name;
                        _this.dataSave.mail_content_html = result.mail_content_html;
                        _this.dataSave.mail_content_text = result.mail_content_text;
                        _this.keyTemplate                = result.key;
                        _this.dataSave.is_protected      = result.is_protected;
                        _this.idEditor                   = 3;
                        $("#mail_body_text").html(result.body);
                        _this.handleDropDrag();
                        _this.handleCalcIDEditor();
                        _this.handleTrackingLock();
                        _this.handleHiddenBtn();
                    }
                },
                error: function(e){
                    console.error(e);
                }
            });
        },
    },
    //Sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
    //Chỉ cho những sự kiện không truyền tham số
    computed: {},
    // Xảy ra trước khi khởi tạo, data và event được khai báo chưa tự thay đổi khi cập nhật
    beforeCreate() {
    },
    //Khởi tạo giống như __contructor của react, diễn ra trước quá trình tạo DOM
    created() {
    },
    // Trước khi có đầy đủ quyền truy cập vào phần tử DOM
    beforeMount() {
    },
    // mounted khi đã có đầy đủ quyền truy cập vào phần tử DOM
    mounted() {
        $("#app").show();
        this.handleCallOnlyOne();
        this.handleCalcIDEditor();
        this.handleTrackingLock();
        this.handleHiddenBtn();
    },
    //xử lý trước khi dữ liệu bị thay đổi
    beforeUpdate() {
    },
    //Xử lý khi dữ liệu đã thay đổi
    updated() {},
    //Xử lý trước khi hủy đối tượng
    beforeDestroy() {
    },
    //Xử lý khi hủy đối tượng
    destroyed() {
    },
});
</script>
@endsection
