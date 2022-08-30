@extends('admin.layout')

@section('titlePage', 'バッチ管理')

<!-- add libs, code css other -->
@section('stylecss')
<style>
.max-width-50 {
    max-width: 50px;
}
.text-long-custom {
    white-space: nowrap !important;
    overflow: hidden;
    text-overflow: ellipsis;
}
.circle {
    height: 1rem;
    width: 1rem;
    margin: auto;
    border-radius: 4rem;
}
.batch-active
{
    background:#3c8dbc;
}
.batch-error
{
    background:red;
}
.batch-stop
{
    background:#d2d6de;
}
.large.tooltip-inner {
    max-width: 450px;
    width: 450px;
}
</style>
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
                            <h2> バッチ管理 </h2>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="data-list" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-lg-center">
                                                #
                                            </th>
                                            <th class="text-lg-center">
                                                Command
                                            </th>
                                            <th class="text-lg-center">
                                                Description
                                            </th>
                                            <th class="text-lg-center">
                                                Error message
                                            </th>
                                            <th class="text-lg-center">
                                                Status
                                            </th>
                                            <th class="text-lg-center">
                                                Last execute
                                            </th>
                                            <th class="text-lg-center">
                                                Process
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="!dataNotFound">
                                        <tr v-for="(item, index) of datas" :key="item.status_flag + item.signature" :class="item.is_active === 0 ? 'bg-secondary' : ''">
                                            <td :data-title="(index + 1)" class="first-column text-center">
                                                @{{ index + 1 }}
                                            </td>
                                            <td :data-title="(item.signature)" class="first-column text-left">
                                                @{{ item.signature }}
                                            </td>
                                            <td :data-title="(item.description)" class="first-column text-left">
                                                @{{ item.description }}
                                            </td>
                                            <td id="batch-message" :data-title="(item.error_message)" class="first-column text-left max-width-50 text-long-custom" data-toggle="tooltip" data-placement="top" >
                                                @{{ item.error_message }}
                                            </td>
                                            <td class="first-column text-center">
                                                <div :class="'circle ' + (item.status_flag === 0 ? 'batch-stop' : (item.status_flag === 1 ? 'batch-active' : 'batch-error'))"></div>
                                            </td>
                                            <td :data-title="(item.last_execute)" class="first-column text-center">
                                                @{{ item.last_execute }}
                                            </td>
                                            <td class="first-column text-center">
                                                <button class="btn btn-danger btn-xs" :disabled="(item.status_flag === 0 ? false : true)" v-on:click.prevent="handleExecute(item.signature)">Execute</button>
                                                <button class="btn btn-primary btn-xs" style="width: 55.28px;" v-on:click.prevent="handleActiveDisactive(item.signature, item.is_active)">@{{item.is_active === 0 ? 'Active' : 'Disactive'}}</button>
                                                <button class="btn btn-success btn-xs" v-on:click.prevent="handleReset(item.signature)">リセット</button>
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
            },
            first:true,
            dataNotFound:false,
            intervalReal: '',
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            //Xử lý lấy dữ liệu
            handleGetData(){
                clearInterval(this.intervalReal);
                var _this = this;
                if (this.first) {
                    $(".loading").show();
                }
                $.ajax({
                    url: '{{route("batch.getDatas")}}',
                    type: "GET",
                    success: function(result){
                        _this.datas = result;
                        _this.first = false;
                        $(".loading").hide();
                    },
                    error: function(error){
                        console.error(error);
                        $(".loading").hide();
                    },
                })
            },
            handleActiveDisactive(signature, active = 0) {
                let _this = this;
                let title = '';
                if(active == 1){
                    title = "disactive";
                }else{
                    title = "active";
                }
                alertAction(title, signature, function () {
                    $(".loading").show();
                    _this.first = true;
                    $.ajax({
                        url: '{{route("batch.activeDisactive")}}',
                        type: "POST",
                        data: {signature: signature},
                        success: function(result){
                            msgCustom('success', result.message, 2000, 'top-end');
                            _this.handleGetData();
                            $(".loading").hide();
                        },
                        error: function(error){
                            console.error(error);
                            $(".loading").hide();
                        },
                    });
                }, 'Yes, '+title+" now!");
            },
            handleReset(signature) {
                let _this = this;
                alertAction('reset', signature, function () {
                    $(".loading").show();
                    _this.first = true;
                    $.ajax({
                        url: '{{route("batch.reset")}}',
                        type: "POST",
                        data: {signature: signature},
                        success: function(result){
                            msgCustom('success', result.message, 2000, 'top-end');
                            _this.handleGetData();
                            $(".loading").hide();
                        },
                        error: function(error){
                            console.error(error);
                            $(".loading").hide();
                        },
                    });
                }, 'Yes, reset now!');
            },
            handleExecute(signature) {
                let _this = this;
                alertAction('execute', signature, function () {
                    $(".loading").show();
                    _this.first = true;
                    $.ajax({
                        url: '{{route("batch.execute")}}',
                        type: "POST",
                        data: {signature: signature},
                        success: function(result){
                            msgCustom('success', result.message, 2000, 'top-end');
                            _this.handleGetData();
                            $(".loading").hide();
                        },
                        error: function(error){
                            console.error(error);
                            $(".loading").hide();
                        },
                    });
                }, 'Yes, execute now!');
            }
        },
        //Sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
        //Chỉ cho những sự kiện không truyền tham số
        computed: {
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
        },
        //xử lý trước khi dữ liệu bị thay đổi
        beforeUpdate() {
        },
        //Xử lý khi dữ liệu đã thay đổi
        updated() {
            $('#batch-message').tooltip({
                boundary: 'window',
                template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner large"></div></div>'
            });
            let _this = this;
            this.intervalReal = setInterval(() => {
                _this.handleGetData();
            }, 10000);
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
