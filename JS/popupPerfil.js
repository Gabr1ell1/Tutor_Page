 // POP UP DE PÁGINAS/PERFiL
 const openPopupBtn = document.getElementById('openPopup');
 const popup = document.getElementById('popup');
 const closePopupBtn = document.getElementById('.popup-close');
 const submenu = document.querySelector('.submenu');

 openPopupBtn.addEventListener('click', () => {
     popup.classList.add('show');
 });

 closePopupBtn.addEventListener('click', () => {
     popup.classList.remove('show');
 });

 dependentes.addEventListener('click', function() {
    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
});

window.addEventListener('click', (event) => {
     if (event.target !== popup && !popup.contains(event.target) && event.target !== openPopupBtn) {
         popup.classList.remove('show');
     }
 });