@extends('admin.layout')
@section('titlePage', 'ユーザー管理')
@section('stylecss')
@endsection
@section('main')
<!-- Main content -->
<div id="app">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>
                                ユーザー管理
                                <span class="sub-title"> / Add User</span>
                            </h2>
                        </div>
                        <!-- /.box-header -->
                        <form role="form" method="POST" v-on:submit.prevent="handleOnSubmit">
                            <div class="card-body">
                                <div v-bind:class="[ errors.name ? 'form-group has-error' : 'form-group']">
                                    <label class="control-label" for="name">Name</label>
                                    <input type="text" class="form-control" id="name" v-model="dataAddUser.name"
                                        name="name" placeholder="Full Name">
                                    <div v-if="errors.name">
                                        <span v-for="error of errors.name" class="help-block" v-html="error"></span>
                                    </div>
                                </div>

                                <!-- text input -->
                                <div v-bind:class="[ errors.email ? 'form-group has-error' : 'form-group']">
                                    <label class="control-label" for="email">Email</label>
                                    <input type="text" class="form-control" id="email" v-model="dataAddUser.email"
                                        name="email" placeholder="Email">
                                    <div v-if="errors.email">
                                        <span v-for="error of errors.email" class="help-block" v-html="error"></span>
                                    </div>
                                </div>

                                <div v-bind:class="[ errors.user_name ? 'form-group has-error' : 'form-group']">
                                    <label class="control-label" for="user_name">User Name</label>
                                    <input type="text" class="form-control" id="user_name"
                                        v-model="dataAddUser.user_name" name="user_name" placeholder="User name"
                                        value="dataAddUser.user_name">
                                    <div v-if="errors.user_name">
                                        <span v-for="error of errors.user_name" class="help-block"
                                            v-html="error"></span>
                                    </div>
                                </div>

                                <!-- password input -->
                                <div v-bind:class="[ errors.password_user ? 'form-group has-error' : 'form-group']">
                                    <label class="control-label" for="password">Password</label>
                                    <input type="password" class="form-control" id="password_user"
                                        v-model="dataAddUser.password_user" name="password_user" placeholder="Password">
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
                                        v-model="dataAddUser.password_user_confim" name="password_user_confim"
                                        placeholder="Password Confirm">
                                    <div v-if="errors.password_user_confim">
                                        <span v-for="error of errors.password_user_confim" class="help-block"
                                            v-html="error"></span>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary pull-right">Save</button>
                                    <a href="{{route('user.list')}}" class="btn btn-default">Cancel</a>
                                </div>
                                <!-- /.box-footer -->
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.box-body -->
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
            dataAddUser: {
                name: '',
                email: '',
                user_name: '',
                password_user: '',
                password_user_confim: '',
            },
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            handleOnSubmit(){
                loading.show();
                var _this = this;
                $.ajax({
                    url: "{{route('user.add')}}",
                    type: 'POST',
                    data: this.dataAddUser,
                    headers:  { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response){
                        if(response.error){
                            msgCustom('error', response.error);
                        }
                        if(response.success){
                            msgCustom('success', response.success);
                            location.replace("{{route('user.list')}}")
                        }
                        _this.errors = [];
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
        },
        //sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
        computed: {
            isRoleAdmin(){
                if(this.dataAddUser.role.option == 'admin'){
                    this.dataAddUser.permission = [];
                    return true;
                }
                return false;
            }
        },
        // Xảy ra trước khi khởi tạo, data và event được khai báo chư tự thay đổi khi cập nhật
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
