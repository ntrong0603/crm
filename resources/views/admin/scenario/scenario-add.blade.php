@extends('admin.layout')
@section('titlePage', 'シナリオメール設定')
<!-- add libs, code css other -->
@section('stylecss')
@endsection

@section('main')

<!-- Main content -->
<div id="app" style="display: none">
    <section class="content scenario-form">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @if ($mail_type == 2)
                            <h2>スポットメール設定</h2>
                            @else
                            <h2> シナリオメール設定 </h2>
                            <span>※この画面で登録・編集した内容でのメール配信は、翌日からになります。</span>
                            @endif
                            <div class="btn-action-main">
                                <a href="{{ route('scenario') }}" class="btn">← メール設定</a>
                                <a class="btn btn-default btn-add" v-on:click.prevent="handleSave">保存</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body mailset-wrap">
                            <form role="form" method="POST">
                                <div class="mailset-section-wrap">
                                    <div class="mailset-section mailset-title">
                                        <input type="hidden" class="size-large" placeholder="設定名称"
                                            name="mail_setting_id" v-model="data.mail_setting_id">
                                        <div class="mailset-box">
                                            <dl>
                                                <dt class="">
                                                    設定名称
                                                    <span class="required-icon">必須</span>
                                                </dt>
                                                <dd>
                                                    <input type="text" class="size-large" placeholder="設定名称"
                                                        name="setting_name" v-model="data.setting_name">
                                                    <p class="msg-error" id="mail_name_error" style="display: none;">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        設定名称を入力してください
                                                    </p>
                                                    <p class="notes">※こちらは管理用の設定名称です。メールの件名ではありませんのでご注意ください。</p>
                                                    <div>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt class="">
                                                    メモ
                                                </dt>
                                                <dd>
                                                    <input type="text" class="size-large" placeholder="メモ"
                                                        name="remarks" v-model="data.remarks">
                                                    <div>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="mailset-section mailset-enable blocked">
                                        <div class="switch">
                                            <span>無効</span>
                                            <input type="checkbox" name="setting_status" v-model="data.status"
                                                value="1" />
                                            <span>有効</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mailset-section-wrap">
                                    <!-- mail set targeting -->
                                    @include('admin.scenario.sub-item.mailset-targeting')
                                    <!-- mail set targeting -->
                                    <!-- mail set targeting -->
                                    @include('admin.scenario.sub-item.mailset-schedule')
                                    <!-- mail set targeting -->

                                </div>
                            </form>
                            <div class="form-group row justify-content-center form-button-footer">
                                <a href="{{ $urlPrevious }}"
                                    class="btn btn-default btn-custom btn-custom-default">キャンセル</a>
                                <button type="submit" class="btn btn-primary pull-center btn-custom btn-custom-primary"
                                    v-on:click.prevent="handleSave">保存</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- select product -->
    @include('admin.scenario.sub-item.popup-select-product')
    <!-- select product -->
</div>
<!-- /.content -->
@endsection

<!-- add libs, code, function js other -->
@section('libraryjs')

