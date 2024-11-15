// Selecionar os elementos
const openPopupBtn = document.getElementById('openPopup');
const popup = document.getElementById('popup');
const closePopupBtn = document.getElementById('closePopup');

// Abrir o pop-up
openPopupBtn.addEventListener('click', () => {
    popup.classList.add('show');
});

// Fechar o pop-up ao clicar no botão de fechar
closePopupBtn.addEventListener('click', closePopup);

// Função para fechar o pop-up
function closePopup() {
    popup.classList.remove('show');
}

// Fechar o pop-up ao clicar fora dele
window.addEventListener('click', (event) => {
    if (!popup.contains(event.target) && event.target !== openPopupBtn) {
        popup.classList.remove('show');
    }
});
