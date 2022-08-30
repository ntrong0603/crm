@extends('admin.layout')
@section('titlePage', 'ユーザー管理')
@section('stylecss')
@endsection
@section('main')
<!-- Main content -->
<div id="app" style="display: none;">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2> ユーザー管理 </h2>
                            <a href="{{route('user.add')}}" title="Add" class="btn btn-default btn-add">
                                <i class="fas fa-plus"></i> 新規作成
                            </a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @include('admin.includes.pagination')
                            <div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">User Name</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Last login</th>
                                            <th class="text-center">Last login ip</th>
                                            <th class="text-center">Created at</th>
                                            <th class="text-center">Update at</th>
                                            <th class="text-center" style="width: 90px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item of datas.data">
                                            <td data-title="ID" class="text-right">@{{item.id}}</td>
                                            <td data-title="User Name" class="first-column">@{{item.user_name}}</td>
                                            <td data-title="Name">@{{item.name}}</td>
                                            <td data-title="Email">@{{item.email}}</td>
                                            <td data-title="Last login">@{{item.last_login_at}}</td>
                                            <td data-title="Last login ip">@{{item.last_login_ip}}</td>
                                            <td data-title="Created at">@{{item.format_created_at}}</td>
                                            <td data-title="Update at">@{{item.format_updated_at}}</td>
                                            <td data-title="Edit/Delete">
                                                <div class="admin-action-edit-or-delete"
                                                    style="width: auto;">
                                                    <a v-bind:href="handleHrefEdit(item.id)" class="btn-edit"
                                                        title="edit">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <a v-if="item.id != id" class="btn-delete" v-bind:data-id="item.id"
                                                        v-bind:data-name="item.name" title="delete"
                                                        v-on:click="handleDeleteUser(item.name, item.id)">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                                <div class="clearfix"></div>
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
        </div>
    </section>
</div>


<!-- /.content -->
@endsection

@section('libraryjs')
<script>
    var paramURLFirst = new URL(location.href).searchParams;
    window.vue = new Vue({
        //Thành phần áp dụng
        el: '#app',
        //khai báo dữ liệu ban đầu
        data: {
            id: '{{Auth::user()->id}}',
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

            //value selected for select box
            itemShow: paramURLFirst.get('limit') ?? 25,
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
            //add class css hanlde element in pagination
            // class hiden: ['hide-total-top', 'hide-pagination-top' ,'hide-number-top', 'hide-total-bottom', 'hide-pagination-bottom' ,'hide-number-bottom']
            paginationClass:['hide-total-bottom', 'hide-number-bottom'],
            loadHistory: false,
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            //Xử lý tạo link edit thông tin customer
            handleHrefEdit(id){
                urlEdit = "{{route('user.edit', ['id' => '?idUser?'])}}";
                return urlEdit.replace('?idUser?', id)
            },
            handleDeleteUser(name, id){
                var _this = this;
                alertAction("remove user", name, function() {
                    loading.show();
                    var url = "{{route('user.delete', ['id' => '?idUser?'])}}";
                    url = url.replace('?idUser?', id);
                    $.ajax({
                        method: "POST",
                        url: url,
                        success: function(data){
                            if(data.error){
                                msgCustom('error', data.error);
                            }
                            if(data.success){
                                msgCustom('success', data.success);
                                _this.handleGetData(_this.datas.current_page);
                            }
                            loading.hide();
                        }
                    });
                }, 'Yes, Remove now!');
            },
            handleGetData(page){
                loading.show();
                var _this = this;
                var paramURL = new URL(location.href).searchParams;
                $.ajax({
                    url: '{{route("user.getListUser")}}'+'?page='+page+'&limit='+_this.itemShow,
                    type: 'GET',
                    headers:  { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response){
                        _this.datas = {...response.listUser};
                        //check empty data it delete item in page
                        if(!_this.loadHistory){
                            _this.handleRenderURL(_this.datas.current_page);
                            console.log(_this.datas.current_page);
                        }
                        if(_this.datas.data.length === 0){
                            _this.handleGetData(_this.datas.last_page);
                        }
                        loading.hide();
                    },
                    error: function(error){
                        console.error(error);
                        loading.hide();
                    },
                });
            },
            //xử lý render url lên thanh địa chỉ và lưu vào lịch sử của browser
            handleRenderURL(page){
                var param = {};
                var url = '{{route("user.list")}}';
                var paramURL = new URL(location.href).searchParams;
                var search = this.paramSearch;
                var urlPath = "";
                if (page !== 1) {
                    urlPath += "&page="+page;
                }
                if (this.itemShow !== 25) {
                    urlPath += "&limit="+this.itemShow;
                }

                urlPath = urlPath.substring(1);
                if (urlPath !== '') {
                    url += "?"+urlPath;
                }
                param.page   = page;
                param.limit  = this.itemShow;
                if (page !== paramURL.get('page') || this.itemShow !== paramURL.get('limit')) {
                    window.history.pushState(param, '',url);
                }
            },
            /* function required for pagination */
            handleChangPaginationSelectBox(){
                this.handleGetData(this.datas.current_page);
            },
            handleChangePage(page) {
                if(page == this.datas.current_page){
                    return;
                }
                this.datas.current_page = page;
                this.loadHistory = false;
                this.handleGetData(page);
            },
            /* end function for pagiantion */
            //Get data khi xử dụng buttom prev, next trên browser
            handleUpdateCurrentPage(){
                var paramURL = new URL(location.href).searchParams;
                this.datas.current_page = paramURL.get('page') ?? 1;
                this.itemShow = paramURL.get('limit') ?? 25;
                this.loadHistory= true;
                this.handleGetData(this.datas.current_page);
            },

        },
        //sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
        computed: {
            /* function caculate for pagination */
            isActived() {
                return this.datas.current_page;
            },
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
        // Xảy ra trước khi khởi tạo, data và event được khai báo chư tự thay đổi khi cập nhật
        beforeCreate() {
        },
        //Khởi tạo giống như __contructor của react, diễn ra trước quá trình tạo DOM
        created() {
            var _this = this;
            $(window).on('popstate', function(event) {
                _this.handleUpdateCurrentPage();
            });
            _this.handleGetData(this.datas.current_page);
        },
        // Trước khi có đầy đủ quyền truy cập vào phần tử DOM
        beforeMount() {
        },
        // mounted khi đã có đầy đủ quyền truy cập vào phần tử DOM
        mounted() {
            $('#app').show();
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

    });
</script>
@endsection
