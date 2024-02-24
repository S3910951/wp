document.addEventListener('DOMContentLoaded', function() {
    //NAV HIGHLIGHT
    const sections = document.querySelectorAll("section");
    const navLinks = document.querySelectorAll(".navbar a");

    function removeCurrentClass() {
        navLinks.forEach(link => {
            link.classList.remove('nav-current');
        });
    }

    function addCurrentClass(id) {
        removeCurrentClass();
        const activeLink = document.querySelector(`.navbar a[href="#${id}"]`);
        if (activeLink) {
            activeLink.classList.add('nav-current');
        }
    }

    // Listen for click events on nav links
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href').replace("#", "");
            addCurrentClass(targetId);
        });
    });

    // Update nav links on scroll
    window.addEventListener('scroll', function() {
        let currentSection = "";
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= (sectionTop - sectionHeight / 3)) {
                currentSection = section.getAttribute('id');
            }
        });
        addCurrentClass(currentSection);
    });

    //TOTAL PRICE CALC
    // Select all day and seat dropdown elements
    const dayDropdowns = document.querySelectorAll('input[name="day"]');
    const seatDropdowns = document.querySelectorAll('select[name^="seats"]');

    // Function to update the total price
    function updateTotalPrice() {
        let pricingPolicy = 'FULL';
        dayDropdowns.forEach(day => {
            if (day.checked) {
                pricingPolicy = day.getAttribute('data-pricing');
            }
        });

        let totalPrice = 0;
        seatDropdowns.forEach(seat => {
            const seatCount = parseInt(seat.value) || 0;
            const seatPrice = parseFloat(seat.getAttribute(`data-${pricingPolicy}`)) || 0;
            totalPrice += seatCount * seatPrice;
        });

        // Display total price or hide it
        if (totalPrice > 0) {
            document.getElementById('totalPrice').textContent = `$${totalPrice.toFixed(2)}`;
        } else {
            document.getElementById('totalPrice').textContent = '';
        }

        // Block or allow form submission
        document.querySelector('input[type="submit"]').disabled = !(totalPrice > 0);
    }

    // Attach event listeners to all day and seat selection dropdowns
    dayDropdowns.forEach(day => day.addEventListener('change', updateTotalPrice));
    seatDropdowns.forEach(seat => seat.addEventListener('change', updateTotalPrice));

    // Initial calculation in case the form is pre-filled
    updateTotalPrice();

    //REMEMBER ME BUTTON
    const nameInput = document.getElementById('customerName');
    const emailInput = document.getElementById('customerEmail');
    const mobileInput = document.getElementById('customerMobile');
    const rememberMeCheckbox = document.getElementById('rememberMe');
    const rememberMeLabel = document.getElementById('rememberMeLabel');

    function handleRememberMe() {
        if (rememberMeCheckbox.checked) {
            localStorage.setItem('customerName', nameInput.value);
            localStorage.setItem('customerEmail', emailInput.value);
            localStorage.setItem('customerMobile', mobileInput.value);
            rememberMeLabel.textContent = 'Forget Me';
        } else {
            localStorage.removeItem('customerName');
            localStorage.removeItem('customerEmail');
            localStorage.removeItem('customerMobile');
            rememberMeLabel.textContent = 'Remember Me';
        }
    }

    function loadCustomerDetails() {
        if (localStorage.getItem('customerName')) {
            nameInput.value = localStorage.getItem('customerName');
            emailInput.value = localStorage.getItem('customerEmail');
            mobileInput.value = localStorage.getItem('customerMobile');
            rememberMeCheckbox.checked = true;
            rememberMeLabel.textContent = 'Forget Me';
        }
    }

    rememberMeCheckbox.addEventListener('change', handleRememberMe);
    loadCustomerDetails();
});
