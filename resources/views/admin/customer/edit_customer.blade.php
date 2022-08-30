@extends('admin.layout')
@section('titlePage', '顧客情報登録')
@section('stylecss')
@endsection

@section('main')
<!-- Main content -->
<div id="app">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- /.box-header -->
                    <div class="card custom-from-customer">
                        <div class="card-header">
                            <h2> 顧客情報登録 </h2>
                        </div>
                        <form role="form" method="POST" v-on:submit.prevent="handleOnSubmit">
                            <div class="card-body">

                                <div class="form-group row" v-bind:class="[ errors.customer_id ? 'has-error' : '']">
                                    <label class="col-sm-2 col-form-label col-form-label-center" for="customer_id">
                                        MR顧客ID
                                        <span class="required">※</span>
                                    </label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" v-model="datas.customer_id"
                                            id="customer_id" name="customer_id" disabled>
                                        <div v-if="errors.customer_id">
                                            <span v-for="error of errors.customer_id" class="help-block"
                                                v-html="error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row"
                                    v-bind:class="[ (errors.lastname || errors.firstname) ? 'has-error' : '']">
                                    <label class="col-sm-2 col-form-label col-form-label-center" for="lastname">
                                        氏名
                                        <span class="required">※</span>
                                    </label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" v-model="datas.lastname" id="lastname"
                                            name="lastname">
                                        <div v-if="errors.lastname">
                                            <span v-for="error of errors.lastname" class="help-block"
                                                v-html="error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" v-model="datas.firstname" id="firstname"
                                            name="firstname">
                                        <div v-if="errors.firstname">
                                            <span v-for="error of errors.firstname" class="help-block"
                                                v-html="error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row"
                                    v-bind:class="[ (errors.lastname_kana || errors.firstname_kana) ? 'has-error' : '']">
                                    <label class="col-sm-2 col-form-label col-form-label-center" for="">
                                        氏名（カナ）
                                        <span class="required">※</span>
                                    </label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" v-model="datas.lastname_kana"
                                            id="lastname_kana" name="lastname_kana">
                                        <div v-if="errors.lastname_kana">
                                            <span v-for="error of errors.lastname_kana" class="help-block"
                                                v-html="error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" v-model="datas.firstname_kana"
                                            id="firstname_kana" name="firstname_kana">
                                        <div v-if="errors.firstname_kana">
                                            <span v-for="error of errors.firstname_kana" class="help-block"
                                                v-html="error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row" v-bind:class="[ errors.name ? 'has-error' : '']">
                                    <label class="col-sm-2 col-form-label col-form-label-center">
                                        性別</label>
                                    <div class="col-sm-3 custom-control custom-radio">
                                        <div class="custom-inline-block">
                                            <input class="custom-control-input disable-position" v-model="datas.sex"
                                                type="radio" id="sex1" name="sex" value="0">
                                            <label for="sex1" class="custom-control-label">男性</label>
                                        </div>
                                        <div class="custom-inline-block">
                                            <input class="custom-control-input disable-position" v-model="datas.sex"
                                                type="radio" id="sex2" name="sex" value="1">
                                            <label for="sex2" class="custom-control-label">女性</label>
                                        </div>
                                        <div class="custom-inline-block">
                                            <input class="custom-control-input disable-position" v-model="datas.sex"
                                                type="radio" id="sex3" name="sex" value="">
                                            <label for="sex3" class="custom-control-label">不明</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row" v-bind:class="[ errors.birthday ? 'has-error' : '']">
                                    <label class="col-sm-2 col-form-label col-form-label-center" for="">
                                        誕生日
                                    </label>
                                    <div class="col-sm-3">
                                        <div class="input-group date">
                                            <input class="form-control" type="text" id="birthday"
                                                v-model="datas.birthday" name="birthday" autocomplete="off">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        <div v-if="errors.birthday">
                                            <span v-for="error of errors.birthday" class="help-block"
                                                v-html="error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row" v-bind:class="[ errors.email ? 'has-error' : '']">
                                    <label class="col-sm-2 col-form-label col-form-label-center" for="">
                                        メールアドレス
                                        <span class="required">※</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" v-model="datas.email" id="email"
                                            name="email">
                                        <div v-if="errors.email">
                                            <span v-for="error of errors.email" class="help-block"
                                                v-html="error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row" v-bind:class="[ errors.telephone ? 'has-error' : '']">
                                    <label class="col-sm-2 col-form-label col-form-label-center" for="">
                                        電話番号
                                    </label>
                                    <div class="col-sm-7">
                                        <div class="custom-inline-block form-element-half-29">
                                            <input type="text" class="form-control" v-model="telephone1" id="telephone1"
                                                name="telephone1">
                                        </div>
                                        <span class="form-span-half-3">-</span>
                                        <div class="custom-inline-block form-element-half-29">
                                            <input type="text" class="form-control" v-model="telephone2" id="telephone2"
                                                name="telephone2">
                                        </div>
                                        <span class="form-span-half-3">-</span>
                                        <div class="custom-inline-block form-element-half-29">
                                            <input type="text" class="form-control" v-model="telephone3" id="telephone3"
                                                name="telephone3">
                                        </div>
                                        <div v-if="errors.telephone">
                                            <span v-for="error of errors.telephone" class="help-block"
                                                v-html="error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row" v-bind:class="[ errors.fax ? 'has-error' : '']">
                                    <label class="col-sm-2 col-form-label col-form-label-center" for="">FAX番号</label>
                                    <div class="col-sm-7">
                                        <div class="custom-inline-block form-element-half-29">
                                            <input type="text" class="form-control" v-model="fax1" name="fax1">
                                        </div>
                                        <span class="form-span-half-3">-</span>
                                        <div class="custom-inline-block form-element-half-29">
                                            <input type="text" class="form-control" v-model="fax2" name="fax2">
                                        </div>
                                        <span class="form-span-half-3">-</span>
                                        <div class="custom-inline-block form-element-half-29">
                                            <input type="text" class="form-control" v-model="fax3" name="fax3">
                                        </div>
                                        <div v-if="errors.fax">
                                            <span v-for="error of errors.fax" class="help-block" v-html="error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row"
                                    v-bind:class="[ (errors.postcode || errors.city || errors.address_1 || errors.address_2) ? 'has-error' : '']">
                                    <label class="col-sm-2 col-form-label col-form-label-center" for="">
                                        住所</label>
                                    <div class="col-sm-10 col-md-8">
                                        <div class="adress-item">
                                            <span class="form-span-half-3">〒</span>
                                            <div class="custom-inline-block form-element-half-25">
                                                <input type="text" class="form-control" v-model="postcode1"
                                                    id="postcode" name="postcode1">
                                            </div>
                                            <span class="form-span-half-3">-</span>
                                            <div class="custom-inline-block form-element-half-30">
                                                <input type="text" class="form-control" v-model="postcode2"
                                                    id="postcode2" name="postcode2">
                                            </div>
                                            <div class="custom-inline-block form-element-half-30">
                                                <button type="button"
                                                    class="btn btn-primary pull-right btn-custom btn-custom-primary"
                                                    v-on:click.prevent="handleSearchAddress">住所検索</button>
                                            </div>
                                            <div v-if="errors.postcode">
                                                <span v-for="error of errors.postcode" class="help-block"
                                                    v-html="error"></span>
                                            </div>
                                        </div>
                                        <div class="adress-item">
                                            <select class="form-control form-element-half-62" v-model="datas.city">
                                                <option value=""></option>
                                                @foreach ($citys as $city)
                                                <option value="{{ $city->prefecture }}">{{ $city->prefecture }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <div v-if="errors.city">
                                                <span v-for="error of errors.city" class="help-block"
                                                    v-html="error"></span>
                                            </div>
                                        </div>
                                        <div class="adress-item">
                                            <input type="text" class="form-control form-element-half-80"
                                                v-model="datas.address_1" id="address_1" name="address_1">
                                            <div v-if="errors.address_1">
                                                <span v-for="error of errors.address_1" class="help-block"
                                                    v-html="error"></span>
                                            </div>
                                        </div>
                                        <div class="adress-item">
                                            <input type="text" class="form-control form-element-half-80"
                                                v-model="datas.address_2" id="address_2" name="address_2">
                                            <div v-if="errors.address_2">
                                                <span v-for="error of errors.address_2" class="help-block"
                                                    v-html="error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row" v-bind:class="[ errors.newsletter ? 'has-error' : '']">
                                    <label class="col-sm-2 col-form-label col-form-label-center" for="">メール配信</label>
                                    <div class="col-sm-10 custom-control custom-radio">
                                        <div class="custom-inline-block">
                                            <input class="custom-control-input disable-position"
                                                v-model="datas.newsletter" type="radio" id="newsletter1"
                                                name="newsletter" value="1">
                                            <label for="newsletter1" class="custom-control-label">許可</label>
                                        </div>
                                        <div class="custom-inline-block">
                                            <input class="custom-control-input disable-position"
                                                v-model="datas.newsletter" type="radio" id="newsletter2"
                                                name="newsletter" value="0">
                                            <label for="newsletter2" class="custom-control-label">不可</label>
                                        </div>
                                        <div class="custom-inline-block">
                                            <input class="custom-control-input disable-position"
                                                v-model="datas.newsletter" type="radio" id="newsletter3"
                                                name="newsletter" value="9">
                                            <label for="newsletter3" class="custom-control-label">不明</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row" v-bind:class="[ errors.status ? 'has-error' : '']">
                                    <label class="col-sm-2 col-form-label col-form-label-center" for="">顧客ステータス</label>
                                    <div class="col-sm-10 custom-control custom-radio">
                                        <div class="custom-inline-block">
                                            <input class="custom-control-input disable-position" v-model="datas.status"
                                                type="radio" id="status1" name="status" value="1">
                                            <label for="status1" class="custom-control-label">有効</label>
                                        </div>
                                        <div class="custom-inline-block">
                                            <input class="custom-control-input disable-position" v-model="datas.status"
                                                type="radio" id="status2" name="status" value="0">
                                            <label for="status2" class="custom-control-label">無効</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row justify-content-center">
                                    <a href="{{ $urlPrevious }}"
                                        class="btn btn-default btn-custom btn-custom-default">キャンセル</a>
                                    <button type="submit"
                                        class="btn btn-primary pull-center btn-custom btn-custom-primary">更新</button>
                                </div>
                            </div>
                            <!-- /.box-footer -->
                        </form>
                    </div>
                    <!-- /.box-body -->
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
    Vue.prototype.$axios = axios;
    Vue.prototype.$jquery = $;
    window.vue = new Vue({
            //Thành phần áp dụng
            el: '#app',
            //khai báo dữ liệu ban đầu
            data: {
                errors: [],
                datas: {
                    customer_id: '{{ $customer->customer_id }}',
                    lastname: '{{ $customer->lastname }}',
                    firstname: '{{ $customer->firstname }}',
                    lastname_kana: '{{ $customer->lastname_kana }}',
                    firstname_kana: '{{ $customer->firstname_kana }}',
                    sex: '{{ $customer->sex }}',
                    birthday: '{{ $customer->birthday }}',
                    email: '{{ $customer->email }}',
                    telephone: '{{ $customer->telephone }}',
                    fax: '{{ $customer->fax }}',
                    postcode: '{{ $customer->postcode }}',
                    city: '{{ $customer->city }}',
                    address_1: '{{ $customer->address_1 }}',
                    address_2: '{{ $customer->address_2 }}',
                    newsletter: '{{ $customer->newsletter }}',
                    status: '{{ $customer->status }}',
                },
                postcode2: '',
                postcode1: '',
                telephone1: '',
                telephone2: '',
                telephone3: '',
                fax1: '',
                fax2: '',
                fax3: '',
            },
            //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
            methods: {
                //Sự kiện nhấn button search địa chỉ
                handleSearchAddress() {
                    var _this = this;
                    var postCode = _this.postcode1.replace(/\D/g, "") + _this.postcode2.replace(/\D/g, "");
                    if (postCode.length == 7) {
                        loading.show();
                        _this.postcode1 = postCode.substr(0, 3);
                        _this.postcode2 = postCode.substr(-4);
                        $.ajax({
                            url: "{{ route('customer.getPostalCode') }}",
                            post: 'GET',
                            data: {
                                postalCode: postCode,
                            },
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            success: function(response) {
                                if (response.errors) {
                                    _this.errors = response.errors;
                                } else {
                                    _this.errors = [];
                                    _this.datas.city = response.prefecture;
                                    _this.datas.address_1 = response.city + response.sub_address;
                                }
                                loading.hide();
                            },
                            error: function(error) {
                                console.error(error);
                                loading.hide();
                            },
                        });
                    } else {
                        _this.errors = {
                            postcode: ['郵便番号が正しくありません', ],
                        };
                    }
                },
                //Sự kiện gửi dữ liệu đến controller xử lý lưu
                handleOnSubmit() {
                    var _this = this;
                    _this.hanldeData;
                    alertAction('save user', '', function () {
                        let url = "{{ route('customer.save') }}";
                        loading.show();
                        _this.$axios.post(url, _this.datas)
                            .then(response => {
                                console.log(response);
                                if(response.status == 200){
                                    let result = response.data;
                                    if (result.error) {
                                        msgCustom('error', result.error);
                                    }
                                    if (result.success) {
                                        _this.errors = [];
                                        msgCustom('success', result.success);
                                        window.location.href = "{!!$urlPrevious!!}";
                                    }
                                    loading.hide();
                                }else{
                                    if (response.response && response.response.status == 422) {
                                        let errors = response.response;
                                        _this.errors = errors.data.errors;
                                    }
                                    loading.hide();
                                }
                                _this.jquery("#js-search-properties").modal('hide');
                                loading.hide();
                            })
                            .catch((error) => {
                                console.error("Error axios: ",error);
                                loading.hide();
                            });
                    }, 'Yes, save now!');
                },
                //Event js
                handleEventJS() {
                    var _this = this;
                    if (this.datas.postcode != '') {
                        var postCode = this.datas.postcode.replace(/\D/g, "");
                        this.postcode1 = postCode.substr(0, 3)
                        this.postcode2 = postCode.substr(-4);
                    }
                    if (this.datas.telephone != '') {
                        this.telephone1 = this.datas.telephone.substr(0, 3);
                        this.telephone2 = this.datas.telephone.substr(4, 4);
                        this.telephone3 = this.datas.telephone.substr(7, 4);
                    }
                    if (this.datas.fax != '') {
                        this.fax1 = this.datas.fax.substr(0, 3);
                        this.fax2 = this.datas.fax.substr(4, 4);
                        this.fax3 = this.datas.fax.substr(7, 4);
                    }
                    _this.jquery('#birthday').datepicker({
                        onSelect: function(dateText) {
                            var name = _this.jquery(this).attr("name");
                            _this.datas.birthday = dateText;
                        }
                    });
                },
            },
            //sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
            computed: {
                //Sync data for half field in form
                hanldeData() {
                    var postCode = this.postcode1.replace(/\D/g, "") + this.postcode2.replace(/\D/g, "");
                    this.datas.postcode = postCode;
                    this.datas.telephone = this.telephone1.replace(/\D/g, "") + this.telephone2.replace(/\D/g, "") +
                        this.telephone3.replace(/\D/g, "");
                    this.datas.fax = this.fax1.replace(/\D/g, "") + this.fax2.replace(/\D/g, "") + this.fax3
                        .replace(/\D/g, "");
                },
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
                this.handleEventJS();
            },
            //xử lý trước khi dữ liệu bị thay đổi
            beforeUpdate() {
            },
            //Xử lý khi dữ liệu đã thay đổi
            updated() {
            },
            //Xử lý trước khi hủy đối tượng
            beforeDestroy() {
                delete this.$axios;
                delete this.$jquery;
                delete this.data;
            },
            //Xử lý khi hủy đối tượng
            destroyed() {
            },

        });

</script>
@endsection
