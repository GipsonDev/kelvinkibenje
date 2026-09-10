/**
 * ==============================================================================
 * KELVIN KIBENJE OFFICIAL WEBSITE - JAVASCRIPT (main.js)
 * Mobile Menu, Order Modal, WhatsApp Checkout & UX Enhancements
 * ==============================================================================
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // --------------------------------------------------------------------------
    // 1. MOBILE MENU TOGGLE
    // --------------------------------------------------------------------------
    const menuToggle = document.getElementById('mobileMenuToggle');
    const navLinks = document.getElementById('navLinks');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            const bars = menuToggle.querySelectorAll('.hamburger-bar');
            if (navLinks.classList.contains('active')) {
                bars[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                bars[1].style.opacity = '0';
                bars[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
            } else {
                bars[0].style.transform = 'none';
                bars[1].style.opacity = '1';
                bars[2].style.transform = 'none';
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!menuToggle.contains(e.target) && !navLinks.contains(e.target)) {
                navLinks.classList.remove('active');
                const bars = menuToggle.querySelectorAll('.hamburger-bar');
                if (bars.length === 3) {
                    bars[0].style.transform = 'none';
                    bars[1].style.opacity = '1';
                    bars[2].style.transform = 'none';
                }
            }
        });
    }

    // --------------------------------------------------------------------------
    // 2. ORDER FORM MODAL (For Book Shop & Detail Pages)
    // --------------------------------------------------------------------------
    const modalOverlay = document.getElementById('orderModal');
    const openModalButtons = document.querySelectorAll('.open-order-modal');
    const closeModalButtons = document.querySelectorAll('.modal-close, .close-modal-btn');

    if (modalOverlay) {
        openModalButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const bookId = this.getAttribute('data-book-id');
                const bookTitle = this.getAttribute('data-book-title');
                const bookPrice = this.getAttribute('data-book-price');

                // Populate hidden form inputs if present
                const inputBookId = modalOverlay.querySelector('#modalBookId');
                const titleDisplay = modalOverlay.querySelector('#modalBookTitleDisplay');
                const priceDisplay = modalOverlay.querySelector('#modalBookPriceDisplay');
                const totalDisplay = modalOverlay.querySelector('#modalTotalPriceDisplay');

                if (inputBookId) inputBookId.value = bookId || '';
                if (titleDisplay) titleDisplay.textContent = bookTitle || '';
                if (priceDisplay && bookPrice) {
                    priceDisplay.textContent = 'TSh ' + Number(bookPrice).toLocaleString();
                }
                if (totalDisplay && bookPrice) {
                    totalDisplay.textContent = 'TSh ' + Number(bookPrice).toLocaleString();
                }

                modalOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        closeModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                modalOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // Close when clicking overlay backdrop
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                modalOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    // --------------------------------------------------------------------------
    // 3. WHATSAPP INSTANT BUY BUTTON ROUTER
    // --------------------------------------------------------------------------
    const whatsappButtons = document.querySelectorAll('.whatsapp-order-btn');
    whatsappButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const bookTitle = this.getAttribute('data-book-title') || 'a book';
            const bookPrice = this.getAttribute('data-book-price') || '';
            const phone = this.getAttribute('data-phone') || '255677853595';

            let msg = `Hello Kelvin! I want to order your book: "${bookTitle}"`;
            if (bookPrice) {
                msg += ` (TSh ${Number(bookPrice).toLocaleString()})`;
            }
            msg += `. Please let me know how to complete my M-Pesa/Tigo Pesa payment and arrange delivery. Thank you!`;

            const encoded = encodeURIComponent(msg);
            const url = `https://wa.me/${phone.replace(/[^0-9]/g, '')}?text=${encoded}`;
            window.open(url, '_blank');
        });
    });

    // --------------------------------------------------------------------------
    // 4. QUANTITY SELECTOR ON BOOK DETAIL
    // --------------------------------------------------------------------------
    const qtyInput = document.getElementById('orderQtyInput');
    const totalDisplay = document.getElementById('modalTotalPriceDisplay');
    if (qtyInput && totalDisplay) {
        const basePrice = Number(qtyInput.getAttribute('data-base-price')) || 20000;
        qtyInput.addEventListener('input', function() {
            let qty = parseInt(this.value, 10) || 1;
            if (qty < 1) qty = 1;
            const total = qty * basePrice;
            totalDisplay.textContent = 'TSh ' + total.toLocaleString();
        });
    }
});
