Vue.component("Pagination", {
    props: {
        //Số button hiển thị khi chọn page, vd giá trị là 4 thì hiển thị
        maxVisibleButtons: {
            type: [Number, String],
            required: false,
            default: 4,
        },
        //Page cuối
        lastPage: {
            type: [Number, String],
            required: false,
            default: 0,
        },
        //Tổng dữ liệu
        total: {
            type: [Number, String],
            required: true,
            default: 0,
        },
        //Số row trên mỗi page
        perPage: {
            type: [Number, String],
            required: true,
            default: 0,
        },
        //Tổng số page
        totalPage: {
            type: [Number, String],
            required: false,
            default: 0,
        },
        //Trang hiện tại
        currentPage: {
            type: [Number, String],
            required: true,
        },
        //Danh sách chọn số row show trên mỗi page
        limitPerPage: {
            type: [Array, Object],
            required: false,
            default: function () {
                return [
                    {
                        value: "10",
                        title: "10件",
                    },
                    {
                        value: "25",
                        title: "25件",
                    },
                    {
                        value: "50",
                        title: "50件",
                    },
                    {
                        value: "100",
                        title: "100件",
                    },
                    {
                        value: "250",
                        title: "250件",
                    },
                ];
            },
        },
        //Option hiển thị thành phần của pagination
        /* class hiden: [
            'hide-total-top',
            'hide-pagination-top' ,
            'hide-number-top',
            'hide-total-bottom',
            'hide-pagination-bottom' ,
            'hide-number-bottom'
        ] */
        paginationClass: {
            type: [Array],
            required: false,
            default: [],
        },
    },
    data() {
        return {};
    },
    methods: {
        //Xử lý sự kiện chọn select box
        handleChangPaginationSelectBox(event) {
            this.$emit("update:loadHistory", false);
            this.$emit("update:per-page", event.target.value);
            this.$emit("update:data");
        },
        handleChangePage(page) {
            if (page == this.currentPage) {
                return;
            }
            this.$emit("update:loadHistory", false);
            this.$emit("update:page", page);
            this.$emit("update:data");
        },
    },
    computed: {
        /* function caculate for pagination */
        //sự kiện active cho phân trang
        isActived() {
            return this.currentPage;
        },
        //Xự kiện tính phần tử cho phân trang
        pagesNumber() {
            let from = this.currentPage - this.maxVisibleButtons;
            if (from < 1) {
                from = 1;
            }
            let to = from + this.maxVisibleButtons * 2;
            if (to >= this.lastPage) {
                to = this.lastPage;
            }
            let pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },
    },
    //Xử lý trước khi hủy đối tượng
    beforeDestroy() {
        delete this.props;
    },
    //Xử lý khi hủy đối tượng
    destroyed() {
    },
    template: `
            <div class="dataTables_wrapper dt-bootstrap4 " v-bind:class="paginationClass">
                <div class="dataTables_paginate paging_simple_numbers customer-pagination">
                    <div class="total">
                        全件{{total}}件
                    </div>
                    <ul class="pagination justify-content-center" v-if="lastPage != 1">
                        <li class="paginate_button page-item" v-if="currentPage > 1">
                            <a class="page-link" aria-label="Previous" v-on:click.prevent="handleChangePage(1)">
                                <i class="fas fa-step-backward"></i>
                            </a>
                        </li>
                        <li class="paginate_button page-item" v-if="currentPage > 1">
                            <a class="page-link" aria-label="Previous"
                                v-on:click.prevent="handleChangePage(currentPage - 1)">
                                <i class="fas fa-caret-left"></i>
                            </a>
                        </li>
                        <li class="paginate_button page-item" v-for="page in pagesNumber"
                            v-bind:class="[ page == isActived ? 'active' : '']">
                            <a class="page-link" v-on:click.prevent="handleChangePage(page)">{{ page }}</a>
                        </li>
                        <li class="paginate_button page-item" v-if="currentPage < lastPage">
                            <a class="page-link" aria-label="Next"
                                v-on:click.prevent="handleChangePage(currentPage + 1)">
                                <i class="fas fa-caret-right"></i>
                            </a>
                        </li>
                        <li class="paginate_button page-item" v-if="currentPage < lastPage">
                            <a class="page-link" aria-label="Next" v-on:click.prevent="handleChangePage(lastPage)">
                                <i class="fas fa-step-forward"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="number-show-record">
                        表示件数
                        <select v-on:change v-on:change="handleChangPaginationSelectBox" v-bind:value="perPage" id="">
                            <option v-bind:value="option.value" v-for="option in limitPerPage">{{option.title}}</option>
                        </select>
                    </div>
                </div>
            </div>
            `,
});
