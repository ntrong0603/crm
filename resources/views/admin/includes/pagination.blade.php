<div class="dataTables_wrapper dt-bootstrap4 " v-bind:class="paginationClass">
    <div class="dataTables_paginate paging_simple_numbers customer-pagination">
        <div class="total">
            全件@{{datas.total}}件
        </div>
        <ul class="pagination justify-content-center" v-if="datas.last_page != 1">
            <li class="paginate_button page-item" v-if="datas.current_page > 1">
                <a class="page-link" href="#" aria-label="Previous" @click.prevent="handleChangePage(1)">
                    <i class="fas fa-step-backward"></i>
                </a>
            </li>
            <li class="paginate_button page-item" v-if="datas.current_page > 1">
                <a class="page-link" href="#" aria-label="Previous"
                    @click.prevent="handleChangePage(datas.current_page - 1)">
                    <i class="fas fa-caret-left"></i>
                </a>
            </li>
            <li class="paginate_button page-item" v-for="page in pagesNumber"
                v-bind:class="[ page == isActived ? 'active' : '']">
                <a class="page-link" href="#" @click.prevent="handleChangePage(page)">@{{ page }}</a>
            </li>
            <li class="paginate_button page-item" v-if="datas.current_page < datas.last_page">
                <a class="page-link" href="#" aria-label="Next"
                    @click.prevent="handleChangePage(datas.current_page + 1)">
                    <i class="fas fa-caret-right"></i>
                </a>
            </li>
            <li class="paginate_button page-item" v-if="datas.current_page < datas.last_page">
                <a class="page-link" href="#" aria-label="Next" @click.prevent="handleChangePage(datas.last_page)">
                    <i class="fas fa-step-forward"></i>
                </a>
            </li>
        </ul>
        <div class="number-show-record">
            表示件数
            <select v-model="itemShow" id="" v-on:change="handleChangPaginationSelectBox">
                <option v-bind:value="option.value" v-for="option in optionChangeLimit">@{{option.title}}</option>
            </select>
        </div>
    </div>
</div>