<script>
    window.vue = new Vue({
            //Thành phần áp dụng
            el: "#app",
            //khai báo dữ liệu ban đầu
            data: {
                edit: false,
                data: {
                    mail_type: '{{ $mail_type }}',
                    mail_setting_id: '',
                    setting_name: '',
                    remarks: '',
                    setting_status: 0,
                    status: false,
                    receive_property: [1],
                    customer_target: [{{$strRank}}],
                    ltv_from: '',
                    ltv_to: '',
                    purchased_date_from: '',
                    purchased_date_to: '',
                    first_time_purchased_date_from: '',
                    first_time_purchased_date_to: '',
                    last_time_purchased_date_from: '',
                    last_time_purchased_date_to: '',
                    purchased_times_from: '',
                    purchased_times_to: '',
                    cumulative_of_earnings_from: '',
                    cumulative_of_earnings_to: '',
                    old_from: '',
                    old_to: '',
                    standard_id: 1,
                    sex: [],
                    prefectures: [],
                    is_all_or_one_purchase: 1,
                    is_buyed: 1,
                    is_all_or_one_stop: 1,
                    schedules: [],
                    array_purchased_product_code: [],
                    array_stop_product: [],
                    product_specify: {
                        model: '',
                        name: '',
                    },
                },
                paramSearchProduct: {
                    key_search: '',
                    // like_condition: 1,
                    product_name: 1,
                    product_code: 1,
                    product_jan: 1,
                    price_from: '',
                    price_to: '',
                },

                // data show in popup
                datas: {},
                // data search product purchased
                firstSearchPurchased: true,
                paramSearchProductPurchased: {
                    key_search: '',
                    product_name: 1,
                    product_code: 1,
                    product_jan: 1,
                    price_from: '',
                    price_to: ''
                },
                // data search product stop
                firstSearchStop: true,
                paramSearchProductStop: {
                    key_search: '',
                    product_name: 1,
                    product_code: 1,
                    product_jan: 1,
                    price_from: '',
                    price_to: ''
                },
                // data search product specific
                firstSearchSpecific: true,
                paramSearchProductSpecific: {
                    key_search: '',
                    product_name: 1,
                    product_code: 1,
                    product_jan: 1,
                    price_from: '',
                    price_to: ''
                },

                limitProductPurchased: 5,
                limitProductStop: 30,
                // 1: product_purchased, 2: product_stop, 3:product_specific
                trackingPopupSelect: 1,
                checkAllCRMCondition: false,
                checkAllSex: false,
                checkAllPref: false,
                limitTimeline: 10,
                showSelectOneProduct: false,
                arrIdStandardOneProduct: [20, 21, 22, 23],
                paginationClass: ['hide-number-top'],
                itemShow: 25,
                offset: 3,
                optionChangeLimit: [],
                btnChangeOneProduct: false,
                idDateSchedule: 1,
                numSchedule: 1,
            },
            //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
            methods: {
                /**
                 * Handle reset data
                 */
                handleReset() {
                    this.datas = {
                        data: [],
                        total: 0,
                        per_page: 2,
                        from: 1,
                        to: 0,
                        current_page: "{{ request()->page ?? 1 }}",
                        first_page_url: '',
                        last_page_url: '',
                        next_page_url: '',
                        per_page: '',
                        prev_page_url: '',
                        total: '',
                        last_page: 0,
                    };
                },
                //check all crm condition sel
                handleCheckAll(checkbox) {
                    var _this = this;
                    //CRM Condition
                    if (checkbox == 'crm_condition') {
                        _this.data.customer_target = [];
                        if (!_this.checkAllCRMCondition) {
                            $("input[name='customer_target[]']").each(function() {
                                _this.data.customer_target.push($(this).val());
                            });
                            _this.checkAllCRMCondition = true;
                        } else {
                            _this.checkAllCRMCondition = false;
                        }
                    }
                    //sex
                    if (checkbox == 'sex') {
                        _this.data.sex = [];
                        if (!_this.checkAllSex) {
                            $("input[name='sex[]']").each(function() {
                                _this.data.sex.push($(this).val());
                            });
                            _this.checkAllSex = true;
                        } else {
                            _this.checkAllSex = false;
                        }
                    }
                    //pref
                    if (checkbox == 'pref') {
                        _this.data.prefectures = [];
                        if (!_this.checkAllPref) {
                            $("input[name='prefectures[]']").each(function() {
                                _this.data.prefectures.push($(this).val());
                            });
                            _this.checkAllPref = true;
                        } else {
                            _this.checkAllPref = false;
                        }
                    }
                },

                /**
                 * handle open popup search and select product
                 * @param string type popup
                 */
                handleOpenPopup(type) {
                    this.trackingPopupSelect = type;
                    $("#select-product").modal('show');
                },

                /**
                 * Handle change date in schedule
                 */
                handleEventChangeDate() {
                    var _this = this;
                    var listInputDate = $('[id^="schedule_date"]');
                    for (var index = 0; index < listInputDate.length; index++) {
                        var item = $(listInputDate[index]);
                        var id = parseInt(item.attr("id").replace("schedule_date", ''));
                        if (id > _this.idDateSchedule) {
                            _this.idDateSchedule = id;
                        }
                    }
                },

                /**
                 * handle event change checkbox if group has checkbox all
                 */
                handleChangeCheckbox() {
                    var _this = this;
                    //CRM Condition
                    $("input[name='crm_condition_sel[]").change(function(e) {
                        var checked = $(this).prop('checked');
                        if (!checked && _this.checkAllCRMCondition) {
                            _this.checkAllCRMCondition = false;
                            $("#form_crm_condition_sel_all").prop("checked", false);
                        }
                        if (checked && _this.data.crm_condition_sel.length == $(
                                "input[name='crm_condition_sel[]").length) {
                            _this.checkAllCRMCondition = true;
                            $("#form_crm_condition_sel_all").prop("checked", true);
                        }
                    });
                    //sex
                    $("input[name='sex[]").change(function(e) {
                        var checked = $(this).prop('checked');
                        if (!checked && _this.checkAllSex) {
                            _this.checkAllSex = false;
                            $("#form_sex_all").prop("checked", false);
                        }
                        if (checked && _this.data.sex.length == $("input[name='sex[]").length) {
                            _this.checkAllSex = true;
                            $("#form_sex_all").prop("checked", true);
                        }
                    });
                    //pref
                    $("input[name='pref_id[]").change(function(e) {
                        var checked = $(this).prop('checked');
                        if (!checked && _this.checkAllPref) {
                            _this.checkAllPref = false;
                            $("#form_pref_id_all").prop("checked", false);
                        }
                        if (checked && _this.data.pref_id.length == $("input[name='pref_id[]").length) {
                            _this.checkAllPref = true;
                            $("#form_pref_id_all").prop("checked", true);
                        }
                    });
                },

                /**
                 * Handel event for element in timeline
                 */
                handleEventTimeline() {
                    /** Sử dụng unbind để xóa tất cả sự kiện của phần tử
                     * (dùng khi gán lại sự kiện các cho đối tượng được tạo mới, tránh khai báo nhiều lần 1 sự kiện dẫn đến double xử lý)
                     */
                    $("li a.btn-play-or-stop, .schedule-action .delete, .schedule-action .cancel").unbind();
                    $('[data-toggle="tooltip"]').tooltip('dispose');
                    $('[data-toggle="tooltip"]').tooltip();
                    //event hover/click btn play/stop timeline
                    $(".timeline li a.btn-play-or-stop").on("mouseover mouseout click", function(e) {
                        e.preventDefault();
                        var elIcon = $(this).find("i.fas");
                        var parentMain = $(this).parent().parent();
                        if (elIcon.hasClass("fa-pause")) {
                            elIcon.removeClass("fa-pause");
                            elIcon.addClass("fa-play");
                        } else {
                            elIcon.removeClass("fa-play");
                            elIcon.addClass("fa-pause");
                        }
                        if (e.type == "click") {
                            if (parentMain.hasClass("blocked")) {
                                parentMain.removeClass("blocked");
                            } else {
                                parentMain.addClass("blocked");
                            }
                        }
                    });
                    //Event button delete timeline
                    $(".timeline li a.delete").on("click", function(e) {
                        e.preventDefault();
                        var parents = $(this).closest("li");
                        parents.addClass("remove");
                    });
                    //Event button delete timeline
                    $(".timeline li a.cancel").on("click", function(e) {
                        e.preventDefault();
                        var parents = $(this).closest("li");
                        parents.removeClass("remove");
                    });
                },
                /**
                 * Xử lý sự kiện js chỉ gọi 1 lần
                 */
                handleCallOne() {
                    var _this = this;
                    _this.handleResizeWidth();
                    $(".hasDatepicker1").datepicker();
                    $(window).resize(function() {
                        _this.handleResizeWidth();
                    });
                    $(window).scroll(function() {
                        var heightNavTop = $(".main-header").outerHeight();
                        var btnAction = $(".btn-action-main");
                        if (heightNavTop <= $(window).scrollTop()) {
                            btnAction.addClass("fixed");
                        } else {
                            btnAction.removeClass("fixed");
                        }
                    });
                    $(".btn-form-edit-condition").click(function(e) {
                        var btn = $(this);
                        var form = btn.parent().find(".form-condition");
                        if (form.hasClass("show")) {
                            btn.removeClass("hidden-condition");
                            form.removeClass("show");
                        } else {
                            btn.addClass("hidden-condition");
                            form.addClass("show");
                        }
                        //.addClass("show");
                    });
                    $("#checkbox_all").change(function() {
                        var listProduct = $(
                            "#select-product tbody input[type='checkbox']:not('#checkbox_all')");
                        var checkBoxAll = $("#checkbox_all");
                        if (checkBoxAll.is(":checked")) {
                            for (var index = 0; index < listProduct.length; index++) {
                                $(listProduct[index]).prop("checked", true);
                            }
                        } else {
                            for (var index = 0; index < listProduct.length; index++) {
                                $(listProduct[index]).prop("checked", false);
                            }
                        }
                    });
                    $("#select-product").on("show.bs.modal", function(e) {
                        var checkBoxAll = $("#checkbox_all").prop("checked", false);
                        switch (_this.trackingPopupSelect) {
                            case 1:
                                if (_this.firstSearchPurchased) {
                                    return;
                                }
                                _this.paramSearchProduct = _this.paramSearchProductPurchased;
                                break;
                            case 2:
                                if (_this.firstSearchStop) {
                                    return;
                                }
                                _this.paramSearchProduct = _this.paramSearchProductStop;
                                break;
                            case 3:
                                if (_this.firstSearchSpecific) {
                                    return;
                                }
                                _this.paramSearchProduct = _this.paramSearchProductSpecific;
                                break;
                            default:
                                break;
                        }
                        _this.handleSearchProduct();
                    });
                    //Change receive property
                    $(".select_allow_mail").on("change", function(e) {
                        var el = $(this);
                        if (!el.is(":checked")) {
                            if (_this.data.receive_property.length == 0) {
                                _this.data.receive_property.push(el.val());
                                el.prop("checked", true);
                                $("#allow_mail_error").css("display", "block");
                            }
                        } else {
                            $("#allow_mail_error").css("display", "none");
                        }
                    });

                    $("#standard_date").change(function(e) {
                        _this.handleSelectBaseDate();
                    });
                },
                /**
                 * Handle event select is after
                 * if 基準日 is "birthday" then 基準日から active select "before" or "after"
                 * else default "after"
                 * if 基準日 is "cart" then only  show one schedule, show: "btn play/pause, btn select template, btn delete, input name schedule"
                 */
                handleSelectBaseDate() {
                    var el = $("#standard_date");
                    var isAfter = $(".input_before_after");
                    for (var index = 0; index < isAfter.length; index++) {
                        var select = $(isAfter[index]);
                        if (el.val() != 3) {
                            select.find("option[value='1']").prop('selected', true);
                            select.prop("disabled", true);
                        } else {
                            select.prop("disabled", false);
                        }
                    }
                },
                /**
                 * resize width group action
                 */
                handleResizeWidth() {
                    var width = $(".scenario-form").outerWidth();
                    $(".btn-action-main").css("width", width + "px");
                },

                /**
                 * Handle add timeline
                 */
                handleAddTimeline() {
                    var timeline = $(".timeline");
                    var _this = this;
                    var item = $(".timeline li:first").clone();
                    _this.idDateSchedule++;
                    _this.numSchedule++;
                    var idScheduleDate = "schedule_date" + _this.idDateSchedule;
                    item.removeClass();
                    item.addClass("pending");
                    $(item).attr("id", "schedule_" + _this.numSchedule);
                    $(item).attr("data-id", _this.numSchedule);
                    $(item).find("input").val('');
                    $(item).find(".msg-error").css("display", "none");
                    $(item).find(".error").removeClass('error');
                    $(item).find(".hasError").removeClass('hasError');
                    $(item).find("select option").prop("selected", false);
                    $(item).find("a.btn-play-or-stop i.fas").removeClass("fa-pause").addClass("fa-play");
                    var datepicker = $(item).find(".select-date input.hasDatepicker1");
                    datepicker.removeClass("hasDatepicker");
                    datepicker.attr("id", idScheduleDate);
                    datepicker.datepicker();
                    $(".timeline li:nth-last-child(3)").after(item);
                    _this.handleEventTimeline();
                    _this.handleCheckLimitTimeline();
                },

                /**
                 * Handle check limit timeline
                 */
                handleCheckLimitTimeline() {
                    var btnAdd = $(".timeline li.add");
                    var btnStop = $(".timeline li.add-stop");
                    var countTimeline = $(".timeline li:not(.add):not(.add-stop)").length;
                    if (countTimeline < this.limitTimeline) {
                        btnAdd.attr("style", "display: block");
                        btnStop.attr("style", "display: none");
                    } else {
                        btnAdd.attr("style", "display: none");
                        btnStop.attr("style", "display: block");
                    }
                },

                /**
                 * Handle search product
                 */
                handleSearchProduct(page = this.datas.current_page) {
                    var _this = this;
                    //Not get model
                    var flag = _this.trackingPopupSelect;
                    if (flag == 1) {
                        var arr = _this.data.array_purchased_product_code;
                        _this.paramSearchProduct.not_get_model = [];
                        for (var index = 0; index < arr.length; index++) {
                            _this.paramSearchProduct.not_get_model.push(arr[index].model);
                        }
                        _this.firstSearchPurchased = false;
                    }
                    if (flag == 2) {
                        var arr = _this.data.array_stop_product;
                        _this.paramSearchProduct.not_get_model = [];
                        for (var index = 0; index < arr.length; index++) {
                            _this.paramSearchProduct.not_get_model.push(arr[index].model);
                        }
                        _this.firstSearchStop = false;
                    }
                    if (flag == 3) {
                        _this.firstSearchSpecific = false;
                    }
                    $.ajax({
                        url: "{{ route('scenario.searchProduct') }}" + '?page=' + page,
                        type: "GET",
                        data: _this.paramSearchProduct,
                        success: function(result) {
                            if (result.total > 0) {
                                _this.datas = result;
                                switch (_this.trackingPopupSelect) {
                                    case 1:
                                        _this.listSearchProductPurchased = result;
                                        _this.paramSearchProductPurchased = {
                                            ..._this.paramSearchProduct
                                        };
                                        break;
                                    case 2:
                                        _this.listSearchProductStop = result;
                                        _this.paramSearchProductStop = {
                                            ..._this.paramSearchProduct
                                        };
                                        break;
                                    case 3:
                                        _this.listSearchProductSpecific = result;
                                        _this.paramSearchProductSpecific = {
                                            ..._this.paramSearchProduct
                                        };
                                        break;
                                    default:
                                        break;
                                }
                                var listProduct = $(
                                    "#select-product tbody input[type='checkbox']:not('#checkbox_all')"
                                    );
                                var checkBoxAll = $("#checkbox_all").prop("checked", false);
                                for (var index = 0; index < listProduct.length; index++) {
                                    $(listProduct[index]).prop("checked", false);
                                }
                            } else {
                                _this.handleReset();
                            }
                        },
                        error: function(error) {
                            console.error(error);
                            loading.hide();
                        },
                    });
                },
                //Xử lý sự kiện chọn select box
                handleChangPaginationSelectBox() {
                    this.getData(this.datas.current_page);
                },
                /**
                 * Handle event change product
                 */
                handleBtnChangeProduct() {
                    // 1: product_purchased, 2: product_stop, 3:product_specific
                    var typeProduct = this.trackingPopupSelect;
                    switch (typeProduct) {
                        case 1:
                        case 2:
                            var maxSelect = 0;
                            var limit = 0;
                            var listProductChecked = $(
                                "#select-product tbody input[type='checkbox']:not('#checkbox_all'):checked");
                            if (typeProduct == 1) {
                                maxSelect = this.limitProductPurchased - this.data.array_purchased_product_code
                                    .length;
                                limit = this.limitProductPurchased;
                            }
                            if (typeProduct == 2) {
                                maxSelect = this.limitProductStop - this.data.array_purchased_product_code.length;
                                limit = this.limitProductStop;
                            }

                            if (maxSelect < listProductChecked.length) {
                                msgCustom("error", "選択可能な商品は" + limit + "件までです");
                                break;
                            }

                            for (var index = 0; index < listProductChecked.length; index++) {
                                var product = $(listProductChecked[index]);
                                var item = {};
                                item.model = product.val();
                                item.name = product.data("name");

                                if (typeProduct == 1 && !this.handleCheckExistProduct(item.model).status) {

                                    this.data.array_purchased_product_code.push(item);
                                }
                                if (typeProduct == 2 && !this.handleCheckExistProduct(item.model).status) {
                                    this.data.array_stop_product.push(item);
                                }
                            }
                            $("#select-product").modal('hide');
                            break;

                        case 3:
                            var product = $("#select-product tbody input[type='radio']:checked");
                            if (product.length > 0 && !this.handleCheckExistProduct(product.val()).status) {
                                this.data.product_specify = {
                                    model: product.val(),
                                    name: product.data("name"),
                                };
                            }
                            $("#select-product").modal('hide');
                            break;
                        default:
                            break;
                    }
                },
                /**
                 * Handle change checkbox product
                 */
                handeSelectProduct() {
                    var _this = this;
                    $("#select-product tbody input[type='checkbox']:not('#checkbox_all')").unbind('click');
                    $("#select-product tbody input[type='checkbox']:not('#checkbox_all')").on('click', function() {
                        var listSelect = $(
                            "#select-product input[type='checkbox']:not('#checkbox_all'):checked");
                        var listProduct = $("#select-product input[type='checkbox']:not('#checkbox_all')");
                        var checkBoxAll = $("#checkbox_all");
                        var checkBox = $(this);
                        if (listSelect.length == listProduct.length) {
                            checkBoxAll.prop("checked", true);
                        } else {
                            checkBoxAll.prop("checked", false);
                        }
                    });
                },
                /**
                 * handle check isset in array productTemporarily
                 * status = 1 is isset in array
                 * status = 0 is not isset in array
                 * index obj in array
                 */
                handleCheckExistProduct(model) {
                    var typeProduct = this.trackingPopupSelect;
                    var result = {
                        status: false,
                        indexElement: -1,
                    };
                    switch (typeProduct) {
                        case 1:
                        case 2:
                            var arr = [];
                            if (typeProduct == 1) {
                                arr = this.data.array_purchased_product_code;
                            }
                            if (typeProduct == 2) {
                                arr = this.data.array_stop_product
                            }
                            for (var index = 0; index < arr.length; index++) {
                                if (typeof arr[index].model != 'undefined' && arr[index].model == model) {
                                    result.status = true;
                                    result.indexElement = index;
                                    break;
                                }
                            }
                            break;
                        case 3:
                            if (this.data.product_specify.model == model) {
                                result.status = true;
                            }
                            break;

                        default:
                            break;
                    }
                    return result;
                },

                /**
                 * Handle remove product specifec
                 */
                handleRemovePrSpecific() {
                    this.data.product_specify = {};
                },

                //Xử lý sự kiện chọn trang, tên mặc định tương tác với pagination được include vào
                handleChangePage(page) {
                    if (page == this.datas.current_page) {
                        return;
                    }
                    this.datas.current_page = page;
                    this.handleSearchProduct(page);
                },
                /** format number
                 * @param float number
                 * @param int digits minimum fraction digits
                 * @return string
                 */
                handleFormatNumber(number, digits = 0) {
                    return formatNumber(number, digits);
                },
                /**
                 * Handle save data
                 */
                handleSave() {
                    var _this = this;
                    var listTimeline = $(".timeline li:not(.add):not(.add-stop)");
                    _this.data.schedules = [];
                    if (listTimeline.length > 0) {
                        for (var index = 0; index < listTimeline.length; index++) {
                            if (!$(listTimeline[index]).hasClass("remove")) {
                                item                  = {};
                                item.schedule_id      = $(listTimeline[index]).find("[name='schedule_id']").val();
                                item.schedule_name    = $(listTimeline[index]).find("[name='schedule_name']").val();
                                item.schedule_status  = $(listTimeline[index]).find(".btn-play-or-stop").find("i").hasClass("fa-play") ? 1 : 0;
                                item.date_num         = $(listTimeline[index]).find("[name='date_num']").val();
                                item.is_after         = $(listTimeline[index]).find("[name='is_after']").val();
                                item.hour             = $(listTimeline[index]).find("[name='hour']").val();
                                item.date             = $(listTimeline[index]).find("[name='date']").val();
                                item.minute           = $(listTimeline[index]).find("[name='minute']").val();
                                item.schedule_id_html = $(listTimeline[index]).data("id");
                                _this.data.schedules.push(item);
                            }
                        }
                    }
                    $.ajax({
                        url: "{{ route('scenario.save') }}",
                        type: "POST",
                        data: _this.data,
                        success: function(result) {
                            $(".msg-error").css("display", "none")
                            $(".error").removeClass('error');
                            $(".hasError").removeClass('hasError');
                            if (result.status == 1) {
                                if(result.arrError["setting_name"]) {
                                    var iSettingName = $("input[name='setting_name']");
                                    iSettingName.addClass('error');
                                    iSettingName.parent().find(".msg-error").css("display", "block");
                                }
                                if(result.arrError['schedule-edit']){
                                    var arrScheduleError = result.arrScheduleError;
                                    for (const [key, value] of Object.entries(arrScheduleError)) {
                                        var schedule = $("#schedule_"+key);
                                        schedule.addClass('hasError');
                                        for (const [key2, value2] of Object.entries(value)) {
                                            var el = schedule.find("[name='" + key2 + "']");
                                            if(key2 == 'schedule_name'){
                                                schedule.find(".msg-error").css("display", "block");
                                            }
                                            el.addClass('error');
                                        }
                                    }
                                }
                                if(result.message != ''){
                                var messageError = '';
                                var count = 1;
                                for (const [key, value] of Object.entries(result.message)) {
                                    if(count == 1){
                                        messageError += value;
                                    }else{
                                        messageError += "<br/>" + value;
                                    }
                                    count++;
                                }
                                msgCustom('error', messageError, 5000);
                            }
                            }
                            if (result.status == 0) {
                                var url = "{{ route('scenario.viewEdit', ['id' => '?id?']) }}";
                                msgCustom('success', result.message, 2000);
                                window.location.href = url.replace('?id?', result.id);
                            }
                        },
                        error: function(error) {
                            console.error(error);
                            loading.hide();
                        },
                    });
                    //
                },
            },
            //Sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
            //Chỉ cho những sự kiện không truyền tham số
            computed: {
                /**
                 * handle name popup
                 */
                handleNamePopup() {
                    var name = "";
                    if (this.trackingPopupSelect == 1) {
                        name = '商品の追加<p class="item-limit">【残り<span id="remaining_to_limit">' + (this
                                .limitProductPurchased - this.data.array_purchased_product_code.length) +
                            '</span>件追加可能】</p>';
                    }
                    if (this.trackingPopupSelect == 2) {
                        name = '配信停止ルール指定<p class="item-limit">【残り<span id="remaining_to_limit">' + (this
                            .limitProductStop - this.data.array_stop_product.length) + '</span>件追加可能】</p>';
                    }
                    if (this.trackingPopupSelect == 3) {
                        name = '商品の追加<p class="item-limit">※指定可能なのは1件のみ</p>';
                    }
                    return name;
                },
                /* function caculate for pagination */
                //sự kiện active cho phân trang
                isActived() {
                    return this.datas.current_page;
                },
                //Xự kiện tính phần tử cho phân trang
                pagesNumber() {
                    var from = this.datas.current_page - this.offset;
                    if (from < 1) {
                        from = 1;
                    }
                    var to = from + (this.offset * 2);
                    if (to >= this.datas.last_page) {
                        to = this.datas.last_page;
                    }
                    var pagesArray = [];
                    while (from <= to) {
                        pagesArray.push(from);
                        from++;
                    }
                    return pagesArray;
                },
                /* end function caculate for pagination */

                /**
                 * Handle show hidden change product
                 */
                handleShowChangeOneProduct: function() {
                    if (this.arrIdStandardOneProduct.indexOf(parseInt(this.data.standard_id)) > -1) {
                        return true;
                    }
                    return false;
                },
            },
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
                this.handleChangeCheckbox();
                this.handleEventTimeline();
                this.handleCallOne();
                this.handleShowChangeOneProduct;
                this.handleSelectBaseDate();
                //created tooltip
            },
            //xử lý trước khi dữ liệu bị thay đổi
            beforeUpdate() {
            },
            //Xử lý khi dữ liệu đã thay đổi
            updated() {
                activeTabelResponsive();
                this.handeSelectProduct();
            },
            //Xử lý trước khi hủy đối tượng
            beforeDestroy() {
            },
            //Xử lý khi hủy đối tượng
            destroyed() {
            },
        })

</script>
@endsection
