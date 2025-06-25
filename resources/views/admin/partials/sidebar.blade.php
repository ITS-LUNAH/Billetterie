<aside>
    <div class="top">
        <div class="logo">
            <img src="{{ asset('assets/images/EVUP.gif') }}" alt="EVUP Logo">
            <h2>EV<span class="danger">UP</span></h2>
        </div>
        <div class="close" id="close-btn">
            <span class="material-icons-sharp">close</span>
        </div>
    </div>

    <div class="sidebar">
        <a href="{{ route('dashboard') }}" 
           class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="material-icons-sharp">grid_view</span>
            <h3>Dashboard</h3>
        </a>

        <a href="{{ route('admin.evenements.index') }}" 
           class="{{ request()->routeIs('admin.evenements.*') ? 'active' : '' }}">
            <span class="material-icons-sharp">event</span>
            <h3>Événements</h3>
        </a>

        <a href="{{ route('admin.organisateurs.index') }}" 
           class="{{ request()->routeIs('admin.organisateurs.*') ? 'active' : '' }}">
            <span class="material-icons-sharp">handshake</span>
            <h3>Organisateurs</h3>
        </a>

        <a href="{{ route('admin.participants.index') }}" 
           class="{{ request()->routeIs('admin.participants.*') ? 'active' : '' }}">
            <span class="material-icons-sharp">group</span>
            <h3>Participants</h3>
        </a>

        <a href="{{ route('admin.commissions.index') }}" 
           class="{{ request()->routeIs('admin.commissions.*') ? 'active' : '' }}">
            <span class="material-icons-sharp">currency_exchange</span>
            <h3>Commissions</h3>
        </a>

        <a href="{{ route('admin.statistiques.index') }}" 
           class="{{ request()->routeIs('admin.statistiques.*') ? 'active' : '' }}">
            <span class="material-icons-sharp">insights</span>
            <h3>Statistiques</h3>
        </a>

        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="material-icons-sharp">logout</span>
            <h3>Déconnexion</h3>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>