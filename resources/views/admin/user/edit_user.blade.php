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
                            <h2>
                                ユーザー管理
                                <span class="sub-title"> / Edit User</span>
                            </h2>
                        </div>
                        <!-- /.box-header -->
                        <form role="form" method="POST" v-on:submit.prevent="onSubmit">
                            <div class="card-body">
                                <div v-bind:class="[ errors.name ? 'form-group has-error' : 'form-group']">
                                    <label class="control-label" for="name">Name</label>
                                    <input type="text" class="form-control" id="name" v-model="dataEditUser.name"
                                        name="name" placeholder="Full Name">
                                    <div v-if="errors.name">
                                        <span v-for="error of errors.name" class="help-block" v-html="error"></span>
                                    </div>
                                </div>

                                <!-- text input -->
                                <div v-bind:class="[ errors.email ? 'form-group has-error' : 'form-group']">
                                    <label class="control-label" for="email">Email</label>
                                    <input type="text" class="form-control" id="email" v-model="dataEditUser.email"
                                        name="email" placeholder="Email">
                                    <div v-if="errors.email">
                                        <span v-for="error of errors.email" class="help-block" v-html="error"></span>
                                    </div>
                                </div>

                                <div v-bind:class="[ errors.user_name ? 'form-group has-error' : 'form-group']">
                                    <label class="control-label" for="user_name">User Name</label>
                                    <input type="text" class="form-control" id="user_name"
                                        v-model="dataEditUser.user_name" name="user_name" placeholder="User name"
                                        value="dataEditUser.user_name">
                                    <div v-if="errors.user_name">
                                        <span v-for="error of errors.user_name" class="help-block"
                                            v-html="error"></span>
                                    </div>
                                </div>

                                <!-- password input -->
                                <div v-bind:class="[ errors.password_user ? 'form-group has-error' : 'form-group']">
                                    <label class="control-label" for="password">Password</label>
                                    <input type="password" class="form-control" id="password_user"
                                        v-model="dataEditUser.password_user" name="password_user"
                                        placeholder="Password">
                                    <div v-if="errors.password_user">
                                        <span v-for="error of errors.password_user" class="help-block" v-html="error">
                                        </span>
                                    </div>
                                </div>

                                <!-- password input -->
                                <div
                                    v-bind:class="[ errors.password_user_confim ? 'form-group has-error' : 'form-group']">
                                    <label class="control-label" for="password_confim">Password Confirm</label>
                                    <input type="password" class="form-control" id="password_user_confim"
                                        v-model="dataEditUser.password_user_confim" name="password_user_confim"
                                        placeholder="Password Confirm">
                                    <div v-if="errors.password_user_confim">
                                        <span v-for="error of errors.password_user_confim" class="help-block"
                                            v-html="error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary pull-right">Save</button>
                                <a href="{{route('user.list')}}" class="btn btn-default">Cancel</a>
                            </div>
                            <!-- /.box-footer -->
                        </form>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.col -->
    </section>
</div>

<!-- /.content -->
@endsection

@section('libraryjs')
<!-- use the latest vue-select release -->
<script>
    window.vue = new Vue({
        //Thành phần áp dụng
        el: "#app",
        //khai báo dữ liệu ban đầu
        data: {
            errors: [],
            dataEditUser: {
                id: '',
                name: '',
                email: '',
                user_name: '',
                password_user: '',
                password_user_confim: '',
            },
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            onSubmit(){
                loading.show();
                var _this = this;
                $.ajax({
                    url: "{{route('user.edit', ['id' => request()->route()->parameters['id']])}}",
                    type: 'POST',
                    data: _this.dataEditUser,
                    headers:  { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response){
                        if(response.error){
                            msgCustom('error', response.error);
                        }
                        if(response.success){
                            msgCustom('success', response.success);
                            _this.errors = [];
                            location.replace("{{route('user.list')}}")
                        }
                        _this.getInforUser();
                        loading.hide();
                    },
                    error: function(error){
                        if (error.status == 422){
                            _this.errors = error.responseJSON.errors;
                        }
                        loading.hide();
                    },
                });
            },
            getInforUser(){
                loading.show();
                var _this = this;
                $.ajax({
                    url: "{{route('user.detail', ['id' => request()->route()->parameters['id']])}}",
                    type: 'GET',
                    headers:  { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response){
                        _this.dataEditUser = {...response.user};
                        loading.hide();
                    },
                    error: function(error){
                        console.error(error);
                        loading.hide();
                    },
                })
            },
        },
        //sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
        computed: {
            isRoleAdmin(){
                if(this.dataEditUser.role.option == 'admin'){
                    this.dataEditUser.permission = [];
                    return true;
                }
                return false;
            },
        },
        // Xảy ra trước khi khởi tạo, data và event được khai báo chư tự thay đổi khi cập nhật
        beforeCreate() {
        },
        //Khởi tạo giống như __contructor của react, diễn ra trước quá trình tạo DOM
        created() {
            this.getInforUser();
        },
        // Trước khi có đầy đủ quyền truy cập vào phần tử DOM
        beforeMount() {
        },
        // mounted khi đã có đầy đủ quyền truy cập vào phần tử DOM
        mounted() {
            $("#app").show();
        },
        //xử lý trước khi dữ liệu bị thay đổi
        beforeUpdate() {
        },
        //Xử lý khi dữ liệu đã thay đổi
        updated() {
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
