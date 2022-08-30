@extends('admin.layout')
@section('titlePage', '効果測定メール一覧')
@section('stylecss')
@endsection
@section('main')
<!-- Main content -->
<div id="app" style="display: none;">
    <section class="content mail-effect">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>効果測定メール一覧</h2>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div>
                                <form role="form-horizontal" class="bkg-cutomer-form_search"
                                    v-on:submit.prevent="handleGetData()">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label for="search-text"
                                                class="col-12 col-sm-12 col-md-3 col-xl-2 col-form-label">キーワードで検索</label>
                                            <div class="col-12 col-sm-8 col-md-4 col-xl-3">
                                                <input type="text" v-model="paramSearch.searchText" class="form-control"
                                                    id="search-text">
                                            </div>
                                            <div class="col-6 col-sm-2 col-md-2 col-xl-1 text-right">
                                                <button type="submit"
                                                    class="btn btn-primary btn-custom btn-custom-primary btn-search-customer margin-right-btn">検索</button>
                                            </div>
                                            <div class="col-6 col-sm-2 col-md-2 col-xl-2 text-left btn-clear-form">
                                                <a v-on:click="handleClearSearch"
                                                    class="btn btn-danger btn-custom btn-custom-success btn-search-customer"
                                                    style="color: #fff;    text-decoration: none;min-width: 100px;">リセット</a>
                                            </div>
                                            <label for="search-text"
                                                    class="col-12 col-sm-12 col-md-12 col-xl-4 col-form-label" style="font-size: 14px">
                                                    ※同時に検索できるキーワードは2つまでです。
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-12 col-xl-12 condition-search">
                                                <label>条件で検索</label>
                                                <a v-on:click.prevent="" data-toggle="modal"
                                                    data-target="#js-search-properties"><i
                                                        class="far fa-edit"></i>条件を編集</a>
                                                <span>@{{showConditionSearch}}</span>
                                            </div>
                                            <div class="col-sm-2">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <Pagination v-bind:pagination-class="paginationClass" v-bind:last-page="arrData.last_page"
                            v-bind:total="arrData.total" v-bind:current-page="arrData.current_page"
                            v-bind:per-page="paramSearch.perPage" v-on:update:per-page="paramSearch.perPage = $event"
                            v-on:update:data="handleGetData" v-on:update:page="paramSearch.page = $event"
                            v-on:update:loadHistory="loadHistory = $event"></Pagination>
                            <div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center">行数</th>
                                            <th rowspan="2" class="text-center" width="20%">
                                                <a class="thead-sort" data-name-column="sort_mail_subject"
                                                    data-name-table="dt_mail_sent" v-on:click="handleSort">
                                                    メール名
                                                </a>
                                            </th>
                                            <th rowspan="2" class="text-center" width="10%">
                                                <a class="thead-sort" data-name-column="sort_mail_type"
                                                    data-name-table="dt_mail_setting" v-on:click="handleSort">
                                                    タイプ
                                                </a>
                                            </th>
                                            <th rowspan="2" class="text-center" width="10%">
                                                <a class="thead-sort" data-name-column="sort_sent_date" data-name-table=""
                                                    v-on:click="handleSort">
                                                    最終配信日

                                                </a>
                                            </th>
                                            <th rowspan="2" class="text-center">
                                                <a class="thead-sort" data-name-column="sort_total_sent" data-name-table=""
                                                    v-on:click="handleSort">
                                                    配信数
                                                </a>
                                            </th>

                                            <th colspan="2" class="text-center parent-col">開封</th>
                                            <th colspan="2" class="text-center parent-col">クリック</th>
                                            <th colspan="4" class="text-center parent-col">CV</th>

                                            <th rowspan="2" class="text-center">
                                                購読停止
                                            </th>
                                            <th rowspan="2" class="text-center">
                                                <a class="thead-sort" data-name-column="sort_send_error_num"
                                                    data-name-table="" v-on:click="handleSort">
                                                    エラー
                                                </a>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-center child-color">
                                                <a class="thead-sort" data-name-column="sort_open_num" data-name-table=""
                                                    v-on:click="handleSort">
                                                    数
                                                </a>
                                            </th>
                                            <th class="text-center child-color">
                                                <a class="thead-sort" data-name-column="sort_open_percent" data-name-table=""
                                                    v-on:click="handleSort">
                                                    ％
                                                </a>
                                            </th>
                                            <th class="text-center child-color">
                                                <a class="thead-sort" data-name-column="sort_clicked_num" data-name-table=""
                                                    v-on:click="handleSort">
                                                    数
                                                </a>
                                            </th>
                                            <th class="text-center child-color">
                                                <a class="thead-sort" data-name-column="sort_clicked_percent"
                                                    data-name-table="" v-on:click="handleSort">
                                                    ％
                                                </a>
                                            </th>
                                            <th class="text-center child-color">
                                                CV数
                                            </th>
                                            <th class="text-center child-color">
                                                CV/配信
                                            </th>
                                            <th class="text-center child-color">
                                                CV/開封
                                            </th>
                                            <th class="text-center child-color">
                                                金額
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="!dataNotFound">
                                        <tr v-for="(item, index) of arrData.data">
                                            <td data-title="行数" class="text-right">@{{index+1}}</td>
                                            <td data-title="メール名" class="first-column">
                                                <span>@{{item.mail_subject}}</span></td>
                                            <td data-title="タイプ">@{{item.mail_type}}</td>
                                            <td data-title="最終配信日">@{{item.sent_date}}</td>
                                            <td data-title="配信数" class="text-right">@{{item.total_sent}}</td>
                                            <td data-title="数" class="text-right">@{{item.open_num}}</td>
                                            <td data-title="％" class="text-right">
                                                @{{handleFormatNumber(item.open_percent)}}</td>
                                            <td data-title="数" class="text-right">
                                                @{{handleFormatNumber(item.clicked_num)}}</td>
                                            <td data-title="％" class="text-right">
                                                @{{handleFormatNumber(item.clicked_percent)}}</td>
                                            <td data-title="CV数" class="text-right">0</td>
                                            <td data-title="CV/配信" class="text-right">0.0</td>
                                            <td data-title="CV/開封" class="text-right">0.0</td>
                                            <td data-title="金額" class="text-right">0</td>
                                            <td data-title="購読停止" class="text-right">0</td>
                                            <td data-title="エラー" class="text-right">@{{item.send_error_num}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-if="dataNotFound">
                                        <tr>
                                            <td colspan="15" class="data-not-found">
                                                空データです。
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <Pagination v-bind:pagination-class="paginationClass" v-bind:last-page="arrData.last_page"
                            v-bind:total="arrData.total" v-bind:current-page="arrData.current_page"
                            v-bind:per-page="paramSearch.perPage" v-on:update:per-page="paramSearch.perPage = $event"
                            v-on:update:data="handleGetData" v-on:update:page="paramSearch.page = $event"
                            v-on:update:loadHistory="loadHistory = $event"></Pagination>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!--model condition search -->
    <div class="modal fade search-properties" id="js-search-properties">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <dl class="search-properties_item search-properties_item_half">
                        <dt class="search-properties_item-title">
                            最終配信日
                        </dt>
                        <dd class="search-properties_item-condition form-group row">
                            <input type="text" name="search_last_date_from" id="search_last_date_from"
                                class="form-input-date hasDatapicker" v-model="paramSearch.search_last_date_from"
                                style="width: calc(50% - 20px);" autocomplete="off">
                            <span class="label-mail-effect">～</span>
                            <input type="text" name="search_last_date_to" id="search_last_date_to"
                                class="form-input-date hasDatapicker" v-model="paramSearch.search_last_date_to"
                                style="width: calc(50% - 20px);" autocomplete="off">
                        </dd>
                    </dl>
                    <dl class="search-properties_item search-properties_item_half">
                        <dt class="search-properties_item-title">
                            配信時間
                        </dt>
                        <dd class="search-properties_item-condition form-group row">
                            <input class="form-input-date" type="time" id="search_last_time_from"
                                v-model="paramSearch.search_last_time_from" min="00:00" max="23:00">
                            <span class="label-mail-effect">～</span>
                            <input class="form-input-date" type="time" id="search_last_time_to"
                                v-model="paramSearch.search_last_time_to" min="00:00" max="23:00">
                        </dd>
                    </dl>
                    <dl class="search-properties_item search-properties_item_half">
                        <dt class="search-properties_item-title">
                            ショップ顧客ID
                        </dt>
                        <dd class="search-properties_item-condition">
                            <input type="text" name="customer_id" v-model="paramSearch.customer_id" style="width: 50%">
                        </dd>
                    </dl>
                    <dl class="search-properties_item search-properties_item_half">
                        <dt class="search-properties_item-title">
                            タイプ
                        </dt>
                        <dd class="search-properties_item-condition">
                            <ul
                                class="search-properties_item-condition_selected search-properties_item-condition_selected-50">
                                <li>
                                    <label>
                                        <input type="radio" name="mail_type" v-on:change="" id="mail_type" value="1"
                                            v-model="paramSearch.mail_type">
                                        シナリオメール
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="mail_type" class="mail_type"
                                            v-model="paramSearch.mail_type" v-on:change="" value="2">
                                        スポットメール
                                    </label>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl class="search-properties_item search-properties_item_half">
                        <dt class="search-properties_item-title">
                            顧客名
                        </dt>
                        <dd class="search-properties_item-condition">
                            <input type="text" name="customer_name" v-model="paramSearch.customer_name"
                                style="width: 100%">
                        </dd>
                    </dl>
                    <dl class="search-properties_item search-properties_item_half">
                        <dt class="search-properties_item-title">
                            顧客メールアドレス
                        </dt>
                        <dd class="search-properties_item-condition">
                            <input type="text" name="customer_mail" v-model="paramSearch.customer_mail"
                                style="width: 100%">
                        </dd>
                    </dl>
                    <div class="search-properties_btn">
                        <button type="button" class="btn btn-default btn-custom btn-custom-default"
                            data-dismiss="modal">キャンセル</button>
                        <button type="button" class="btn btn-primary btn-custom btn-custom-primary"
                            v-on:click="handleGetData()">この内容で検索</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- /.content -->
@endsection

@section('libraryjs')
<script src="{{asset('js/component/pagination.js')}}"></script>
<script>
    'use strict';
    // Khởi tạo router , khi đã new đối tượng phải giải phóng đối tượng.
    var router = new VueRouter({
        mode: 'history',
        base: '/',
        routes: []
    });
    //Bắt buộc khai báo để cho vuejs nhận biết chúng ta đang sử dụng thư viện thứ ba.
    Vue.prototype.$axios = axios;
    Vue.prototype.$jquery = $;
    Vue.prototype.$lodash = _;
    window.vue = new Vue({
        //Thành phần áp dụng
        router,
        el: '#app',
        //khai báo dữ liệu ban đầu
        data: {
            arrData: {
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
            },
            //add class css hanlde element in pagination
            // class hiden: ['hide-total-top', 'hide-pagination-top' ,'hide-number-top', 'hide-total-bottom', 'hide-pagination-bottom' ,'hide-number-bottom']
            paginationClass:['hide-total-bottom', 'hide-number-bottom'],
            paramSearch:{
                perPage: 25,
                page: 1,

                searchText:  '',
                search_last_date_from:  '',
                search_last_date_to:  '',
                search_last_time_from:  '',
                search_last_time_to:  '',
                customer_id : '',
                mail_type : '',
                customer_name : '',
                customer_mail : '',
                sort: [],
            },
            paramSearchDefault:{},
            dataNotFound: false,
            loadHistory: false,
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            handleGetData(){
                //Sử dụng lodash để lọc các param cái mà không có định nghĩa hoặc nó là rỗng.
                let currentSearch = this.$lodash.pickBy(this.paramSearch, this.$lodash.identity);
                //Dùng router của vue để push đến browser.
                //Posva(người cũng tạo ra vue) gợi ý cú pháp push và xử lý catch https://github.com/vuejs/vue-router/issues/2872
                if(!this.loadHistory){
                    this.$router.push({ query: currentSearch }).catch(error => {});
                }
                this.loadHistory = false;
                let url = '{{route("mail-effect.list")}}';
                loading.show();
                this.$axios.get(url, { params: this.paramSearch })
                .then(response => {
                    if(response.status == 200){
                        this.arrData = response.data;
                        if(this.arrData.data.length == 0 && this.arrData.total != 0){
                            this.paramSearch.page = this.arrData.last_page;
                            this.handleGetData();
                            return;
                        }
                        this.paramSearchDefault = {};
                        this.paramSearchDefault = copyObj(this.paramSearch);
                        if(this.arrData.data.length == 0 && this.arrData.total == 0){
                            this.dataNotFound = true;
                        }else{
                            this.dataNotFound = false;
                        }
                    }else{
                        console.error(response);
                    }
                    this.$jquery("#js-search-properties").modal('hide');
                    loading.hide();
                })
                .catch((error) => {
                    console.error(error)
                });
            },
            /**
             * Handle add event lib js
            */
            HandleEventLibTime(){
                var _this = this;
                _this.$jquery('.hasDatapicker').datepicker({
                    onSelect: function(dateText) {
                        var name = _this.$jquery(this).attr("name");
                        _this.paramSearch[name] = dateText;
                    },
                    dateFormat: "yy-mm-dd",
                });
            },
            /** format number
             * @param float number
             * @param int digits minimum fraction digits
             * @return string
            */
            handleFormatNumber(number, digits = 0){
                if (number !== null) {
                    return formatNumber(number, digits);
                } else {
                    return 0;
                }
            },
            //Handle sort data
            handleSort(e){
                //clear class active and class thead-sort-desc
                this.$jquery(".thead-sort").each(function(index, element){
                    this.$jquery(element).removeClass('active');
                    this.$jquery(element).removeClass('thead-sort-desc');
                });
                var element = this.$jquery(e.target);
                var column = element.data('name-column');
                var nameTable = element.data('name-table') ?? '';
                if(this.paramSearch.sort.length == 0){
                    this.paramSearch.sort = [column, 'desc', nameTable];
                    element.addClass('active');
                    this.$jquery(element).addClass('thead-sort-desc');
                }else if(this.paramSearch.sort.length != 0){
                    if(this.paramSearch.sort[1] == 'asc' || this.paramSearch.sort[0] != column){
                        this.paramSearch.sort = [column, 'desc', nameTable];
                        element.addClass('active');
                        this.$jquery(element).addClass('thead-sort-desc');
                    }else{
                        this.paramSearch.sort = [column, 'asc', nameTable];
                        element.addClass('active');
                    }
                }
                this.handleGetData();
            },
            /*handle clear search*/
            handleClearSearch() {
                //Set router về trạng thái ban đầu sau đó set lại params search
                this.$router.push({ query: null }).catch(error =>{});
                this.paramSearch = {
                    perPage: 25,
                    page: 1,

                    searchText:  '',
                    search_last_date_from:  '',
                    search_last_date_to:  '',
                    search_last_time_from:  '',
                    search_last_time_to:  '',
                    customer_id : '',
                    mail_type : '',
                    customer_name : '',
                    customer_mail : '',
                    sort: [],
                };
                this.handleGetData();
            },
            /**
             * Xử lý sự kiện khi copy link có param từ nơi khác parse vào
            */
            handleQuery(){
                let paramsBrowser = this.$route.query;
                for(let child in paramsBrowser){
                    //Kiểm tra kiểu dữ liệu mặc đinh của tham số là mảng hay chuỗi
                    if(Array.isArray(this.paramSearch[child]) && !Array.isArray(paramsBrowser[child])){
                        this.paramSearch[child] = [];
                        this.paramSearch[child].push(paramsBrowser[child]);
                    }else{
                        this.paramSearch[child] = paramsBrowser[child];
                    }
                }
            },
            /**
             * Xử lý sự kiện nhấn button back trên browser
            */
            handleBtnBrowser(){
                var _this = this;
                $(window).on('popstate', function(event) {
                    _this.loadHistory = true;
                    _this.handleClearSearch();
                    _this.handleQuery();
                    _this.handleGetData();
                });
            },
            /**
            * Xử lý các sự kiện js khác
            * Các xự kiện xảy ra sau khi hoàn thành tải DOM thật
            */
            handleEventJS(){
                let _this = this;
                //xử lý sự kiện khi đóng model điều kiện search, refesh lại dữ liệu search lúc đầu
                _this.$jquery("#js-search-properties").on('hidden.bs.modal', function (e) {
                    let size = Object.keys(_this.paramSearchDefault).length;
                    if(size > 0){
                        _this.paramSearch = {};
                        _this.paramSearch = {..._this.paramSearchDefault};
                    }
                })
            },
        },
        //sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
        computed: {
             //handle show condition search
            showConditionSearch(){
                var arrStrCondition = [];
                var count = Object.keys(this.paramSearchDefault).length;
                var condition = this.paramSearchDefault;
            },
        },
        // Xảy ra trước khi khởi tạo, data và event được khai báo chư tự thay đổi khi cập nhật
        beforeCreate() {
        },
        //Khởi tạo giống như __contructor của react, diễn ra trước quá trình tạo DOM
        created() {
            this.handleQuery();
            this.handleGetData();
            this.handleBtnBrowser();
        },
        // Trước khi có đầy đủ quyền truy cập vào phần tử DOM
        beforeMount() {
        },
        // mounted khi đã có đầy đủ quyền truy cập vào phần tử DOM
        mounted() {
            this.handleEventJS();
            this.$jquery('#app').show();
            this.HandleEventLibTime();
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
            delete this.$axios;
            delete this.$jqplot;
            delete this.$jquery;
            delete this.data;
        },
        //Xử lý khi hủy đối tượng
        destroyed() {
        },


    });
</script>
@endsection
