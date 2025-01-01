<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label">Main Menu</li>
            <li><a href="{{ route('dashboard') }}" aria-expanded="false"><i class="bi bi-speedometer2"></i><span
                        class="nav-text">Dashboard</span></a></li>
            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                        class="bi bi-graph-up-arrow"></i><span class="nav-text">Potensi Desa</span></a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('list-potensi-desa') }}" aria-expanded="false"><i
                                class="bi bi-list-task"></i><span class="nav-text">List Potensi Desa</span></a>
                    </li>
                    <li><a href="{{ route('tambah-potensi-desa') }}" aria-expanded="false"><i
                                class="bi bi-plus-circle"></i><span class="nav-text">Tambah Potensi Desa</span></a></li>
                </ul>
            </li>
            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="bi bi-newspaper"></i><span
                        class="nav-text">Berita Desa</span></a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('list-berita-desa') }}" aria-expanded="false"><i
                                class="bi bi-list-task"></i><span class="nav-text">List Berita Desa</span></a>
                    </li>
                    <li><a href="{{ route('tambah-berita-desa') }}" aria-expanded="false"><i
                                class="bi bi-plus-circle"></i><span class="nav-text">Tambah Berita Desa</span></a></li>
                </ul>
            </li>
            <li class="menu-separator"></li>
            <li>
                <a href="#" data-toggle="modal" data-target="#logoutModal" aria-expanded="false">
                    <i class="bi bi-box-arrow-left"></i>
                    <span class="nav-text">Keluar</span>
                </a>
            </li>
        </ul>
    </div>
</div>
