// main.js
const searchIcon = document.getElementById("search-icon");
const searchBox = document.querySelector(".search-box");
const searchInput = document.querySelector(".search-box input");
const menuItems = document.querySelectorAll(".products-container .box");
const logIn = document.querySelectorAll("header-actions .login");
const signIn = document.querySelectorAll("header-actions .signin");


//DROP DOWN USER
document.addEventListener("DOMContentLoaded", function () {
    const dropdownToggle = document.getElementById("dropdown-toggle");
    const dropdownMenu = document.getElementById("dropdown-menu");

    dropdownToggle.addEventListener("click", function (e) {
        e.stopPropagation(); // Mencegah event bubbling ke elemen lain
        dropdownMenu.classList.toggle("active");
    });

    // Menutup dropdown jika klik di luar menu
    document.addEventListener("click", function (e) {
        if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove("active");
        }
    });
});


// SEARCH
searchIcon.addEventListener("click", () => {
    // Toggle tampilan search box
    if (searchBox.style.display === "none" || searchBox.style.display === "") {
        searchBox.style.display = "block";
    } else {
        searchBox.style.display = "none";
    }
});

// SEARCH INPUT
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

window.onscroll = () => {
    if(window.scrollY > 60){
        document.querySelector('.header').classList.add('active');
    }else{
        document.querySelector('.header').classList.remove('active');
    }
}

// Get the cart modal and cart icon

const cartIcon = document.querySelector('.bx-cart-alt');
const cartModal = document.getElementById('cart-modal');

// Menampilkan modal cart
cartIcon.addEventListener('click', () => {
    cartModal.classList.toggle('active'); 
});


//FILTER
// Fungsi untuk memfilter produk berdasarkan kategori
function filterProducts(category) {
    // Ambil semua elemen produk di dalam container
    const products = document.querySelectorAll('.products-container .box');

    products.forEach(product => {
        const productCategory = product.getAttribute('data-category'); // Ambil data-category
        
        if (!category || category === 'All' || productCategory === category) {
            product.style.display = "block"; // Tampilkan produk
        } else {
            product.style.display = "none"; // Sembunyikan produk
        }
    });
}

// // CART 
// let cart = [];

// function saveCartToLocalStorage() {
//     localStorage.setItem('cart', JSON.stringify(cart));
// }

// // Fungsi untuk memuat data keranjang dari localStorage
// function loadCartFromLocalStorage() {
//     const savedCart = localStorage.getItem('cart');
//     if (savedCart) {
//         cart = JSON.parse(savedCart);
//     }
// }

// // Fungsi untuk memperbarui UI keranjang
// function updateCartUI() {
//     console.log('Updating cart UI...');
//     const cartItemsContainer = document.getElementById('cart-items');
//     const totalCartSpan = document.getElementById('totalCart');
// memuat data keranjang dari localStorage
function loadCartFromLocalStorage() {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
    }
}

// memperbarui UI keranjang
function updateCartUI() {
    console.log('Updating cart UI...');
    const cartItemsContainer = document.getElementById('cart-items');
    const totalCartSpan = document.getElementById('totalCart');

//     cartItemsContainer.innerHTML = '';

//     cart.forEach((item, index) => {
//         const cartItem = document.createElement('li');
//         cartItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
//         cartItem.innerHTML = `
//             <div class="d-flex align-items-center">
//                 <img src="${item.img}" class="rounded me-3" alt="${item.name}" style="width: 50px; height: 50px;">
//                 <div>
//                     <h6 class="mb-0">${item.name}</h6>
//                     <small>Price: ${item.price}</small>
//                 </div>
//             </div>
//             <div>
//                 <button class="btn btn-sm btn-outline-secondary minus" onclick="updateQuantity(${index}, 'minus')">-</button>
//                 <span class="quantity">${item.quantity}</span>
//                 <button class="btn btn-sm btn-outline-secondary plus" onclick="updateQuantity(${index}, 'plus')">+</button>
//                 <button class="btn btn-danger btn-sm mt-2" onclick="removeFromCart(${index})">Remove</button>
//             </div>
//         `;
//         cartItemsContainer.appendChild(cartItem);
//     });
//     // totalCartSpan.textContent = cart.reduce((total, item) => total + item.quantity, 0);
//     // saveCartToLocalStorage(); // Simpan ke localStorage setiap kali keranjang diperbarui
// }

// // Fungsi untuk menyimpan data keranjang ke localStorage
// function saveCartToLocalStorage() {
//     localStorage.setItem('cart', JSON.stringify(cart));
// }

// // Fungsi untuk menambahkan item ke keranjang
// function addToCart(name, price, img) {
//     console.log(`Adding to cart: ${name}, ${price}, ${img}`);
//     const existingItemIndex = cart.findIndex(item => item.name === name);

//     if (existingItemIndex !== -1) {
//         cart[existingItemIndex].quantity++;
//     } else {
//         cart.push({ name, price, img, quantity: 1 });
//     }

//     console.log(cart);
//     saveToHistory(existingItemIndex); // Panggil fungsi
//     updateCartUI();
// }

// // Fungsi untuk menghapus item dari keranjang
// function removeFromCart(index) {
//     console.log('Removing item at index:', index); // Untuk debugging
//     cart.splice(index, 1);
//     updateCartUI();
// }

// // Fungsi untuk memperbarui jumlah item dalam keranjang
// function updateQuantity(index, action) {
//     const quantity = parseInt(cart[index].quantity, 10) || 0; // Pastikan nilai valid

//     if (action === 'plus') {
//         cart[index].quantity = quantity + 1;
//     } else if (action === 'minus') {
//         cart[index].quantity = Math.max(quantity - 1, 1); // Minimal 1
//     }
    console.log(cart);
    saveToHistory(existingItemIndex);
    updateCartUI();
}

