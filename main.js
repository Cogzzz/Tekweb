// main.js
const searchIcon = document.getElementById("search-icon");
const searchBox = document.querySelector(".search-box");
const searchInput = document.querySelector(".search-box input");
const menuItems = document.querySelectorAll(".products-container .box");
const logIn = document.querySelectorAll("header-actions .login");
const signIn = document.querySelectorAll("header-actions .signin");

// Dropdown toggle functionality
document.getElementById('dropdown-toggle').addEventListener('click', function () {
    const dropdownMenu = document.getElementById('dropdown-menu');
    dropdownMenu.classList.toggle('active');
});

// Close dropdown if clicked outside
document.addEventListener('click', function (event) {
    const dropdown = document.querySelector('.dropdown');
    const dropdownMenu = document.getElementById('dropdown-menu');

    if (!dropdown.contains(event.target)) {
        dropdownMenu.classList.remove('active');
    }
});

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

// Function to toggle the modal visibility
cartIcon.addEventListener('click', () => {
    cartModal.classList.toggle('active'); // Toggle the 'active' class to show/hide the modal
});


//FILTER

// Fungsi untuk menyaring produk berdasarkan kategori
function filterProducts(category = '') {
    // Mendapatkan semua produk
    const products = document.querySelectorAll('.products-container .box');

    // Mendapatkan semua tombol filter
    const buttons = document.querySelectorAll('.filter-buttons .btn');

    // Menambahkan kelas 'active' pada tombol yang diklik dan menghapusnya dari tombol lainnya
    buttons.forEach(button => {
        if (button.textContent.toLowerCase() === category || category === '') {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    });

    // Menampilkan atau menyembunyikan produk berdasarkan kategori
    if (category === '') {
        products.forEach(product => {
            product.style.display = 'block';
        });
    } else {
        products.forEach(product => {
            if (product.getAttribute('data-category') === category) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    }
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



// Modal Product 

// Tangkap semua elemen produk
const productImages = document.querySelectorAll('.products-container .box img');

// Tangkap elemen modal
const productModal = new bootstrap.Modal(document.getElementById('productModal'));
const modalTitle = document.getElementById('productModalLabel');
const modalImage = document.getElementById('modal-img');
const modalPrice = document.getElementById('modal-price');
const modalDescription = document.getElementById('modal-description');

// Tambahkan event listener pada setiap gambar produk
productImages.forEach((image) => {
    image.addEventListener('click', (event) => {
        // Ambil elemen box dari produk terkait
        const productBox = event.target.closest('.box');

        // Ambil data dari elemen produk
        const productName = productBox.getAttribute('data-name');
        const productPrice = productBox.querySelector('.content span').innerText;
        const productImage = productBox.querySelector('img').getAttribute('src');

        // Isi modal dengan data produk
        modalTitle.textContent = productName;
        modalImage.src = productImage;
        modalPrice.textContent = productPrice;
        modalDescription.textContent = `Discover the unique taste of ${productName}!`;

        // Tampilkan modal
        productModal.show();
    });
});

//CUSTOMERS TESTIMONIALS
document.getElementById('testimonialForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Ambil data dari form
    const name = document.getElementById('name').value;
    const message = document.getElementById('message').value;
    const stars = document.getElementById('stars').value;

    // Buat elemen baru untuk testimoni
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
