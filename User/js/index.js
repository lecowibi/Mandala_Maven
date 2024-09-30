function toggle() {
    var cartList = document.querySelector('.dropdown2 ul');
    if (cartList.style.display === 'none' || cartList.style.display === '') {
        cartList.style.display = 'block';
    } else {
        cartList.style.display = 'none';
    }
};

