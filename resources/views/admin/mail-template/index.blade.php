@extends('admin.layout')
@section('titlePage', 'テンプレート編集')
<!-- add libs, code css other -->
@section('stylecss')
@endsection

@section('main')

<!-- Main content -->
<div id="app" style="display: none">
    <section class="content customer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2> テンプレート一覧 </h2>
                            <a href="{{route('mail-template.viewAdd')}}" class="btn btn-default btn-add">
                                <i class="fas fa-plus"></i> 新規作成
                            </a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="inner-body">
                                <div class="table-responsive">
                                    <Pagination v-bind:pagination-class="paginationClass"
                                        v-bind:last-page="arrData.last_page" v-bind:total="arrData.total"
                                        v-bind:current-page="arrData.current_page" v-bind:per-page="paramSearch.perPage"
                                        v-on:update:per-page="paramSearch.perPage = $event"
                                        v-on:update:data="handleGetData" v-on:update:page="paramSearch.page = $event"
                                        v-on:update:loadHistory="loadHistory = $event"></Pagination>
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-lg-center col-switch" style="width: 80px;">
                                                    行数
                                                </th>
                                                <th class="text-lg-center">

                                                    テンプレート名
                                                </th>
                                                <th class="tab-infor text-lg-center col-action" style="width: 140px;">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="!dataNotFound">
                                            <tr v-for="(item, index) of arrData.data" :key="item.mail_template_id">
                                                <td data-title="行数" class="text-right">
                                                    @{{index + arrData.from}}
                                                </td>
                                                <td data-title="テンプレート名" class="first-column text-left">
                                                    <a
                                                        v-bind:href="handleHrefEdit(item.mail_template_id)">@{{item.template_name}}</a>
                                                </td>
                                                <td data-title="">
                                                    <div class="admin-action-edit-or-delete"
                                                        style="width: auto;justify-content: center;"
                                                        v-if="item.is_protected == 0">

                                                        <a v-bind:href="handleHrefEdit(item.mail_template_id)"
                                                            class="btn-edit">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <a v-on:click.prevent="handleCopy(item.template_name, item.mail_template_id)"
                                                            class="btn-copy">
                                                            <i class="far fa-copy"></i>
                                                        </a>
                                                        <a v-on:click.prevent="handleDelete(item.template_name, item.mail_template_id)"
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
                                    <Pagination v-bind:pagination-class="paginationClass"
                                        v-bind:last-page="arrData.last_page" v-bind:total="arrData.total"
                                        v-bind:current-page="arrData.current_page" v-bind:per-page="paramSearch.perPage"
                                        v-on:update:per-page="paramSearch.perPage = $event"
                                        v-on:update:data="handleGetData" v-on:update:page="paramSearch.page = $event"
                                        v-on:update:loadHistory="loadHistory = $event"></Pagination>
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
<script src="{{asset('js/component/pagination.js')}}"></script>
<script>
    'use strict';
    // Khởi tạo router , khi đã new đối tượng phải giải phóng đối tượng.
    let router = new VueRouter({
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
        el: "#app",
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
            paramSearch: {
                perPage: 25,
                page: 1,
            },
            dataNotFound: false,
            // class hiden: ['hide-total-top', 'hide-pagination-top' ,'hide-number-top', 'hide-total-bottom', 'hide-pagination-bottom' ,'hide-number-bottom']
            // paginationClass:['hide-number-top','hide-number-bottom', 'hide-total-bottom'],
            paginationClass:['hide-total-bottom', 'hide-number-bottom'],
            loadHistory: false,
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            //Xử lý tạo link edit
            handleHrefEdit(id){
                let urlEdit = "{{route('mail-template.viewEdit', ['id' => '?id?'])}}";
                return urlEdit.replace('?id?', id)
            },
            //Handle get data
            handleGetData() {
                //Sử dụng lodash để lọc các param cái mà không có định nghĩa hoặc nó là rỗng.
                let currentSearch = this.$lodash.pickBy(this.paramSearch, this.$lodash.identity);
                //Dùng router của vue để push đến browser.
                //Posva(người cũng tạo ra vue) gợi ý cú pháp push và xử lý catch https://github.com/vuejs/vue-router/issues/2872
                if(!this.loadHistory){
                    this.$router.push({ query: currentSearch }).catch(error => {});
                }
                this.loadHistory = false;
                let url = '{{route("mail-template.getData")}}';
                loading.show();
                this.$axios.get(url, { params: this.paramSearch })
                .then(response => {
                    if(response.status == 200){
                        this.arrData = response.data;
                        //If change limit item need show and page not found
                        if(this.arrData.data.length == 0 && this.arrData.total != 0){
                            this.handleGetData();
                            return;
                        }
                        if(this.arrData.data.length == 0 && this.arrData.total == 0){
                            this.dataNotFound = true;
                        }else{
                            this.dataNotFound = false;
                        }
                    }else{
                        console.error(response);
                    }
                    loading.hide();
                })
                .catch(error => {
                    console.error(error);
                    loading.hide();
                });
            },
            //Xử lý sự kiện click btn copy mail setting
            handleCopy(name, id){
                let _this = this;
                // popup xác nhận
                alertAction("copy mail setting", name, function() {
                    loading.show();
                    let url = "{{route('mail-template.copy', ['id' => '?id?'])}}";
                    url = url.replace('?id?', id);
                    _this.$axios.post(url)
                    .then(response => {
                        if(response.status == 200){
                            let data = response.data;
                            if(data.error){
                                msgCustom('error', data.error);
                            }
                            if(data.success){
                                msgCustom('success', data.success);
                                _this.handleGetData();
                            }
                        }else{
                            console.error(response);
                        }
                        loading.hide();
                    })
                    .catch(error => {
                        console.error(error);
                        loading.hide();
                    });
                }, "Yes, Copy now!");
            },
            //Xử lý xóa mail setting
            handleDelete(name, id){
                let _this = this;
                // popup xác nhận
                alertAction("remove mail setting", name, function() {
                    loading.show();
                    let url = "{{route('mail-template.delete', ['id' => '?id?'])}}";
                    url = url.replace('?id?', id);
                    _this.$axios.post(url)
                    .then(response => {
                        if(response.status == 200){
                            let data = response.data;
                            if(data.error){
                                msgCustom('error', data.error);
                            }
                            if(data.success){
                                msgCustom('success', data.success);
                                _this.handleGetData();
                            }
                        }else{
                            console.error(response);
                        }
                        loading.hide();
                    })
                    .catch(error => {
                        console.error(error);
                        loading.hide();
                    });
                });
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
                let _this = this;
                this.$jquery(window).on('popstate', function(event) {
                    _this.loadHistory = true;
                    _this.handleQuery();
                    _this.handleGetData();
                });
            }
        },
        //Sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
        //Chỉ cho những sự kiện không truyền tham số
        computed: {},
        // Xảy ra trước khi khởi tạo, data và event được khai báo chưa tự thay đổi khi cập nhật
        beforeCreate() {
        },
        //Khởi tạo giống như __contructor của react, diễn ra trước quá trình tạo DOM
        created() {
            this.handleGetData();
            this.handleBtnBrowser();
        },
        // Trước khi có đầy đủ quyền truy cập vào phần tử DOM
        beforeMount() {
        },
        // mounted khi đã có đầy đủ quyền truy cập vào phần tử DOM
        mounted() {
            this.$jquery("#app").show();
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
            //Làm sạch instance vue
            delete this.$axios;
            delete this.$jquery;
            delete this.$lodash;
            delete this.data;
        },
        //Xử lý khi hủy đối tượng
        destroyed() {
        },
    })

</script>
@endsection
