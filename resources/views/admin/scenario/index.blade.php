@extends('admin.layout')
@section('titlePage', 'メール設定')
<!-- add libs, code css other -->
@section('stylecss')
@endsection

@section('main')

<!-- Main content -->
<div id="app" style="display: none">
    <section class="content customer scr-scenario">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2> メール設定 </h2>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="btn-box">
                                <div class="btn-box_items">
                                    <a href="{{route('scenario.viewAdd')}}" class="btn btn-blue">
                                        <i class="fas fa-walking fa-rotate-360"></i>
                                        <p>購入者の行動を基準に<br>メールをつくる<br><span>【シナリオメール】</span></p>
                                    </a>
                                </div>
                                <div class="btn-box_items">
                                    <a href="{{route('scenario.viewAddSpot')}}" class="btn btn-blue">
                                        <i class="far fa-calendar-alt"></i>
                                        <p>送信日付を基準に<br>メールをつくる<br><span>【スポットメール】</span></p>
                                    </a>
                                </div>
                                <div class="btn-box_items btn-box_items-rows">
                                    <a href="{{route('mail-template.viewAdd')}}" class="btn btn-gray">
                                        デザインからメールを作る
                                    </a>
                                    {{-- <a href="" class="btn btn-gray">
                                        戦略からメールを作る
                                    </a> --}}
                                </div>
                            </div>
                            <div class="inner-body">
                                <div class="tabs">
                                    <a href="" class="tabs-item" v-on:click.prevent="changeTab('tab1')"
                                        v-bind:class="[ maillSettingTab == 'tab1' ? 'active' : '']">シナリオメール一覧</a>
                                    <a href="" class="tabs-item" v-on:click.prevent="changeTab('tab2')"
                                        v-bind:class="[ maillSettingTab == 'tab2' ? 'active' : '']">スポットメール一覧</a>
                                </div>
                                <form class="mail-form-search" v-on:submit.prevent="getData(1)">
                                    <div class="form-box">
                                        <div class="form-box_items">
                                            <input type="text" name="search_keyword" id="search_keyword"
                                                class="input-keyword" placeholder="キーワード入力"
                                                v-model="paramSearch.search_keyword">
                                        </div>
                                        <div class="form-box_items">
                                            <label for="">最終配信日</label>
                                            <input type="text" name="search_send_date_from" id="search_send_date_from"
                                                class="form-input-date hasDatapicker"
                                                v-model="paramSearch.search_send_date_from" autocomplete="off">
                                            ~
                                            <input type="text" name="search_send_date_to" id="search_send_date_to"
                                                class="form-input-date hasDatapicker"
                                                v-model="paramSearch.search_send_date_to" autocomplete="off">
                                        </div>
                                        <div class="form-box_items">
                                            <label for="">作成日</label>
                                            <input type="text" name="search_create_date_from"
                                                id="search_create_date_from" class="form-input-date hasDatapicker"
                                                v-model="paramSearch.search_create_date_from" autocomplete="off">
                                            ~
                                            <input type="text" name="search_create_date_to" id="search_create_date_to"
                                                class="form-input-date hasDatapicker"
                                                v-model="paramSearch.search_create_date_to" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-box pull-right">
                                        <button class="btn btn-blue margin-right-btn"><i class="fa fa-search"></i>
                                            検索</button>
                                        <a class="btn btn-danger search-btn" v-on:click="handleClearSearch">リセット</a>
                                    </div>
                                </form>
                                <form class="mail-form-sort">
                                    <div class="mail-form-sort_items">
                                        <label for="">
                                            表示件数
                                        </label>
                                        <select name="btn_show_line_num" id="btn_show_line_num"
                                            v-on:change="handleChangPaginationSelectBox" v-model='itemShow'>
                                            <option v-bind:value="option.value" v-for="option in optionChangeLimit">
                                                @{{option.title}}</option>
                                        </select>
                                    </div>
                                    <div class="mail-form-sort_items">
                                        <label for="">
                                            フィルター
                                        </label>
                                        <select name="btn_filter" id="btn_filter" v-model='filter'
                                            v-on:change="handleChangPaginationSelectBox">
                                            <option v-for="option in optionFilter" v-bind:value="option.value">
                                                @{{option.title}}</option>
                                        </select>
                                    </div>
                                    <div class="mail-form-sort_items total-right">
                                        <label for="">
                                            全件@{{datas.total}}件
                                        </label>
                                    </div>

                                </form>
                                <div class="tabs-content">
                                    <!-- Mail setting 1 -->
                                    <div class="tabs-setting-1 table-responsive"
                                        v-bind:class="[ maillSettingTab == 'tab1' ? 'active' : '']">
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-lg-center col-switch">
                                                        <a>
                                                            ステータス
                                                        </a>
                                                    </th>
                                                    <th class="text-lg-center">
                                                        <a>
                                                            シナリオメール名
                                                        </a>
                                                    </th>
                                                    <th class="tab-infor text-lg-center">
                                                        <a>
                                                            ステップ数
                                                        </a>
                                                    </th>
                                                    <th class="tab-infor text-lg-center">
                                                        <a>
                                                            基準日
                                                        </a>
                                                    </th>
                                                    <th class="tab-infor text-lg-center">
                                                        <a class="thead-sort" data-name-column="up_date"
                                                            data-name-table="dt_mail_setting" v-on:click="handleSort">
                                                            更新日時
                                                        </a>
                                                    </th>
                                                    <th class="tab-infor text-lg-center col-action"
                                                        style="width: 130px">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="!dataNotFound">
                                                <tr v-for="item of datas.data" :key="item.mail_setting_id">
                                                    <td data-title="ステータス"
                                                        v-bind:class="item.setting_status == 2 ? 'blocked' : ''">
                                                        <div class="switch">
                                                            <span>無効</span>
                                                            <input type="checkbox" v-bind:data-id="item.mail_setting_id"
                                                                value="1" v-model="item.status"
                                                                v-on:click.prevent="changeStatusMailSetting(item.mail_setting_id)" />
                                                            <span>有効</span>
                                                        </div>
                                                    </td>
                                                    <td data-title="シナリオメール名" class="first-column text-left">
                                                        <a
                                                            v-bind:href="handleHrefEdit(item.mail_setting_id)">@{{item.setting_name}}</a>
                                                    </td>
                                                    <td data-title="ステップ数" class="text-right">
                                                        <span>@{{item.total}}</span>
                                                    </td>
                                                    <td data-title="基準日" class="text-left">
                                                        @{{item.standard_date_name}}
                                                    </td>
                                                    <td data-title="更新日時" class="text-left">
                                                        @{{(item.up_date)}}
                                                    </td>
                                                    <td data-title="">
                                                        <div class="admin-action-edit-or-delete text-center" style="">
                                                            <a v-bind:href="handleHrefEdit(item.mail_setting_id)"
                                                                class="btn-edit">
                                                                <i class="fas fa-pen"></i>
                                                            </a>
                                                            <a v-on:click.prevent="handleCopy(item.setting_name, item.mail_setting_id)"
                                                                class="btn-copy">
                                                                <i class="far fa-copy"></i>
                                                            </a>
                                                            <a v-on:click.prevent="handleDelete(item.setting_name, item.mail_setting_id)"
                                                                class="btn-delete">
                                                                <i class="far fa-trash-alt"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody v-if="dataNotFound">
                                                <tr>
                                                    <td colspan="11" class="data-not-found">
                                                        空データです。
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @include('admin.includes.pagination')
                                    </div>
                                    <!-- Mail setting 1 -->
                                    <!-- Mail setting 2 -->
                                    <div class="tabs-setting-2 table-responsive"
                                        v-bind:class="[ maillSettingTab == 'tab2' ? 'active' : '']">
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-lg-center col-switch">
                                                        <a>
                                                            ステータス
                                                        </a>
                                                    </th>
                                                    <th class="text-lg-center">
                                                        <a>
                                                            巣ポートメール名
                                                        </a>
                                                    </th>
                                                    <th class="tab-infor text-lg-center">
                                                        <a>
                                                            送信メール数
                                                        </a>
                                                    </th>
                                                    <th class="tab-infor text-lg-center">
                                                        <a class="thead-sort" data-name-column="up_date"
                                                            data-name-table="dt_mail_setting" v-on:click="handleSort">
                                                            更新日時
                                                        </a>
                                                    </th>
                                                    <th class="tab-infor text-lg-center col-action"
                                                        style="width: 130px">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="!dataNotFound">
                                                <tr v-for="item of datas.data" :key="item.mail_setting_id">
                                                    <td data-title="ステータス"
                                                        v-bind:class="item.setting_status == 2 ? 'blocked' : ''">
                                                        <div class="switch">
                                                            <span>無効</span>
                                                            <input type="checkbox" v-bind:data-id="item.mail_setting_id"
                                                                value="1" v-model="item.status"
                                                                v-on:click.prevent="changeStatusMailSetting(item.mail_setting_id)" />
                                                            <span>有効</span>
                                                        </div>
                                                    </td>
                                                    <td data-title="巣ポートメール名" class="first-column text-left">
                                                        <a
                                                            v-bind:href="handleHrefEditSpot(item.mail_setting_id)">@{{item.setting_name}}</a>
                                                    </td>
                                                    <td data-title="送信メール数" class="text-right">
                                                        @{{item.total}}
                                                    </td>
                                                    <td data-title="更新日時" class="text-left">
                                                        @{{(item.up_date)}}
                                                    </td>
                                                    <td data-title="">
                                                        <div class="admin-action-edit-or-delete " style="">
                                                            <a v-bind:href="handleHrefEditSpot(item.mail_setting_id)"
                                                                class="btn-edit">
                                                                <i class="fas fa-pen"></i>
                                                            </a>
                                                            <a v-on:click.prevent="handleCopy(item.setting_name, item.mail_setting_id)"
                                                                class="btn-copy">
                                                                <i class="far fa-copy"></i>
                                                            </a>
                                                            <a v-on:click.prevent="handleDelete(item.setting_name, item.mail_setting_id)"
                                                                class="btn-delete">
                                                                <i class="far fa-trash-alt"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody v-if="dataNotFound">
                                                <tr>
                                                    <td colspan="11" class="data-not-found">
                                                        空データです。
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @include('admin.includes.pagination')
                                    </div>
                                    <!-- Mail setting 2 -->
                                </div>
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
</div>
<!-- /.content -->
@endsection

