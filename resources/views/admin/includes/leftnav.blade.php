<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" id="js-main-menu">
    <img src="{{asset('images/img_login.png')}}" alt="" srcset="">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('admin')}}" data-route="" class="nav-link">
                        <i class="fas fa-home"></i>
                        <p>ダッシュボード</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="far fa-envelope"></i>
                        <p>メール設定 <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('scenario')}}" data-route="scenario" class="nav-link">
                                <i class="fas fa-cog"></i>
                                <p>メール設定</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('mail-template')}}" data-route="mail-template" class="nav-link">
                                <i class="fas fa-th"></i>
                                <p>テンプレート一覧</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('schedule')}}" data-route="schedule" class="nav-link">
                                <i class="far fa-calendar-alt"></i>
                                <p>配信スケジュール</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('mail-effect')}}" data-route="mail-effect-meas" class="nav-link">
                        <i class="fas fa-chart-line"></i>
                        <p>メール効果測定</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-bar" aria-hidden="true"></i>
                        <p>
                            分析
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('customer-rank-analisys.chart')}}" data-route="customer-rank-analisys"
                                class="nav-link">
                                <i class="far fa-dot-circle"></i>
                                <p>ランク分析</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('customer-move-rate.chart')}}" data-route="customer-move-rate"
                                class="nav-link">
                                <i class="far fa-dot-circle"></i>
                                <p>推移率</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('ltv-analisys.chart')}}" data-route="ltv-analisys" class="nav-link">
                                <i class="far fa-dot-circle"></i>
                                <p>LTV分析</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('customer.list')}}" data-route="customer" class="nav-link">
                        <i class="fa fa-users"></i>
                        <p>顧客管理</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fa fa-cogs"></i>
                        <p>システム設定 <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('customer-rank.setting')}}" data-route="customer-rank" class="nav-link">
                                <i class="far fa-dot-circle"></i>
                                <p>顧客ランクしきい値</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('rfm-threshold-setting.setting')}}" data-route="rfm-threshold-setting"
                                class="nav-link">
                                <i class="far fa-dot-circle"></i>
                                <p>RFMランクしきい値</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('user.list')}}" data-route="user" class="nav-link">
                        <i class="fa fa-user"></i>
                        <p>ユーザー管理</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('batch')}}" data-route="batch" class="nav-link">
                        <i class="far fa-play-circle"></i>
                        <p>バッチ管理</p>
                    </a>
                </li>
            </ul>
        </nav>
        <script>
            var routeName = '{{request()->segment(2)}}';
        </script>
    </section>
    <!-- /.sidebar -->
</aside>
