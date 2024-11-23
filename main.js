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

// Optionally, close the modal when clicking outside
window.addEventListener('click', (e) => {
    if (!cartModal.contains(e.target) && !cartIcon.contains(e.target)) {
        cartModal.classList.remove('active'); // Close modal if clicking outside
    }
});



// // Ambil elemen yang dibutuhkan
// const cartIcon = document.getElementById('cart-icon');
// const cartModal = document.getElementById('cart-modal');
// const cartList = document.getElementById('cart-list');
// const addToCartButtons = document.querySelectorAll('.add-to-cart'); // Tambahkan class ini ke tombol add

// // Array untuk menyimpan item di cart
// let cartItems = [];

// // Toggle modal saat ikon cart diklik
// cartIcon.addEventListener('click', () => {
//     cartModal.classList.toggle('active');
// });

// // Fungsi untuk menambahkan item ke cart
// function addToCart(itemName, itemPrice) {
//     // Tambahkan item ke array
//     cartItems.push({ name: itemName, price: itemPrice });
//     renderCart();
// }

// // Fungsi untuk memperbarui daftar cart di modal
// function renderCart() {
//     cartList.innerHTML = ''; // Kosongkan daftar sebelumnya
//     cartItems.forEach((item, index) => {
//         const li = document.createElement('li');
//         li.innerHTML = `
//             <span>${item.name}</span>
//             <span>$${item.price.toFixed(2)}</span>
//             <button onclick="removeFromCart(${index})">Remove</button>
//         `;
//         cartList.appendChild(li);
//     });
// }

// // Fungsi untuk menghapus item dari cart
// function removeFromCart(index) {
//     cartItems.splice(index, 1); // Hapus item dari array
//     renderCart();
// }

// // Tambahkan event listener ke tombol add-to-cart
// addToCartButtons.forEach((button) => {
//     button.addEventListener('click', () => {
//         const itemName = button.dataset.name; // Ambil nama item dari atribut data-name
//         const itemPrice = parseFloat(button.dataset.price); // Ambil harga item dari atribut data-price
//         addToCart(itemName, itemPrice);
//     });
// });


// Ambil elemen modal
const productModal = new bootstrap.Modal(document.getElementById('productModal'));
const modalImg = document.getElementById('modal-img');
const modalTitle = document.getElementById('productModalLabel');
const modalDescription = document.getElementById('modal-description');
const modalPrice = document.getElementById('modal-price');

// Event listener untuk gambar produk
const productImages = document.querySelectorAll('.products-container .box img');
productImages.forEach((image) => {
    image.addEventListener('click', () => {
        const box = image.closest('.box');
        const product = {
            image: image.src,
            title: box.dataset.name,
            description: `This is the description of ${box.dataset.name}.`,
            price: '25.000',
        };

        // Set data ke modal
        modalImg.src = product.image;
        modalTitle.textContent = product.title;
        modalDescription.textContent = product.description;
        modalPrice.textContent = `Rp.${product.price}`;

        // Tampilkan modal
        productModal.show();
    });
});
