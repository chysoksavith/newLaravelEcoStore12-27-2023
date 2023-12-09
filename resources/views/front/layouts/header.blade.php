<header>
    <div class="header_nav" id="headerNav">
        <nav class="MenuREs">
            <ul>
                <li>
                    <span class="LogoAside" id="menuBtn">
                        <img class="logo" src="{{ asset('icons8-menu-50.png') }}" alt="">
                    </span>

                </li>
            </ul>
        </nav>
        <nav class="CollectionNav">
            <ul class="nav-links">
                <li><a class="NavHead" href="">Collection</a></li>
                {{-- mega dropdown --}}
                <li>
                    <div class="dropdown" data-dropdown>
                        <a href="#" class="link" data-dropdown-button>Info</a>
                        <div class="dropdown-memu infomation-grid">
                            <div>
                                <div class="dropdown-heading">Hello</div>
                                <div class="dropdown-links">
                                    <a href="#">Hello</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
        <a class="LogoHeader" href="{{ route('front.indexPage') }}">
            <img src="{{ asset('pngwing.com.png') }}" alt="">
        </a>
        <nav class="ResNavSearch">
            <ul>
                <li class="ResN"><a href="">Search</a></li>
                <li class="ResN"><a href="">Account</a></li>
                <li class="ResN"><a href="">Bag</a></li>
            </ul>

        </nav>
        {{-- nav ressponsvve --}}
        <nav class="NavREs">
            <ul>
                <li class="NavLiRes"><a href=""><i class="fa-solid fa-magnifying-glass"></i></a></li>
                <li class="NavLiRes"><a href=""><i class="fa-regular fa-user"></i></a></li>
                <li class="NavLiRes"><a href=""><i class="fa-solid fa-bag-shopping"></i></a></li>
            </ul>
        </nav>
    </div>
    @include('front.layouts.aside')
</header>
@section('scripts')
    <script>
        const headerNav = document.getElementById('headerNav');
        let lastScrollTop = 0;

        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documenrElement.scrollTop;
            if (scrollTop > lastScrollTop) {
                headerNav.classList.add('nav-scroll');
            } else {
                headerNav.classList.remove('nav-scroll');
            }
            lastScrollTop = scrollTop;
        })
    </script>
    <script defer>
        document.addEventListener('click', e => {
            const isDropdownMenu = e.target.matches("[data-dropdown-button]");
            if (!isDropdownMenu && e.target.closest('[data-dropdown]') !== null) return;

            let currentDropdown;
            if (isDropdownMenu) {
                currentDropdown = e.target.closest('[data-dropdown]');
                currentDropdown.classList.toggle('active');
            }
            document.querySelectorAll("[data-dropdown].active").forEach(dropdown => {
                if (dropdown === currentDropdown) return;
                dropdown.classList.remove('active');
            });
        });
    </script>
    <script>
        const openMenu = () => {
            document.querySelector('.backdrop').className = 'backdrop active';
            document.querySelector('aside').className = 'active';
        }
        const closeMenu = () => {
            document.querySelector('.backdrop').className = 'backdrop';
            document.querySelector('aside').className = '';
        }

        document.getElementById('menuBtn').onclick = e => {
            e.preventDefault();
            openMenu();
        }
        document.querySelector('aside button.close').onclick = e => {
            closeMenu();
        }
        document.querySelector('.backdrop').onclick = e => {
            closeMenu();
        }
    </script>
@endsection
