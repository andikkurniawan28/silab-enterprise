<style>
    #searchSuggestions {
        position: fixed;
        /* Ubah menjadi fixed */
        top: 100%;
        /* Atur jarak dari atas input search */
        left: 0;
        display: none;
        min-width: 100%;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        /* Optional: Tambahkan efek bayangan */
    }

    #searchSuggestions .dropdown-item:hover {
        cursor: pointer; /* Ubah gaya kursor saat hover */
    }
</style>
<!-- Navbar -->
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center position-relative">
            <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                    aria-label="Search..." id="search_input" oninput="search()" autocomplete="off"/>

                <!-- Suggestions dropdown -->
                <ul id="searchSuggestions" class="dropdown-menu dropdown-menu-end position-absolute start-0 mt-2"
                    style="display: none;">
                </ul>
                <!-- /Suggestions dropdown -->
            </div>
        </div>
        <!-- /Search -->

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{ asset('sneat/assets/img/avatars/1.png') }}" alt
                                class="w-px-40 h-auto rounded-circle" />
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="{{ asset('sneat/assets/img/avatars/1.png') }}" alt
                                                class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block">{{ Auth()->user()->name }}</span>
                                        <small class="text-muted">{{ Auth()->user()->role->name }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>
    </div>
</nav>
<!-- / Navbar -->

<style>
    /* CSS untuk menyesuaikan posisi suggestions dropdown */
    #searchSuggestions {
        z-index: 1000;
        /* Menempatkan di atas konten lain jika diperlukan */
    }
</style>

<script>
    function search() {
        const searchInputValue = document.getElementById('search_input').value.trim();
        console.log('Search input value:', searchInputValue);

        // Fetch CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Clear suggestion dropdown if search input is empty
        if (searchInputValue === '') {
            const searchSuggestions = document.getElementById('searchSuggestions');
            searchSuggestions.innerHTML = '';
            searchSuggestions.style.display = 'none';
            return; // Exit function early
        }

        // Fetch data using fetch API
        fetch('{{ route("material.search") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify({
                query: searchInputValue
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('API response:', data);

            const searchSuggestions = document.getElementById('searchSuggestions');
            searchSuggestions.innerHTML = '';

            // Populate with new suggestions
            data.forEach(material => {
                const li = document.createElement('li');
                li.classList.add('dropdown-item');
                li.textContent = material.name;
                li.addEventListener('click', function() {
                    handleSuggestionClick(material.id);
                });
                searchSuggestions.appendChild(li);
            });

            // Show suggestions dropdown
            const searchInput = document.getElementById('search_input');
            const inputRect = searchInput.getBoundingClientRect();
            searchSuggestions.style.top = `${inputRect.bottom}px`; // Position directly below the input
            searchSuggestions.style.left = `${inputRect.left}px`; // Align with the left edge of the input
            searchSuggestions.style.display = 'block';
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
    }

    function handleSuggestionClick(materialId) {
        console.log('Material ID clicked:', materialId);

        // Redirect to result_by_material.index with material_id parameter
        window.location.href = `{{ route('result_by_material.index', ':material_id') }}`.replace(':material_id', materialId);
    }

    // Optional: Close suggestions dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const searchSuggestions = document.getElementById('searchSuggestions');
        if (!searchSuggestions.contains(event.target)) {
            searchSuggestions.style.display = 'none';
        }
    });
</script>