<!-- add libs, code, function js other -->
@section('libraryjs')
<script>
    var paramURLFirst = new URL(location.href).searchParams;
    window.vue = new Vue({
        //Thành phần áp dụng
        el: "#app",
        //khai báo dữ liệu ban đầu
        data: {
            maillSettingTab: 'tab1',
            mail_type: paramURLFirst.get('mail_type') ?? 1,
            datas: {
                data: [],
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: paramURLFirst.get('page') ?? 1,
                first_page_url: '',
                last_page_url: '',
                next_page_url: '',
                per_page: '',
                prev_page_url: '',
                total: '',
                last_page: 0,
            },
            paramSearch: {
                search_keyword: paramURLFirst.get('search_keyword') ? paramURLFirst.get('search_keyword') :'',
                search_send_date_from: paramURLFirst.get('search_send_date_from') ? paramURLFirst.get('search_send_date_from') :'',
                search_send_date_to: paramURLFirst.get('search_send_date_to') ? paramURLFirst.get('search_send_date_to') :'',
                search_create_date_from: paramURLFirst.get('search_create_date_from') ?? '',
                search_create_date_to: paramURLFirst.get('search_create_date_to') ?? '',
                sort: [],
            },
            dataNotFound: false,
            filter: paramURLFirst.get('filter') ?? '',
            itemShow: paramURLFirst.get('limit') ?? 25,
            offset: 4,
            // class hiden: ['hide-total-top', 'hide-pagination-top' ,'hide-number-top', 'hide-total-bottom', 'hide-pagination-bottom' ,'hide-number-bottom']
            paginationClass:['hide-total-bottom', 'hide-number-bottom'],
            //option for selectbox pagination
            optionChangeLimit: [
                {
                    value: '10',
                    title: '10件',
                },
                {
                    value: '25',
                    title: '25件',
                },
                {
                    value: '50',
                    title: '50件',
                },
                {
                    value: '100',
                    title: '100件',
                },
                {
                    value: '250',
                    title: '250件',
                },
            ],
            //option for selectbox filter
            optionFilter: [
                {
                    value: '',
                    title: 'すべて',
                },
                {
                    value: '0',
                    title: '有効',
                },
                {
                    value: '1',
                    title: '無効または未設定',
                },
            ],
            loadHistory: false,
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            //Handle clear
            handleClear(){
                this.datas = {
                    data: [],
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1,
                    first_page_url: '',
                    last_page_url: '',
                    next_page_url: '',
                    per_page: '',
                    prev_page_url: '',
                    total: '',
                    last_page: 0,
                };
                this.paramSearch = {
                    search_keyword: '',
                    search_send_date_from: '',
                    search_send_date_to: '',
                    search_send_date_to: '',
                    search_create_date_to: '',
                    search_send_date_from: '',
                    sort: [],
                };
                this.filter = '';
                this.itemShow = 25;
                this.getData();
            },
            //Xử lý tạo link edit thông tin mail setting
            handleHrefEdit(id){
                urlEdit = "{{route('scenario.viewEdit', ['id' => '?id?'])}}";
                return urlEdit.replace('?id?', id)
            },
            //Xử lý tạo link edit thông tin mail setting
            handleHrefEditSpot(id){
                urlEdit = "{{route('scenario.viewEditSpot', ['id' => '?id?'])}}";
                return urlEdit.replace('?id?', id)
            },
            //Xử lý tạo link copy thông tin mail setting
            handleHrefCopy(id){
                urlCopy = "{{route('scenario.copy', ['id' => '?id?'])}}";
                return urlCopy.replace('?id?', id)
            },
            /**
             * Handle change status mail setting
            */
            changeStatusMailSetting(id){
                var mail_setting_id = id;
                var _this = this;
                loading.show();
                $.ajax({
                    url: '{{route("scenario.changeStatusMailSeting")}}',
                    type: 'POST',
                    data: {
                        mail_setting_id: mail_setting_id,
                    },
                    success: function(result){
                        if(result.error){
                            msgCustom('error', result.error);
                        }
                        if(result.success){
                            _this.errors = [];
                            msgCustom('success', result.success);
                        }
                        _this.getData();
                        loading.hide();
                    },
                    error: function(error) {
                        console.error(error);
                        loading.hide();
                    }
                });
            },
            /** change tab mail setting 1 and mail setting 2
             * @param boolean tab active tab mail setting 1
             * @return void
            */
            changeTab(tab){
                this.maillSettingTab = tab;
                if(tab == "tab1") {
                    this.mail_type = 1;
                }else{
                    this.mail_type = 2;
                }
                //Kích hoạt lại sự kiện reponsive cho table
                activeTabelResponsive();
                this.handleClear();
            },
            /**
             * Handle add event lib js
            */
            HandleEventLib(){
                var _this = this;
                $('.hasDatapicker').datepicker({
                    onSelect: function(dateText) {
                        var name = $(this).attr("name");
                        _this.paramSearch[name] = dateText;
                    }
                }) ;
            },
            //Xử lý sự kiện copy mail setting
            handleCopy(name, id){
                var _this = this;
                // popup xác nhận
                alertAction("copy mail setting", name, function() {
                    loading.show();
                    var url = "{{route('scenario.copy', ['id' => '?id?'])}}";
                    url = url.replace('?id?', id);
                    $.ajax({
                        method: "POST",
                        url: url,
                        success: function(data){
                            if(data.error){
                                msgCustom('error', data.error);
                            }
                            if(data.success){
                                msgCustom('success', data.success);
                                _this.getData(_this.datas.current_page);
                            }
                            loading.hide();
                        }
                    });
                }, "Yes, Copy now!");
            },
            //Xử lý xóa mail setting
            handleDelete(name, id){
                var _this = this;
                // popup xác nhận
                alertAction("remove mail setting", name, function() {
                    loading.show();
                    var url = "{{route('scenario.delete', ['id' => '?id?'])}}";
                    url = url.replace('?id?', id);
                    $.ajax({
                        method: "POST",
                        url: url,
                        success: function(data){
                            if(data.error){
                                msgCustom('error', data.error);
                            }
                            if(data.success){
                                msgCustom('success', data.success);
                                _this.getData(_this.datas.current_page);
                            }
                            loading.hide();
                        },
                        error: function(error) {
                            console.error(error);
                            loading.hide();
                        }
                    });
                });
            },
            /**
             * Process get data
             * @return void
            */
            getData(page = this.datas.current_page){
                if(this.mail_type == 1) {
                    this.maillSettingTab = "tab1";
                }
                if(this.mail_type == 2){
                    this.maillSettingTab = "tab2";
                }
                loading.show();
                var _this = this;
                _this.paramSearch.mail_type = _this.mail_type;
                $.ajax({
                    type: "GET",
                    url: "{{route('scenario.getData')}}"+'?page='+page+'&limit='+_this.itemShow+'&filter='+_this.filter,
                    data: _this.paramSearch,
                    headers:  { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(result){
                        //If change limit item need show and page not found
                        if(result.data.length == 0 && result.total != 0){
                            _this.getData(result.last_page);
                            return;
                        }
                        if(result.data.length == 0 && result.total == 0){
                            _this.dataNotFound = true;
                        }else{
                            // msgCustom('success', 'Success');
                            _this.dataNotFound = false;
                        }
                        _this.datas = result;
                        if(!_this.loadHistory){
                        _this.handleRenderURL(_this.datas.current_page);
                        }
                        loading.hide();
                    },
                    error: function(error){
                        console.error(error);
                        loading.hide();
                    }
                })
            },
            /* function required for pagination */
            //Xử lý sự kiện chọn select box
            handleChangPaginationSelectBox(){
                this.getData(this.datas.current_page);
            },
            //xử lý render url lên thanh địa chỉ và lưu vào lịch sử của browser
            handleRenderURL(page){
                var param = {};
                var url = '{{route("scenario")}}';
                var paramURL = new URL(location.href).searchParams;
                var search = this.paramSearch;
                var urlPath = "";
                if (page !== 1) {
                    urlPath += "&page="+page;
                }
                if (this.itemShow !== 25) {
                    urlPath += "&limit="+this.itemShow;
                }
                if (this.filter !== '') {
                    urlPath += "&filter="+this.filter;
                }
                var isSearch = false;
                Object.keys(search).forEach(function(key,index) {
                    if (search[key] !== '' && key != 'sort') {
                        urlPath += "&"+key+"="+search[key];
                        param[key] = search[key];
                        isSearch = true;
                    }
                    if (key == 'sort' && search[key].length > 0){
                        urlPath += "&"+search[key][0]+"="+search[key][1];
                        param[search[key][0]] = search[key][0];
                        isSearch = true;
                    }
                });
                urlPath = urlPath.substring(1);
                url += "?"+urlPath;
                param.page   = page;
                param.limit  = this.itemShow;
                param.filter = this.filter;
                if (page !== paramURL.get('page') || this.itemShow !== paramURL.get('limit') || this.filter !== '' || isSearch === true) {
                    window.history.pushState(param, '',url);
                }
            },
            //Xử lý sự kiện chọn trang, tên mặc định tương tác với pagination được include vào
            handleChangePage(page) {
                if(page == this.datas.current_page){
                    return;
                }
                this.datas.current_page = page;
                this.loadHistory = false;
                this.getData(page);
            },
            //Get data khi xử dụng buttom prev, next trên browser
            handleUpdateCurrentPage: function(){
                var paramURL = new URL(location.href).searchParams;
                this.datas.current_page = paramURL.get('page') ?? 25;
                this.loadHistory = true;
                this.getData(this.datas.current_page);
            },
            /**handle format date
             * @param string stringDate string date need format type Y-m-d
             * @return string
            */
            hanldeFormatDate(stringDate){
                if(stringDate != null){
                    var date = new Date(stringDate);
                    return date.getFullYear() + "/" + ("0" + (date.getMonth()+1)).slice(-2) + "/" + ("0" + date.getDate()).slice(-2)+ " " + date.getHours() + ":" + date.getMinutes();
                }
                return;
            },
            /*handle clear search*/
            handleClearSearch: function() {
                var self = this;
                Object.keys(this.paramSearch).forEach(function(key,index) {
                    self.paramSearch[key] = '';
                });
                self.itemShow = 25;
                self.filter = '';
                // self.mail_type = 1;
                self.getData(1);
            },
            //Handle sort data
            handleSort(e){
                //clear class active and class thead-sort-desc
                $(".thead-sort").each(function(index, element){
                    $(element).removeClass('active');
                    $(element).removeClass('thead-sort-desc');
                });
                var element = $(e.target);
                var column = element.data('name-column');
                var nameTable = element.data('name-table') ?? '';
                if(this.paramSearch.sort.length == 0){
                    this.paramSearch.sort = [column, 'desc', nameTable];
                    element.addClass('active');
                    $(element).addClass('thead-sort-desc');
                }else if(this.paramSearch.sort.length != 0){
                    if(this.paramSearch.sort[1] == 'asc' || this.paramSearch.sort[0] != column){
                        this.paramSearch.sort = [column, 'desc', nameTable];
                        element.addClass('active');
                        $(element).addClass('thead-sort-desc');
                    }else{
                        this.paramSearch.sort = [column, 'asc', nameTable];
                        element.addClass('active');
                    }
                }
                this.getData();
            },
            //handel data sort first load page
            handleSortFirst() {
                var paramURL = getUrlParamAll();
                var search = this.paramSearch;
                var _this = this;
                Object.keys(paramURL).forEach(function(key,index) {
                    if(typeof search[key] == 'undefined' && key != 'mail_type' && key != 'filter') {
                        //clear class active and class thead-sort-desc
                        $(".thead-sort").each(function(index, element){
                            $(element).removeClass('active');
                            $(element).removeClass('thead-sort-desc');
                        });
                        var el = $("[data-name-column='"+key+"']");
                        var nameTable = el.data('name-table') ?? '';
                        if(paramURL[key] == 'desc'){
                            el.addClass('active');
                            el.addClass('thead-sort-desc');
                        }else{
                            el.addClass('active');
                        }
                        _this.paramSearch.sort = [key, paramURL[key], nameTable];
                    }
                });
            },
        },
        //Sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
        //Chỉ cho những sự kiện không truyền tham số
        computed: {
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
        },
        // Xảy ra trước khi khởi tạo, data và event được khai báo chưa tự thay đổi khi cập nhật
        beforeCreate() {
        },
        //Khởi tạo giống như __contructor của react, diễn ra trước quá trình tạo DOM
        created() {
            var _this = this;
            $(window).on('popstate', function(event) {
                _this.handleUpdateCurrentPage();
            });
            this.handleSortFirst();
            this.getData();
        },
        // Trước khi có đầy đủ quyền truy cập vào phần tử DOM
        beforeMount() {
        },
        // mounted khi đã có đầy đủ quyền truy cập vào phần tử DOM
        mounted() {
            $("#app").show();
            this.HandleEventLib();
        },
        //xử lý trước khi dữ liệu bị thay đổi
        beforeUpdate() {
        },
        //Xử lý khi dữ liệu đã thay đổi
        updated() {
            activeTabelResponsive();
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
