<nav class="navbar">
    <div class="nav-brand">Aplikasi Laporan PSN</div>

    <input type="checkbox" id="nav-toggle" class="nav-toggle">
    <label for="nav-toggle" class="nav-icon">&#9776;</label>

    <ul class="nav-menu">
        <li><a href="{{ route('ikhtisar.tahunan') }}" class="{{ request()->is('ikhtisartahunan') ? 'active' : '' }}">Ikhtisar Tahunan</a></li>
        <li><a href="{{ route('drd.index') }}" class="{{ request()->is('drd') ? 'active' : '' }}">DRD</a></li>
        <li><a href="{{ route('lhk.index') }}" class="{{ request()->is('lhk') ? 'active' : '' }}">LHK</a></li>
        <li><a href="{{ route('pasang.index') }}" class="{{ request()->is('pasang') ? 'active' : '' }}">Pemasangan</a></li>
        <li><a href="{{ route('pemakaian.index') }}" class="{{ request()->is('pemakaian') ? 'active' : '' }}">Pemakaian</a></li>
        <li><a href="{{ route('mutasi.index') }}" class="{{ request()->is('mutasi') ? 'active' : '' }}">Mutasi</a></li>
        <li><a href="{{ route('pelanggan.index') }}" class="{{ request()->is('pelanggan') ? 'active' : '' }}">Pelanggan</a></li>
        <li><a href="{{ route('monitoring.tarif') }}" class="{{ request()->is('monitoring-tarif') ? 'active' : '' }}">Monitor</a></li>
        <li><a href="{{ route('tagihan.index') }}" class="{{ request()->is('tagihan') ? 'active' : '' }}">Tagihan</a></li>
        <li><a href="{{ route('logout') }}" class="logout-btn">Logout</a></li>
    </ul>
</nav>

<style>
.navbar {
    background: #2c3e50;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: white;
    position: sticky;
    top: 0;
}

.nav-brand {
    font-size: 20px;
    font-weight: bold;
}

.nav-menu {
    list-style: none;
    display: flex;
    gap: 20px;
}

.nav-menu a {
    color: white;
    text-decoration: none;
    font-size: 16px;
    padding-bottom: 3px;
    transition: 0.2s;
}

.logout-btn {
    color: #ff7675 !important;
    font-weight: bold;
}

.logout-btn:hover {
    border-bottom: 2px solid #ff7675;
}

.nav-menu a:hover {
    border-bottom: 2px solid #1abc9c;
}

.active {
    border-bottom: 3px solid #1abc9c;
    padding-bottom: 3px;
}

.nav-toggle {
    display: none;
}

.nav-icon {
    display: none;
    font-size: 28px;
    cursor: pointer;
}

@media (max-width: 1200px) {
    .nav-menu {
        position: absolute;
        top: 60px;
        right: 0;
        width: 200px;
        flex-direction: column;
        background: #2c3e50;
        padding: 10px;
        display: none;
        border-radius: 5px;
    }

    .nav-menu li {
        margin: 10px 0;
    }

    .nav-toggle:checked ~ .nav-menu {
        display: flex;
    }

    .nav-icon {
        display: block;
    }
}
</style>