// Fungsi untuk menghapus item dari keranjang
function removeFromCart(index) {
    console.log('Removing item at index:', index); 
    cart.splice(index, 1);
    updateCartUI();
}

// Fungsi untuk memperbarui jumlah item dalam keranjang
// function updateQuantity(index, action) {
//     const quantity = parseInt(cart[index].quantity, 10) || 0;

//     if (action === 'plus') {
//         cart[index].quantity = quantity + 1;
//     } else if (action === 'minus') {
//         cart[index].quantity = Math.max(quantity - 1, 1);
//     }

//     updateCartUI();
// }

// // Event listener untuk menambahkan item ke keranjang saat tombol add-to-cart ditekan
// // document.querySelectorAll('.add-to-cart').forEach(button => {
// //     button.addEventListener('click', () => {
// //         console.log('Button clicked!');
// //         const name = button.dataset.name;
// //         const price = button.dataset.price;
// //         const img = button.dataset.img;

// //         console.log(`Name: ${name}, Price: ${price}, Img: ${img}`);
// //         addToCart(name, price, img);
// //     });
// // });

// // Memuat keranjang dan memperbarui UI setelah halaman dimuat
// document.addEventListener('DOMContentLoaded', () => {
//     loadCartFromLocalStorage();
//     updateCartUI();
// });


//CUSTOMERS TESTIMONIALS
document.getElementById('testimonialForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Ambil data dari form
    const name = document.getElementById('name').value;
    const message = document.getElementById('message').value;
    const stars = document.getElementById('stars').value;

    const testimonialBox = document.createElement('div');
    testimonialBox.className = 'box d-inline-block';
    testimonialBox.style.minWidth = '300px';
    testimonialBox.innerHTML = `
        <div class="stars">${'<i class="bx bxs-star"></i>'.repeat(stars)}</div>
        <p>${message}</p>
        <h2>${name}</h2>
        <img src="asset/rev2.png" alt="User Image">
    `;

    // Tambahkan testimoni ke container
    document.querySelector('.customers-container').appendChild(testimonialBox);

    // Reset form dan tutup modal
    document.getElementById('testimonialForm').reset();
    const modal = bootstrap.Modal.getInstance(document.getElementById('testimonialModal'));
    modal.hide();
    // customerElement.remove(); // Menghapus elemen dari DOM
});


// Fungsi untuk menyimpan riwayat transaksi ke localStorage tanpa duplikasi
function saveToHistory(newTransaction) {
    let history = JSON.parse(localStorage.getItem('purchaseHistory')) || [];

    // Cek apakah transaksi sudah ada berdasarkan orderNumber
    const isDuplicate = history.some(transaction => transaction.orderNumber === newTransaction.orderNumber);

    if (!isDuplicate) {
        history.push(newTransaction); // Tambahkan hanya jika tidak duplikat
        localStorage.setItem('purchaseHistory', JSON.stringify(history));
    }
}
