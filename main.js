// main.js
const searchIcon = document.getElementById("search-icon");
const searchBox = document.querySelector(".search-box");
const searchInput = document.querySelector(".search-box input");
const menuItems = document.querySelectorAll(".products-container .box");
const logIn = document.querySelectorAll("header-actions .login");
const signIn = document.querySelectorAll("header-actions .signin");

// Tambahkan event listener untuk icon search
searchIcon.addEventListener("click", () => {
    // Toggle tampilan search box
    if (searchBox.style.display === "none" || searchBox.style.display === "") {
        searchBox.style.display = "block";
    } else {
        searchBox.style.display = "none";
    }
});

// Event listener untuk input pencarian
searchInput.addEventListener("input", () => {
    const query = searchInput.value.toLowerCase().trim();
    const menuSection = document.getElementById("menu");
    let foundItem = false; // Flag untuk mengecek apakah ada item yang cocok

    // Periksa setiap item di menu
    menuItems.forEach(item => {
        const name = item.getAttribute("data-name").toLowerCase();
        if (name.includes(query)) {
            item.style.display = "block"; // Tampilkan item jika cocok
            foundItem = true; // Tandai jika ditemukan item yang cocok
        } else {
            item.style.display = "none"; // Sembunyikan item jika tidak cocok
        }
    });

    // Scroll ke menu hanya jika ada item yang cocok
    if (foundItem && query !== "") {
        menuSection.scrollIntoView({ behavior: "smooth" });
    }
});

const header = document.querySelector("header");

window.addEventListener("scroll", () => {
    header.classList.toggle("shadow", window.scrollY > 0)
});

// Get the cart modal and cart icon
const cartIcon = document.querySelector('.bx-cart-alt');
const cartModal = document.getElementById('cart-modal');

// Function to toggle the modal visibility
cartIcon.addEventListener('click', () => {
    cartModal.classList.toggle('active'); // Toggle the 'active' class to show/hide the modal
});


document.addEventListener('DOMContentLoaded', () => {
let cart = [];

function updateCartUI() {
    console.log('Updating cart UI...');
    const cartItemsContainer = document.getElementById('cart-items');
    const totalCartSpan = document.getElementById('totalCart');

    cartItemsContainer.innerHTML = '';

    cart.forEach((item, index) => {
        const cartItem = document.createElement('li');
        cartItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
        cartItem.innerHTML = `
            <div class="d-flex align-items-center">
                <img src="${item.img}" class="rounded me-3" alt="${item.name}" style="width: 50px; height: 50px;">
                <div>
                    <h6 class="mb-0">${item.name}</h6>
                    <small>Price: ${item.price}</small>
                </div>
            </div>
            <div>
                <input type="number" class="form-control form-control-sm text-center" style="width: 60px;" value="${item.quantity}" min="1" onchange="updateQuantity(${index}, this.value)">
                <button class="btn btn-danger btn-sm mt-2" onclick="removeFromCart(${index})">Remove</button>
            </div>
        `;
        cartItemsContainer.appendChild(cartItem);
    });

    totalCartSpan.textContent = cart.reduce((total, item) => total + item.quantity, 0);
}

function addToCart(name, price, img) {
    console.log(`Adding to cart: ${name}, ${price}, ${img}`);
    const existingItemIndex = cart.findIndex(item => item.name === name);

    if (existingItemIndex !== -1) {
        cart[existingItemIndex].quantity++;
    } else {
        cart.push({ name, price, img, quantity: 1 });
    }

    console.log(cart);
    updateCartUI();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartUI();
}

function updateQuantity(index, quantity) {
    if (quantity <= 0) return;
    cart[index].quantity = parseInt(quantity, 10);
    updateCartUI();
}

document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
        console.log('Button clicked!');
        const name = button.dataset.name;
        const price = button.dataset.price;
        const img = button.dataset.img;

        console.log(`Name: ${name}, Price: ${price}, Img: ${img}`);
        addToCart(name, price, img);
    });
});
});
