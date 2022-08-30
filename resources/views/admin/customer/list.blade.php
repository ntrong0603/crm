@extends('admin.layout')
@section('titlePage', '顧客管理')
{{-- add libs, code css other --}}
@section('stylecss')
@endsection

@section('main')

{{-- Main content --}}
<div id="app" style="display: none;">
    <section class="content customer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2> 顧客管理 </h2>
                        </div>
                        {{-- /.card-header --}}
                        <div class="card-body">
                            <div>
                                <form role="form-horizontal" class="bkg-cutomer-form_search"
                                    v-on:submit.prevent="handleGetData(true)">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label for="search-text"
                                                class="col-12 col-sm-12 col-md-3 col-xl-2 col-form-label">キーワードで検索</label>
                                            <div class="col-12 col-sm-10 col-md-4 col-xl-3">
                                                <input type="text" v-model="paramSearch.searchText" class="form-control"
                                                    id="search-text">
                                            </div>
                                            <div class="col-12 col-sm-2 col-md-2 col-xl-1 text-right">
                                                <button type="submit"
                                                    class="btn btn-primary btn-custom btn-custom-primary btn-search-customer">検索</button>
                                            </div>
                                            <div class="col-12 col-sm-2 col-md-2 col-xl-1 text-right">
                                                <a v-on:click="handleClearSearch"
                                                    class="btn btn-danger btn-custom btn-custom-success btn-search-customer"
                                                    style="color: #fff;    text-decoration: none; min-width: 90px;">リセット</a>
                                            </div>
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
                                {{-- <div class="flex-box justify-content-end">
                                    <a href="{{route('customer.add')}}"
                                class="btn btn-primary btn-custom-primary">新規登録</a>
                            </div> --}}
                        </div>
                        <Pagination v-bind:pagination-class="paginationClass" v-bind:last-page="arrData.last_page"
                            v-bind:total="arrData.total" v-bind:current-page="arrData.current_page"
                            v-bind:per-page="paramSearch.perPage" v-on:update:per-page="paramSearch.perPage = $event"
                            v-on:update:data="handleGetData" v-on:update:page="paramSearch.page = $event"
                            v-on:update:loadHistory="loadHistory = $event"></Pagination>
                        <ul class="customer-tab">
                            <li class="customer-tab_item" v-on:click.prevent="changeTab(true)"
                                v-bind:class="[ tabInfoIsActive ? 'active' : '']">
                                購買情報
                            </li>
                            <li class="customer-tab_item" v-on:click.prevent="changeTab(false)"
                                v-bind:class="[ !tabInfoIsActive ? 'active' : '']">
                                属性情報
                            </li>
                        </ul>
                        <div class="table-responsive">
                            <table id="example2" class="table table-bordered table-hover" v-bind:class="showTab">
                                <thead>
                                    <tr>
                                        <th class="text-lg-center">
                                            <a data-name-column="oc_customer.customer_id">
                                                MR顧客ID
                                            </a>
                                        </th>
                                        <th class="text-lg-center">
                                            <a data-name-column="oc_customer.lastname">
                                                名前
                                            </a>
                                        </th>
                                        <th class="tab-infor text-lg-center">
                                            <a data-name-column="oc_customer.buy_total">
                                                売上累計
                                            </a>
                                        </th>
                                        <th class="tab-infor text-lg-center">
                                            <a data-name-column="oc_customer.buy_times">
                                                購入回数
                                            </a>
                                        </th>
                                        <th class="tab-infor text-lg-center">
                                            <a data-name-column="oc_customer.first_buy_date">
                                                初回購入日
                                            </a>
                                        </th>
                                        <th class="tab-infor text-lg-center">
                                            <a data-name-column="oc_customer.last_buy_date">
                                                最終購入日
                                            </a>
                                        </th>
                                        <th class="tab-infor text-lg-center">
                                            在籍期間（日）
                                        </th>
                                        <th class="tab-infor text-lg-center">
                                            離脱期間（日）
                                        </th>
                                        <th class="tab-infor text-lg-center">
                                            <a data-name-column="oc_customer.customer_rank">
                                                顧客ﾗﾝｸ
                                            </a>
                                        </th>
                                        <th class="tab-infor text-lg-center">
                                            <a data-name-column="oc_customer.life_time_value">
                                                LTV
                                            </a>
                                        </th>
                                        <th class="tab-infor text-lg-center"></th>

                                        <th class="tab-detail text-lg-center">
                                            <a data-name-column="oc_customer.telephone">
                                                電話番号
                                            </a>
                                        </th>
                                        <th class="tab-detail text-lg-center">
                                            <a data-name-column="oc_customer.email">
                                                メールアドレス
                                            </a>
                                        </th>
                                        <th class="tab-detail text-lg-center">
                                            <a data-name-column="oc_customer.newsletter">
                                                ﾒｰﾙ配信
                                            </a>
                                        </th>
                                        <th class="tab-detail text-lg-center">
                                            <a data-name-column="oc_address.postcode">
                                                郵便番号
                                            </a>
                                        </th>
                                        <th class="tab-detail text-lg-center">
                                            <a data-name-column="oc_address.city">
                                                都道府県
                                            </a>
                                        </th>
                                        <th class="tab-detail text-lg-center">
                                            住所
                                        </th>
                                        <th class="tab-detail text-lg-center">
                                            <a data-name-column="oc_customer.sex">
                                                性別
                                            </a>
                                        </th>
                                        <th class="tab-detail text-lg-center">
                                            <a data-name-column="oc_customer.birthday">
                                                年齢
                                            </a>
                                        </th>
                                        <th class="tab-detail text-lg-center">
                                            <a data-name-column="oc_customer.status">
                                                顧客ｽﾃｰﾀｽ
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody v-if="!dataNotFound">
                                    <tr v-for="item of arrData.data" :key="item.customer_id">
                                        <td data-title="MR顧客ID" class="text-right">@{{item.customer_id}}</td>
                                        <td data-title="名前" class="first-column text-left">@{{item.firstname}}
                                            @{{item.lastname}}
                                        </td>
                                        <td class="tab-infor text-right" data-title="売上累計">
                                            @{{item.buy_total}}</td>
                                        <td class="tab-infor text-right" data-title="購入回数">
                                            @{{item.buy_times}}</td>
                                        <td class="tab-infor text-left" data-title="初回購入日">
                                            @{{hanldeFormatDate(item.first_buy_date)}}</td>
                                        <td class="tab-infor text-left" data-title="最終購入日">
                                            @{{hanldeFormatDate(item.last_buy_date)}}</td>
                                        <td class="tab-infor text-right" data-title="在籍期間（日）">
                                            @{{handleFormatNumber(handleSubDay(item.first_buy_date, item.last_buy_date))}}
                                        </td>
                                        <td class="tab-infor text-right" data-title="離脱期間（日）">
                                            @{{handleFormatNumber(handleSubDay(item.last_buy_date, new Date()))}}
                                        </td>
                                        <td class="tab-infor text-lg-left" data-title="顧客ﾗﾝｸ">
                                            @{{handleShowTextRankCust(item.customer_rank)}}</td>
                                        <td class="tab-infor text-right" data-title="LTV">
                                            @{{item.life_time_value}}</td>
                                        <td class="admin-action-edit-or-delete tab-infor text-lg-center"
                                            data-title="Edit/Delete">
                                            <a v-bind:href="handleHrefEdit(item.customer_id)" class="btn-edit"
                                                title="edit">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <div class="clearfix"></div>
                                        </td>

                                        <td class="tab-detail text-right" data-title="電話番号">
                                            @{{item.telephone}}</td>
                                        <td class="tab-detail text-left" data-title="メールアドレス">
                                            @{{item.email}}</td>
                                        <td class="tab-detail text-lg-left" data-title="ﾒｰﾙ配信">
                                            @{{item.newsletter == 1 ? '許可' : item.newsletter == 9 ? '配信時エラー' : '不可'}}
                                        </td>
                                        <td class="tab-detail text-right" data-title="郵便番号">
                                            @{{item.postcode}}</td>
                                        <td class="tab-detail text-left" data-title="都道府県">
                                            @{{item.city}}</td>
                                        <td class="tab-detail text-left" data-title="住所">
                                            @{{item.address_1 +''+item.address_2}}
                                        </td>
                                        <td class="tab-detail text-lg-left" data-title="性別">
                                            @{{item.sex ? "女性" : "男性"}}
                                        </td>
                                        <td class="tab-detail text-lg-center" data-title="年齢">
                                            @{{handleCalAge(item.birthday)}}</td>
                                        <td class="tab-detail text-lg-left" data-title="顧客ｽﾃｰﾀｽ">
                                            @{{item.status ? "有効" : "無効(退会)"}}
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
                        <Pagination v-bind:pagination-class="paginationClass" v-bind:last-page="arrData.last_page"
                            v-bind:total="arrData.total" v-bind:current-page="arrData.current_page"
                            v-bind:per-page="paramSearch.perPage" v-on:update:per-page="paramSearch.perPage = $event"
                            v-on:update:data="handleGetData" v-on:update:page="paramSearch.page = $event"
                            v-on:update:loadHistory="loadHistory = $event"></Pagination>
                    </div>
                    {{-- /.card-body --}}
                </div>
            </div>
            {{-- /.col --}}
        </div>
        {{-- /.row --}}
    </section>
    {{--model condition search --}}
    <div class="modal fade search-properties" id="js-search-properties">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <dl class="search-properties_item">
                        <dt class="search-properties_item-title">
                            受信可否属性
                        </dt>
                        <dd class="search-properties_item-condition">
                            <ul
                                class="search-properties_item-condition_selected search-properties_item-condition_selected-25">
                                <li><label><input type="checkbox" v-model="paramSearch.newsLetter" class="news-letter"
                                            value="1">
                                        受信許可している顧客</label></li>
                                <li><label><input type="checkbox" v-model="paramSearch.newsLetter" class="news-letter"
                                            value="0">
                                        受信意志が不明な顧客</label></li>
                                <li><label><input type="checkbox" v-model="paramSearch.newsLetter" class="news-letter"
                                            value="9">
                                        受信拒否している顧客</label></li>
                            </ul>
                        </dd>
                    </dl>
                    <dl class="search-properties_item">
                        <dt class="search-properties_item-title">
                            対象顧客
                        </dt>
                        <dd class="search-properties_item-condition">
                            <ul
                                class="search-properties_item-condition_selected search-properties_item-condition_selected-all">
                                <li>
                                    <label>
                                        <input type="checkbox" value="" v-on:change="handleCheckAll('customerRank')"
                                            id="js-chekbox-all-customerRank">
                                        すべてにチェックを入れる
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" class="customerRank" v-model="paramSearch.customerRank"
                                            value="1">
                                        新規客
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" class="customerRank" v-model="paramSearch.customerRank"
                                            value="2">
                                        入門客
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" class="customerRank" v-model="paramSearch.customerRank"
                                            value="3">
                                        安定客
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" class="customerRank" v-model="paramSearch.customerRank"
                                            value="4">
                                        流行客
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" class="customerRank" v-model="paramSearch.customerRank"
                                            value="5">
                                        優良客
                                    </label>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl class="search-properties_item">
                        <dt class="search-properties_item-title">
                            LTV
                        </dt>
                        <dd class="search-properties_item-condition">
                            <input type="text" v-model="paramSearch.ltvFrom"> ～ <input type="text"
                                v-model="paramSearch.ltvTo">
                        </dd>
                    </dl>
                    <dl class="search-properties_item search-properties_item_half">
                        <dt class="search-properties_item-title">
                            売上累計
                        </dt>
                        <dd class="search-properties_item-condition">
                            <input type="text" v-model="paramSearch.uriFrom"> ～ <input type="text"
                                v-model="paramSearch.uriTo"> 円
                        </dd>
                    </dl>
                    <dl class="search-properties_item search-properties_item_half">
                        <dt class="search-properties_item-title">
                            購入回数
                        </dt>
                        <dd class="search-properties_item-condition">
                            <input type="text" v-model="paramSearch.buyTimesFrom"> ～ <input type="text"
                                v-model="paramSearch.buyTimesTo"> 回
                        </dd>
                    </dl>
                    <dl class="search-properties_item search-properties_item_half">
                        <dt class="search-properties_item-title">
                            年齢
                        </dt>
                        <dd class="search-properties_item-condition">
                            <input type="text" v-model="paramSearch.agesFrom"> ～ <input type="text"
                                v-model="paramSearch.agesTo"> 回
                        </dd>
                    </dl>
                    <dl class="search-properties_item search-properties_item_half">
                        <dt class="search-properties_item-title">
                            性別
                        </dt>
                        <dd class="search-properties_item-condition">
                            <ul
                                class="search-properties_item-condition_selected search-properties_item-condition_selected-25">
                                <li>
                                    <label>
                                        <input type="checkbox" v-on:change="handleCheckAll('sex')"
                                            id="js-chekbox-all-sex">
                                        すべて
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" class="sex" v-model="paramSearch.sex" value="0">
                                        男性
                                    </label>
                                </li>
                                <li>
                                    <label><input type="checkbox" class="sex" v-model="paramSearch.sex" value="1">
                                        女性
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" class="sex" v-model="paramSearch.sex" value="">
                                        不明
                                    </label>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    @if ($prefectures->count())
                    <dl class="search-properties_item">
                        <dt class="search-properties_item-title">
                            都道府県
                        </dt>
                        <dd class="search-properties_item-condition">
                            <ul
                                class="search-properties_item-condition_selected search-properties_item-condition_selected-all">
                                <li>
                                    <label>
                                        <input type="checkbox" id="js-chekbox-all-prefectures"
                                            v-on:change="handleCheckAll('prefecture')"> すべて
                                    </label>
                                </li>
                                @foreach ($prefectures as $prefecture)
                                <li>
                                    <label>
                                        <input type="checkbox" class="prefecture" value="{{$prefecture->name}}"
                                            v-model="paramSearch.prefecture"> {{$prefecture->name}}
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                        </dd>
                    </dl>
                    @endif
                    <div class="search-properties_btn">
                        <button type="button" class="btn btn-default btn-custom btn-custom-default"
                            data-dismiss="modal">キャンセル</button>
                        <button type="button" class="btn btn-primary btn-custom btn-custom-primary"
                            v-on:click="handleGetData(true)">この内容で検索</button>
                    </div>
                </div>
            </div>
            {{-- /.modal-content --}}
        </div>
        {{-- /.modal-dialog --}}
    </div>
    {{-- /.modal-dialog --}}
