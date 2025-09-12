</div> <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');

            if (menuToggle && mobileMenu) {
                // Abre e fecha o menu
                menuToggle.addEventListener('click', function() {
                    mobileMenu.classList.toggle('open');
                });
                
                // Fecha o menu se clicar no fundo (overlay)
                mobileMenu.addEventListener('click', function(event) {
                    if (event.target === mobileMenu) {
                        mobileMenu.classList.remove('open');
                    }
                });
            }
        });
    </script>
</body>
</html>