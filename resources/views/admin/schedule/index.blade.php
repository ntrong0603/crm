@extends('admin.layout')

@section('titlePage', '配信スケジュール一覧')

<!-- add libs, code css other -->
@section('stylecss')
@endsection

@section('main')

<!-- Main content -->
<div id="app" style="display: none;">
    <section class="content customer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2> 顧客管理 </h2>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div>
                                <form role="form-horizontal" class="mail-form-search"
                                    v-on:submit.prevent="handleGetData(1)">
                                    <div class="form-box">
                                        <div class="form-box_items">
                                            <input type="text" v-model="paramSearch.search_keyword"
                                                class="input-keyword" id="search-keyword" placeholder="キーワード入力">
                                        </div>
                                        <div class="form-box_items">
                                            <label for="search-mail-type">最終配信日</label>
                                            <select name="search_mail_type" id="search-mail-type"
                                                v-model="paramSearch.search_mail_type">
                                                <option value=""></option>
                                                <option v-for="item of mail_type" v-bind:value="item.value">
                                                    @{{item.title}}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-box_items">
                                            <label for="">作成日</label>
                                            <input type="text" name="search_create_date_from"
                                                id="search-create-date-from" class="form-input-date hasDatapicker"
                                                v-model="paramSearch.search_create_date_from" autocomplete="off">
                                            ~
                                            <input type="text" name="search_create_date_to" id="search-create-date-to"
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
                            </div>
                            @include('admin.includes.pagination')
                            <div class="table-responsive">
                                <table id="data-list" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-lg-center">
                                                メール名
                                            </th>
                                            <th class="text-lg-center">
                                                配信スケジュール名
                                            </th>
                                            <th class="text-lg-center">
                                                タイプ
                                            </th>
                                            <th class="text-lg-center">
                                                ステータス
                                            </th>
                                            <th class="text-lg-center">
                                                Date Create
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="!dataNotFound">
                                        <tr v-for="item of datas.data" :key="item.customer_id">
                                            <td data-title="メール名" class="first-column text-left">
                                                <a
                                                    v-bind:href="item.mail_type == 1 ? handleHrefEdit(item.mail_setting_id) : handleHrefEditSpot(item.mail_setting_id)">
                                                    @{{item.setting_name}}
                                                </a>
                                            </td>
                                            <td data-title="配信スケジュール名" class="text-left">
                                                @{{item.schedule_name}}
                                            </td>
                                            <td data-title="タイプ" class="text-left">
                                                @{{item.mail_type_title}}
                                            </td>
                                            <td data-title="ステータス" class="text-left">
                                                @{{item.status}}
                                            </td>
                                            <td data-title="Date Create" class="text-left">
                                                @{{item.in_date}}
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
                            </div>
                            @include('admin.includes.pagination')
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
    </section>
</div>
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
            offset: 4,
            //option for selectbox pagination
            optionChangeLimit: [{value: '10',title: '10件'},{value: '25',title: '25件'},{value: '50',title: '50件'},{value: '100',title: '100件'},{value: '250',title: '250件'}],
            //add class css hanlde element in pagination
            // class hiden: ['hide-total-top', 'hide-pagination-top' ,'hide-number-top', 'hide-total-bottom', 'hide-pagination-bottom' ,'hide-number-bottom']
            paginationClass:['hide-total-bottom', 'hide-number-bottom'],
            // tabInfoIsActive: true,
            paramSearch:{
                search_keyword         : paramURLFirst.get('search_keyword') ?? '',
                search_mail_type       : paramURLFirst.get('search_mail_type') ?? '',
                search_create_date_from: paramURLFirst.get('search_create_date_from') ?? '',
                search_create_date_to  : paramURLFirst.get('search_create_date_to') ?? '',
                sort: [],
            },
            mail_type:[
                {
                    value: "1",
                    title: "シナリオメール"
                },
                {
                    value: "2",
                    title: "スポットメール"
                }
            ],
            itemShow: paramURLFirst.get('limit') ?? 25,
            paramSearchDefault:{},
            dataNotFound:false,
            loadHistory: false,
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
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
            //Xử lý lấy dữ liệu
            handleGetData(page = this.datas.current_page){
                var _this = this;
                $(".loading").show();
                $.ajax({
                    url: '{{route("schedule.getListData")}}'+'?page='+page+'&limit='+_this.itemShow,
                    type: "GET",
                    data: _this.paramSearch,
                    success: function(result){
                        _this.datas = result;
                        if(result.data.length == 0 && result.total != 0){
                            _this.handleGetData(result.last_page);
                            return;
                        }
                        if(result.data.length == 0 && result.total == 0){
                            _this.dataNotFound = true;
                        }else{
                            _this.dataNotFound = false;
                        }
                        if(!_this.loadHistory){
                        _this.handleRenderURL(_this.datas.current_page);
                        }
                        $(".loading").hide();
                    },
                    error: function(error){
                        console.error(error);
                        $(".loading").hide();
                    },
                })
            },
            //xử lý render url lên thanh địa chỉ và lưu vào lịch sử của browser
            handleRenderURL(page){
                var param = {};
                var url = '{{route("schedule")}}';
                var paramURL = new URL(location.href).searchParams;
                var search = this.paramSearch;
                var urlPath = "";
                if (page !== 1) {
                    urlPath += "&page="+page;
                }
                if (this.itemShow !== 25) {
                    urlPath += "&limit="+this.itemShow;
                }
                var isSearch = false;
                Object.keys(search).forEach(function(key,index) {
                    if (search[key] !== '' && key !== 'sort') {
                        urlPath += "&"+key+"="+search[key];
                        param[key] = search[key];
                        isSearch = true;
                    }
                });
                urlPath = urlPath.substring(1);
                if (urlPath !== '') {
                    url += "?"+urlPath;
                }
                param.page   = page;
                param.limit  = this.itemShow;
                if (page !== paramURL.get('page') || this.itemShow !== paramURL.get('limit') || isSearch === true) {
                    window.history.pushState(param, '',url);
                }
            },

            /**
             * Handle add event lib js change date
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

            /* function required for pagination */
            //Xử lý sự kiện chọn select box
            handleChangPaginationSelectBox(){
                this.handleGetData(this.datas.current_page);
            },
            //Xử lý sự kiện chọn trang, tên mặc định tương tác với pagination được include vào
            handleChangePage(page) {
                if(page == this.datas.current_page){
                    return;
                }
                this.datas.current_page = page;
                this.loadHistory = false;
                this.handleGetData(page);
            },
            /* end function for pagiantion */

            //xử lý sự kiện khi nhấn back, prev trên browser
            handleUpdateCurrentPage(){
                var paramURL = new URL(location.href).searchParams;
                this.datas.current_page = paramURL.get('page') ?? 1;
                this.itemShow = paramURL.get('limit') ?? 25;
                this.loadHistory = true;
                this.handleGetData(this.datas.current_page);
            },
            /*handle clear search*/
            handleClearSearch: function() {
                var self = this;
                Object.keys(this.paramSearch).forEach(function(key,index) {
                    self.paramSearch[key] = '';
                });
                self.itemShow = 25;
                self.handleGetData(1);
            }
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
            this.handleGetData();
        },
        // Trước khi có đầy đủ quyền truy cập vào phần tử DOM
        beforeMount() {
        },
        // mounted khi đã có đầy đủ quyền truy cập vào phần tử DOM
        mounted() {
            // var _this = this;s
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