</div>


{{-- /.content --}}
@endsection

{{-- add libs, code, function js other --}}
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
                last_page: 0,
            },

            //add class css hanlde element in pagination
            // class hiden: ['hide-total-top', 'hide-pagination-top' ,'hide-number-top', 'hide-total-bottom', 'hide-pagination-bottom' ,'hide-number-bottom']
            paginationClass:['hide-total-bottom', 'hide-number-bottom'],
            tabInfoIsActive: true,
            paramSearch:{
                perPage: 25,
                page: 1,

                rank: '',
                searchText: '',
                ltvFrom: '',
                ltvTo: '',
                uriFrom: '',
                uriTo: '',
                buyTimesFrom: '',
                buyTimesTo: '',
                agesFrom: '',
                agesTo: '',

                sex: [],
                prefecture: [],
                newsLetter: [],
                customerRank: [],

                priod_to_secession: '',
                sort: [],
            },
            paramSearchDefault:{},
            checkAllCustomerRank: false,
            checkAllSex: false,
            checkAllPrefecture: false,
            dataNotFound: false,
            loadHistory: false,
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            //Xử lý tạo link edit thông tin customer
            handleHrefEdit(id){
                let urlEdit = "{{route('customer.edit', ['id' => '?id?'])}}";
                return urlEdit.replace('?id?', id)
            },
            //Xử lý lấy dữ liệu customer
            handleGetData(){
                //Sử dụng lodash để lọc các param cái mà không có định nghĩa hoặc nó là rỗng.
                let currentSearch = this.$lodash.pickBy(this.paramSearch, this.$lodash.identity);
                //Dùng router của vue để push đến browser.
                //Posva(người cũng tạo ra vue) gợi ý cú pháp push và xử lý catch https://github.com/vuejs/vue-router/issues/2872
                if(!this.loadHistory){
                    this.$router.push({ query: currentSearch }).catch(error => {});
                }
                this.loadHistory = false;
                let url = '{{route("customer.getList")}}';
                loading.show();
                this.$axios.get(url, { params: this.paramSearch })
                    .then(response => {
                        if(response.status == 200){
                            // this.arrData = clearVariable(this.arrData);
                            this.arrData = response.data;
                            if(this.arrData.data.length == 0 && this.arrData.total != 0){
                                this.paramSearch.page = this.arrData.last_page;
                                this.handleGetData();
                                return;
                            }
                            //Lưu lại các param search hiện tại
                            // this.paramSearchDefault = clearVariable(this.paramSearchDefault);
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
                        console.error(error);
                        loading.hide();
                    });
            },
            /* end function for pagiantion */
            /** tính số ngày chênh lệch
             * @param date firstDay
             * @param date lastDay
             * @return int
            */
            handleSubDay(firstDay, lastDay){
                if(firstDay != null && lastDay != null){
                    let day = Math.abs(new Date(lastDay) - new Date(firstDay));
                    return convertIntToDay(day).toFixed(2);
                }
                return 0;
            },
            //sự kiện tính tuổi của customer
            handleCalAge(birthday){
                if(birthday){
                    let yearNow = (new Date()).getFullYear();
                    let yearBirthday = (new Date(birthday)).getFullYear();
                    return yearNow - yearBirthday;
                }
                return;
            },
            //sự kiện chọn tab
            changeTab(tab){
                this.tabInfoIsActive = tab;
                activeTabelResponsive();
            },
            //Xử lý sự kiện click checkbox check all
            handleCheckAll(el){
                let _this = this;
                let check = true;
                switch (el) {
                    case "sex":
                        check = _this.checkAllSex;
                        break;
                    case "prefecture":
                        check =  _this.checkAllPrefecture;
                        break;
                    case "customerRank":
                        check = _this.checkAllCustomerRank;
                        break;
                    default:
                        break;
                }
                _this.paramSearch[el] = [];
                if(!check){
                    _this.$jquery("."+el).each(function(){
                        _this.paramSearch[el].push(_this.$jquery(this).val());
                    });
                }
                _this.checkboxAll();
            },
            //Handle show text rank customer
            handleShowTextRankCust(rank){
                let textRank = '';
                switch (rank) {
                    case 1:
                        textRank = '新規客';
                        break;
                    case 2:
                        textRank = '入門客';
                        break;
                    case 3:
                        textRank = '安定客';
                        break;
                    case 4:
                        textRank = '流行客';
                        break;
                    case 5:
                        textRank = '優良客';
                        break;
                    default:
                        break;
                }
                return textRank;
            },
            /**handle format date
             * @param string stringDate string date need format type Y-m-d
             * @return string
            */
            hanldeFormatDate(stringDate){
                if(stringDate != null){
                    let date = new Date(stringDate);
                    return date.getFullYear() + "-" + ("0" + (date.getMonth()+1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);
                }
                return;
            },
            /**
            * handle event js other
            */
            handleEventJS(){
                let _this = this;

                //Tracking checked checkbox all where load screen first
                _this.checkboxAll();

                //handle where change checkbox
                _this.$jquery(".prefecture, .sex, .customerRank").change(function(e){
                    _this.checkboxAll();
                });

                //xử lý sự kiện khi đóng model điều kiện search
                _this.$jquery("#js-search-properties").on('hidden.bs.modal', function (e) {
                    let size = Object.keys(_this.paramSearchDefault).length;
                    if(size > 0){
                        _this.paramSearch = {};
                        _this.paramSearch = {..._this.paramSearchDefault};
                        _this.checkboxAll();
                    }
                })
            },

            //Handle event checkbox all
            checkboxAll() {
                let _this = this;

                if( _this.paramSearch.prefecture.length != {{$prefectures->count()}}){
                    _this.checkAllPrefecture = false;
                }else{
                    _this.checkAllPrefecture = true;
                }

                if( _this.paramSearch.customerRank.length != 5 ){
                    _this.checkAllCustomerRank = false;
                }else{
                    _this.checkAllCustomerRank = true;
                }

                if(_this.paramSearch.sex.length != 3 ){
                    _this.checkAllSex = false;
                }else{
                    _this.checkAllSex = true;
                }

                _this.$jquery("#js-chekbox-all-prefectures").prop("checked", _this.checkAllPrefecture);
                _this.$jquery("#js-chekbox-all-customerRank").prop("checked", _this.checkAllCustomerRank);
                _this.$jquery("#js-chekbox-all-sex").prop("checked", _this.checkAllSex);
            },

            /** format number
             * @param float number
             * @param int digits minimum fraction digits
             * @return string
            */
            handleFormatNumber(number, digits = 0){
                return formatNumber(number, digits);
            },
            /*handle clear search*/
            handleClearSearch() {
                //Set router về trạng thái ban đầu sau đó set lại params search
                this.$router.push({ query: null }).catch(error =>{});
                this.paramSearch = {
                    perPage: 25,
                    page: 1,

                    rank: '',
                    searchText: '',
                    ltvFrom: '',
                    ltvTo: '',
                    uriFrom: '',
                    uriTo: '',
                    buyTimesFrom: '',
                    buyTimesTo: '',
                    agesFrom: '',
                    agesTo: '',
                    priod_to_secession: '',

                    sex: [],
                    prefecture: [],
                    newsLetter: [],
                    customerRank: [],
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
            }
        },
        // Sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
        //Chỉ cho những sự kiện không truyền tham số
        computed: {
            /* end function caculate for pagination */
            //handle show tab
            showTab(){
                return {
                    'show-tab-info': this.tabInfoIsActive,
                    'show-tab-detail': !this.tabInfoIsActive,
                };
            },

            //handle show condition search
            showConditionSearch(){
                let arrStrCondition = [];
                let count = Object.keys(this.paramSearchDefault).length;
                let condition = this.paramSearchDefault;
                if(count == 0){
                    return "";
                }
                if(condition.newsLetter.length > 0){
                    let str = "受信可否属性: "
                    condition.newsLetter.forEach(function(el, key){
                        switch(el) {
                            case "0":
                                str += "受信意志が不明な顧客";
                                break;
                            case "1":
                                str += "受信許可している顧客";
                                break;
                            case "9":
                                str += "受信拒否している顧客";
                                break;
                        }
                        if(condition.newsLetter.length > (key + 1)){
                            str += "、";
                        }
                    });
                    arrStrCondition.push(str);
                }
                if(condition.customerRank.length > 0){
                    let str = "対象顧客: "
                    condition.customerRank.forEach(function(el, key){
                        switch(el) {
                            case "1":
                                str += "新規客";
                                break;
                            case "2":
                                str += "入門客";
                                break;
                            case "3":
                                str += "安定客";
                                break;
                            case "4":
                                str += "流行客";
                                break;
                            case "5":
                                str += "優良客";
                                break;
                        }
                        if(condition.customerRank.length > (key + 1)){
                            str += "、";
                        }
                    });
                    arrStrCondition.push(str);
                }
                if(condition.ltvFrom != '' || condition.ltvTo != ''){
                    let str = "LTV: " + condition.ltvFrom + "～" + condition.ltvTo;
                    arrStrCondition.push(str);
                }
                if(condition.uriFrom != '' || condition.uriTo != ''){
                    let str = "売上累計: " + condition.uriFrom + "～" + condition.uriTo + ' 円';
                    arrStrCondition.push(str);
                }
                if(condition.buyTimesFrom != '' || condition.buyTimesTo != ''){
                    let str = "購入回数: " + condition.buyTimesFrom + "～" + condition.buyTimesTo + ' 回';
                    arrStrCondition.push(str);
                }
                if(condition.agesFrom != '' || condition.agesTo != ''){
                    let str = "年齢: " + condition.agesFrom + "～" + condition.agesTo + ' 回';
                    arrStrCondition.push(str);
                }
                if(condition.sex.length > 0){
                    let str = "性別: "
                    condition.sex.forEach(function(el, key){
                        switch(el) {
                            case "0":
                                str += "男性";
                                break;
                            case "1":
                                str += "女性";
                                break;
                            case "":
                                str += "不明";
                                break;
                        }
                        if(condition.sex.length > (key + 1)){
                            str += "、";
                        }
                    });
                    arrStrCondition.push(str);
                }
                if(condition.prefecture.length > 0){
                    let str = "都道府県: "
                    condition.prefecture.forEach(function(el, key){
                        str += el;
                        if(condition.prefecture.length > (key + 1)){
                            str += "、";
                        }
                    });
                    arrStrCondition.push(str);
                }
                if(arrStrCondition.length > 0){
                    return "(" + arrStrCondition.join(" / ") + ")";
                }else{
                    return "";
                }
            }
        },
        // Xảy ra trước khi khởi tạo, data và event được khai báo chưa tự thay đổi khi cập nhật
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
        },
        //xử lý trước khi dữ liệu có sự thay đổi
        beforeUpdate() {
        },
        //Xử lý khi dữ liệu có sự thay đổi
        updated() {
            this.$jquery("#app").show();
            activeTabelResponsive();
        },
        //Xử lý trước khi hủy đối tượng
        beforeDestroy() {
            delete this.$router;
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
