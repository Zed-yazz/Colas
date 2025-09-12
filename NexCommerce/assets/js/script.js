document.addEventListener("DOMContentLoaded", function() {
    // --- LÓGICA DO HERO SLIDER (SWIPERJS) ---
    if (typeof Swiper !== 'undefined' && document.querySelector('.hero-slider')) {
        new Swiper('.hero-slider', { loop: true, autoplay: { delay: 5000 }, pagination: { el: '.swiper-pagination', clickable: true }, navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' } });
    }
    // --- LÓGICA DO CARROSSEL DE PRODUTOS (SWIPERJS) ---
    if (typeof Swiper !== 'undefined' && document.querySelector('.product-carousel-swiper')) {
        new Swiper('.product-carousel-swiper', { slidesPerView: 1, spaceBetween: 20, loop: true, pagination: { el: '.product-carousel-pagination', clickable: true }, navigation: { nextEl: '.product-carousel-next', prevEl: '.product-carousel-prev' }, breakpoints: { 640: { slidesPerView: 2 }, 768: { slidesPerView: 3 }, 1024: { slidesPerView: 4 } } });
    }
    // --- LÓGICA DO MENU LATERAL (SIDEBAR) ---
    const menuToggle = document.getElementById('menu-toggle');
    if (menuToggle) {
        const sidebar = document.querySelector('.sidebar');
        const sidebarClose = document.getElementById('sidebar-close');
        let overlay = document.querySelector('.sidebar-overlay');
        if (!overlay) { overlay = document.createElement('div'); overlay.className = 'sidebar-overlay'; document.body.appendChild(overlay); }
        const openMenu = () => { sidebar.classList.add('open'); overlay.classList.add('open'); };
        const closeMenu = () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); };
        menuToggle.addEventListener('click', openMenu);
        sidebarClose.addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu);
    }
    // --- LÓGICA DO MODAL DE CÁLCULO DE FRETE ---
    const openModalBtn = document.getElementById('calculate-shipping-btn');
    if (openModalBtn) {
        const shippingModal = document.getElementById('shipping-modal');
        const closeModalBtn = shippingModal.querySelector('.modal-close-btn');
        const cepSubmitBtn = document.getElementById('cep-submit-btn');
        const shippingResults = document.getElementById('shipping-results');
        const cepInput = document.getElementById('cep-input');
        const openModal = () => shippingModal.classList.add('visible');
        const closeModal = () => shippingModal.classList.remove('visible');
        openModalBtn.addEventListener('click', openModal);
        closeModalBtn.addEventListener('click', closeModal);
        shippingModal.addEventListener('click', (e) => { if (e.target === shippingModal) closeModal(); });
        cepSubmitBtn.addEventListener('click', () => {
            if (cepInput.value.trim() === '') { shippingResults.innerHTML = '<p style="color: #ef4444;">Por favor, digite um CEP.</p>'; return; }
            shippingResults.innerHTML = '<p>Calculando...</p>';
            setTimeout(() => { shippingResults.innerHTML = `<div class="shipping-option"><span>SEDEX</span><span><strong>R$ 28,50</strong> - 3 dias úteis</span></div><div class="shipping-option"><span>PAC</span><span><strong>R$ 19,80</strong> - 8 dias úteis</span></div>`; }, 1500);
        });
    }
    // --- LÓGICA DO CHECKOUT DINÂMICO ---
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    if (paymentMethodRadios.length > 0) {
        const paymentForms = document.querySelectorAll('.payment-form');
        const handlePaymentFormChange = (radio) => {
            paymentForms.forEach(form => form.classList.remove('active'));
            const activeForm = document.getElementById(radio.value + '-form');
            if (activeForm) activeForm.classList.add('active');
            if (radio.value === 'pix') {
                const qrContainer = document.getElementById('pix-qrcode');
                if (qrContainer && typeof QRCode !== 'undefined' && qrContainer.childElementCount === 0) { new QRCode(qrContainer, { text: document.getElementById('pix-key').value, width: 180, height: 180 }); }
            } else if (radio.value === 'boleto') {
                const barcodeImg = document.getElementById('boleto-barcode');
                if (barcodeImg && typeof JsBarcode === 'function') {
                    const boletoNumber = document.getElementById('boleto-key').value.replace(/[.\s]/g, '');
                    if (boletoNumber) { JsBarcode(barcodeImg, boletoNumber, { format: "CODE128", lineColor: "#000", background: "#FFFFFF", width: 2, height: 80, displayValue: false }); }
                }
            }
        };
        paymentMethodRadios.forEach(radio => radio.addEventListener('change', () => handlePaymentFormChange(radio)));
        const checkedRadio = document.querySelector('input[name="payment_method"]:checked');
        if (checkedRadio) handlePaymentFormChange(checkedRadio);
    }
    // --- LÓGICA DO MENU DROPDOWN DO USUÁRIO ---
    const userMenuToggle = document.getElementById('user-menu-toggle');
    if (userMenuToggle) {
        const userDropdownMenu = document.getElementById('user-dropdown-menu');
        userMenuToggle.addEventListener('click', (event) => { event.stopPropagation(); userDropdownMenu.classList.toggle('show'); });
        window.addEventListener('click', (event) => { if (userDropdownMenu.classList.contains('show') && !userMenuToggle.contains(event.target)) { userDropdownMenu.classList.remove('show'); } });
    }
    // --- LÓGICA DAS ABAS DA PÁGINA DE PRODUTO ---
    const tabLinks = document.querySelectorAll('.tab-link');
    if (tabLinks.length > 0) {
        const tabContents = document.querySelectorAll('.tab-content');
        tabLinks.forEach(link => {
            link.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                tabLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                tabContents.forEach(content => { content.classList.remove('active'); if (content.id === tabId) { content.classList.add('active'); } });
            });
        });
    }
});